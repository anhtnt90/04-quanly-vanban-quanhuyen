<?php
/**
 * Nguoi tao: phuongtt
 * Ngay tao: 11/09/2010
 * Y nghia: Class Xu ly LAP CONG VIEC dien tu
 */	
class work_creatingController extends  Zend_Controller_Action {
	//Bien public luu quyen
	public $_publicPermission;
	public function init(){
		//Load cau hinh thu muc trong file config.ini
        $tempDirApp = Zend_Registry::get('conDirApp');
		$this->_dirApp = $tempDirApp->toArray();
		$this->view->dirApp = $tempDirApp->toArray();
		//Cau hinh cho Zend_layout
		Zend_Layout::startMvc(array(
			    'layoutPath' => $this->_dirApp['layout'],
			    'layout' => 'index'			    
			    ));	
		//Load ca thanh phan cau vao trang layout (index.phtml)
		$response = $this->getResponse();
		//Lay cac hang so su dung trong JS public
		Zend_Loader::loadClass('Sys_Init_Config');
		$objConfig = new Sys_Init_Config();
		$this->view->UrlAjax = $objConfig->_setUrlAjax();	
		
		//Load cau hinh thu muc trong file config.ini de lay ca hang so dung chung
        $tempConstPublic = Zend_Registry::get('ConstPublic');
		$this->_ConstPublic = $tempConstPublic->toArray();
		
		//Lay so dong tren man hinh danh sach
		$this->view->NumberRowOnPage 	= $this->_ConstPublic['NumberRowOnPage'];		
		
		//Ky tu dac biet phan tach giua cac phan tu
		$this->view->delimitor 			= $this->_ConstPublic['delimitor'];
		
		//Lay duong dan thu muc goc (path directory root)
		$this->view->baseUrl = $this->_request->getBaseUrl() . "/public/";	
				//Goi lop Listxml_modList
		Zend_Loader::loadClass('web_modWebMenu');
		//Lay tat ca cac chuyen muc
		$objWebMenu = new web_modWebMenu();
		$arrResul = $objWebMenu->WebMenuGetAll('4',$_SESSION['OWNER_CODE'],'3','1');
		$this->view->arrMenu = $arrResul;	
		$sliidvisit = $this->_request->getParam('sliid','');
		if ($sliidvisit == "" || is_null($sliidvisit) || !isset($sliidvisit)){
			$sliidvisit = Sys_Library::_getCookie("headervisit");
		}else{
			Sys_Library::_createCookie("headervisit",$sliidvisit);
		}
		$this->view->sliidvisit = $sliidvisit;	
		$sleftmenu = $this->_request->getParam('sleftmenu','');
		$this->view->sleftmenu = $sleftmenu;	
	
		//Goi lop modSendReceived
		Zend_Loader::loadClass('work_modWork');
		
		
		$this->view->JSPublicConst = $objConfig->_setJavaScriptPublicVariable();		
		
		// Load tat ca cac file Js va Css
		$this->view->LoadAllFileJsCss = Sys_Publib_Library::_getAllFileJavaScriptCss('','sys-js','Work.js,js_calendar.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','sys-js','ajax.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','sys-js','jquery-1.4.2.min.js,jQuery.equalHeights.js',',','js'). Sys_Publib_Library::_getAllFileJavaScriptCss('','sys-js/LibSearch','actb_search.js,common_search.js',',','js');

		//Lay tra tri trong Cookie
		$sGetValueInCookie = Sys_Library::_getCookie("showHideMenu");
		
		//Neu chua ton tai thi khoi tao
		if ($sGetValueInCookie == "" || is_null($sGetValueInCookie) || !isset($sGetValueInCookie)){
			Sys_Library::_createCookie("showHideMenu",1);
			Sys_Library::_createCookie("ImageUrlPath",$this->_request->getBaseUrl() . "/public/images/close_left_menu.gif");
			//Mac dinh hien thi menu trai
			$this->view->hideDisplayMeneLeft = 1;// = 1 : hien thi menu
			//Hien thi anh dong menu trai
			$this->view->ShowHideimageUrlPath = $this->_request->getBaseUrl() . "/public/images/close_left_menu.gif";
		}else{//Da ton tai Cookie
			/*
			Lay gia tri trong Cookie, neu gia tri trong Cookie = 1 thi hien thi menu, truong hop = 0 thi an menu di
			*/
			if ($sGetValueInCookie != 0){
				$this->view->hideDisplayMeneLeft = 1;// = 1 : hien thi menu
			}else{
				$this->view->hideDisplayMeneLeft = "";// = "" : an menu
			}
			//Lay dia chi anh trong Cookie
			$this->view->ShowHideimageUrlPath = Sys_Library::_getCookie("ImageUrlPath");
		}

		
		// Ham lay thong tin nguoi dang nhap hien thi tai Lefmenu
		$this->view->InforStaff = Sys_Publib_Library::_InforStaff();
		
		//Dinh nghia current modul code
		$this->view->currentModulCode = "WORK";
		//Lay Quyen cap nhat VB dien tu
		//$this->_publicPermission = Sys_Function_DocFunctions::Doc_ArchivesStaff($_SESSION['arrStaffPermission'],$_SESSION['staff_id']);
		//echo $this->_publicPermission;
		$psshowModalDialog = $this->_request->getParam('showModalDialog',"");
		$this->view->currentModulCodeForLeft = 'CREATING-WORK';
		$sStatusLeftMenu = $this->_request->getParam('modul','');		
		if($sStatusLeftMenu == '')
			$sStatusLeftMenu = $this->_request->getParam('hdn_left_status','');
		$this->view->getStatusLeftMenu = $sStatusLeftMenu;	
		$this->view->showModelDialog = $psshowModalDialog;
		if ($psshowModalDialog != 1){
			//Hien thi file template
			$response->insert('header', $this->view->renderLayout('header.phtml','./application/views/scripts/'));    //Hien thi header 
			$response->insert('left', $this->view->renderLayout('left.phtml','./application/views/scripts/'));    //Hien thi header 		    
	        $response->insert('footer', $this->view->renderLayout('footer.phtml','./application/views/scripts/'));  	 //Hien thi footer
		}
		//echo $psshowModalDialog; exit;
  	}	
	/**
	 * Idea : Phuong thuc hien thi danh sach
	 *
	 */
	public function indexAction(){
		$pUrl = $_SERVER['REQUEST_URI'];
		// Tieu de tim kiem
		$this->view->bodyTitleSearch = "DANH SÁCH CÔNG VIỆC";				
		// Tieu de man hinh danh sach
		$this->view->bodyTitle = "DANH SÁCH CÔNG VIỆC";
		$sleftStatus   	=$this->_request->getParam('modul','');	
		//Bat dau lay vet tim kiem tu session
		$iLeaderId = $this->_request->getParam('C_LEADER','');
		$sStatus   = $this->_request->getParam('C_STATUS','');
		$sfromDate = $this->_request->getParam('txtfromDate','');
		$stoDate = $this->_request->getParam('txttoDate','');
		$sfullTextSearch = trim($this->_request->getParam('txtfullTextSearch',''));
		$iCurrentPage 	= $this->_request->getParam('hdn_current_page',0);
		$iNumRowOnPage 	= $this->_request->getParam('hdn_record_number_page',0);
		// Tao doi tuong Zend_Filter
		$objFilter = new Zend_Filter();
		$ojbSysLib = new Sys_Library();
		$objDocFun = new Sys_Function_DocFunctions();
		$ojbSysInitConfig = new Sys_Init_Config();
		$objWork = new work_modWork();
		//
		if($sfromDate == '')
			$sfromDate = '1/1/'.date("Y");
		if($stoDate == '')
			$stoDate = date("d/m/Y");
		if($iCurrentPage < 1)
			$iCurrentPage = 1;
		if($iNumRowOnPage == 0)
			$iNumRowOnPage = 15;
		//Neu ton tai gia tri tim kiem tron session thi lay trong session
		if(isset($_SESSION['seArrParameter'])){
			$Parameter 			= $_SESSION['seArrParameter'];
			$iLeaderId          = $Parameter['lanhDaoGiaoViec'];
			$sStatus          	= $Parameter['trangThai'];
			$sfullTextSearch	= $Parameter['chuoiTimKiem'];
			$sfromDate			= $Parameter['tuNgay'];
			$stoDate			= $Parameter['denNgay'];
			$iCurrentPage		= $Parameter['trangHienThoi'];
			$iNumRowOnPage		= $Parameter['soBanGhiTrenTrang'];
			unset($_SESSION['seArrParameter']);
		}
		if($sStatus == '')
			$sStatus = 'CAN_XU_LY';
		//Day cac gia tri tim kiem ra view
		if($sleftStatus == 'DA_XU_LY')
				$sStatus = 'DA_XU_LY';
		$this->view->iLeaderId      	= $iLeaderId;
		$this->view->sStatus 			= $sStatus;
		$this->view->sFullTextSearch 	= $sfullTextSearch;
		$this->view->fromDate 			= $sfromDate;
		$this->view->toDate				= $stoDate;
		$this->view->iCurrentPage 		= $iCurrentPage;
		$this->view->iNumRowOnPage 		= $iNumRowOnPage;
		
		//Lay cac hang so dung chung
		$arrCount = $ojbSysInitConfig->_setProjectPublicConst();
		$this->view->arrCount = $arrCount;
		
		// Lay toan bo tham so truyen tu form			
		$arrInput = $this->_request->getParams();

		/*
		$iOwnerId = $_SESSION['OWNER_ID'];
		if($_SESSION['OWNER_ID'] != Sys_Init_Config::_setParentOwnerId())
				$arrLeader = Sys_Function_DocFunctions::docGetAllUnitLeader('LANH_DAO_PHUONG','arr_all_staff');
		else 	$arrLeader = Sys_Function_DocFunctions::docGetAllUnitLeader('LANH_DAO_UB','arr_all_staff');
		*/
		$iOwnerId = $_SESSION['OWNER_ID'];
		$arrPositionConst =	$ojbSysInitConfig->_setLeaderPostionGroup();
		$arrLeader = $objDocFun->docGetAllUnitLeader($arrPositionConst['_CONST_POSITION_GROUP'],'arr_all_staff');
		$this->view->arrLeader = $arrLeader;
		
		$this->view->search_textselectbox_leader = Sys_Function_DocFunctions::doc_search_ajax($arrLeader,"id","name","C_LEADER","hdn_leader_id",1,'position_code',1);
		$iStaffId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
		$iLeaderId = Sys_Function_DocFunctions::convertStaffNameToStaffId($iLeaderId,'');
		$arrPermission = $_SESSION['arrStaffPermission'];
		if($arrPermission['TOI_CAO_CONGVIEC'])
			$iStaffId = '';
		$arrResul = $objWork->DocDocWorkGetAll($iOwnerId, $iStaffId, $iLeaderId, $sStatus, Sys_Library::_ddmmyyyyToYYyymmdd($sfromDate),Sys_Library::_ddmmyyyyToYYyymmdd($stoDate), $sfullTextSearch, $iCurrentPage, $iNumRowOnPage);			
		$iNumberRecord = $arrResul[0]['C_TOTAL_RECORD'];	
		$sdocpertotal ="Danh sách này không có công việc nào";
		//Hien thi thong tin man hinh danh sach nay co bao nhieu ban ghi va hien thi Radio "Chon tat ca"; "Bo chon tat ca"
		$this->view->SelectDeselectAll = Sys_Publib_Library::_selectDeselectAll(sizeof($arrResul), $iNumberRecord);
		if (count($arrResul) > 0){
			$this->view->sdocpertotal = "Danh sách có: ".sizeof($arrResul).'/'.$iNumberRecord." công việc";
			//Sinh xau HTML mo ta so trang (Trang 1; Trang 2;...)
			$this->view->generateStringNumberPage = Sys_Publib_Library::_generateStringNumberPage($iNumberRecord, $iCurrentPage, $iNumRowOnPage,$pUrl) ;
			//Sinh chuoi HTML mo ta tong so trang (Trang 1; Trang 2;...) va quy dinh so record/page
			$this->view->generateHtmlSelectBoxPage = Sys_Publib_Library::_generateChangeRecordNumberPage($iNumRowOnPage,"index");
		}
		$this->view->arrResul = $arrResul;
		$this->view->NumberRowOnPage 	= $this->_ConstPublic['NumberRowOnPage'];	
		$this->view->iCountElement = count($this->view->arrAllRecord);
	}
	/**
	 * Idea : Phuong thuc them moi mot VB
	 *
	 */
	public function addAction(){
		$this->view->bodyTitle = 'TẠO LẬP CÔNG VIỆC';
		$arrInput = $this->_request->getParams();
		$objDocFun = new Sys_Function_DocFunctions();
		$objWork = new work_modWork();
		$ojbXmlLib = new Sys_Publib_Xml();
		$ojbSysLib = new Sys_Library();
		$objFilter = new Zend_Filter();
		$ojbSysInitConfig = new Sys_Init_Config();	
		
		//Lay ID cua NSD dang nhap hien thoi
		$StaffId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
		//Lay ID phong ban cua NSD dang nhap hien thoi
		$piUnitId = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');	
		//Lay TEN cua NSD dang nhap hien thoi
		$sStaffName = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'name');
		//Lay CHUC VU phong ban cua NSD dang nhap hien thoi
		$sStaffPosition = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'position_code');	
				
		//Luu thong tin tim kiem vao session
		if(!isset($_SESSION['seArrParameter'])){
			//Lay gia tri tim kiem tren form
			$iLeaderId          = $this->_request->getParam('C_LEADER','');
			$sStatus          	= $this->_request->getParam('C_STATUS','');
			$sfullTextSearch	= $this->_request->getParam('txtfullTextSearch','');
			$sfromDate			= $this->_request->getParam('txtfromDate','');
			$stoDate			= $this->_request->getParam('txttoDate','');
			$iCurrentPage		= $this->_request->getParam('hdn_current_page',0);
			if ($iCurrentPage <= 1){
				$iCurrentPage = 1;
			}
			$iNumRowOnPage = $this->_request->getParam('cbo_nuber_record_page',0);
			if ($iNumRowOnPage == 0)
				$iNumRowOnPage = 15;
			$arrParaSet = array("lanhDaoGiaoViec"=>$iLeaderId, "trangThai"=>$sStatus,"chuoiTimKiem"=>$sfullTextSearch,"tuNgay"=>$sfromDate,"denNgay"=>$stoDate,"trangHienThoi"=>$iCurrentPage,"soBanGhiTrenTrang"=>$iNumRowOnPage);
			$_SESSION['seArrParameter'] = $arrParaSet;	
		}
		
		$iOwnerId = $_SESSION['OWNER_ID'];
		/*
		if($_SESSION['OWNER_ID'] != Sys_Init_Config::_setParentOwnerId())
				$arrLeader = Sys_Function_DocFunctions::docGetAllUnitLeader('LANH_DAO_PHUONG','arr_all_staff');
		else 	$arrLeader = Sys_Function_DocFunctions::docGetAllUnitLeader('LANH_DAO_UB','arr_all_staff');
		*/
		$arrPositionConst =	$ojbSysInitConfig->_setLeaderPostionGroup();
		$arrLeader = $objDocFun->docGetAllUnitLeader($arrPositionConst['_CONST_POSITION_GROUP'],'arr_all_staff');
		
		$this->view->search_textselectbox_leader = Sys_Function_DocFunctions::doc_search_ajax($arrLeader,"id","name","C_LEADER","hdn_leader_id",1,'position_code',1);
		 //Lay thong tin history back
		$this->view->historyBack = '../index/';	
		//Lay tham so cau hinh
		$sysLibUrlPath = $ojbSysInitConfig->_setLibUrlPath();
		$url_path_calendar = $sysLibUrlPath . 'sys-calendar/';
		$this->view->urlCalendar = $url_path_calendar;
		//Lay cac hang so dung chung
		$arrCount = Sys_Init_Config::_setProjectPublicConst();
		$this->view->arrCount = $arrCount;
		
		//Goi ham thuc hien lay thong tin cho selectbox
		$this->view->search_textselectbox_staff = Sys_Function_DocFunctions::doc_search_ajax($_SESSION['arr_all_staff_keep'],"id","name","C_STAFF_NAME_LIST","hdn_staff_id_list",0,'position_code');
		$this->view->search_textselectbox_unit = Sys_Function_DocFunctions::doc_search_ajax($_SESSION['arr_all_unit_keep'],"id","name","C_UNIT_NAME_LIST","hdn_unit_id_list",0);

		//Lay thong tin file dinh kem
		$arrFileNameUpload = $ojbSysLib->_uploadFileList(10,$this->_request->getBaseUrl() . "/public/attach-file/",'FileName','!#~$|*');
		$arFileAttach = array();
		$this->view->AttachFile = $objDocFun->DocSentAttachFile($arFileAttach,sizeof($arFileAttach),10,true,43);	
		
		//Tuy chon ung voi cac truong hop update du lieu	
		$sOption = $this->_request->getParam('hdh_option','');
		$this->view->option = $sOption;
	
		//Lay danh sach ten PHONG BAN XU LY
		$sUnitNameList = $objFilter->filter($arrInput['C_UNIT_NAME_LIST']);
		//Lay danh sach Id PHONG BAN XU LY
		$sUnitIdList = $objDocFun->convertUnitNameListToUnitIdList($sUnitNameList);
		//Lay danh sach ten CAN BO XU LY 
		$sStaffNameList = $objFilter->filter($arrInput['C_STAFF_NAME_LIST']);
		//Lay danh sach Id CAN BO XU LY
		$sStaffIdList = $objDocFun->convertStaffNameToStaffId($sStaffNameList);
		$sUnitByStaffIdList = $objDocFun->doc_get_all_unit_permission_form_staffIdList($sStaffIdList);
		$sProcessStatusUnitList = '';
		if($sUnitIdList != ''){
			$arrUnitIdList = explode(',',$sUnitIdList);
			$arrUnitByStaffIdList = explode(',',$sUnitByStaffIdList);
			for($i = 0; $i < sizeof($arrUnitIdList) - 1; $i++){
				if(in_array($arrUnitIdList[$i],$arrUnitByStaffIdList))
						$sProcessStatusUnitList .= 'CAN_XU_LY;';
				else 	$sProcessStatusUnitList .= 'CHO_PHAN_CONG;';
			}
			if(in_array($arrUnitIdList[$i],$arrUnitByStaffIdList))
					$sProcessStatusUnitList .= 'CAN_XU_LY';
			else 	$sProcessStatusUnitList .= 'CHO_PHAN_CONG';	
		}
		if($sUnitIdList != '' Or $sStaffIdList != '')
				$sWorkStatus = 'CAN_XU_LY';
		else 	$sWorkStatus = 'CHO_PHAN_CONG';
		$iLeaderId = $objDocFun->convertStaffNameToStaffId($this->_request->getParam('C_LEADER',''));
		$arrParameter = array(	
								'PK_WORK_MANAGE'				=>'',	
								'FK_UNIT'						=>$_SESSION['OWNER_ID'],			
								'C_LEADER'						=>$iLeaderId,
								'C_LEADER_POSITION_NAME'		=>$this->_request->getParam('C_LEADER',''),
								'FK_CREATER'					=>$StaffId,
								'C_CREATER_POSITION_NAME'		=>$sStaffPosition.' - '.$sStaffName,
								'C_WORK_CONTENT'				=>$this->_request->getParam('C_WORK_CONTENT',''),
								'C_NOTES'						=>$this->_request->getParam('C_NOTES',''),					
								'C_APPROVE_DATE'				=>Sys_Library::_ddmmyyyyToYYyymmdd($this->_request->getParam('C_APPROVE_DATE','')),
								'C_WORK_STATUS'					=>$sWorkStatus,																	
								'C_APPOINTED_DATE'				=>Sys_Library::_ddmmyyyyToYYyymmdd($this->_request->getParam('C_APPOINTED_DATE','')),
								'C_STAFF_ID_LIST'				=>$sStaffIdList,
								'C_STAFF_POSITION_NAME_LIST'	=>str_replace(';','!#~$|*',$sStaffNameList),
								'C_UNIT_ID_BY_STAFF'			=>$sUnitByStaffIdList,
								'C_UNIT_ID_LIST'				=>$sUnitIdList,
								'C_UNIT_NAME_LIST'				=>str_replace(';','!#~$|*',$sUnitNameList),	
								'C_STATUS_BY_UNIT'				=>$sProcessStatusUnitList,
								'C_XML_DATA'					=>'',
								'NEW_FILE_ID_LIST'				=>$arrFileNameUpload,
							);	
		//Bien luu gia tri tra ve cua ham update ID cua van ban duoc THEM MOI hoac CHINH SUA
		$arrResult = "";
		if ($this->_request->getParam('C_WORK_CONTENT','') != ""){
			$arrResult = $objWork->DocWorkUpdate($arrParameter);
			//Truong hop ghi va them moi
			if ($sOption == "GHI_THEMMOI"){
				//Ghi va quay lai chinh form voi noi dung rong						
				$this->_redirect('work/creating/add/');
			}	
			if ($sOption == "GHI_THEMTIEP"){
				$this->view->sWorkId = $arrResult['NEW_ID'];
				$this->view->option = $sOption;
				//Them van ban moi va giu lai noi dung thong tin tren form					
				$this->_redirect('work/creating/edit/hdn_object_id/' . $arrResult['NEW_ID']);
			}
			if ($sOption == "GHI_TAM"){
				$this->view->sWorkId = $arrResult['NEW_ID'];
				$this->view->option = $sOption;
				//Ghi va quay lai chinh form voi noi dung rong						
				$this->_redirect('work/creating/edit/hdn_object_id/' . $arrResult['NEW_ID']);
			}
			if ($sOption == "GHI_QUAYLAI"){				
				$this->_redirect('work/creating/index/modul/CAN_XU_LY');	
			}	
		}					
	}
	public function editAction(){
		$this->view->bodyTitle = 'TẠO LẬP CÔNG VIỆC';
		$objDocFun = new Sys_Function_DocFunctions();
		$objWork   = new work_modWork();
		$ojbXmlLib = new Sys_Xml();
		$ojbSysLib = new Sys_Library();
		$objFilter = new Zend_Filter();
		$ojbSysInitConfig = new Sys_Init_Config();	
		
		//Lay thong tin nguoi dang nhap hien thoi
		$StaffId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
		$sStaffName = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'name');
		$sStaffPosition = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'position_code');		
		
		//Luu thong tin tim kiem vao session
		if(!isset($_SESSION['seArrParameter'])){
			//Lay gia tri tim kiem tren form
			$iLeaderId          = $this->_request->getParam('C_LEADER','');
			$sStatus          	= $this->_request->getParam('C_STATUS','');
			$sfullTextSearch	= $this->_request->getParam('txtfullTextSearch','');
			$sfromDate			= $this->_request->getParam('txtfromDate','');
			$stoDate			= $this->_request->getParam('txttoDate','');
			$iCurrentPage		= $this->_request->getParam('hdn_current_page',0);
			if ($iCurrentPage <= 1){
				$iCurrentPage = 1;
			}
			$iNumRowOnPage = $this->_request->getParam('cbo_nuber_record_page',0);
			if ($iNumRowOnPage == 0)
				$iNumRowOnPage = 15;
			$arrParaSet = array("lanhDaoGiaoViec"=>$iLeaderId, "trangThai"=>$sStatus,"chuoiTimKiem"=>$sfullTextSearch,"tuNgay"=>$sfromDate,"denNgay"=>$stoDate,"trangHienThoi"=>$iCurrentPage,"soBanGhiTrenTrang"=>$iNumRowOnPage);
			$_SESSION['seArrParameter'] = $arrParaSet;	
		}
		
		//Lay cac hang so dung chung
		$arrCount = Sys_Init_Config::_setProjectPublicConst();
		$this->view->arrCount = $arrCount;
		$this->view->historyBack = '../index/';	
		
		//Lay tham so cau hinh
		$sysLibUrlPath = $ojbSysInitConfig->_setLibUrlPath();
		$url_path_calendar = $sysLibUrlPath . 'sys-calendar/';
		$this->view->urlCalendar = $url_path_calendar;
		
		//Goi ham thuc hien lay thong tin cho selectbox
		/*
		if($_SESSION['OWNER_ID'] != Sys_Init_Config::_setParentOwnerId())
				$arrLeader = Sys_Function_DocFunctions::docGetAllUnitLeader('LANH_DAO_PHUONG','arr_all_staff');
		else 	$arrLeader = Sys_Function_DocFunctions::docGetAllUnitLeader('LANH_DAO_UB','arr_all_staff');
		*/
		
		$arrPositionConst =	$ojbSysInitConfig->_setLeaderPostionGroup();
		$arrLeader = $objDocFun->docGetAllUnitLeader($arrPositionConst['_CONST_POSITION_GROUP'],'arr_all_staff');
		
		$this->view->search_textselectbox_leader = Sys_Function_DocFunctions::doc_search_ajax($arrLeader,"id","name","C_LEADER","hdn_leader_id",1,'position_code',1);
		$this->view->search_textselectbox_staff = Sys_Function_DocFunctions::doc_search_ajax($_SESSION['arr_all_staff_keep'],"id","name","C_STAFF_NAME_LIST","hdn_staff_id_list",0,'position_code');
		$this->view->search_textselectbox_unit = Sys_Function_DocFunctions::doc_search_ajax($_SESSION['arr_all_unit_keep'],"id","name","C_UNIT_NAME_LIST","hdn_unit_id_list",0);
		
		$sWorkId = $this->_request->getParam('hdn_object_id','');
		$this->view->sWorkId = $sWorkId;
		
		//Tuy chon ung voi cac truong hop update du lieu	
		$sOption = $this->_request->getParam('hdh_option','');
		$this->view->option = $sOption;
		if ($sOption == "QUAY_LAI"){
			$this->_redirect('work/creating/index/modul/CAN_XU_LY');
		}
		
		//Lay thong tin file dinh kem
		$arrFileNameUpload = $ojbSysLib->_uploadFileList(10,$this->_request->getBaseUrl() . "/public/attach-file/",'FileName','!#~$|*');
		$sWorkIdTemp = $sWorkId;
		//Khong lay thong tin file dinh kem trong truong hop ghi va them tiep
		if($sOption == "GHI_THEMTIEP"){
			$sWorkIdTemp = "";
		}	
		//Lay file da dinh kem tu truoc
		if($sOption != "GHI_TAM"){
			$arFileAttach = $objWork->DOC_GetAllDocumentFileAttach($sWorkIdTemp,'','T_DOC_WORK_MANAGE');	
			$this->view->AttachFile = $objDocFun->DocSentAttachFile($arFileAttach,sizeof($arFileAttach),10,true,45);	
		}
		//Mang luu thong tin chi tiet cua mot cong viec
		$arrWork = $objWork->DocWorkGetSingle($sWorkId);
		$this->view->arrWork = $arrWork;
		if($sWorkId != '' && $sWorkId != null  && $sOption != "QUAY_LAI"){
				//Neu la ghi va them tiep thi gan ID VB lay duoc = "" de them moi mot VB
				if ($sOption == "GHI_THEMTIEP"){
					$sWorkId = "";
				}
				//Mang luu tham so update in database	
				//Lay danh sach thong tin phong ban
				$sUnitNameList = $this->_request->getParam('C_UNIT_NAME_LIST','');
				$sUnitIdList = $objDocFun->convertUnitNameListToUnitIdList($sUnitNameList);
				
				//Lay danh sach thong tin can bo 
				$sStaffNameList = $this->_request->getParam('C_STAFF_NAME_LIST','');
				$sStaffIdList = $objDocFun->convertStaffNameToStaffId($sStaffNameList);
				$sUnitByStaffIdList = $objDocFun->doc_get_all_unit_permission_form_staffIdList($sStaffIdList);
				
				$sProcessStatusUnitList = '';
				if($sUnitIdList != ''){
					$arrUnitIdList = explode(',',$sUnitIdList);
					$arrUnitByStaffIdList = explode(',',$sUnitByStaffIdList);
					for($i = 0; $i < sizeof($arrUnitIdList) - 1; $i++){
						if(in_array($arrUnitIdList[$i],$arrUnitByStaffIdList))
								$sProcessStatusUnitList .= 'CAN_XU_LY;';
						else 	$sProcessStatusUnitList .= 'CHO_PHAN_CONG;';
					}
					if(in_array($arrUnitIdList[$i],$arrUnitByStaffIdList))
							$sProcessStatusUnitList .= 'CAN_XU_LY';
					else 	$sProcessStatusUnitList .= 'CHO_PHAN_CONG';	
				}
				if($sUnitIdList != '' Or $sStaffIdList != '')
						$sWorkStatus = 'CAN_XU_LY';
				else 	$sWorkStatus = 'CHO_PHAN_CONG';
				
				$iLeaderId = $objDocFun->convertStaffNameToStaffId($this->_request->getParam('C_LEADER',''));
				$arrParameter = array(										
										'PK_WORK_MANAGE'				=>$sWorkId,	
										'FK_UNIT'						=>$_SESSION['OWNER_ID'],			
										'C_LEADER'						=>$iLeaderId,
										'C_LEADER_POSITION_NAME'		=>$this->_request->getParam('C_LEADER',''),
										'FK_CREATER'					=>$StaffId,
										'C_CREATER_POSITION_NAME'		=>$sStaffPosition.' - '.$sStaffName,
										'C_WORK_CONTENT'				=>$this->_request->getParam('C_WORK_CONTENT',''),
										'C_NOTES'						=>$this->_request->getParam('C_NOTES',''),					
										'C_APPROVE_DATE'				=>Sys_Library::_ddmmyyyyToYYyymmdd($this->_request->getParam('C_APPROVE_DATE','')),
										'C_WORK_STATUS'					=>$sWorkStatus,																	
										'C_APPOINTED_DATE'				=>Sys_Library::_ddmmyyyyToYYyymmdd($this->_request->getParam('C_APPOINTED_DATE','')),
										'C_STAFF_ID_LIST'				=>$sStaffIdList,
										'C_STAFF_POSITION_NAME_LIST'	=>str_replace(';','!#~$|*',$sStaffNameList),
										'C_UNIT_ID_BY_STAFF'			=>$sUnitByStaffIdList,
										'C_UNIT_ID_LIST'				=>$sUnitIdList,
										'C_UNIT_NAME_LIST'				=>str_replace(';','!#~$|*',$sUnitNameList),	
										'C_STATUS_BY_UNIT'				=>$sProcessStatusUnitList,
										'C_XML_DATA'					=>'',
										'NEW_FILE_ID_LIST'				=>$arrFileNameUpload,
									);
				$arrResult = "";
		}
		if ($this->_request->getParam('C_WORK_CONTENT','') != ""){
				$arrResult = $objWork->DocWorkUpdate($arrParameter);
		
				if ($sOption == "GHI_THEMMOI"){
					//Ghi va quay lai chinh form voi noi dung rong		
					$this->_redirect('work/creating/add/');
				}	
				if ($sOption == "GHI_THEMTIEP"){
					$this->view->option = $sOption;
					//Lay ID VB vua moi insert vao DB
					$this->view->sWorkId = $arrResult['NEW_ID'];
					//Lay thong tin van ban vua them moi va hien thi ra view
					$arrWork = $objWork->DocWorkGetSingle($arrResult['NEW_ID']);
					$this->view->arrWork = $arrWork;
				}
				if ($sOption == "GHI_TAM"){
					//Lay ID VB vua moi insert vao DB
					$this->view->sWorkId = $arrResult['NEW_ID'];
					$this->view->option = $sOption;
					//Lay thong tin van ban vua them moi va hien thi ra view
					$arrWork = $objWork->DocWorkGetSingle($arrResult['NEW_ID']);
					$this->view->arrWork = $arrWork;
					//Lay file da dinh kem tu truoc
					$arFileAttach = $objWork->DOC_GetAllDocumentFileAttach($arrResult['NEW_ID'],'','T_DOC_WORK_MANAGE');	
					$this->view->AttachFile = $objDocFun->DocSentAttachFile($arFileAttach,sizeof($arFileAttach),10,true,45);
				}
				if ($sOption == "GHI_QUAYLAI"){				
					$this->_redirect('work/creating/index/modul/CAN_XU_LY');	
				}	
		}
	}
	public function deleteAction(){
		$objDocFun = new Sys_Function_DocFunctions();
		$objWork   = new work_modWork();
		$ojbSysInitConfig = new Sys_Init_Config();	
		$sWorkIdList = $this->_request->getParam('hdn_object_id_list','');
		$sRetError = $objWork->DocWorkDelete($sWorkIdList,1);
		if($sRetError != null || $sRetError != '' ){
			echo "<script type='text/javascript'>alert('$sRetError')</script>";
		}
		else 
			$this->_redirect('work/creating/index/modul/CAN_XU_LY');	

	}
	public function viewAction(){
		$objDocFun = new Sys_Function_DocFunctions();
		$objWork = new work_modWork();
		$ojbSysInitConfig = new Sys_Init_Config();	
		$this->view->bodyTitle = "CHI TIẾT CÔNG VIỆC";
		
		//Lay cac hang so dung chung
		$arrConst = Sys_Init_Config::_setProjectPublicConst();
		$this->view->arrConst = $arrConst;
		
		//Lay id van ban tu view
		$sWorkId = $this->_request->getParam('hdn_object_id','');
		$this->view->sWorkId = $sWorkId;
		
		//Mang luu thong tin chi tiet cua mot van ban
		$arrWork = $objWork->DocWorkGetSingle($sWorkId);
		$this->view->arrWork = $arrWork;
		//Luu thong tin tim kiem vao session
		if(!isset($_SESSION['seArrParameter'])){
			//Lay gia tri tim kiem tren form
			$iLeaderId          = $this->_request->getParam('C_LEADER','');
			$sStatus          	= $this->_request->getParam('C_STATUS','');
			$sfullTextSearch	= $this->_request->getParam('txtfullTextSearch','');
			$sfromDate			= $this->_request->getParam('txtfromDate','');
			$stoDate			= $this->_request->getParam('txttoDate','');
			$iCurrentPage		= $this->_request->getParam('hdn_current_page',0);
			if ($iCurrentPage <= 1){
				$iCurrentPage = 1;
			}
			$iNumRowOnPage = $this->_request->getParam('cbo_nuber_record_page',0);
			if ($iNumRowOnPage == 0)
				$iNumRowOnPage = 15;
			$arrParaSet = array("lanhDaoGiaoViec"=>$iLeaderId, "trangThai"=>$sStatus,"chuoiTimKiem"=>$sfullTextSearch,"tuNgay"=>$sfromDate,"denNgay"=>$stoDate,"trangHienThoi"=>$iCurrentPage,"soBanGhiTrenTrang"=>$iNumRowOnPage);
			$_SESSION['seArrParameter'] = $arrParaSet;	
		}
	}
	public function printAction(){
		$objDocFun = new Sys_Function_DocFunctions();
		$objWork = new work_modWork();
		$ojbSysInitConfig = new Sys_Init_Config();	

		$sWorkId = $this->_request->getParam('hdn_object_id','');
	
		//Mang luu thong tin chi tiet cua mot cong viec
		$arrWork = $objWork->DocWorkGetSingle($sWorkId);
		$this->view->sWorkId = $sWorkId;
		//Lay file dinh kem
		$strFileName 				= $arrWork[0]['C_FILE_NAME'];
		$sFile 						= Sys_Library::_getAllFileAttach($strFileName,"!#~$|*","!~!",$this->_request->getBaseUrl() . "/public/attach-file/");
		//Lay duong dan
		$path = $_SERVER['SCRIPT_FILENAME'];
		$path = substr($path, 0, -9);
		$my_report = str_replace("/", "\\", $path) . "rpt\\work\\work.rpt";
		// Tao doi tuong Crystal 9
		$COM_Object = "CrystalDesignRunTime.Application.9";		
		$crapp= new COM($COM_Object) or die("Unable to Create Object");
		$creport = $crapp->OpenReport($my_report, 1);		
		$creport->ReadRecords();
		
		// Truyen tham so vao
		$sstaffName = str_replace(';','; ',$arrWork[0]['C_STAFF_NAME_LIST']);
		$sStaffNameList = '';
		if(trim($arrWork[0]['C_STAFF_NAME_LIST']) != ''){
			$arrUnitNameList = explode(';',$arrWork[0]['C_STAFF_NAME_LIST']);
			for($i = 0; $i < sizeof($arrUnitNameList); $i++){
				$sUnit = explode(':',$arrUnitNameList[$i]);
				$sStaffNameList .=$sUnit['0'].'; ';
			}
		}
		$sStaffNameList = substr($sStaffNameList,0,-2);
		$sUnitNameList = '';
		if(trim($arrWork[0]['C_UNIT_NAME_LIST']) != ''){
			$arrUnitNameList = explode(';',$arrWork[0]['C_UNIT_NAME_LIST']);
			for($i = 0; $i < sizeof($arrUnitNameList); $i++){
				$sUnit = explode(':',$arrUnitNameList[$i]);
				$sUnitNameList .=$sUnit['0'].'; ';
			}
		}
		$sUnitNameList = substr($sUnitNameList,0,-2);
		$creport->ParameterFields(1)->SetCurrentValue($arrWork[0]['C_APPROVE_DATE']);
		$creport->ParameterFields(2)->SetCurrentValue((string)$arrWork[0]['C_LEADER_POSITION_NAME']);
		$creport->ParameterFields(3)->SetCurrentValue($arrWork[0]['C_APPOINTED_DATE']);
		$creport->ParameterFields(4)->SetCurrentValue((string)$arrWork[0]['C_WORK_CONTENT']);
		$creport->ParameterFields(5)->SetCurrentValue((string)$arrWork[0]['C_NOTES']);
		$creport->ParameterFields(6)->SetCurrentValue($sFile);
		$creport->ParameterFields(7)->SetCurrentValue((string)$sStaffNameList);
		$creport->ParameterFields(8)->SetCurrentValue((string)$sUnitNameList);
		
		//Ten file
		$report_file = 'work'. mt_rand(1,1000000) .'.doc';
		// Duong dan file report
		$my_report_file = str_replace("/", "\\", $path) . "public\\" . $report_file;
		//export to PDF process
		$creport->ExportOptions->DiskFileName=$my_report_file; //export to file 
		$creport->ExportOptions->PDFExportAllPages=true;
		$creport->ExportOptions->DestinationType = 1; // export to file
		$creport->ExportOptions->FormatType= 14;
		$creport->Export(false);
		$my_report_file = 'http://'.$_SERVER['HTTP_HOST'].$this->_request->getBaseUrl() .'/public/' . $report_file;
		$this->view->my_report_file = $my_report_file;
	}
}?>
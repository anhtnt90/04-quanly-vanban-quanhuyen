<?php
/**
 * Nguoi tao: phuongtt
 * Ngay tao: 11/09/2010
 * Y nghia: Class Xu ly LAP CONG VIEC dien tu
 */	
class work_processController extends  Zend_Controller_Action {
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
		$JSandStyle = Sys_Publib_Library::_getAllFileJavaScriptCss('','sys-js','Work.js,js_calendar.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','sys-js','ajax.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','sys-js','jquery-1.4.2.min.js,jQuery.equalHeights.js',',','js'). Sys_Publib_Library::_getAllFileJavaScriptCss('','sys-js/LibSearch','actb_search.js,common_search.js',',','js');
				
		$JSandStyle.= Sys_Publib_Library::_getAllFileJavaScriptCss('','sys-js','ui/i18n/jquery.ui.datepicker-vi.js',',','js');
		$JSandStyle.= Sys_Publib_Library::_getAllFileJavaScriptCss('','sys-js','ui/jquery-ui-1.8.14.custom.min.js',',','js');
		$JSandStyle.= Sys_Publib_Library::_getAllFileJavaScriptCss('','sys-style','themes/redmond/jquery-ui-1.8.15.custom.css',',','css');
		$this->view->LoadAllFileJsCss = $JSandStyle;	
		
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
		$this->view->currentModulCodeForLeft = 'PROCESS-WORK';
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
		//Bat dau lay vet tim kiem tu session
		$iLeaderId = $this->_request->getParam('C_LEADER','');
		$sStatus   = $this->_request->getParam('modul','');	
		$sfromDate = $this->_request->getParam('txtfromDate','');
		$stoDate = $this->_request->getParam('txttoDate','');
		$sfullTextSearch = trim($this->_request->getParam('txtfullTextSearch',''));
		$iCurrentPage = $this->_request->getParam('hdn_current_page',0);
		$iNumRowOnPage = $this->_request->getParam('hdn_record_number_page',0);
		
		// Tao doi tuong Zend_Filter
		$objFilter = new Zend_Filter();
		$ojbSysLib = new Sys_Library();
		$objWork = new work_modWork();
		$ojbSysInitConfig = new Sys_Init_Config();
		$objDocFun = new Sys_Function_DocFunctions();
		
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
			//$sStatus          	= $Parameter['trangThai'];
			$sfullTextSearch	= $Parameter['chuoiTimKiem'];
			$sfromDate			= $Parameter['tuNgay'];
			$stoDate			= $Parameter['denNgay'];
			$iCurrentPage		= $Parameter['trangHienThoi'];
			$iNumRowOnPage		= $Parameter['soBanGhiTrenTrang'];
			unset($_SESSION['seArrParameter']);
		}
		//Day cac gia tri tim kiem ra view
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
		
		$iOwnerId = $_SESSION['OWNER_ID'];
		$iUnitId = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');
		/*
		if($_SESSION['OWNER_ID'] != Sys_Init_Config::_setParentOwnerId())
				$arrLeader = Sys_Function_DocFunctions::docGetAllUnitLeader('LANH_DAO_PHUONG','arr_all_staff');
		else 	$arrLeader = Sys_Function_DocFunctions::docGetAllUnitLeader('LANH_DAO_UB','arr_all_staff');
		*/
		$arrPositionConst =	$ojbSysInitConfig->_setLeaderPostionGroup();
		$arrLeader = $objDocFun->docGetAllUnitLeader($arrPositionConst['_CONST_POSITION_GROUP'],'arr_all_staff');		
		
		$this->view->search_textselectbox_leader = $objDocFun->doc_search_ajax($arrLeader,"id","name","C_LEADER","hdn_leader_id",1,'position_code',1);
		
		$iStaffId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
		$iLeaderId = Sys_Function_DocFunctions::convertStaffNameToStaffId($iLeaderId,'');
		$arrResul = $objWork->DocWorkProcessGetAll($iOwnerId, $iStaffId, $iLeaderId, $iUnitId, $sStatus, Sys_Library::_ddmmyyyyToYYyymmdd($sfromDate),Sys_Library::_ddmmyyyyToYYyymmdd($stoDate), $sfullTextSearch, $iCurrentPage, $iNumRowOnPage);			
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
	
	public function processAction(){
		$this->view->infowork 	 	= 'THÔNG TIN CÔNG VIỆC';
		$this->view->updateprocess 	= 'CẬP NHẬT KẾT QUẢ XỬ LÝ CÔNG VIỆC';
		$this->view->infoprocess 	= 'QUÁ TRÌNH XỬ LÝ CÔNG VIỆC';
		$objDocFun = new Sys_Function_DocFunctions();
		$objWork   = new work_modWork();
		$ojbXmlLib = new Sys_Xml();
		$ojbSysLib = new Sys_Library();
		$objFilter = new Zend_Filter();
		$ojbSysInitConfig = new Sys_Init_Config();	
		$sStatus = $this->_request->getParam('status','');
		$this->view->status	= $sStatus;
		//Lay thong tin nguoi dang nhap hien thoi
		$StaffId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
		$sStaffName = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'name');
		$sStaffPosition = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'position_code');		
		
		//Lay thong tin file dinh kem
		$arrFileNameUpload = $ojbSysLib->_uploadFileList(10,$this->_request->getBaseUrl() . "/public/attach-file/",'FileName','!#~$|*');
		$arFileAttach = array();
		$this->view->AttachFile = $objDocFun->DocSentAttachFile($arFileAttach,sizeof($arFileAttach),10,true,43);	
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
		$arrConst = Sys_Init_Config::_setProjectPublicConst();
		$this->view->arrConst = $arrConst;
		$this->view->historyBack = '../index/';	
		
		//Lay tham so cau hinh
		$sysLibUrlPath = $ojbSysInitConfig->_setLibUrlPath();
		$url_path_calendar = $sysLibUrlPath . 'sys-calendar/';
		$this->view->urlCalendar = $url_path_calendar;
		
		//Goi ham thuc hien lay thong tin cho selectbox
		$iUnitId = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');
		$sUnitName = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_unit'],$iUnitId,'name');
		$arrDepartmentStaffId = Sys_Function_DocFunctions::docGetAllDepartmentStaffId($iUnitId);
		$this->view->search_textselectbox_staff = Sys_Function_DocFunctions::doc_search_ajax($arrDepartmentStaffId,"id","name","C_STAFF_NAME_LIST","hdn_staff_id_list",0,'position_code');
		
		$sWorkId = $this->_request->getParam('hdn_object_id','');
		$this->view->sWorkId = $sWorkId;
		
		//Id tien do cong viec
		$sProcessWorkId = $this->_request->getParam('hdn_processs_work_id','');
		//Mang luu thong tin chi tiet cua mot cong viec
		$arrWork = $objWork->DocWorkGetSingle($sWorkId);
		$this->view->arrWork = $arrWork;
		$sStatus	= $this->_request->getParam('C_PROCESS_STATUS','');
		//Cap nhat thong tin xu ly cong viec
		if($sWorkId != '' && $sWorkId != null){
			$arrParameter = array(	
								'PK_DOC_WORK'				=>$sProcessWorkId,	
								'FK_DOC'					=>$sWorkId,			
								'FK_UNIT'					=>$iUnitId,
								'C_UNIT_NAME'				=>$sUnitName,
								'FK_STAFF'					=>$StaffId,
								'C_STAFF_POSITION_NAME'		=>$sStaffPosition.' - '.$sStaffName,
								'C_WORK_DATE'				=>Sys_Library::_ddmmyyyyToYYyymmdd($this->_request->getParam('C_WORK_DATE','')),
								'C_RESULT'					=>$this->_request->getParam('C_RESULTS',''),	
								'C_PROCESS_STATUS'			=>$sStatus,				
								'NEW_FILE_ID_LIST'			=>$arrFileNameUpload,
							);	
			$arrResult = "";
		}
		//Truong hop cap nhat tien do cong viec
		$is_update				=	$_REQUEST['hdn_is_update'];
		if($is_update == 'GHI_TAM'){
			$arrResult = $objWork->DocWorkProcessUpdate($arrParameter);				
			$this->_redirect('work/process/process/?status='.$sStatus.'&hdn_object_id='.$sWorkId);
		}
		
		//Truong hop xoa mot hoac nhieu tien do cong viec
		if($_REQUEST['hdn_is_delete'] == 'XOA_TIEN_DO'){
			$sWorkProcessIdList = $this->_request->getParam('hdn_object_id_list','');
			$sRetError = $objWork->DocWorkProcessDelete($sWorkProcessIdList,1);
			if($sRetError != null || $sRetError != '' ){
				echo "<script type='text/javascript'>alert('$sRetError')</script>";
			}
		}
	
		//Lay thong tin tien do xu ly cua mot phong ban
		$arrProcesResultAll = $objWork->DocWorkProcessResultGetAll($sWorkId);
		$this->view->arrProcesResultAll	= $arrProcesResultAll;
	}
	
	
	public function viewAction(){
		$objDocFun = new Sys_Function_DocFunctions();
		$objWork = new work_modWork();
		$ojbSysInitConfig = new Sys_Init_Config();	
		$this->view->bodyTitle = "CHI TIẾT CÔNG VIỆC";
		$this->view->getStatusLeftMenu = $this->_request->getParam('hdn_left_status','');
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
		$objFunction =	new	Sys_Function_DocFunctions()	;
		$arrConst = Sys_Init_Config::_setProjectPublicConst();
		//Lay duong dan
		$path = $_SERVER['SCRIPT_FILENAME'];
		$path = substr($path, 0, -9);
		$my_report = str_replace("/", "\\", $path) . "rpt\\work\\ListWork.rpt";
		// Tao doi tuong Crystal 9
		$COM_Object = "CrystalDesignRunTime.Application.9";		
		$crapp= new COM($COM_Object) or die("Unable to Create Object");
		$creport = $crapp->OpenReport($my_report, 1);	
		//Lay cac tham so tren form
		$sfromDate			= $this->_request->getParam('hdn_from_date','');
		$stoDate			= $this->_request->getParam('hdn_to_date','');
		$sFromDate 			= Sys_Library::_ddmmyyyyToYYyymmdd($sfromDate);
		$sToDate 			= Sys_Library::_ddmmyyyyToYYyymmdd($stoDate);
		$sfullTextSearch	= $this->_request->getParam('hdn_full_textSearch','');
		$sStatus			= $this->_request->getParam('hdn_status','');
		$iLeaderId			= $this->_request->getParam('hdn_leader_id','');
		$StaffId			= Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
		$sStaffName 		= Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'name');
		$sStaffPosition 	= Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'position_name');	
		$iUnitId 			= Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');
		$sUnitName			= Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_unit'],$iUnitId,'name');
		
		// Truyen tham so vao
		//Ket noi CSDL SQL theo kieu ADODB
		$connectSQL = new Zend_Config_Ini('./config/config.ini','dbmssql');
		$arrConn = $connectSQL->db->config->toArray();
		$creport->Database->Tables(1)->SetLogOnInfo($arrConn['host'], $arrConn['dbname'], $arrConn['username'], $arrConn['password']);
		$creport->EnableParameterPrompting = 0;
		//echo $sadvandeSearch.'-'.$iLeaderId.'-'.$iUnitId.'-'.$sStatus.'-'.$sDocType.'-'.$sFromDate.'-'.$sToDate.'-'.$sfullTextSearch; exit;
		//echo $sRecordArchivedId; exit;
		$creport->ReadRecords();
		$z = $creport->ParameterFields(1)->SetCurrentValue('Từ ngày: '.$sfromDate.'&nbsp;&nbsp;&nbsp;&nbsp;Đến ngày: '.$stoDate);
		$z = $creport->ParameterFields(2)->SetCurrentValue($sStaffPosition.' - '.$sStaffName.', '.$sUnitName);
		$z = $creport->ParameterFields(3)->SetCurrentValue((int)$_SESSION['OWNER_ID']);
		$z = $creport->ParameterFields(4)->SetCurrentValue($StaffId);
		$z = $creport->ParameterFields(5)->SetCurrentValue((int)$iLeaderId);
		$z = $creport->ParameterFields(6)->SetCurrentValue((int)$iUnitId);
		$z = $creport->ParameterFields(7)->SetCurrentValue($sStatus);
		$z = $creport->ParameterFields(8)->SetCurrentValue($sFromDate);
		$z = $creport->ParameterFields(9)->SetCurrentValue($sToDate);
		$z = $creport->ParameterFields(10)->SetCurrentValue($sfullTextSearch);
		$z = $creport->ParameterFields(11)->SetCurrentValue(1);
		$z = $creport->ParameterFields(12)->SetCurrentValue(9999);
		$z = $creport->ParameterFields(13)->SetCurrentValue(1);
		// Dinh dang file report ket xuat
		$report_file = 'danh sach cong viec' . mt_rand(1,1000000) . '.doc';
		// Duong dan file report	
		$my_report_file = str_replace("/", "\\", $path) . "public\\" . $report_file;
		$creport->ExportOptions->DiskFileName=$my_report_file; //export to file 
		$creport->ExportOptions->PDFExportAllPages=true;
		$creport->ExportOptions->DestinationType = 1; // export to file
		$creport->ExportOptions->FormatType= 14; // Type file
		$creport->Export(false);
		$my_report_file = 'http://'.$_SERVER['HTTP_HOST'].Sys_Init_Config::_setWebSitePath().'public/'.$report_file;
		$this->view->my_report_file = $my_report_file; 
	}
	public function printworkAction(){
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
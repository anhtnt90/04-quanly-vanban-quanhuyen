<?php
class record_archivesController extends  Zend_Controller_Action {
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
		Zend_Loader::loadClass('dashboard_modWebMenu');
		//Lay tat ca cac chuyen muc
		$objWebMenu = new dashboard_modWebMenu();
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
		//Goi lop modRecord
		Zend_Loader::loadClass('record_modRecord');
		$this->view->JSPublicConst = $objConfig->_setJavaScriptPublicVariable();		
		
		// Load tat ca cac file Js va Css
		$JSandStyle = Sys_Publib_Library::_getAllFileJavaScriptCss('','js','Record.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','ajax.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','jquery-1.4.2.min.js,jQuery.equalHeights.js',',','js'). Sys_Publib_Library::_getAllFileJavaScriptCss('','js/LibSearch','actb_search.js,common_search.js',',','js');
		$JSandStyle.= Sys_Publib_Library::_getAllFileJavaScriptCss('','js','jquery-1.4.2.min.js,ui/i18n/jquery.ui.datepicker-vi.js',',','js');
		$JSandStyle.= Sys_Publib_Library::_getAllFileJavaScriptCss('','js','ui/jquery-ui-1.8.14.custom.min.js',',','js');
		$JSandStyle.= Sys_Publib_Library::_getAllFileJavaScriptCss('','style','themes/redmond/jquery-ui-1.8.15.custom.css',',','css');
		//echo "AAA" . $JSandStyle; exit; 
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
		$this->view->currentModulCode = "RECORD";
		$pcurrentModulCodeForLeft = $this->_request->getParam('htn_leftmodul',"");
		if($pcurrentModulCodeForLeft != '')
				$this->view->currentModulCodeForLeft = $pcurrentModulCodeForLeft;
		else 	$this->view->currentModulCodeForLeft = 'DOCUMENT-RECORD-DOC';
		//echo 'okoko'.$pcurrentModulCodeForLeft;
		$psshowModalDialog = $this->_request->getParam('showModalDialog',"");
		$this->view->showModelDialog = $psshowModalDialog;
		if ($psshowModalDialog != 1){
			$response->insert('header', $this->view->renderLayout('twd_header.phtml','template/'));    
			$response->insert('left', $this->view->renderLayout('twd_left.phtml','template/'));  	    
	        $response->insert('footer', $this->view->renderLayout('twd_footer.phtml','template/'));
		}
  	}	
	/**
	 * Idea : Phuong thuc hien thi danh sach
	 *
	 */
	public function indexAction(){
		//Lay ID cua NSD dang nhap hien thoi
		$iCurrentStaffId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
		//Lay ID phong ban cua NSD dang nhap hien thoi
		$iCurrentUnitId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');
		$pUrl = $_SERVER['REQUEST_URI'];
		// Tieu de tim kiem
		$this->view->bodyTitleSearch = "DANH SÁCH HỒ SƠ LƯU TRỮ";				
		// Tieu de man hinh danh sach
		$this->view->bodyTitle = "DANH SÁCH HỒ SƠ LƯU TRỮ";
		//Bat dau lay vet tim kiem tu session
		$sfromDate = $this->_request->getParam('txtfromDate','');
		//if($sfromDate == '')
			//$sfromDate = '1/1/'.date("Y");
		$stoDate = $this->_request->getParam('txttoDate','');
		//if($stoDate == '')
			//$stoDate = date("d/m/Y");
		$sfullTextSearch = $this->_request->getParam('txtfullTextSearch','');
		$iCurrentPage = $this->_request->getParam('hdn_current_page',0);
		if($iCurrentPage < 1)
			$iCurrentPage = 1;
		$iNumRowOnPage = $this->_request->getParam('hdn_record_number_page',0);
		if($iNumRowOnPage == 0)
			$iNumRowOnPage = 15;
		//Neu ton tai gia tri tim kiem tron session thi lay trong session
		if(isset($_SESSION['seArrParameter'])){
			$Parameter 			= $_SESSION['seArrParameter'];
			$sfullTextSearch	= $Parameter['chuoiTimKiem'];
			$sfromDate			= $Parameter['tuNgay'];
			$stoDate			= $Parameter['denNgay'];
			$iCurrentPage		= $Parameter['trangHienThoi'];
			$iNumRowOnPage		= $Parameter['soBanGhiTrenTrang'];
			unset($_SESSION['seArrParameter']);
		}
		//Day cac gia tri tim kiem ra view
		$this->view->sFullTextSearch 	= $sfullTextSearch;
		$this->view->fromDate 			= $sfromDate;
		$this->view->toDate				= $stoDate;
		$this->view->iCurrentPage 		= $iCurrentPage;
		$this->view->iNumRowOnPage 		= $iNumRowOnPage;
		//Lay cac hang so dung chung
		$arrConst = Sys_Init_Config::_setProjectPublicConst();
		$this->view->arrConst = $arrConst;
		// Tao doi tuong Zend_Filter
		$objFilter = new Zend_Filter();
		$ojbSysLib = new Sys_Library();
		// Lay toan bo tham so truyen tu form			
		$arrInput = $this->_request->getParams();
		$objRecordArchive = new record_modRecord();
		//Lay MA DON VI NSD dang nhap hien thoi
		$sOwnerCode = $_SESSION['OWNER_CODE'];
		$arrResul = $objRecordArchive->DocRecordArchivesGetAll($iCurrentStaffId ,$_SESSION['OWNER_ID'], $iCurrentUnitId, $sfullTextSearch, $iCurrentPage, $iNumRowOnPage, Sys_Library::_ddmmyyyyToYYyymmdd($sfromDate), Sys_Library::_ddmmyyyyToYYyymmdd($stoDate));			
		$iNumberRecord = $arrResul[0]['C_TOTAL_RECORD'];	
		$sdocpertotal ="Danh sách này không có văn bản nào";
		//Hien thi thong tin man hinh danh sach nay co bao nhieu ban ghi va hien thi Radio "Chon tat ca"; "Bo chon tat ca"
		$this->view->SelectDeselectAll = Sys_Publib_Library::_selectDeselectAll(sizeof($arrResul), $iNumberRecord);
		if (count($arrResul) > 0){
			$this->view->sdocpertotal = "Danh sách có: ".sizeof($arrResul).'/'.$iNumberRecord." hồ sơ";
			//Sinh xau HTML mo ta so trang (Trang 1; Trang 2;...)
			$this->view->generateStringNumberPage = Sys_Publib_Library::_generateStringNumberPage($iNumberRecord, $iCurrentPage, $iNumRowOnPage,$pUrl) ;
			//Sinh chuoi HTML mo ta tong so trang (Trang 1; Trang 2;...) va quy dinh so record/page
			$this->view->generateHtmlSelectBoxPage = Sys_Publib_Library::_generateChangeRecordNumberPage($iNumRowOnPage,"index");
		}
		$this->view->arrResul = $arrResul;
		$this->view->iCountElement = count($this->view->arrAllRecord);
	}
	/**
	 * Idea : Phuong thuc them moi mot VB
	 *
	 */
	public function addAction(){
		$this->view->bodyTitle = 'TẠO HỒ SƠ LƯU TRỮ';
		$arrInput = $this->_request->getParams();
		$objDocFun = new Sys_Function_DocFunctions();
		$objRecordArchive = new record_modRecord();
		$ojbXmlLib = new Sys_Publib_Xml();
		$ojbSysLib = new Sys_Library();
		$objFilter = new Zend_Filter();
		$ojbSysInitConfig = new Sys_Init_Config();	
		//Lay cac hang so dung chung
		$arrConst = Sys_Init_Config::_setProjectPublicConst();
		$this->view->arrConst = $arrConst;
		$this->view->currentModulCodeForLeft = "RECORD-ARCHIVES";
		//Lay tham so cau hinh
		$sysLibUrlPath = $ojbSysInitConfig->_setLibUrlPath();
		$url_path_calendar = $sysLibUrlPath . 'sys-calendar/';
		$this->view->urlCalendar = $url_path_calendar;
		//Lay ID cua NSD dang nhap hien thoi
		$StaffId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
		//Lay ID phong ban cua NSD dang nhap hien thoi
		$iUnitId = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');	
		//Lay ten phong ban nguoi dang nhap hien thoi
		$sUnitName = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_unit'],$iUnitId,'name');	
		$this->view->sUnitName = $sUnitName;
		//Lay TEN cua NSD dang nhap hien thoi
		$sStaffName = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'name');
		$this->view->sStaffName = $sStaffName;
		//Lay CHUC VU phong ban cua NSD dang nhap hien thoi
		$sStaffPosition = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'position_code');	
		$this->view->sStaffPosition = $sStaffPosition;		
		//Tuy chon ung voi cac truong hop update du lieu	
		$sOption = $this->_request->getParam('hdh_option','');
		$this->view->option = $sOption;
		//Lay gia tri tim kiem tren form
		$sfullTextSearch 	= $this->_request->getParam('txtfullTextSearch','');
		$sfromDate 			= $this->_request->getParam('txtfromDate','');
		$stoDate 			= $this->_request->getParam('txttoDate','');
		$iCurrentPage		= $this->_request->getParam('hdn_current_page',0);
		$iNumRowOnPage 		= $this->_request->getParam('hdn_record_number_page',0);
		if ($iCurrentPage <= 1){
			$iCurrentPage = 1;
		}
		$iNumRowOnPage = $this->_request->getParam('cbo_nuber_record_page',0);
		if ($iNumRowOnPage == 0)
			$iNumRowOnPage = 15;
		$arrParaSet = array("trangHienThoi"=>$iCurrentPage, "soBanGhiTrenTrang"=>$iNumRowOnPage,"chuoiTimKiem"=>$sfullTextSearch,"tuNgay"=>$sfromDate,"denNgay"=>$stoDate);
		$_SESSION['seArrParameter'] = $arrParaSet;	
		if ($sOption == "QUAY_LAI"){
			$this->_redirect('record/archives/index/');
		}
		//Lay ten file XML
		$psFileName = $this->_request->getParam('hdn_xml_file','');
		//Neu khong ton tai file XML thi doc file XML mac dinh
		if($psFileName == "" || !is_file($psFileName)){
			$psFileName = Sys_Init_Config::_setXmlFileUrlPath(1) . "Record/thong_tin_nguoi_xem.xml";
		}
		$psXmlStr = "";
		$arrGetSingleList = array();
		$this->view->generateFormHtml = $ojbXmlLib->_xmlGenerateFormfield($psFileName, 'update_row', $psXmlStr, $arrGetSingleList,true,true);
		$arrParameter = array(	
								'PK_RECORD'					=>'',	
								'FK_OWNER_ID'				=>$_SESSION['OWNER_ID'],			
								'FK_STAFF'					=>$StaffId,
								'C_STAFF_POSITION_NAME'		=>$sStaffPosition . ' - ' . $sStaffName,
								'FK_UNIT'					=>$iUnitId,
								'C_UNIT_NAME'				=>$sUnitName,
								'C_CREATE_DATE'				=>Sys_Library::_ddmmyyyyToYYyymmdd($this->_request->getParam('C_CREATE_DATE','')),
								'C_RECORD_NAME'				=>$this->_request->getParam('C_RECORD_NAME',''),
								'C_RECORD_ID'				=>$this->_request->getParam('C_RECORD_ID',''),
								'C_NOTES'					=>$this->_request->getParam('C_NOTES',''),																	
								'C_PERMISSION_VIEW'			=>$this->_request->getParam('hdn_option_view',''),
								'C_VIEW_STAFF_LIST_ID'		=>$this->_request->getParam('hdn_view_staff',''),
							);	
		//Bien luu gia tri tra ve cua ham update ID cua van ban duoc THEM MOI hoac CHINH SUA
		$arrResult = "";
		if ($this->_request->getParam('C_RECORD_NAME','') != ""){
			$arrResult = $objRecordArchive->DocRecordArchiveUpdate($arrParameter);
			//Truong hop ghi va them moi
			if ($sOption == "GHI_THEMMOI"){
				//Ghi va quay lai chinh form voi noi dung rong						
				$this->_redirect('record/archives/add/');
			}	
			//Truong hop ghi va them tiep
			if ($sOption == "GHI_THEMTIEP"){
				$this->view->option = $sOption;
				//Them van ban moi va giu lai noi dung thong tin tren form					
				$this->_redirect('record/archives/edit/hdn_object_id/' . $arrResult['NEW_ID']);
			}
			//Truong hop ghi nhan
			if ($sOption == "GHI_TAM"){
				$this->view->option = $sOption;
				//Ghi va quay lai chinh form voi noi dung rong						
				$this->_redirect('record/archives/edit/hdn_object_id/' . $arrResult['NEW_ID']);
			}
			//Truong hop ghi va quay lai
			if ($sOption == "GHI_QUAYLAI"){
				//Tro ve trang index						
				$this->_redirect('record/archives/index/');	
			}	
		}	
	}
	public function editAction(){
		$this->view->bodyTitle = 'TẠO HỒ SƠ LƯU TRỮ';
		$arrInput = $this->_request->getParams();
		$objDocFun = new Sys_Function_DocFunctions();
		$objRecordArchive = new record_modRecord();
		$ojbXmlLib = new Sys_Publib_Xml();
		$ojbSysLib = new Sys_Library();
		$objFilter = new Zend_Filter();
		$ojbSysInitConfig = new Sys_Init_Config();	
		//Lay cac hang so dung chung
		$arrConst = Sys_Init_Config::_setProjectPublicConst();
		$this->view->arrConst = $arrConst;
		//Lay tham so cau hinh
		$sysLibUrlPath = $ojbSysInitConfig->_setLibUrlPath();
		$url_path_calendar = $sysLibUrlPath . 'sys-calendar/';
		$this->view->urlCalendar = $url_path_calendar;
		//Lay ID cua NSD dang nhap hien thoi
		$StaffId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
		//Lay ID phong ban cua NSD dang nhap hien thoi
		$iUnitId = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');	
		//Lay ten phong ban nguoi dang nhap hien thoi
		$sUnitName = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_unit'],$iUnitId,'name');	
		$this->view->sUnitName = $sUnitName;
		//Lay TEN cua NSD dang nhap hien thoi
		$sStaffName = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'name');
		$this->view->sStaffName = $sStaffName;
		//Lay CHUC VU phong ban cua NSD dang nhap hien thoi
		$sStaffPosition = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'position_code');	
		$this->view->sStaffPosition = $sStaffPosition;		
		//Tuy chon ung voi cac truong hop update du lieu	
		$sOption = $this->_request->getParam('hdh_option','');
		$this->view->option = $sOption;
		if ($sOption == "QUAY_LAI"){
			$this->_redirect('record/archives/index/');
		}
		//Lay ten file XML
		$psFileName = $this->_request->getParam('hdn_xml_file','');
		//Neu khong ton tai file XML thi doc file XML mac dinh
		if($psFileName == "" || !is_file($psFileName)){
			$psFileName = Sys_Init_Config::_setXmlFileUrlPath(1) . "Record/thong_tin_nguoi_xem.xml";
		}
		//Lay id ho so tu view
		$sRecordArchivedId = $this->_request->getParam('hdn_object_id','');
		$this->view->sRecordArchivedId = $sRecordArchivedId;
		//Mang luu thong tin chi tiet cua mot van ban
		$arrRecordArchive = $objRecordArchive->DocRecordArchiveGetSingle($sRecordArchivedId,'','');
		$this->view->arrRecordArchive = $arrRecordArchive;
		//Lay gia tri tim kiem tren form
		$sfullTextSearch 	= $this->_request->getParam('txtfullTextSearch','');
		$sfromDate 			= $this->_request->getParam('txtfromDate','');
		$stoDate 			= $this->_request->getParam('txttoDate','');
		$iCurrentPage		= $this->_request->getParam('hdn_current_page',0);
		$iNumRowOnPage 		= $this->_request->getParam('hdn_record_number_page',0);
		if ($iCurrentPage <= 1){
			$iCurrentPage = 1;
		}
		$iNumRowOnPage = $this->_request->getParam('cbo_nuber_record_page',0);
		if ($iNumRowOnPage == 0)
			$iNumRowOnPage = 15;
		$arrParaSet = array("trangHienThoi"=>$iCurrentPage, "soBanGhiTrenTrang"=>$iNumRowOnPage,"chuoiTimKiem"=>$sfullTextSearch,"tuNgay"=>$sfromDate,"denNgay"=>$stoDate);
		$_SESSION['seArrParameter'] = $arrParaSet;
		//Hien thi thong tin Phan quyen xem ho so luu tru
		$psXmlStr = "";
		if ($sOption == "GHI_THEMTIEP"){
			$arrRecordArchive = array();
		}
		$this->view->generateFormHtml = $ojbXmlLib->_xmlGenerateFormfield($psFileName, 'update_row', $psXmlStr, $arrRecordArchive, true, true);
		if($sRecordArchivedId != '' && $sRecordArchivedId != null  && $sOption != "QUAY_LAI"){
				//Neu la ghi va them tiep thi gan ID VB lay duoc = "" de them moi mot VB
				if ($sOption == "GHI_THEMTIEP"){
					$sRecordArchivedId = "";
				}
				//Mang luu tham so update in database
				$arrParameter = array(										
										'PK_RECORD'					=>$sRecordArchivedId,	
										'FK_OWNER_ID'				=>$_SESSION['OWNER_ID'],			
										'FK_STAFF'					=>$StaffId,
										'C_STAFF_POSITION_NAME'		=>$sStaffPosition . ' - ' . $sStaffName,
										'FK_UNIT'					=>$iUnitId,
										'C_UNIT_NAME'				=>$sUnitName,
										'C_CREATE_DATE'				=>Sys_Library::_ddmmyyyyToYYyymmdd($this->_request->getParam('C_CREATE_DATE','')),
										'C_RECORD_NAME'				=>$this->_request->getParam('C_RECORD_NAME',''),
										'C_RECORD_ID'				=>$this->_request->getParam('C_RECORD_ID',''),
										'C_NOTES'					=>$this->_request->getParam('C_NOTES',''),																	
										'C_PERMISSION_VIEW'			=>$this->_request->getParam('hdn_option_view',''),
										'C_VIEW_STAFF_LIST_ID'		=>$this->_request->getParam('hdn_view_staff',''),
									);
				$arrResult = "";
		}
		if ($this->_request->getParam('C_RECORD_NAME','') != ""){
			$arrResult = $objRecordArchive->DocRecordArchiveUpdate($arrParameter);
				//Truong hop ghi va them moi
				if ($sOption == "GHI_THEMMOI"){
					//Ghi va quay lai chinh form voi noi dung rong		
					$this->_redirect('record/archives/add/');
				}	
				//Truong hop ghi va them tiep
				if ($sOption == "GHI_THEMTIEP"){
					$this->view->option = $sOption;
					//Lay ID ho so vua moi insert vao DB
					$this->view->sRecordArchivedId = $arrResult['NEW_ID'];
					//Lay thong tin van ban vua them moi va hien thi ra view
					$arrRecordArchive = $objRecordArchive->DocRecordArchiveGetSingle($arrResult['NEW_ID'],'','');
					$this->view->arrRecordArchive = $arrRecordArchive;
				}
				//Truong hop ghi tam
				if ($sOption == "GHI_TAM"){
					//Lay ID VB vua moi insert vao DB
					$this->view->sRecordArchivedId = $arrResult['NEW_ID'];
					$this->view->option = $sOption;
					//Lay thong tin van ban vua them moi va hien thi ra view
					$arrRecordArchive = $objRecordArchive->DocRecordArchiveGetSingle($arrResult['NEW_ID'],'','');
					$this->view->arrRecordArchive = $arrRecordArchive;
					$this->view->generateFormHtml = $ojbXmlLib->_xmlGenerateFormfield($psFileName, 'update_row', $psXmlStr, $arrRecordArchive, true, true);
				}
				//Truong hop ghi va quay lai
				if ($sOption == "GHI_QUAYLAI"){				
					$this->_redirect('record/archives/index/');	
				}	
		}
	}
	public function deleteAction(){
		$objDocFun 			 = new Sys_Function_DocFunctions();
		$objRecordArchive	 = new record_modRecord();
		$ojbSysInitConfig	 = new Sys_Init_Config();	
		$RecordArchiveIdList = $this->_request->getParam('hdn_object_id_list','');
		$arrDocIdList        = $objRecordArchive->DocRecordArchiveDocIdGetAllInRecord($RecordArchiveIdList);
		$sRetError			 = $objRecordArchive->DocRecordArchivesDelete($RecordArchiveIdList,1);
		if($sRetError != null || $sRetError != '' ){
			echo "<script type='text/javascript'>alert('$sRetError');actionUrl('index/');</script>";
		}
		else{
			if($arrDocIdList != null && $arrDocIdList != ''){
				$sDocIdList  	 = '';
				for($index = 0;$index < sizeof($arrDocIdList)-1;$index++){
					$sDocIdList  = $sDocIdList.$arrDocIdList[$index]['FK_DOC'].',';
				}
				$sDocIdList		 = $sDocIdList.$arrDocIdList[sizeof($arrDocIdList)-1]['FK_DOC'];
			}
			$sDocType			 = 'VB_HSLT';
			$sTableName 		 = 'T_DOC_DOCUMENT_OTHER_RECORD';
			$sdelimitor          = '!#~$|*';
			$arrResult        	 = $objRecordArchive->DocRecordArchiveFileNameInDoc($sDocIdList, $sDocType, $sTableName, $sdelimitor);
			if($arrResult != null && $arrResult != ''){
				for($index = 0;$index < sizeof($arrResult);$index++){
					//xoa file tren o cung
					$arrFileName = explode($sdelimitor, $arrResult[$index]['C_FILE_NAME']);
					$scriptUrl = $_SERVER['SCRIPT_FILENAME'];
					$scriptFileName = explode("/", $scriptUrl);
					$linkFile = $scriptFileName[0] . "\\" . $scriptFileName[1] . "\\" . $scriptFileName[2]. "\\" . "public\attach-file\\";
					for($i =0; $i < sizeof($arrFileName); $i ++){
						$fileId = explode("!~!", $arrFileName[$i]);
						$fileId = explode("_" ,$fileId[0]);
						$unlink = $linkFile . $fileId[0] . "\\" . $fileId[1] . "\\" . "\\" . $fileId[2] . "\\" . $arrFileName[$i];
						unlink($unlink);
					}
					$objRecordArchive->deleteFileUpload($arrResult[$index]['C_FILE_NAME']);
				}
			}
			$this->_redirect('record/archives/index/');	
		}
	}
	public function docrelateAction(){
		$this->view->bodyTitle = 'TẠO HỒ SƠ LƯU TRỮ';
		$arrInput = $this->_request->getParams();
		$objDocFun = new Sys_Function_DocFunctions();
		$objRecordArchive = new record_modRecord();
		$ojbXmlLib = new Sys_Publib_Xml();
		$ojbSysLib = new Sys_Library();
		$objFilter = new Zend_Filter();
		$ojbSysInitConfig = new Sys_Init_Config();	
		//Lay cac hang so dung chung
		$arrConst = Sys_Init_Config::_setProjectPublicConst();
		$this->view->arrConst = $arrConst;
		//Lay tham so cau hinh
		$sysLibUrlPath = $ojbSysInitConfig->_setLibUrlPath();
		$url_path_calendar = $sysLibUrlPath . 'sys-calendar/';
		$this->view->urlCalendar = $url_path_calendar;
		//Lay ID cua NSD dang nhap hien thoi
		$StaffId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
		$this->view->StaffId = $StaffId;
		//Lay ID phong ban cua NSD dang nhap hien thoi
		$iUnitId = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');	
		//Lay ten phong ban nguoi dang nhap hien thoi
		$sUnitName = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_unit'],$iUnitId,'name');	
		$this->view->sUnitName = $sUnitName;
		//Lay TEN cua NSD dang nhap hien thoi
		$sStaffName = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'name');
		$this->view->sStaffName = $sStaffName;
		//Lay CHUC VU phong ban cua NSD dang nhap hien thoi
		$sStaffPosition = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'position_code');	
		$this->view->sStaffPosition = $sStaffPosition;	
		//Lay id ho so tu view
		$sRecordArchivedId = $this->_request->getParam('hdn_object_id','');
		$this->view->sRecordArchivedId = $sRecordArchivedId;
		//Mang luu thong tin chi tiet cua mot van ban
		$arrRecordArchive = $objRecordArchive->DocRecordArchiveGetSingle($sRecordArchivedId,'','');
		$this->view->arrRecordArchive = $arrRecordArchive;
		//Lay gia tri tim kiem tren form
		$sfullTextSearch 	= $this->_request->getParam('txtfullTextSearch','');
		$sfromDate 			= $this->_request->getParam('txtfromDate','');
		$stoDate 			= $this->_request->getParam('txttoDate','');
		$iCurrentPage		= $this->_request->getParam('hdn_current_page',0);
		$iNumRowOnPage 		= $this->_request->getParam('hdn_record_number_page',0);
		if ($iCurrentPage <= 1){
			$iCurrentPage = 1;
		}
		$iNumRowOnPage = $this->_request->getParam('cbo_nuber_record_page',0);
		if ($iNumRowOnPage == 0)
			$iNumRowOnPage = 15;
		$arrParaSet = array("trangHienThoi"=>$iCurrentPage, "soBanGhiTrenTrang"=>$iNumRowOnPage,"chuoiTimKiem"=>$sfullTextSearch,"tuNgay"=>$sfromDate,"denNgay"=>$stoDate);
		$_SESSION['seArrParameter'] = $arrParaSet;
	}
	/**
	 * nguoi tao: phuongtt
	 * ngay tao: 20/07/2010
	 * y nghia: phuong thuc cap nhat van ban den lien quan vao mot ho so luu tru
	 */
	public function getreceivedAction(){
		//An MeneLeft , MenuHeader , MenuFooter	
		$this->view->hideDisplayMeneLeft = ""; 
		$this->view->hideDisplayMenuHeader ="";
		$this->view->hideDisplayMenuFooter = "";
		$this->view->bodyTitle = "LẤY VĂN BẢN ĐẾN";
		$objRecordArchive = new record_modRecord();
		$sUrl = $_SERVER['REQUEST_URI'];			
		//Lay cac hang so dung chung
		$arrConst = Sys_Init_Config::_setProjectPublicConst();
		$this->view->arrConst = $arrConst;
		// Tao doi tuong Zend_Filter
		$objFilter = new Zend_Filter();
		$ojbSysLib = new Sys_Library();
		$objDocFun = new Sys_Function_DocFunctions();
		// Lay toan bo tham so truyen tu form			
		$arrInput = $this->_request->getParams();		
		//Lay thong tin tu danh muc
		$arrLoaiVB = $objRecordArchive->getPropertiesDocument('DM_LOAI_VAN_BAN');
		$arrDocCate = $objRecordArchive->getPropertiesDocument('DM_LINH_VUC_VAN_BAN');
		// Goi ham search lay ra loai van ban
		$this->view->search_textselectbox_doc_type = Sys_Function_DocFunctions::doc_search_ajax($arrLoaiVB,"C_CODE","C_NAME","C_DOC_TYPE","hdn_doc_type",1,"",1);
		// Goi ham search lay ra linh vuc van ban
		$this->view->search_textselectbox_doc_cate = Sys_Function_DocFunctions::doc_search_ajax($arrDocCate,"C_CODE","C_NAME","C_DOC_CATE","hdn_doc_cate",1,"",1);
		$sRecordArchiveId	= $this->_request->getParam('hdn_object_id','');
		$this->view->sRecordArchiveId = $sRecordArchiveId;
		$sDocRelateListId	= $this->_request->getParam('hdn_docrelate_id_list','');
		$sDocRelateType		= $this->_request->getParam('hdn_docrelate_type','');
		$this->view->sDocRelateType = $sDocRelateType;
		if ($this->_request->getParam('hdn_save','') == 'CHON' && $sDocRelateListId != ''){
			$arrResult = $objRecordArchive->DocRecordArchiveDocRelateUpdate($sRecordArchiveId, $sDocRelateListId, $sDocRelateType);
		}	
	}
	/**
	 * nguoi tao: phuongtt
	 * ngay tao: 20/07/2010
	 * y nghia: phuong thuc cap nhat van ban den lien quan vao mot ho so luu tru
	 */
	public function getsendAction(){
		//An MeneLeft , MenuHeader , MenuFooter	
		$this->view->hideDisplayMeneLeft = ""; 
		$this->view->hideDisplayMenuHeader ="";
		$this->view->hideDisplayMenuFooter = "";
		$this->view->bodyTitle = "LẤY VĂN BẢN ĐI";
		$objRecordArchive = new record_modRecord();
		$sUrl = $_SERVER['REQUEST_URI'];			
		//Lay cac hang so dung chung
		$arrConst = Sys_Init_Config::_setProjectPublicConst();
		$this->view->arrConst = $arrConst;
		// Tao doi tuong Zend_Filter
		$objFilter = new Zend_Filter();
		$ojbSysLib = new Sys_Library();
		$objDocFun = new Sys_Function_DocFunctions();
		// Lay toan bo tham so truyen tu form			
		$arrInput = $this->_request->getParams();		
		//Lay thong tin tu danh muc
		$arrLoaiVB = $objRecordArchive->getPropertiesDocument('DM_LOAI_VAN_BAN');
		$arrDocCate = $objRecordArchive->getPropertiesDocument('DM_LINH_VUC_VAN_BAN');
		// Goi ham search lay ra loai van ban
		$this->view->search_textselectbox_doc_type = Sys_Function_DocFunctions::doc_search_ajax($arrLoaiVB,"C_CODE","C_NAME","C_DOC_TYPE","hdn_doc_type",1,"",1);
		// Goi ham search lay ra linh vuc van ban
		$this->view->search_textselectbox_doc_cate = Sys_Function_DocFunctions::doc_search_ajax($arrDocCate,"C_CODE","C_NAME","C_DOC_CATE","hdn_doc_cate",1,"",1);
		$sRecordArchiveId	= $this->_request->getParam('hdn_object_id','');
		$this->view->sRecordArchiveId = $sRecordArchiveId;
		$sDocRelateListId	= $this->_request->getParam('hdn_docrelate_id_list','');
		$sDocRelateType		= $this->_request->getParam('hdn_docrelate_type','');
		$this->view->sDocRelateType = $sDocRelateType;
		if ($this->_request->getParam('hdn_save','') == 'CHON' && $sDocRelateListId != ''){
			$arrResult = $objRecordArchive->DocRecordArchiveDocRelateUpdate($sRecordArchiveId, $sDocRelateListId, $sDocRelateType);
		}	
	}
	public function printAction(){
		//Lay id ho so tu view
		$sRecordArchivedId = $this->_request->getParam('hdn_object_id','');
		$objRecordArchive = new record_modRecord();
		$arrRecordArchive = $objRecordArchive->DocRecordArchiveGetSingle($sRecordArchivedId,'','');
		// Duong dan file rpt
		//Lay duong dan
		$path = $_SERVER['SCRIPT_FILENAME'];
		$path = substr($path, 0, -9);
		$my_report = str_replace("/", "\\", $path) . "rpt\\record\\Record.rpt";
		// Tao doi tuong Crystal 9
		$COM_Object = "CrystalDesignRunTime.Application.9";		
		$crapp= new COM($COM_Object) or die("Unable to Create Object");
		$creport = $crapp->OpenReport($my_report, 1);	
		
		//Ket noi CSDL SQL theo kieu ADODB
		$connectSQL = new Zend_Config_Ini('./config/config.ini','dbmssql');
		$arrConn = $connectSQL->db->config->toArray();
		$creport->Database->Tables(1)->SetLogOnInfo($arrConn['host'], $arrConn['dbname'], $arrConn['username'], $arrConn['password']);
		$creport->EnableParameterPrompting = 0;
		//echo $sRecordArchivedId; exit;
		$creport->ReadRecords();
		// Truyen tham so vao
		$z = $creport->ParameterFields(1)->SetCurrentValue($arrRecordArchive[0]['C_RECORD_ID']);
		$z = $creport->ParameterFields(2)->SetCurrentValue($arrRecordArchive[0]['C_RECORD_NAME']);
		$z = $creport->ParameterFields(3)->SetCurrentValue($arrRecordArchive[0]['C_STAFF_POSITION_NAME'].', '.$arrRecordArchive[0]['C_UNIT_NAME']);
		$z = $creport->ParameterFields(4)->SetCurrentValue($arrRecordArchive[0]['C_NOTES']);
		$z = $creport->ParameterFields(5)->SetCurrentValue($arrRecordArchive[0]['C_CREATE_DATE']);
		$z = $creport->ParameterFields(6)->SetCurrentValue($sRecordArchivedId);
		$z = $creport->ParameterFields(8)->SetCurrentValue(1);
		$z = $creport->ParameterFields(9)->SetCurrentValue(1000);
		// Dinh dang file report ket xuat
		$report_file = 'record' . mt_rand(1,1000000) . '.doc';
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
	public function adddocotherAction(){
		//An MeneLeft , MenuHeader , MenuFooter	
		$this->view->hideDisplayMeneLeft = ""; 
		$this->view->hideDisplayMenuHeader ="";
		$this->view->hideDisplayMenuFooter = "";
		$this->view->bodyTitle = 'TẠO VĂN BẢN';
		$arrInput = $this->_request->getParams();
		$objDocFun = new Sys_Function_DocFunctions();
		$objRecordArchive = new record_modRecord();
		$ojbXmlLib = new Sys_Publib_Xml();
		$ojbSysLib = new Sys_Library();
		$objFilter = new Zend_Filter();
		$ojbSysInitConfig = new Sys_Init_Config();	
		//Lay cac hang so dung chung
		$arrConst = Sys_Init_Config::_setProjectPublicConst();
		$this->view->arrConst = $arrConst;
		$this->view->currentModulCodeForLeft = "RECORD-ARCHIVES";
		//Lay tham so cau hinh
		$sysLibUrlPath = $ojbSysInitConfig->_setLibUrlPath();
		$url_path_calendar = $sysLibUrlPath . 'sys-calendar/';
		$this->view->urlCalendar = $url_path_calendar;
		//Lay ID cua NSD dang nhap hien thoi
		$StaffId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
		//Lay TEN cua NSD dang nhap hien thoi
		$sStaffName = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'name');
		$this->view->sStaffName = $sStaffName;
		//Lay CHUC VU phong ban cua NSD dang nhap hien thoi
		$sStaffPosition = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'position_code');	
		$this->view->sStaffPosition = $sStaffPosition;		
		//Lay thong tin tu danh muc
		$arrDocCate = $objRecordArchive->getPropertiesDocument('DM_LINH_VUC_VAN_BAN');
		$arrDocType = $objRecordArchive->getPropertiesDocument('DM_LOAI_VAN_BAN');
		$arrAgentcyGroupt = $objRecordArchive->getPropertiesDocument('DM_CAP_NOI_GUI_VAN_BAN');
		$arrAgentcyName   = $objRecordArchive->getPropertiesDocument('DM_NOI_GUI_VAN_BAN');
		//Lay cac hang so dung chung
		$arrCount = Sys_Init_Config::_setProjectPublicConst();
		$this->view->arrCount = $arrCount;
		//Goi ham thuc hien lay thong tin cho selectbox
		// Goi ham search lay ra loai van ban
		$this->view->search_textselectbox_doc_type = Sys_Function_DocFunctions::doc_search_ajax($arrDocType,"C_CODE","C_NAME","C_DOC_TYPE","hdn_doc_type",1,"",1);
		// Goi ham search lay ra nguoi ky
		$this->view->search_textselectbox_doc_cate = Sys_Function_DocFunctions::doc_search_ajax($arrDocCate,"C_CODE","C_NAME","C_DOC_CATE","hdn_doc_cate",1,"",1);
		// Goi ham search lay ra toan bo thong tin can bo nhan
		$this->view->search_textselectbox_agentcy_group = Sys_Function_DocFunctions::doc_search_ajax($arrAgentcyGroupt,"C_CODE","C_NAME","C_AGENTCY_GROUP","hdn_agentcy_groupt",1,"",1);
		// Goi ham search lay ra toan bo thong don vi, phong ban nhan
		$this->view->search_textselectbox_agentcy_name = Sys_Function_DocFunctions::doc_search_ajax($arrAgentcyName,"C_CODE","C_NAME","C_AGENTCY_NAME","hdn_agentcy_name",1,"",1);
		//Thuc hien upload file
		$arrFileNameUpload = $ojbSysLib->_uploadFileList(10,$this->_request->getBaseUrl() . "/public/attach-file/",'FileName','!#~$|*');	
		$this->view->AttachFile = $objDocFun->DocSentAttachFile(array(),0,10,true,39);	
		//Tuy chon ung voi cac truong hop update du lieu	
		$sOption = $this->_request->getParam('hdh_option','');
		$sRecordArchivedId = $this->_request->getParam('hdn_object_id','');
		$this->view->sRecordArchivedId = $sRecordArchivedId;
		$sDocRelateId = $this->_request->getParam('hdn_doc_id','');
		$this->view->sDocRelateId = $sDocRelateId;
		$this->view->option = $sOption;
		$arrParameter = array(	
								'PK_DOCUMENT_OTHER_RECORD'	=>'',
								'FK_RECORD'					=>$sRecordArchivedId,	
								'FK_CREATER'				=>$StaffId,			
								'C_CREATER_POSITION_NAME'	=>$sStaffPosition . ' - ' . $sStaffName,
								'C_CREATE_DATE'				=>Sys_Library::_ddmmyyyyToYYyymmdd($this->_request->getParam('C_CREATE_DATE','')),
								'C_DOC_TYPE'				=>$this->_request->getParam('C_DOC_TYPE',''),
								'C_SYMBOL'					=>$this->_request->getParam('C_SYMBOL',''),
								'C_DOC_CATE'				=>$this->_request->getParam('C_DOC_CATE',''),
								'C_SUBJECT'					=>$this->_request->getParam('C_SUBJECT',''),
								'C_AGENTCY_GROUP'			=>$this->_request->getParam('C_AGENTCY_GROUP',''),
								'C_AGENTCY_NAME'			=>$this->_request->getParam('C_AGENTCY_NAME',''),
								'NEW_FILE_ID_LIST'			=>$arrFileNameUpload,																	
							);	
		//Bien luu gia tri tra ve cua ham update ID cua van ban duoc THEM MOI hoac CHINH SUA
		$arrResult = "";
		if ($this->_request->getParam('C_SUBJECT','') != ""){
			
			$arrResult = $objRecordArchive->DocRecordArchiveDocOtherUpdate($arrParameter);
			//Truong hop ghi va them moi
			if ($sOption == "GHI_THEMMOI"){
				//Ghi va quay lai chinh form voi noi dung rong						
				$this->_redirect('record/archives/adddocother/hdn_object_id/'.$sRecordArchivedId.'?showModalDialog=1');
			}	
			//Truong hop ghi va them tiep
			if ($sOption == "GHI_THEMTIEP"){
				$this->view->option = $sOption;
				//Them van ban moi va giu lai noi dung thong tin tren form					
				$this->_redirect('record/archives/editdocother?hdn_doc_id=' . $arrResult['NEW_ID'] .'&showModalDialog=1&hdn_object_id='.$sRecordArchivedId);
			}
			//Truong hop ghi nhan
			if ($sOption == "GHI_TAM"){
				$this->view->option = $sOption;
				$this->view->sDocRelateId = $arrResult['NEW_ID'];
				//Ghi va quay lai chinh form voi noi dung rong						
				$this->_redirect('record/archives/editdocother?hdn_doc_id=' . $arrResult['NEW_ID'] . '&showModalDialog=1&hdn_object_id='.$sRecordArchivedId);
			}
			
		}	
	}
	public function editdocotherAction(){
		//An MeneLeft , MenuHeader , MenuFooter	
		$this->view->hideDisplayMeneLeft = ""; 
		$this->view->hideDisplayMenuHeader ="";
		$this->view->hideDisplayMenuFooter = "";
		$this->view->bodyTitle = 'HIỆU CHỈNH VĂN BẢN';
		$arrInput = $this->_request->getParams();
		$objDocFun = new Sys_Function_DocFunctions();
		$objRecordArchive = new record_modRecord();
		$ojbXmlLib = new Sys_Publib_Xml();
		$ojbSysLib = new Sys_Library();
		$objFilter = new Zend_Filter();
		$ojbSysInitConfig = new Sys_Init_Config();	
		//Lay cac hang so dung chung
		$arrConst = Sys_Init_Config::_setProjectPublicConst();
		$this->view->arrConst = $arrConst;
		$this->view->currentModulCodeForLeft = "RECORD-ARCHIVES";
		//Lay tham so cau hinh
		$sysLibUrlPath = $ojbSysInitConfig->_setLibUrlPath();
		$url_path_calendar = $sysLibUrlPath . 'sys-calendar/';
		$this->view->urlCalendar = $url_path_calendar;
		//Lay ID cua NSD dang nhap hien thoi
		$StaffId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
		//Lay TEN cua NSD dang nhap hien thoi
		$sStaffName = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'name');
		$this->view->sStaffName = $sStaffName;
		//Lay CHUC VU phong ban cua NSD dang nhap hien thoi
		$sStaffPosition = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'position_code');	
		$this->view->sStaffPosition = $sStaffPosition;		
		//Lay thong tin tu danh muc
		$arrDocCate = $objRecordArchive->getPropertiesDocument('DM_LINH_VUC_VAN_BAN');
		$arrDocType = $objRecordArchive->getPropertiesDocument('DM_LOAI_VAN_BAN');
		$arrAgentcyGroupt = $objRecordArchive->getPropertiesDocument('DM_CAP_NOI_GUI_VAN_BAN');
		$arrAgentcyName   = $objRecordArchive->getPropertiesDocument('DM_NOI_GUI_VAN_BAN');
		//Lay cac hang so dung chung
		$arrCount = Sys_Init_Config::_setProjectPublicConst();
		$this->view->arrCount = $arrCount;

		//Goi ham thuc hien lay thong tin cho selectbox
		// Goi ham search lay ra loai van ban
		$this->view->search_textselectbox_doc_type = Sys_Function_DocFunctions::doc_search_ajax($arrDocType,"C_CODE","C_NAME","C_DOC_TYPE","hdn_doc_type",1,"",1);
		// Goi ham search lay ra nguoi ky
		$this->view->search_textselectbox_doc_cate = Sys_Function_DocFunctions::doc_search_ajax($arrDocCate,"C_CODE","C_NAME","C_DOC_CATE","hdn_doc_cate",1,"",1);
		// Goi ham search lay ra toan bo thong tin can bo nhan
		$this->view->search_textselectbox_agentcy_group = Sys_Function_DocFunctions::doc_search_ajax($arrAgentcyGroupt,"C_CODE","C_NAME","C_AGENTCY_GROUP","hdn_agentcy_groupt",1,"",1);
		// Goi ham search lay ra toan bo thong don vi, phong ban nhan
		$this->view->search_textselectbox_agentcy_name = Sys_Function_DocFunctions::doc_search_ajax($arrAgentcyName,"C_CODE","C_NAME","C_AGENTCY_NAME","hdn_agentcy_name",1,"",1);
		//Tuy chon ung voi cac truong hop update du lieu	
		$sOption = $this->_request->getParam('hdh_option','');
		$sRecordArchivedId = $this->_request->getParam('hdn_object_id','');
		$sDocRelateId = $this->_request->getParam('hdn_doc_id','');
		$this->view->sDocRelateId = $sDocRelateId;
		$this->view->sRecordArchivedId = $sRecordArchivedId;
		//Mang luu thong tin chi tiet cua mot van ban
		$arrDocRelateSingle = $objRecordArchive->DocRecordArchiveDocOtherGetSingle($sRecordArchivedId,$sDocRelateId);
		$this->view->arrDocRelateSingle = $arrDocRelateSingle;
		$this->view->option = $sOption;
		//Lay thong tin file dinh kem
		$arrFileNameUpload = $ojbSysLib->_uploadFileList(10,$this->_request->getBaseUrl() . "/public/attach-file/",'FileName','!#~$|*');
		$sDocRelateIdTemp = $sDocRelateId;
		if($sOption == "GHI_THEMTIEP"){
			$sDocRelateIdTemp = "";
		}	
		//Lay file da dinh kem tu truoc
		if($sOption != "GHI_TAM"){
			$arFileAttach = $objRecordArchive->DOC_GetAllDocumentFileAttach($sDocRelateIdTemp,'','T_DOC_DOCUMENT_OTHER_RECORD');	
			$this->view->AttachFile = $objDocFun->DocSentAttachFile($arFileAttach,sizeof($arFileAttach),10,true,39);	
		}
		if($sDocRelateId != '' && $sDocRelateId != null  && $sOption != "QUAY_LAI"){
			if($sOption == "GHI_THEMTIEP"){
				$sDocRelateId = "";
			}
			$arrParameter = array(	
									'PK_DOCUMENT_OTHER_RECORD'	=>$sDocRelateId,
									'FK_RECORD'					=>$sRecordArchivedId,	
									'FK_CREATER'				=>$StaffId,			
									'C_CREATER_POSITION_NAME'	=>$sStaffPosition . ' - ' . $sStaffName,
									'C_CREATE_DATE'				=>Sys_Library::_ddmmyyyyToYYyymmdd($this->_request->getParam('C_CREATE_DATE','')),
									'C_DOC_TYPE'				=>$this->_request->getParam('C_DOC_TYPE',''),
									'C_SYMBOL'					=>$this->_request->getParam('C_SYMBOL',''),
									'C_DOC_CATE'				=>$this->_request->getParam('C_DOC_CATE',''),
									'C_SUBJECT'					=>$this->_request->getParam('C_SUBJECT',''),
									'C_AGENTCY_GROUP'			=>$this->_request->getParam('C_AGENTCY_GROUP',''),
									'C_AGENTCY_NAME'			=>$this->_request->getParam('C_AGENTCY_NAME',''),
									'NEW_FILE_ID_LIST'			=>$arrFileNameUpload,																	
								);	
			//Bien luu gia tri tra ve cua ham update ID cua van ban duoc THEM MOI hoac CHINH SUA
			$arrResult = "";
		}
		
		if ($this->_request->getParam('C_SUBJECT','') != ""){
			$arrResult = $objRecordArchive->DocRecordArchiveDocOtherUpdate($arrParameter);			
				//Truong hop ghi va them moi
				if ($sOption == "GHI_THEMMOI"){
					//Ghi va quay lai chinh form voi noi dung rong		
					$this->_redirect('record/archives/adddocother/?showModalDialog=1&hdn_object_id='.$sRecordArchivedId);
				}	
				//Truong hop ghi va them tiep
				if ($sOption == "GHI_THEMTIEP"){
					$this->view->option = $sOption;
					//Lay ID VB vua moi insert vao DB
					$this->view->sDocRelateId = $arrResult['NEW_ID'];
					//Lay thong tin van ban vua them moi va hien thi ra view
					$arrDocRelateSingle = $objRecordArchive->DocRecordArchiveDocOtherGetSingle($sRecordArchivedId, $arrResult['NEW_ID']);
					$this->view->arrDocRelateSingle = $arrDocRelateSingle;
				}
				//Truong hop ghi tam
				if ($sOption == "GHI_TAM"){
					//Lay ID VB vua moi insert vao DB
					$this->view->sDocRelateId = $arrResult['NEW_ID'];
					$this->view->option = $sOption;
					//Lay thong tin van ban vua them moi va hien thi ra view
					$arrDocRelateSingle = $objRecordArchive->DocRecordArchiveDocOtherGetSingle($sRecordArchivedId, $arrResult['NEW_ID']);
					$this->view->arrDocRelateSingle = $arrDocRelateSingle;
					//Lay file da dinh kem tu truoc
					$arFileAttach = $objRecordArchive->DOC_GetAllDocumentFileAttach($arrResult['NEW_ID'],'','T_DOC_DOCUMENT_OTHER_RECORD');	
					$this->view->AttachFile = $objDocFun->DocSentAttachFile($arFileAttach,sizeof($arFileAttach),10,true,39);
				}
		}
	}
	public function findrecordAction(){
		//Lay ID cua NSD dang nhap hien thoi
		$iCurrentStaffId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
		//Lay ID phong ban cua NSD dang nhap hien thoi
		$iCurrentUnitId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');
		$pUrl = $_SERVER['REQUEST_URI'];
		// Tieu de tim kiem
		$this->view->bodyTitleSearch = "DANH SÁCH HỒ SƠ LƯU TRỮ";				
		// Tieu de man hinh danh sach
		$this->view->bodyTitle = "DANH SÁCH HỒ SƠ LƯU TRỮ";
		//Bat dau lay vet tim kiem tu session
		$sfromDate = $this->_request->getParam('txtfromDate','');
		//if($sfromDate == '')
			//$sfromDate = '1/1/'.date("Y");
		$stoDate = $this->_request->getParam('txttoDate','');
		//if($stoDate == '')
			//$stoDate = date("d/m/Y");
		$sfullTextSearch = $this->_request->getParam('txtfullTextSearch','');
		$iCurrentPage = $this->_request->getParam('hdn_current_page',0);
		if($iCurrentPage < 1)
			$iCurrentPage = 1;
		$iNumRowOnPage = $this->_request->getParam('hdn_record_number_page',0);
		if($iNumRowOnPage == 0)
			$iNumRowOnPage = 15;
		//Neu ton tai gia tri tim kiem tron session thi lay trong session
		if(isset($_SESSION['seArrParameter'])){
			$Parameter 			= $_SESSION['seArrParameter'];
			$sfullTextSearch	= $Parameter['chuoiTimKiem'];
			$sfromDate			= $Parameter['tuNgay'];
			$stoDate			= $Parameter['denNgay'];
			$iCurrentPage		= $Parameter['trangHienThoi'];
			$iNumRowOnPage		= $Parameter['soBanGhiTrenTrang'];
			unset($_SESSION['seArrParameter']);
		}
		//Day cac gia tri tim kiem ra view
		$this->view->sFullTextSearch 	= $sfullTextSearch;
		$this->view->fromDate 			= $sfromDate;
		$this->view->toDate				= $stoDate;
		$this->view->iCurrentPage 		= $iCurrentPage;
		$this->view->iNumRowOnPage 		= $iNumRowOnPage;
		//Lay cac hang so dung chung
		$arrConst = Sys_Init_Config::_setProjectPublicConst();
		$this->view->arrConst = $arrConst;
		// Tao doi tuong Zend_Filter
		$objFilter = new Zend_Filter();
		$ojbSysLib = new Sys_Library();
		// Lay toan bo tham so truyen tu form			
		$arrInput = $this->_request->getParams();
		$objRecordArchive = new record_modRecord();
		//Lay MA DON VI NSD dang nhap hien thoi
		$sOwnerCode = $_SESSION['OWNER_CODE'];
		$arrResul = $objRecordArchive->DocRecordArchivesStaffGetAll($iCurrentStaffId ,$_SESSION['OWNER_ID'], $iCurrentUnitId, $sfullTextSearch, $iCurrentPage, $iNumRowOnPage, Sys_Library::_ddmmyyyyToYYyymmdd($sfromDate), Sys_Library::_ddmmyyyyToYYyymmdd($stoDate));			
		$iNumberRecord = $arrResul[0]['C_TOTAL_RECORD'];	
		$sdocpertotal ="Danh sách này không có văn bản nào";
		//Hien thi thong tin man hinh danh sach nay co bao nhieu ban ghi va hien thi Radio "Chon tat ca"; "Bo chon tat ca"
		$this->view->SelectDeselectAll = Sys_Publib_Library::_selectDeselectAll(sizeof($arrResul), $iNumberRecord);
		if (count($arrResul) > 0){
			$this->view->sdocpertotal = "Danh sách có: ".sizeof($arrResul).'/'.$iNumberRecord." hồ sơ";
			//Sinh xau HTML mo ta so trang (Trang 1; Trang 2;...)
			$this->view->generateStringNumberPage = Sys_Publib_Library::_generateStringNumberPage($iNumberRecord, $iCurrentPage, $iNumRowOnPage,$pUrl) ;
			//Sinh chuoi HTML mo ta tong so trang (Trang 1; Trang 2;...) va quy dinh so record/page
			$this->view->generateHtmlSelectBoxPage = Sys_Publib_Library::_generateChangeRecordNumberPage($iNumRowOnPage,"index");
		}
		$this->view->arrResul = $arrResul;
		$this->view->NumberRowOnPage 	= $this->_ConstPublic['NumberRowOnPage'];	
		$this->view->iCountElement = count($this->view->arrAllRecord);
	}
}?>
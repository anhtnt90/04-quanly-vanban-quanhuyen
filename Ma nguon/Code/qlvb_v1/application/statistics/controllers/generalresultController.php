<?php
class statistics_generalresultController extends  Zend_Controller_Action {
	public $_publicPermission;
	public function init(){	
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
		//Goi lop modReceived
		Zend_Loader::loadClass('statistics_modRop');
		
		$this->view->JSPublicConst = $objConfig->_setJavaScriptPublicVariable();		
		// Load tat ca cac file Js va Css
		$JSandStyle = Sys_Publib_Library::_getAllFileJavaScriptCss('','js','Rop.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','ajax.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','jquery-1.4.2.min.js,jQuery.equalHeights.js',',','js'). Sys_Publib_Library::_getAllFileJavaScriptCss('','js/LibSearch','actb_search.js,common_search.js',',','js');		
		$JSandStyle.= Sys_Publib_Library::_getAllFileJavaScriptCss('','js','ui/i18n/jquery.ui.datepicker-vi.js',',','js');
		$JSandStyle.= Sys_Publib_Library::_getAllFileJavaScriptCss('','js','ui/jquery-ui-1.8.14.custom.min.js',',','js');
		$JSandStyle.= Sys_Publib_Library::_getAllFileJavaScriptCss('','style','themes/redmond/jquery-ui-1.8.15.custom.css',',','css');
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
		$this->view->currentModulCode = "ROP";
		$this->view->currentModulCodeForLeft = "GENERAL-RESULT-DOC";
		//Lay Quyen cap nhat VB DEN
		//$this->_publicPermission = Sys_Function_DocFunctions::Doc_ArchivesStaff($_SESSION['arrStaffPermission'],$_SESSION['staff_id']);
	
		$response->insert('header', $this->view->renderLayout('twd_header.phtml','template/'));    
		$response->insert('left', $this->view->renderLayout('twd_left.phtml','template/'));  	    
        $response->insert('footer', $this->view->renderLayout('twd_footer.phtml','template/'));
  	}	
	/**
	 * Idea : Phuong thuc hien thi danh sach
	 *
	 */
	public function indexAction(){
		//Lay ID cua NSD dang nhap hien thoi
		//$iUnitId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');
		//Lay ID phong ban cua NSD dang nhap hien thoi
		$pUrl = $_SERVER['REQUEST_URI'];
		$objFunction =	new	Sys_Function_DocFunctions()	;
		$objFilter = new Zend_Filter();			
		// Tieu de man hinh danh sach
		$this->view->bodyTitle = "TỔNG HỢP KẾT QUẢ XỬ LÝ VĂN BẢN ĐẾN";
		$sFromDate				= $this->_request->getParam('fromDate','');
		$sToDate				= $this->_request->getParam('toDate','');
		If($sFromDate == '')
			$sFromDate 			= '01/01'.'/'.date('Y');
		If ($sToDate == '')
			$sToDate			= date('d/m/Y');
		//Neu ton tai gia tri tim kiem tron session thi lay trong session
		if(isset($_SESSION['seArrParameter'])){
			$Parameter 			= $_SESSION['seArrParameter'];
			$sFromDate			= $Parameter['tuNgay'];
			$sToDate			= $Parameter['denNgay'];
			unset($_SESSION['seArrParameter']);
		}
		//Day gia tri tim kiem ra view
		$this->view->fromDate 		 = $sFromDate;
		$this->view->toDate 		 = $sToDate;
		//Lay cac hang so dung chung
		$arrConst = Sys_Init_Config::_setProjectPublicConst();
		$this->view->arrConst = $arrConst;
		// Tao doi tuong Zend_Filter
		$objFilter = new Zend_Filter();
		$ojbSysLib = new Sys_Library();
		// Lay toan bo tham so truyen tu form			
		$arrInput = $this->_request->getParams();
		$objReceive = new statistics_modRop();
		//Lay thong tin trong danh muc
		$arrLoaiVB = $objReceive->getPropertiesDocument('DM_LOAI_VAN_BAN','','');
		$arrDocCate = $objReceive->getPropertiesDocument('DM_LINH_VUC_VAN_BAN','','');
		$arrLeader = $objFunction->docGetAllUnitLeader('LANH_DAO_UB','arr_all_staff_keep');
		// Goi ham search de hien thi ra Complete Textbox
		$arrResul = $objReceive->DocRopGeneralResultReceivedGetAll($_SESSION['OWNER_ID'],Sys_Library::_ddmmyyyyToYYyymmdd($sFromDate), Sys_Library::_ddmmyyyyToYYyymmdd($sToDate));			
		$iNumberRecord = $arrResul[0]['C_TOTAL_RECORD'];	
		$this->view->arrResul = $arrResul;
		$sdocpertotal ="Danh sách này không có văn bản nào";
		//Hien thi thong tin man hinh danh sach nay co bao nhieu ban ghi va hien thi Radio "Chon tat ca"; "Bo chon tat ca"
	/*	$this->view->SelectDeselectAll = Sys_Publib_Library::_selectDeselectAll(sizeof($arrResul), $iNumberRecord);
		if (count($arrResul) > 0){
			$this->view->sdocpertotal = "Danh sách có: ".sizeof($arrResul).'/'.$iNumberRecord." văn bản";
			//Sinh xau HTML mo ta so trang (Trang 1; Trang 2;...)
			$this->view->generateStringNumberPage = Sys_Publib_Library::_generateStringNumberPage($iNumberRecord, $iCurrentPage, $iNumRowOnPage,$pUrl) ;
			//Sinh chuoi HTML mo ta tong so trang (Trang 1; Trang 2;...) va quy dinh so record/page
			$this->view->generateHtmlSelectBoxPage = Sys_Publib_Library::_generateChangeRecordNumberPage($iNumRowOnPage,$this->view->getStatusLeftMenu);
		}
		$this->view->NumberRowOnPage 	= $this->_ConstPublic['NumberRowOnPage'];	
		$this->view->iCountElement = count($this->view->arrAllRecord);	
	*/
	}
	function viewAction(){
		//Lay ID cua NSD dang nhap hien thoi
		//$iUnitId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');
		//Lay ID phong ban cua NSD dang nhap hien thoi
		$pUrl = $_SERVER['REQUEST_URI'];
		$objFunction =	new	Sys_Function_DocFunctions()	;
		$objFilter = new Zend_Filter();			
		// Tieu de man hinh danh sach
		$this->view->unitInfo 		= "THÔNG TIN ĐƠN VỊ XỬ LÝ";
		$this->view->resultProcess 	= "KẾT QUẢ XỬ LÝ VĂN BẢN";
		$sFromDate				= $this->_request->getParam('fromDate','');
		$sToDate				= $this->_request->getParam('toDate','');
		$iUnitId				= $this->_request->getParam('hdn_unit_id','');
		$sStatus				= $this->_request->getParam('hdn_status','');
		If($sFromDate == '')
			$sFromDate 			= '01/01'.'/'.date('Y');
		If ($sToDate == '')
			$sToDate			= date('d/m/Y');
		$sfullTextSearch 	= trim($this->_request->getParam('txtfullTextSearch',''));
		$iCurrentPage		= $this->_request->getParam('hdn_current_page',0);		
		if ($iCurrentPage <= 1){
			$iCurrentPage = 1;
		}
		$iNumRowOnPage = $this->_request->getParam('cbo_nuber_record_page',0);
		if ($iNumRowOnPage == 0)
			$iNumRowOnPage = 15;
		//Neu ton tai gia tri tim kiem tron session thi lay trong session
		if(isset($_SESSION['seArrParameter'])){
			$Parameter 			= $_SESSION['seArrParameter'];
			$iCurrentPage		= $Parameter['trangHienThoi'];
			$sfullTextSearch	= $Parameter['chuoiTimKiem'];
			$sDocType 			= $Parameter['loaiVanBan'];
			$iLeaderId			= $Parameter['lanhdaogiaoviec'];
			$iUnitId 			= $Parameter['donvixuly'];
			$sStatus			= $Parameter['trangthai'];
			$iNumRowOnPage 		= $Parameter['soBanGhiTrenTrang'];
			unset($_SESSION['seArrParameter']);
		}
		//Day gia tri tim kiem ra view
		$this->view->sfullTextSearch = $sfullTextSearch;
		$this->view->iUnitId 		 = $iUnitId;
		$this->view->sStatus		 = $sStatus;
		$this->view->iCurrentPage 	 = $iCurrentPage;
		$this->view->iNumRowOnPage 	 = $iNumRowOnPage;
		$this->view->fromDate 		 = $sFromDate;
		$this->view->toDate 		 = $sToDate;
		//Lay cac hang so dung chung
		$arrConst = Sys_Init_Config::_setProjectPublicConst();
		$this->view->arrConst = $arrConst;
		// Tao doi tuong Zend_Filter
		$objFilter = new Zend_Filter();
		$ojbSysLib = new Sys_Library();
		// Lay toan bo tham so truyen tu form			
		$arrInput = $this->_request->getParams();
		$objReceive = new statistics_modRop();
		//Lay thong tin trong danh muc
		$arrResul = $objReceive->DocRopResultProcessUnitByStatusGetAll($_SESSION['OWNER_ID'], $iUnitId, $sfullTextSearch, Sys_Library::_ddmmyyyyToYYyymmdd($sFromDate), Sys_Library::_ddmmyyyyToYYyymmdd($sToDate), $sStatus, $iCurrentPage, $iNumRowOnPage);			
		$iNumberRecord = $arrResul[0]['C_TOTAL_RECORD'];	
		$sdocpertotal ="Danh sách này không có văn bản nào";
		//Hien thi thong tin man hinh danh sach nay co bao nhieu ban ghi va hien thi Radio "Chon tat ca"; "Bo chon tat ca"
		$this->view->SelectDeselectAll = Sys_Publib_Library::_selectDeselectAll(sizeof($arrResul), $iNumberRecord);
		if (count($arrResul) > 0){
			$this->view->sdocpertotal = "Danh sách có: ".sizeof($arrResul).'/'.$iNumberRecord." văn bản";
			//Sinh xau HTML mo ta so trang (Trang 1; Trang 2;...)
			$this->view->generateStringNumberPage = Sys_Publib_Library::_generateStringNumberPage($iNumberRecord, $iCurrentPage, $iNumRowOnPage,$pUrl) ;
			//Sinh chuoi HTML mo ta tong so trang (Trang 1; Trang 2;...) va quy dinh so record/page
			$this->view->generateHtmlSelectBoxPage = Sys_Publib_Library::_generateChangeRecordNumberPage($iNumRowOnPage,$this->view->getStatusLeftMenu);
		}
		$this->view->arrResul = $arrResul;
		$this->view->NumberRowOnPage 	= $this->_ConstPublic['NumberRowOnPage'];	
		$this->view->iCountElement = count($this->view->arrAllRecord);	
	}
	public function printresultAction(){
		$objFunction =	new	Sys_Function_DocFunctions()	;
		//Lay duong dan
		$path = $_SERVER['SCRIPT_FILENAME'];
		$path = substr($path, 0, -9);
		$my_report = str_replace("/", "\\", $path) . "rpt\\rop\\GeneralResult.rpt";
		// Tao doi tuong Crystal 9
		$COM_Object = "CrystalDesignRunTime.Application.9";		
		$crapp= new COM($COM_Object) or die("Unable to Create Object");
		$creport = $crapp->OpenReport($my_report, 1);	
		//Lay cac tham so tren form
		$sfromDate			= $this->_request->getParam('hdn_from_date','');
		$stoDate			= $this->_request->getParam('hdn_to_date','');
		$sFromDate 			= Sys_Library::_ddmmyyyyToYYyymmdd($sfromDate);
		$sToDate 			= Sys_Library::_ddmmyyyyToYYyymmdd($stoDate);
		$iTongSoVB			= $this->_request->getParam('hdn_tongsovb','');
		$iSumDangXuLy		= $this->_request->getParam('hdn_dangxuly','');
		$iSumDaXuLyDungHan	= $this->_request->getParam('hdn_daxulydunghan','');
		$iSumDaXuLyQuaHan	= $this->_request->getParam('hdn_daxulyquahan','');
		$iSumQuaHanChuaXuLy	= $this->_request->getParam('hdn_quahanchuaxuly','');		
		$connectSQL = new Zend_Config_Ini('./config/config.ini','dbmssql');
		$arrConn = $connectSQL->db->config->toArray();
		$creport->Database->Tables(1)->SetLogOnInfo($arrConn['host'], $arrConn['dbname'], $arrConn['username'], $arrConn['password']);
		$creport->EnableParameterPrompting = 0;
		//echo $sadvandeSearch.'-'.$iLeaderId.'-'.$iUnitId.'-'.$sStatus.'-'.$sDocType.'-'.$sFromDate.'-'.$sToDate.'-'.$sfullTextSearch; exit;
		//echo $sRecordArchivedId; exit;
		$creport->ReadRecords();
		$z = $creport->ParameterFields(1)->SetCurrentValue($sfromDate);
		$z = $creport->ParameterFields(2)->SetCurrentValue($stoDate);
		$z = $creport->ParameterFields(3)->SetCurrentValue((int)$iTongSoVB);
		$z = $creport->ParameterFields(4)->SetCurrentValue((int)$iSumDangXuLy);
		$z = $creport->ParameterFields(5)->SetCurrentValue((int)$iSumDaXuLyDungHan);
		$z = $creport->ParameterFields(6)->SetCurrentValue((int)$iSumDaXuLyQuaHan);
		$z = $creport->ParameterFields(7)->SetCurrentValue((int)$iSumQuaHanChuaXuLy);
		$z = $creport->ParameterFields(8)->SetCurrentValue((int)$_SESSION['OWNER_ID']);
		$z = $creport->ParameterFields(9)->SetCurrentValue($sFromDate);
		$z = $creport->ParameterFields(10)->SetCurrentValue($sToDate);
		// Dinh dang file report ket xuat
		$report_file = 'RopReceived' . mt_rand(1,1000000) . '.doc';
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
	public function printresultunitAction(){
		$objFunction =	new	Sys_Function_DocFunctions()	;
		$arrConst = Sys_Init_Config::_setProjectPublicConst();
		//Lay duong dan
		$path = $_SERVER['SCRIPT_FILENAME'];
		$path = substr($path, 0, -9);
		$my_report = str_replace("/", "\\", $path) . "rpt\\rop\\ResulUnit.rpt";
		// Tao doi tuong Crystal 9
		$COM_Object = "CrystalDesignRunTime.Application.9";		
		$crapp= new COM($COM_Object) or die("Unable to Create Object");
		$creport = $crapp->OpenReport($my_report, 1);	
		//Lay cac tham so tren form
		$sfromDate			= $this->_request->getParam('hdn_from_date','');
		$stoDate			= $this->_request->getParam('hdn_to_date','');
		$sFromDate 			= Sys_Library::_ddmmyyyyToYYyymmdd($sfromDate);
		$sToDate 			= Sys_Library::_ddmmyyyyToYYyymmdd($stoDate);
		$sfullTextSearch	= $this->_request->getParam('txt_fullTextSearch','');
		$sStatus			= $this->_request->getParam('hdn_status','');
		$iUnitId			= $this->_request->getParam('hdn_unit_id','');
		$sUnitName			= $objFunction->getNameUnitByIdUnitList($iUnitId,'');
		$strangthai			= $arrConst['_'.$sStatus]; 
		// Truyen tham so vao
		//Ket noi CSDL SQL theo kieu ADODB
		$connectSQL = new Zend_Config_Ini('./config/config.ini','dbmssql');
		$arrConn = $connectSQL->db->config->toArray();
		$creport->Database->Tables(1)->SetLogOnInfo($arrConn['host'], $arrConn['dbname'], $arrConn['username'], $arrConn['password']);
		$creport->EnableParameterPrompting = 0;
		//echo $sadvandeSearch.'-'.$iLeaderId.'-'.$iUnitId.'-'.$sStatus.'-'.$sDocType.'-'.$sFromDate.'-'.$sToDate.'-'.$sfullTextSearch; exit;
		//echo $sRecordArchivedId; exit;
		$creport->ReadRecords();
		$z = $creport->ParameterFields(1)->SetCurrentValue($strangthai);
		$z = $creport->ParameterFields(2)->SetCurrentValue($sfromDate);
		$z = $creport->ParameterFields(3)->SetCurrentValue($stoDate);
		$z = $creport->ParameterFields(4)->SetCurrentValue($sUnitName);
		$z = $creport->ParameterFields(5)->SetCurrentValue((int)$_SESSION['OWNER_ID']);
		$z = $creport->ParameterFields(6)->SetCurrentValue((int)$iUnitId);
		$z = $creport->ParameterFields(7)->SetCurrentValue($sfullTextSearch);
		$z = $creport->ParameterFields(8)->SetCurrentValue($sFromDate);
		$z = $creport->ParameterFields(9)->SetCurrentValue($sToDate);
		$z = $creport->ParameterFields(10)->SetCurrentValue($sStatus);
		$z = $creport->ParameterFields(11)->SetCurrentValue(1);
		$z = $creport->ParameterFields(12)->SetCurrentValue(9999);
		$z = $creport->ParameterFields(13)->SetCurrentValue(0);
		// Dinh dang file report ket xuat
		$report_file = 'RopReceived' . mt_rand(1,1000000) . '.doc';
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
}?>
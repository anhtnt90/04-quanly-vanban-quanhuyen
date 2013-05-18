<?php
/**
 * Nguoi tao: phuongtt
 * Ngay tao: 11/09/2010
 * Y nghia: Class Xu ly LAP CONG VIEC dien tu
 */	
class rop_workController extends  Zend_Controller_Action {
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
		//Goi lop modSendReceived
		Zend_Loader::loadClass('rop_modRop');
		
		
		$this->view->JSPublicConst = $objConfig->_setJavaScriptPublicVariable();		
		
		// Load tat ca cac file Js va Css
		$JSandStyle = Sys_Publib_Library::_getAllFileJavaScriptCss('','sys-js','Rop.js,js_calendar.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','sys-js','ajax.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','sys-js','jquery-1.4.2.min.js,jQuery.equalHeights.js',',','js'). Sys_Publib_Library::_getAllFileJavaScriptCss('','sys-js/LibSearch','actb_search.js,common_search.js',',','js');
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
		$this->view->currentModulCode = "ROP";
		//Lay Quyen cap nhat VB dien tu
		//$this->_publicPermission = Sys_Function_DocFunctions::Doc_ArchivesStaff($_SESSION['arrStaffPermission'],$_SESSION['staff_id']);
		//echo $this->_publicPermission;
		$psshowModalDialog = $this->_request->getParam('showModalDialog',"");
		$this->view->currentModulCodeForLeft = 'WORK-DOC';
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
		$objFunction =	new	Sys_Function_DocFunctions()	;
		$ojbSysInitConfig = new Sys_Init_Config();	
		// Tieu de tim kiem
		$this->view->bodyTitleSearch = "DANH SÁCH CÔNG VIỆC";				
		// Tieu de man hinh danh sach
		$this->view->bodyTitle = "DANH SÁCH CÔNG VIỆC";
		$sadvandeSearch 	= $this->_request->getParam('hdn_advande_search','');
		/*
		$iOwnerId = $_SESSION['OWNER_ID'];
		if($_SESSION['OWNER_ID'] != Sys_Init_Config::_setParentOwnerId())
				$arrLeader = Sys_Function_DocFunctions::docGetAllUnitLeader('LANH_DAO_PHUONG','arr_all_staff');
		else 	$arrLeader = Sys_Function_DocFunctions::docGetAllUnitLeader('LANH_DAO_UB','arr_all_staff');
		*/
		$arrPositionConst =	$ojbSysInitConfig->_setLeaderPostionGroup();
		$arrLeader = $objFunction->docGetAllUnitLeader($arrPositionConst['_CONST_POSITION_GROUP'],'arr_all_staff');
		$this->view->arrLeader = $arrLeader;
		//
		if(isset($_SESSION['seArrParameter'])){
			$sadvandeSearch = $_SESSION['seArrParameter']['timkiemnangcao'];
		}
		if($sadvandeSearch == 'yes'){
			$iLeaderId	 		= $objFunction->convertStaffNameToStaffId($this->_request->getParam('C_LEADER',''));
			$iUnitId 			= $objFunction->convertUnitNameListToUnitIdList($this->_request->getParam('C_UNIT_ID',''));
			$sStatus			= $this->_request->getParam('C_STATUS','');
		}else{
			$iLeaderId = ''; $iUnitId = ''; $sStatus = '';
			for ($i = 0; $i < sizeof($arrLeader); $i++){
				if(in_array($_SESSION['staff_id'],$arrLeader[$i]))
					$iLeaderId = $_SESSION['staff_id'];
			}
		}
		$sFromDate				= $this->_request->getParam('fromDate','');
		$sToDate				= $this->_request->getParam('toDate','');
		If($sFromDate == '')
			$sFromDate 			= '01/01/'.date('Y');
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
			$iLeaderId			= $Parameter['lanhdaogiaoviec'];
			$iUnitId 			= $Parameter['donvixuly'];
			$sStatus			= $Parameter['trangthai'];
			$iNumRowOnPage 		= $Parameter['soBanGhiTrenTrang'];
			$sFromDate			= $Parameter['tungay'];
			$sToDate			= $Parameter['denngay'];
			unset($_SESSION['seArrParameter']);
		}
		if($_SESSION['arrStaffPermission']['THEO_DOI_CONG_VIEC_PHONG_BAN'] && !$_SESSION['arrStaffPermission']['THEO_DOI_CONG_VIEC_DON_VI']){
			$iUnitId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');
		}
		//Day gia tri tim kiem ra view
		$this->view->sfullTextSearch = $sfullTextSearch;
		$this->view->iLeaderId 		 = $iLeaderId;
		$this->view->iUnitId 		 = $iUnitId;
		$this->view->sStatus		 = $sStatus;
		$this->view->iCurrentPage 	 = $iCurrentPage;
		$this->view->iNumRowOnPage 	 = $iNumRowOnPage;
		$this->view->fromDate 		 = $sFromDate;
		$this->view->toDate 		 = $sToDate;
		$this->view->sadvandeSearch  = $sadvandeSearch;
		$this->view->sStatus		 = $sStatus;
		
		//Lay cac hang so dung chung
		$arrConst = Sys_Init_Config::_setProjectPublicConst();
		$this->view->arrConst = $arrConst;
		// Tao doi tuong Zend_Filter
		$objFilter = new Zend_Filter();
		$ojbSysLib = new Sys_Library();
		// Lay toan bo tham so truyen tu form			
		$arrInput = $this->_request->getParams();
		$objWork = new Rop_modRop();
		
		$this->view->search_textselectbox_leader = Sys_Function_DocFunctions::doc_search_ajax($arrLeader,"id","name","C_LEADER","hdn_leader_id",1,'position_code',1);
		$this->view->search_textselectbox_unit = Sys_Function_DocFunctions::doc_search_ajax($_SESSION['arr_all_unit'],"id","name","C_UNIT_ID","hdn_unit_id");
		$this->view->arrLeader = $arrLeader;
		$arrResul = $objWork->DocDocRopWorkGetAll($iOwnerId, '', $iLeaderId, $sStatus, Sys_Library::_ddmmyyyyToYYyymmdd($sFromDate),Sys_Library::_ddmmyyyyToYYyymmdd($sToDate), $sfullTextSearch, $iCurrentPage, $iNumRowOnPage, $iUnitId);			
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
	
	function viewAction(){
		$this->view->bodyTitle = '';
		$ojbSysLib = new Sys_Library();
		$objDocFun = new Sys_Function_DocFunctions();
		$this->view->infowork 	 	= 'THÔNG TIN CÔNG VIỆC';
		$this->view->infoprocess 	= 'QUÁ TRÌNH XỬ LÝ CÔNG VIỆC';
		// Tao doi tuong cho lop xu ly du lieu lien quan modul	
		$objWork = new Rop_modRop();
		$ojbSysInitConfig = new Sys_Init_Config();	
		//Lay id van ban tu view
		$sWorkId = $this->_request->getParam('hdn_object_id','');		
		$this->view->sWorkId = $sWorkId;
		//Mang luu thong tin chi tiet cua mot cong viec
		$arrWork = $objWork->DocRopWorkGetSingle($sWorkId);
		$this->view->arrWork = $arrWork;
		//Lay toan bo thong tin qua trinh xu ly cua mot cong viec
		$arrProcesResultAll = $objWork->DocRopWorkProcessResultGetAll($sWorkId);
		$this->view->arrProcesResultAll = $arrProcesResultAll;
		$this->view->arrConst =	$ojbSysInitConfig->_setProjectPublicConst();
		$sSubModulLeft = $this->_request->getParam('hdn_subModulLeft','');	
		$this->view->getStatusLeftMenu = $sSubModulLeft;
		//Lay gia tri tim kiem tren form
			$sfullTextSearch 	= $this->_request->getParam('txtfullTextSearch','');
			$iUnitId 			= $objDocFun->convertUnitNameListToUnitIdList($this->_request->getParam('C_UNIT_ID',''));
			$iLeaderId	 		= $objDocFun->convertStaffNameToStaffId($this->_request->getParam('C_LEADER',''));
			$iCurrentPage		= $this->_request->getParam('hdn_current_page',0);	
			$sadvandeSearch 	= $this->_request->getParam('hdn_advande_search','');
			$sFromDate			= $this->_request->getParam('fromDate','');
			$sToDate			= $this->_request->getParam('toDate','');
			$sStatus			= $this->_request->getParam('C_STATUS','');
			if ($iCurrentPage <= 1){
				$iCurrentPage = 1;
			}
			$iNumRowOnPage = $this->_request->getParam('cbo_nuber_record_page',0);
			if ($iNumRowOnPage == 0)
				$iNumRowOnPage = 15;
			$arrParaSet = array("trangthai"=>$sStatus,"trangHienThoi"=>$iCurrentPage, "soBanGhiTrenTrang"=>$iNumRowOnPage,"linhvucvanban"=>$sDocCate,"donvixuly"=>$iUnitId,"chuoiTimKiem"=>$sfullTextSearch,"timkiemnangcao"=>$sadvandeSearch,"lanhdaogiaoviec"=>$iLeaderId,"tungay"=>$sFromDate,"denngay"=>$sToDate);
			$_SESSION['seArrParameter'] = $arrParaSet;	
	}
	public function printAction(){
		$objFunction =	new	Sys_Function_DocFunctions()	;
		$arrConst = Sys_Init_Config::_setProjectPublicConst();
		//Lay duong dan
		$path = $_SERVER['SCRIPT_FILENAME'];
		$path = substr($path, 0, -9);
		$my_report = str_replace("/", "\\", $path) . "rpt\\rop\\ListWork.rpt";
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
		$iUnitId			= $this->_request->getParam('hdn_unit_id','');
		$StaffId			= Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
		$sStaffName 		= Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'name');
		$sStaffPosition 	= Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'position_name');	
		$sUnitName			= Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_unit'],$iUnitId,'name');
		// Truyen tham so vao
		//Ket noi CSDL SQL theo kieu ADODB
		$connectSQL = new Zend_Config_Ini('./config/config.ini','dbmssql');
		$arrConn = $connectSQL->db->config->toArray();
		$creport->Database->Tables(1)->SetLogOnInfo($arrConn['host'], $arrConn['dbname'], $arrConn['username'], $arrConn['password']);
		$creport->EnableParameterPrompting = 0;
		//echo (int)$iLeaderId; exit;
		$creport->ReadRecords();
		$z = $creport->ParameterFields(1)->SetCurrentValue('Từ ngày: '.$sfromDate.'&nbsp;&nbsp;&nbsp;&nbsp;Đến ngày: '.$stoDate);
		$z = $creport->ParameterFields(2)->SetCurrentValue($sUnitName);
		$z = $creport->ParameterFields(3)->SetCurrentValue((int)$_SESSION['OWNER_ID']);
		//$z = $creport->ParameterFields(4)->SetCurrentValue((int)$_SESSION['staff_id']);
		$z = $creport->ParameterFields(5)->SetCurrentValue((string)$iLeaderId);
		$z = $creport->ParameterFields(6)->SetCurrentValue($sStatus);
		$z = $creport->ParameterFields(7)->SetCurrentValue($sFromDate);
		$z = $creport->ParameterFields(8)->SetCurrentValue($sToDate);
		$z = $creport->ParameterFields(9)->SetCurrentValue($sfullTextSearch);
		$z = $creport->ParameterFields(10)->SetCurrentValue(1);
		$z = $creport->ParameterFields(11)->SetCurrentValue(9999);
		$z = $creport->ParameterFields(12)->SetCurrentValue((int)$iUnitId);
		//echo (int)$_SESSION['OWNER_ID'].(int)$_SESSION['staff_id'].'-'.$iLeaderId.'-'.$sStatus.'-'.$sDocType.'-'.$sFromDate.'-'.$sToDate.'-'.$sfullTextSearch; exit;
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
}?>
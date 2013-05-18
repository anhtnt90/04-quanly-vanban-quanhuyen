<?php
class statistics_receivedController extends  Zend_Controller_Action {
	
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
		if ($sGetValueInCookie == "" || is_null($sGetValueInCookie) || !isset($sGetValueInCookie)){
			Sys_Library::_createCookie("showHideMenu",1);				
			$this->view->hideDisplayMeneLeft = 1;					
		}else{			
			if ($sGetValueInCookie != 0){
				$this->view->hideDisplayMeneLeft = 1;// = 1 : hien thi menu
			}else{
				$this->view->hideDisplayMeneLeft = "";// = "" : an menu
			}
			$this->view->ShowHideimageUrlPath = Sys_Library::_getCookie("ImageUrlPath");
		}		
		$this->view->InforStaff = Sys_Publib_Library::_InforStaff();		
		$this->view->currentModulCode = "ROP";
		$this->view->currentModulCodeForLeft = "DOCUMENT-RECEIVED-DOC";		
		$response->insert('header', $this->view->renderLayout('twd_header.phtml','template/'));    
		$response->insert('left', $this->view->renderLayout('twd_left.phtml','template/'));  	    
        $response->insert('footer', $this->view->renderLayout('twd_footer.phtml','template/'));
  	}		
	public function indexAction(){		
		//Lay ID cua NSD dang nhap hien thoi
		//$iUnitId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');
		//Lay ID phong ban cua NSD dang nhap hien thoi
		$pUrl = $_SERVER['REQUEST_URI'];
		$objFunction =	new	Sys_Function_DocFunctions()	;
		$objFilter = new Zend_Filter();			
		// Tieu de man hinh danh sach
		$this->view->bodyTitle = "TỔNG HỢP THEO DÕI KQXL VĂN BẢN ĐẾN";
		$sadvandeSearch 	= $this->_request->getParam('hdn_advande_search','');
		if(isset($_SESSION['seArrParameter'])){
			$sadvandeSearch = $_SESSION['seArrParameter']['timkiemnangcao'];
		}
		if($sadvandeSearch == 'yes'){
			$sDocType 			= trim($this->_request->getParam('C_DOC_TYPE',''));
			$iLeaderId	 		= $objFunction->convertStaffNameToStaffId($this->_request->getParam('C_LEADER_ID',''));
			$iUnitId 			= $objFunction->convertUnitNameListToUnitIdList($this->_request->getParam('C_UNIT_ID',''));
			$sStatus			= $this->_request->getParam('C_STATUS','');
		}else{
			$sDocType = ''; $iLeaderId = ''; $iUnitId = ''; $sStatus = '';
		}
		$sFromDate				= $this->_request->getParam('fromDate','');
		$sToDate				= $this->_request->getParam('toDate','');
		//If($sFromDate == '')
			//$sFromDate 			= '01/'.date('m').'/'.date('Y');
		//If ($sToDate == '')
			//$sToDate			= date('d/m/Y');
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
			$sFromDate			= $Parameter['tungay'];
			$sToDate			= $Parameter['denngay'];
			unset($_SESSION['seArrParameter']);
		}
		if($_SESSION['arrStaffPermission']['THEO_DOI_KQXL_VB_DEN_PB'] == 1 || $_SESSION['arrStaffPermission']['THEODOI_XLVBDEN_PX'] == 1){
			$iUnitId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');
		}
		if($_SESSION['arrStaffPermission']['THEO_DOI_KQXL_VB_DEN_PB'] != 1 && $_SESSION['arrStaffPermission']['THEO_DOI_KQXL_VBDEN'] != 1 && $_SESSION['arrStaffPermission']['THEODOI_XLVBDEN_PX'] != 1){
			$iUnitId = 9999;
		}
		//Day gia tri tim kiem ra view
		$sfullTextSearch = Sys_Publib_Library::_replaceBadChar($sfullTextSearch);
		$this->view->sfullTextSearch = $sfullTextSearch;
		$this->view->sDocType 		 = $sDocType;
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
		
		$ojbSysInitConfig = new Sys_Init_Config();	
		$arrConst = $ojbSysInitConfig->_setProjectPublicConst();
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
		$arrPositionConst =	$ojbSysInitConfig->_setLeaderPostionGroup();
		$arrLeader = $objFunction->docGetAllUnitLeader($arrPositionConst['_CONST_POSITION_GROUP'],'arr_all_staff_keep');
		
		// Goi ham search de hien thi ra Complete Textbox
		$this->view->search_textselectbox_doc_type = Sys_Function_DocFunctions::doc_search_ajax($arrLoaiVB,"C_CODE","C_NAME","C_DOC_TYPE","hdn_doc_type",1,"",1);
		$this->view->search_textselectbox_leader = Sys_Function_DocFunctions::doc_search_ajax($arrLeader,"id","name","C_LEADER_ID","hdn_leader_id_list",1,'position_code',1);
		$this->view->search_textselectbox_unit = Sys_Function_DocFunctions::doc_search_ajax($_SESSION['arr_all_unit_keep'],"id","name","C_UNIT_ID","hdn_unit_id_list",1,'',1);
		//var_dump($_SESSION['arr_all_unit_keep']);
		$iOption = 1;
		if($_SESSION['OWNER_ID'] != Sys_Init_Config::_setParentOwnerId())
			$iOption = 0;
		$arrResul = $objReceive->DocRopResultProcessReceivedGetAll($_SESSION['OWNER_ID'],$iLeaderId, $iUnitId, $sStatus, $sDocType, Sys_Library::_ddmmyyyyToYYyymmdd($sFromDate), Sys_Library::_ddmmyyyyToYYyymmdd($sToDate), $sfullTextSearch, $iCurrentPage, $iNumRowOnPage, $iOption);			
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
	function viewAction(){
		$this->view->bodyTitle = '';
		$ojbSysLib = new Sys_Library();
		$objDocFun = new Sys_Function_DocFunctions();
		$this->view->bodyTitle = "THÔNG TIN VĂN BẢN";
		$this->view->WorkTitle = "QUÁ TRÌNH XỬ LÝ VĂN BẢN";
		// Tao doi tuong cho lop xu ly du lieu lien quan modul	
		$objReceive = new statistics_modRop();
		$ojbSysInitConfig = new Sys_Init_Config();	
		//Lay id van ban tu view
		$sReceiveDocumentId = $this->_request->getParam('hdn_object_id','');		
		$this->view->sReceiveDocumentId = $sReceiveDocumentId;
		//Mang luu thong tin chi tiet cua mot van ban
		//echo $sReceiveDocumentId; exit;
		$arrReceived = $objReceive->DocRopReceivedGetSingle($sReceiveDocumentId);
		$this->view->arrReceived = $arrReceived;
		//Lay toan bo thong tin qua trinh xu ly cua mot van ban den
		$arrWorkAll = $objReceive->DocReceivedProcessWorkGetAll($sReceiveDocumentId);
		$this->view->arrWorkAll = $arrWorkAll;
		$this->view->arrConst =	$ojbSysInitConfig->_setProjectPublicConst();
		$sSubModulLeft = $this->_request->getParam('hdn_subModulLeft','');	
		$this->view->getStatusLeftMenu = $sSubModulLeft;
		//Lay gia tri tim kiem tren form
			$sfullTextSearch 	= $this->_request->getParam('txtfullTextSearch','');
			$sDocType 			= $this->_request->getParam('C_DOC_TYPE','');
			$iUnitId 			= $objDocFun->convertUnitNameListToUnitIdList($this->_request->getParam('C_UNIT_ID',''));
			$iLeaderId	 		= $objDocFun->convertStaffNameToStaffId($this->_request->getParam('C_LEADER_ID',''));
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
			$arrParaSet = array("trangthai"=>$sStatus,"trangHienThoi"=>$iCurrentPage, "soBanGhiTrenTrang"=>$iNumRowOnPage,"loaiVanBan"=>$sDocType,"linhvucvanban"=>$sDocCate,"donvixuly"=>$iUnitId,"chuoiTimKiem"=>$sfullTextSearch,"timkiemnangcao"=>$sadvandeSearch,"lanhdaogiaoviec"=>$iLeaderId,"tungay"=>$sFromDate,"denngay"=>$sToDate);
			$_SESSION['seArrParameter'] = $arrParaSet;	
	}
	public function printAction(){
		$objFunction =	new	Sys_Function_DocFunctions()	;
		//Lay duong dan
		$path = $_SERVER['SCRIPT_FILENAME'];
		$path = substr($path, 0, -9);
		$my_report = str_replace("/", "\\", $path) . "rpt\\rop\\Recieved.rpt";
		// Tao doi tuong Crystal 9
		$COM_Object = "CrystalDesignRunTime.Application.9";		
		$crapp= new COM($COM_Object) or die("Unable to Create Object");
		$creport = $crapp->OpenReport($my_report, 1);	
		$sadvandeSearch     = $this->_request->getParam('hdn_advand_search','');
		//Lay cac tham so tren form
		if($sadvandeSearch == 'yes'){
			$sDocType 			= trim($this->_request->getParam('hdn_doc_type',''));
			$iLeaderId	 		= $objFunction->convertStaffNameToStaffId($this->_request->getParam('hdn_leader_name',''));
			$iUnitId 			= $this->_request->getParam('hdn_unit_id','');
			$sStatus			= $this->_request->getParam('hdn_status','');
		}else{
			$sDocType = ''; $iLeaderId = ''; $iUnitId = ''; $sStatus = '';
		}
		$sfullTextSearch 	= trim($this->_request->getParam('hdn_full_textSearch',''));
		$sfromDate			= $this->_request->getParam('hdn_from_date','');
		$stoDate			= $this->_request->getParam('hdn_to_date','');
		$sFromDate 			= Sys_Library::_ddmmyyyyToYYyymmdd($sfromDate);
		$sToDate 			= Sys_Library::_ddmmyyyyToYYyymmdd($stoDate);
		echo $iUnitId.'<br>';
		if($_SESSION['arrStaffPermission']['THEO_DOI_KQXL_VB_DEN_PB'] == 1 || $_SESSION['arrStaffPermission']['THEODOI_XLVBDEN_PX'] == 1){
			$iUnitId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');
		}
		if($_SESSION['arrStaffPermission']['THEO_DOI_KQXL_VB_DEN_PB'] != 1 && $_SESSION['arrStaffPermission']['THEO_DOI_KQXL_VBDEN'] != 1 && $_SESSION['arrStaffPermission']['THEODOI_XLVBDEN_PX'] != 1){
			$iUnitId = 9999;
		}
		// Truyen tham so vao
		//Ket noi CSDL SQL theo kieu ADODB
		$connectSQL = new Zend_Config_Ini('./config/config.ini','dbmssql');
		$arrConn = $connectSQL->db->config->toArray();
		$creport->Database->Tables(1)->SetLogOnInfo($arrConn['host'], $arrConn['dbname'], $arrConn['username'], $arrConn['password']);
		$creport->EnableParameterPrompting = 0;		
		$creport->ReadRecords();
		$iOption = 1;
		if($_SESSION['OWNER_ID'] != Sys_Init_Config::_setParentOwnerId())
			$iOption = 0;
		$z = $creport->ParameterFields(2)->SetCurrentValue($sfromDate);
		$z = $creport->ParameterFields(3)->SetCurrentValue($stoDate);
		$z = $creport->ParameterFields(4)->SetCurrentValue((int)$_SESSION['OWNER_ID']);
		if(!is_null($iLeaderId) and $iLeaderId != '')
			$z = $creport->ParameterFields(5)->SetCurrentValue((int)$iLeaderId);
		if(!is_null($iUnitId) and $iUnitId != '')
			$z = $creport->ParameterFields(6)->SetCurrentValue((int)$iUnitId);
		$z = $creport->ParameterFields(7)->SetCurrentValue($sStatus);
		$z = $creport->ParameterFields(8)->SetCurrentValue($sDocType);
		$z = $creport->ParameterFields(9)->SetCurrentValue($sFromDate);
		$z = $creport->ParameterFields(10)->SetCurrentValue($sToDate);
		$z = $creport->ParameterFields(11)->SetCurrentValue($sfullTextSearch);
		$z = $creport->ParameterFields(12)->SetCurrentValue(1);
		$z = $creport->ParameterFields(13)->SetCurrentValue(1000);
		$z = $creport->ParameterFields(14)->SetCurrentValue($iOption);
		$strangthai = 'Tất cả';
		if($sStatus == 'DANG_XU_LY') 
				$strangthai = 'Đang xử lý';
		elseif ($sStatus == 'DA_XU_LY')
				$strangthai = 'Đã xử lý';
		elseif ($sStatus == 'QUA_HAN')	
				$strangthai = 'Quá hạn';
		$z = $creport->ParameterFields(1)->SetCurrentValue($strangthai);
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
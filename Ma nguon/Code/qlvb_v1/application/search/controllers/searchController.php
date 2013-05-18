<?php
class search_searchController extends  Zend_Controller_Action {
	public $_publicPermission;
	public function init(){
		
        $tempDirApp = Zend_Registry::get('conDirApp');
		$this->_dirApp = $tempDirApp->toArray();
		$this->view->dirApp = $tempDirApp->toArray();
		Zend_Layout::startMvc(array(
			    'layoutPath' => $this->_dirApp['layout'],
			    'layout' => 'index'			    
			    ));			
		$response = $this->getResponse();
		//Lay cac hang so su dung trong JS public
		Zend_Loader::loadClass('Sys_Init_Config');
		$objConfig = new Sys_Init_Config();
		$this->view->UrlAjax = $objConfig->_setUrlAjax();			
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
		//Goi lop modReceived
		
		Zend_Loader::loadClass('search_modsearch');	
			
		$this->view->JSPublicConst = $objConfig->_setJavaScriptPublicVariable();		
		// Load tat ca cac file Js va Css
		$this->view->LoadAllFileJsCss = Sys_Publib_Library::_getAllFileJavaScriptCss('','js','FullTextSearch.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','ajax.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','jquery-1.4.2.min.js,jQuery.equalHeights.js',',','js'). Sys_Publib_Library::_getAllFileJavaScriptCss('','js/LibSearch','actb_search.js,common_search.js',',','js');		
		$sGetValueInCookie = Sys_Library::_getCookie("showHideMenu");
				
		$this->view->InforStaff = Sys_Publib_Library::_InforStaff();
		
		//Dinh nghia current modul code
		$this->view->currentModulCode = "FULL_TEXT_SEARCH";
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
		$response->insert('header', $this->view->renderLayout('header.phtml','./application/views/scripts/'));    //Hien thi header 
		$response->insert('left', $this->view->renderLayout('left.phtml','./application/views/scripts/'));    //Hien thi header 		    
        $response->insert('footer', $this->view->renderLayout('footer.phtml','./application/views/scripts/'));  	 //Hien thi footer
  	}		
	public function indexAction(){					
		$this->view->hideDisplayMeneLeft = "";
		$pUrl = $_SERVER['REQUEST_URI'];
		$objFunction =	new	Sys_Function_DocFunctions()	;
		// Tao doi tuong Zend_Filter
		$objFilter = new Zend_Filter();
		$ojbSysLib = new Sys_Library();	
		// Tieu de man hinh danh sach
		$this->view->bodyTitle = "DANH SÁCH VĂN BẢN";
		$iOptionSearch         = $this->_request->getParam('hdn_option_search',0);
		if ($iOptionSearch == 0){
				$sFullTextSearch	= trim($this->_request->getParam('key',''));
		}
		else{
			$sFullTextSearch    = trim($this->_request->getParam('txtfullTextSearch',''));
		}
		$iYear				= $this->_request->getParam('year','');
		if(is_null($iYear) || $iYear == '')
			$iYear = date('Y');
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
			var_dump($Parameter);
			$sFullTextSearch	= $Parameter['chuoiTimKiem'];
			$iYear				= $Parameter['Nam'];
			$iCurrentPage		= $Parameter['trangHienThoi'];
			$iNumRowOnPage		= $Parameter['soBanGhiTrenTrang'];
			unset($_SESSION['seArrParameter']);
		}
		$sFullTextSearch = $ojbSysLib->_replaceBadChar($sFullTextSearch);
		//Day cac gia tri tim kiem ra view
		$this->view->sFullTextSearch 	= $sFullTextSearch;
		$this->view->iYear 				= $iYear;
		$this->view->iCurrentPage 		= $iCurrentPage;
		$this->view->iNumRowOnPage 		= $iNumRowOnPage;
		$this->view->iOptionSearch		= $iOptionSearch;
		//Lay cac hang so dung chung
		$arrConst = Sys_Init_Config::_setProjectPublicConst();
		$this->view->arrConst = $arrConst;
		
		// Lay toan bo tham so truyen tu form			
		$arrInput = $this->_request->getParams();
		$objFullTextSearch = new search_modsearch();
		//Lay thong tin trong danh muc
		// Goi ham search de hien thi ra Complete Textbox
		$arrDoc = $objFullTextSearch->DocFullTextSearchDocGetAll($_SESSION['OWNER_ID'],$iYear, $sFullTextSearch, $iCurrentPage, $iNumRowOnPage);		
		$iNumberRecord = $arrDoc[0]['C_TOTAL_RECORD'];	
		$this->view->arrDoc = $arrDoc;
		$sdocpertotal ="Danh sách này không có văn bản nào";
		//Hien thi thong tin man hinh danh sach nay co bao nhieu ban ghi va hien thi Radio "Chon tat ca"; "Bo chon tat ca"
		$this->view->SelectDeselectAll = Sys_Publib_Library::_selectDeselectAll(sizeof($arrDoc), $iNumberRecord);
		if (count($arrDoc) > 0){
			$this->view->sdocpertotal = "Danh sách có: ".sizeof($arrDoc).'/'.$iNumberRecord." văn bản";
			//Sinh xau HTML mo ta so trang (Trang 1; Trang 2;...)
			$this->view->generateStringNumberPage = Sys_Publib_Library::_generateStringNumberPage($iNumberRecord, $iCurrentPage, $iNumRowOnPage,$pUrl) ;
			//Sinh chuoi HTML mo ta tong so trang (Trang 1; Trang 2;...) va quy dinh so record/page
			$this->view->generateHtmlSelectBoxPage = Sys_Publib_Library::_generateChangeRecordNumberPage($iNumRowOnPage,$this->view->getStatusLeftMenu);
		}
		$this->view->NumberRowOnPage 	= $this->_ConstPublic['NumberRowOnPage'];	
		$this->view->iCountElement = count($this->view->arrAllRecord);	
	}
	function viewAction(){
		//Lay ID cua NSD dang nhap hien thoi
		//$iUnitId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');
		//Lay ID phong ban cua NSD dang nhap hien thoi
		$pUrl = $_SERVER['REQUEST_URI'];
		$objFunction =	new	Sys_Function_DocFunctions()	;
		$objFilter = new Zend_Filter();			
		// Tieu de man hinh danh sach
		$this->view->docInfo 		= "THÔNG TIN VĂN BẢN";
		$sDocumentId = $this->_request->getParam('hdn_object_id','');
		$sType		 = $this->_request->getParam('hdn_type','');
		//Lay cac hang so dung chung
		$arrConst = Sys_Init_Config::_setProjectPublicConst();
		$this->view->arrConst = $arrConst;
		// Tao doi tuong Zend_Filter
		$objFilter = new Zend_Filter();
		$ojbSysLib = new Sys_Library();
		$objFullTextSearch = new fulltextsearch_modFulltextsearch();
		//Lay thong tin trong danh muc
		$arrDocSingle = $objFullTextSearch->DocFullTextSearchDocGetSingle($sDocumentId, $sType);			
		$this->view->arrDocSingle = $arrDocSingle;
		//Lay gia tri tim kiem tren form
			$sfullTextSearch 	= $this->_request->getParam('txtfullTextSearch','');
			$iYear	 			= $this->_request->getParam('year','');
			$iCurrentPage		= $this->_request->getParam('hdn_current_page',0);	
			if ($iCurrentPage <= 1){
				$iCurrentPage = 1;
			}
			$iNumRowOnPage = $this->_request->getParam('cbo_nuber_record_page',0);
			if ($iNumRowOnPage == 0)
				$iNumRowOnPage = 15;
			$arrParaSet = array("trangHienThoi"=>$iCurrentPage, "soBanGhiTrenTrang"=>$iNumRowOnPage,"chuoiTimKiem"=>$sfullTextSearch,"Nam"=>$iYear);
			$_SESSION['seArrParameter'] = $arrParaSet;	
		
	}
	public function printAction(){
		$objFunction =	new	Sys_Function_DocFunctions()	;
		//Lay duong dan
		$path = $_SERVER['SCRIPT_FILENAME'];
		$path = substr($path, 0, -9);
		$my_report = str_replace("/", "\\", $path) . "rpt\\fullTextSearch\\doclist.rpt";
		// Tao doi tuong Crystal 9
		$COM_Object = "CrystalDesignRunTime.Application.9";		
		$crapp= new COM($COM_Object) or die("Unable to Create Object");
		$creport = $crapp->OpenReport($my_report, 1);	
		//Lay cac tham so tren form
		$sfullTextSearch 	= $this->_request->getParam('txtfullTextSearch','');
		$iYear	 			= $this->_request->getParam('hdn_year','');
		// Truyen tham so vao
		//Ket noi CSDL SQL theo kieu ADODB
		$connectSQL = new Zend_Config_Ini('./config/config.ini','dbmssql');
		$arrConn = $connectSQL->db->config->toArray();
		$creport->Database->Tables(1)->SetLogOnInfo($arrConn['host'], $arrConn['dbname'], $arrConn['username'], $arrConn['password']);
		$creport->EnableParameterPrompting = 0;
		//echo $sfullTextSearch.'-'.$iYear;exit;
		//echo $sRecordArchivedId; exit;
		$creport->ReadRecords();
		$z = $creport->ParameterFields(1)->SetCurrentValue((int)$_SESSION['OWNER_ID']);
		$z = $creport->ParameterFields(2)->SetCurrentValue((int)$iYear);
		$z = $creport->ParameterFields(3)->SetCurrentValue($sfullTextSearch);
		$z = $creport->ParameterFields(4)->SetCurrentValue(1);
		$z = $creport->ParameterFields(5)->SetCurrentValue(9999);
		// Dinh dang file report ket xuat
		$report_file = 'doclist' . mt_rand(1,1000000) . '.doc';
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
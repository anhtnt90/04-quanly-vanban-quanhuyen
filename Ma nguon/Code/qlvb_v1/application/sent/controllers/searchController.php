<?php

/**
 * Creater : phuongtt
 * Date : 03/08/2010
 * Idea : Tim kiem van ban quan hoac don vi phat hanh
 */
class Sent_searchController extends  Zend_Controller_Action {
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
			
		//Goi lop modHandle	
		Zend_Loader::loadClass('sent_modSent');
		//Lay cac hang so su dung trong JS public
		Zend_Loader::loadClass('Sys_Init_Config');
		$objConfig = new Sys_Init_Config();
		$this->view->UrlAjax = $objConfig->_setUrlAjax();	
		$this->view->JSPublicConst = $objConfig->_setJavaScriptPublicVariable();		
		// Load tat ca cac file Js va Css
		$this->view->LoadAllFileJsCss = Sys_Publib_Library::_getAllFileJavaScriptCss('','js','sent.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','received.js,jquery-1.4.2.min.js,jQuery.equalHeights.js',',','js') .  Sys_Publib_Library::_getAllFileJavaScriptCss('','js','ajax.js',',','js'). Sys_Publib_Library::_getAllFileJavaScriptCss('','js/LibSearch','actb_search.js,common_search.js',',','js');
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
		$this->view->currentModulCode = "SENT";				
		//Modul chuc nang				
		$this->view->currentModulCodeForLeft = "SEARCH-SENT-DOC";			
		//lay modul left
		$this->view->getStatusLeftMenu = $this->_request->getParam('modul','');	
		//Hien thi file template
		$response->insert('header', $this->view->renderLayout('twd_header.phtml','template/'));    
			$response->insert('left', $this->view->renderLayout('twd_left.phtml','template/'));  	    
	        $response->insert('footer', $this->view->renderLayout('twd_footer.phtml','template/'));       
        //lay quyen la van thu Bo hay van thu cac don vi
        //$this->_publicPermission = Sys_Function_DocFunctions::Doc_ArchivesStaff($_SESSION['arrStaffPermission'],$_SESSION['staff_id']);
		//$this->view->permissionVT = $this->_publicPermission;
	}
	public function indexAction(){	
		$pUrl = $_SERVER['REQUEST_URI'];
		$objFunction =	new	Sys_Function_DocFunctions()	;
		// Tao doi tuong Zend_Filter
		$objFilter = new Zend_Filter();
		$ojbSysLib = new Sys_Library();
		// Tieu de tim kiem
		if ($this->view->getStatusLeftMenu == 'QUAN'){
			$this->view->bodyTitle = "DANH SÁCH VĂN ĐƠN VỊ PHÁT HÀNH";
			$iUnitId = Sys_Init_Config::_setParentOwnerId();
		}		
		if ($this->view->getStatusLeftMenu == 'DONVI'){
			$this->view->bodyTitle = "DANH SÁCH VĂN BẢN ĐƠN VỊ PHÁT HÀNH";	
			$iUnitId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');
		}
		//Lay gia tri tim kiem tren form
		$sfullTextSearch 	= trim($this->_request->getParam('txtfullTextSearch',''));
		$sDocType 			= trim($this->_request->getParam('C_DOC_TYPE',''));
		$sDocCate 			= trim($this->_request->getParam('C_DOC_CATE',''));
		$iCurrentPage		= $this->_request->getParam('hdn_current_page',0);		
		if ($iCurrentPage <= 1){
			$iCurrentPage = 1;
		}
		$iNumRowOnPage = $this->_request->getParam('cbo_nuber_record_page',0);
		if ($iNumRowOnPage == 0)
			$iNumRowOnPage = 15;
		//Neu ton tai gia tri tim kiem tron session thi lay trong session
		if(isset($_SESSION['seArrParameter'])){
			if($sfullTextSearch != $Parameter['chuoiTimKiem']||$sDocType != $Parameter['loaiVanBan']||$sDocCate != $Parameter['linhvucvanban']||$iUnitId != $Parameter['donvixuly']||$iNumRowOnPage != $Parameter['soBanGhiTrenTrang'])
				$iCurrentPage   = 1;
			else 
				$iCurrentPage	= $Parameter['trangHienThoi'];
			$Parameter 			= $_SESSION['seArrParameter'];
			$sfullTextSearch	= $Parameter['chuoiTimKiem'];
			$sDocType 			= $Parameter['loaiVanBan'];
			$sDocCate			= $Parameter['linhvucvanban'];
			$iNumRowOnPage 		= $Parameter['soBanGhiTrenTrang'];
			unset($_SESSION['seArrParameter']);
		}
		//Day gia tri tim kiem ra view
		$sfullTextSearch = $ojbSysLib->_replaceBadChar($sfullTextSearch);
		$this->view->sfullTextSearch = $sfullTextSearch;
		$this->view->sDocType 		 = $sDocType;
		$this->view->sDocCate 		 = $sDocCate;
		$this->view->iCurrentPage 	 = $iCurrentPage;
		$this->view->iNumRowOnPage 	 = $iNumRowOnPage;
		//Lay cac hang so dung chung
		$arrConst = Sys_Init_Config::_setProjectPublicConst();
		$this->view->arrConst = $arrConst;
		// Lay toan bo tham so truyen tu form			
		$arrInput = $this->_request->getParams();
		$objSent = new sent_modSent();
		//Lay thong tin trong danh muc
		$arrLoaiVB = $objSent->getPropertiesDocument('DM_LOAI_VAN_BAN','','');
		$arrDocCate = $objSent->getPropertiesDocument('DM_LINH_VUC_VAN_BAN','','');
		// Goi ham search lay ra loai van ban
		$this->view->search_textselectbox_doc_type = Sys_Function_DocFunctions::doc_search_ajax($arrLoaiVB,"C_CODE","C_NAME","C_DOC_TYPE","hdn_doc_type",1,'',1);
		// Goi ham search lay ra linh vuc van ban
		$this->view->search_textselectbox_doc_cate = Sys_Function_DocFunctions::doc_search_ajax($arrDocCate,"C_CODE","C_NAME","C_DOC_CATE","hdn_doc_cate",1,'',1);
		$this->view->search_textselectbox_unit = Sys_Function_DocFunctions::doc_search_ajax($_SESSION['arr_all_unit_keep'],"id","name","C_UNIT_ID","hdn_unit_id_list",1,'',1);
		
		$iYear			= $this->_request->getParam('year','');
		$this->view->year = $iYear;
		$arrResul = $objSent->DocSearchSentGetAll($iUnitId, $sDocType, $sDocCate, $iYear, $sfullTextSearch, $iCurrentPage, $iNumRowOnPage);
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
	public function viewAction(){	
		$this->view->bodyTitle = 'CHI TIẾT VĂN BẢN ĐI';
		$ojbSysLib = new Sys_Library();
		$objSent   = new Sent_modSent();	
		$objDocFun = new Sys_Function_DocFunctions();
		$objFunction =	new	Sys_Function_DocFunctions()	;
		$ojbSysInitConfig = new Sys_Init_Config();
		//Lay Id doi tuong 
		$sentID = $this->_request->getParam('hdn_object_id','');	
		$this->view->sentID = $sentID;
		//Lay thong tin VB di va gui ra View
		$arrSent = $objSent->docSentGetSingle($sentID);
		$this->view->arrSent = $arrSent;
		//Lay cac gia tri const
		$this->view->arrConst =	$ojbSysInitConfig->_setProjectPublicConst();
		$sSubModulLeft = $this->_request->getParam('hdn_subModulLeft','');	
		$this->view->getStatusLeftMenu = $sSubModulLeft;
		//Lay gia tri tim kiem tren form
			$sfullTextSearch 	= $this->_request->getParam('txtfullTextSearch','');
			$sDocType 			= $this->_request->getParam('C_DOC_TYPE','');
			$sDocCate 			= $this->_request->getParam('C_DOC_CATE','');
			$iCurrentPage		= $this->_request->getParam('hdn_current_page',0);		
			if ($iCurrentPage <= 1){
				$iCurrentPage = 1;
			}
			$iNumRowOnPage = $this->_request->getParam('cbo_nuber_record_page',0);
			if ($iNumRowOnPage == 0)
				$iNumRowOnPage = 15;
			$arrParaSet = array("trangHienThoi"=>$iCurrentPage, "soBanGhiTrenTrang"=>$iNumRowOnPage,"loaiVanBan"=>$sDocType,"linhvucvanban"=>$sDocCate,"chuoiTimKiem"=>$sfullTextSearch);
			$_SESSION['seArrParameter'] = $arrParaSet;			
	}	
	/**
	 * Idea : Phuong thuc In thong tin chi tiet VB
	 *
	 */
	public function printdocAction(){
		//Tao doi tuong ham dung chung
		$objDocFun = new Sys_Function_DocFunctions();	
		$ojbSysInitConfig = new Sys_Init_Config();
		//Tao doi tuong Sys_lib
		$ojbSysLib = new Sys_Library();	
		// Tao doi tuong Zend_Filter
		$filter = new Zend_Filter();		
		// Tao doi tuong cho lop xu ly du lieu lien quan modul	
		$objSent   = new Sent_modSent();
		//Lay id van ban tu view
		$sentID = $this->_request->getParam('hdn_object_id','');		
		$this->view->sentID = $sentID;
		//Mang luu thong tin chi tiet cua mot van ban
		$arrSent = $objSent->docSentGetSingle($sentID);
		$this->view->arrSent = $arrSent;
		//Lay file dinh kem
		$strFileName 				= $arrSent[0]['C_FILE_NAME'];
		$sFile 						= Sys_Library::_getAllFileAttach($strFileName,"!#~$|*","!~!",$this->_request->getBaseUrl() . "/public/attach-file/");
		//Lay duong dan
		$path = $_SERVER['SCRIPT_FILENAME'];
		$path = substr($path, 0, -9);
		$my_report = str_replace("/", "\\", $path) . "rpt\\sent\\sent.rpt";
		// Tao doi tuong Crystal 9
		$COM_Object = "CrystalDesignRunTime.Application.9";		
		$crapp= new COM($COM_Object) or die("Unable to Create Object");
		$creport = $crapp->OpenReport($my_report, 1);	
		//$creport->DiscardSavedData;		
		$creport->ReadRecords();
		// Truyen tham so vao		
		$creport->ParameterFields(1)->SetCurrentValue(Sys_Library::_getNameByCode($arrInputBooks,$arrSent[0]['C_TEXT_BOOK'],'C_NAME'));
		$creport->ParameterFields(2)->SetCurrentValue($arrSent[0]['C_SENT_DATE']);	
		$creport->ParameterFields(3)->SetCurrentValue($arrSent[0]['C_DOC_TYPE']);
		$creport->ParameterFields(4)->SetCurrentValue($arrSent[0]['C_NUMBER'].'/'.$arrSent[0]['C_SYMBOL']);
		$creport->ParameterFields(5)->SetCurrentValue(Sys_Library::_getNameByCode($arrNature,$arrSent[0]['C_NATURE'],'C_NAME'));
		$creport->ParameterFields(6)->SetCurrentValue($arrSent[0]['SO_BAN']);
		$creport->ParameterFields(7)->SetCurrentValue($arrSent[0]['SO_TRANG']);
		$creport->ParameterFields(8)->SetCurrentValue($arrSent[0]['GIA_SO']);
		$creport->ParameterFields(9)->SetCurrentValue($arrSent[0]['CAP_SO']);
		$creport->ParameterFields(10)->SetCurrentValue($arrSent[0]['C_SUBJECT']);
		$creport->ParameterFields(11)->SetCurrentValue($arrSent[0]['C_RECEIVE_PLACE']);
		$creport->ParameterFields(12)->SetCurrentValue($arrSent[0]['C_DOC_CATE']);
		$creport->ParameterFields(13)->SetCurrentValue($arrSent[0]['C_UNIT_NAME']); //don vi soan thao
		$creport->ParameterFields(14)->SetCurrentValue($arrSent[0]['C_STAFF_POSITION_NAME']);//chuyen vien soan thao
		$creport->ParameterFields(15)->SetCurrentValue((string)$sFile); 	
		$creport->ParameterFields(16)->SetCurrentValue($arrSent[0]['C_TEXT_OF_EMERGENCY']); 
		//Ten file
		$report_file = 'sent.doc';
		// Duong dan file report
		$my_report_file = str_replace("/", "\\", $path) . "public\\" . $report_file;
		$this->view->my_report_file = $my_report_file;
		//export to PDF process
		$creport->ExportOptions->DiskFileName=$my_report_file; //export to file 
		$creport->ExportOptions->PDFExportAllPages=true;
		$creport->ExportOptions->DestinationType = 1; // export to file
		$creport->ExportOptions->FormatType= 14;
		$creport->Export(false);
		$my_report_file = 'http://'.$_SERVER['HTTP_HOST'].':8080/'.$this->_request->getBaseUrl() .'/public/' . $report_file;
		$this->view->my_report_file = $my_report_file;
		
	}
}?>
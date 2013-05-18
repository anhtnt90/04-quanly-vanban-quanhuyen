<?php
class exchangework_sentController extends  Zend_Controller_Action {
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
		//Load cau hinh thu muc trong file config.ini de lay ca hang so dung chung
        $tempConstPublic = Zend_Registry::get('ConstPublic');
		$this->_ConstPublic = $tempConstPublic->toArray();
				
		//Lay duong dan thu muc goc (path directory root)
		$this->view->baseUrl = $this->_request->getBaseUrl() . "/public/";	
		
		//Goi lop Listxml_modList
		Zend_Loader::loadClass('dashboard_modWebMenu');
		//Lay tat ca cac chuyen muc
		$objWebMenu = new dashboard_modWebMenu();
		$arrResul = $objWebMenu->WebMenuGetAll('4',$_SESSION['OWNER_CODE'],'3','1');
		$this->view->arrMenu = $arrResul;	
		//Lay nhac viec cho cong viec
		/*
		$arrTaskNoty = $objWebMenu->TaskWorkNotyGetAll($_SESSION['staff_id']);
		if($arrTaskNoty[0]['SENT'] > 0){
			$this->view->TaskSent = '<span style="color:#FF0000;"> ('.$arrTaskNoty[0]['SENT'].')</span>';	
		}else{
			$this->view->TaskSent = '';	
		}
		if($arrTaskNoty[0]['REC'] > 0){
			$this->view->TaskRec = '<span style="color:#FF0000;"> ('.$arrTaskNoty[0]['REC'].')</span>';	
		}else{
			$this->view->TaskRec = '';	
		}
		*/
		$sliidvisit = $this->_request->getParam('sliid','');
		$sleftmenu = $this->_request->getParam('sleftmenu','');	
		//echo $sleftmenu;
		//Neu khong co gia tri thì lay trong cookie
		if ($sleftmenu == "" || is_null($sleftmenu) || !isset($sleftmenu)){
			$sleftmenu = Sys_Library::_getCookie("leftvisit");
		}else{
			Sys_Library::_createCookie("leftvisit",$sleftmenu);
		}
		if ($sliidvisit == "" || is_null($sliidvisit) || !isset($sliidvisit)){
			$sliidvisit = Sys_Library::_getCookie("headervisit");
		}else{
			Sys_Library::_createCookie("headervisit",$sliidvisit);
		}
		$this->view->sliidvisit = $sliidvisit;	
		$this->view->sleftmenu = $sleftmenu;		
		//echo $sliidvisit;
		//Goi lop MODEL
		Zend_Loader::loadClass('exchangework_modTalk');
		//Lay cac hang so su dung trong JS public
		Zend_Loader::loadClass('Sys_Init_Config');	
		$ojbSysInitConfig = new Sys_Init_Config();
		$this->view->UrlAjax = $ojbSysInitConfig->_setUrlAjax();	
		$this->view->arrConst =	$ojbSysInitConfig->_setProjectPublicConst();
		$this->view->JSPublicConst = $ojbSysInitConfig->_setJavaScriptPublicVariable();	
		//So luong tin bai trong mot chuyen muc
		$this->view->CountInMenu = $ojbSysInitConfig->_setCountInMenu();	
		//Ky tu dac biet phan tach giua cac phan tu
		$this->view->delimitor 			= $this->_ConstPublic['delimitor'];
		//Tao doi tuong XML
		Zend_Loader::loadClass('Sys_Publib_Xml');		
		// Load tat ca cac file Js va Css
		$JSandStyle = Sys_Publib_Library::_getAllFileJavaScriptCss('','js','jsTaskwork.js,util.js,js_calendar.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','ajax.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','jquery-1.4.2.min.js,jQuery.equalHeights.js',',','js'). Sys_Publib_Library::_getAllFileJavaScriptCss('','js/LibSearch','actb_search.js,common_search.js',',','js');												
		$JSandStyle.= Sys_Publib_Library::_getAllFileJavaScriptCss('','js','ui/i18n/jquery.ui.datepicker-vi.js',',','js');
		$JSandStyle.= Sys_Publib_Library::_getAllFileJavaScriptCss('','js','ui/jquery-ui-1.8.14.custom.min.js',',','js');
		$JSandStyle.= Sys_Publib_Library::_getAllFileJavaScriptCss('','style','themes/redmond/jquery-ui-1.8.15.custom.css',',','css');
		$this->view->LoadAllFileJsCss = $JSandStyle;
		
		/* Ket thuc*/
		//Lay tra tri trong Cookie
		$sGetValueInCookie = Sys_Library::_getCookie("showHideMenu");
		$this->view->currentModulCode = "SMS";				
		$this->view->currentModulCodeForLeft ="SUB_MENU_SENT";
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
		$response->insert('header', $this->view->renderLayout('twd_header.phtml','template/'));    
		$response->insert('left', $this->view->renderLayout('twd_left.phtml','template/'));  	    
        $response->insert('footer', $this->view->renderLayout('twd_footer.phtml','template/'));
 	}
	/**
	 * Idea : Phuong thuc hien thi man hinh danh sach
	 *
	 */
	public function indexAction(){	
		//Lay URL 
		$sUrl = $_SERVER['REQUEST_URI'];
		$this->view->sUrl = $sUrl;			
		// Tieu de man hinh danh sach
		$this->view->bodyTitle = "DANH SÁCH CÔNG VIỆC ĐÃ GỬI";
		// Tao doi tuong Zend_Filter
		$objFilter = new Zend_Filter();
		$ojbSysLib = new Sys_Library();
		$objDocFun = new Sys_Function_DocFunctions();
		// Lay toan bo tham so truyen tu form			
		$arrInput = $this->_request->getParams();		
		$objReceive = new exchangework_modTalk();
		// Lay trang thai
		//$arrStatus = $objReceive->getPropertiesDocument('DM_TRANG_THAI_CONG_VIEC ','','');
		//$this->view->arrStatus = $arrStatus;
		//echo 'sentController.php';
		//var_dump($arrStatus);
		//exit;
		$dReceivedDate = date("d/m/Y");
		$this->view->ReceivedDate = $dReceivedDate;
		$iCurrentPage = $this->_request->getParam('hdn_current_page','');		
		if ($iCurrentPage <= 1){
			$iCurrentPage = 1;
		}
		//Lay thong tin quy dinh so row / page
		$iNumRowOnPage = 15;
		$iNumRowOnPage = $objFilter->filter($arrInput['hdn_record_number_page']);		
		if ($iNumRowOnPage <= $this->view->NumberRowOnPage){
			$iNumRowOnPage = $this->view->NumberRowOnPage;
		}		
		//Tieu chi tim kiem
		$sFullTextSearch = $this->_request->getParam('FullTextSearch','');
		$dFromDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($this->_request->getParam('fromDate',''));
		$dToDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($this->_request->getParam('toDate',''));
		$checkstatus = $this->_request->getParam('hdn_search_check',0);
		$filetatus = $this->_request->getParam('hdn_search_file',0);
		//Lay gia tri trong session
		if (isset($_SESSION['seArrParameter'])){
			$arrParaInSession = $_SESSION['seArrParameter'];
			//Tieu chi tim kiem
			$sFullTextSearch = $arrParaInSession['FullTextSearch'];
			$dFromDate = $arrParaInSession['fromDate'];
			$dToDate = $arrParaInSession['toDate'];
			//Trang hien thoi
			$iCurrentPage = $arrParaInSession['hdn_current_page'];
			//So record/page
			$iNumRowOnPage = $arrParaInSession['hdn_record_number_page'];
			//check	
			$checkstatus = $arrParaInSession['hdn_search_check'];
			//file	
			$filetatus = $arrParaInSession['hdn_search_file'];
			//Xoa gia tri trong session
			unset($_SESSION['seArrParameter']);								
		}
		if($iCurrentPage == 0 || $iCurrentPage =="" ||$iCurrentPage == null){
			$iCurrentPage =1;
		}
		if($iNumRowOnPage == 0 || $iNumRowOnPage =="" ||$iNumRowOnPage == null){
			$iNumRowOnPage =15;
		}
		$this->view->currentPage = $iCurrentPage; //Gan gia tri vao View
		$this->view->numRowOnPage = $iNumRowOnPage; //Gan gia tri vao View
		
		//Mac dinh tu ngay (Tu dau tuan) den ngay (den ngay hien tai)
		/*
		if($dFromDate == '' and $dToDate == '' and $sFullTextSearch == ''){
			$dFromDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($objDocFun->getFirstDayOfWeek() );
			$dToDate = date("Y/m/d");
		}
		*/	
		$this->view->sSearchCheck = $checkstatus;
		$this->view->sSearchFile = $filetatus;
		//Thuc hien lay du lieu	
		$arrResul = $objReceive->TaskWorkGetAll('CONG_VIEC_DA_GUI',$_SESSION['staff_id'],'','','','', $dFromDate, $dToDate, trim($sFullTextSearch),$checkstatus, $filetatus, $iCurrentPage, $iNumRowOnPage);
		$iNumberRecord = $arrResul[0]['C_TOTAL_RECORD'];
		$sdocpertotal ="Danh sách này không có văn bản nào";
		//Phan trang
		if (count($arrResul) > 0){
			$this->view->sdocpertotal = "Danh sách có ".sizeof($arrResul).'/'.$iNumberRecord." văn bản";
			//Sinh xau HTML mo ta so trang (Trang 1; Trang 2;...)
			$this->view->generateStringNumberPage = Sys_Publib_Library::_generateStringNumberPage($iNumberRecord, $iCurrentPage, $iNumRowOnPage,$sUrl) ;
			//Sinh chuoi HTML mo ta tong so trang (Trang 1; Trang 2;...) va quy dinh so record/page
			$this->view->generateHtmlSelectBoxPage = Sys_Publib_Library::_generateChangeRecordNumberPage($iNumRowOnPage,$sUrl);
		}

		//var_dump($arrResul);
		$this->view->arrResul = $arrResul;
		//
		$this->view->NumberRowOnPage 	= $this->_ConstPublic['NumberRowOnPage'];	
		$this->view->iCountElement = count($this->view->arrAllRecord);
		
		//Luu cac gia tri tim kiem duoc nhap vao tu form 
        $this->view->fromDate = $ojbSysLib->_yyyymmddToDDmmyyyy($dFromDate);
        $this->view->toDate =  $ojbSysLib->_yyyymmddToDDmmyyyy($dToDate);
        $this->view->FullTextSearch = $sFullTextSearch;
	}
	/*
	 * 
	 */
	public function checkAction(){		
		$objReceive = new exchangework_modTalk();
		$ojbSysLib = new Sys_Library();
		//Lay thong tin trang hien thoi
		$iCurrentPage = $this->_request->getParam('hdn_current_page',0);
		$this->view->currentPage	= $iCurrentPage;	
		//Lay thong tin quy dinh so row / page
		$iNumRowOnPage = $this->_request->getParam('hdn_record_number_page',0);
		$this->view->numRowOnPage	= $iNumRowOnPage;	
		//Tieu chi tim kiem
		$sFullTextSearch = $this->_request->getParam('FullTextSearch','');
		$dFromDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($this->_request->getParam('fromDate',''));
		$dToDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($this->_request->getParam('toDate',''));
		$checkstatus = $this->_request->getParam('hdn_search_check',0);
		$filetatus = $this->_request->getParam('hdn_search_file',0);
		//Luu cac gia tri tim kiem duoc nhap vao tu form 
        //$this->view->fromDate = $ojbSysLib->_yyyymmddToDDmmyyyy($dFromDate);
        //$this->view->toDate =  $ojbSysLib->_yyyymmddToDDmmyyyy($dToDate);
        //$this->view->FullTextSearch = $sFullTextSearch;
        //$this->view->sSearchCheck = $checkstatus;
		//Luu gia tri												
		$arrParaSet = array("hdn_current_page"=>$iCurrentPage,"hdn_record_number_page"=>$iNumRowOnPage,"FullTextSearch"=>$sFullTextSearch, "fromDate"=>$dFromDate, "toDate"=>$dToDate, "hdn_search_check"=>$checkstatus, "hdn_search_file"=>$filetatus);
		$_SESSION['seArrParameter'] = $arrParaSet;
		$this->_request->setParams($arrParaSet);	
		//check
		$object_check_id = $this->_request->getParam('hdn_object_check_id','');
		if($object_check_id != ''){
			$arrResul = $objReceive->TaskWorkCheck($object_check_id);
		}
		$this->_redirect('exchangework/sent/index/');
	}
	/**
	 * Idea : Phuong thuc them moi mot VB
	 *
	 */
	public function addAction(){
		$this->view->bodyTitle = 'THÊM MỚI CÔNG VIỆC';
		$arrInput = $this->_request->getParams();
		$objDocFun = new Sys_Function_DocFunctions();
		$objReceive = new exchangework_modTalk();
		$ojbXmlLib = new Sys_Publib_Xml();
		$ojbSysLib = new Sys_Library();
		$objFilter = new Zend_Filter();
		$ojbSysInitConfig = new Sys_Init_Config();	
		 //Lay thong tin history back
		$this->view->historyBack = $this->_request->getParam('hdn_history_back','');
		//Lay tham so cau hinh
		$sysLibUrlPath = $ojbSysInitConfig->_setLibUrlPath();
		$url_path_calendar = $sysLibUrlPath . 'sys-calendar/';
		$this->view->urlCalendar = $url_path_calendar;
		
		//Lay thong tin trang hien thoi
		$iCurrentPage = $this->_request->getParam('hdn_current_page',0);
		$this->view->currentPage	= $iCurrentPage;	
		//Lay thong tin quy dinh so row / page
		$iNumRowOnPage = $this->_request->getParam('hdn_record_number_page',0);
		$this->view->numRowOnPage	= $iNumRowOnPage;	
		//Tieu chi tim kiem
		$sFullTextSearch = $this->_request->getParam('FullTextSearch','');
		$dFromDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($this->_request->getParam('fromDate',''));
		$dToDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($this->_request->getParam('toDate',''));
		$checkstatus = $this->_request->getParam('hdn_search_check',0);
		$this->view->sSearchCheck = $checkstatus;
		$filetatus = $this->_request->getParam('hdn_search_file',0);
		$this->view->sSearchFile = $filetatus;
		//Luu cac gia tri tim kiem duoc nhap vao tu form 
        $this->view->fromDate = $ojbSysLib->_yyyymmddToDDmmyyyy($dFromDate);
        $this->view->toDate =  $ojbSysLib->_yyyymmddToDDmmyyyy($dToDate);
        $this->view->FullTextSearch = $sFullTextSearch;
        //cuongnh - tao nhom nguoi dung
        //Lay mang ca phong ban lan nguoi dung
        $arrRecieveObj = Sys_Function_DocFunctions::getArrRecieveObj();
		//var_dump($_SESSION['arr_all_staff_keep']);
		// Goi ham search
		$this->view->search_textselectbox_staff = Sys_Function_DocFunctions::doc_search_ajax($arrRecieveObj,"id","name","C_STAFF_ID_LIST","hdn_staff_id_list",0,'position_code');
		$this->view->search_textselectbox_onestaff = Sys_Function_DocFunctions::doc_search_ajax($_SESSION['arr_all_staff_keep'],"id","name","C_STAFF_ID","hdn_staff_id",1,'position_code');

		//Tuy chon ung voi cac truong hop update du lieu	
		$sOption = $this->_request->getParam('hdh_option','');
		$this->view->option = $sOption;
		$this->view->AttachFile = $objDocFun->DocSentAttachFile(array(),0,10,true,42);
		if ($objFilter->filter($arrInput['C_TITLE']) != ""){		
			$arrFileNameUpload = $ojbSysLib->_uploadFileList(10,$this->_request->getBaseUrl() . "/public/attach-file/",'FileName','!#~$|*');
			//lay nguoi tao
			$sStaffID = $_SESSION['staff_id'];
			$sStaffName = $objDocFun->getNamePositionStaffByIdList($sStaffID);
			//lay nguoi xu ly
			$sProcessStaffName = $objFilter->filter($arrInput['C_STAFF_ID']);
			$sProcessStaffNameList = $objFilter->filter($arrInput['C_STAFF_ID_LIST']);
			$sNameList = '';
			if($sProcessStaffName != ''){
				$sNameList = $sNameList . $sProcessStaffName;
				//$sProcessStaffID = $objDocFun->convertStaffNameToStaffId($sProcessStaffName);
				$sProcessStaffID = $objFilter->filter($arrInput['hdn_staff_id']);
			}
			$fullUnit = 0;
			$fullOwner = 0;
			$fullLederUnit = 0;
			$fullLederOwner = 0;
			$sProcessStaffIDList = '';
			$sProcessUnitIDList = '';
			if($sProcessStaffNameList != ''){
				$sNameList = $sNameList . $sProcessStaffNameList;
				//$sProcessStaffIDList = $objDocFun->convertStaffNameToStaffId($sProcessStaffNameList);
				$sProcessStaffIDList = $objFilter->filter($arrInput['hdn_staff_id_list']);
				//echo 'tesssssssss'.$sProcessStaffIDList;
				//chuyen doi danh sach mang mot chieu
				$arrRecieveObjId = explode(",",$sProcessStaffIDList);
				$sProcessStaffIDList = '';
				for ($index = 0; $index<sizeof($arrRecieveObjId); $index++){
					$arrId = explode("|~|",$arrRecieveObjId[$index]);
					if($arrId[0]=='PHBA'){
						$fullUnit = 1;
					}elseif ($arrId[0]=='PHXA'){
						$fullOwner = 1;
					}elseif ($arrId[0]=='LAPB'){
						$fullLederUnit = 1;
					}elseif ($arrId[0]=='LAPX'){
						$fullLederOwner = 1;
					}elseif ($arrId[0]=='UNIT'){
						$sProcessUnitIDList = $sProcessUnitIDList . $arrId[1].',';
					}elseif ($arrId[0]=='STAFF'){
						$sProcessStaffIDList = $sProcessStaffIDList . $arrId[1].',';
					}
				}
			}
			$sProcessStaffIDList = str_replace($sProcessStaffID,'',$sProcessStaffIDList);
			//echo 'tesssssssss'.$fullLederOwner; exit;
			//Mang luu tham so update in database	
			$arrParameter = array(	
								'PK_TASK_WORK'						=>'',										
								'FK_TASK_WORK'						=>'',
								'FK_CREATE_STAFF_ID'				=>$sStaffID,
								'FK_CREATE_STAFF_NAME'				=>$sStaffName,
								'C_NATURE'							=>$objFilter->filter($arrInput['hdn_option_natr']),
								'C_TITLE'							=>$objFilter->filter($arrInput['C_TITLE']),
								'C_CONTENT'							=>$objFilter->filter($arrInput['C_CONTEN']),
								'FK_PROCESS_STAFF_ID'				=>$sProcessStaffID,
								'FK_PROCESS_STAFF_ID_LIST'			=>$sProcessStaffIDList,
								'FK_PROCESS_UNIT_ID_LIST'			=>$sProcessUnitIDList,
								'FK_PROCESS_STAFF_NAME_LIST'		=>$sNameList,
								'C_APPOINTED_DATE'					=>$ojbSysLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['C_APPOINTED_DATE'])),
								'ATTACH_FILE_NAME_LIST'				=>$arrFileNameUpload,
								'C_FULL_UNIT_STATUS'				=>$fullUnit,
								'C_FULL_OWNER_STATUS'				=>$fullOwner,
								'C_FULL_LENDER_UNIT_STATUS'			=>$fullLederUnit,
								'C_FULL_LENDER_OWNER_STATUS'		=>$fullLederOwner								
						);	
			$Result = "";
			//var_dump($arrParameter);exit;				
			$Result = $objReceive->TaskWorkUpdate($arrParameter);				
			//Luu gia tri												
			$arrParaSet = array("hdn_current_page"=>$iCurrentPage,"hdn_record_number_page"=>$iNumRowOnPage,"FullTextSearch"=>$sFullTextSearch, "fromDate"=>$dFromDate, "toDate"=>$dToDate, "hdn_search_check"=>$checkstatus, "hdn_search_file"=>$filetatus);
			//var_dump($arrParaSet); exit;
			$_SESSION['seArrParameter'] = $arrParaSet;
			$this->_request->setParams($arrParaSet);	
			$this->_redirect('exchangework/sent/index/');
		}
	}
	/**
	 * Idea : Phuong thuc hieu chinh mot cong viec
	 *
	 */
	public function editAction(){		
		$this->view->bodyTitle = 'HIỆU CHỈNH THÔNG TIN CÔNG VIỆC';
		$arrInput = $this->_request->getParams();
		$objDocFun = new Sys_Function_DocFunctions();
		$objReceive = new exchangework_modTalk();
		$ojbXmlLib = new Sys_Publib_Xml();
		$ojbSysLib = new Sys_Library();
		$objFilter = new Zend_Filter();
		$ojbSysInitConfig = new Sys_Init_Config();	
		 //Lay thong tin history back
		$this->view->historyBack = $this->_request->getParam('hdn_history_back','');
		//Lay tham so cau hinh
		$sysLibUrlPath = $ojbSysInitConfig->_setLibUrlPath();
		$url_path_calendar = $sysLibUrlPath . 'sys-calendar/';
		$this->view->urlCalendar = $url_path_calendar;
		
		//Lay thong tin trang hien thoi
		$iCurrentPage = $this->_request->getParam('hdn_current_page',0);
		$this->view->currentPage	= $iCurrentPage;	
		//Lay thong tin quy dinh so row / page
		$iNumRowOnPage = $this->_request->getParam('hdn_record_number_page',0);
		$this->view->numRowOnPage	= $iNumRowOnPage;	
		//Tieu chi tim kiem
		$sFullTextSearch = $this->_request->getParam('FullTextSearch','');
		$dFromDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($this->_request->getParam('fromDate',''));
		$dToDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($this->_request->getParam('toDate',''));
		$checkstatus = $this->_request->getParam('hdn_search_check',0);
		$this->view->sSearchCheck = $checkstatus;
		$filetatus = $this->_request->getParam('hdn_search_file',0);
		$this->view->sSearchFile = $filetatus;
		//Luu cac gia tri tim kiem duoc nhap vao tu form 
        $this->view->fromDate = $ojbSysLib->_yyyymmddToDDmmyyyy($dFromDate);
        $this->view->toDate =  $ojbSysLib->_yyyymmddToDDmmyyyy($dToDate);
        $this->view->FullTextSearch = $sFullTextSearch;
        //Lay mang ca phong ban lan nguoi dung
        $arrRecieveObj = Sys_Function_DocFunctions::getArrRecieveObj();
		// Goi ham search
		$this->view->search_textselectbox_staff = Sys_Function_DocFunctions::doc_search_ajax($arrRecieveObj,"id","name","C_STAFF_ID_LIST","hdn_staff_id_list",0,'position_code');
		$this->view->search_textselectbox_onestaff = Sys_Function_DocFunctions::doc_search_ajax($_SESSION['arr_all_staff_keep'],"id","name","C_STAFF_ID","hdn_staff_id",1,'position_code');

		//LAY THONG TIN CO BAN
		$sTaskWorkId = $this->_request->getParam('hdn_object_id','');		
		$this->view->sTaskWorkId = $sTaskWorkId;	
		$arrReceived = $objReceive->TaskWorkSingle($sTaskWorkId);
		$this->view->arrReceived = $arrReceived;
		$this->view->sProcessStaffID = $arrReceived[0]['FK_PROCESS_STAFF'];
		//Lay id doi tuong nhan cong viec
		$recieveObjId = '';
		if($arrReceived[0]['FK_STAFF_ID_LIST']!=''){
			$arrRecieveObjId = explode(",",$arrReceived[0]['FK_STAFF_ID_LIST']);
			for ($index = 0; $index<sizeof($arrRecieveObjId); $index++){
				$recieveObjId = $recieveObjId.'STAFF|~|'.$arrRecieveObjId[$index].',';
			}
		}
		if($arrReceived[0]['FK_UNIT_ID_LIST']!=''){
			$arrRecieveObjId = explode(",",$arrReceived[0]['FK_UNIT_ID_LIST']);
			for ($index = 0; $index<sizeof($arrRecieveObjId); $index++){
				$recieveObjId = $recieveObjId.'UNIT|~|'.$arrRecieveObjId[$index].',';
			}
		}
		if($arrReceived[0]['C_FULL_UNIT_STATUS']!=''){
			$recieveObjId = $recieveObjId.'PHBA|~|FFFFFFFF-FFFF-FFFF-FFFF-FFFFFFFFFFFF,';
		}
		if($arrReceived[0]['C_FULL_OWNER_STATUS']!=''){
			$recieveObjId = $recieveObjId.'PHXA|~|FFFFFFFF-FFFF-FFFF-FFFF-FFFFFFFFFFFF,';
		}
		if($arrReceived[0]['C_FULL_LENDER_UNIT_STATUS']!=''){
			$recieveObjId = $recieveObjId.'LAPB|~|FFFFFFFF-FFFF-FFFF-FFFF-FFFFFFFFFFFF,';
		}
		if($arrReceived[0]['C_FULL_LENDER_OWNER_STATUS']!=''){
			$recieveObjId = $recieveObjId.'LAPX|~|FFFFFFFF-FFFF-FFFF-FFFF-FFFFFFFFFFFF,';
		}
		//echo $recieveObjId;
		$this->view->sProcessStaffIDList = $recieveObjId;
		//var_dump($arrReceived);
		$arFileAttach = $objReceive->DOC_GetAllDocumentFileAttach($sTaskWorkId,'','T_TASK_WORK');	
		$this->view->AttachFile = $objDocFun->DocSentAttachFile($arFileAttach,sizeof($arFileAttach),10,true,42);
		if ($objFilter->filter($arrInput['C_TITLE']) != ""){			
			//Thuc hien upload file len o cung toi da 10 file
			$arrFileNameUpload = $ojbSysLib->_uploadFileList(10,$this->_request->getBaseUrl() . "/public/attach-file/",'FileName','!#~$|*');
			//lay nguoi tao
			$sStaffID = $_SESSION['staff_id'];
			$sStaffName = $objDocFun->getNamePositionStaffByIdList($sStaffID);
			//lay nguoi xu ly
			$sProcessStaffName = $objFilter->filter($arrInput['C_STAFF_ID']);
			$sProcessStaffNameList = $objFilter->filter($arrInput['C_STAFF_ID_LIST']);
			$sNameList = '';
			if($sProcessStaffName != ''){
				$sNameList = $sNameList . $sProcessStaffName;
				//$sProcessStaffID = $objDocFun->convertStaffNameToStaffId($sProcessStaffName);
				$sProcessStaffID = $objFilter->filter($arrInput['hdn_staff_id']);
			}
			$fullUnit = 0;
			$fullOwner = 0;
			$fullLederUnit = 0;
			$fullLederOwner = 0;
			$sProcessStaffIDList = '';
			$sProcessUnitIDList = '';
			if($sProcessStaffNameList != ''){
				$sNameList = $sNameList . $sProcessStaffNameList;
				//$sProcessStaffIDList = $objDocFun->convertStaffNameToStaffId($sProcessStaffNameList);
				$sProcessStaffIDList = $objFilter->filter($arrInput['hdn_staff_id_list']);
				//echo '::'.$sProcessStaffIDList;
				//exit;
				//chuyen doi danh sach mang mot chieu
				$arrRecieveObjId = explode(",",$sProcessStaffIDList);
				$sProcessStaffIDList = '';
				for ($index = 0; $index<sizeof($arrRecieveObjId); $index++){
					$arrId = explode("|~|",$arrRecieveObjId[$index]);
					if($arrId[0]=='PHBA'){
						$fullUnit = 1;
					}elseif ($arrId[0]=='PHXA'){
						$fullOwner = 1;
					}elseif ($arrId[0]=='LAPB'){
						$fullLederUnit = 1;
					}elseif ($arrId[0]=='LAPX'){
						$fullLederOwner = 1;
					}elseif ($arrId[0]=='UNIT'){
						$sProcessUnitIDList = $sProcessUnitIDList . $arrId[1].',';
					}elseif ($arrId[0]=='STAFF'){
						$sProcessStaffIDList = $sProcessStaffIDList . $arrId[1].',';
					}
				}
			}
			$sProcessStaffIDList = str_replace($sProcessStaffID,'',$sProcessStaffIDList);
			//echo $sProcessStaffIDList; exit;
			//Mang luu tham so update in database	
			$arrParameter = array(	
								'PK_TASK_WORK'						=>$sTaskWorkId,										
								'FK_TASK_WORK'						=>'',
								'FK_CREATE_STAFF_ID'				=>$sStaffID,
								'FK_CREATE_STAFF_NAME'				=>$sStaffName,
								'C_NATURE'							=>$objFilter->filter($arrInput['hdn_option_natr']),
								'C_TITLE'							=>$objFilter->filter($arrInput['C_TITLE']),
								'C_CONTENT'							=>$objFilter->filter($arrInput['C_CONTEN']),
								'FK_PROCESS_STAFF_ID'				=>$sProcessStaffID,
								'FK_PROCESS_STAFF_ID_LIST'			=>$sProcessStaffIDList,
								'FK_PROCESS_UNIT_ID_LIST'			=>$sProcessUnitIDList,
								'FK_PROCESS_STAFF_NAME_LIST'		=>$sNameList,
								'C_APPOINTED_DATE'					=>$ojbSysLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['C_APPOINTED_DATE'])),
								'ATTACH_FILE_NAME_LIST'				=>$arrFileNameUpload,
								'C_FULL_UNIT_STATUS'				=>$fullUnit,
								'C_FULL_OWNER_STATUS'				=>$fullOwner,
								'C_FULL_LENDER_UNIT_STATUS'			=>$fullLederUnit,
								'C_FULL_LENDER_OWNER_STATUS'		=>$fullLederOwner	
						);	
			$Result = "";
			//var_dump($arrParameter);exit;				
			$Result = $objReceive->TaskWorkUpdate($arrParameter);				
			//Luu gia tri												
			$arrParaSet = array("hdn_current_page"=>$iCurrentPage,"hdn_record_number_page"=>$iNumRowOnPage,"FullTextSearch"=>$sFullTextSearch, "fromDate"=>$dFromDate, "toDate"=>$dToDate, "hdn_search_check"=>$checkstatus, "hdn_search_file"=>$filetatus);
			//var_dump($arrParaSet); exit;
			$_SESSION['seArrParameter'] = $arrParaSet;
			$this->_request->setParams($arrParaSet);	
			$this->_redirect('exchangework/sent/index/');
		}
	}
	/**
	 * 
	 * Enter description here ...
	 */
	public function forwardAction(){		
		$this->view->bodyTitle = 'CHUYỂN TIẾP CÔNG VIỆC';
		$arrInput = $this->_request->getParams();
		$objDocFun = new Sys_Function_DocFunctions();
		$objReceive = new exchangework_modTalk();
		$ojbXmlLib = new Sys_Publib_Xml();
		$ojbSysLib = new Sys_Library();
		$objFilter = new Zend_Filter();
		$ojbSysInitConfig = new Sys_Init_Config();	
		 //Lay thong tin history back
		$this->view->historyBack = $this->_request->getParam('hdn_history_back','');
		//Lay tham so cau hinh
		$sysLibUrlPath = $ojbSysInitConfig->_setLibUrlPath();
		$url_path_calendar = $sysLibUrlPath . 'sys-calendar/';
		$this->view->urlCalendar = $url_path_calendar;
		
		//Lay thong tin trang hien thoi
		$iCurrentPage = $this->_request->getParam('hdn_current_page',0);
		$this->view->currentPage	= $iCurrentPage;	
		//Lay thong tin quy dinh so row / page
		$iNumRowOnPage = $this->_request->getParam('hdn_record_number_page',0);
		$this->view->numRowOnPage	= $iNumRowOnPage;	
		//Tieu chi tim kiem
		$sFullTextSearch = $this->_request->getParam('FullTextSearch','');
		$dFromDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($this->_request->getParam('fromDate',''));
		$dToDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($this->_request->getParam('toDate',''));
		$checkstatus = $this->_request->getParam('hdn_search_check',0);
		$this->view->sSearchCheck = $checkstatus;
		$filetatus = $this->_request->getParam('hdn_search_file',0);
		$this->view->sSearchFile = $filetatus;
		//Luu cac gia tri tim kiem duoc nhap vao tu form 
        $this->view->fromDate = $ojbSysLib->_yyyymmddToDDmmyyyy($dFromDate);
        $this->view->toDate =  $ojbSysLib->_yyyymmddToDDmmyyyy($dToDate);
        $this->view->FullTextSearch = $sFullTextSearch;
        //Lay mang ca phong ban lan nguoi dung
        $arrRecieveObj = Sys_Function_DocFunctions::getArrRecieveObj();
		// Goi ham search
		$this->view->search_textselectbox_staff = Sys_Function_DocFunctions::doc_search_ajax($arrRecieveObj,"id","name","C_STAFF_ID_LIST","hdn_staff_id_list",0,'position_code');
		$this->view->search_textselectbox_onestaff = Sys_Function_DocFunctions::doc_search_ajax($_SESSION['arr_all_staff_keep'],"id","name","C_STAFF_ID","hdn_staff_id",1,'position_code');

		//LAY THONG TIN CO BAN
		$sTaskWorkId = $this->_request->getParam('hdn_object_id','');		
		$this->view->sTaskWorkId = $sTaskWorkId;	
		$arrReceived = $objReceive->TaskWorkSingle($sTaskWorkId);
		$this->view->arrReceived = $arrReceived;
		$this->view->sProcessStaffID = $arrReceived[0]['FK_PROCESS_STAFF'];
		$this->view->sProcessStaffIDList = $arrReceived[0]['FK_STAFF_ID_LIST'];
		//var_dump($arrReceived);
		$arFileAttach = $objReceive->DOC_GetAllDocumentFileAttach($sTaskWorkId,'','T_TASK_WORK');	
		$this->view->AttachFile = $objDocFun->DocSentAttachFile($arFileAttach,sizeof($arFileAttach),10,false,42);
		if ($objFilter->filter($arrInput['C_TITLE']) != ""){			
			//Thuc hien upload file len o cung toi da 10 file
			$arrFileNameUpload = $ojbSysLib->_uploadFileList(10,$this->_request->getBaseUrl() . "/public/attach-file/",'FileName','!#~$|*');
			//lay nguoi tao
			$sStaffID = $_SESSION['staff_id'];
			$sStaffName = $objDocFun->getNamePositionStaffByIdList($sStaffID);
			//lay nguoi xu ly
			$sProcessStaffName = $objFilter->filter($arrInput['C_STAFF_ID']);
			$sProcessStaffNameList = $objFilter->filter($arrInput['C_STAFF_ID_LIST']);
			$sNameList = '';
			if($sProcessStaffName != ''){
				$sNameList = $sNameList . $sProcessStaffName;
				//$sProcessStaffID = $objDocFun->convertStaffNameToStaffId($sProcessStaffName);
				$sProcessStaffID = $objFilter->filter($arrInput['hdn_staff_id']);
			}
			$fullUnit = 0;
			$fullOwner = 0;
			$fullLederUnit = 0;
			$fullLederOwner = 0;
			$sProcessStaffIDList = '';
			$sProcessUnitIDList = '';
			if($sProcessStaffNameList != ''){
				$sNameList = $sNameList . $sProcessStaffNameList;
				//$sProcessStaffIDList = $objDocFun->convertStaffNameToStaffId($sProcessStaffNameList);
				$sProcessStaffIDList = $objFilter->filter($arrInput['hdn_staff_id_list']);
				//echo '::'.$sProcessStaffIDList;
				//exit;
				//chuyen doi danh sach mang mot chieu
				$arrRecieveObjId = explode(",",$sProcessStaffIDList);
				$sProcessStaffIDList = '';
				for ($index = 0; $index<sizeof($arrRecieveObjId); $index++){
					$arrId = explode("|~|",$arrRecieveObjId[$index]);
					if($arrId[0]=='PHBA'){
						$fullUnit = 1;
					}elseif ($arrId[0]=='PHXA'){
						$fullOwner = 1;
					}elseif ($arrId[0]=='LAPB'){
						$fullLederUnit = 1;
					}elseif ($arrId[0]=='LAPX'){
						$fullLederOwner = 1;
					}elseif ($arrId[0]=='UNIT'){
						$sProcessUnitIDList = $sProcessUnitIDList . $arrId[1].',';
					}elseif ($arrId[0]=='STAFF'){
						$sProcessStaffIDList = $sProcessStaffIDList . $arrId[1].',';
					}
				}
			}
			$sProcessStaffIDList = str_replace($sProcessStaffID,'',$sProcessStaffIDList);
			$sTitle = $objFilter->filter($arrInput['C_TITLE']);
			//echo $sProcessStaffIDList; exit;
			//Mang luu tham so update in database	
			$arrParameter = array(	
								'PK_TASK_WORK'						=>'',										
								'FK_TASK_WORK'						=>$sTaskWorkId,
								'FK_CREATE_STAFF_ID'				=>$sStaffID,
								'FK_CREATE_STAFF_NAME'				=>$sStaffName,
								'C_NATURE'							=>$objFilter->filter($arrInput['hdn_option_natr']),
								'C_TITLE'							=>$sTitle,
								'C_CONTENT'							=>$objFilter->filter($arrInput['C_CONTEN']),
								'FK_PROCESS_STAFF_ID'				=>$sProcessStaffID,
								'FK_PROCESS_STAFF_ID_LIST'			=>$sProcessStaffIDList,
								'FK_PROCESS_STAFF_NAME_LIST'		=>$sNameList,
								'C_APPOINTED_DATE'					=>$ojbSysLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['C_APPOINTED_DATE'])),
								'ATTACH_FILE_NAME_LIST'				=>$arrFileNameUpload,
								'C_FULL_UNIT_STATUS'				=>$fullUnit,
								'C_FULL_OWNER_STATUS'				=>$fullOwner,
								'C_FULL_LENDER_UNIT_STATUS'			=>$fullLederUnit,
								'C_FULL_LENDER_OWNER_STATUS'		=>$fullLederOwner	
						);	
			$Result = "";
			//var_dump($arrParameter);exit;				
			$Result = $objReceive->TaskWorkUpdate($arrParameter);				
			//Luu gia tri												
			$arrParaSet = array("hdn_current_page"=>$iCurrentPage,"hdn_record_number_page"=>$iNumRowOnPage,"FullTextSearch"=>$sFullTextSearch, "fromDate"=>$dFromDate, "toDate"=>$dToDate, "hdn_search_check"=>$checkstatus, "hdn_search_file"=>$filetatus);
			//var_dump($arrParaSet); exit;
			$_SESSION['seArrParameter'] = $arrParaSet;
			$this->_request->setParams($arrParaSet);	
			$this->_redirect('exchangework/sent/index/');
		}
	}
	/**
	 * 
	 * Enter description here ...
	 */
	public function feedbackAction(){		
		$this->view->NomTitle = 'THÔNG TIN CƠ BẢN';
		$this->view->ContenTitle = 'PHẢN HỒI';
		$this->view->ProcessTitle = 'QUÁ TRÌNH THỰC HIỆN';
		$arrInput = $this->_request->getParams();
		$objDocFun = new Sys_Function_DocFunctions();
		$objReceive = new exchangework_modTalk();
		$ojbXmlLib = new Sys_Publib_Xml();
		$ojbSysLib = new Sys_Library();
		$objFilter = new Zend_Filter();
		$ojbSysInitConfig = new Sys_Init_Config();	
		 //Lay thong tin history back
		$this->view->historyBack = $this->_request->getParam('hdn_history_back','');
		//Lay tham so cau hinh
		$sysLibUrlPath = $ojbSysInitConfig->_setLibUrlPath();
		$url_path_calendar = $sysLibUrlPath . 'sys-calendar/';
		$this->view->urlCalendar = $url_path_calendar;
		
		//Lay thong tin trang hien thoi
		$iCurrentPage = $this->_request->getParam('hdn_current_page',0);
		$this->view->currentPage	= $iCurrentPage;	
		//Lay thong tin quy dinh so row / page
		$iNumRowOnPage = $this->_request->getParam('hdn_record_number_page',0);
		$this->view->numRowOnPage	= $iNumRowOnPage;	
		//Tieu chi tim kiem
		$sFullTextSearch = $this->_request->getParam('FullTextSearch','');
		$dFromDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($this->_request->getParam('fromDate',''));
		$dToDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($this->_request->getParam('toDate',''));
		$checkstatus = $this->_request->getParam('hdn_search_check',0);
		$this->view->sSearchCheck = $checkstatus;
		$filetatus = $this->_request->getParam('hdn_search_file',0);
		$this->view->sSearchFile = $filetatus;
		//Luu cac gia tri tim kiem duoc nhap vao tu form 
        $this->view->fromDate = $ojbSysLib->_yyyymmddToDDmmyyyy($dFromDate);
        $this->view->toDate =  $ojbSysLib->_yyyymmddToDDmmyyyy($dToDate);
        $this->view->FullTextSearch = $sFullTextSearch;   

		//LAY THONG TIN CO BAN
		$sTaskWorkId = $this->_request->getParam('hdn_object_id','');		
		$this->view->sTaskWorkId = $sTaskWorkId;	
		//update lai trang thai phan hoi
		$arrReceived = $objReceive->TaskWorkFeedBackStatusUpdate($sTaskWorkId);
		$arrReceived = $objReceive->TaskWorkSingle($sTaskWorkId);
		$this->view->arrReceived = $arrReceived;
		//var_dump($arrReceived);
		//Lay thon tin xu ly
		$arrReceived = $objReceive->TaskWorkFeedBackGetAll($sTaskWorkId);
		$this->view->arrProcess = $arrReceived;
		//file
		$this->view->AttachFile = $objDocFun->DocSentAttachFile(array(),0,10,true,42);
		if ($this->_request->getParam('C_RESULT','')!= ""){		
			//Thuc hien upload file len o cung toi da 10 file
			$arrFileNameUpload = $ojbSysLib->_uploadFileList(10,$this->_request->getBaseUrl() . "/public/attach-file/",'FileName','!#~$|*');
			$sStatusProcess= $objFilter->filter($arrInput['C_PROCESS_STATUS']);
			//if($sStatusProcess == 'DANG_XU_LY'){
				//$sStatusProcess = '';
			//}
			//echo $sStatusProcess; exit;
			//Mang luu tham so update in database	
			$arrParameter = array(	
								'PK_TASK_FEEDBACK'					=>'',										
								'FK_TASK_WORK'						=>$sTaskWorkId,
								'FK_FEEDBACK_STAFF'					=>$_SESSION['staff_id'],
								'C_FEEDBACK_TYPE'					=>'TRAO_DOI',
								'C_STATUS'							=>$sStatusProcess,
								'C_CONTENT'							=>$this->_request->getParam('C_RESULT',''),
								'ATTACH_FILE_NAME_LIST'				=>$arrFileNameUpload
						);	
			$Result = "";
			//var_dump($arrParameter);exit;				
			$Result = $objReceive->TaskWorkFeedBackUpdate($arrParameter);				
			//Luu gia tri												
			$arrParaSet = array("hdn_current_page"=>$iCurrentPage,"hdn_record_number_page"=>$iNumRowOnPage,"FullTextSearch"=>$sFullTextSearch, "fromDate"=>$dFromDate, "toDate"=>$dToDate, "hdn_search_check"=>$checkstatus, "hdn_search_file"=>$filetatus);
			//var_dump($arrParaSet); exit;
			$_SESSION['seArrParameter'] = $arrParaSet;
			$this->_request->setParams($arrParaSet);	
			$this->_redirect('exchangework/sent/feedback/?hdn_object_id='.$sTaskWorkId);	
		}
	}
	/**
	 * Idea : Phuong thuc xoa mot phan hoi
	 *
	 */
	public function deletefeedbackAction(){
		// Tao doi tuong cho lop xu ly du lieu lien quan modul	
		$objReceive = new exchangework_modTalk();
		$ojbSysLib = new Sys_Library();
		//Lay id cua van ban
		$sReceiveDocumentId = $this->_request->getParam('hdn_object_id','');		
		$this->view->sReceiveDocumentId = $sReceiveDocumentId;			
		//Lay Id cong viec xu ly VB can xoa
		$sPkDocWorkIdList = $this->_request->getParam('hdn_object_id_list',"");
		//echo 'okokkk'.$sPkDocWorkIdList; exit;	
		if ($sPkDocWorkIdList != ""){
			$sRetError = $objReceive->TaskWorkFeedBackDelete($sPkDocWorkIdList);
			$this->_redirect('exchangework/sent/feedback/.?hdn_object_id='.$sReceiveDocumentId);	
		}	
	}
	/**
	 * Idea : Phuong thuc xoa
	 *
	 */
	public function deleteAction(){
		// Tao doi tuong cho lop xu ly du lieu lien quan modul	
		$objReceive = new exchangework_modTalk();
		$ojbSysLib = new Sys_Library();
		// Thuc hien lay du lieu tu form 		
		if($this->_request->isPost()){	
			// Lay toan bo tham so truyen tu form			
			$arrInput = $this->_request->getParams();				
			//Lay thong tin trang hien thoi
			$iCurrentPage = $this->_request->getParam('hdn_current_page',0);
			$this->view->currentPage	= $iCurrentPage;	
			//Lay thong tin quy dinh so row / page
			$iNumRowOnPage = $this->_request->getParam('hdn_record_number_page',0);
			$this->view->numRowOnPage	= $iNumRowOnPage;	
			//Tieu chi tim kiem
			$sFullTextSearch = $this->_request->getParam('FullTextSearch','');
			$dFromDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($this->_request->getParam('fromDate',''));
			$dToDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($this->_request->getParam('toDate',''));
			$checkstatus = $this->_request->getParam('hdn_search_check',0);
			$this->view->sSearchCheck = $checkstatus;
			$filetatus = $this->_request->getParam('hdn_search_file',0);
			$this->view->sSearchFile = $filetatus;
			//Luu cac gia tri tim kiem duoc nhap vao tu form 
	        $this->view->fromDate = $ojbSysLib->_yyyymmddToDDmmyyyy($dFromDate);
	        $this->view->toDate =  $ojbSysLib->_yyyymmddToDDmmyyyy($dToDate);				
			//Lay Id doi tuong VB can xoa
			$sReceiveDocumentIdList = $this->_request->getParam('hdn_object_id_list',"");	
			if ($sReceiveDocumentIdList != ""){
				$sRetError = $objReceive->TaskWorkDelete($sReceiveDocumentIdList);
				// Neu co loi			
				if($sRetError != null || $sRetError != '' ){											
					echo "<script type='text/javascript'>";
					echo "alert('$sRetError');\n";				
					echo "</script>";
				}else {		
					//Luu gia tri												
					$arrParaSet = array("hdn_current_page"=>$iCurrentPage,"hdn_record_number_page"=>$iNumRowOnPage,"FullTextSearch"=>$sFullTextSearch, "fromDate"=>$dFromDate, "toDate"=>$dToDate, "hdn_search_check"=>$checkstatus, "hdn_search_file"=>$filetatus);
					$_SESSION['seArrParameter'] = $arrParaSet;
					$this->_request->setParams($arrParaSet);
					//Tro ve trang index												
					$this->_redirect('exchangework/sent/index/');		
				}
			}
		}	
	
	}	
}?>
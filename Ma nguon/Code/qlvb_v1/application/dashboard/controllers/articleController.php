<?php
class dashboard_articleController extends  Zend_Controller_Action {	
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
		
		Zend_Loader::loadClass('dashboard_modWebArticle');
			
		//Lay cac hang so su dung trong JS public
		Zend_Loader::loadClass('Sys_Init_Config');	
		$ojbSysInitConfig = new Sys_Init_Config();
		$this->view->UrlAjax = $ojbSysInitConfig->_setUrlAjax();	
		$this->view->arrConst =	$ojbSysInitConfig->_setProjectPublicConst();
		$this->view->JSPublicConst = $ojbSysInitConfig->_setJavaScriptPublicVariable();	
		//$this->view->UrlRichtext = $ojbSysInitConfig->_getCurrentHttpAndHost().'/public/sys-js/';	
		//Ky tu dac biet phan tach giua cac phan tu
		$this->view->delimitor 			= $this->_ConstPublic['delimitor'];
		//Tao doi tuong XML
		Zend_Loader::loadClass('Sys_Publib_Xml');		
		
		// Load tat ca cac file Js va Css
		$this->view->LoadAllFileJsCss = Sys_Publib_Library::_getAllFileJavaScriptCss('','js','jsWeb.js,util.js,js_calendar.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','ajax.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','jquery-1.4.2.min.js,jQuery.equalHeights.js',',','js'). Sys_Publib_Library::_getAllFileJavaScriptCss('','js/LibSearch','actb_search.js,common_search.js',',','js');										
		/* Ket thuc*/
		
		//Dinh nghia current modul code
		$this->view->currentModulCode = "LIST";
		$this->view->currentModulCodeForLeft = "WEB_ARTICLE";		
		
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
		$response->insert('header', $this->view->renderLayout('twd_header.phtml','template/'));    
		$response->insert('left', $this->view->renderLayout('twd_left_list.phtml','template/'));  	    
        $response->insert('footer', $this->view->renderLayout('twd_footer.phtml','template/'));
 	}
		
	/**
	 * Idea: Thuc hien phuong thuc Action hien thi danh sach doi tuong
	 */
	public function indexAction(){	
		//Lay URL 
		$sUrl = $_SERVER['REQUEST_URI'];
		$this->view->sUrl = $sUrl;		
		// Tieu de man hinh danh sach
		$this->view->bodyTitle = "DANH SÁCH TIN BÀI";
		// Tao doi tuong Zend_Filter
		$objFilter = new Zend_Filter();
		$ojbSysLib = new Sys_Library();
		$objDocFun = new Sys_Function_DocFunctions();
		$arrInput = $this->_request->getParams();
		// Lay mang vi tri hien thi
		$arrStatus = array();
		$arrStatus[0]['C_CODE'] = 2;
		$arrStatus[0]['C_NAME'] = 'Chờ duyệt';
		$arrStatus[1]['C_CODE'] = 1;
		$arrStatus[1]['C_NAME'] = 'Đã duyệt';
		$this->view->arrStatus = $arrStatus;

		
		// Lay trang so.../ quy dinh so tin bai tren mot trang
		$iCurrentPage = $this->_request->getParam('hdn_current_page','');		
		$iNumRowOnPage = 15;
		$iNumRowOnPage = $objFilter->filter($arrInput['hdn_record_number_page']);		
		//Lay danh muc
		$sMenuID = $objFilter->filter($arrInput['C_MENU']);
		//Lay trang thai
		$iStatus = $objFilter->filter($arrInput['C_STATUS']);
		//Tim kiem
		$sFullTextSearch = $this->_request->getParam('FullTextSearch','');
		//echo $sMenuID;
		//var_dump($_SESSION['seArrParameter']);
		//Lay gia tri trong session
		if (isset($_SESSION['seArrParameter'])){
			$arrParaInSession = $_SESSION['seArrParameter'];
			//Tieu chi tim kiem
			$sMenuID = $arrParaInSession['hdn_menuid'];
			$iStatus = $arrParaInSession['hdn_status'];
			$sFullTextSearch = $arrParaInSession['hdn_fulltextseach'];
			$iCurrentPage = $arrParaInSession['hdn_current_page'];
			$iNumRowOnPage = $arrParaInSession['hdn_record_number_page'];
			//Xoa gia tri trong session
			unset($_SESSION['seArrParameter']);								
		}
		if ($iCurrentPage <= 1){
			$iCurrentPage = 1;
		}
		if ($iNumRowOnPage <= $this->view->NumberRowOnPage){
			$iNumRowOnPage = $this->view->NumberRowOnPage;
		}	
		//Truyen sang view
		$this->view->iCurrentPage = $iCurrentPage;
		$this->view->iNumRowOnPage = $iNumRowOnPage;
		$this->view->sMenuID = $sMenuID;
		$this->view->iStatus = $iStatus;
		$this->view->FullTextSearch = $sFullTextSearch;
		
		$objWebMenu = new dashboard_modWebArticle();
		//Lay quyen cua nguoi dung doi voi tin bai
		//Lay mang chuyen muc
		$arrMenu = $objWebMenu->WebArticlePermissionCheck($_SESSION['staff_id'],'EDIT_APPROVE','1');
		$this->view->arrMenu = $arrMenu;
		//var_dump($arrMenu);	
		//Kiem tra xem co duoc them/ duyet tin hay ko?
		$sShowApprove = 0;
		$sShowEdit = 0;
		$sarrApprove = '';
		for($index = 0;$index < sizeof($arrMenu);$index++){
			if($arrMenu[$index]['C_EDIT']== '1'){
				$sShowEdit = 1;
			}
			if($arrMenu[$index]['C_APPROVE']== '1'){
				$sShowApprove = 1;
				$sarrApprove = $sarrApprove.$arrMenu[$index]['PK_WEB_MENU'].',';
			}
		}	
		//echo $sShowApprove;
		$this->view->sShowEdit = $sShowEdit;
		$this->view->sShowApprove = $sShowApprove;
		$this->view->sarrApprove = $sarrApprove;		
		//Thuc hien lay du lieu	
		$arrResul = $objWebMenu->WebArticleGetAll($_SESSION['staff_id'],$sMenuID,$iStatus,$sFullTextSearch,$iCurrentPage,$iNumRowOnPage);
		$this->view->arrResul = $arrResul;
		$iNumberRecord = $arrResul[0]['C_TOTAL_RECORD'];
		$sdocpertotal ="Danh sách này không có tin bài nào";
		//Phan trang
		if (count($arrResul) > 0){
			$this->view->sdocpertotal = "Danh sách có ".sizeof($arrResul).'/'.$iNumberRecord." tin bài";
			//Sinh xau HTML mo ta so trang (Trang 1; Trang 2;...)
			$this->view->generateStringNumberPage = Sys_Publib_Library::_generateStringNumberPage($iNumberRecord, $iCurrentPage, $iNumRowOnPage,$sUrl) ;
			//Sinh chuoi HTML mo ta tong so trang (Trang 1; Trang 2;...) va quy dinh so record/page
			$this->view->generateHtmlSelectBoxPage = Sys_Publib_Library::_generateChangeRecordNumberPage($iNumRowOnPage,$sUrl,'tin bài');
		}
	}
	/**
	 * Idea: Thuc hien phuong thuc Action them moi doi tuong 
	 */
	public function addAction(){
		$this->view->bodyTitle = 'THÊM MỚI TIN BÀI';
		
		$arrInput = $this->_request->getParams();
		$objDocFun = new Sys_Function_DocFunctions();
		$objArticle = new dashboard_modWebArticle();
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
		
		//Lay editor
		Zend_Loader::loadClass('editor_fckeditor');
		//Tao doi tuong editor
		$objFCKeditor = new editor_fckeditor('C_CONTEN');
		//Duong dan vitual 
		$objFCKeditor->BasePath	= Sys_Init_Config::_setWebSitePath() . 'public/editor/';
		$objFCKeditor->Value = '';
		$sEditorString = $objFCKeditor->Create();
		$this->view->editor = $sEditorString;

		//Lay mang chuyen muc
		$arrMenu = $objArticle->WebArticlePermissionCheck($_SESSION['staff_id'],'EDIT_APPROVE','1');
		$this->view->arrMenu = $arrMenu;
		//Kiem tra xem co duoc them/ duyet tin hay ko?
		//$sShowApprove = 0;
		//$sShowEdit = 0;
		$sarrApprove = '';
		$arrMenuEdit = array();
		$i = 0;
		for($index = 0;$index < sizeof($arrMenu);$index++){
			if($arrMenu[$index]['C_EDIT']== '1'){
				//$sShowEdit = 1;
				$arrMenuEdit[$i]['PK_WEB_MENU'] = $arrMenu[$index]['PK_WEB_MENU'];
				$arrMenuEdit[$i]['C_NAME'] = $arrMenu[$index]['C_NAME'];
				$i = $i + 1;
			}
			if($arrMenu[$index]['C_APPROVE']== '1'){
				//$sShowApprove = 1;
				$sarrApprove = $sarrApprove.$arrMenu[$index]['PK_WEB_MENU'].',';
			}
		}	
		//var_dump($sarrApprove);
		//$this->view->sShowEdit = $sShowEdit;
		//$this->view->sShowApprove = $sShowApprove;
		$this->view->arrMenuEdit = $arrMenuEdit;
		$this->view->sarrApprove = $sarrApprove;		
		//Gan quyen sang VIEW
		$this->view->PermissionUser = $this->_publicPermission;
		
		//Lay cac tieu chi loc
		//Lay danh muc
		$sMenuID = $objFilter->filter($arrInput['C_MENU']);
		$this->view->sMenuID = $sMenuID;
		//Lay trang thai
		$iStatus = $objFilter->filter($arrInput['C_STATUS']);
		$this->view->iStatus = $iStatus;
		//Tim kiem
		$sFullTextSearch = $this->_request->getParam('FullTextSearch','');
		$this->view->FullTextSearch = $sFullTextSearch;
		// Lay trang so.../ quy dinh so tin bai tren mot trang
		$iCurrentPage = $this->_request->getParam('hdn_current_page','');		
		$iNumRowOnPage = $objFilter->filter($arrInput['hdn_record_number_page']);	
		//Truyen sang view
		$this->view->iCurrentPage = $iCurrentPage;
		$this->view->iNumRowOnPage = $iNumRowOnPage;
		//echo '$iNumRowOnPage'.$iNumRowOnPage;
		// Dua vao session											
		$arrParaSet = array("hdn_menuid"=>$sMenuID,"hdn_status"=>$iStatus,"hdn_fulltextseach"=>$sFullTextSearch,"hdn_current_page"=>$iCurrentPage,"hdn_record_number_page"=>$iNumRowOnPage);
		$_SESSION['seArrParameter'] = $arrParaSet;
		$this->_request->setParams($arrParaSet);

		$this->view->AttachImageFile = $objDocFun->DocSentAttachOneFile(array(),0,1,true,40,'article/image-upload/');
		
		$this->view->AttachFile = $objDocFun->DocSentAttachFile(array(),0,10,true,80,'article/file-upload/');
		
		//Tuy chon ung voi cac truong hop update du lieu	
		$sOption = $this->_request->getParam('hdh_option','');
		$this->view->option = $sOption;
		//echo $sOption; exit;
		//echo 'ok'.$objFilter->filter($arrInput['C_NAME']); exit;
		if ($objFilter->filter($arrInput['C_TITLE']) != ""){
			$sStaffName = $objDocFun->getNamePositionStaffByIdList($_SESSION['staff_id']);
			$iStatus = $objFilter->filter($arrInput['hdn_approved_status']);
			if($iStatus == '1'){
				$sApproved_staff = $_SESSION['staff_id'];
			}else{
				$sApproved_staff = '';
			}
			//echo $sStaffName;
			//Thuc hien upload anh len o cung
			$arrImageNameUpload = $ojbSysLib->_uploadFileList(1,$this->_request->getBaseUrl() . "/public/attach-file/article/image-upload/",'FileImageName','!#~$|*');
			//Thuc hien upload file dinh kem len o cung
			$arrFileNameUpload = $ojbSysLib->_uploadFileList(10,$this->_request->getBaseUrl() . "/public/attach-file/article/file-upload/",'FileName','!#~$|*');
			//var_dump($arrFileNameUpload); exit;	
			$sconten = str_replace(chr(92),'',htmlspecialchars($this->_request->getParam('C_CONTEN','')));
			$sconten = str_replace(chr(39),'',$sconten);
			$arrParameter = array(	
								'PK_WEB_ARTICLE'				=>'',										
								'FK_WEB_MENU'					=>$objFilter->filter($arrInput['PK_WEB_MENU']),
								'C_TITLE'						=>$ojbSysLib->_replaceBadChar($objFilter->filter($arrInput['C_TITLE'])),
								'C_SHORT_CONTENT'				=>$ojbSysLib->_replaceBadChar($objFilter->filter($arrInput['C_SUBJECT'])),
								'C_DETAIL_CONTENT'				=>$sconten,
								'C_IMAGE_TITLE'					=>$ojbSysLib->_replaceBadChar($objFilter->filter($arrInput['C_IMAGE_TITLE'])),
								'FK_CREATE_STAFF'				=>$_SESSION['staff_id'],
								'FK_CREATE_NAME'				=>$sStaffName,
								'FK_APPROVE_STAFF'				=>$sApproved_staff,
								'C_STATUS'						=>$iStatus,
								'IMAGE_NAME'					=>$arrImageNameUpload,
								'ATTACH_FILE_NAME_LIST'			=>$arrFileNameUpload
						);
			//var_dump($arrParameter); exit;				
			$Result = "";	
			$Result = $objArticle->WebArticleUpdate($arrParameter);			
			if($sOption =='GHI_THEMMOI'){
				$this->_redirect('/dashboard/article/add/');	
			}else{
				$this->_redirect('/dashboard/article/index/');
			}		
		}
	}	
	public function editAction(){
		$this->view->bodyTitle = 'THÔNG TIN CHI TIẾT TIN BÀI';
		
		$arrInput = $this->_request->getParams();
		$objDocFun = new Sys_Function_DocFunctions();
		$objArticle = new dashboard_modWebArticle();
		$ojbXmlLib = new Sys_Publib_Xml();
		$ojbSysLib = new Sys_Library();
		$objFilter = new Zend_Filter();
		$ojbSysInitConfig = new Sys_Init_Config();	
		 //Lay thong tin history back
		$this->view->historyBack = $this->_request->getParam('hdn_history_back','');
		$sWebArticleId = $this->_request->getParam('hdn_object_id','');		
		//Lay thong tin chi tiet
		$this->view->sWebArticleId = $sWebArticleId;
		$arrArticle = $objArticle->WebArticleGetSingle($sWebArticleId);
		$this->view->arrArticle = $arrArticle;
		//Lay editor
		Zend_Loader::loadClass('editor_fckeditor');
		//Tao doi tuong editor
		$objFCKeditor = new editor_fckeditor('C_CONTEN');
		//Duong dan vitual 
		$objFCKeditor->BasePath	= Sys_Init_Config::_setWebSitePath() . 'public/editor/';
		$objFCKeditor->Value = $arrArticle[0][C_DETAIL_CONTENT];
		$sEditorString = $objFCKeditor->Create();
		$this->view->editor = $sEditorString;
		
		//Lay tham so cau hinh
		$sysLibUrlPath = $ojbSysInitConfig->_setLibUrlPath();
		$url_path_calendar = $sysLibUrlPath . 'sys-calendar/';
		$this->view->urlCalendar = $url_path_calendar;
		//Lay mang chuyen muc
		$arrMenu = $objArticle->WebArticlePermissionCheck($_SESSION['staff_id'],'EDIT_APPROVE','1');
		$this->view->arrMenu = $arrMenu;
		//Kiem tra xem co duoc them/ duyet tin hay ko?
		$sShowApprove = 0;
		//$sShowEdit = 0;
		$sarrApprove = '';
		$arrMenuEdit = array();
		$i = 0;
		for($index = 0;$index < sizeof($arrMenu);$index++){
			if($arrMenu[$index]['C_EDIT']== '1'){
				//$sShowEdit = 1;
				$arrMenuEdit[$i]['PK_WEB_MENU'] = $arrMenu[$index]['PK_WEB_MENU'];
				$arrMenuEdit[$i]['C_NAME'] = $arrMenu[$index]['C_NAME'];
				$i = $i + 1;
			}
			if($arrMenu[$index]['C_APPROVE']== '1'){
				if( $arrMenu[$index]['PK_WEB_MENU']==$arrArticle[0][FK_WEB_MENU]){
					$sShowApprove = 1;	
				}
				$sarrApprove = $sarrApprove.$arrMenu[$index]['PK_WEB_MENU'].',';
			}
		}	
		//$this->view->sShowEdit = $sShowEdit;
		//echo $sShowApprove;
		$this->view->sShowApprove = $sShowApprove;
		$this->view->arrMenuEdit = $arrMenuEdit;
		$this->view->sarrApprove = $sarrApprove;	
		
		//Gan quyen sang VIEW
		$this->view->PermissionUser = $this->_publicPermission;
		
		$idisabled = 0;
		if($arrArticle[0]['FK_CREATE_STAFF'] == $_SESSION['staff_id']){
			$idisabled = 1;  
		}
		$this->view->idisabled = $idisabled;
		//Lay cac tieu chi loc
		//Lay danh muc
		$sMenuID = $objFilter->filter($arrInput['C_MENU']);
		$this->view->sMenuID = $sMenuID;
		//Lay trang thai
		$iStatus = $objFilter->filter($arrInput['C_STATUS']);
		$this->view->iStatus = $iStatus;
		//Tim kiem
		$sFullTextSearch = $this->_request->getParam('FullTextSearch','');
		$this->view->FullTextSearch = $sFullTextSearch;
		// Lay trang so.../ quy dinh so tin bai tren mot trang
		$iCurrentPage = $this->_request->getParam('hdn_current_page','');		
		$iNumRowOnPage = $objFilter->filter($arrInput['hdn_record_number_page']);	
		//Truyen sang view
		$this->view->iCurrentPage = $iCurrentPage;
		$this->view->iNumRowOnPage = $iNumRowOnPage;
		//echo '$iNumRowOnPage'.$iNumRowOnPage;
		// Dua vao session											
		$arrParaSet = array("hdn_menuid"=>$sMenuID,"hdn_status"=>$iStatus,"hdn_fulltextseach"=>$sFullTextSearch,"hdn_current_page"=>$iCurrentPage,"hdn_record_number_page"=>$iNumRowOnPage);
		$_SESSION['seArrParameter'] = $arrParaSet;
		$this->_request->setParams($arrParaSet);
		//var_dump($_SESSION['seArrParameter']);
		//Lay anh da dinh kem tu truoc
		$arrImage = $objArticle->DOC_GetAllDocumentFileAttach($sWebArticleId,'WEB_IMAGE','T_WEB_ARTICLE');	
		$this->view->AttachImageFile = $objDocFun->DocSentAttachOneFile($arrImage,sizeof($arrImage),1,true,40,'article/image-upload/');	
		//Lay file dinh kem tu truoc
		$arFileAttach = $objArticle->DOC_GetAllDocumentFileAttach($sWebArticleId,'WEB_FILE','T_WEB_ARTICLE');	
		$this->view->AttachFile = $objDocFun->DocSentAttachFile($arFileAttach,sizeof($arFileAttach),10,true,80,'article/file-upload/');
		//Tuy chon ung voi cac truong hop update du lieu	
		$sOption = $this->_request->getParam('hdh_option','');
		$this->view->option = $sOption;
		//echo 'ok'.$objFilter->filter($arrInput['C_NAME']); exit;
		if ($objFilter->filter($arrInput['C_TITLE']) != ""){
			$sStaffName = $objDocFun->getNamePositionStaffByIdList($_SESSION['staff_id']);
			$iStatus = $objFilter->filter($arrInput['hdn_approved_status']);
			if($iStatus == '1'){
				$sApproved_staff = $_SESSION['staff_id'];
			}else{
				$sApproved_staff = '';
			}

			//Thuc hien upload anh len o cung
			$arrImageNameUpload = $ojbSysLib->_uploadFileList(1,$this->_request->getBaseUrl() . "/public/attach-file/article/image-upload/",'FileImageName','!#~$|*');
			//Thuc hien upload file dinh kem len o cung
			$arrFileNameUpload = $ojbSysLib->_uploadFileList(10,$this->_request->getBaseUrl() . "/public/attach-file/article/file-upload/",'FileName','!#~$|*');
			//var_dump($arrFileNameUpload); exit;	
			$sconten = str_replace(chr(92),'',htmlspecialchars($this->_request->getParam('C_CONTEN','')));
			$sconten = str_replace(chr(39),'',$sconten);	
			$arrParameter = array(	
								'PK_WEB_ARTICLE'				=>$sWebArticleId,										
								'FK_WEB_MENU'					=>$objFilter->filter($arrInput['PK_WEB_MENU']),
								'C_TITLE'						=>$ojbSysLib->_replaceBadChar($objFilter->filter($arrInput['C_TITLE'])),
								'C_SHORT_CONTENT'				=>$ojbSysLib->_replaceBadChar($objFilter->filter($arrInput['C_SUBJECT'])),
								'C_DETAIL_CONTENT'				=>$sconten,
								'C_IMAGE_TITLE'					=>$ojbSysLib->_replaceBadChar($objFilter->filter($arrInput['C_IMAGE_TITLE'])),
								'FK_CREATE_STAFF'				=>$_SESSION['staff_id'],
								'FK_CREATE_NAME'				=>$sStaffName,
								'FK_APPROVE_STAFF'				=>$sApproved_staff,
								'C_STATUS'						=>$iStatus,
								'IMAGE_NAME'					=>$arrImageNameUpload,
								'ATTACH_FILE_NAME_LIST'			=>$arrFileNameUpload
						);
			//var_dump($arrParameter); exit;				
			$Result = "";			
			$Result = $objArticle->WebArticleUpdate($arrParameter);				
			if($sOption =='GHI_THEMMOI'){
				$this->_redirect('/dashboard/article/add/');	
			}else{
				$this->_redirect('/dashboard/article/index/');
			}		
		}
	}
	
	/**
	 * 
	 * Enter description here ...
	 */
	public function deleteAction(){	
		// Tao doi tuong cho lop xu ly du lieu lien quan modul	
		$objArticle = new dashboard_modWebArticle();
		$ojbSysLib = new Sys_Library();
		$objFilter = new Zend_Filter();
		$arrInput = $this->_request->getParams();
		// Lay vi tri hien thi
		$iPosition = $this->_request->getParam('hdn_position',0);
		$this->view->iPosition = $iPosition;
		
		//Lay cac tieu chi loc
		//Lay danh muc
		$sMenuID = $objFilter->filter($arrInput['C_MENU']);
		$this->view->sMenuID = $sMenuID;
		//Lay trang thai
		$iStatus = $objFilter->filter($arrInput['C_STATUS']);
		$this->view->iStatus = $iStatus;
		//Tim kiem
		$sFullTextSearch = $this->_request->getParam('FullTextSearch','');
		$this->view->FullTextSearch = $sFullTextSearch;
		// Lay trang so.../ quy dinh so tin bai tren mot trang
		$iCurrentPage = $this->_request->getParam('hdn_current_page','');		
		$iNumRowOnPage = $objFilter->filter($arrInput['hdn_record_number_page']);	
		//echo '$iNumRowOnPage'.$iNumRowOnPage;
		// Dua vao session											
		$arrParaSet = array("hdn_menuid"=>$sMenuID,"hdn_status"=>$iStatus,"hdn_fulltextseach"=>$sFullTextSearch,"hdn_current_page"=>$iCurrentPage,"hdn_record_number_page"=>$iNumRowOnPage);
		$_SESSION['seArrParameter'] = $arrParaSet;
		$this->_request->setParams($arrParaSet);
		
		// Thuc hien lay du lieu tu form 		
		if($this->_request->isPost()){	
			// Lay toan bo tham so truyen tu form			
			$arrInput = $this->_request->getParams();							
			//Lay Id doi tuong VB can xoa
			$sArticleIdList = $this->_request->getParam('hdn_object_id_list',"");	
			//echo $sArticleIdList; exit;
			if ($sArticleIdList != ""){
				$sRetError = $objArticle->WebArticleDelete($sArticleIdList);
				// Neu co loi			
				if($sRetError != null || $sRetError != '' ){											
					echo "<script type='text/javascript'>";
					echo "alert('$sRetError');\n";				
					echo "</script>";
				}else {		
					//Tro ve trang index												
					$this->_redirect('/web/article/index/');				
				}
			}
		}	
	}
	/**
	 * 
	 * Enter description here ...
	 */
	public function moveAction(){	
		// Tao doi tuong cho lop xu ly du lieu lien quan modul	
		$objArticle = new dashboard_modWebArticle();
		$ojbSysLib = new Sys_Library();
		$objFilter = new Zend_Filter();
		$arrInput = $this->_request->getParams();
		//Lay cac tieu chi loc
		//Lay danh muc
		$sMenuID = $objFilter->filter($arrInput['C_MENU']);
		$this->view->sMenuID = $sMenuID;
		//Lay trang thai
		$iStatus = $objFilter->filter($arrInput['C_STATUS']);
		$this->view->iStatus = $iStatus;
		//Tim kiem
		$sFullTextSearch = $this->_request->getParam('FullTextSearch','');
		$this->view->FullTextSearch = $sFullTextSearch;
		// Lay trang so.../ quy dinh so tin bai tren mot trang
		$iCurrentPage = $this->_request->getParam('hdn_current_page','');		
		$iNumRowOnPage = $objFilter->filter($arrInput['hdn_record_number_page']);	
		//echo '$iNumRowOnPage'.$iNumRowOnPage;
		// Dua vao session											
		$arrParaSet = array("hdn_menuid"=>$sMenuID,"hdn_status"=>$iStatus,"hdn_fulltextseach"=>$sFullTextSearch,"hdn_current_page"=>$iCurrentPage,"hdn_record_number_page"=>$iNumRowOnPage);
		$_SESSION['seArrParameter'] = $arrParaSet;
		$this->_request->setParams($arrParaSet);
		//thuc hien di chuyen tin bai
		$cMove = $objFilter->filter($arrInput['hdn_move']);
		if($cMove == 'MOVE'){
			$iMoveOrder = $objFilter->filter($arrInput['hdn_move_order']);
			$sArticleID = $objFilter->filter($arrInput['hdn_object_id']);
			$sRetError = $objArticle->WebArticleMove($sArticleID,$iMoveOrder);
			$this->_redirect('/dashboard/article/index/');	
		}

	}
	/**
	 * 
	 * Enter description here ...
	 */
	public function approvedAction(){	
		// Tao doi tuong cho lop xu ly du lieu lien quan modul	
		$objArticle = new dashboard_modWebArticle();
		$ojbSysLib = new Sys_Library();
		$objFilter = new Zend_Filter();
		$arrInput = $this->_request->getParams();
		//Lay cac tieu chi loc
		//Lay danh muc
		$sMenuID = $objFilter->filter($arrInput['C_MENU']);
		$this->view->sMenuID = $sMenuID;
		//Lay trang thai
		$iStatus = $objFilter->filter($arrInput['C_STATUS']);
		$this->view->iStatus = $iStatus;
		//Tim kiem
		$sFullTextSearch = $this->_request->getParam('FullTextSearch','');
		$this->view->FullTextSearch = $sFullTextSearch;
		// Lay trang so.../ quy dinh so tin bai tren mot trang
		$iCurrentPage = $this->_request->getParam('hdn_current_page','');		
		$iNumRowOnPage = $objFilter->filter($arrInput['hdn_record_number_page']);	
		//echo '$iNumRowOnPage'.$iNumRowOnPage;
		// Dua vao session											
		$arrParaSet = array("hdn_menuid"=>$sMenuID,"hdn_status"=>$iStatus,"hdn_fulltextseach"=>$sFullTextSearch,"hdn_current_page"=>$iCurrentPage,"hdn_record_number_page"=>$iNumRowOnPage);
		$_SESSION['seArrParameter'] = $arrParaSet;
		$this->_request->setParams($arrParaSet);
		// Thuc hien lay du lieu tu form 		
		if($this->_request->isPost()){	
			$sArticleIdList = $this->_request->getParam('hdn_object_id_list',"");	
			//echo $sArticleIdList;exit;
			if ($sArticleIdList != ""){
				$sRetError = $objArticle->WebArticleApprove($sArticleIdList,$_SESSION['staff_id']);							
				$this->_redirect('/dashboard/article/index/');				
			}
		}	
	}
}
?>
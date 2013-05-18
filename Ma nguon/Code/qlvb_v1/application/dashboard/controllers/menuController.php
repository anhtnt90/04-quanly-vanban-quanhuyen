<?php
class dashboard_menuController extends  Zend_Controller_Action {	
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
		
		//Goi lop Listxml_modList
		//Zend_Loader::loadClass('web_modWebMenu');
			
		//Lay cac hang so su dung trong JS public
		Zend_Loader::loadClass('Sys_Init_Config');	
		$ojbSysInitConfig = new Sys_Init_Config();
		$this->view->UrlAjax = $ojbSysInitConfig->_setUrlAjax();	
		$this->view->arrConst =	$ojbSysInitConfig->_setProjectPublicConst();
		$this->view->JSPublicConst = $ojbSysInitConfig->_setJavaScriptPublicVariable();	
		//Ky tu dac biet phan tach giua cac phan tu
		$this->view->delimitor 			= $this->_ConstPublic['delimitor'];
		//Tao doi tuong XML
		Zend_Loader::loadClass('Sys_Publib_Xml');		
		
		// Load tat ca cac file Js va Css
		$this->view->LoadAllFileJsCss = Sys_Publib_Library::_getAllFileJavaScriptCss('','js','jsWeb.js,util.js,js_calendar.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','ajax.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','jquery-1.4.2.min.js,jQuery.equalHeights.js',',','js'). Sys_Publib_Library::_getAllFileJavaScriptCss('','js/LibSearch','actb_search.js,common_search.js',',','js');										
		/* Ket thuc*/
		
		//Dinh nghia current modul code
		$this->view->currentModulCode = "LIST";
		$this->view->currentModulCodeForLeft = "WEB_MENU";
				
		$sShowModel = $this->_request->getParam('showModalDialog','');
		$this->view->showModelDialog = $sShowModel;
		if ($sShowModel != 1){
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
 	}
		
	/**
	 * Idea: Thuc hien phuong thuc Action hien thi danh sach doi tuong
	 */
	public function indexAction(){	
		//Lay URL 
		$sUrl = $_SERVER['REQUEST_URI'];
		$this->view->sUrl = $sUrl;		
		// Tieu de man hinh danh sach
		$this->view->bodyTitle = "DANH SÁCH CHUYÊN MỤC";
		// Tao doi tuong Zend_Filter
		$objFilter = new Zend_Filter();
		$ojbSysLib = new Sys_Library();
		$objDocFun = new Sys_Function_DocFunctions();
		
		// Lay mang vi tri hien thi
		$arrPosition = array();
		$arrPosition[0]['C_CODE'] = 0;
		$arrPosition[0]['C_NAME'] = 'Bên trái';
		$arrPosition[1]['C_CODE'] = 1;
		$arrPosition[1]['C_NAME'] = 'Bên phải';
		$arrPosition[2]['C_CODE'] = 2;
		$arrPosition[2]['C_NAME'] = 'Thanh ngang bên trên';
		$arrPosition[3]['C_CODE'] = 3;
		$arrPosition[3]['C_NAME'] = 'Thanh ngang bên dưới';
		$this->view->arrPosition = $arrPosition;
		// Lay vi tri hien thi
		$iPosition = $this->_request->getParam('C_POSITION',0);
		$sOwner = $this->_request->getParam('C_OWNER','');	
		//Lay gia tri trong session
		if (isset($_SESSION['seArrParameter'])){
			$arrParaInSession = $_SESSION['seArrParameter'];
			//Tieu chi tim kiem
			$iPosition = $arrParaInSession['hdn_position'];
			$sOwner = $arrParaInSession['hdn_owner'];
			//Xoa gia tri trong session
			unset($_SESSION['seArrParameter']);								
		}
		$this->view->iPosition = $iPosition;
		$this->view->sOwner = $sOwner;

		//echo $sOwner;
		$objWebMenu = new dashboard_modWebMenu();
		//Thuc hien lay du lieu	
		$arrResul = $objWebMenu->WebMenuGetAll($iPosition,$sOwner,'3','2');
		$iNumberRecord = sizeof($arrResul);
		$this->view->arrResul = $arrResul;
	}
	/**
	 * 
	 * Enter description here ...
	 */
	public function getarticleAction(){	
		$sUrl = $_SERVER['REQUEST_URI'];
		$this->view->sUrl = $sUrl;		
		// Tieu de man hinh danh sach
		$this->view->bodyTitle = "DANH SÁCH TIN BÀI";
		// Tao doi tuong Zend_Filter
		$objFilter = new Zend_Filter();
		$ojbSysLib = new Sys_Library();
		$objDocFun = new Sys_Function_DocFunctions();
		$objWebMenu = new dashboard_modWebMenu();
		$arrInput = $this->_request->getParams();
		//Lay danh muc
		$sMenuID = $objFilter->filter($arrInput['C_MENU']);
		$this->view->sMenuID = $sMenuID;
		$sFullTextSearch = $this->_request->getParam('FullTextSearch','');
		$this->view->FullTextSearch = $sFullTextSearch;
		//Thuc hien lay du lieu	
		$arrMenu = $objWebMenu->WebArticlePermissionCheck($_SESSION['staff_id'],'EDIT_APPROVE','1');
		//var_dump($arrResul);
		// Lay trang so.../ quy dinh so tin bai tren mot trang
		$iCurrentPage = $this->_request->getParam('hdn_current_page','');		
		$iNumRowOnPage = 15;
		$iNumRowOnPage = $this->_request->getParam('hdn_record_number_page','');	
		if ($iCurrentPage <= 1){
			$iCurrentPage = 1;
		}
		if ($iNumRowOnPage <= $this->view->NumberRowOnPage){
			$iNumRowOnPage = $this->view->NumberRowOnPage;
		}	
		
		$this->view->arrMenu = $arrMenu;
		//Thuc hien lay du lieu	
		$arrResul = $objWebMenu->WebArticleGetAll('',$sMenuID,0,$sFullTextSearch,$iCurrentPage,$iNumRowOnPage);
		$iNumberRecord = sizeof($arrResul);
		$this->view->arrResul = $arrResul;
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
		$this->view->bodyTitle = 'THÔNG TIN CHUYÊN MỤC';
		$this->view->bodyRoleTitle = 'THÔNG TIN QUẢN TRỊ CHUYÊN MỤC';
		
		$arrInput = $this->_request->getParams();
		$objDocFun = new Sys_Function_DocFunctions();
		$objReceive = new dashboard_modWebMenu();
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
		
		// Lay mang vi tri hien thi
		$arrPosition = array();
		$arrPosition[0]['C_CODE'] = 0;
		$arrPosition[0]['C_NAME'] = 'Bên trái';
		$arrPosition[1]['C_CODE'] = 1;
		$arrPosition[1]['C_NAME'] = 'Bên phải';
		$arrPosition[2]['C_CODE'] = 2;
		$arrPosition[2]['C_NAME'] = 'Thanh ngang bên trên';
		$arrPosition[3]['C_CODE'] = 3;
		$arrPosition[3]['C_NAME'] = 'Thanh ngang bên dưới';
		$this->view->arrPosition = $arrPosition;
		
		// Lay mang cap chuyen muc
		$arrLevel = array();
		$arrLevel[0]['C_CODE'] = 0;
		$arrLevel[0]['C_NAME'] = 'Cấp 0';
		$arrLevel[1]['C_CODE'] = 1;
		$arrLevel[1]['C_NAME'] = 'Cấp 1';
		$arrLevel[2]['C_CODE'] = 2;
		$arrLevel[2]['C_NAME'] = 'Cấp 2';
		$this->view->arrLevel = $arrLevel;
		// Lay mang chuyen muc goc
		$arrMenu = $objReceive->WebMenuGetAll('4','','3','2');
		//var_dump($arrMenu);
		$arrMenu01 = array();
		$indexMenu = 0;
		for($index = 0;$index < sizeof($arrMenu);$index++){
			if($arrMenu[$index]['C_LEVEL']!='2'){
				$arrMenu01[$indexMenu]['PK_WEB_MENU'] = $arrMenu[$index]['PK_WEB_MENU'];
				$arrMenu01[$indexMenu]['C_NAME'] = $arrMenu[$index]['C_NAME'];
				$indexMenu++;
			}
		}
		$this->view->arrMenu01 = $arrMenu01;
		//Gan quyen sang VIEW
		$this->view->PermissionUser = $this->_publicPermission;
		
		// Lay vi tri hien thi
		$iPosition = $this->_request->getParam('hdn_position',0);
		$this->view->iPosition = $iPosition;
		
		// Lay don vi trien khai
		$sOwner = $this->_request->getParam('hdn_owner','');	
		$this->view->sOwner = $sOwner;
		
		// Dua vao session											
		$arrParaSet = array("hdn_position"=>$iPosition,"hdn_owner"=>$sOwner);
		$_SESSION['seArrParameter'] = $arrParaSet;
		$this->_request->setParams($arrParaSet);
		//echo 'ok'.$objFilter->filter($arrInput['C_NAME']); exit;
		if ($objFilter->filter($arrInput['C_NAME']) != ""){
			$iPublic = 1;
			if($objFilter->filter($arrInput['hdn_option_view'])=='KHAC'){
				$iPublic = 0;
			}
			$arrParameter = array(	
								'PK_WEB_MENU'					=>'',										
								'FK_WEB_MENU'					=>$objFilter->filter($arrInput['PK_WEB_MENU']),
								'C_NAME'						=>$objFilter->filter($arrInput['C_NAME']),
								'C_LEVEL'						=>$objFilter->filter($arrInput['C_LEVEL']),
								'C_URL'							=>$objFilter->filter($arrInput['C_URL']),
								'FK_WEB_ARTICLE'				=>$objFilter->filter($arrInput['hdn_article_id']),
								'C_POSITION'					=>$objFilter->filter($arrInput['C_POSTISION']),
								'C_WEB_DISPLAY'					=>$objFilter->filter($arrInput['hdn_display_web']),
								'C_WINDOWS_OPEN'				=>$objFilter->filter($arrInput['hdn_open_win']),
								'C_ORDER'						=>$objFilter->filter($arrInput['ORDER']),
								'C_STATUS'						=>$objFilter->filter($arrInput['hdn_status']),
								'C_PUBLIC_VIEW'					=>$iPublic,
								'C_EDIT_ID_LIST'				=>$objFilter->filter($arrInput['C_EDIT_ID_LIST']),
								'C_APPROVED_ID_LIST'			=>$objFilter->filter($arrInput['C_APPROVED_ID_LIST']),
								'C_VIEW_ID_LIST'				=>$objFilter->filter($arrInput['C_VIEW_ID_LIST']),
								'C_OWNER_CODE_LIST'				=>$objFilter->filter($arrInput['C_OWNER_CODE_LIST'])
						);
							
			$Result = "";			
			$Result = $objReceive->WebMenuUpdate($arrParameter);				
			$this->_redirect('/dashboard/menu/index/');		
		}
	}	
	/**
	 * Creater: cuongnh
	 * Date: 21/07/2011
	 * Idea: Thuc hien Action hieu chinh thong tin doi tuong
	 */
	public function editAction(){
		$this->view->bodyTitle = 'THÔNG TIN CHUYÊN MỤC';
		$this->view->bodyRoleTitle = 'THÔNG TIN QUẢN TRỊ CHUYÊN MỤC';
		
		$arrInput = $this->_request->getParams();
		$objDocFun = new Sys_Function_DocFunctions();
		$objMenu = new dashboard_modWebMenu();
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
		
		// Lay mang vi tri hien thi
		$arrPosition = array();
		$arrPosition[0]['C_CODE'] = 0;
		$arrPosition[0]['C_NAME'] = 'Bên trái';
		$arrPosition[1]['C_CODE'] = 1;
		$arrPosition[1]['C_NAME'] = 'Bên phải';
		$arrPosition[2]['C_CODE'] = 2;
		$arrPosition[2]['C_NAME'] = 'Thanh ngang bên trên';
		$arrPosition[3]['C_CODE'] = 3;
		$arrPosition[3]['C_NAME'] = 'Thanh ngang bên dưới';
		$this->view->arrPosition = $arrPosition;
		
		// Lay mang cap chuyen muc
		$arrLevel = array();
		$arrLevel[0]['C_CODE'] = 0;
		$arrLevel[0]['C_NAME'] = 'Cấp 0';
		$arrLevel[1]['C_CODE'] = 1;
		$arrLevel[1]['C_NAME'] = 'Cấp 1';
		$arrLevel[2]['C_CODE'] = 2;
		$arrLevel[2]['C_NAME'] = 'Cấp 2';
		$this->view->arrLevel = $arrLevel;
		// Lay mang chuyen muc goc
		$arrMenu = $objMenu->WebMenuGetAll('4','','3','2');
		//var_dump($arrMenu);
		$arrMenu01 = array();
		$indexMenu = 0;
		for($index = 0;$index < sizeof($arrMenu);$index++){
			if($arrMenu[$index]['C_LEVEL']!='2'){
				$arrMenu01[$indexMenu]['PK_WEB_MENU'] = $arrMenu[$index]['PK_WEB_MENU'];
				$arrMenu01[$indexMenu]['C_NAME'] = $arrMenu[$index]['C_NAME'];
				$indexMenu++;
			}
		}
		$this->view->arrMenu01 = $arrMenu01;
		//Gan quyen sang VIEW
		$this->view->PermissionUser = $this->_publicPermission;
		//Lay thong tin chi tiet
		$sWebMenuId = $this->_request->getParam('hdn_object_id','');		
		$this->view->sWebMenuId = $sWebMenuId;
		$arrMenu = $objMenu->WebMenuGetSingle($sWebMenuId);
		$this->view->arrMenu = $arrMenu;
		$sArticleID = $arrMenu[0]['FK_WEB_ARTICLE'];
		$this->view->sArticleID = $sArticleID;
		//var_dump($arrMenu);
		// Lay vi tri hien thi
		$iPosition = $this->_request->getParam('hdn_position',0);
		$this->view->iPosition = $iPosition;
		
		// Lay don vi trien khai
		$sOwner = $this->_request->getParam('hdn_owner','');	
		$this->view->sOwner = $sOwner;
		// Dua vao session											
		$arrParaSet = array("hdn_position"=>$iPosition,"hdn_owner"=>$sOwner);
		$_SESSION['seArrParameter'] = $arrParaSet;
		$this->_request->setParams($arrParaSet);
		
		
		if ($objFilter->filter($arrInput['C_NAME']) != ""){
			$iPublic = 1;
			if($objFilter->filter($arrInput['hdn_option_view'])=='KHAC'){
				$iPublic = 0;
			}
			$arrParameter = array(	
								'PK_WEB_MENU'					=>$sWebMenuId,										
								'FK_WEB_MENU'					=>$objFilter->filter($arrInput['PK_WEB_MENU']),
								'C_NAME'						=>$objFilter->filter($arrInput['C_NAME']),
								'C_LEVEL'						=>$objFilter->filter($arrInput['C_LEVEL']),
								'C_URL'							=>$objFilter->filter($arrInput['C_URL']),
								'FK_WEB_ARTICLE'				=>$objFilter->filter($arrInput['hdn_article_id']),
								'C_POSITION'					=>$objFilter->filter($arrInput['C_POSTISION']),
								'C_WEB_DISPLAY'					=>$objFilter->filter($arrInput['hdn_display_web']),
								'C_WINDOWS_OPEN'				=>$objFilter->filter($arrInput['hdn_open_win']),
								'C_ORDER'						=>$objFilter->filter($arrInput['ORDER']),
								'C_STATUS'						=>$objFilter->filter($arrInput['hdn_status']),
								'C_PUBLIC_VIEW'					=>$iPublic,
								'C_EDIT_ID_LIST'				=>$objFilter->filter($arrInput['C_EDIT_ID_LIST']),
								'C_APPROVED_ID_LIST'			=>$objFilter->filter($arrInput['C_APPROVED_ID_LIST']),
								'C_VIEW_ID_LIST'				=>$objFilter->filter($arrInput['C_VIEW_ID_LIST']),
								'C_OWNER_CODE_LIST'				=>$objFilter->filter($arrInput['C_OWNER_CODE_LIST'])
						);
							
			$Result = "";			
			$Result = $objMenu->WebMenuUpdate($arrParameter);	
			if($Result == 'LOI1' ){											
					echo "<script type='text/javascript'>";
					echo "alert('Không thể thêm chuyên mục với vị trí này');\n";				
					echo "</script>";
			}else {		
					//Tro ve trang index												
					$this->_redirect('/dashboard/menu/index/');				
			}			
		}
	}
	
	/**
	 * 
	 * Enter description here ...
	 */
	public function deleteAction(){	
		// Tao doi tuong cho lop xu ly du lieu lien quan modul	
		$objMenu = new dashboard_modWebMenu();
		$ojbSysLib = new Sys_Library();
		// Lay vi tri hien thi
		$iPosition = $this->_request->getParam('hdn_position',0);
		$this->view->iPosition = $iPosition;
		
		// Lay don vi trien khai
		$sOwner = $this->_request->getParam('hdn_owner','');	
		$this->view->sOwner = $sOwner;
		// Dua vao session											
		$arrParaSet = array("hdn_position"=>$iPosition,"hdn_owner"=>$sOwner);
		$_SESSION['seArrParameter'] = $arrParaSet;
		$this->_request->setParams($arrParaSet);
		// Thuc hien lay du lieu tu form 		
		if($this->_request->isPost()){	
			// Lay toan bo tham so truyen tu form			
			$arrInput = $this->_request->getParams();							
			//Lay Id doi tuong VB can xoa
			$sMenuIdList = $this->_request->getParam('hdn_object_id_list',"");	
			//echo $sMenuIdList; exit;
			if ($sMenuIdList != ""){
				$sRetError = $objMenu->WebMenuDelete($sMenuIdList);
				// Neu co loi			
				if($sRetError != null || $sRetError != '' ){											
					echo "<script type='text/javascript'>";
					echo "alert('$sRetError');\n";				
					echo "</script>";
				}else {		
					//Tro ve trang index												
					$this->_redirect('/dashboard/menu/index/');				
				}
			}
		}	
	}
}
?>
<?php
class authorized_authorizedController extends  Zend_Controller_Action {
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
		//Zend_Loader::loadClass('Sys_Init_Config');
		$objConfig = new Sys_Init_Config();
		$this->view->UrlAjax = $objConfig->_setUrlAjax();			
		//Load cau hinh thu muc trong file config.ini de lay ca hang so dung chung
        $tempConstPublic = Zend_Registry::get('ConstPublic');
		$this->_ConstPublic = $tempConstPublic->toArray();
		
		//Lay cac gia tri const
		$ojbSysInitConfig = new Sys_Init_Config();
		$this->view->arrConst =	$ojbSysInitConfig->_setProjectPublicConst();
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
		/*
		//Lay nhac viec cho cong viec
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
		//Neu khong co gia tri thì lay trong cookie	
		if ($sliidvisit == "" || is_null($sliidvisit) || !isset($sliidvisit)){
			$sliidvisit = Sys_Library::_getCookie("headervisit");
		}else{
			Sys_Library::_createCookie("headervisit",$sliidvisit);
		}		
		$this->view->sliidvisit = $sliidvisit;	
		$sleftmenu = $this->_request->getParam('sleftmenu','');		
		if ($sleftmenu == "" || is_null($sleftmenu) || !isset($sleftmenu)){
			$sleftmenu = Sys_Library::_getCookie("leftvisit");
		}else{
			Sys_Library::_createCookie("leftvisit",$sleftmenu);
		}		
		$this->view->sleftmenu = $sleftmenu;				
		Zend_Loader::loadClass('authorized_modauthorized');
		Zend_Loader::loadClass('Sent_modSent');		
		$this->view->JSPublicConst = $objConfig->_setJavaScriptPublicVariable();					
		// Load tat ca cac file Js va Css
		$JSandStyle= Sys_Publib_Library::_getAllFileJavaScriptCss('','js','util.js,js_calendar.js,jsSchedule.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','ajax.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','jquery-1.4.2.min.js,jQuery.equalHeights.js',',','js'). Sys_Publib_Library::_getAllFileJavaScriptCss('','js/LibSearch','actb_search.js,common_search.js',',','js');
		
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
		$this->view->currentModulCode = "AUTHORIED";				
		//Modul chuc nang						
		$this->view->currentModulCodeForLeft ="AUTHORIED-DOC";
		$this->view->getStatusLeftMenu = $this->_request->getParam('status','');			
		//Lay trang thai left menu
		$this->view->getStatusLeftMenu = $this->_request->getParam('status','');
		//echo 'status = '.$this->_request->getParam('status','');	
		//Lay Quyen PHAN CONG XU LY VB DEN
		//$this->_publicPermission = Sys_Function_DocFunctions::Doc_AssignDocument($_SESSION['arrStaffPermission'],$_SESSION['staff_id']);	
		//Gan quyen PCXL sang VIEW
		//$this->view->PermissionAssigner = $this->_publicPermission;		
		//Mang quyen cua NSD hien thoi
		$arrPermission = $_SESSION['arrStaffPermission'];				
		//Hien thi file template
		$response->insert('header', $this->view->renderLayout('twd_header.phtml','template/'));    
		$response->insert('left', $this->view->renderLayout('twd_left.phtml','template/'));  	    
        $response->insert('footer', $this->view->renderLayout('twd_footer.phtml','template/'));   
              
  	}	
	/**
	 * Idea : Phuong thuc hien thi Lich ca nhan
	 *
	 */
	public function indexAction(){
				
		//Lay URL	
		$sUrl = $_SERVER['REQUEST_URI'];
		//Lay trang thai 
		$sStatus = $this->_request->getParam('status','');
		$this->view->sStatus = $sStatus;						
		// Tao doi tuong Zend_Filter
		$objFilter = new Zend_Filter();
		$ojbSysLib = new Sys_Library();
		$ojbauthorized = new authorized_modauthorized();
		$objDocFun = new Sys_Function_DocFunctions();
		//Lay cac gia tri const
		$ojbSysInitConfig = new Sys_Init_Config();
		$arrPositionConst =	$ojbSysInitConfig->_setLeaderPostionGroup();		
		// Tieu de man hinh danh sach
		$this->view->bodyTitle = "DANH CÁN BỘ ỦY ĐƯỢC ỦY QUYỀN";
		//$this->view->bodyTitle = "Lịch làm việc của đồng chí ".$ojbSysLib->_InforStaff(); 				
		$arrInput = $this->_request->getParams();
		$objSent   = new Sent_modSent();
		$arrUathorized = $objSent->getSignByUnit('DM_NGUOI_KY',$_SESSION['arr_all_staff']);
		$arrAllAuthorized = $ojbauthorized->Authorized_getAll();
		//var_dump($arrAllAuthorized);
		$this->view->arrUathorized = $arrUathorized;
		$this->view->arrAllAuthorized = $arrAllAuthorized; 
	}	
	
public function addAction(){		
		$sUrl = $_SERVER['REQUEST_URI'];
		$this->view->bodyTitle = "CẬP NHẬT CÁN BỘ ỦY ĐƯỢC ỦY QUYỀN";
		//Lay trang thai 
		$sStatus = $this->_request->getParam('status','');
		$this->view->sStatus = $sStatus;						
		// Tao doi tuong Zend_Filter
		$objFilter = new Zend_Filter();
		$ojbSysLib = new Sys_Library();
		$objDocFun = new Sys_Function_DocFunctions();
		$objAuthoried = new authorized_modauthorized();
		$arrInput = $this->_request->getParams();	
		//Lay cac gia tri const
		$ojbSysInitConfig = new Sys_Init_Config();		
		$id_leader = $this->_request->getParam('hdn_object_id','');		 
		$this->view->sStaffID = $id_leader; 		
		$status_update = $this->_request->getParam('htn_schedule_update','');
		$status_authori = $this->_request->getParam('hdn_approve_schedule','');
		$startDate = $this->_request->getParam('C_START_DATE','');
		$endDate = $this->_request->getParam('C_END_DATE','');
		
		$sStaffName = $objDocFun->convertStaffIdToStaffName($id_leader);		
		$this->view->sStaffName = $sStaffName; 
		
		if($status_update){
			$arrParameter = array(									
							'C_ID_LEADER'				=>$this->_request->getParam('C_STAFF_ID',''),
							'C_NAME'					=>$this->_request->getParam('C_NAME',''),
							'C_START_DATE'				=>$ojbSysLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['C_START_DATE'])),
							'C_END_DATE'				=>$ojbSysLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['C_END_DATE'])),
							'C_STATUS'					=>$status_authori,								
			);		
		$Result = "";			
		$Result = $objAuthoried->authorized_Update($arrParameter);	
		$this->_redirect('authorized/authorized/index/');								
		}
	}
	public function deleteAction(){
		$sUrl = $_SERVER['REQUEST_URI'];				
		$sStatus = $this->_request->getParam('status','');
		$this->view->sStatus = $sStatus;						
		// Tao doi tuong Zend_Filter
		$objFilter = new Zend_Filter();
		$ojbSysLib = new Sys_Library();
		$objDocFun = new Sys_Function_DocFunctions();
		$objAuthoried = new authorized_modauthorized();
		$arrInput = $this->_request->getParams();	
		//Lay cac gia tri const
		$ojbSysInitConfig = new Sys_Init_Config();
		$id_leader = $this->_request->getParam('hdn_object_id','');		
		$objAuthoried->authorized_delete($id_leader);
		$this->_redirect('authorized/authorized/index/');
	
}	
	
	
	
}
?>
<?php
class dashboard_homeController extends  Zend_Controller_Action {		
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
		
        $tempConstPublic = Zend_Registry::get('ConstPublic');
		$this->_ConstPublic = $tempConstPublic->toArray();						
		$this->view->baseUrl = $this->_request->getBaseUrl() . "/public/";				
		Zend_Loader::loadClass('dashboard_modWebMenu');		
		$objWebMenu = new dashboard_modWebMenu();			
		$arrResul = $objWebMenu->WebMenuGetAll('4',$_SESSION['OWNER_CODE'],'3','1');
		$this->view->arrMenu = $arrResul;			
		$sliidvisit = $this->_request->getParam('sliid','');
		$sleftmenu = $this->_request->getParam('sleftmenu','');			
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
		Zend_Loader::loadClass('Sys_Init_Config');	
		$ojbSysInitConfig = new Sys_Init_Config();
		$this->view->UrlAjax = $ojbSysInitConfig->_setUrlAjax();	
		$this->view->arrConst =	$ojbSysInitConfig->_setProjectPublicConst();
		$this->view->JSPublicConst = $ojbSysInitConfig->_setJavaScriptPublicVariable();			
		$this->view->CountInMenu = $ojbSysInitConfig->_setCountInMenu();			
		$this->view->delimitor 			= $this->_ConstPublic['delimitor'];
		Zend_Loader::loadClass('Sys_Publib_Xml');			
		$this->view->LoadAllFileJsCss = Sys_Publib_Library::_getAllFileJavaScriptCss('','js','jsWeb.js,util.js,js_calendar.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','ajax.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','jquery-1.4.2.min.js,jQuery.equalHeights.js',',','js'). Sys_Publib_Library::_getAllFileJavaScriptCss('','js/LibSearch','actb_search.js,common_search.js',',','js');												
		$this->view->currentModulCode = "WEB_HOME";
		$this->view->currentModulCodeForLeft = "WEB_HOME";					
		$sGetValueInCookie = Sys_Library::_getCookie("showHideMenu");			
		if ($sGetValueInCookie == "" || is_null($sGetValueInCookie) || !isset($sGetValueInCookie)){
			Sys_Library::_createCookie("showHideMenu",1);
			Sys_Library::_createCookie("ImageUrlPath",$this->_request->getBaseUrl() . "/public/images/close_left_menu.gif");
			$this->view->hideDisplayMeneLeft = 1;			
			$this->view->ShowHideimageUrlPath = $this->_request->getBaseUrl() . "/public/images/close_left_menu.gif";
		}else{
			if ($sGetValueInCookie != 0){
				$this->view->hideDisplayMeneLeft = 1;
			}else{
				$this->view->hideDisplayMeneLeft = "";
			}		
			$this->view->ShowHideimageUrlPath = Sys_Library::_getCookie("ImageUrlPath");
		}
		
		$response->insert('header', $this->view->renderLayout('twd_header.phtml','template/'));    
		$response->insert('left', $this->view->renderLayout('twd_left_list.phtml','template/'));  	    
        $response->insert('footer', $this->view->renderLayout('twd_footer.phtml','template/'));
 	}	
	public function indexAction(){	
		//Lay URL 
		$sUrl = $_SERVER['REQUEST_URI'];
		$this->view->sUrl = $sUrl;		
		// Tieu de lich cong tac
		$this->view->sheTitle = "LỊCH CÔNG TÁC TRONG NGÀY";
		// Tieu de CONG VIEC
		$this->view->worTitle = "CÔNG VIỆC CẦN XỬ LÝ";
		// Tao doi tuong Zend_Filter
		$objFilter = new Zend_Filter();
		$ojbSysLib = new Sys_Library();
		$objDocFun = new Sys_Function_DocFunctions();
		$objWebMenu = new web_modWebMenu();
		//Lay thong tin lich ca nhan trong ngay
		$v_year = date('Y');					
		$v_week = date("W");
		$v_day = date("D");	
		$sOwner_name = $_SESSION['OWNER_CODE'];	
		$arrScheduleStaff = $objWebMenu->Schedule_StaffGetSingle($_SESSION['staff_id'],$v_week,$v_year);
		//var_dump($arrScheduleStaff);
		
		//$this->view->arrScheduleStaff = $arrScheduleStaff;
		if($v_day =='Mon'){
			$v_day ='THU_2';
			$v_day_staff = $arrScheduleStaff[0]['C_MON'];
		}
		if($v_day =='Tue'){
			$v_day ='THU_3';
			$v_day_staff = $arrScheduleStaff[0]['C_TUE'];
		}	
		if($v_day =='Wed'){
			$v_day ='THU_4';
			$v_day_staff = $arrScheduleStaff[0]['C_WED'];
		}
		if($v_day =='Thu'){
			$v_day ='THU_5';
			$v_day_staff = $arrScheduleStaff[0]['C_THU'];
		}
		if($v_day =='Fri'){
			$v_day ='THU_6';
			$v_day_staff = $arrScheduleStaff[0]['C_FRI'];
		}
		if($v_day =='Sat'){
			$v_day ='THU_7';
			$v_day_staff = $arrScheduleStaff[0]['C_SAT'];
		}
		if($v_day =='Sun'){
			$v_day ='THU_8';
			$v_day_staff = $arrScheduleStaff[0]['C_SUN'];	
		}	
		
		$this->view->v_day_staff = $v_day_staff;		
		$arrScheduleToday = $objWebMenu->ScheduleUnitGetToday($v_week,$v_year,$v_day,1,$sOwner_name);
		$this->view->arrScheduleToday = $arrScheduleToday;	
		$sStaffRole = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff_keep'],$_SESSION['staff_id'],'position_group_code');  
		$sUnitID = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff_keep'],$_SESSION['staff_id'],'unit_id');
		if($_SESSION['OWNER_ID']==Sys_Init_Config::_setParentOwnerId()){
			$sUnitType = 'PHONG_BAN';
		}else{
			$sUnitType = 'PHUONG_XA';
		}
		$arrTaskNoty = $objWebMenu->TaskWorkNotyGetAll($_SESSION['staff_id'],$sStaffRole,$sUnitID,$sUnitType);
		$this->view->arrTaskNoty = $arrTaskNoty;		
		$objSession = new Sys_Init_Session();
		$arrPermission = $objSession->SesGetAllPermissionForSession($_SESSION['staff_id']);
		$sPermissionList = '';
		foreach ($arrPermission as $key=>$value){			
			$sPermissionList = $sPermissionList.$key.',';
		}
		$sRoleLeader = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'position_code');
		$iPosition= $objDocFun->docTestUser($_SESSION['staff_id']);
		$ojbSysInitConfig = new Sys_Init_Config();
		$arrPositionConst =	$ojbSysInitConfig->_setLeaderPostionGroup();
		$arrReminder = $objWebMenu->docReminderGetAll($_SESSION['staff_id'],$sUnitID,$_SESSION['OWNER_ID'],$sPermissionList,$sRoleLeader,$iPosition,$arrPositionConst['_CONST_MAIN_LEADER_POSITION_GROUP'],$arrPositionConst['_CONST_SUB_LEADER_POSITION_GROUP']);
		$iDocNoti = 0;
		for($index = 0;$index < sizeof($arrReminder);$index++){
			if($arrReminder[$index]['C_COUNT'] > 0){
				$iDocNoti = 1;
				break;
			}
		}
		$this->view->iDocNoti = $iDocNoti;
		$arrMenu = $this->view->arrMenu;			
		$MenuIdList = '';
		$i = 0;
		for($index = 0;$index < sizeof($arrMenu);$index++){
			if($arrMenu[$index]['C_WEB_DISPLAY']== '1'){
				$MenuIdList.= $arrMenu[$index]['PK_WEB_MENU'].',';
				$arrMenuHome[$i]['PK_WEB_MENU'] = $arrMenu[$index]['PK_WEB_MENU'];
				$arrMenuHome[$i]['C_NAME'] = $arrMenu[$index]['C_NAME'];
				$arrMenuHome[$i]['FK_WEB_MENU'] = $arrMenu[$index]['FK_WEB_MENU'];
				$arrMenuHome[$i]['FK_WEB_ARTICLE'] = $arrMenu[$index]['FK_WEB_ARTICLE'];	
				$arrMenuHome[$i]['C_WINDOWS_OPEN'] = $arrMenu[$index]['C_WINDOWS_OPEN'];		
				$arrMenuHome[$i]['C_VISIT_TOP'] = 'li'.$arrMenu[$index]['C_ORDER_LEVER1'];
				$arrMenuHome[$i]['C_VISIT_LEFT'] = 'mn_'.$arrMenu[$index]['C_ORDER_LEVER1'].'_'.$arrMenu[$index]['C_ORDER_LEVER2'].'_'.$arrMenu[$index]['C_ORDER_LEVER3'];
				$i = $i + 1;
			}
		}
		$MenuIdList = substr($MenuIdList,0,-1);
		$itr = ((int)($i/2) + ($i%2));		
		$arrResul = $objWebMenu->WebHomeInfoGetAll($MenuIdList,$this->view->CountInMenu);
		$this->view->arrResul = $arrResul;
		$this->view->arrMenuHome = $arrMenuHome;
		$this->view->countMenu = $itr;
	}
	public function getarticleAction(){			
		$sUrl = $_SERVER['REQUEST_URI'];
		$this->view->sUrl = $sUrl;				
		$objFilter = new Zend_Filter();
		$ojbSysLib = new Sys_Library();
		$objDocFun = new Sys_Function_DocFunctions();
		$objWebMenu = new web_modWebMenu();		
		$ArticleID = $this->_request->getParam('articleid','');
		if($ArticleID != ''){
			$sleftmenu = $this->_request->getParam('sleftmenu','');
			$this->_redirect('web/home/viewarticle/?sleftmenu='.$sleftmenu.'&hdn_object_id='.$ArticleID);
		}else{			
			$iCurrentPage = $this->_request->getParam('hdn_current_page','');		
			if ($iCurrentPage <= 1){
				$iCurrentPage = 1;
			}		
			$icountArticle = Sys_Init_Config::_setCountInArticle();			
			$MenuId = $this->_request->getParam('menuid','');
			$this->view->MenuId = $MenuId;
			$MenuName = $this->_request->getParam('menuname','');
			$this->view->Menuname = Sys_Publib_Library::_convertETXToUnicode($MenuName);	
			$arrResul = $objWebMenu->WebArticleGetAll('',$MenuId,'1','',$iCurrentPage,$icountArticle);
			$this->view->arrResul = $arrResul;			
			$iNumberRecord = $arrResul[0]['C_TOTAL_RECORD'];
			if (count($arrResul) > 0){				
				$this->view->generateStringNumberPage = Sys_Publib_Library::_generateStringNumberPage($iNumberRecord, $iCurrentPage, $icountArticle,$sUrl) ;
			}			
		}
	}
	public function viewarticleAction(){			
		$sUrl = $_SERVER['REQUEST_URI'];
		$this->view->sUrl = $sUrl;				
		$objFilter = new Zend_Filter();
		$ojbSysLib = new Sys_Library();
		$objDocFun = new Sys_Function_DocFunctions();
		$objWebMenu = new web_modWebMenu();		
		$ArticleId = $this->_request->getParam('hdn_object_id','');
		$this->view->ArticleId = $ArticleId;	
		$arrResul = $objWebMenu->WebArticleGetSingle($ArticleId);
		$this->view->arrResul = $arrResul;	
	}
}
?>
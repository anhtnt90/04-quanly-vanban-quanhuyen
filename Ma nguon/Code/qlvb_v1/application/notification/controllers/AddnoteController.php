<?php
class notification_AddnoteController extends  Zend_Controller_Action {	
	public $_ArchivesStaffPermission;
	public $_DistributionPermission;
	public $_AssignPermission;
	public function init(){		
		//Load cau hinh thu muc trong file config.ini
        $tempDirApp = Zend_Registry::get('conDirApp');
		$this->_dirApp = $tempDirApp->toArray();
		$this->view->dirApp = $tempDirApp->toArray();		
		//Cau hinh cho Zend_layoutasdfsdfsd
		Zend_Layout::startMvc(array(
			    'layoutPath' => $this->_dirApp['layout'],
			    'layout' => 'index'			    
			    ));			
		$response = $this->getResponse();		
        $tempConstPublic = Zend_Registry::get('ConstPublic');
		$this->_ConstPublic = $tempConstPublic->toArray();			
		$this->view->delimitor 			= $this->_ConstPublic['delimitor'];		
		$this->view->baseUrl = $this->_request->getBaseUrl() . "/public/";		
		Zend_Loader::loadClass('notification_modNotification');		
		Zend_Loader::loadClass('Sys_Publib_Xml');
		$objDocFun = new Sys_Function_DocFunctions();	
		Zend_Loader::loadClass('Sys_Init_Config');
		Zend_Loader::loadClass('Sys_Init_Session');
		$objConfig = new Sys_Init_Config();
		$this->view->JSPublicConst = $objConfig->_setJavaScriptPublicVariable();				
		$objPublicLibrary = new Sys_Library();				
		$sLoadFileJSCSS  = Sys_Publib_Library::_getAllFileJavaScriptCss('public/sys-js/ListType','','','','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('public/js/ListType','','','','css');
		$sLoadFileJSCSS .= Sys_Publib_Library::_getAllFileJavaScriptCss('','js','js_calendar.js',',','js');		
		$sLoadFileJSCSS .= Sys_Publib_Library::_getAllFileJavaScriptCss('','js','Receive.js',',','js');		
		$this->view->LoadAllFileJsCss = $sLoadFileJSCSS;	
		Zend_Loader::loadClass('dashboard_modWebMenu');
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
		$sPeriodCode = $this->_request->getParam('period',"");
		$PeriodCode = "";			
		$this->view->periodCode = $PeriodCode['periodCode']; 
		$this->view->periodStep = $PeriodCode['periodStep'];		
		$psModulName = $this->_request->getParam('hdn_function_modul',"");			
		$this->view->functionModul = $psModulName;	
		$this->view->currentModulCode = "ADDNOTE";
		$this->view->currentModulCodeForLeft = "REMINDER";	
		$this->view->ShowHideimageUrlPath = Sys_Library::_getCookie("ImageUrlPath");
		$sGetValueInCookie = Sys_Library::_getCookie("showHideMenu");
		if ($sGetValueInCookie == "" || is_null($sGetValueInCookie) || !isset($sGetValueInCookie)){
			Sys_Library::_createCookie("showHideMenu",1);
			Sys_Library::_createCookie("ImageUrlPath",$this->_request->getBaseUrl() . "/public/images/close_left_menu.gif");
			$this->view->hideDisplayMeneLeft = 1;// = 1 : hien thi menu
			$this->view->ShowHideimageUrlPath = $this->_request->getBaseUrl() . "/public/images/close_left_menu.gif";
		}else{
			if ($sGetValueInCookie != 0){
				$this->view->hideDisplayMeneLeft = 1;// = 1 : hien thi menu
			}else{
				$this->view->hideDisplayMeneLeft = "";// = "" : an menu
			}			
			$this->view->ShowHideimageUrlPath = Sys_Library::_getCookie("ImageUrlPath");
		}  	
		$response->insert('header', $this->view->renderLayout('twd_header.phtml','template/'));    
		$response->insert('left', $this->view->renderLayout('twd_left.phtml','template/'));  	    
        $response->insert('footer', $this->view->renderLayout('twd_footer.phtml','template/'));

	}		
	public function indexAction(){
		$objSession = new Sys_Init_Session();
		$objReminder = new notification_modNotification();	
		$objFunction =	new	Sys_Function_DocFunctions()	;
		//Lay cac gia tri const
		$ojbSysInitConfig = new Sys_Init_Config();
		$arrPositionConst =	$ojbSysInitConfig->_setLeaderPostionGroup();
		$iUserId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
		$iDepartmentId  = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');		
		$iOwnerId = $_SESSION['OWNER_ID'];
		$sRoleLeader = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$iUserId,'position_code');
		$arrPermission = $objSession->SesGetAllPermissionForSession($iUserId);
		$sPermissionList = '';		
		foreach ($arrPermission as $key=>$value){			
			$sPermissionList = $sPermissionList.$key.',';
		}
		$iPosition= $objFunction->docTestUser($iUserId);
		$this->view->bodyTitle = 'CÁC CÔNG VIỆC CẦN XỬ LÝ'; //CỦA ĐỒNG CHÍ: ' . Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'name');
		$arrInput = $this->_request->getParams();		
		$arrReminder = $objReminder->docReminderGetAll($iUserId,$iDepartmentId,$iOwnerId,$sPermissionList,$sRoleLeader,$iPosition,$arrPositionConst['_CONST_MAIN_LEADER_POSITION_GROUP'],$arrPositionConst['_CONST_SUB_LEADER_POSITION_GROUP']);
		$vb_den = 0; $vb_di = 0; $gui_nhan_vb = 0;$cong_viec = 0;$iCountVb_den = 0;$iCountVb_di = 0;$iCountGui_nhan = 0;$iCountCong_viec = 0;
		for($i=0;$i<sizeof($arrReminder);$i++){
			If($arrReminder[$i]['C_DOCTYPE'] == 'VB_DEN'){
				If($arrReminder[$i]['C_COUNT'] > 0){
					$iCountVb_den ++;
				}
				$arrReceived[$vb_den]['C_COUNT'] = $arrReminder[$i]['C_COUNT'];
				$arrReceived[$vb_den]['C_STATUS'] = $arrReminder[$i]['C_STATUS'];
				$vb_den ++;
			}
			If($arrReminder[$i]['C_DOCTYPE'] == 'VB_DI'){
				If($arrReminder[$i]['C_COUNT'] > 0){
					$iCountVb_di ++;
				}
				$arrSent[$vb_di]['C_COUNT'] = $arrReminder[$i]['C_COUNT'];
				$arrSent[$vb_di]['C_STATUS'] = $arrReminder[$i]['C_STATUS'];
				$vb_di ++;
			}
			If($arrReminder[$i]['C_DOCTYPE'] == 'GUI_NHAN_VB'){
				If($arrReminder[$i]['C_COUNT'] > 0){
					$iCountGui_nhan ++;
				}
				$arrSentReceived[$gui_nhan_vb]['C_COUNT'] = $arrReminder[$i]['C_COUNT'];
				$arrSentReceived[$gui_nhan_vb]['C_STATUS'] = $arrReminder[$i]['C_STATUS'];
				$gui_nhan_vb++;
			}
			If($arrReminder[$i]['C_DOCTYPE'] == 'CONG_VIEC'){
				If($arrReminder[$i]['C_COUNT'] > 0){
					$iCountCong_viec ++;
				}
				$arrWork[$cong_viec]['C_COUNT'] = $arrReminder[$i]['C_COUNT'];
				$arrWork[$cong_viec]['C_STATUS'] = $arrReminder[$i]['C_STATUS'];
				$cong_viec++;
			}
		}
		$this->view->arrReceived = $arrReceived;
		$this->view->arrSent = $arrSent;
		$this->view->arrSentReceived = $arrSentReceived;
		$this->view->arrWork  = $arrWork;
		//Tong so VB cua moi loai
		$this->view->iCountVb_den = $iCountVb_den;
		$this->view->iCountVb_di = $iCountVb_di;
		$this->view->iCountGui_nhan = $iCountGui_nhan;
		$this->view->iCountCong_viec = $iCountCong_viec;
	}
}
?>
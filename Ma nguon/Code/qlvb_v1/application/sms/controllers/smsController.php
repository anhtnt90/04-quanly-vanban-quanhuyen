<?php
/**
 * Class Xu ly thong thong tin loai danh muc
 */
class sms_smsController extends  Zend_Controller_Action {

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
		//Load ca thanh phan cau vao trang layout (index.phtml)
		$response = $this->getResponse();		
		//Load cau hinh thu muc trong file config.ini de lay ca hang so dung chung
        $tempConstPublic = Zend_Registry::get('ConstPublic');
		$this->_ConstPublic = $tempConstPublic->toArray();			
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
	
		//Goi lop Listxml_modProject
		Zend_Loader::loadClass('Sms_modSms');		
		//Tao doi tuong XML
		Zend_Loader::loadClass('Sys_Publib_Xml');
		$objDocFun = new Sys_Function_DocFunctions();	
		//Lay cac hang so su dung trong JS public
		Zend_Loader::loadClass('Sys_Init_Config');
		Zend_Loader::loadClass('Sys_Init_Session');
		$objConfig = new Sys_Init_Config();
		$this->view->JSPublicConst = $objConfig->_setJavaScriptPublicVariable();		
		/* Dung de load file Js va css		/*/
		// Goi lop public		
		$objPublicLibrary = new Sys_Library();			
			// Load tat ca cac file Js va Css
		$this->view->LoadAllFileJsCss = Sys_Publib_Library::_getAllFileJavaScriptCss('','sys-js','jsUser.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','sys-js','ajax.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','sys-js','jquery-1.4.2.min.js,jQuery.equalHeights.js',',','js'). Sys_Publib_Library::_getAllFileJavaScriptCss('','sys-js/LibSearch','actb_search.js,common_search.js',',','js');
		
		//-------------Lay ma giai doan thuc hien-------------------------	
		$sPeriodCode = $this->_request->getParam('period',"");
		$PeriodCode = "";//$objDocFun->DocGetPeriodParameter($sPeriodCode);			
		$this->view->periodCode = $PeriodCode['periodCode']; //Chuyen thong tin ma giai doan vao VIEW
		$this->view->periodStep = $PeriodCode['periodStep']; //Chuyen thong tin bien xac dinh giai doan  thuc hien vao VIEW
		
		//Gan modul chuc nang cho view	
		$psModulName = $this->_request->getParam('hdn_function_modul',"");			
		$this->view->functionModul = $psModulName;	
		//Dinh nghia current modul code
		$this->view->currentModulCode = "SMS";
		//
		$pcurrentModulCodeForLeft = $this->_request->getParam('htn_leftmodul',"");
		if($pcurrentModulCodeForLeft != '')
				$this->view->currentModulCodeForLeft = $pcurrentModulCodeForLeft;
		else 	$this->view->currentModulCodeForLeft = 'WAIT';
		//
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

		//Hien thi file template
		$response->insert('header', $this->view->renderLayout('header.phtml','./application/views/scripts/'));    //Hien thi header 
		$response->insert('left', $this->view->renderLayout('left.phtml','./application/views/scripts/'));    //Hien thi header 		    
        $response->insert('footer', $this->view->renderLayout('footer.phtml','./application/views/scripts/'));  	 //Hien thi footer        

	}	
	/**
	 * Idea : Phuong thuc hien thi danh sach
	 *
	 */
	public function indexAction(){
		//var_dump($_SESSION['arr_all_staff_keep']);
		$objSession = new Sys_Init_Session();
		$objFunction =	new	Sys_Function_DocFunctions()	;
		$objSms = new Sms_modSms();
		$ojbSysInitConfig = new Sys_Init_Config();
		//Lay cac gia tri const
		$this->view->arrConst =	$ojbSysInitConfig->_setProjectPublicConst();
		//Xu ly Autocomplete
		$this->view->search_textselectbox = $objFunction->doc_search_ajax($_SESSION['arr_all_staff'],"id","name","sFullTextSearch","hdn_name",0,"position_code");
		//Nhan bien truyen vao tu form
		$sFullTextSearch = trim($this->_request->getParam('sFullTextSearch',''));
		$this->view->sFullTextSearch = $sFullTextSearch;
		//
		$sStaffIdList = $objFunction->convertStaffNameToStaffId(trim($sFullTextSearch));
		if(trim($sStaffIdList) == ''){
			$arrStaffId = $objSms->docSmsUserIdList();
			for ($i=0;$i<sizeof($arrStaffId);$i++){
				$sStaffIdList = $sStaffIdList.$arrStaffId[$i]['FK_STAFF'].',';
				$sDepartmentIdList = $sDepartmentIdList.Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$arrStaffId[$i]['FK_STAFF'],'unit_id').',';
				$sRoleLeaderList = $sRoleLeaderList.Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$arrStaffId[$i]['FK_STAFF'],'position_code').',';
			}
		}else{
			$arrStaffId =explode(',',trim($sStaffIdList));
			$sStaffIdList = $sStaffIdList.',';
			for ($i=0;$i<sizeof($arrStaffId);$i++){
				$sDepartmentIdList = $sDepartmentIdList.Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$arrStaffId[$i],'unit_id').',';
				$sRoleLeaderList = $sRoleLeaderList.Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$arrStaffId[$i],'position_code').',';
			}
		}
		//Lay ID don vi
		$iOwnerId = $_SESSION['OWNER_ID'];
		
		$this->view->bodyTitle = 'DANH SÃ�CH CHá»œ Gá»¬I TIN';
		
		// Lay toan bo tham so truyen tu form			
		$arrInput = $this->_request->getParams();		
		
		//Phan trang
		$piCurrentPage = $this->_request->getParam('hdn_current_page',0);		
		if ($piCurrentPage <= 1){
			$piCurrentPage = 1;
		}
		//echo $piCurrentPage;exit;
		$this->view->currentPage = $piCurrentPage; //Gan gia tri vao View		
		//Lay thong tin quy dinh so row / page
		$piNumRowOnPage = $this->_request->getParam('hdn_record_number_page');
		if($piNumRowOnPage == ''){
			$piNumRowOnPage = 15;
		} 	
		
		//THUC HIEN TRUY VAN LAY DU LIEU CAN NHAC VIEC
		$arrSms = $objSms->docSmsReminderGetAll($sStaffIdList,$sDepartmentIdList,$iOwnerId,$sRoleLeaderList,$piCurrentPage,$piNumRowOnPage);
		$this->view->arrSms = $arrSms;
		
		//Mang luu thong tin tong so ban ghi tim thay
		$psCurrentPage = $arrSms[0]['C_TOTAL'] ;				
		//Hien thi thong tin man hinh danh sach nay co bao nhieu ban ghi va hien thi Radio "Chon tat ca"; "Bo chon tat ca"
		if (count($arrSms) > 0){
			$this->view->sdocpertotal = "Danh sÃ¡ch cÃ³ ".sizeof($arrSms).'/'.$psCurrentPage." cÃ¡n bá»™";
			//Sinh xau HTML mo ta so trang (Trang 1; Trang 2;...)
			$this->view->generateStringNumberPage = Sys_Publib_Library::_generateStringNumberPage($psCurrentPage, $piCurrentPage, $piNumRowOnPage,$pUrl) ;		
			//quy dinh so record/page	
			$this->view->generateHtmlSelectBoxPage = Sys_Publib_Library::_generateChangeRecordNumberPage($piNumRowOnPage,"../index/?htn_leftModule=WAIT" );
		}
	}
	public function sendAction(){
		$objSession = new Sys_Init_Session();
		$objFunction =	new	Sys_Function_DocFunctions()	;
		$objSms = new Sms_modSms();
		$ojbSysInitConfig = new Sys_Init_Config();
		//Lay cac gia tri const
		$this->view->arrConst =	$ojbSysInitConfig->_setProjectPublicConst();
		//Nhan bien truyen vao tu form
		$sFullTextSearch = trim($this->_request->getParam('sFullTextSearch',''));
		$this->view->sFullTextSearch = $sFullTextSearch;
		//Phan trang
		$piCurrentPage = $this->_request->getParam('hdn_current_page',0);		
		if ($piCurrentPage <= 1){
			$piCurrentPage = 1;
		}
		//echo $piCurrentPage;exit;
		$this->view->currentPage = $piCurrentPage; //Gan gia tri vao View		
		//Lay thong tin quy dinh so row / page
		$piNumRowOnPage = $this->_request->getParam('hdn_record_number_page');
		if($piNumRowOnPage == ''){
			$piNumRowOnPage = 15;
		}	
		//Lay cac tin SMS da gui 
		$arrSmsSend = $objSms->docSmsSendGetAll($sFullTextSearch,$piCurrentPage,$piNumRowOnPage);
		$this->view->arrSmsSend = $arrSmsSend;
		//Mang luu thong tin tong so ban ghi tim thay
		$psCurrentPage = $arrSmsSend[0]['C_TOTAL'];				
		//Hien thi thong tin man hinh danh sach nay co bao nhieu ban ghi va hien thi Radio "Chon tat ca"; "Bo chon tat ca"
		if (count($arrSmsSend) > 0){
			$this->view->sdocpertotal = "Danh sÃ¡ch cÃ³ ".sizeof($arrSmsSend).'/'.$psCurrentPage." tin nháº¯n";
			//Sinh xau HTML mo ta so trang (Trang 1; Trang 2;...)
			$this->view->generateStringNumberPage = Sys_Publib_Library::_generateStringNumberPage($psCurrentPage, $piCurrentPage, $piNumRowOnPage,$pUrl) ;		
			//quy dinh so record/page	
			$this->view->generateHtmlSelectBoxPage = Sys_Publib_Library::_generateChangeRecordNumberPage($piNumRowOnPage,"../send/?htn_leftModule=SENT" );
		}
		$this->view->bodyTitle = 'DANH SÃ�CH TIN Ä�Ãƒ Gá»¬I';
		
		// Lay toan bo tham so truyen tu form			
		$arrInput = $this->_request->getParams();		
			
	}
	public function receivedAction(){
		$objSession = new Sys_Init_Session();
		$objFunction =	new	Sys_Function_DocFunctions()	;
		$objSms = new Sms_modSms();
		$ojbSysInitConfig = new Sys_Init_Config();
		//Lay cac gia tri const
		$this->view->arrConst =	$ojbSysInitConfig->_setProjectPublicConst();
		//Phan trang
		$piCurrentPage = $this->_request->getParam('hdn_current_page',0);		
		if ($piCurrentPage <= 1){
			$piCurrentPage = 1;
		}
		//echo $piCurrentPage;exit;
		$this->view->currentPage = $piCurrentPage; //Gan gia tri vao View		
		//Lay thong tin quy dinh so row / page
		$piNumRowOnPage = $this->_request->getParam('hdn_record_number_page');
		if($piNumRowOnPage == ''){
			$piNumRowOnPage = 15;
		}	
		//Lay cac tin SMS da gui 
		$arrSmsReceived = $objSms->docSmsReceivedGetAll($piCurrentPage,$piNumRowOnPage);
		$this->view->arrSmsReceived = $arrSmsReceived;
		//Mang luu thong tin tong so ban ghi tim thay
		$psCurrentPage = $arrSmsReceived[0]['C_TOTAL'];				
		//Hien thi thong tin man hinh danh sach nay co bao nhieu ban ghi va hien thi Radio "Chon tat ca"; "Bo chon tat ca"
		if (count($arrSmsReceived) > 0){
			$this->view->sdocpertotal = "Danh sÃ¡ch cÃ³ ".sizeof($arrSmsReceived).'/'.$psCurrentPage." tin nháº¯n";
			//Sinh xau HTML mo ta so trang (Trang 1; Trang 2;...)
			$this->view->generateStringNumberPage = Sys_Publib_Library::_generateStringNumberPage($psCurrentPage, $piCurrentPage, $piNumRowOnPage,$pUrl) ;		
			//quy dinh so record/page	
			$this->view->generateHtmlSelectBoxPage = Sys_Publib_Library::_generateChangeRecordNumberPage($piNumRowOnPage,"../send/?htn_leftModule=SENT" );
		}
		$this->view->bodyTitle = 'DANH SÃ�CH TIN PHáº¢N Há»’I';
		
		// Lay toan bo tham so truyen tu form			
		$arrInput = $this->_request->getParams();	;
			
	}
	
	public function updateAction(){	
		$objFunction =	new	Sys_Function_DocFunctions()	;
		$objSms = new Sms_modSms();	
		//Danh sach ID can bo can gui tin
		$sList = $this->_request->getParam('hdn_object_id_list',"");	
		$arrList = explode('!~~!',$sList);
		
		
		for ($i=0;$i<sizeof($arrList);$i++){
			$arrElement = explode('!#~$|*',$arrList[$i]);
			$sListId = $sListId.$arrElement[0].',';
			$sMsgList = $sMsgList.$arrElement[1].'!#~$|';
		}
		//var_dump($arrElement); exit;
				
		//var_dump($arrElement);		
		//echo "MSM1:<br/>".$sMsgList; 
		$sMsgList = substr($sMsgList,0,strlen($sMsgList)-5);
		//echo "<br/>MSM2:<br/>".$sMsgList; 
		
		$sListId = substr($sListId,0,strlen($sListId)-1);
		//Danh sach so dien thoai tuong duong voi danh sach can bo
		$sTelMobileList = $objFunction->convertIdListToTelMobileList($sListId);
		//Danh sach ten-chuc vu can bo (!#~$|*)
		$sPositionNameList = $objFunction->getNamePositionStaffByIdList($sListId);
		//Danh sach ID phong ban 
		$sUnitIDList = $objFunction->doc_get_all_unit_permission_form_staffIdList($sListId);
		//Danh sach ten phong ban (!#~$|*)
		$sUnitNameList = $objFunction->getNameUnitByIdUnitList($sUnitIDList);
		//Trang thai
		$sStatus ='Send';
		//Msg
		//Goi phuong thuc gui tin SMS
		$objSms->docSmsSendUpdate($sTelMobileList,$sMsgList,$sStatus,$sPositionNameList,$sUnitNameList);
		$this->_redirect('sms/sms/index/');	
	}
	public function deleteAction(){	
		$objSms = new Sms_modSms();	
		//Lay Id doi tuong can xoa
		$sListId = $this->_request->getParam('hdn_object_id_list',"");	
		$sStatus = $this->_request->getParam('htn_leftmodul',"");	
		//Goi phuong thuc xoa doi tuong
		$objSms->docSmsDelete($sListId,$sStatus);
		If($sStatus == 'SENT'){
			$this->_redirect('sms/sms/send/?htn_leftmodul=SENT');	
		}else{
			$this->_redirect('sms/sms/received/?htn_leftmodul=RECEIVED');
		}
	}	
}
?>
<?php
/**
 * Nguoi tao: QUANGDD
 * Ngay tao: 09/11/2010
 * Y nghia: Class thu ly HS
 */	
class user_orgController extends  Zend_Controller_Action {
	/**
	 * (non-PHPdoc)
	 * @see Zend_Controller_Action::init()
	 */
	public $objConfig;
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
		//Ky tu dac biet phan tach giua cac phan tu
		$this->view->delimitor 	= "!~~!";	
		//Load cau hinh thu muc trong file config.ini de lay ca hang so dung chung
        $tempConstPublic = Zend_Registry::get('ConstPublic');
		$this->_ConstPublic = $tempConstPublic->toArray();	
		
		//Lay duong dan thu muc goc (path directory root)
		$this->view->baseUrl = $this->_request->getBaseUrl() . "/public/";	
			
		//Goi lop Listxml_modList
		Zend_Loader::loadClass('User_modOrg');
		
		//Lay cac hang so su dung trong JS public
		$objConfig = new Sys_Init_Config();
		$this->objConfig = $objConfig;
		$this->view->JSPublicConst = $objConfig->_setJavaScriptPublicVariable();		
		
		//Tao doi tuong XML
		Zend_Loader::loadClass('Sys_Publib_Xml');		
		
		// Load tat ca cac file Js va Css
		if($this->_request->getParam('showModelDialog','')!=1){
			//$this->view->LoadAllFileJsCss = Sys_Publib_Library::_getAllFileJavaScriptCss('','js','js_org.js,jquery-1.4.3.min.js,jquery.simplemodal.js,cal.js',',','js').Sys_Publib_Library::_getAllFileJavaScriptCss('','style','simpleModal.css,calendar.css',',','css');						
			$sStyle = Sys_Publib_Library::_getAllFileJavaScriptCss('','js','js_org.js,jquery-1.4.3.min.js,jquery-1.5.1.js,jquery.simplemodal.js,',',','js');
			$sStyle.= Sys_Publib_Library::_getAllFileJavaScriptCss('','js','ui/jquery.ui.core.js,ui/jquery.ui.widget.js,ui/jquery.ui.datepicker.js,ui/i18n/jquery.ui.datepicker-vi.js',',','js');
			//$sStyle.= Sys_Publib_Library::_getAllFileJavaScriptCss('','style','simpleModal.css,themes/base/jquery.ui.base.css,themes/base/jquery.ui.theme.css,themes/base/calendar.css',',','css');
			//$sStyle.= Sys_Publib_Library::_getAllFileJavaScriptCss('','style','simpleModal.css,themes/ui-lightness/jquery-ui-1.8.13.custom.css,themes/base/calendar.css',',','css');
			$sStyle.= Sys_Publib_Library::_getAllFileJavaScriptCss('','style','simpleModal.css,themes/humanity/jquery-ui-1.8.13.custom.css,themes/base/calendar.css',',','css');			
			$this->view->LoadAllFileJsCss = $sStyle;	 									
		}	
		else {
			//$this->view->LoadAllFileJsCss = Sys_Publib_Library::_getAllFileJavaScriptCss('','js','jquery-1.4.3.min.js,jquery.simplemodal.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','style','simpleModal.css,calendar.css',',','css');
			$this->view->LoadAllFileJsCss = Sys_Publib_Library::_getAllFileJavaScriptCss('','js','jquery-1.4.3.min.js,jquery.simplemodal.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','style','simpleModal.css,calendar.css',',','css');
		}			
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
		$this->view->showModelDialog = $this->_request->getParam('showModelDialog','');
		//Dinh nghia current modul code
		$this->view->currentModulCode = "ORG";
		if($this->_request->getParam('showModelDialog','')!=1){
			//Hien thi file template
			$response->insert('header', $this->view->renderLayout('header.phtml','./application/views/scripts/'));    	//Hien thi header 
			$response->insert('left', $this->view->renderLayout('left.phtml','./application/views/scripts/'));    		//Hien thi header 		    
	        $response->insert('footer', $this->view->renderLayout('footer.phtml','./application/views/scripts/'));
		}  
	}	
	/**
	 * Action: indexAction
	 * Nguoi tao: TuyenNH
	 * Ngay tao: 11/06/2011
	 * Idea : Phuong thuc hien thi danh sach
	 */
	public function indexAction(){		
		//Xac dinh left module
		$this->view->currentModulCodeForLeft = "UNIT";
		//Hien thi left menu
		$this->_response->insert('left', $this->view->renderLayout('left.phtml','./application/views/scripts/')); 	
		//Goi cac doi tuong
		$objInitConfig 			 	= new Sys_Init_Config();
		$objFunction	     		= new Sys_Function_RecordFunctions();	
		$objOrg						= new User_modOrg();
		// Tao doi tuong Zend_Filter
		$filter = new Zend_Filter();
		//Tieu de man hinh danh sach
		$this->view->bodyTitle = 'CƠ CẤU PHÒNG BAN';
		//echo "test<br>";
		//Lay cac hang so dung chung
		$arrConst = $this->objConfig->_setProjectPublicConst();
		//var_dump($arrConst);
		$this->view->arrConst = $arrConst;//C to V
		$arrInput = $this->_request->getParams();// V to C
		$sFullTextSearch=$this->_request->getParam('txtFullTextSearch','');
		$sOwnercode=$this->_request->getParam('txtUnit','');
		//Lay du lieu trong CSDL	
		$sUnitId = $this->_request->getParam('hdn_item_id','');		
		if($sUnitId==""){
			$sUnitId = $_SESSION['UNIT_ID'];	
		}
		$sUnitParentId = $this->_request->getParam('hdn_parent_item_id','');	
		//lay thong tin fong a	
		$this->view->sUnitId=$sUnitId;
		$this->view->sUnitParentId=$sUnitParentId;
		$this->view->txtFullTextSearch = $sFullTextSearch;//C -> V
		//$this->view->sPkUnitId = $sPkUnitId;
		$sStatus			= $filter->filter($arrInput['C_STATUS'],'');		
		$this->view->sStatus = $sStatus;
		$this->view->sOwnercode= $sOwnercode;
		//lay don vi trien khai
		$sOwner_code='';
		if($_SESSION['STAFF_PERMISSTION']==Sys_Init_Config::_setPermisstionSystem(2)){
			$sOwner_code=$_SESSION['OWNER_CODE'];
		}
		$arrResult = $objOrg->USERStaffGetAll($sStatus,$sUnitId,'',$sOwner_code);
		$this->view->arrResult = $arrResult;
		//lay danh sach don vi trien khai
		$arrOwnerCode = $objFunction->getAllObjectbyListCode($sOwner_code,'DM_DON_VI_TRIEN_KHAI');
		$this->view->arrOwnerCode = $arrOwnerCode;
		//var_dump($arrOwnerCode);
		//luu vao mang java 
		$arrAllUnit=$objOrg->USERUnitGetAll('HOAT_DONG','','');
		echo '<script type="text/javascript">var arrProfession=new Array();var arrValue=new Array();';
		$i = 0;
		foreach ($arrAllUnit as $value){
			echo 'arrValue=new Array();arrValue[0]="'.$value['PK_OBJECT'].'";arrValue[1]="'.$value['C_NAME'].'";arrValue[2]="'.$value['C_OWNER_CODE'].'";arrValue[3]="'.$value['C_INTERNAL_ORDER'].'";arrProfession['.$i.']=arrValue;';
			$i++;
		}
		echo '</script>';
		//$this->view->genlist = $objxml->_xmlGenerateList($sxmlFileName,'col',$arrRecord, "C_RECEIVED_RECORD_XML_DATA","PK_RECORD",$sfullTextSearch,false,false,'../viewrecord/');
		//var_dump($arrOwnerCode);
		
	}
	
	/**
	 * 
	 * Enter description here ...
	 */
	public function addAction(){ 
		//Xac dinh left module
		$this->view->currentModulCodeForLeft = "UNIT";
		$this->_response->insert('left', $this->view->renderLayout('left.phtml','./application/views/scripts/'));
		session_start();
		$sUnitId = $this->_request->getParam('hdn_item_id','');			
		//gan vao session
		if($sUnitId==''){
			$sUnitId=$_SESSION['UNIT_ID'];
		}
		$_SESSION['UNIT_ID']=$sUnitId;
		$objOrg			= new User_modOrg();
		$objConfig		= new Sys_Init_Config();
		$ojbSysLib		= new Sys_library();
		$objFunction	= new Sys_Function_RecordFunctions();
		$this->view->UrlImg=$objConfig->_setImageUrlPath();
		$arrConst = $this->objConfig->_setProjectPublicConst();
		//var_dump($arrConst);
		$this->view->arrConst = $arrConst;
		//Get Order
		$iOrder = $ojbSysLib->_getNextValue("T_USER_UNIT","C_ORDER","1 = 1");
		$this->view->iOrder = $iOrder;
		//Lay don vi trien khai
		$sOwner_code='';
		if($_SESSION['STAFF_PERMISSTION']==Sys_Init_Config::_setPermisstionSystem(2)){
			$sOwner_code=$_SESSION['OWNER_CODE'];
		}
		$arrOwnercode = $objFunction->getAllObjectbyListCode($sOwner_code,'DM_DON_VI_TRIEN_KHAI');
		$this->view->arrOwnercode = $arrOwnercode;
		$arrResult = $objOrg->USERUnitGetSingle($sUnitId);
		$sOwnerCode=$arrResult['C_OWNER_CODE'];
		$sUnitName=$arrResult['C_NAME'];		
		//gan vao session
		$this->view->sOwnerCode=$sOwnerCode;
		$this->view->sUnitName=$sUnitName;
		$this->view->sUnitId=$sUnitId;		
		$sStatus='';
		if($this->_request->getParam('chk_status')){
			$sStatus='HOAT_DONG';	
		}
		else {
			$sStatus='KHONG_HOAT_DONG';
		}
		//Hien thi left menu
		if($this->_request->getParam('hdn_is_update','') == '1'||$this->_request->getParam('hdn_is_update','') == '2'){
			$arrParameter = array(	
				'PK_UNIT'	=> '',
				'FK_UNIT' 	=> $this->_request->getParam('hdn_item_id',''),
				'C_CODE'	=> $this->_request->getParam('txt_code',''),	
				'C_NAME' 	=> trim($this->_request->getParam('txt_name','')),			
				'C_ADDRESS' => $this->_request->getParam('txt_address',''),
				'C_TEL' 	=> $this->_request->getParam('txt_tel',''),
				'C_LOCAL' 	=> $this->_request->getParam('txt_local',''),
				'C_FAX' 	=> $this->_request->getParam('txt_fax',''),
				'C_EMAIL' 	=> $this->_request->getParam('txt_email',''),
				'C_ORDER' 	=> $this->_request->getParam('txt_order',''),
				'C_STATUS' 	=> $sStatus,																
				'C_OWNER_CODE'=> $this->_request->getParam('C_OWNER_CODE','')
			);	
			$arrResult = $objOrg->USERUnitUpdate($arrParameter);
			if($this->_request->getParam('hdn_is_update','') == '1'){
				$_SESSION['UNIT_ID']=$this->_request->getParam('txt_parent_code','');
				$this->_redirect('user/org/add/');
			}
			else {
				$this->_redirect('user/org/index/');
			}
				
		}				
	}
	public function editAction(){ 
		//Xac dinh left module
		$this->view->currentModulCodeForLeft = "UNIT";
		$this->_response->insert('left', $this->view->renderLayout('left.phtml','./application/views/scripts/'));
		session_start();
		$sUnitId = $this->_request->getParam('sUnitId','');	
		$_SESSION['UNIT_ID']=$sUnitId;	
		$objOrg			= new User_modOrg();
		$objFunction	= new Sys_Function_RecordFunctions();
		$objConfig= new Sys_Init_Config();
		$this->view->UrlImg=$objConfig->_setImageUrlPath();
		$arrConst = $this->objConfig->_setProjectPublicConst();
		//var_dump($arrConst);
		$this->view->arrConst = $arrConst;
		//Lay don vi trien khai
		$sOwner_code='';
		if($_SESSION['STAFF_PERMISSTION']==Sys_Init_Config::_setPermisstionSystem(2)){
			$sOwner_code=$_SESSION['OWNER_CODE'];
		}
		$arrOwnercode = $objFunction->getAllObjectbyListCode($sOwner_code,'DM_DON_VI_TRIEN_KHAI');
		$this->view->arrOwnercode = $arrOwnercode;
		//Lay danh sach phong ban
		$arrUnit = $objOrg->USERUnitGetAll('HOAT_DONG','',$_SESSION['OWNER_CODE']);
		//day du lieu vao mang java
		echo '<script type="text/javascript">var arrProfession=new Array();var arrValue=new Array();';
		$i = 0;
		foreach ($arrUnit as $value){
			echo 'arrValue=new Array();arrValue[0]="'.$value['PK_OBJECT'].'";arrValue[1]="'.$value['C_NAME'].'";arrValue[2]="'.$value['C_OWNER_CODE'].'";arrProfession['.$i.']=arrValue;';
			$i++;
		}
		echo '</script>';
		$arrResult = $objOrg->USERUnitGetSingle($sUnitId);
		$this->view->arrResult=$arrResult;
		//Lay danh sach phong ban
		$arrUnitParent = $objOrg->USERUnitGetAll('HOAT_DONG','',$arrResult['C_OWNER_CODE']);
		$this->view->arrUnitParent = $arrUnitParent;
		$arrUnitSingle=	$objOrg->USERUnitGetSingle($arrResult['FK_UNIT']);
		$this->view->sUnitName=$arrUnitSingle['C_NAME'];
		$sStatus='';
		if($this->_request->getParam('chk_status')){
			$sStatus='HOAT_DONG';	
		}
		else {
			$sStatus='KHONG_HOAT_DONG';
		}
		//Hien thi left menu
		if($this->_request->getParam('hdn_is_update','') == '1'||$this->_request->getParam('hdn_is_update','') == '2'){
			$arrParameter = array(	
				'PK_UNIT'	=> $sUnitId,
				'FK_UNIT' 	=> $this->_request->getParam('hdn_item_id',''),
				'C_CODE'	=> Sys_Publib_Library::_convertVNtoEN($this->_request->getParam('txt_code','')),	
				'C_NAME' 	=> trim($this->_request->getParam('txt_name','')),			
				'C_ADDRESS' => $this->_request->getParam('txt_address',''),
				'C_TEL' 	=> $this->_request->getParam('txt_tel',''),
				'C_LOCAL' 	=> $this->_request->getParam('txt_local',''),
				'C_FAX' 	=> $this->_request->getParam('txt_fax',''),
				'C_EMAIL' 	=> $this->_request->getParam('txt_email',''),
				'C_ORDER' 	=> $this->_request->getParam('txt_order',''),
				'C_STATUS' 	=> $sStatus,																
				'C_OWNER_CODE'=>$this->_request->getParam('C_OWNER_CODE','')
			);	
			$arrResult = $objOrg->USERUnitUpdate($arrParameter);
			if($this->_request->getParam('hdn_is_update','') == '1'){
				$_SESSION['UNIT_ID']=$this->_request->getParam('txt_parent_code','');
				$this->_redirect('user/org/add/');
			}
			else {
				$this->_redirect('user/org/index/');
			}	
		}				
	}
	public function deleteunitAction(){ 
		session_start();
		$sUnitId = $this->_request->getParam('sUnit','');	
		$sParentId = $this->_request->getParam('parentId','');
		$_SESSION['UNIT_ID']=$sParentId;
		$this->view->sUnitId=$sUnitId;
		$objOrg	= new User_modOrg();
		$sRetError = $objOrg->USERUnitDelete($sUnitId);	
		$error='';
		if($sRetError != null || $sRetError != '' ){
			//echo "<script type='text/javascript'>alert('$sRetError')</script>";
			//$error=$sRetError;
			echo "<script type='text/javascript'>alert('$sRetError'); actionUrl('../index/')</script>";
		}
		else{
			$this->_redirect('user/org/index/');
		}			
	}
	public function addstaffAction(){
		//Xac dinh left module
		$this->view->currentModulCodeForLeft = "UNIT";
		$this->_response->insert('left', $this->view->renderLayout('left.phtml','./application/views/scripts/'));
		session_start();
		$sUnitId = $this->_request->getParam('hdn_item_id','');	
		if($sUnitId==''){
			$sUnitId=$_SESSION['UNIT_ID'];
		}
		$this->view->sUnitId=$sUnitId;
		$_SESSION['UNIT_ID']=$sUnitId;
		$objOrg			= new User_modOrg();
		$objFunction	= new Sys_Function_RecordFunctions();
		$objConfig		= new Sys_Init_Config();
		$ojbSysLib		= new Sys_library();
		$this->view->UrlImg=$objConfig->_setImageUrlPath();
		$arrConst = $this->objConfig->_setProjectPublicConst();
		$this->view->arrConst = $arrConst;
		//lay ten phong ban
		$arrUnitName=$objOrg->USERUnitGetSingle($sUnitId);
		$this->view->sUnitName=$arrUnitName['C_NAME'];
		//Lay duong dan thu muc goc (path directory root)
		$objconfig = new Sys_Init_Config();
		$SysLibUrlPath = $objconfig->_setLibUrlPath();
		$url_path_calendar = $SysLibUrlPath . 'Sys-calendar/';
		$this->view->urlCalendar = $url_path_calendar;
		//danh sach tham so he thong
		$sOwner_code='';
		if($_SESSION['STAFF_PERMISSTION']==Sys_Init_Config::_setPermisstionSystem(2)){
			$sOwner_code=$_SESSION['OWNER_CODE'];
		}
		$arrSystemPara = $objFunction->getAllObjectbyListCode('','DM_THAM_SO_HE_THONG');
		$this->view->arrSystemPara=$arrSystemPara;
		//danh sach gioi tinh
		$arrSex=$arrOwnercode = $objFunction->getAllObjectbyListCode('','DM_GIOITINH');
		$this->view->arrSex=$arrSex;
		//Lay danh sach phong ban
		$arrUnit = $objOrg->USERUnitGetAll('HOAT_DONG','',$_SESSION['OWNER_CODE']);
		//Lay Autocomplete phong ban
		$this->view->arr_autocomplete_unit = $objFunction->doc_search_ajax($arrUnit,"PK_OBJECT","C_NAME","txt_unit_name","hdn_department_unit",1,"",0);
		//thong tin nhom chuc vu
		$arrGroupPosition=$objOrg->UserPositionGroupGetAll('','HOAT_DONG');
		$this->view->arrGroupPosition=$arrGroupPosition;
		//Get Order
		$iOrder = $ojbSysLib->_getNextValue("T_USER_STAFF","C_ORDER","1 = 1");
		$this->view->iOrder = $iOrder;
		//bien dung de quay lai trang goi no
		$hdn_urlback=$this->_request->getParam('hdn_urlback','');
		$this->view->hdn_urlback=$hdn_urlback;
		//thong tin chuc vu
		$arrPosition=$objOrg->UserPositionGetAll('','','HOAT_DONG');
		$this->view->arrPosition=$arrPosition;
		//Lay Autocomplete chuc danh
		//$this->view->arr_autocomplete_title = $objFunction->doc_search_ajax($arrPosition,"PK_POSITION","C_NAME","txt_title","hdn_department_list",1,"",0);
		$sRoll='';
		if($this->_request->getParam('chk_role')=='1'){
			$sRoll='ADMIN_SYSTEM';	
		}
		if($this->_request->getParam('chk_role')=='2'){
			$sRoll='ADMIN_OWNER';	
		}
		$sStatus='';
		if($this->_request->getParam('chk_status')){
			$sStatus='HOAT_DONG';	
		}
		else {
			$sStatus='KHONG_HOAT_DONG';
		}
		
		//echo $sRoll; exit;
		
		//Hien thi left menu
		if($this->_request->getParam('hdn_is_update','') == '1'||$this->_request->getParam('hdn_is_update','') == '2'){
			$arrParameter = array(	
				'T_USER_STAFF'	=> '',
				'FK_UNIT' 		=>$this->_request->getParam('hdn_item_id',''),
				'FK_POSITION'	=> $this->_request->getParam('txt_title',''),			
				'C_NAME' 		=> trim($this->_request->getParam('txt_name','')),
				'C_ADDRESS' 	=> $this->_request->getParam('txt_address',''),
				'C_EMAIL' 		=> $this->_request->getParam('txt_email',''),
				'C_TEL_LOCAL' 	=> $this->_request->getParam('txt_tel_local',''),
				'C_TEL'			=> $this->_request->getParam('txt_tel_office',''),	
				'C_TEL_MOBILE' 	=> $this->_request->getParam('txt_tel_mobile',''),			
				'C_TEL_HOME' 	=> $this->_request->getParam('txt_tel_home',''),
				'C_FAX' 		=> $this->_request->getParam('txt_fax',''),
				'C_USERNAME' 	=> trim($this->_request->getParam('txt_username','')),
				'C_PASSWORD' 	=> md5($this->_request->getParam('txt_password','')),
				'C_ORDER' 		=> $this->_request->getParam('txt_order',''),
				'C_STATUS' 		=> $sStatus,
				'C_ROLE' 		=> $sRoll,													
				'C_SEX'			=> $this->_request->getParam('txt_sex',''),
				'C_BIRTHDAY' 	=> Sys_Library::_ddmmyyyyToYYyymmdd($this->_request->getParam('txt_birthday','')),													
				'C_DN'			=>''
			);	
			$arrResult = $objOrg->UserStaffUpdate($arrParameter);
			//neu ghi va them moi
			if($this->_request->getParam('hdn_is_update','') == '1'){
				$_SESSION['UNIT_ID']=$this->_request->getParam('txt_parent_code','');
				$this->_redirect('user/org/addstaff/');
			}
			else{
				if($this->_request->getParam('hdn_urlback','')!=''){
					$this->_redirect($this->_request->getParam('hdn_urlback',''));
				}
				else {
					$this->_redirect('user/org/index/');
				}
			}	
		}				
	}	
	public function editstaffAction(){ 
		//Xac dinh left module
		$this->view->currentModulCodeForLeft = "UNIT";
		$this->_response->insert('left', $this->view->renderLayout('left.phtml','./application/views/scripts/'));
		session_start();
		$sStaffId = $this->_request->getParam('sUnitId','');	
		$sUnitId = $this->_request->getParam('hdn_item_id','');	
		$_SESSION['UNIT_ID']=$sUnitId;	
		$this->view->sUnitId = $sUnitId;
		$objOrg			= new User_modOrg();
		$objFunction	= new Sys_Function_RecordFunctions();
		$arrConst = $this->objConfig->_setProjectPublicConst();
		$objConfig= new Sys_Init_Config();
		$this->view->UrlImg=$objConfig->_setImageUrlPath();
		$this->view->Urlresetpass=$objConfig->_setWebSitePath().'user/org/resetpassword/';
		//var_dump($arrConst);
		$this->view->arrConst = $arrConst;
		$objconfig = new Sys_Init_Config();
		$SysLibUrlPath = $objconfig->_setLibUrlPath();
		$url_path_calendar = $SysLibUrlPath . 'Sys-calendar/';
		$this->view->urlCalendar = $url_path_calendar;
		$sOwner_code='';
		if($_SESSION['STAFF_PERMISSTION']==Sys_Init_Config::_setPermisstionSystem(2)){
			$sOwner_code=$_SESSION['OWNER_CODE'];
		}
		//Lay don vi trien khai
		$arrOwnercode = $objFunction->getAllObjectbyListCode($sOwner_code,'DM_DON_VI_TRIEN_KHAI');
		$this->view->arrOwnercode = $arrOwnercode;
		//danh sach gioi tinh
		$arrSex=$arrOwnercode = $objFunction->getAllObjectbyListCode('','DM_GIOITINH');
		$this->view->arrSex=$arrSex;
		//Lay danh sach phong ban
		$arrUnit = $objOrg->USERUnitGetAll('HOAT_DONG','',$sOwner_code);
		//Lay Autocomplete phong ban
		$this->view->arr_autocomplete_unit = $objFunction->doc_search_ajax($arrUnit,"PK_OBJECT","C_NAME","txt_unit_name","hdn_department_unit",1,"",0);
		//thong tin nhom chuc vu
		$arrGroupPosition=$objOrg->UserPositionGroupGetAll('','HOAT_DONG');
		$this->view->arrGroupPosition=$arrGroupPosition;
		//thong tin chuc vu
		$arrPosition=$objOrg->UserPositionGetAll('','','HOAT_DONG');
		$this->view->arrPosition=$arrPosition;
		//bien dung de quay lai trang goi no
		$hdn_urlback=$this->_request->getParam('hdn_urlback','');
		$this->view->hdn_urlback=$hdn_urlback;
		//Lay danh sach phong ban
		//$arrUnit = $objOrg->USERUnitGetAll('HOAT_DONG','',$_SESSION['OWNER_CODE']);
		//day du lieu vao mang java
		echo '<script type="text/javascript">var arrProfession=new Array();var arrValue=new Array();';
		$i = 0;
		foreach ($arrUnit as $value){
			echo 'arrValue=new Array();arrValue[0]="'.$value['PK_OBJECT'].'";arrValue[1]="'.$value['C_NAME'].'";arrValue[2]="'.$value['C_OWNER_CODE'].'";arrProfession['.$i.']=arrValue;';
			$i++;
		}
		echo '</script>';
		$arrResult = $objOrg->USERStaffGetSingle($sStaffId);
		$this->view->arrResult=$arrResult;
		//lay ten fong ban
		$arrUnitSingle=	$objOrg->USERUnitGetSingle($arrResult['FK_UNIT']);
		//Lay danh sach phong ban
		$arrUnitParent = $objOrg->USERUnitGetAll('HOAT_DONG','',$arrResult['C_OWNER_CODE']);
		$this->view->arrUnitParent = $arrUnitParent;
		$this->view->sUnitName=$arrUnitSingle['C_NAME'];
		$sRoll='';
		if($this->_request->getParam('chk_role')=='2'){
			$sRoll='ADMIN_OWNER';	
		}
		if($this->_request->getParam('chk_role')=='1'){
			$sRoll='ADMIN_SYSTEM';	
		}
		$sStatus='';
		if($this->_request->getParam('chk_status')){
			$sStatus='HOAT_DONG';	
		}
		else {
			$sStatus='KHONG_HOAT_DONG';
		}		
		//echo $sRoll; exit;
		$sPassWord='';
		if($this->_request->getParam('txt_password','')!=""){
			$sPassWord=md5($this->_request->getParam('txt_password',''));
		}
		//Hien thi left menu
		if($this->_request->getParam('hdn_is_update','') == '1'|| $this->_request->getParam('hdn_is_update','') == '2'){
			$arrParameter = array(	
				'T_USER_STAFF'	=> $arrResult['PK_STAFF'],
				'FK_UNIT' 		=>$this->_request->getParam('hdn_item_id',''),
				'FK_POSITION'	=> $this->_request->getParam('txt_title',''),		
				'C_NAME' 		=> trim($this->_request->getParam('txt_name','')),
				'C_ADDRESS' 	=> $this->_request->getParam('txt_address',''),
				'C_EMAIL' 		=> $this->_request->getParam('txt_email',''),
				'C_TEL_LOCAL' 	=> $this->_request->getParam('txt_tel_local',''),
				'C_TEL'			=> $this->_request->getParam('txt_tel_office',''),	
				'C_TEL_MOBILE' 	=> $this->_request->getParam('txt_tel_mobile',''),			
				'C_TEL_HOME' 	=> $this->_request->getParam('txt_tel_home',''),
				'C_FAX' 		=> $this->_request->getParam('txt_fax',''),
				'C_USERNAME' 	=> trim($this->_request->getParam('txt_username','')),
				'C_PASSWORD' 	=> $sPassWord,
				'C_ORDER' 		=> $this->_request->getParam('txt_order',''),
				'C_STATUS' 		=> $sStatus,
				'C_ROLE' 		=> $sRoll,													
				'C_SEX'			=> $this->_request->getParam('txt_sex',''),
				'C_BIRTHDAY' 	=> Sys_Library::_ddmmyyyyToYYyymmdd($this->_request->getParam('txt_birthday','')),													
				'C_DN'			=>''
			);	
			$arrResult = $objOrg->UserStaffUpdate($arrParameter);
			if($this->_request->getParam('hdn_is_update','') == '1'){
				$_SESSION['UNIT_ID']=$this->_request->getParam('txt_parent_code','');
				$this->_redirect('user/org/addstaff/');
			}else {
				if($this->_request->getParam('hdn_urlback','')!=''){
					$this->_redirect($this->_request->getParam('hdn_urlback',''));
				}
				else {
					$this->_redirect('user/org/index/');
				}	
			}
		}				
	}
	public function deletestaffAction(){ 
		session_start();
		$sUnitId = $this->_request->getParam('sUnit','');	
		$sParentId = $this->_request->getParam('parentId','');
		$_SESSION['UNIT_ID']=$sParentId;
		$this->view->sUnitId=$sUnitId;
		$objOrg	= new User_modOrg();
		$sRetError = $objOrg->USERStaffDelete($sUnitId);	
		$error='';
		$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
		if($sRetError != null || $sRetError != '' ){
			//echo "<script type='text/javascript'>alert('$sRetError')</script>";
			//$error=$sRetError;
			echo "<script type='text/javascript'>alert('$sRetError'); actionUrl($url)</script>";
		}
		else{			
	 		$this->_redirect($url);
		}			
	}	
	public function searchAction(){		
		//Xac dinh left module
		$this->view->currentModulCodeForLeft = "UNIT";
		$this->_response->insert('left', $this->view->renderLayout('left.phtml','./application/views/scripts/'));	
		$objInitConfig 			 	= new Sys_Init_Config();
		$objFunction	     		= new Sys_Function_RecordFunctions();	
		$objOrg						= new User_modOrg();
		// Tao doi tuong Zend_Filter
		$filter = new Zend_Filter();
		$arrConst = $this->objConfig->_setProjectPublicConst();
		//var_dump($arrConst);
		$this->view->arrConst = $arrConst;
		$arrInput = $this->_request->getParams();// V to C
		$sFullTextSearch=$this->_request->getParam('hdn_fulltextsearch','');
		$sUnitId=$this->_request->getParam('hdn_unitid','');			
		$sOwnercode=$this->_request->getParam('hdn_owner','');
		//echo $sOwnercode; 
		session_start();
		if($sFullTextSearch!=''){
			$_SESSION['hdn_fulltextsearch']=$sFullTextSearch;
		}
		$_SESSION['hdn_unitid']=$sUnitId;
		$_SESSION['hdn_owner']=$sOwnercode;
		if($this->_request->getParam('hdn_option','')!='1'){
			$sFullTextSearch=$_SESSION['hdn_fulltextsearch'];
			$sOwnercode=$_SESSION['hdn_owner'];
			$sUnitId=$_SESSION['hdn_unitid'];
		}
		//lay don vi trien khai
		if($sOwnercode=="" &$_SESSION['STAFF_PERMISSTION']==Sys_Init_Config::_setPermisstionSystem(2)){
			$sOwnercode=$_SESSION['OWNER_CODE'];
		}		
		$arrOwnerCode = $objFunction->getAllObjectbyListCode('','DM_DON_VI_TRIEN_KHAI');		
		$this->view->arrOwnerCode = $arrOwnerCode;
			
		//Lay du lieu trong CSDL	
		$this->view->txtFullTextSearch = $sFullTextSearch;
		$this->view->sOwnercode = $sOwnercode;
		//$this->view->sPkUnitId = $sPkUnitId;
		$arrResult = $objOrg->USERStaffGetAllBySearch('',$sFullTextSearch,$sOwnercode,$sUnitId);
		$this->view->arrResult = $arrResult;
		$arrResultAll = $objOrg->USERStaffGetAllBySearch('','','','');	
		//luu vao mang java 
		$arrAllUnit=$objOrg->USERUnitGetAll('HOAT_DONG','','');
		echo '<script type="text/javascript">var arrProfession=new Array();var arrValue=new Array();';
		$i = 0;
		foreach ($arrAllUnit as $value){
			echo 'arrValue=new Array();arrValue[0]="'.$value['PK_OBJECT'].'";arrValue[1]="'.$value['C_NAME'].'";arrValue[2]="'.$value['C_OWNER_CODE'].'";arrValue[3]="'.$value['C_INTERNAL_ORDER'].'";arrProfession['.$i.']=arrValue;';
			$i++;
		}
		echo '</script>';
		//$this->view->genlist = $objxml->_xmlGenerateList($sxmlFileName,'col',$arrRecord, "C_RECEIVED_RECORD_XML_DATA","PK_RECORD",$sfullTextSearch,false,false,'../viewrecord/');
		//var_dump($arrOwnerCode);
		
	}	
	public function dialogAction(){				
		//Goi cac doi tuong
		$objInitConfig 			 	= new Sys_Init_Config();
		$objFunction	     		= new Sys_Function_RecordFunctions();	
		$objOrg						= new User_modOrg();
		// Tao doi tuong Zend_Filter
		$filter = new Zend_Filter();
		//Lay cac hang so dung chung
		$arrConst = $this->objConfig->_setProjectPublicConst();
		$this->view->arrConst = $arrConst;//C to V
		$arrInput = $this->_request->getParams();// V to C
		$sOwnercode=$this->_request->getParam('sParentCode','');
		//neu la don vi trien khai
		if($_SESSION['STAFF_PERMISSTION']==Sys_Init_Config::_setPermisstionSystem(2)){
			$sOwnercode=$_SESSION['OWNER_CODE'];
		}
		$sDialog = $this->_request->getParam('showModelDialog','');	
		$this->view->showModelDialog = $sDialog;
		//Lay trang thai
		$arrOwnerCode = $objFunction->getAllObjectbyListCode($sOwnercode,'DM_DON_VI_TRIEN_KHAI');
		$this->view->arrOwnerCode = $arrOwnerCode;
		//Lay du lieu trong CSDL	
		$sUnitId = $this->_request->getParam('sUnitId','');	
		if($sUnitId==""){
			$sUnitId = $this->_request->getParam('hdn_item_id','');
		}	
		$this->view->sUnitId=$sUnitId;//echo '$sUnitId:'.$sUnitId;
		//$this->view->sPkUnitId = $sPkUnitId;
		$sStatus			= $filter->filter($arrInput['C_STATUS'],'');		
		$this->view->sStatus = $sStatus;
		$this->view->sOwnercode= $sOwnercode;
		$arrResult = $objOrg->USERUnitGetAll($sStatus,'',$sOwnercode);
		$this->view->arrResult = $arrResult;
	}	
	public function resetpasswordAction(){			
		//Goi cac doi tuong
		$objInitConfig 			 	= new Sys_Init_Config();
		$objFunction	     		= new Sys_Function_RecordFunctions();	
		$objOrg						= new User_modOrg();
		Zend_Loader::loadClass('Sys_Mail_Phpmailer');
		//Lay cac hang so dung chung
		$arrConst = $this->objConfig->_setProjectPublicConst();
		$arrInput = $this->_request->getParams();
		//lay thong tin quan tri mang
		$arrSystemPara = $objFunction->getAllObjectbyListCode('','DM_THAM_SO_HE_THONG');
		$sStaffAdmin='';
		for($index =0; $index < sizeof($arrSystemPara); $index ++){
			if($arrSystemPara[$index]['C_CODE']=="TT_LIEN_HE_QTHT"){
				$sStaffAdmin=$arrSystemPara[$index]['C_NAME'];
			}
		}
		//cap nhat database defaul_pass
		$sPassWord='';
		if($arrInput['text_pass']!=''){
			$sPassWord=$arrInput['text_pass'];
		}
		else {
			$sPassWord=$arrConst['_DEFAUL_PASS_WORD'];
		}
		$arrResult = $objOrg->USERStaffResetPassWord($arrInput['staffid'],md5($sPassWord));
		//thuc hien gui mail
		if($arrInput['email']!=''){
			$v_message_text=str_replace('#FULLNAME#', $arrInput['full_name'],$arrConst['_MESSAGE_EMAIL']);
			$v_message_text=str_replace('#NEWPASSWORD#', $sPassWord, $v_message_text);
			$v_message_text=$v_message_text.$sStaffAdmin;
			if($objFunction->smtpmailer($arrInput['email'],$arrInput['full_name'],$arrConst['_EMAIL_PUBLIC'],$arrConst['_PASS_WORD_EMAIL'],$arrConst['_ADMIN_USER'],$arrConst['_TITLE_EMAIL'],$v_message_text)){				
				echo 'Thay đổi mật khẩu và Gửi Email thông báo thành công';
			}else{ echo 'Gửi Email thông báo thất bại, bạn hãy thông báo cho người sử dụng bằng cách khác';
			}
		}
		else {
			echo 'Thay đổi mật khẩu thành công, không có thông tin Email của người sử dụng, bạn hãy thông báo cho người sử dụng bằng cách khác';
		}
		exit;
	}	
	/* Nguoi thuc hien: Do viet Hai
	 * Ngay thuc hien: 4/11/2011
	 * Phuong thuc thuc hien In danh sach nguoi dung
	 * Theo dang WEB or EXCEL
	 * */
	
public function printAction(){	
		$ojbSysLib = new Sys_Library();			
		$ojbSysInitConfig = new Sys_Init_Config();
		$v_SitePath = $ojbSysInitConfig->_setWebSitePath();	
		$objOrg	    = new User_modOrg();						
		$iOwnerId 	= $_SESSION['OWNER_ID'];		
		$path = $_SERVER['SCRIPT_FILENAME'];
		$path = substr($path, 0, -9);	
		$path = str_replace("/", "\\", $path) . "templates\\staff\\NSD.html";
		//echo "URI<br/>" . $path; exit;
		$v_html_header = $ojbSysLib->_read_file($path);	
		//echo $v_html_header; exit;
		$v_exporttype = $this->_request->getParam('hdn_exporttype');
		$v_unit_name = $this->_request->getParam('hdn_unit');
		$v_fullTextSearch = $this->_request->getParam('hdn_fullTextSearch');
		$v_DepartMent =	$this->_request->getParam('hnd_department');			
		//echo "Type:" . $v_exporttype. $v_unit_name . $v_fullTextSearch . $v_DepartMent; exit;				
		if($v_exporttype == 1){
		   $report_file = 'Danh_Sach.html';
		}elseif ($v_exporttype == 2) {
		   $report_file = 'Danh_Sach.xls';
		}								
		$v_conten = '';							
		$arrResult = $objOrg->USERStaffGetAllBySearch('',$v_fullTextSearch,$v_unit_name,$v_DepartMent);
		$this->view->arrResult = $arrResult;	
		//var_dump($arrResult); exit;	
		$v_conten = $v_conten.'<table class="List_user"  boder="1" cellpadding="0" cellspacing="0" width="100%" id="table1">';			
		$v_conten = $v_conten.'<tr>';
		$v_conten = $v_conten.'<td class = "title">Họ và Tên</td>';
		$v_conten = $v_conten.'<td class = "title">Giới tính </td>';
		$v_conten = $v_conten.'<td class = "title">Chức vụ</td>';
		$v_conten = $v_conten.'<td class = "title">Địa chỉ</td>';
		$v_conten = $v_conten.'<td class = "title">Đơn vị</td>';
		$v_conten = $v_conten.'<td class = "title">ĐT CQ</td>';
		$v_conten = $v_conten.'<td class = "title">ĐT NR</td>';
		$v_conten = $v_conten.'<td class = "title">ĐTDĐ</td>';
		$v_conten = $v_conten.'<td class = "title">Email</td>';
		$v_conten = $v_conten.'</tr>';
		$v_conten = $v_conten.'<tr>';		
		for($i =0; $i < sizeof($arrResult);$i++){		
			$v_conten = $v_conten.'<td style="text-align:left;">&nbsp;'.$arrResult[$i]['C_NAME'].'</td>';
			$v_conten = $v_conten.'<td style="text-align:left;">&nbsp;'.$arrResult[$i]['C_SEX'].'</td>';
			$v_conten = $v_conten.'<td style="text-align:left;">&nbsp;'.$arrResult[$i]['POSITION_NAME'].'</td>';
			$v_conten = $v_conten.'<td style="text-align:left;">&nbsp;'.$arrResult[$i]['C_ADDRESS'].'</td>';		
			$v_conten = $v_conten.'<td style="text-align:left;">&nbsp;'.$arrResult[$i]['UNIT_NAME'].'</td>';
			$v_conten = $v_conten.'<td style="text-align:right;">&nbsp;'.$arrResult[$i]['C_TEL'].'</td>';
			$v_conten = $v_conten.'<td style="text-align:right;">&nbsp;'.$arrResult[$i]['C_TEL_HOME'].'</td>';
			$v_conten = $v_conten.'<td style="text-align:right;">&nbsp;'.$arrResult[$i]['C_TEL_MOBILE'].'</td>';
			$v_conten = $v_conten.'<td style="text-align:left;">&nbsp;'.$arrResult[$i]['C_EMAIL'].'</td>';		
			$v_conten = $v_conten.'</tr>';			
		}
		$v_conten = $v_conten.'</table>';
		//echo $v_conten; exit;			
		$v_title = "DANH SÁCH CÁN BỘ";
		$v_resul = str_replace("#TITLE#",$v_title,$v_html_header);															
		$v_resul = str_replace("#CONTENBODY#",$v_conten,$v_resul);			
		// Duong dan file report	
		$path = $_SERVER['SCRIPT_FILENAME'];
		$path = substr($path, 0, -9);	
		$my_report_file = str_replace("/", "\\", $path) . "public\\" . $report_file;
		//echo $v_resul; exit;
		$ojbSysLib->_write_file($my_report_file,$v_resul);
		// doc file pdf len trinh duyet				
		$my_report_file = 'http://'.$_SERVER['HTTP_HOST'].$v_SitePath.'public/'.$report_file;
		//echo $my_report_file; exit;		
		$this->view->my_report_file = $my_report_file; 
		//echo $my_report_file; exit;
		//echo "<script type='text/javascript'> window.open('http://google.com');</script>";
		//exit;
						
}		
public function checkvntextAction(){	
		$sText = $this->_request->getParam('value','');
		if($sText!=Sys_Publib_Library::_convertVNtoEN($sText)){
			echo 'Tên đăng nhập phải không dấu';
		}
		else {
			echo '';
		}
		exit;
	}	
	
}?>

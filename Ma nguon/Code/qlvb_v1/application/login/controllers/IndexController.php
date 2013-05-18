<?php
class Login_IndexController extends  Zend_Controller_Action {
	public function init(){		
		//Load cau hinh thu muc trong file config.ini
        $tempDirApp = Zend_Registry::get('conDirApp');
		$this->_dirApp = $tempDirApp->toArray();
		$this->view->dirApp = $tempDirApp->toArray();
		Zend_Loader::loadClass('login_modCheckLogin');		
		//Cau hinh cho Zend_layoutasdfsdfsd
		Zend_Layout::startMvc(array(
			    'layoutPath' => $this->_dirApp['layout'],
			    'layout' => 'login'			    
			    ));	
		// Load tat ca cac file Js va Css
		$this->view->LoadAllFileJsCss = Sys_Publib_Library::_getAllFileJavaScriptCss('','js','util.js,js_calendar.js',',','js') 
										.Sys_Publib_Library::_getAllFileJavaScriptCss('','js','ajax.js',',','js') 
										.Sys_Publib_Library::_getAllFileJavaScriptCss('','js','jquery-1.4.2.min.js,jquery.simplemodal.js,jQuery.equalHeights.js',',','js')
										.Sys_Publib_Library::_getAllFileJavaScriptCss('','js/LibSearch','actb_search.js,common_search.js',',','js')
										.Sys_Publib_Library::_getAllFileJavaScriptCss('','style','simpleModal.css',',','css');	
		$this->view->showModelDialog = 1;//An menu
		//Lay duong dan thu muc goc (path directory root)
		$this->view->baseUrl = $this->_request->getBaseUrl() . "/public/";
		$objConfig = new Sys_Init_Config();
		$this->view->UrlAjax = $objConfig->_setUrlAjax();	
	}	
	public function indexAction(){				
		//Zend_Loader::loadClass('Zend_Session');
		//Zend_Session::destroy();
		$sReURL = Sys_Init_Config::_setUserLoginUrl();
		$objInitConfig 		= new Sys_Init_Config();
		$objmodLogin		= new login_modCheckLogin();
		//
		$this->view->bodyTitle = 'THÔNG TIN ĐĂNG NHẬP';
		$arrConst =$objInitConfig->_setProjectPublicConst();
		$this->view->arrConst= $arrConst;//C-> V(arrConst)
		$hdhOption = $this->_request->getParam('hdn_option');
		$this->view->hdn_option= $hdhOption;
		//Lay trong cook
		$sCheckReme = Sys_Library::_getCookie("sCheckReme");
		if($sCheckReme){
			$sUserName = Sys_Library::_getCookie("sUserName");
			$sPassWord = Sys_Library::_getCookie("sPassWord");
		}else{
			$sCheckReme = 0;
		}
		$sUserNameTemp =$this->_request->getParam('txt_usename');
		if($sUserNameTemp!=''){
			$sUserName = $sUserNameTemp;
			$sPassWord = '';
		}
		$this->view->txt_password= $sPassWord;
		$this->view->txt_usename= $sUserName;
		$this->view->sCheckReme= $sCheckReme;
		//$sUserNameTemp =$this->_request->getParam('txt_usename');		
		//$this->view->sUserNameTemp	= $sUserNameTemp;
		$sUrlre= $this->_request->getParam('urlre');
		$this->view->urlRe =$this->baseUrl . "/login/";
		if($hdhOption =="1"){
			$sUserName =$this->_request->getParam('txt_usename');//Lay tham so tu V truyen den C
			$sPassWord =$this->_request->getParam('txt_password');
			$sPassMD5_url = md5($sPassWord);
			$arrStaff =$objmodLogin->UserCheckLogin($sUserName,md5($sPassWord));//kt username va pass NSD neu dung tra ra mot ban ghi chua tt NSD
			//var_dump($arrStaff); exit;
			if (sizeof($arrStaff)>0){
				//Luu thong tin vao cook
				$sCheckReme = $this->_request->getParam('hdn_autorem');
				Sys_Library::_createCookie("sCheckReme",$sCheckReme);
				if($sCheckReme){
					Sys_Library::_createCookie("sUserName",$sUserName);
					Sys_Library::_createCookie("sPassWord",$sPassWord);
				}
				//luu thong tin nguoi dang nhap vao session
				@session_start();
				$_SESSION['INFORMATION_STAFF_LOGIN'] = sys_library::_UserInfo($arrStaff[0]['C_NAME'],$arrStaff[0]['C_POSITION_CODE'],$arrStaff[0]['C_UNIT_NAME'],$sUserName);
				$_SESSION['STAFF_LOGIN'] = sys_library::_UserInfo($arrStaff[0]['C_NAME'],$arrStaff[0]['C_POSITION_CODE'],$arrStaff[0]['C_UNIT_NAME'],$sUserName);
				//var_dump($arrStaff); exit;	
				//$_SESSION['staff_id'] = //$arrStaff[0]['PK_STAFF'];//str_replace('{','',str_replace('}', '',$arrStaff[0]['PK_STAFF'])); //Luu ID can bo dang nhap vao Session
				$_SESSION['staff_id'] = str_replace('{','',str_replace('}', '',$arrStaff[0]['PK_STAFF']));
				$_SESSION['OWNER_CODE'] = $arrStaff[0]['C_UNIT_OWNER_CODE'];//luu don vi trien khai		
				//var_dump($_SESSION['OWNER_CODE']); exit;	
				$_SESSION['STAFF_PERMISSTION'] = $arrStaff[0]['C_ROLE'];

				//Lay thong tin phong ban
				if(!isset($_SESSION['arr_all_staff']) || is_null($_SESSION['arr_all_staff'])){
					//Luu tru thong tin phong ban cua toan bo cac don vi trien khai
					$_SESSION['arr_all_unit_keep'] = Sys_Init_Session::SesGetDetailInfoOfAllUnit();	
					//Luu co cau to chuc cua can bo hien tai
					$_SESSION['arr_all_unit'] = Sys_Init_Session::_getAllUnitsByCurrentStaff($_SESSION['OWNER_CODE']);	
				}
				
				//Lay thong tin can bo
				if(!isset($_SESSION['arr_all_staff']) || is_null($_SESSION['arr_all_staff'])){
					//Luu thong tin can bo cua tat ca don vi trien khai
					$_SESSION['arr_all_staff_keep'] = Sys_Init_Session::SesGetPersonalInfoOfAllStaff();	
					//Luu thong tin can bo thuoc don vi NSD hien thoi
					$_SESSION['arr_all_staff'] = Sys_Init_Session::_getAllUsersByCurrentOrg($_SESSION['OWNER_CODE']);	
				}
				
				//Lay quyen cua NSD
				if(!isset($_SESSION['arrStaffPermission']) || is_null($_SESSION['arrStaffPermission'])){
					$_SESSION['arrStaffPermission'] = Sys_Init_Session::StaffPermisionGetAll($_SESSION['staff_id']);	
				}				
				
				//Lay thong tin don vi trien khai
				if(!isset($_SESSION['SesGetAllOwner']) || is_null($_SESSION['SesGetAllOwner'])){		
					$_SESSION['SesGetAllOwner'] = Sys_Init_Session::SesGetAllOwner();		
				}
					
				//Lay secssion 
				$arr_value = explode("|!~~!|",Sys_Init_Session::_getUnitLevelOneNameAndId($_SESSION['staff_id']));
				$_SESSION['OWNER_ID'] = $arr_value[0];
				//var_dump($_SESSION['OWNER_ID']);
				//exit;
				//Thanh cong thi thuc hien URL mac dinh duoc cau hinh trong file Config
				$this->_redirect(Sys_Init_Config::_setDefaultUrl());
				
			}else{?>
				<script>
					alert('Tên đăng nhập hoặc mật khẩu không đúng!');
				</script><?php
			}	
		}
	}
public function changepassAction()
	{						
		$objmodLogin = new login_modCheckLogin();
		$this->view->hideDisplayMeneLeft = ""; 
		$this->view->hideDisplayMenuHeader ="";
		$this->view->hideDisplayMenuFooter = "";
		$this->view->bodyTitle = "THAY ĐỔI MẬT KHẨU NGƯỜI SỬ DỤNG";
		$sUrl = $_SERVER['REQUEST_URI'];
		$objConfig = new Sys_Init_Config();
		$this->view->urlbackup=$objConfig->_setWebSitePath().'login/index/changepass/';			
		//Lay cac hang so dung chung
		$arrConst = Sys_Init_Config::_setProjectPublicConst();
		$this->view->arrConst = $arrConst;		
		$StaffName = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'name');
		$this->view->StaffName = $StaffName;
		$username = $this->_request->getParam('username');
		$this->view->username = $username;
		$sOption = $this->_request->getParam('hdn_option');
		
		$sPassword	 = $this->_request->getParam('hdn_new_pass');	
		$sOldPassword	 = $this->_request->getParam('hdn_old_pass');	
		$sUserName		 = 	 $this->_request->getParam('hdn_username');		
		if($sOption =='CAP_NHAT'){													
				$staff_id =$_SESSION['staff_id'];				
				$arrayResuft = $objmodLogin->UserChangePass($staff_id,md5($sPassword),md5($sOldPassword),$sUserName);
				$this->view->arrayResuft = $arrayResuft;	
						
				if($arrayResuft['C_USER_NAME']){
					//echo "OK"; exit;	
					echo 'Thay đổi thông tin thành công';	exit;				
					$_SESSION['C_USER_NAME'] = $arrayResuft['C_USER_NAME'];	
				}				
				if($arrayResuft['RET_ERROR']){					
					echo $arrayResuft['RET_ERROR']; exit;
			}				
		}		
	}		
}
?>
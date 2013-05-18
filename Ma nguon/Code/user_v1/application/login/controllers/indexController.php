<?php
/**
 * Class Xu ly kiem tra xac thuc thong tin NSD khi dang nhap vao phan mem
 */
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
			    'layout' => 'index'			    
			    ));	
		// Load tat ca cac file Js va Css
		$this->view->LoadAllFileJsCss = Sys_Publib_Library::_getAllFileJavaScriptCss('','js','position.js,jquery-1.4.3.min.js,ajax.js',',','js');	
		$this->view->showModelDialog = 1;//An menu
		//Lay duong dan thu muc goc (path directory root)
		$this->view->baseUrl = $this->_request->getBaseUrl() . "/public/";
	}	
	/**
	 * Creater : TuyenNH
	 * Date : 22/06/2011
	 * Idea : Tao phuong thuc hien kiem tra xac thuc login vao he thong
	 */
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
		$sUserName =$this->_request->getParam('txt_usename');//Lay tham so tu V truyen den C
		$this->view->txt_usename= $sUserName;
		$sPassWord =$this->_request->getParam('txt_password');
		$this->view->txt_password= $sPassWord;
		$sUrlre= $this->_request->getParam('urlre');
		$this->view->urlRe =$this->baseUrl . "/login/";
		//echo $hdhOption;
		if($hdhOption =="1"){
			$arrStaff =$objmodLogin->UserCheckLogin($sUserName,md5($sPassWord));//kt username va pass NSD neu dung tra ra mot ban ghi chua tt NSD
			//var_dump($arrStaff);exit;
			if (sizeof($arrStaff)>0){
				//neu khong co quyen quan tri thi thong bao
				if($arrStaff['C_ROLE']!=Sys_Init_Config::_setPermisstionSystem(1) & $arrStaff['C_ROLE']!=Sys_Init_Config::_setPermisstionSystem(2)){?>
					<script>
					alert('Bạn không có quyền quản trị hệ thống này!');
					</script><?php 
				}
			//luu thong tin nguoi dang nhap vao session
				else{
					@session_start();
					$_SESSION['INFORMATION_STAFF_LOGIN'] = Sys_library::_getInformationStaffLogin($arrStaff['C_NAME'],$arrStaff['C_POSITION_CODE'],$arrStaff['C_UNIT_NAME']);
					$_SESSION['staff_id'] = $arrStaff['PK_STAFF']; //Luu ID can bo dang nhap vao Session
					$_SESSION['STAFF_PERMISSTION'] = $arrStaff['C_ROLE'];//luu quyen
					$_SESSION['OWNER_CODE'] = $arrStaff['C_UNIT_OWNER_CODE'];//luu don vi trien khai
					//echo $_SESSION['staff_id'];exit;
					//chuyen trang khi dang nhap thanh cong
					$this->_redirect('user/org/index/');
				}
			}
			else{?>
				<script>
					alert('Tên đăng nhập hoặc mật khẩu không đúng!');
				</script><?php
			}	
		}
	}
}
?>
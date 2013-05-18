<?php
// Dinh nghia duong dan den thu vien cua Zend
	set_include_path('../../../../library/'
			. PATH_SEPARATOR . '../../../../application/models/'
			. PATH_SEPARATOR . '../../../../config/');
			
	// Goi class Zend_Load
	include "../../../../library/Zend/Loader.php";	
	Zend_Loader::loadClass('Zend_Config_Ini');	
	Zend_Loader::loadClass('Zend_Registry');
	Zend_Loader::loadClass('Sys_Library');
	Zend_Loader::loadClass('Zend_Db');	
	Zend_Loader::loadClass('Sys_DB_Connection');
	Zend_Loader::loadClass('Sys_Init_Config');
	//echo "OK";  exit;
	//Ket noi CSDL SQL theo kieu ADODB
	$connectSQL = new Zend_Config_Ini('../../../../config/config.ini','dbmssql');
	$registry = Zend_Registry::getInstance();
	$registry->set('connectSQL', $connectSQL);
	$connAdo = Sys_Db_Connection::connectADO($connectSQL->db->adapter,$connectSQL->db->config->toArray());	
	//Load class login_modCheckLogin
	Zend_Loader::loadClass('Login_modCheckLogin');
	$objChangepass = new login_modCheckLogin();		
	$sPassword = $_REQUEST['hdn_passnew']; //$this->_request->getParam('hdn_passnew');
	$sOldPassword = $_REQUEST['hnd_pass_old']; // $this->_request->getParam('hnd_pass_old');	
	$sUserName   = $_REQUEST['hdn_username']; //$this->_request->getParam('hdn_username');
	$staff_id = $_REQUEST['hdn_staff_id'];	
	//echo "OK" . $sPassword; exit;	
				$arrayResuft = $objChangepass->UserChangePass($staff_id,md5($sPassword),md5($sOldPassword),$sUserName);				
				//$this->view->arrayResuft = $arrayResuft;				
				if($arrayResuft['C_USER_NAME']){
					echo '<script>
							alert(\'Thay đổi thông tin thành công, tên đang nhập mới của bạn là  '.$arrayResuft['C_USER_NAME'].'\');
						</script>';						
					$_SESSION['C_USER_NAME'] = $arrayResuft['C_USER_NAME'];	
				}				
				if($arrayResuft['RET_ERROR']){
					echo '<script>alert(\''.$arrayResuft['RET_ERROR'].'\');</script>';
			}		
	//echo $html;
?>
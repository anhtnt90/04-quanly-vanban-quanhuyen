<?php
	//error_reporting(E_ALL|E_STRICT);
	//error_reporting(0);
	date_default_timezone_set('Europe/London');
	//@ini_set('display_errors','0');
	
	// Dinh nghia duong dan den thu vien cua Zend
	set_include_path('./library/'
			. PATH_SEPARATOR . './public/'
			. PATH_SEPARATOR . './application/models'. PATH_SEPARATOR . './application/');
	
	// Goi class Zend_Load
	include "./library/Zend/Loader.php";	
	
	//Goi class Controller
	Zend_Loader::loadClass('Zend_Controller_Front');	
	Zend_Loader::loadClass('Zend_View');	
	Zend_Loader::loadClass('Zend_Config_Ini');		
	Zend_Loader::loadClass('Zend_Registry');
	Zend_Loader::loadClass('Zend_Layout');	
	Zend_Loader::loadClass('Zend_Db');	
	Zend_Loader::loadClass('Sys_Db_Connection');	
	Zend_Loader::loadClass('Sys_Library');		
	Zend_Loader::loadClass('Sys_Xml');
	Zend_Loader::loadClass('Sys_Init_Session');	
	Zend_Loader::loadClass('Sys_Init_Config');
	Zend_Loader::loadClass('Sys_Function_DocFunctions'); //Goi lop tao cac phuong thuc dung chung
	Zend_Loader::loadClass('Sys_Publib_Browser'); 
	//Zend_Loader::loadClass('Sys_Editor_Fckeditor'); 
	//Zend_Loader::loadClass('Sys_Publib_Logout'); 
	
	//Khai bao bien toan cuc 
	$conDirApp = new Zend_Config_Ini('./config/config.ini','dirApp');
	$registry = Zend_Registry::getInstance();
	$registry->set('conDirApp', $conDirApp);	
	
	//Dinh nghia hang so dung chung 
	$ConstPublic = new Zend_Config_Ini('./config/config.ini','ConstPublic');
	$registry = Zend_Registry::getInstance();
	$registry->set('ConstPublic', $ConstPublic);	
	
	//Ket noi CSDL SQL theo kieu ADODB
	$connectSQL = new Zend_Config_Ini('./config/config.ini','dbmssql');
	$registry = Zend_Registry::getInstance();
	$registry->set('connectSQL', $connectSQL);
	$connAdo = Sys_Db_Connection::connectADO($connectSQL->db->adapter,$connectSQL->db->config->toArray());

	//Khoi tao bien session
	//Sys_Init_Session::getValueSession($ConstPublic->toArray());
	//Lay url hien tai
	$url = Sys_Function_DocFunctions::curPageURL();               
    // Goi ham kiem tra user login
	Sys_Function_DocFunctions::CheckLogin($url);
		
	// setup controller
	$frontController = Zend_Controller_Front::getInstance();	
	$frontController->addControllerDirectory('./application/Received/controllers', 'received');	
	$frontController->addControllerDirectory('./application/listxml/controllers', 'listxml');
	$frontController->addControllerDirectory('./application/reports/controllers', 'reports');
	$frontController->addControllerDirectory('./application/notification/controllers', 'notification');
	$frontController->addControllerDirectory('./application/controllers');
	$frontController->addControllerDirectory('./application/sent/controllers','sent');
	$frontController->addControllerDirectory('./application/record/controllers', 'record');
	$frontController->addControllerDirectory('./application/statistics/controllers','statistics');
	$frontController->addControllerDirectory('./application/permission/controllers','permission');
	$frontController->addControllerDirectory('./application/exit/controllers','exit');
	$frontController->addControllerDirectory('./application/assigner/controllers','assign');
	$frontController->addControllerDirectory('./application/sendReceived/controllers','sendReceived');
	$frontController->addControllerDirectory('./application/search/controllers','search');
	$frontController->addControllerDirectory('./application/work/controllers','work');
	$frontController->addControllerDirectory('./application/sms/controllers','sms');
	$frontController->addControllerDirectory('./application/login/controllers','login');	
	$frontController->addControllerDirectory('./application/calendar/controllers','calendar');
	$frontController->addControllerDirectory('./application/exchangework/controllers','exchangework');	
	$frontController->addControllerDirectory('./application/dashboard/controllers','dashboard');	
	$frontController->addControllerDirectory('./application/email/controllers','email');
	$frontController->addControllerDirectory('./application/authorized/controllers','authorized');	
	$frontController->throwExceptions(true);
	$frontController->setDefaultModule('public');
	try {
		$frontController->dispatch();
	} catch (Exception $e) {
		echo 'Kh&#244;ng t&#236;m th&#7845;y trang b&#7841;n y&#234;u c&#7847;u! C&#243; th&#7875; &#273;&#432;&#7901;ng d&#7851;n kh&#244;ng ch&#237;nh x&#225;c!';
	}
?>
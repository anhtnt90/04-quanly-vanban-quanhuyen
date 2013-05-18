<?php
	//error_reporting(E_ALL|E_STRICT);
	//error_reporting(0);
	date_default_timezone_set('Europe/London');
	//@ini_set('display_errors','0');
	
	// Dinh nghia duong dan den thu vien cua Zend
	set_include_path('./library/'
			. PATH_SEPARATOR . './application/models'. PATH_SEPARATOR . './application/');
	
	// Goi class Zend_Load
	include "./library/Zend/Loader.php";	
	
	//Goi class Controller
	Zend_Loader::loadClass('Zend_Controller_Front');	
	Zend_Loader::loadClass('Zend_View');	
	Zend_Loader::loadClass('Zend_Config_Ini');		
	Zend_Loader::loadClass('Zend_Config_Xml');	
	Zend_Loader::loadClass('Zend_Registry');
	Zend_Loader::loadClass('Zend_Layout');	
	Zend_Loader::loadClass('Zend_Db');	
	Zend_Loader::loadClass('Sys_Db_Connection');	
	Zend_Loader::loadClass('Sys_Library');		
	Zend_Loader::loadClass('Sys_Xml');
	Zend_Loader::loadClass('Sys_Init_Session');	
	Zend_Loader::loadClass('Sys_Init_Config');	
	Zend_Loader::loadClass('Sys_Function_RecordFunctions'); //Goi lop tao cac phuong thuc dung chung
	Zend_Loader::loadClass('Sys_Publib_Browser'); 
	
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
	// Goi ham kiem tra user login
	Sys_Function_RecordFunctions::CheckLogin();
	// setup controller
	$frontController = Zend_Controller_Front::getInstance();	
	$frontController->addControllerDirectory('./application/controllers');
	$frontController->addControllerDirectory('./application/user/controllers', 'user');
	$frontController->addControllerDirectory('./application/listxml/controllers', 'listxml');
	$frontController->addControllerDirectory('./application/login/controllers','login');
	$frontController->addControllerDirectory('./application/logout/controllers','logout');
	//
	$frontController->throwExceptions(true);
	$frontController->setDefaultModule('public');
	$frontController->dispatch();
?>
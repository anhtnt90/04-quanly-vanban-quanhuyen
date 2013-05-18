<?php
// Dinh nghia duong dan den thu vien cua Zend
	set_include_path('../../library/'
			. PATH_SEPARATOR . '../../application/models/'
			. PATH_SEPARATOR . '../../config/');			
	// Goi class Zend_Load
	include "../../library/Zend/Loader.php";	
	Zend_Loader::loadClass('Zend_Config_Ini');
	Zend_Loader::loadClass('Zend_Registry');
	Zend_Loader::loadClass('Sys_Library');
	Zend_Loader::loadClass('Zend_Db');	
	Zend_Loader::loadClass('Sys_DB_Connection');
	
	//Ket noi CSDL SQL theo kieu ADODB
	$connectSQL = new Zend_Config_Ini('../../config/config.ini','dbmssql');
	$registry = Zend_Registry::getInstance();
	$registry->set('connectSQL', $connectSQL);
	$connAdo = Sys_Db_Connection::connectADO($connectSQL->db->adapter,$connectSQL->db->config->toArray());
	Zend_Loader::loadClass(jobProfile_modJobProfile);
	$jobProfile = new jobProfile_modJobProfile();	
	$fileNameList = substr($_REQUEST['fileNameList'],0,-4);
	//xoa file trong CSDL
	$jobProfile->_deleteFileUpload($fileNameList);
	//xoa file tren o cung
	$arrFileName = explode('#@@#', $fileNameList);
	$scriptUrl = $_SERVER['SCRIPT_FILENAME'];
	$scriptFileName = explode("/", $scriptUrl);
	$linkFile = $scriptFileName[0] . "\\" . $scriptFileName[1] . "\\" . $scriptFileName[2] . "\\" . $scriptFileName[3] . "\\" . $scriptFileName[4] . "\\" . "public\attach-file\\";
	for($i =0; $i < sizeof($arrFileName); $i ++){
		$fileId = explode("!~!", $arrFileName[$i]);
		$fileId = explode("_" ,$fileId[0]);
		$unlink = $linkFile . $fileId[0] . "\\" . $fileId[1] . "\\" . $arrFileName[$i];
		unlink($unlink);
	}
?>
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
	
	//Ket noi CSDL SQL theo kieu ADODB
	$connectSQL = new Zend_Config_Ini('../../../../config/config.ini','dbmssql');
	$registry = Zend_Registry::getInstance();
	$registry->set('connectSQL', $connectSQL);
	$connAdo = Sys_Db_Connection::connectADO($connectSQL->db->adapter,$connectSQL->db->config->toArray());
		
	//Load class Sent_documentSent
	Zend_Loader::loadClass('Sent_documentSent');
	$objSent = new Sent_documentSent();
	$date = $_REQUEST['date'];
	$number = $_REQUEST['sysbol'];
	$nowYear = explode('/',$date);
	$arrSysbol = $objSent->_getCheckSysbol($nowYear[2], $number);
	$sGetError = "";
	for($i = 0; $i < sizeof($arrSysbol); $i ++){
		$sGetError = $sGetError . $arrSysbol[$i]['C_SYMBOL'] . "!@--@!";
	}
	$html = "";
	$html .= "<input optional = 'true' style = 'width:500px;' id = 'getSysbolForYear' value = '" . $sGetError . "'>";
	echo $html;
?>

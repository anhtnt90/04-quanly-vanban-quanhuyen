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
			
	//Load class Received_modReceived
	Zend_Loader::loadClass('Received_modReceived');
	$objReceive = new received_modReceived();
	//Lay SO VB
	$sDocBook = $_REQUEST['DocBook'];
	//Lay ID DON VI
	$iUnitId = $_REQUEST['iUnitId']; 
	//Lay tu dong so den VB 
	$arrDocNumber = $objReceive ->DocReceivedGetNumber($sDocBook,$iUnitId);
	//var_dump($arrDocNumber);
	if($arrDocNumber[0][C_NUM_MAX] != null || $arrDocNumber[0][C_NUM_MAX] !="" ){
		$DocNumber = $arrDocNumber[0][C_NUM_MAX] + 1;
	}else{
		$DocNumber = 1; 
	}
	$arrDocNumber = $arrDocNumber[0][C_NUMBER_DOCUMENT_LIST];
	$html = "<input class='textbox' style='width:30%;' type='text' onchange='submitDocNumber(this,$arrDocNumber);'  id='C_NUM' name='C_NUM' value='$DocNumber' align='right' option = 'true' xml_tag_in_db='' xml_data='false' column_name='C_NUM' message='SO VAN BAN DEN phai la so nguyen duong (1, 2, …)!'>";
	echo $html;
?>
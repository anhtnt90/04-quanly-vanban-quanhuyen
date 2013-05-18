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
	Zend_Loader::loadClass('web_modWebMenu');
	$objMenu = new web_modWebMenu();
	//Lay cap chuyen muc
	$iLevel = $_REQUEST['iLevel'];
	//Lay vi tri
	$iPosition = $_REQUEST['iPosition']; 
	//Lay ID chuyen muc cha
	$sMenuID = $_REQUEST['sMenuID']; 
	//Lay tu dong so den VB 
	$arrOrder = $objMenu ->Web_MenuGetOrder($iPosition,$iLevel,$sMenuID);
	//var_dump($arrDocNumber);
	if($arrOrder[0][ORDERPOS] != null || $arrOrder[0][ORDERPOS] !="" ){
		$DocNumber = $arrOrder[0][ORDERPOS] + 1;
	}else{
		$DocNumber = 1; 
	}
	//$html = "<input class='textbox' style='width:30%;' type='text' onchange='submitDocNumber(this,$arrDocNumber);'  id='C_NUM' name='C_NUM' value='$DocNumber' align='right' option = 'true' xml_tag_in_db='' xml_data='false' column_name='C_NUM' message='SO VAN BAN DEN phai la so nguyen duong (1, 2, …)!'>";
	$html = "<input style='width:90%;' type='text' id='ORDER' name='ORDER' value='$DocNumber' isnumeric = 'true' option = 'true' message='SỐ THƯ TỰ phải nhập dạng chữ số 1, 2, 3, ...!'>";
	echo $html;
?>
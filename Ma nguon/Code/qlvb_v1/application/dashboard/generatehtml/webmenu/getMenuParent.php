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
	if($iLevel==1){
		$arrOrder = $objMenu ->WebMenuGetAll('4','','0','2');
	}
	if($iLevel==2){
		$arrOrder = $objMenu ->WebMenuGetAll('4','','1','2');
	}
	$html =	'<select style="width:100%;" id="PK_WEB_MENU" name="PK_WEB_MENU" class="textbox normal_label" onchange="getOrderNumber(document.getElementById(\'OrderNumber\'));" optional = "true">';
	$html = $html."<option id='' name = '' value=''>-- Chọn chuyên mục gốc --</option>";
	$html = $html . Sys_Library::_generateSelectOption($arrOrder,'PK_WEB_MENU','PK_WEB_MENU','C_NAME','');
	$html = $html .	"</select>";
	//$html = "<input class='textbox' style='width:30%;' type='text' onchange='submitDocNumber(this,$arrDocNumber);'  id='C_NUM' name='C_NUM' value='$DocNumber' align='right' option = 'true' xml_tag_in_db='' xml_data='false' column_name='C_NUM' message='SO VAN BAN DEN phai la so nguyen duong (1, 2, …)!'>";
	echo $html;
?>
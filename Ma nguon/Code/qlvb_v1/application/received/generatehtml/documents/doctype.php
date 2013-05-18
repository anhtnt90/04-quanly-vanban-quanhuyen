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
	Zend_Loader::loadClass('Received_modReceived');
	$objReceive = new Received_modReceived();
	$code = $_REQUEST['code'];
	$xmlTag = $_REQUEST['xmlTag'];
	if($xmlTag == "ten_co_quan"){
		$xmlTag = "DM_CAP_NOI_GUI_VAN_BAN";
	}
	$html = "<select id = '$xmlTag' name='$xmlTag' optional = 'true' style='width:99%;' class='textbox normal_label'  xml_data= 'true' xml_tag_in_db='$xmlTag' column_name='' >";
	$html .= "<option id = '' value = ''>--- Chọn---</option>";
	$arrDetails = $objReceive->getDetail($code,$xmlTag);
	$html .= Sys_Library::_generateSelectOption($arrDetails,'C_CODE','C_CODE','C_NAME','');
	$html .= "</select>";
	echo $html;
?>
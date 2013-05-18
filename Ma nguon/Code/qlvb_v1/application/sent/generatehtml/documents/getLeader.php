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
	//Danh sach LANH DAO DAO DON VI
	$code = $_REQUEST['groupLeader'];
	$option = $_REQUEST['option'];
	if($option != 1){
		$arrLeader = $objSent->getDetail($code,'nhom_canbo');		
		$html = "<select id='FK_SIGNER' name='FK_SIGNER' optional = 'true' style='width:200px;' class='textbox normal_label' xml_tag_in_db='' xml_data='false' column_name='FK_SIGNER'>";
		$html .= "<option id='' name = '' value=''>-- tất cả --</option>";
		$html .= Sys_Library::_generateSelectOption($arrLeader,'PK_LIST','C_CODE','C_NAME','');
		$html .= "</select>";
	}else{
		$arr = explode('@!!@', $code);
		$arrLeader = array();
		$k =0;
		for($i =0; $i < sizeof($arr) -1; $i ++){
			$arrStaff = explode('@@',$arr[$i]);
			$arrLeader[$k]['C_CODE'] = 	$arrStaff[0];
			$arrLeader[$k]['C_NAME'] = 	$arrStaff[1];
			$k ++;
		}
		$html = "<select id='FK_SIGNER' name='FK_SIGNER' optional = 'true' style='width:200px;' class='textbox normal_label' xml_tag_in_db='' xml_data='false' column_name='FK_SIGNER'>";
		$html .= "<option id='' name = '' value=''>-- tất cả --</option>";
		$html .= Sys_Library::_generateSelectOption($arrLeader,'C_CODE','C_CODE','C_NAME','');
		$html .= "</select>";
	}
	//echo htmlspecialchars($html);
	echo $html;
?>
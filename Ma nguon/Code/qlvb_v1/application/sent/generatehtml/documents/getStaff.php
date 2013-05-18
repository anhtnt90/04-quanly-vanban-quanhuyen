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
	Zend_Loader::loadClass('Sent_modSent');
	$objSent = new Sent_modSent();
	//Danh sach LANH DAO DAO DON VI
	$unitID = $_REQUEST['unitID'];	
	$arr_all_unit = explode('!@@!', $_REQUEST['arr_all_unit']);
	$arr_all_staff = explode('!@@!', $_REQUEST['arr_all_staff']);	
	$arr_all_parentUnitID = explode('@~@', $arr_all_unit[1]); 
	$arr_all_departmentID = explode('@~@', $arr_all_unit[0]);
	$arr_all_UnitId = array();
	$k =0;
	for($i = 0; $i < sizeof($arr_all_parentUnitID); $i ++){
		$arr_all_UnitId[$k]['id'] = $arr_all_departmentID[$i];
		$arr_all_UnitId[$k]['parnet_id'] = $arr_all_parentUnitID[$i];
		$k ++;
	}
	$arr_unit = array();
	foreach ($arr_all_UnitId as $unit){
		if($unit['id'] == $unitID || $unit['parnet_id'] == $unitID){
			$arr_unit[] = $unit;
		}
	}
	$arr_staff_id = explode('@~@', $arr_all_staff[0]);
	$arr_staff_unitid = explode('@~@', $arr_all_staff[1]);
	$arr_staff_name = explode('@~@', $arr_all_staff[2]);
	$arr_staff = array();
	$k =0;
	for($j =0; $j < sizeof($arr_staff_unitid); $j ++){
		$arr_staff[$k]['C_CODE'] = $arr_staff_id[$j];
		$arr_staff[$k]['unit_id'] = $arr_staff_unitid[$j];
		$arr_staff[$k]['C_NAME'] = $arr_staff_name[$j];
		$k ++;
	}
	$arr_all_staff_department = array();
	foreach ($arr_unit as $unit){
		foreach ($arr_staff as $staff){
			if($unit['id'] == $staff['unit_id']){
				$arr_all_staff_department[] = $staff;
			}
		}
	}
	$html = "<select id='FK_STAFF' name='FK_STAFF' option = 'true' style='width:200px;' class='textbox normal_label' xml_data='false' column_name='FK_STAFF' message = 'Ban phai chon CHUYEN VIEN SOAN THAO van ban!'>";
	$html .= "<option id='' name = '' value=''>-- Chọn chuyên viên--</option>";
	$html .= Sys_Library::_generateSelectOption($arr_all_staff_department,'C_CODE','C_CODE','C_NAME','');
	$html .= "</select>";
	//echo htmlspecialchars($html);
	echo $html;
?>
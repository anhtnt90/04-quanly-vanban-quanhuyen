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
	Zend_Loader::loadClass('Sys_Function_DocFunctions');
	Zend_Loader::loadClass('Sys_Publib_Library');	
	//Ket noi CSDL SQL theo kieu ADODB
	$connectSQL = new Zend_Config_Ini('../../../../config/config.ini','dbmssql');
	$registry = Zend_Registry::getInstance();
	$registry->set('connectSQL', $connectSQL);
	$connAdo = Sys_Db_Connection::connectADO($connectSQL->db->adapter,$connectSQL->db->config->toArray());
	//Load class Sent_documentSent
	Zend_Loader::loadClass('Sent_modSent');
	$objSent = new Sent_modSent();
	//Danh sach LANH DAO DAO DON VI
	$departmentID = $_REQUEST['unitID'];	
	$positionGroupCode = $_REQUEST['positionGroupCode'];
	$arr_all_staff 		= explode('!@@!', $_REQUEST['arr_all_staff']);	
	$arr_all_staff_ID 	= explode('@~@', $arr_all_staff[0]);
	$arr_all_staff_name = explode('@~@', $arr_all_staff[1]);
	$arr_all_staff_UnitID = explode('@~@', $arr_all_staff[2]);;
	$arr_all_staff_PositionCode = explode('@~@', $arr_all_staff[3]);
	$arr_all_staff_Code = explode('@~@', $arr_all_staff[4]);
	//Lay ra danh sach cac phan tu
	$k = 0;
	for($i=0;$i<sizeof($arr_all_staff_PositionCode);$i++){
		if($positionGroupCode == 'LANH_DAO_UB'){	
			if($arr_all_staff_PositionCode[$i] == $positionGroupCode){
			$staff[$k]['C_CODE'] = $arr_all_staff_ID[$i] ;	
			$staff[$k]['C_NAME']  = 	$arr_all_staff_Code[$i].' - '.$arr_all_staff_name[$i] ;	
			$k++;				
			}	
		}else{
			
			if($arr_all_staff_PositionCode[$i] == $positionGroupCode && $arr_all_staff_UnitID[$i] == $departmentID){
			$staff[$k]['C_CODE'] = $arr_all_staff_ID[$i] ;	
			$staff[$k]['C_NAME']  = 	$arr_all_staff_Code[$i].' - '.$arr_all_staff_name[$i] ;	
			$k++;				
			}
		}	
	}
	//var_dump($staff);
	//Sys_Publib_Library::_getAllFileJavaScriptCss('','sys-js/LibSearch','actb_search.js,common_search.js',',','js');
	Sys_Function_DocFunctions::doc_search_ajax($staff,"C_CODE","C_NAME","C_LEADER_POSITION_NAME","hdn_position_leader_name");	
	//var_dump($arr_all_staff_UnitID);
	$html = "<input style='width:200px' id ='C_LEADER_POSITION_NAME' name='C_LEADER_POSITION_NAME' type='text'  option = 'false'  xml_tag_in_db='' xml_data='false' column_name='C_LEADER_POSITION_NAME' onKeyDown='change_focus(document.forms[0],this)'>";
	//$html = "<select id='FK_SIGNER' name='FK_SIGNER' option = 'true' style='width:200px;' class='textbox normal_label' xml_data='false' column_name='FK_SIGNER' >";
	//$html .= "<option id='' name = '' value=''>-- Chọn người ký--</option>";
	//$html .= Sys_Library::_generateSelectOption($staff,'C_CODE','C_CODE','C_NAME','');
	//$html .= "</select>";
	echo $html;
?>	
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
	Zend_Loader::loadClass('Sys_Publib_Xml');
	Zend_Loader::loadClass('Sent_modSent');	
	Zend_Loader::loadClass('Sys_Init_Config');
	
	//Lay cac gia tri const
	$ojbSysInitConfig = new Sys_Init_Config();
	$arrConst =	$ojbSysInitConfig->_setProjectPublicConst();
	//Ket noi CSDL SQL theo kieu ADODB
	$connectSQL = new Zend_Config_Ini('../../../../config/config.ini','dbmssql');
	$registry = Zend_Registry::getInstance();
	$registry->set('connectSQL', $connectSQL);
	$connAdo = Sys_Db_Connection::connectADO($connectSQL->db->adapter,$connectSQL->db->config->toArray());

	// Tao doi tuong cho lop tren		
	Zend_Loader::loadClass('Listxml_modList');
	$objList = new Listxml_modList();
	//Lay SO VB
	$IdDoctype = $_REQUEST['IdDoctype'];
	$iUnitId = $_REQUEST['UnitId'];
	$sTextBook = $_REQUEST['TextBook'];
	//Lay SO VB
	if($IdDoctype != 0){
				$arrGetSingleList 	= 	$objList->getSingleList($IdDoctype);
				$psXmlStr 			= 	$arrGetSingleList['C_XML_DATA'];
	}else{
		$psXmlStr = "";
		$arrGetSingleList = array();	
	}
	$sDoctype = $arrGetSingleList['C_NAME'];
	$iNumber = $objList->getMaxNumber($sDoctype,$iUnitId,$sTextBook);
	$iNumber++;
	$sSymbol = trim(Sys_Publib_Xml::_xmlGetXmlTagValue($psXmlStr,"data_list","ky_hieu_di") ) ;
	$html =	"<label >&nbsp;".$arrConst['_SO']." <span class=\"requiein\">*</span></label>
			<input class=\"textbox\" style=\"width:20%;\" isnumeric = \"false\"  onchange=\"submitBool(document.getElementById('C_DOC_TYPE'),this);\" type=\"text\" id=\"C_NUMBER\" name=\"C_NUMBER\" value=\"$iNumber\" align=\"right\" optional = \"true\"  xml_data=\"false\" column_name=\"C_NUMBER\">
			<label style=\"float:none;clear:none; display:inline;\">&nbsp;".$arrConst['_KY_HIEU']."<span class=\"requiein\">*</span></label>
			<input style=\"width:21%;\" class=\"textbox\"   type\"text\" id=\"C_SYMBOL\" name=\"C_SYMBOL\"  align=\"right\" optional = \"true\" xml_data=\"false\" column_name=\"C_SYMBOL\"  value=\"$sSymbol\"> ";
	echo $html;
?>
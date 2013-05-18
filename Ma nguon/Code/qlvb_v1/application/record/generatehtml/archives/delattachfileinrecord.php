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
	Zend_Loader::loadClass('Sys_Function_DocFunctions');	
	$objFunction =	new	Sys_Function_DocFunctions()	;	
	//Zend_Loader::loadClass('Sys_HeaderTable');	
	Zend_Loader::loadClass('Sys_DB_Connection');
	//Lay cac hang so su dung trong JS public
		Zend_Loader::loadClass('Sys_Init_Config');
		$objConfig = new Sys_Init_Config();
	//Ket noi CSDL SQL theo kieu ADODB
	$arrConst = Sys_Init_Config::_setProjectPublicConst();
	$connectSQL = new Zend_Config_Ini('../../../../config/config.ini','dbmssql');
		// Load tat ca cac file Js va Css
	Sys_Publib_Library::_getAllFileJavaScriptCss('','sys-js','Record.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','sys-js','ajax.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','sys-js','jquery-1.4.2.min.js,jQuery.equalHeights.js',',','js'). Sys_Publib_Library::_getAllFileJavaScriptCss('','sys-js/LibSearch','actb_search.js,common_search.js',',','js');
	$registry = Zend_Registry::getInstance();
	$registry->set('connectSQL', $connectSQL);
	$connAdo = Sys_Db_Connection::connectADO($connectSQL->db->adapter,$connectSQL->db->config->toArray());
	$delimitor = '!#~$|*';
	//Load class Sent_documentSent
	Zend_Loader::loadClass('record_modRecord');
	$objRecordArchive = new record_modRecord();
	$sRecordArchiveIdList   = $_REQUEST['RecordArchiveIdList'];
	$arrDocIdList 			= $objRecordArchive->fgenerateDocIdList($sRecordArchiveIdList);
	$sDocIdList 			= $arrDocIdList[0]['C_DOC_ID_LIST'];
	$arrFileNameList 		= $objRecordArchive->DOC_GetAllDocumentFileAttachName($sDocIdList);
	$sFileNameList 			= $arrFileNameList[0]['C_FILE_NAME_LIST'].'!#~$|*';
	$html = '<input type = "hidden" id = "hdn_file_name_list" value="'.$sFileNameList.'" />';
	echo $html;
?>
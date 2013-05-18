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
	Sys_Publib_Library::_getAllFileJavaScriptCss('','sys-js','sendreceived.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','sys-js','ajax.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','sys-js','jquery-1.4.2.min.js,jQuery.equalHeights.js',',','js'). Sys_Publib_Library::_getAllFileJavaScriptCss('','sys-js/LibSearch','actb_search.js,common_search.js',',','js');
	$registry = Zend_Registry::getInstance();
	$registry->set('connectSQL', $connectSQL);
	$connAdo = Sys_Db_Connection::connectADO($connectSQL->db->adapter,$connectSQL->db->config->toArray());
	$delimitor = '!#~$|*';
	//Load class Sent_documentSent
	Zend_Loader::loadClass('sendReceived_modSendReceived');
	$objSend = new sendReceived_modSendReceived();
	//Lay SO VB
	$sfullTextSearch = $_REQUEST['fullTextSearch'];
	$iCurentPage     = $_REQUEST['curentPage'];
	$iRowOnPage      = $_REQUEST['rowOnPage'];
	$sListID     	 = $_REQUEST['listID'];
	if(strlen($sListID) > 36)
		$sListID 		 = substr($sListID,0,-1);
	$sOwnerId     	 = $_REQUEST['OwnerId'];
	//Lay ID DON VI
	$iUnitId = $_REQUEST['iUnitId']; 
	//Lay tu dong so den VB 
	$arrresult = $objSend ->DocSendGetAll($sOwnerId,$sfullTextSearch,$iCurentPage,$iRowOnPage,$sListID);
	//var_dump($arrresult);exit;
	$html = '<div style="height:200px;overflow:auto;border-bottom:1px solid black;margin-left:1%;width:98%;">';
	$html .= '<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center" class="list_table2" id="table1">';
	$html .= "<col width='4%'><col width='15%'><col width='11%'><col width='40%'><col width='30%'>";
				$v_current_style_name = "round_row";
				for($index = 0;$index < sizeof($arrresult);$index++){
					//Lay file dinh kem
					$strFileName 				= $arrresult[$index]['C_FILE_NAME'];
					$sFile 						= Sys_Library::_getAllFileAttach($strFileName,"!#~$|*","!~!",'../../../../../sys-doc-v3.0/public/attach-file/');
					$documentId 				= $arrresult[$index]['PK_SENT_DOCUMENT'];		
					$ssendDate					= $objFunction->searchCharColor($sfullTextSearch,$arrresult[$index]['C_SENT_DATE']) .'&nbsp;';		
					$snumber					= $objFunction->searchCharColor($sfullTextSearch,$arrresult[$index]['C_NUMBER'] ).'&nbsp;';						
					$ssymbol					= $objFunction->searchCharColor($sfullTextSearch,$arrresult[$index]['C_SYMBOL']).'&nbsp;';					

					if($strFileName == '' || $strFileName == null){
						$ssubject				= $objFunction->searchStringColor($sfullTextSearch,$arrresult[$index]['C_SUBJECT']) .'&nbsp;';	
					}else{
						$ssubject				= $objFunction->searchStringColor($sfullTextSearch,$arrresult[$index]['C_SUBJECT']) .'<br>'. $sFile;
						$sFile 					= "";
					}
					$sreceivedPlace				= $objFunction->searchStringColor($sfullTextSearch,$arrresult[$index]['C_RECEIVE_PLACE']).'&nbsp;';	
					$iOrder					= $arrresult[$index]['C_ORDER'];		
					if ($v_current_style_name == "odd_row"){
						$v_current_style_name = "round_row";
					}else{
						$v_current_style_name = "odd_row";					
					}
					if($iOrder ==1){
						$html = $html.'<tr class="'.$v_current_style_name.' selected">';
						$html .='<td align="center" style="padding-left:3px;padding-right:3px;" class="normal_label">';
						$html .='<input type="checkbox" id="chk_item_id" name="chk_item_id" onclick ="selectrow(this)" checked value ="'.$arrresult[$index]['PK_SENT_DOCUMENT'].'" /></td>';
					}else{
						$html = $html.'<tr class="'.$v_current_style_name.'">';
						$html .='<td align="center" style="padding-left:3px;padding-right:3px;" class="normal_label">';
					$html .='<input type="checkbox" id="chk_item_id" name="chk_item_id" onclick ="selectrow(this)" value ="'.$arrresult[$index]['PK_SENT_DOCUMENT'].'" /></td>';
					}
					$html .='<td onclick="set_hidden_dialog(this,document.getElementsByName(\'chk_item_id\'),document.getElementById(\'hdn_object_id\'),\''.$documentId.'\',document.getElementById(\'hdn_doc_id\'));" align="center"  style="padding-left:3px;padding-right:3px;" class="normal_label" >'.$ssendDate.'</td>';													
					$html .='<td onclick="set_hidden_dialog(this,document.getElementsByName(\'chk_item_id\'),document.getElementById(\'hdn_object_id\'),\''.$documentId.'\',document.getElementById(\'hdn_doc_id\'));" align="center"  style="padding-left:3px;padding-right:3px;" class="normal_label">'.$snumber.$ssymbol.'&nbsp;</td>';
					$html .='<td onclick="set_hidden_dialog(this,document.getElementsByName(\'chk_item_id\'),document.getElementById(\'hdn_object_id\'),\''.$documentId.'\',document.getElementById(\'hdn_doc_id\'));" align="center" valign="middle"  style="padding-left:3px;padding-right:3px;" class="normal_label">'.$ssubject.'</td>';
					$html .='<td onclick="set_hidden_dialog(this,document.getElementsByName(\'chk_item_id\'),document.getElementById(\'hdn_object_id\'),\''.$documentId.'\',document.getElementById(\'hdn_doc_id\'));" align="left" style="padding-left:3px;padding-right:3px;" class="normal_label">'.$sreceivedPlace.'</td>';
					$html .='</tr>';	
				}		
				//Tu dien cac dong trang trong truong hop du lieu tra ve < so row _CONST_NUMBER_OF_ROW_PER_LIST		
				if(sizeof($arrresult) < 15){	
					$html .= Sys_Library::_addEmptyRow(count($arrresult),15 - sizeof($arrresult),$v_current_style_name,5);		
				}				
		$html .='</table></div>';	
		//Xau html hien thi phan trang
		$iTotalRecord = $arrresult[0]['C_TOTAL_RECORD'];
		$htmlpaging  ='<div style="margin-left:1%;width:98%;">';
		$htmlpaging .='<table width="100%"><col width="30%" align="left"></col><col width="10%"></col><col width="30%" align="right"></col><tr><td class="normal_label"><small class="small_starmark">';
		if(count($arrresult) == 0)
				$htmlpaging .='Danh sách này không có văn bản nào';
		else	$htmlpaging .='Danh sách có '.count($arrresult).'/'.$iTotalRecord. ' văn bản</small></td>';
		$htmlpaging .='<td><table><tr>';
		$iNumberPage  = ceil($iTotalRecord/15);
		if($iNumberPage >= 10){
			if($iCurentPage >= 10){
				$iEndpage = $iCurentPage + 5; 
				$iStartPage = $iCurentPage - 5;
			}
			else {
				$iStartPage = 1;
				$iEndpage = 10;
			}
		}else {
			$iStartPage = 1;
			$iEndpage = $iNumberPage;
		}
		if($iCurentPage != 1){
			$htmlpaging .='<td><a onclick ="gotopage('.($iCurentPage - 1 ).')">Trước</a></td>';
		}
		for($i = $iStartPage; $i < $iEndpage; $i++){
			if($i == $iCurentPage)
				$htmlpaging .='<td><a style="color:red;" onclick ="gotopage('.$i.')">'.$i.'</a></td>';
			else $htmlpaging .='<td><a onclick ="gotopage('.$i.')">'.$i.'</a></td>';
		}
		if($iEndpage < $iNumberPage){
			$htmlpaging .='<td><a onclick ="gotopage('.($iCurentPage + 1 ).')">Tiếp</a></td>';
		}
		$htmlpaging .='</tr></table></td>';
		$htmlpaging .='<td class="normal_label" align="right">Hiển thị <select id="cboRowOnPage" onchange="shownumberpage()">';
		if($iRowOnPage == 15){
			$htmlpaging .=' <option value="15" selected>15</option>
							<option value="50">50</option>
							<option value="100>100</option>';
		}
		if($iRowOnPage == 50){
			$htmlpaging .=' <option value="15">15</option>
							<option value="50" selected>50</option>
							<option value="100>100</option>';
		}
		if($iRowOnPage == 100){
			$htmlpaging .='<option value="15">15</option>
							<option value="50">50</option>
							<option value="100 selected>100</option>';
		}
		$htmlpaging .='</select> Văn bản/Trang
					   </td>';
		$htmlpaging .='</tr></table></div>';
		$html .=$htmlpaging;
		//echo $htmlpaging; exit;
	echo $html;
?>
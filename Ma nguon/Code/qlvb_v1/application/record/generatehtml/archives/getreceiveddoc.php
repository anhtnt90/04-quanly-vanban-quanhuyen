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
	Zend_Loader::loadClass('record_modRecord');
	$objRecordArchive = new record_modRecord();
	//Lay SO VB
	$sDocType		 = $_REQUEST['doctype'];
	$sDocCate		 = $_REQUEST['doccate'];
	$iYear			 = $_REQUEST['year'];
	$sDocCate		 = $_REQUEST['doccate'];
	$sOwnerId     	 = $_REQUEST['OwnerId'];
	$sfullTextSearch = $_REQUEST['fullTextSearch'];
	$iCurentPage     = $_REQUEST['curentPage'];
	$iRowOnPage      = $_REQUEST['rowOnPage'];
	//Lay tu dong so den VB 
	$arrresult = $objRecordArchive ->DocRecordArchiveReceivedDocGetAll($sDocType,$sDocCate,$iYear,$sOwnerId,$sfullTextSearch,$iCurentPage,$iRowOnPage);
	//exit;
	$html = '<div style="height:200px;overflow:auto;border-bottom:1px solid black;margin-left:1%;width:98%;">';
	$html .= '<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center" class="list_table2" id="table1">';
	$html .= "<col width='4%'><col width='10%'><col width='10%'><col width='15%'><col width='30%'><col width='30%'>";
				$v_current_style_name = "round_row";
				for($index = 0;$index < sizeof($arrresult);$index++){
					//Lay file dinh kem
					$strFileName 				= $arrresult[$index]['C_FILE_NAME'];
					$sFile 						= Sys_Library::_getAllFileAttach($strFileName,"!#~$|*","!~!",'../../../../../'.Sys_Init_Config::_setWebSitePath().'/public/attach-file/');
					//ID VB
					$documentId 				= $arrresult[$index]['PK_RECEIVED_DOC'];		
					//echo 'aa'.$documentId; exit;	
					// so den
					$snum					= $arrresult[$index]['C_NUM'] .'&nbsp;';		
					//ngay den
					$sreceivedDate			= $objFunction->searchCharColor($sfullTextSearch,$arrresult[$index]['C_RECEIVED_DATE']) .'&nbsp;';							
					// so ky hieu
					$ssymbol				= $objFunction->searchCharColor($sfullTextSearch,$arrresult[$index]['C_SYMBOL']) .'&nbsp;';					
					//Trich yeu 
					if($strFileName == '' || $strFileName == null){
						$ssubject					= $objFunction->searchStringColor($sfullTextSearch,$arrresult[$index]['C_SUBJECT']) .'&nbsp;';	
					}else{
						$ssubject					= $objFunction->searchStringColor($sfullTextSearch,$arrresult[$index]['C_SUBJECT']) .'<br>'. $sFile;
						$sFile 					= "";
					}
					// Don vi xu ly
					$sprocesunit			= $objFunction->searchCharColor($sfullTextSearch,$arrresult[$index]['C_PROCESS_UNIT_NAME_LIST']) .'&nbsp;';	
					// c_oder: van ban moi o tren cung
					$iOrder					= $arrresult[$index]['C_ORDER'];				
					//$fileName 			 		= $arrResul[$index]['C_FILE_NAME'] .'&nbsp;';			
					if ($v_current_style_name == "odd_row"){
						$v_current_style_name = "round_row";
					}else{
						$v_current_style_name = "odd_row";					
					}
					$html = $html.'<tr class="'.$v_current_style_name.'">';
					$html .='<td align="center" style="padding-left:3px;padding-right:3px;" class="normal_label">';
					$html .='<input type="checkbox" id="chk_item_id" name="chk_item_id" onclick ="selectrow_docrelate(this)" value ="'.$arrresult[$index]['PK_RECEIVED_DOC'].'" /></td>';
					$html .='<td onclick="set_hidden_docrelate(this,document.getElementsByName(\'chk_item_id\'),\''.$documentId.'\');" align="center"  style="padding-left:3px;padding-right:3px;" class="normal_label" >'.$snum.'</td>';													
					$html .='<td onclick="set_hidden_docrelate(this,document.getElementsByName(\'chk_item_id\'),\''.$documentId.'\');" align="center"  style="padding-left:3px;padding-right:3px;" class="normal_label">'.$sreceivedDate.'&nbsp;</td>';
					$html .='<td onclick="set_hidden_docrelate(this,document.getElementsByName(\'chk_item_id\'),\''.$documentId.'\');" align="center" valign="middle"  style="padding-left:3px;padding-right:3px;" class="normal_label">'.$ssymbol.'</td>';
					$html .='<td onclick="set_hidden_docrelate(this,document.getElementsByName(\'chk_item_id\'),\''.$documentId.'\');" align="left" style="padding-left:3px;padding-right:3px;" class="normal_label">'.$ssubject.'</td>';
					$html .='<td onclick="set_hidden_docrelate(this,document.getElementsByName(\'chk_item_id\'),\''.$documentId.'\');" align="left" style="padding-left:3px;padding-right:3px;" class="normal_label">'.$sprocesunit.'</td>';
					$html .='</tr>';	
				}		
				//Tu dien cac dong trang trong truong hop du lieu tra ve < so row _CONST_NUMBER_OF_ROW_PER_LIST		
				if(sizeof($arrresult) < 15){	
					$html .= Sys_Library::_addEmptyRow(count($arrresult),15 - sizeof($arrresult),$v_current_style_name,6);		
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
		//Cac tham so:PHAN TRANG
			$iNumberPage  = ceil($iTotalRecord/$iRowOnPage); //Tong so trang
			$inumberpageinsheet = 10; 						 //so trang hien thi
			$iStartPage	  = 1;								 //Trang dau tien
			$iEndpage	  = 1;								 //Trang cuoi cung
		if($iNumberPage < $inumberpageinsheet){
			$iStartPage   = 1;
			$iEndpage	  = $iNumberPage;
		}else{
			if($iNumberPage > ($iCurentPage + $inumberpageinsheet/2))
				$iEndpage = $iCurentPage + $inumberpageinsheet/2;
			else{
				$iEndpage = $iNumberPage;
			}
			if(($iCurentPage - $inumberpageinsheet/2) > 0)
				$iStartPage = $iCurentPage - $inumberpageinsheet/2;
		}
		//echo 'so trang:'.$iStartPage.'<br>'.'so ban:'.$iEndpage.'<br>so trang tren ban:'.$inumberpageinsheet; exit;
		if($iCurentPage > 1){
			$htmlpaging .='<td><a onclick ="gotopage('.($iCurentPage - 1 ).')">Trước</a></td>';
		}
		if($iTotalRecord > $iRowOnPage)
			for($i = $iStartPage; $i <= $iEndpage; $i++){
				if($i == $iCurentPage)
					$htmlpaging .='<td><a style="color:red;" onclick ="gotopage('.$i.')">'.$i.'</a></td>';
				else $htmlpaging .='<td><a onclick ="gotopage('.$i.')">'.$i.'</a></td>';
			}
		if($iCurentPage < $iNumberPage){
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
	echo $html;
?>
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
	Zend_Loader::loadClass('Sys_Init_Config');	 
	Zend_Loader::loadClass('Sys_Function_DocFunctions');	
	Zend_Loader::loadClass('Zend_Session');
	Zend_Session::isStarted();
	//Load class Sent_documentSent
	Zend_Loader::loadClass('Sent_modSent');
	//Ket noi CSDL SQL theo kieu ADODB
	$connectSQL = new Zend_Config_Ini('../../../../config/config.ini','dbmssql');
	$registry = Zend_Registry::getInstance();
	$objSent = new Sent_modSent();
	$ojbSysInitConfig = new Sys_Init_Config();
	
	$registry->set('connectSQL', $connectSQL);
	$connAdo = Sys_Db_Connection::connectADO($connectSQL->db->adapter,$connectSQL->db->config->toArray());
	$arrConst =	$ojbSysInitConfig->_setProjectPublicConst();
	session_start() ; 
	ob_start() ;
	if($_SESSION['list_id_received'] != '' and $_REQUEST['listId'] !=''){
		$_SESSION['list_id_received'] = $_SESSION['list_id_received'].','.$_REQUEST['listId'];
	}
	if($_SESSION['list_id_received'] == '' ){
		$_SESSION['list_id_received'] = $_REQUEST['listId'];	
	}
	if($_SESSION['list_id_sent'] != '' and $_REQUEST['listSentId'] !=''){
		$_SESSION['list_id_sent'] = $_SESSION['list_id_sent'].','.$_REQUEST['listSentId'];
	}
	if($_SESSION['list_id_sent'] == ''){
		$_SESSION['list_id_sent'] = $_REQUEST['listSentId'];	
	}
	$baseUrl = $_REQUEST['BaseUrl'];	
	$SentID		= $_REQUEST['SentID'];
	//echo $SentID;
	$arrRelate=$objSent->docRelateGetAll($SentID,$_SESSION['list_id_received'],$_SESSION['list_id_sent']);
	//echo 'Request: '.$_REQUEST['listId'].'<br>'.'Den: '.$_SESSION['list_id_received'].'<br>'.'Di: '.$_SESSION['list_id_sent'];
?>

	<table cellpadding="0" cellspacing="0" border="0" width="78%" align="center" style="margin-left:16%;" class="list_table2" id="table1">
		<?php			
		$delimitor = '!~~!';//Lay ky tu phan cach giua cac phan tu
		//Hien thi cac cot cua bang hien thi du lieu
			$StrHeader = explode("!~~!",Sys_Library::_GenerateHeaderTable("3%" . $delimitor . "16%" . $delimitor . "14%" . $delimitor . "45%" . $delimitor . "26%"
											,'<input type="checkbox" name="chk_all_item_id" value="xxx" onclick="checkbox_all_item_id(document.forms[0].chk_item_id);" option="true" optional="true">' . $delimitor .$arrConst['_NGAY_SOAN_THAO']  . $delimitor . $arrConst['_SO_KY_HIEU'] . $delimitor . $arrConst['_TRICH_YEU'] . $delimitor . $arrConst['_DON_VI_PHAT_HANH'] 
											,$delimitor));
		echo $StrHeader[0];				
		echo $StrHeader[1]; //Hien thi <col width = 'xx'><...		
		//Dinh nghia URL
		$sUrView  = "../../view/";
		$sCurrentStyleName = "round_row";
		for($index = 0;$index < sizeof($arrRelate);$index++){	
			echo $arrRelate[$index]['C_DOC_TYPE'];
			//lay file dinh kem
			$strFileName 				= $arrRelate[$index]['C_FILE_NAME'];
			$sFile = Sys_Library::_getAllFileAttach($strFileName,"!#~$|*","!~!",$baseUrl . "attach-file/");
			$documentId 				= $arrRelate[$index]['PK_DOCUMENT'];				
			$sDate						= $arrRelate[$index]['C_DATE'] .'&nbsp;';	
			$sNumber					= $arrRelate[$index]['C_NUMBER'] ;
			$sSymbol					= $arrRelate[$index]['C_SYMBOL'].'&nbsp;';	
			//$sSubject					= $arrRelate[$index]['C_SUBJECT'].'&nbsp;';		
			$sUnitName					= $arrRelate[$index]['C_UNIT_NAME'] ;	
			if	($sUnitName ==''){
				$sUnitName	= Sys_Function_DocFunctions::getNameUnitByIdUnitList($_SESSION['OWNER_ID']);
				
			}
			//Tai lieu kem theo ho so
			if($strFileName == '' || $strFileName == null){
				$sSubject				= $arrRelate[$index]['C_SUBJECT'] .'&nbsp;';
			}else {
				$sSubject				= $arrRelate[$index]['C_SUBJECT']. '<br>'.$sFile;
				$sFile = "";
			}
			if ($sCurrentStyleName == "odd_row"){
				$sCurrentStyleName = "round_row";
			}else{
				$sCurrentStyleName = "odd_row";					
			}?>					
			<tr class="<?=$sCurrentStyleName?>" id="<?=$documentId?>" name="<?= $documentId ?>" >		
				<td align="center" style="padding-left:3px;padding-right:3px;" class="normal_label">					
					 <input type="checkbox" name="chk_item_id"  id="chk_item_id"  value="<?= $documentId ?>"  optional="true" >
				</td>
				<td align="center" ondblclick="item_onclick('<?=$documentId?>','<?=$sUrView?>')" onclick="set_hidden(this,document.getElementsByName('chk_item_id'),document.getElementById('hdn_object_id'),'<?=$documentId?>');" style="padding-left:3px;padding-right:3px;" class="normal_label">
					<?=$sDate?>
				</td>								
				<!--Hien thi trich yeu!-->					
				<td ondblclick="item_onclick('<?=$documentId?>','<?=$sUrView?>')" onclick="set_hidden(this,document.getElementsByName('chk_item_id'),document.getElementById('hdn_object_id'),'<?=$documentId?>');"  style="padding-left:3px;padding-right:3px;" class="normal_label"><?=$sNumber.$sSymbol ?></td>
				<!--Hien ket qua xu ly!-->	
				<td align="left" ondblclick="item_onclick('<?=$documentId?>','<?=$sUrView?>')" onclick="set_hidden(this,document.getElementsByName('chk_item_id'),document.getElementById('hdn_object_id'),'<?=$documentId?>');"  style="padding-left:3px;padding-right:3px;" class="normal_label"><?=  $sSubject?></td>
				<td align="center" ondblclick="item_onclick('<?=$documentId?>','<?=$sUrView?>')" onclick="set_hidden(this,document.getElementsByName('chk_item_id'),document.getElementById('hdn_object_id'),'<?=$documentId?>');"  style="padding-left:3px;padding-right:3px;" class="normal_label"><?=  $sUnitName?></td>	
			</tr><?php
		}		
		?>
	</table>	
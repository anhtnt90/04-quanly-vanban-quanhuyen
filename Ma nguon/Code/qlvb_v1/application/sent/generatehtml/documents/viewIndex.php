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
?>
	<table cellpadding="0" cellspacing="0" border="0" width="98%" align="center" class="list_table2">
			<?php			
			$delimitor = $this->delimitor;//Lay ky tu phan cach giua cac phan tu
			//Hien thi cac cot cua bang hien thi du lieu
			$StrHeader = explode("!~~!",$this->GenerateHeaderTable("5%" . $delimitor . "12%" . $delimitor . "18%" . $delimitor . "40%" . $delimitor . "25%"
											,"#" . $delimitor . "Ngày văn bản" . $delimitor . "Số/ký hiệu văn bản" . $delimitor . "Trích yếu" . $delimitor . "Đơn vị soạn thảo"
											,$delimitor));
			echo $StrHeader[0];				
			
			echo $StrHeader[1]; //Hien thi <col width = 'xx'><...		
				//Dinh nghia URL
				$sUrlEdit  = "../edit";
				$v_current_style_name = "round_row";
				$arrResul = $this->arrResul;
				for($index = 0;$index < sizeof($arrResul);$index++){	
					// Pk cua bang
					$documentId 				= $arrResul[$index]['PK_SENT_DOCUMENT'] . '&nbsp;';				
					// Ten ho so
					$date						= $arrResul[$index]['C_SENT_DATE'] .'&nbsp;';								
					// Ten ho so
					$numSysbol					= $arrResul[$index]['C_SYMBOL'] .'&nbsp;';				
					//Tai lieu kem theo ho so
					$subject					= $arrResul[$index]['C_SUBJECT'] .'&nbsp;';
					$unitId 					= $arrResul[$index]['C_UNIT_NAME'] .'&nbsp;';				
					
					if ($v_current_style_name == "odd_row"){
						$v_current_style_name = "round_row";
					}else{
						$v_current_style_name = "odd_row";					
					}?>
					
					<tr class="<?=$v_current_style_name?>">	
						<td align="center" style="padding-left:3px;padding-right:3px;" class="normal_label">						
							<?=$this->formCheckbox('chk_item_id',$documentId, array('checked' => false));?>					
						</td>
						<td align="center" onclick="item_onclick('<?=$documentId?>','<?=$sUrlEdit?>');" style="padding-left:3px;padding-right:3px;" class="normal_label">
							<?=$date?>
						</td>								
						<!--Hien thi trich yeu!-->					
						<td onclick="item_onclick('<?=$documentId?>','<?=$sUrlEdit?>')"  style="padding-left:3px;padding-right:3px;" class="normal_label"><?=$numSysbol;?></td>
						<!--Hien ket qua xu ly!-->	
						<td align="left" onclick="item_onclick('<?=$documentId?>','<?=$sUrlEdit?>')"  style="padding-left:3px;padding-right:3px;" class="normal_label"><?=$subject?></td>	
						<td align="left" onclick="item_onclick('<?=$documentId?>','<?=$sUrlEdit?>')"  style="padding-left:3px;padding-right:3px;" class="normal_label"><?=$unitId?></td>	
					</tr><?php
				}					
				//Tu dien cac dong trang trong truong hop du lieu tra ve < so row _CONST_NUMBER_OF_ROW_PER_LIST			
				echo $this->addEmptyRow($this->iCountElement,$this->NumberRowOnPage,$v_current_style_name,5);			
			?>			
		</table>	
		<!--Hien thi trang can xem!-->			
		<table width="100%" cellpadding="0" cellspacing="0" border="0">				
				<tr>
					<td style="padding-left:10px;padding-right:8px;">
						<?=$this->SelectDeselectAll;?>
					</td>
				</tr>			
				</tr>
				<tr>
					<td align="right" style="font-size:13px; padding-right:8px; font:tahoma" class="normal_label"><?php		
							//Hien thi so trang	
							echo $this->generateHtmlSelectBoxPage;?>
					</td>
				</tr>		
		</table>
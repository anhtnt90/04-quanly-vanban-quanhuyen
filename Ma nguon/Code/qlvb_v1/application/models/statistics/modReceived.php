<?php
class statistics_modReceived extends Sys_DB_Connection {
		public function RopReceiveDocumentGetAll($pStaffId,$pDepartmentId,$pFromDate,$pToDate, $pReceiveStatus,$pDetailStatus,$sOwnerCode, $pPage, $pNumberRecordPerPage){		
		$sql = "Exec Doc_RopReceiveDocumentGetAll ";
		$sql = $sql . "'" . $pStaffId . "'";
		$sql = $sql . ",'" . $pDepartmentId . "'";
		$sql = $sql . ",'" . $pFromDate . "'";
		$sql = $sql . ",'" . $pToDate . "'";
		$sql = $sql . ",'" . $pReceiveStatus . "'";
		$sql = $sql . ",'" . $pDetailStatus. "'";			
		$sql = $sql . ",'" . $sOwnerCode . "'";
		$sql = $sql . ",'" . $pPage . "'";
		$sql = $sql . ",'" . $pNumberRecordPerPage . "'";
		//echo $sql . '<br>'; //exit;
		try{
			$arrResul = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		
		if (count($arrResul) > 0){		
			//Tao doi tuong trong thu vien dung chung
			$ojblib = new Sys_Library();			
			for ($index = 0; $index < count($arrResul);$index++){
				$unitId = $arrResul[$index]['FK_PROCESS_UNIT'];				
				//Lay ten DON VI xu ly
				$unitName = "";
				if (trim($unitId) > 0){//TOn tai don vi xu ly chinh
				 	$unitName .= "-" . Sys_Library::_getItemAttrById($_SESSION['arr_all_unit'],$unitId,'name');													
				} 	
				
				//Don vi phoi hop
				$CombineunitIdList = $arrResul[$index]['FK_UNIT_ID_LIST'];					
				if ($CombineunitIdList != ""){
					$arrCombineUnit = explode(",",$CombineunitIdList);
					for ($j = 0; $j<sizeof($arrCombineUnit); $j++){
						//Lay ten DON VI xu ly
				 		$unitName .= "<br> - " . Sys_Library::_getItemAttrById($_SESSION['arr_all_unit'],$arrCombineUnit[$j],'name');	
					}
				}	
				
				//-------------------Can bo xu ly------------------------
				$sStaffIdList = "";
				if ($arrResul[$index]['FK_PROCESSOR'] > 0){
					$sStaffIdList .= $arrResul[$index]['FK_PROCESSOR'];				
				}
				//Can bo phoi hop
				if ($arrResul[$index]['FK_COMBINE_ID_LIST'] != ""){
					$sStaffIdList .= $arrResul[$index]['FK_COMBINE_ID_LIST'] . ",";				
				}				
				if ($sStaffIdList != ""){					
					$arrStaff = explode(",",$sStaffIdList);
					$sStaffNameList = "";
					for ($k = 0;$k<sizeof($arrStaff); $k++){
						$sStaffNameList .= Sys_Library::_getItemAttrById($_SESSION['arr_all_staff'],$arrStaff[$k],'name');
					}
				}
				//-------------------------------------------------------
				//Gan nguoc gia tri lai
				if ($sStaffNameList != ""){
					$unitName .= $sStaffNameList;
				}			
				$arrResul[$index]['C_PROCESS_UNIT_NAME'] = $unitName;				
			}
		}	
			
		return $arrResul;
	}
	public function getPropertiesDocument($code){
		$sql = "SysLib_ListGetAllbyCode ";
		$sql = $sql . "'" . $code . "', ''";
		try {
			$arrSel = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrSel;
	}
	public function getProcessResultGetAll($pDocumentId){		
		$sql = "[RopJobProfileProcessGetSingle] ";
		$sql = $sql . "'" . $pDocumentId . "'";
		//echo $sql . '<br>';
		try{
			$arrResult = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}		
		return $arrResult;
	}
}
	
?>
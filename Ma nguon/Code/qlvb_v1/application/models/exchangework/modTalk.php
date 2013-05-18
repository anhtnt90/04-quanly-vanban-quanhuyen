<?php
class exchangework_modTalk extends Sys_DB_Connection {
	public function TaskWorkGetAll($sWorkType,$iFkStaff,$StaffRole,$iFkUnit,$sUnitType,$istatus,$sFromDate,$sToDate,$sFullTextSearch,$icheck_status,$ifile_status,$iPage,$iNumberRecordPerPage){		
		$sql = "Exec Task_WorkGetAll ";
		$sql = $sql . "'" . $sWorkType . "'";
		$sql = $sql . ",'" . $iFkStaff . "'";
		$sql = $sql . ",'" . $StaffRole . "'";
		$sql = $sql . ",'" . $iFkUnit . "'";
		$sql = $sql . ",'" . $sUnitType . "'";
		$sql = $sql . ",'" . $istatus . "'";
		$sql = $sql . ",'" . $sFromDate . "'";
		$sql = $sql . ",'" . $sToDate . "'";
		$sql = $sql . ",'" . $sFullTextSearch . "'";
		$sql = $sql . ",'" . $icheck_status . "'";		
		$sql = $sql . ",'" . $ifile_status . "'";		
		$sql = $sql . ",'" . $iPage . "'";
		$sql = $sql . ",'" . $iNumberRecordPerPage . "'";	
		//echo $sql . '<br>'; //exit;
		try{
			$arrResul = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}		
		return $arrResul;
	}
public function getPropertiesDocument($code,$sXml,$sValueNotGet){
		$sql = "SysLib_ListGetAllbyCode ";
		$sql = $sql . "'" . $code . "'";
		$sql = $sql . ",'" . $sXml . "'";
		$sql = $sql . ",'" . $sValueNotGet . "'";
		//echo '$sql'.$sql;
		try {
			$arrSel = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrSel;
	}

public function TaskWorkUpdate($arrParameter){
		$psSql = "Exec Task_WorkUpdate ";	
		$psSql .= "'" . $arrParameter['PK_TASK_WORK'] . "'";		
		$psSql .= ",'" . $arrParameter['FK_TASK_WORK'] . "'";
		$psSql .= ",'" . $arrParameter['FK_CREATE_STAFF_ID'] . "'";
		$psSql .= ",'" . $arrParameter['FK_CREATE_STAFF_NAME'] . "'";
		$psSql .= ",'" . $arrParameter['C_NATURE'] . "'";
		$psSql .= ",'" . $arrParameter['C_TITLE'] . "'";
		$psSql .= ",'" . $arrParameter['C_CONTENT'] . "'";
		$psSql .= ",'" . $arrParameter['FK_PROCESS_STAFF_ID'] . "'";
		$psSql .= ",'" . $arrParameter['FK_PROCESS_STAFF_ID_LIST'] . "'";
		$psSql .= ",'" . $arrParameter['FK_PROCESS_UNIT_ID_LIST'] . "'";
		$psSql .= ",'" . $arrParameter['FK_PROCESS_STAFF_NAME_LIST'] . "'";
		$psSql .= ",'" . $arrParameter['C_APPOINTED_DATE'] . "'";
		$psSql .= ",'" . $arrParameter['ATTACH_FILE_NAME_LIST'] . "'";
		$psSql .= ",'" . $arrParameter['C_FULL_UNIT_STATUS'] . "'";
		$psSql .= ",'" . $arrParameter['C_FULL_OWNER_STATUS'] . "'";
		$psSql .= ",'" . $arrParameter['C_FULL_LENDER_UNIT_STATUS'] . "'";
		$psSql .= ",'" . $arrParameter['C_FULL_LENDER_OWNER_STATUS'] . "'";
		try {			
			$arrTempResult = $this->adodbExecSqlString($psSql) ; 
			$Result = $arrTempResult['NEW_ID'];
		}catch (Exception $e){
			echo $e->getMessage();
		};
		//Return result
		return $Result;		
	}
public function TaskWorkSingle($sTaskWorkId){
		$sql = "Exec Task_WorkGetSingle ";
		$sql .= "'" . $sTaskWorkId . "'";
		//echo $sql . '<br>'; //exit;
		try{
			$arrReceived = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};		
		return $arrReceived;
	}
public function DOC_GetAllDocumentFileAttach($pReceiveDocumentId, $pFileTyle, $pTableObject){
		$sql = "Exec Doc_GetAllDocumentFileAttach '" . $pReceiveDocumentId . "'";
		$sql .= ",'".$pFileTyle ."'";		
		$sql .= ",'".$pTableObject ."'"; 
		//echo $sql . '<br>';
		try {						
			$arrResult = $this->adodbQueryDataInNameMode($sql);					
		}catch (Exception $e){
			echo $e->getMessage();
		};				
		return $arrResult;			
	}
public function TaskWorkDelete($sTaskWorkIdList){
		$Result = null;			
		$sql = "Exec Task_WorkDelete ";		
		$sql .= "'".$sTaskWorkIdList ."'";	
		//echo $sql . '<br>'; exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($sql) ; 			
			$Result= $arrTempResult['RET_ERROR'];
		}catch (Exception $e){
			echo $e->getMessage();
		};				
		return $Result;	
	}
	/*
	 * 
	 */
public function TaskWorkFeedBackUpdate($arrParameter){
		$psSql = "Exec Task_WorkFeedBackUpdate ";	
		$psSql .= "'" . $arrParameter['PK_TASK_FEEDBACK'] . "'";		
		$psSql .= ",'" . $arrParameter['FK_TASK_WORK'] . "'";
		$psSql .= ",'" . $arrParameter['FK_FEEDBACK_STAFF'] . "'";
		$psSql .= ",'" . $arrParameter['C_FEEDBACK_TYPE'] . "'";
		$psSql .= ",'" . $arrParameter['C_STATUS'] . "'";
		$psSql .= ",'" . $arrParameter['C_CONTENT'] . "'";
		$psSql .= ",'" . $arrParameter['ATTACH_FILE_NAME_LIST'] . "'";
		//Thuc thi lenh SQL	
		//echo htmlspecialchars($psSql); exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($psSql) ; 
			$Result = $arrTempResult['NEW_ID'];
		}catch (Exception $e){
			echo $e->getMessage();
		};
		//Return result
		return $Result;		
	}
public function TaskWorkFeedBackGetAll($sTaskWorkId){
		$sql = "Exec Task_WorkFeedBackGetAll ";
		$sql .= "'" . $sTaskWorkId . "'";
		//echo $sql . '<br>'; //exit;
		try{
			$arrReceived = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};		
		return $arrReceived;
	}
public function TaskWorkFeedBackDelete($sTaskWorkIdList){
		$Result = null;			
		$sql = "Exec Task_WorkFeedBackDelete ";		
		$sql .= "'".$sTaskWorkIdList ."'";	
		//echo $sql . '<br>'; exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($sql) ; 			
			$Result= $arrTempResult['RET_ERROR'];
		}catch (Exception $e){
			echo $e->getMessage();
		};				
		return $Result;	
	}
	/**
	 * 
	 */
	public function TaskWorkCheck($sTaskWorkId){
		$psSql = "Exec Task_WorkCheck ";	
		$psSql .= "'" . $sTaskWorkId . "'";		
		//Thuc thi lenh SQL	
		//echo htmlspecialchars($psSql); exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($psSql) ; 
			$Result = $arrTempResult['NEW_ID'];
		}catch (Exception $e){
			echo $e->getMessage();
		};
		//Return result
		return $Result;		
	}
	/**
	 * 
	 */
	public function TaskWorkRecCheck($sStaffId,$sTaskWorkId){
		$psSql = "Exec Task_WorkRecCheck ";	
		$psSql .= "'" . $sStaffId . "'";		
		$psSql .= ",'" . $sTaskWorkId . "'";	
		//Thuc thi lenh SQL	
		//echo htmlspecialchars($psSql); exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($psSql) ; 
			$Result = $arrTempResult['NEW_ID'];
		}catch (Exception $e){
			echo $e->getMessage();
		};
		//Return result
		return $Result;		
	}
	/**
	 * 
	 */
	public function TaskWorkStatusUpdate($sStaffId,$sTaskWorkId){
		$psSql = "Exec Task_WorkStatusUpdate ";	
		$psSql .= "'" . $sStaffId . "'";		
		$psSql .= ",'" . $sTaskWorkId . "'";	
		//Thuc thi lenh SQL	
		//echo htmlspecialchars($psSql); exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($psSql) ; 
			$Result = $arrTempResult['NEW_ID'];
		}catch (Exception $e){
			echo $e->getMessage();
		};
		//Return result
		return $Result;		
	}
	/**
	 * 
	 */
	public function TaskWorkFeedBackStatusUpdate($sTaskWorkId){
		$psSql = "Exec Task_WorkFeedBackStatusUpdate ";		
		$psSql .= "'" . $sTaskWorkId . "'";	
		//Thuc thi lenh SQL	
		//echo htmlspecialchars($psSql); exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($psSql) ; 
			$Result = $arrTempResult['NEW_ID'];
		}catch (Exception $e){
			echo $e->getMessage();
		};
		//Return result
		return $Result;		
	}
}	
?>
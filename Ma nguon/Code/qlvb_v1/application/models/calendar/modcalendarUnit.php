<?php
/**
* Nguoi tao: HAIDV
* Ngay tao: 19/07/2011
* Y nghia: Xu ly Tao lap LICH dien tu
*/

class calendar_modcalendarUnit extends Sys_DB_Connection {
	
	public function ScheduleUnitGetAll($iWekk,$iYear,$sOwnercode,$iStatus){
		$sql = "Exec Schedule_UnitGetAll ";
		$sql = $sql . "'" . $iWekk . "'";
		$sql = $sql . ",'" . $iYear . "'";
		$sql = $sql . ",'" . $sOwnercode . "'";	
		$sql = $sql . ",'" . $iStatus . "'";		
		//echo '<br>'.$sql . '<br>'; //exit;
		try{
			$arrResul = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}			
		return $arrResul;
	}
	public function ScheduleUnitGetSingle($iScheduleID){
		$sql = "Exec Shedule_UnitGetSingle ";
		$sql = $sql . "'" . $iScheduleID . "'";		
		//echo '<br>'.$sql . '<br>'; //exit;
		try{
			$arrResul = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}			
		return $arrResul;
	}	
/**
 * Enter description here...
 * @param unknown_type $arrParameter -- Mang luu cac gia tri truyen vao
 * @return unknown
 */
	public function ScheduleUnitUpdate($arrParameter){
			$psSql = "Exec Schedule_UnitUpdate ";	
			$psSql .= "'" . $arrParameter['PK_SCHEDULE_UNIT'] . "'";
			$psSql .= ",'" . $arrParameter['FK_CREATE_STAFF'] . "'";
			$psSql .= ",'" . $arrParameter['FK_APPROVE_STAFF'] . "'";
			$psSql .= ",'" . $arrParameter['FK_JOINER_ID_LIST'] . "'";
			$psSql .= ",'" . $arrParameter['C_NAME_JOINER'] . "'";
			$psSql .= ",'" . $arrParameter['C_WEEK'] . "'";			
			$psSql .= ",'" . $arrParameter['C_YEAR'] . "'";
			$psSql .= ",'" . $arrParameter['C_DAY'] . "'";
			$psSql .= ",'" . $arrParameter['C_DAY_PART'] . "'";
			$psSql .= ",'" . $arrParameter['C_START_TIME'] . "'";
			$psSql .= ",'" . $arrParameter['C_FINISH_TIME'] . "'";
			$psSql .= ",'" . $arrParameter['C_WORK_NAME'] . "'";		
			$psSql .= ",'" . $arrParameter['C_WORK_CONTENT'] . "'";		
			$psSql .= ",'" . $arrParameter['C_PLACE'] . "'";		
			$psSql .= ",'" . $arrParameter['C_PREPARE_ORGAN'] . "'";		
			$psSql .= ",'" . $arrParameter['C_ATTENDING'] . "'";		
			$psSql .= ",'" . $arrParameter['C_OWNER_CODE'] . "'";		
			$psSql .= ",'" . $arrParameter['C_STATUS'] . "'";					
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
public function ScheduleUnitApprove($iScheduleID_list,$iApprove_staff_id,$iStatus){
			$sql = "Exec Schedule_UnitApprove ";	
			$sql = $sql . "'" . $iScheduleID_list . "'";
			$sql = $sql . ",'" . $iApprove_staff_id . "'";		
			$sql = $sql . ",'" . $iStatus . "'";				
			//echo htmlspecialchars($sql); exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($sql) ; 			
			$Result= $arrTempResult['RET_ERROR'];
		}catch (Exception $e){
			echo $e->getMessage();
		};				
		return $Result;		
	}

public function ScheduleUnitDelete($iScheduleID_list){
		$Result = null;			
		$sql = "Exec Schedule_UnitDelete ";		
		$sql .= "'".$iScheduleID_list ."'";	
		//echo $sql . '<br>'; exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($sql) ; 			
			$Result= $arrTempResult['RET_ERROR'];
		}catch (Exception $e){
			echo $e->getMessage();
		};				
		return $Result;	
	}
public function WebArticlePermissionCheck($sStaffId,$sPermission,$iHaveArticle){	
		$sql = "Exec Web_ArticlePermissionCheck ";		
		$sql .= "'".$sStaffId ."'";	
		$sql .= ",'".$sPermission ."'";	
		$sql .= ",'".$iHaveArticle ."'";	
		//echo $sql . '<br>'; //exit;
		try{
			$arrResul = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}		
		return $arrResul;
	}		
}
?>
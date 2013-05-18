<?php
/**
* Nguoi tao: HAIDV
* Ngay tao: 19/07/2011
* Y nghia: Xu ly Tao lap LICH dien tu
*/

class calendar_modcalendarStaff extends Sys_DB_Connection {
	
	public function Schedule_StaffGetSingle($staff_id,$wekk,$year){
		$sql = "Exec Schedule_StaffGetSingle ";
		$sql = $sql . "'" . $staff_id . "'";
		$sql = $sql . ",'" . $wekk . "'";
		$sql = $sql . ",'" . $year . "'";		
		//echo '<br>'.$sql . '<br>'; //exit;
		try{
			$arrResul = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}			
		return $arrResul;
	}
	public function Schedule_LeaderGetAll($list_id_leader,$wekk,$year){
		$sql = "Exec Schedule_LeaderGetAll ";
		$sql = $sql . "'" . $list_id_leader . "'";
		$sql = $sql . ",'" . $wekk . "'";
		$sql = $sql . ",'" . $year . "'";		
		//echo '<br>'.$sql . '<br>'; exit;
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
	public function Schedule_StaffUpdate($arrParameter){
		$psSql = "Exec Schedule_StaffUpdate ";	
		$psSql .= "'" . $arrParameter['PK_SCHEDULE_STAFF'] . "'";
		$psSql .= ",'" . $arrParameter['FK_STAFF'] . "'";
		$psSql .= ",'" . $arrParameter['C_WEEK'] . "'";
		$psSql .= ",'" . $arrParameter['C_YEAR'] . "'";
		$psSql .= ",'" . $arrParameter['C_MON'] . "'";
		$psSql .= ",'" . $arrParameter['C_TUE'] . "'";			
		$psSql .= ",'" . $arrParameter['C_WED'] . "'";
		$psSql .= ",'" . $arrParameter['C_THU'] . "'";
		$psSql .= ",'" . $arrParameter['C_FRI'] . "'";
		$psSql .= ",'" . $arrParameter['C_SAT'] . "'";
		$psSql .= ",'" . $arrParameter['C_SUN'] . "'";		
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
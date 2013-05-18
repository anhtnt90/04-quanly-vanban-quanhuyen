<?php
/**
* Nguoi tao: HAIDV
* Ngay tao: 19/07/2011
* Y nghia: Xu ly Tao lap LICH dien tu
*/

class authorized_modauthorized extends Sys_DB_Connection {
	public function Authorized_getAll(){
		$sql = "Exec Authorized_getAll ";		
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
	public function authorized_Update($arrParameter){
		$psSql = "Exec Authorized_Create ";
		$psSql .= "'" . $arrParameter['C_ID_LEADER'] . "'";
		$psSql .= ",'" . $arrParameter['C_NAME'] . "'";
		$psSql .= ",'" . $arrParameter['C_START_DATE'] . "'";
		$psSql .= ",'" . $arrParameter['C_END_DATE'] . "'";
		$psSql .= ",'" . $arrParameter['C_STATUS'] . "'";					
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
public function authorized_delete($auID){
		$Result = null;			
		$sql = "Exec Authorized_Delete ";		
		$sql .= "'".$auID ."'";	
		//echo $sql . '<br>'; exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($sql) ; 			
			$Result= $arrTempResult['RET_ERROR'];
		}catch (Exception $e){
			echo $e->getMessage();
		};				
		return $Result;	
	}
}
?>
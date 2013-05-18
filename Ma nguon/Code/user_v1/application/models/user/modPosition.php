<?php
/**
 * @copyright :Sys.com.vn - 11/2010 
 * @see : Nguoi tao: QUANGDD
 * */
class user_modPosition extends Sys_DB_Connection {		
	/**
	 * Creater : Sys
	 * Date : 06/06/2011
	 * Idea : Tao phuong thuc Lay danh sach chuc vu can bo
	 * @param $sFkPositionGroupId 	: Id Nhom chuc vu
	 * @param $sFulltextsearch		: Tu can tim kiem
	 * @param $sStatus				: Trang thai hoat dong cua chuc vu
	 */
	public function UserPositionGetAll($sFkPositionGroupId,$sFulltextsearch,$sStatus){		
		$sql = "Exec dbo.USER_PositionGetAll ";
		$sql = $sql . "'" . $sFkPositionGroupId . "'";
		$sql = $sql . ",'" . $sFulltextsearch . "'";
		$sql = $sql . ",'" . $sStatus . "'";
		//echo $sql . '<br>';// exit;
		try{
			$arrResul = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}		
		return $arrResul;
	}
	//-------------------------------------------------Xu ly du lieu NHOM CHUC VU-------------------------------------------------
	/**
	 * Creater : Sys
	 * Date : 06/06/2011
	 * Idea : Tao phuong thuc Lay danh sach NHOM chuc vu can bo
	 * @param $sFulltextsearch		: Tu can tim kiem
	 * @param $sStatus				: Trang thai hoat dong cua chuc vu
	 */
	public function UserPositionGroupGetAll($sFulltextsearch,$sStatus){		
		$sql = "Exec dbo.USER_PositionGroupGetAll ";
		$sql = $sql . "'" . $sFulltextsearch . "'";
		$sql = $sql . ",'" . $sStatus . "'";
		//echo $sql . '<br>';// exit;
		try{
			$arrResul = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}		
		return $arrResul;
	}
	/**
	 * Creater : Sys
	 * Date : 11/06/2011
	 * @param $arrParameter : Mang tham so
	 */
	public function UserPositionUpdate($arrParameter){
		$psSql = "Exec dbo.USER_PositionUpdate ";	
		$psSql .= "'" . $arrParameter['PK_POSITION'] . "'";
		$psSql .= ",'" . $arrParameter['FK_POSITION_GROUP'] . "'";
		$psSql .= ",'" . $arrParameter['C_CODE'] . "'";
		$psSql .= ",'" . $arrParameter['C_NAME'] . "'";
		$psSql .= ",'" . $arrParameter['C_ORDER'] . "'";
		$psSql .= ",'" . $arrParameter['C_STATUS'] . "'";
		//echo htmlspecialchars($psSql); exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($psSql) ; 
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrTempResult;		
	}
	/**
	 * Creater : Sys
	 * Date : 11/06/2011
	 * Idea : Tao phuong thuc lay thong tin chi  tiet chuc vu
	 * @param $sPositionId : Id Chuc vu
	 */
	public function UserPositionGetSingle($sPositionId){
		$arrResult = null;
		$sql = "Exec USER_PositionGetSingle ";
		$sql .= "'" . $sPositionId . "'";
		//echo $sql . '<br>'; 
		try{
			$arrResult = $this->adodbExecSqlString($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrResult;
	}
	/**
	 * Creater : Sys
	 * Date : 17/06/2011
	 * Idea : Tao phuong thuc xoa thong tin CHUC VU
	 * @param $sPositionIdList : Danh sach Id Chuc vu
	 */
	public function UserPositionDelete($sPositionIdList){
		$Result = null;			
		$sql = "Exec USER_PositionDelete ";		
		$sql .= "'".$sPositionIdList ."'";	
		//echo $sql . '<br>'; exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($sql); 			
			$Result= $arrTempResult['RET_ERROR'];
		}catch (Exception $e){
			echo $e->getMessage();
		};				
		return $Result;	
	}
}	
?>

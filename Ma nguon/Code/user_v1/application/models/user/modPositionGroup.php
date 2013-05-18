<?php
/**
 * @copyright :Sys.com.vn - 11/2010 
 * @see : Nguoi tao: QUANGDD
 * */
class user_modPositionGroup extends Sys_DB_Connection {		

	/**
	 * Creater : TuyenNH
	 * Date : 14/06/2011
	 * Idea : Tao phuong thuc Lay danh sach NHOM chuc vu can bo
	 * @param $sFulltextsearch		: Tu can tim kiem
	 * @param $sStatus				: Trang thai hoat dong cua chuc vu
	 */
	public function UserPositionGroupGetAll($sFulltextsearch,$sStatus){		
		$sql = "Exec dbo.USER_PositionGroupGetAll ";
		$sql = $sql . "'" . $sFulltextsearch . "'";
		$sql = $sql . ",'" . $sStatus . "'";
		//echo '<br>'.$sql . '<br>';// exit;
		try{
			$arrResul = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}		
		return $arrResul;
	}
	/**
	 * Creater : TuyenNH
	 * Date : 14/06/2011
	 * Idea : Tao phuong thuc Hien thi chi tiet mot NHOM chuc vu can bo
	 * @param $sFulltextsearch		: Tu can tim kiem
	 * @param $sStatus				: Trang thai hoat dong cua chuc vu
	 */
	public function UserPositionGroupGetSingle($sPkPositionGroupId){		
		$sql = "Exec dbo.USER_PositionGroupGetSingle ";
		$sql = $sql . "'" . $sPkPositionGroupId . "'";
		//echo $sql . '<br>';// exit;
		try{
			$arrResul = $this->adodbExecSqlString($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}		
		return $arrResul;
	}
	/**
	 * Creater : TuyenNH
	 * Date : 14/06/2011
	 * @param $arrParameter : Mang tham so
	 */
	public function UserPositionGroupUpdate($arrParameter){
		$psSql = "Exec dbo.USER_PositionGroupUpdate ";	
		$psSql .= "'" . $arrParameter['PK_POSITION_GROUP'] . "'";
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
	public function UserPositionGroupDelete($sPositionGroupIdList){
		$Result = null;			
		$sql = "Exec USER_PositionGroupDelete ";		
		$sql .= "'".$sPositionGroupIdList ."'";	
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

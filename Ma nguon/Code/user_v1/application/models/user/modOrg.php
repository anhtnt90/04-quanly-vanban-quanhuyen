<?php
/**
 * @copyright :Sys.com.vn - 11/2010 
 * @see : Nguoi tao: QUANGDD
 * */
class User_modOrg extends Sys_DB_Connection {		
	public function USERNetUserUpdate($arrParameter){
		$psSql = "Exec eCS_NetUserUpdate  ";	
		$psSql .= "'"  . $arrParameter['PK_NET_ID'] . "'";
		$psSql .= ",'" . $arrParameter['C_FULLNAME'] . "'";
		$psSql .= ",'" . $arrParameter['C_USERNAME'] . "'";
		$psSql .= ",'" . $arrParameter['C_PASSWORD'] . "'";
		$psSql .= ",'" . $arrParameter['C_EMAIL'] . "'";
		$psSql .= ",'" . $arrParameter['C_ID_CARD'] . "'";;			
		$psSql .= ",'" . $arrParameter['C_CREATED_DATE'] . "'";
		$psSql .= ",'" . $arrParameter['C_XML_DATA'] . "'";
		//Thuc thi lenh SQL		
		//echo $psSql; exit;
		try {			
			$arrResult = $this->adodbExecSqlString($psSql) ; 
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrResult;
	}
	public function USERNetStaffGetALL($sUsername,$sNewpass){
		$psSql = "Exec eCS_NetUpdatePass ";
		$psSql .= "'"  . $sUsername . "'";
		$psSql .= ",'"  . $sNewpass . "'";
		//echo  "<br>". $psSql . "<br>"; 
		//exit;
		try{
			$arrResult = $this->adodbExecSqlString($psSql);
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrResult;
	}
	
	public function USERUnitGetSingle($sUnitId){
		$psSql = "Exec USER_UnitGetSingle ";
		$psSql .= "'"  . $sUnitId . "'";
		//echo  "<br>". $psSql . "<br>"; 
		//exit;
		try{
			$arrResult = $this->adodbExecSqlString($psSql);
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrResult;
	}
	public function USERStaffGetSingle($sStaffId){
		$psSql = "Exec USER_StaffGetSingle ";
		$psSql .= "'"  . $sStaffId . "'";
		//echo  "<br>". $psSql . "<br>"; 
		//exit;
		try{
			$arrResult = $this->adodbExecSqlString($psSql);
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrResult;
	}
	
	public function USERStaffGetAll ($sStatus,$sPkUnitId,$sFullTextSearch,$sOwnerCode){
		$psSql = "Exec USER_StaffGetAll ";
		$psSql .= "'"  . $sStatus . "'";
		$psSql .= ",'"  . $sPkUnitId . "'";
		$psSql .= ",'"  . $sFullTextSearch . "'";
		$psSql .= ",'"  . $sOwnerCode . "'";
		//echo  "<br>". $psSql . "<br>"; 
		//exit;
		try{
			$arrResult = $this->adodbQueryDataInNameMode($psSql);//Thuc thi chuoi sql va tra ra mang da chieu
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrResult;
	}
	public function USERStaffGetAllBySearch ($sStatus,$sFullTextSearch,$sOwnerCode,$sUnitId){
		$psSql = "Exec USER_StaffGetAllBySearch ";
		$psSql .= "'"  . $sStatus . "'";
		$psSql .= ",'"  . $sFullTextSearch . "'";
		$psSql .= ",'"  . $sOwnerCode . "'";
		$psSql .= ",'"  . $sUnitId . "'";
		//echo  "<br>". $psSql . "<br>"; 
		//exit;
		try{
			$arrResult = $this->adodbQueryDataInNameMode($psSql);//Thuc thi chuoi sql va tra ra mang da chieu
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrResult;
	}
	public function USERUnitUpdate($arrParameter){
		$psSql = "Exec USER_UnitUpdate  ";	
		$psSql .= "'"  . $arrParameter['PK_UNIT'] . "'";
		$psSql .= ",'" . $arrParameter['FK_UNIT'] . "'";
		$psSql .= ",'" . $arrParameter['C_CODE'] . "'";
		$psSql .= ",'" . $arrParameter['C_NAME'] . "'";
		$psSql .= ",'" . $arrParameter['C_ADDRESS'] . "'";
		$psSql .= ",'" . $arrParameter['C_TEL'] . "'";;			
		$psSql .= ",'" . $arrParameter['C_LOCAL'] . "'";
		$psSql .= ",'" . $arrParameter['C_FAX'] . "'";		
		$psSql .= ",'" . $arrParameter['C_EMAIL'] . "'";
		$psSql .= ",'" . $arrParameter['C_ORDER'] . "'";;			
		$psSql .= ",'" . $arrParameter['C_STATUS'] . "'";
		$psSql .= ",'" . $arrParameter['C_OWNER_CODE'] . "'";
		//Thuc thi lenh SQL		
		//echo $psSql; exit;
		try {			
			$arrResult = $this->adodbExecSqlString($psSql) ; 
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrResult;
	}
	public function USERUnitDelete($sUnitId){
		$psSql = "Exec USER_UnitDelete ";
		$psSql .= "'"  . $sUnitId . "'";
		//echo  "<br>". $psSql . "<br>"; 
		//exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($psSql) ; 			
			$Result= $arrTempResult['RET_ERROR'];
		}catch (Exception $e){
			echo $e->getMessage();
		};				
		return $Result;	
	}
	public function USERStaffDelete($sStaffListId){
		$psSql = "Exec USER_StaffDelete ";
		$psSql .= "'"  . $sStaffListId . "'";
		//echo  "<br>". $psSql . "<br>"; 
		//exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($psSql) ; 			
			$Result= $arrTempResult['RET_ERROR'];
		}catch (Exception $e){
			echo $e->getMessage();
		};				
		return $Result;	
	}
	public function USERUnitGetAll($sStatus,$sFullTextSearch,$sOwnercode){
		$psSql = "Exec USER_UnitGetAll ";
		$psSql .= "'"  . $sStatus . "'";
		$psSql .= ",'"  . $sFullTextSearch . "'";
		$psSql .= ",'"  . $sOwnercode . "'";
		//echo  "<br>". $psSql . "<br>"; 
		//exit;
		try {			
			$arrResult = $this->adodbQueryDataInNameMode($psSql);
		}catch (Exception $e){
			echo $e->getMessage();
		};				
		return $arrResult;
	}
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
	public function UserPositionGroupGetAll($sFulltextsearch,$sStatus){		
		$sql = "Exec USER_PositionGroupGetAll ";
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
	public function UserStaffUpdate($arrParameter){
		$psSql = "Exec USER_StaffUpdate  ";	
		$psSql .= "'"  . $arrParameter['T_USER_STAFF'] . "'";
		$psSql .= ",'" . $arrParameter['FK_UNIT'] . "'";
		$psSql .= ",'" . $arrParameter['FK_POSITION'] . "'";
		$psSql .= ",'" . $arrParameter['C_NAME'] . "'";
		$psSql .= ",'" . $arrParameter['C_ADDRESS'] . "'";
		$psSql .= ",'" . $arrParameter['C_EMAIL'] . "'";
		$psSql .= ",'" . $arrParameter['C_TEL_LOCAL'] . "'";
		$psSql .= ",'" . $arrParameter['C_TEL'] . "'";;			
		$psSql .= ",'" . $arrParameter['C_TEL_MOBILE'] . "'";
		$psSql .= ",'" . $arrParameter['C_TEL_HOME'] . "'";
		$psSql .= ",'" . $arrParameter['C_FAX'] . "'";		
		$psSql .= ",'" . $arrParameter['C_USERNAME'] . "'";
		$psSql .= ",'" . $arrParameter['C_PASSWORD'] . "'";
		$psSql .= "," . $arrParameter['C_ORDER'] ;			
		$psSql .= ",'" . $arrParameter['C_STATUS'] . "'";
		$psSql .= ",'" . $arrParameter['C_ROLE'] . "'";
		$psSql .= ",'" . $arrParameter['C_SEX'] . "'";
		$psSql .= ",'" . $arrParameter['C_BIRTHDAY'] . "'";
		$psSql .= ",'" . $arrParameter['C_DN'] . "'";
		//Thuc thi lenh SQL		
		//echo $psSql; //exit;
		try {			
			$arrResult = $this->adodbExecSqlString($psSql) ; 
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrResult;
	}
	public function USERStaffResetPassWord($sStaffId,$sNewPassWord){		
		$sql = "Exec USER_StaffResetPassWord ";
		$sql = $sql . "'" . $sStaffId . "'";
		$sql = $sql . ",'" . $sNewPassWord . "'";
		//echo $sql . '<br>';// exit;
		try{
			$arrResul = $this->adodbExecSqlString($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}		
		return $arrResul;
	}
}	
?>

<?php
class login_modCheckLogin extends Sys_DB_Connection {		
	public function UserCheckLogin($sUserName,$sPassWord){		
		$sql = Sys_Init_Config::_setDbLinkUser() . ".dbo.USER_UserCheckLogin ";
		$sql = $sql . "'" . $sUserName . "'";
		$sql = $sql . ",'" . $sPassWord . "'";			
		//echo '<br>'.$sql . '<br>'; exit;
		try{
			$arrResult = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}		
		return $arrResult;
	}
	/**
	 * Creater: Do viet Hai
	 * Enter:Tao phuong thuc Reset mat khau nguoi su dung
	 * Date: 16/08/2011
	 * @param unknown_type $iStaffID
	 * @param unknown_type $sPassWord
	 * @param unknown_type $sOldPassword
	 */
	public function UserChangePass($iStaffID,$sPassWord,$sOldPassword,$sUserName){		
		$sql = Sys_Init_Config::_setDbLinkUser() . ".dbo.USER_StaffResetPassWord ";
		$sql = $sql . "'" . $iStaffID . "'";
		$sql = $sql . ",'" . $sPassWord . "'";
		$sql = $sql . ",'" . $sOldPassword . "'";
		$sql = $sql . ",'" . $sUserName . "'";		
		//echo '<br>'.$sql . '<br>'; exit;
		try {			
			$Result = $this->adodbExecSqlString($sql) ; 			
		}catch (Exception $e){
			echo $e->getMessage();
		};
		//Return result
		return $Result;	
	}
}
?>

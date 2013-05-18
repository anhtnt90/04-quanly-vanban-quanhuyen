<?php
/**
 * @copyright :Sys.com.vn - 11/2010 
 * @see : Nguoi tao: QUANGDD
 * */
class login_modCheckLogin extends Sys_DB_Connection {		

	/**
	 * Creater : TuyenNH
	 * Date : 14/06/2011
	 * Idea : Tao phuong thuc Kiem tra, xac thuc NSD khi dang nhap
	 * @param $sUserName				: Ten dang nhap
	 * @param $sPassWord				: Mat khau
	 */
	public function UserCheckLogin($sUserName,$sPassWord){		
		$sql = "Exec dbo.USER_UserCheckLogin ";
		$sql = $sql . "'" . $sUserName . "'";
		$sql = $sql . ",'" . $sPassWord . "'";
		//echo '<br>'.$sql . '<br>';// exit;
		try{
			$arrResult = $this->adodbExecSqlString($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}		
		return $arrResult;
	}
}	
?>

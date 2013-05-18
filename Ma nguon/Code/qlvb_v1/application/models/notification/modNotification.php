<?php
/**

/**
* Nguoi tao: Truong Huu Thanh	
* Ngay tao: 20/11/2009
* Y nghia:Lay thong tin nhac viec
*/

class notification_modNotification extends Sys_DB_Connection {
	
	/** Nguoi tao: NGHIAT
	* Ngay tao: 05/08/2010
	* Y nghia: Lay ra danh sach nhac viec
	*/
	public function docReminderGetAll($iUserId,$iDepartmentId,$iOwnerId,$sPermissionList,$sRoleLeader,$iPosition, $sMainRoleLeaderGroup = '', $sSubRoleLeaderGroup = ''){
		$sql = "Doc_DocReminderGetAll ";
		$sql = $sql . " '" . $iUserId . "'";
		$sql = $sql . ",'" . $iDepartmentId . "'";		
		$sql = $sql . ",'" . $iOwnerId . "'";	
		$sql = $sql . ",'" . $sPermissionList . "'";	
		$sql = $sql . ",'" . $sRoleLeader . "'";	
		$sql = $sql . ",'" . $iPosition . "'";	
		$sql = $sql . ",'" . $sMainRoleLeaderGroup . "'";
		$sql = $sql . ",'" . $sSubRoleLeaderGroup . "'";
		//echo  "<br>". $sql . "<br>"; 
		//exit;
		try{
			$arrReminder = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrReminder;		
	}
}
?>
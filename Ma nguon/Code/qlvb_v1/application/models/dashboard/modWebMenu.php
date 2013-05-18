<?php
class dashboard_modWebMenu extends Sys_DB_Connection {	
	public function WebMenuUpdate($arrParameter){		
		$psSql = "Exec Web_MenuUpdate ";
		$psSql .= "'" . $arrParameter['PK_WEB_MENU'] . "'";
		$psSql .= ",'" . $arrParameter['FK_WEB_MENU'] . "'";
		$psSql .= ",'" . $arrParameter['C_NAME'] . "'";
		$psSql .= ",'" . $arrParameter['C_LEVEL'] . "'";
		$psSql .= ",'" . $arrParameter['C_URL'] . "'";
		$psSql .= ",'" . $arrParameter['FK_WEB_ARTICLE'] . "'";			
		$psSql .= ",'" . $arrParameter['C_POSITION'] . "'";
		$psSql .= ",'" . $arrParameter['C_WEB_DISPLAY'] . "'";
		$psSql .= ",'" . $arrParameter['C_WINDOWS_OPEN'] . "'";
		$psSql .= ",'" . $arrParameter['C_ORDER'] . "'";
		$psSql .= ",'" . $arrParameter['C_STATUS'] . "'";
		$psSql .= ",'" . $arrParameter['C_PUBLIC_VIEW'] . "'";
		$psSql .= ",'" . $arrParameter['C_EDIT_ID_LIST'] . "'";
		$psSql .= ",'" . $arrParameter['C_APPROVED_ID_LIST'] . "'";
		$psSql .= ",'" . $arrParameter['C_VIEW_ID_LIST'] . "'";
		$psSql .= ",'" . $arrParameter['C_OWNER_CODE_LIST'] . "'";
		//echo $psSql . '<br>'; exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($psSql) ; 
			$Result = $arrTempResult['NEW_ID'];
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $Result;	
	}	
	public function WebMenuGetAll($iPosition,$sOwner,$iLevel, $iStatus){		
		$sql = "Exec Web_MenuGetAll ";
		$sql = $sql . "'" . $iPosition . "'";
		$sql = $sql . ",'" . $sOwner . "'";	
		$sql = $sql . ",'" . $iLevel . "'";		
		$sql = $sql . ",'" . $iStatus . "'";	
		//echo $sql . '<br>'; //exit;
		try{
			$arrResul = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}		
		return $arrResul;
	}
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $sReceiveDocumentId
	 * @param unknown_type $sUnitId
	 */	
	public function WebMenuGetSingle($sWebMenuId){
		$sql = "Exec Web_MenuGetSingle ";
		$sql .= "'" . $sWebMenuId . "'";
		//echo $sql . '<br>'; //exit;
		try{
			$arrResul = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};		
		return $arrResul;
	}
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $sReceiveDocumentIdList
	 * @param unknown_type $iHasDeleteAllPermission
	 */
	public function WebMenuDelete($sMenuIdList){
		$Result = null;			
		$sql = "Exec Web_MenuDelete ";		
		$sql .= "'".$sMenuIdList ."'";	
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
	 * Enter description here ...
	 * @param unknown_type $sWebMenuId
	 */
	public function Web_MenuGetOrder($iPosition,$iLevel,$sMenuID){
		$sql = "Exec Web_getOrderbyPosition ";
		$sql .= "'" . $iPosition . "'";
		$sql .= ",'" . $iLevel . "'";
		$sql .= ",'" . $sMenuID . "'";
		//echo $sql . '<br>'; //exit;
		try{
			$arrResul = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};		
		return $arrResul;
	}
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $iPosition
	 * @param unknown_type $sOwner
	 * @param unknown_type $iLevel
	 */
	public function WebArticleGetAll($sCreateStaff,$sMenuID,$iStatus,$sFullTextSearch,$iPage,$iNumberRecordPerPage){		
		$sql = "Exec Web_ArticleGetAll ";
		$sql = $sql . "'" . $sCreateStaff . "'";
		$sql = $sql . ",'" . $sMenuID . "'";	
		$sql = $sql . ",'" . $iStatus . "'";		
		$sql = $sql . ",'" . $sFullTextSearch . "'";
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
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $sCreateStaff
	 * @param unknown_type $sMenuID
	 * @param unknown_type $iStatus
	 * @param unknown_type $sFullTextSearch
	 * @param unknown_type $iPage
	 * @param unknown_type $iNumberRecordPerPage
	 */
	public function WebArticleGetSingle($sArticleID){		
		$sql = "Exec Web_ArticleGetSingle ";
		$sql = $sql . "'" . $sArticleID . "'";
		//echo $sql . '<br>'; //exit;
		try{
			$arrResul = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}		
		return $arrResul;
	}
	/**
	 * 
	 * Kiem tra quyen tin bai
	 * @param unknown_type $sArticleIdList
	 */
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
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $iPosition
	 * @param unknown_type $sOwner
	 * @param unknown_type $iLevel
	 */
	public function WebHomeInfoGetAll($sMenuIdList,$iCountInMenu){		
		$sql = "Exec Web_HomeInfoGetAll ";
		$sql = $sql . "'" . $sMenuIdList . "'";	
		$sql = $sql . ",'" . $iCountInMenu . "'";				
		//echo $sql . '<br>'; //exit;
		try{
			$arrResul = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}		
		return $arrResul;
	}
	/**
	 * 
	 */
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
	public function ScheduleUnitGetToday($iWeek,$iYear,$sDay,$iStaus,$Owenercode){		
		$sql = "Exec Schedule_UnitGetToday ";
		$sql .= "'" . $iWeek . "'";
		$sql .= ",'" . $iYear . "'";
		$sql .= ",'" . $sDay . "'";
		$sql .= ",'" . $iStaus . "'";
		$sql .= ",'" . $Owenercode . "'";
		//echo $sql . '<br>'; //exit;
		try{
			$arrResul = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}		
		return $arrResul;
	}
	/**
	 * 
	 */
	public function TaskWorkNotyGetAll($staff_id,$sStaffRole,$sUnitID,$sUnitType){		
		$sql = "Exec Task_WorkNotyGetAll ";
		$sql .= "'" . $staff_id . "'";
		$sql .= ",'" . $sStaffRole . "'";
		$sql .= ",'" . $sUnitID . "'";
		$sql .= ",'" . $sUnitType . "'";
		//echo $sql . '<br>'; //exit;
		try{
			$arrResul = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}		
		return $arrResul;
	}
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
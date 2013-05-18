<?php
class dashboard_modWebArticle extends Sys_DB_Connection {
	public function WebArticleUpdate($arrParameter){		
		$psSql = "Exec Web_ArticleUpdate ";
		$psSql .= "'" . $arrParameter['PK_WEB_ARTICLE'] . "'";
		$psSql .= ",'" . $arrParameter['FK_WEB_MENU'] . "'";
		$psSql .= ",'" . $arrParameter['C_TITLE'] . "'";
		$psSql .= ",'" . $arrParameter['C_SHORT_CONTENT'] . "'";
		$psSql .= ",'" . $arrParameter['C_DETAIL_CONTENT'] . "'";
		$psSql .= ",'" . $arrParameter['C_IMAGE_TITLE'] . "'";			
		$psSql .= ",'" . $arrParameter['FK_CREATE_STAFF'] . "'";
		$psSql .= ",'" . $arrParameter['FK_CREATE_NAME'] . "'";
		$psSql .= ",'" . $arrParameter['FK_APPROVE_STAFF'] . "'";
		$psSql .= ",'" . $arrParameter['C_STATUS'] . "'";
		$psSql .= ",'" . $arrParameter['IMAGE_NAME'] . "'";
		$psSql .= ",'" . $arrParameter['ATTACH_FILE_NAME_LIST'] . "'";
		//echo $psSql . '<br>'; exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($psSql) ; 
			$Result = $arrTempResult['NEW_ID'];
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $Result;	
	}
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $iPosition
	 */
	public function WebMenuGetAll($iPosition,$sOwner,$iLevel){		
		$sql = "Exec Web_MenuGetAll ";
		$sql = $sql . "'" . $iPosition . "'";
		$sql = $sql . ",'" . $sOwner . "'";	
		$sql = $sql . ",'" . $iLevel . "'";		
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
	 * Enter description here ...
	 * @param unknown_type $pReceiveDocumentId
	 * @param unknown_type $pFileTyle
	 * @param unknown_type $pTableObject
	 */
	public function DOC_GetAllDocumentFileAttach($pReceiveDocumentId, $pFileTyle, $pTableObject){
		$sql = "Exec Doc_GetAllDocumentFileAttach '" . $pReceiveDocumentId . "'";
		$sql .= ",'".$pFileTyle ."'";		
		$sql .= ",'".$pTableObject ."'"; 
		//echo $sql . '<br>';
		try {						
			$arrResult = $this->adodbQueryDataInNameMode($sql);					
		}catch (Exception $e){
			echo $e->getMessage();
		};				
		return $arrResult;			
	}
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $sMenuIdList
	 */
	public function WebArticleDelete($sArticleIdList){
		$Result = null;			
		$sql = "Exec Web_ArticleDelete ";		
		$sql .= "'".$sArticleIdList ."'";	
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
	 * @param unknown_type $sStaffId
	 * @param unknown_type $sPermission
	 * @param unknown_type $iHaveArticle
	 */
	public function WebArticleMove($sArticleID,$iMoveOrder){	
		$Result = null;		
		$sql = "Exec Web_ArticleMove ";	
		$sql .= "'".$sArticleID ."'";	
		$sql .= ",'".$iMoveOrder ."'";	
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
	 * @param unknown_type $sArticleIdList
	 */
	public function WebArticleApprove($sArticleIdList,$staffid){
		$Result = null;			
		$sql = "Exec Web_ArticleApproved ";		
		$sql .= "'".$sArticleIdList ."'";	
		$sql .= ",'".$staffid ."'";
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
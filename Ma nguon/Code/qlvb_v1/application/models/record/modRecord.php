<?php
/**
* Nguoi tao: Phuongtt
* Ngay tao: /11/2009
* Y nghia:Lay thong tin bao cao cong viec
*/

class record_modRecord extends Sys_DB_Connection {
	/**
	 * Y nghia: Lay danh sach ho so luu tru
	 *
	 * @param unknown_type $iCurrentStaffId
	 * @param unknown_type $iOwnerId
	 * @param unknown_type $iCurrentUnitId
	 * @param unknown_type $sFullTextSearch
	 * @param unknown_type $iPage
	 * @param unknown_type $iNumberRecordPerPage
	 * @param unknown_type $sFromDate
	 * @param unknown_type $sToDate
	 * @return unknown
	 */
	public function DocRecordArchivesGetAll($iCurrentStaffId, $iOwnerId, $iCurrentUnitId, $sFullTextSearch, $iPage, $iNumberRecordPerPage, $sFromDate, $sToDate){		
		$sql = "Exec Doc_RecordArchivesGetAll ";
		$sql = $sql . "'" . $iCurrentStaffId . "'";
		$sql = $sql . ",'" . $iOwnerId . "'";
		$sql = $sql . ",'" . $iCurrentUnitId . "'";
		$sql = $sql . ",'" . $sFullTextSearch . "'";
		$sql = $sql . ",'" . $iPage . "'";
		$sql = $sql . ",'" . $iNumberRecordPerPage . "'";
		$sql = $sql . ",'" . $sFromDate . "'";
		$sql = $sql . ",'" . $sToDate . "'";
		//echo '<br>'.$sql . '<br>'; //exit;
		try{
			$arrResul = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrResul;
	}
	/**
	 * Y nghia: lay danh sach ho so luu tru can bo duoc quyen xem va da tao ra
	 *
	 * @param unknown_type $iCurrentStaffId
	 * @param unknown_type $iOwnerId
	 * @param unknown_type $iCurrentUnitId
	 * @param unknown_type $sFullTextSearch
	 * @param unknown_type $iPage
	 * @param unknown_type $iNumberRecordPerPage
	 * @param unknown_type $sFromDate
	 * @param unknown_type $sToDate
	 * @return unknown
	 */
	public function DocRecordArchivesStaffGetAll($iCurrentStaffId, $iOwnerId, $iCurrentUnitId, $sFullTextSearch, $iPage, $iNumberRecordPerPage, $sFromDate, $sToDate){		
		$sql = "Exec Doc_RecordArchivesStaffGetAll ";
		$sql = $sql . "'" . $iCurrentStaffId . "'";
		$sql = $sql . ",'" . $iOwnerId . "'";
		$sql = $sql . ",'" . $iCurrentUnitId . "'";
		$sql = $sql . ",'" . $sFullTextSearch . "'";
		$sql = $sql . ",'" . $iPage . "'";
		$sql = $sql . ",'" . $iNumberRecordPerPage . "'";
		$sql = $sql . ",'" . $sFromDate . "'";
		$sql = $sql . ",'" . $sToDate . "'";
		//echo '<br>'.$sql . '<br>'; //exit;
		try{
			$arrResul = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrResul;
	}
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $code
	 * @return unknown
	 */
	public function getPropertiesDocument($code){
		$sql = "SysLib_ListGetAllbyCode ";
		$sql = $sql . "'" . $code . "', ''";
		try {
			$arrSel = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrSel;
	}

	/**
	 * Y nghia: Cap nhat thong tin mot ho so luu tru
	 *
	 * @param unknown_type $arrParameter
	 * @return unknown
	 */
	public function DocRecordArchiveUpdate($arrParameter){
		$psSql = "Exec Doc_RecordArchiveUpdate  ";	
		$psSql .= "'" . $arrParameter['PK_RECORD'] . "'";
		$psSql .= ",'" . $arrParameter['FK_OWNER_ID'] . "'";
		$psSql .= ",'" . $arrParameter['FK_STAFF'] . "'";
		$psSql .= ",'" . $arrParameter['C_STAFF_POSITION_NAME'] . "'";
		$psSql .= ",'" . $arrParameter['FK_UNIT'] . "'";
		$psSql .= ",'" . $arrParameter['C_UNIT_NAME'] . "'";
		$psSql .= ",'" . $arrParameter['C_CREATE_DATE'] . "'";			
		$psSql .= ",'" . $arrParameter['C_RECORD_NAME'] . "'";
		$psSql .= ",'" . $arrParameter['C_RECORD_ID'] . "'";
		$psSql .= ",'" . $arrParameter['C_NOTES'] . "'";
		$psSql .= ",'" . $arrParameter['C_PERMISSION_VIEW'] . "'";
		$psSql .= ",'" . $arrParameter['C_VIEW_STAFF_LIST_ID'] . "'";
		//Thuc thi lenh SQL		
		//echo htmlspecialchars($psSql); exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($psSql) ; 
			//$Result = $arrTempResult['NEW_ID'];
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrTempResult;		
	}
	/**
	 * Y nghia: Phuong thuc lay thong tin chi tiet mot ho so luu tru
	 *
	 * @param unknown_type $sRecordArchiveId
	 * @return unknown
	 */
	public function DocRecordArchiveGetSingle($sRecordArchiveId){
		$sql = "Exec Doc_RecordArchiveGetSingle ";
		$sql .= "'" . $sRecordArchiveId . "'";
		//echo $sql . '<br>'; exit;
		try{
			$arrRecordArchive = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};		
		return $arrRecordArchive;
	}
	/**
	 * Y nghia: Cap nhat van ban lien quan cho mot ho so luu tru
	 *
	 * @param unknown_type $sRecordArchiveId
	 * @param unknown_type $sDocRelateListId
	 * @param unknown_type $sDocRelateType
	 * @return unknown
	 */
	public function DocRecordArchiveDocRelateUpdate($sRecordArchiveId, $sDocRelateListId, $sDocRelateType){
		$sql = "Exec Doc_RecordArchiveDocRelateUpdate  ";	
		$sql = $sql . "'" . $sRecordArchiveId . "'";
		$sql = $sql . ",'" . $sDocRelateListId . "'";
		$sql = $sql . ",'" . $sDocRelateType . "'";
		//Thuc thi lenh SQL		
		//echo htmlspecialchars($sql); exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($sql) ; 
			//$Result = $arrTempResult['NEW_ID'];
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrTempResult;	
	}
	/**
	 * Y nghia: Phuong thuc lay danh sach van ban lien quan
	 *
	 * @param unknown_type $sRecordArchiveId
	 * @param unknown_type $sFullTextSearch
	 * @param unknown_type $iPage
	 * @param unknown_type $iNumberRecordPerPage
	 * @param unknown_type $sFromDate
	 * @param unknown_type $sToDate
	 * @return unknown
	 */
	public function DocRecordArchiveDocRelateGetAll($sRecordArchiveId, $sFullTextSearch, $iPage, $iNumberRecordPerPage, $sFromDate, $sToDate){		
		$sql = "Exec Doc_RecordArchiveDocRelateGetAll ";
		$sql = $sql . "'" . $sRecordArchiveId . "'";
		$sql = $sql . ",'" . $sFullTextSearch . "'";
		$sql = $sql . ",'" . $iPage . "'";
		$sql = $sql . ",'" . $iNumberRecordPerPage . "'";
		$sql = $sql . ",'" . $sFromDate . "'";
		$sql = $sql . ",'" . $sToDate . "'";
		//echo $sql . '<br>'; //exit;
		try{
			$arrDocRelate = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrDocRelate;
	}
	/**
	 * Y nghia: Phuong thuc lay danh sach van ban den cho viec cap nhat van ban lien quan vao ho so luu tru
	 *
	 * @param unknown_type $sDocType
	 * @param unknown_type $sDocCate
	 * @param unknown_type $iYear
	 * @param unknown_type $iFkUnit
	 * @param unknown_type $sFullTextSearch
	 * @param unknown_type $iPage
	 * @param unknown_type $iNumberRecordPerPage
	 * @return unknown
	 */
	public function DocRecordArchiveReceivedDocGetAll($sDocType, $sDocCate, $iYear, $iFkUnit, $sFullTextSearch, $iPage, $iNumberRecordPerPage){		
		$sql = "Exec Doc_RecordArchiveReceivedDocGetAll ";
		$sql = $sql . "'" . $sDocType . "'";
		$sql = $sql . ",'" . $sDocCate . "'";
		$sql = $sql . ",'" . $iYear . "'";
		$sql = $sql . ",'" . $iFkUnit . "'";
		$sql = $sql . ",'" . $sFullTextSearch . "'";
		$sql = $sql . ",'" . $iPage . "'";
		$sql = $sql . ",'" . $iNumberRecordPerPage . "'";
		//echo '<br>'.$sql . '<br>'; exit;
		try{
			$arrReceivedDoc = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrReceivedDoc;
	}
	/**
	 * Y nghia: Phuong thuc lay danh sach van ban di cho viec cap nhat van ban lien quan vao ho so luu tru
	 *
	 * @param unknown_type $sDocType
	 * @param unknown_type $sDocCate
	 * @param unknown_type $iYear
	 * @param unknown_type $iFkUnit
	 * @param unknown_type $sFullTextSearch
	 * @param unknown_type $iPage
	 * @param unknown_type $iNumberRecordPerPage
	 * @return unknown
	 */
	public function DocRecordArchiveSentDocGetAll($sDocType, $sDocCate, $iYear, $iFkUnit, $sFullTextSearch, $iPage, $iNumberRecordPerPage){		
		$sql = "Exec Doc_RecordArchiveSentDocGetAll ";
		$sql = $sql . "'" . $sDocType . "'";
		$sql = $sql . ",'" . $sDocCate . "'";
		$sql = $sql . ",'" . $iYear . "'";
		$sql = $sql . ",'" . $iFkUnit . "'";
		$sql = $sql . ",'" . $sFullTextSearch . "'";
		$sql = $sql . ",'" . $iPage . "'";
		$sql = $sql . ",'" . $iNumberRecordPerPage . "'";
		//echo '<br>'.$sql . '<br>'; //exit;
		try{
			$arrSentDoc = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrSentDoc;
	}
	/**
	 * Y nghia: Phuong thuc xoa mot hay nhieu ho so luu tru
	 *
	 * @param unknown_type $sRecordArchiveIdList
	 * @param unknown_type $iHasDeleteAllPermission
	 * @return unknown
	 */
	public function DocRecordArchivesDelete($sRecordArchiveIdList, $iHasDeleteAllPermission){
		$Result = null;			
		$sql = "Exec Doc_RecordArchivesDelete ";		
		$sql .= "'".$sRecordArchiveIdList ."'";	
		$sql .= ",'".$iHasDeleteAllPermission ."'";
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
	 * Y nghia: Phuong thuc xoa mot hay nhieu van ban lien quan cua mot ho so luu tru
	 *
	 * @param unknown_type $sRecordArchiveId
	 * @param unknown_type $sDocRelateIdList
	 * @return unknown
	 */
	public function DocRecordArchivesDocRelateDelete($sRecordArchiveId, $sDocRelateIdList){
		$Result = null;			
		$sql = "Exec Doc_RecordArchivesDocRelateDelete ";		
		$sql .= "'".$sRecordArchiveId ."'";	
		$sql .= ",'".$sDocRelateIdList ."'";
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
	 * Idea : Phuong lay file dinh kem 
	 *
	 * @param unknown_type $pReceiveDocumentId
	 * @param unknown_type $pFileTyle
	 * @param unknown_type $pTableObject
	 * @return unknown
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
	public function DocRecordArchivesDocRelateCheck($sRecordArchiveId){		
		$sql = "Exec Doc_RecordArchivesDocRelateId '" . $sRecordArchiveId . "'";
		//echo $sql . '<br>'; exit;
		try{
			$arrDocRelateId = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrDocRelateId;
	}
	/**
	 * Y nghia: Cap nhat van ban khac vao ho so luu tru
	 *
	 * @param unknown_type $arrParameter
	 * @return unknown
	 */
	public function DocRecordArchiveDocOtherUpdate($arrParameter){
		$psSql = "Exec Doc_RecordArchiveDocOtherUpdate  ";	
		$psSql .= "'" . $arrParameter['PK_DOCUMENT_OTHER_RECORD'] . "'";
		$psSql .= ",'" . $arrParameter['FK_RECORD'] . "'";
		$psSql .= ",'" . $arrParameter['FK_CREATER'] . "'";
		$psSql .= ",'" . $arrParameter['C_CREATER_POSITION_NAME'] . "'";
		$psSql .= ",'" . $arrParameter['C_CREATE_DATE'] . "'";
		$psSql .= ",'" . $arrParameter['C_DOC_TYPE'] . "'";		
		$psSql .= ",'" . $arrParameter['C_SYMBOL'] . "'";
		$psSql .= ",'" . $arrParameter['C_DOC_CATE'] . "'";
		$psSql .= ",'" . $arrParameter['C_SUBJECT'] . "'";
		$psSql .= ",'" . $arrParameter['C_AGENTCY_GROUP'] . "'";
		$psSql .= ",'" . $arrParameter['C_AGENTCY_NAME'] . "'";
		$psSql .= ",'" . $arrParameter['NEW_FILE_ID_LIST'] . "'";
		//Thuc thi lenh SQL		
		//echo htmlspecialchars($psSql); exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($psSql) ; 
			//$Result = $arrTempResult['NEW_ID'];
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrTempResult;		
	}
	/**
	 * Y nghia: Phuong thuc lay thong tin chi tiet mot ho so luu tru
	 *
	 * @param unknown_type $sRecordArchiveId
	 * @return unknown
	 */
	public function DocRecordArchiveDocOtherGetSingle($sRecordArchiveId, $sDocRelateId){
		$sql = "Exec Doc_RecordArchiveDocOtherGetSingle ";
		$sql .= "'" . $sRecordArchiveId . "'";
		$sql .= ",'" . $sDocRelateId . "'";
		//echo $sql . '<br>'; exit;
		try{
			$arrDocRelate = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};		
		return $arrDocRelate;
	}
	/**
	 * Y nghia: ham lay danh sach ten file dinh kem
	 *
	 * @param unknown_type $pReceiveDocumentId
	 * @param unknown_type $pFileTyle
	 * @param unknown_type $pTableObject
	 * @return unknown
	 */
	public function DOC_GetAllDocumentFileAttachName($sListIdDoc,$sDocType,$sTableName,$sdelimitor){
		$sql = "Select dbo.f_generateFileAttachList(";
		$sql .= "'" . $sListIdDoc . "'";
		$sql .= "'" . $sRecordArchiveId . "'";
		$sql .= ",'" . $sDocRelateId . "'";
		$sql .= ",'" . $sdelimitor . "'";
		$sql .= ") As C_FILE_NAME_LIST";
		//echo $sql . '<br>';
		try {						
			$arrResult = $this->adodbQueryDataInNameMode($sql);					
		}catch (Exception $e){
			echo $e->getMessage();
		};				
		return $arrResult;		
	}
	/**
	 * Y nghia: ham lay danh sach id van ban trong danh sach ho so luu tru
	 *
	 * @param unknown_type $pReceiveDocumentId
	 * @param unknown_type $pFileTyle
	 * @param unknown_type $pTableObject
	 * @return unknown
	 */
	public function fgenerateDocIdList($sListRecordId){
		$sql = "Select dbo.f_generateDocIdList('" . $sListRecordId . "') As C_DOC_ID_LIST";
		//echo $sql . '<br>';
		try {						
			$arrResult = $this->adodbQueryDataInNameMode($sql);					
		}catch (Exception $e){
			echo $e->getMessage();
		};				
		return $arrResult;		
	}
	/**
	 * Y nghia: lay danh sach doc id trong danh sach record id
	 *
	 * @param unknown_type $sListRecordId
	 * @return unknown
	 */
	public function DocRecordArchiveDocIdGetAllInRecord($sListRecordId){
		$sql = "Exec dbo.Doc_RecordArchiveDocIdGetAllInRecord '" . $sListRecordId . "'";
		//echo $sql . '<br>'; exit;
		try {						
			$arrResult = $this->adodbQueryDataInNameMode($sql);					
		}catch (Exception $e){
			echo $e->getMessage();
		};				
		return $arrResult;		
	}
	public function DocRecordArchiveFileNameInDoc($sListIdDoc, $sDocType, $sTableName, $sdelimitor){
		$sql = "Exec Doc_RecordArchiveFileNameInDoc ";
		$sql .= "'" . $sListIdDoc . "'";
		$sql .= ",'" . $sDocType . "'";
		$sql .= ",'" . $sTableName . "'";
		$sql .= ",'" . $sdelimitor . "'";
		//echo $sql . '<br>'; exit;
		try{
			$arrResult = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};		
		return $arrResult;
	}
	/**
	 * Y nghia: xoa file dinh kem
	 *
	 * @param unknown_type $sql
	 * @param unknown_type $arrTempResult
	 * @param unknown_type $conn
	 * @param unknown_type $sql
	 * @param unknown_type $sql
	 */
	public function deleteFileUpload($fileNameList){
		$sql = "Exec [_deleteFileUpload] '" . $fileNameList . "'";
		try {			
			$arrTempResult = $this->adodbExecSqlString($sql); 
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
}
?>
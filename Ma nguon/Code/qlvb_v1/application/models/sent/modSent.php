<?php

class Sent_modSent extends Sys_DB_Connection {
	public function docSentGetAll($sFromDate,$sToDate,$sFullTextSearch,$sStatus,$iUnitId,$piCurrentPage,$piNumRowOnPage){
		$sql = "Doc_DocSentGetAll ";
		$sql = $sql . "'" . $sFromDate . "'";
		$sql = $sql . ",'" . $sToDate . "'";
		$sql = $sql . ",'" . $sFullTextSearch . "'";
		$sql = $sql . ",'" . $sStatus . "'";
		$sql = $sql . ",'" . $iUnitId . "'";
		$sql = $sql . ",'" . $piCurrentPage . "'";
		$sql = $sql . ",'" . $piNumRowOnPage . "'";
		//echo $sql; //exit;
		try{
			$arrSent = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrSent;
	}	
	public function getPropertiesDocument($code){
		$sql = "SysLib_ListGetAllbyCode ";
		$sql = $sql . "'" . $code . "', ''";
		//echo $sql . '<br>';
		try {
			$arrSel = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrSel;
	}
	public function _getDepartment($month = '', $year = ''){
		$sql = "Doc_getAllDepartment ";
		$sql = $sql . " '" . $month . "'";
		$sql = $sql . " ,'" . $year . "'";
		//echo '<br>' . $sql . '<br>';
		try {
			$arrSel = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrSel;
	}
	public function _getAllUnitName($month, $year, $signType){
		$sql = "Exec [Doc_Sent_getAllUnitName] ";
		$sql = $sql . " '" . $month . "'";
		$sql = $sql . " ,'" . $year . "'";
		$sql = $sql . " ,'" . $signType . "'";
		//echo $sql;
		try {
			$arrSel = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrSel;
	}
	public function _getAllSingerName($signType, $month, $year){
		$sql = "Exec [Doc_Sent_getAllSignerName] ";
		$sql = $sql . " '" . $signType . "'";
		$sql = $sql . " ,'" . $month . "'";
		$sql = $sql . " ,'" . $year . "'";
		//echo $sql;
		try {
			$arrSel = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrSel;
	}
	public function getDetail($code,$xmlTag){
		$sql = "Doc_getDetailForGroupDocument ";
		$sql = $sql . " '" . $code . "'";
		$sql = $sql . " ,'" . $xmlTag . "'";
		//echo $sql; exit;
		try {			
			$arrDetail = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrDetail;
	}
	/**
	*  
	* Ngay tao: 05/06/2010
	* Y nghia:them moi, sua VB di
	*/
	public function docSentUpdate($arrParameter){
		$psSql = "Exec Doc_DocSentUpdate ";	
		$psSql .= "'"  . $arrParameter['PK_SENT_DOCUMENT'] . "'";
		$psSql .= ",'" . $arrParameter['C_TEXT_BOOK'] . "'";
		$psSql .= ",'" . $arrParameter['C_SENT_DATE'] . "'";
		$psSql .= ",'" . $arrParameter['C_DOC_TYPE'] . "'";
		$psSql .= ",'" . $arrParameter['C_NUMBER'] . "'";
		$psSql .= ",'" . $arrParameter['C_SYMBOL'] . "'";			
		$psSql .= ",'" . $arrParameter['C_TEXT_OF_EMERGENCY'] . "'";
		$psSql .= ",'" . $arrParameter['C_NATURE'] . "'";
		$psSql .= ",'" . $arrParameter['C_XML_DATA'] . "'";
		$psSql .= ",'" . $arrParameter['C_SUBJECT'] . "'";
		$psSql .= ",'" . $arrParameter['C_RECEIVE_PLACE'] . "'";
		$psSql .= ",'" . $arrParameter['FK_SIGNER'] . "'";
		$psSql .= ",'" . $arrParameter['C_SIGNER_POSITION_NAME'] . "'";
		$psSql .= ",'" . $arrParameter['C_DOC_CATE'] . "'";
		$psSql .= ",'" . $arrParameter['C_STATUS'] . "'";
		$psSql .= ",'" . $arrParameter['FK_UNIT_TAOVB'] . "'";
		$psSql .= ",'" . $arrParameter['FK_UNIT_SOANTHAO'] . "'";
		$psSql .= ",'" . $arrParameter['C_UNIT_NAME'] . "'";
		$psSql .= ",'" . $arrParameter['FK_STAFF'] . "'";
		$psSql .= ",'" . $arrParameter['C_STAFF_POSITION_NAME'] . "'";
		$psSql .= ",'" . $arrParameter['C_FILE_NAME'] . "'";	
		$psSql .= ",'" . $arrParameter['C_DOC_TYPE_ID'] . "'";
		$psSql .= ",'" . $arrParameter['C_OWNER_NAME'] . "'";
		//Thuc thi lenh SQL		
		//echo $psSql; exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($psSql) ; 
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrTempResult;		
	}	
	public function docSentGetSingle($sentID){
		$arrResult = null;
		$sql = "Exec Doc_DocSentGetSingle";
		$sql .= "'" . $sentID . "'";
		//echo $sql;
		try{
			$arrResult = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrResult;
	}
	public function docSentDelete($sListId ){
		// Bien luu trang thai
		$sql = "Exec Doc_DocSentDelete '" . $sListId . "'";	
		//echo $sql;exit;
		// thuc hien cap nhat du lieu vao csdl
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
	* Ngay tao: 30/06/2010
	* Y nghia:Cap nhat trang thai cho VB CHO_DANG_KY
	*/
	public function docRegistrationStatusUpdate($sListId,$sStatus ){
		// Bien luu trang thai
		$sql = "Exec Doc_DocRegistrationStatusUpdate  '" . $sListId . "','".$sStatus."'";	
		// thuc hien cap nhat du lieu vao csdl
		try {			
			$arrTempResult = $this->adodbExecSqlString($sql) ; 			
			$Result= $arrTempResult['RET_ERROR'];
		}catch (Exception $e){
			echo $e->getMessage();
		};				
		return $Result;	
	}
	public function _uploadFile($fileList, $table, $documentId){
		$sql = "Exec doc_upload_file '" . $fileList . "','" . $table . "','" . $documentId . "'";
		//echo $sql; exit;
		try{
			$arrFile = $this->adodbExecSqlString($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrFile;
	}
	public function _getAllEveryThing($sp, $month, $year){
		$sql = "Exec " . $sp;
		$sql = $sql . " '" . $month . "'";
		$sql = $sql . ",'" . $year . "'";
		//echo $sql;
		try {
			$arrSel = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrSel;
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
	/**
	*  
	* Ngay tao: 24/06/2010
	* Y nghia:Lấy list danh sách VB đi da ban hanh hoac cho ban hanh
	*/
	public function docRegistrationGetAll($sFullTextSearch,$sStatus,$iUnitId,$iDepartmentId,$iUserId,$piCurrentPage,$piNumRowOnPage){
		$sql = "Doc_DocRegistrationGetAll ";
		$sql = $sql . " '" . $sFullTextSearch . "'";
		$sql = $sql . ",'" . $sStatus . "'";
		$sql = $sql . ",'" . $iUnitId . "'";
		$sql = $sql . ",'" . $iDepartmentId . "'";
		$sql = $sql . ",'" . $iUserId . "'";
		$sql = $sql . ",'" . $piCurrentPage . "'";
		$sql = $sql . ",'" . $piNumRowOnPage . "'";
		//echo $sql; 
		try{
			$arrSent = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrSent;
	}
	/**
	*  
	* Ngay tao: 07/07/2010
	* Y nghia:Lấy list danh sách VB du thao
	*/
	public function docDraftGetAll($sFullTextSearch,$sStatus,$iOwnerId,$iDepartmentId,$iUserId,$piCurrentPage,$piNumRowOnPage){
		$sql = "Doc_DocDraftGetAll ";
		$sql = $sql . " '" . $sFullTextSearch . "'";
		$sql = $sql . ",'" . $sStatus . "'";
		$sql = $sql . ",'" . $iOwnerId . "'";
		$sql = $sql . ",'" . $iDepartmentId . "'";
		$sql = $sql . ",'" . $iUserId . "'";
		$sql = $sql . ",'" . $piCurrentPage . "'";
		$sql = $sql . ",'" . $piNumRowOnPage . "'";
		//echo $sql; 
		try{
			$arrDocDraff = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrDocDraff;
	}

	/**
	*  
	* Ngay tao: 07/07/2010
	* Y nghia:Lấy list danh sách VB di hoac den lien quan
	*/
	public function docRelateGetAll($sPkId,$sDocReceivedIdListTemp,$sDocSentIdListTemp){
		
		$sql = "Doc_DocRelateGetAll ";
		$sql = $sql . " '" . $sPkId . "'";
		$sql = $sql . ",'" . $sDocReceivedIdListTemp . "'";	
		$sql = $sql . ",'" . $sDocSentIdListTemp . "'";		
		//echo  "<br>". $sql . "<br>"; 
		//exit;
		try{
			$arrSent = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrSent;
	}	
	/**
	*  
	* Ngay tao: 07/07/2010
	* Y nghia:them moi, sua VB du thao
	*/
	public function docDraftUpdate($arrParameter){
		$psSql = "Exec Doc_DocDraftUpdate ";	
		$psSql .= "'"  . $arrParameter['PK_SENT_DOCUMENT'] . "'";
		$psSql .= ",'" . $arrParameter['C_DOC_TYPE'] . "'";
		$psSql .= ",'" . $arrParameter['C_SENT_DATE'] . "'";
		$psSql .= ",'" . $arrParameter['C_SUBJECT'] . "'";
		$psSql .= ",'" . $arrParameter['C_DOC_CATE'] . "'";
		$psSql .= ",'" . $arrParameter['C_NATURE'] . "'";			
		$psSql .= ",'" . $arrParameter['C_TEXT_OF_EMERGENCY'] . "'";
		$psSql .= ",'" . $arrParameter['C_XML_DATA'] . "'";
		$psSql .= ",'" . $arrParameter['C_RECEIVE_PLACE'] . "'";
		$psSql .= ",'" . $arrParameter['FK_SIGNER'] . "'";
		$psSql .= ",'" . $arrParameter['C_SIGNER_POSITION_NAME'] . "'";
		$psSql .= ",'" . $arrParameter['C_RECEIVE_LIST_ID'] . "'";
		$psSql .= ",'" . $arrParameter['C_SENT_LIST_ID'] . "'";
		$psSql .= ",'" . $arrParameter['C_RELATE_LIST_ID'] . "'";
		$psSql .= ",'" . $arrParameter['C_NOTE'] . "'";
		$psSql .= ",'" . $arrParameter['FK_UNIT_TAOVB'] . "'";
		$psSql .= ",'" . $arrParameter['FK_UNIT_SOANTHAO'] . "'";
		$psSql .= ",'" . $arrParameter['C_UNIT_NAME'] . "'";
		$psSql .= ",'" . $arrParameter['FK_STAFF'] . "'";
		$psSql .= ",'" . $arrParameter['C_STAFF_POSITION_NAME'] . "'";
		$psSql .= ",'" . $arrParameter['C_IDEA_UNIT_ID'] . "'";
		$psSql .= ",'" . $arrParameter['C_IDEA_UNIT_NAME'] . "'";	
		$psSql .= ",'" . $arrParameter['C_IDEA_STAFT_ID'] . "'";
		$psSql .= ",'" . $arrParameter['C_IDEA_STAFT_NAME'] . "'";
		$psSql .= ",'" . $arrParameter['C_FILE_NAME'] . "'";
		$psSql .= ",'" . $arrParameter['C_STATUS'] . "'";
		$psSql .= ",'" . $arrParameter['C_DELIMITOR'] . "'";
		$psSql .= ",'" . $arrParameter['C_APPOINTED_DATE'] . "'";
		$psSql .= ",'" . $arrParameter['C_IDEA_STAFT_FKUNIT'] . "'";
		//Thuc thi lenh SQL		
		//echo $psSql; exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($psSql) ; 
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrTempResult;		
	}
	/**
	*  
	* Ngay tao: 09/07/2010
	* Y nghia:xem chi tiet mot VB du thao
	*/
	public function docDraftGetSingle($sentID,$iStaff,$iDepartmentId){
		$arrResult = null;
		$sql = "Exec Doc_DocDraftGetSingle ";
		$sql .= "'" . $sentID . "'";
		$sql .= ",'" . $iStaff . "'";
		$sql .= ",'" . $iDepartmentId . "'";
		//echo $sql;
		try{
			$arrResult = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrResult;
	}
	/**
	*  
	* Ngay tao: 21/07/2010
	* Y nghia:Lấy list danh sách VB DU THAO cho/da phan cong
	*/
	public function docDraftAssignGetAll($iOwnerId,$iDepartmentId,$sFullTextSearch,$sStatus,$sProcessStatus,$iPage,$iNumberRecordPerPage){
		
		$sql = "Doc_DocDraftAssignGetAll ";
		$sql = $sql . " '" . $iOwnerId . "'";
		$sql = $sql . ",'" . $iDepartmentId . "'";	
		$sql = $sql . ",'" . $sFullTextSearch . "'";		
		$sql = $sql . ",'" . $sStatus . "'";	
		$sql = $sql . ",'" . $sProcessStatus . "'";		
		$sql = $sql . ",'" . $iPage . "'";	
		$sql = $sql . ",'" . $iNumberRecordPerPage . "'";	
		//echo  "<br>". $sql . "<br>"; 
		//exit;
		try{
			$arrSent = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrSent;
	}	
	/**
	*  
	* Ngay tao: 22/07/2010
	* Y nghia:Cap nhat thong tin phan cong xu ly vai tro LANH DAO PHONG
	*/
	public function docDocDraftAssignUpdate($arrParameter){
		$psSql = "Exec Doc_DocDraftAssignUpdate  ";	
		$psSql .= "'"  . $arrParameter['PK_SENT_DOCUMENT'] . "'";
		$psSql .= ",'" . $arrParameter['C_DEPARTMENT_ID'] . "'";
		$psSql .= ",'" . $arrParameter['C_ASSIGNED_DATE'] . "'";
		$psSql .= ",'" . $arrParameter['C_ASSIGNED_IDEA'] . "'";
		$psSql .= ",'" . $arrParameter['C_STAFF_PROCESS_MAIN_ID'] . "'";
		$psSql .= ",'" . $arrParameter['C_STAFF_PROCESS_MAIN_NAME'] . "'";			
		$psSql .= ",'" . $arrParameter['C_STAFF_PROCESS_COORDINATE_ID_LIST'] . "'";
		$psSql .= ",'" . $arrParameter['C_STAFF_PROCESS_COORDINATE_NAME_LIST'] . "'";
		$psSql .= ",'" . $arrParameter['C_APPOINTED_DATE'] . "'";
		$psSql .= ",'" . $arrParameter['C_UNIT_NAME'] . "'";
		$psSql .= ",'" . $arrParameter['FK_LEADER_UNIT'] . "'";
		$psSql .= ",'" . $arrParameter['C_LEADER_UNIT_POSITION_NAME'] . "'";
		//Thuc thi lenh SQL		
		//echo $psSql; exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($psSql) ; 
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrTempResult;		
	}
		/**
	*  
	* Ngay tao: 22/07/2010
	* Y nghia:Lay thong tin VB du thao Phan cong
	*/
	public function docAssignGetSingle($sentID,$iDepartmentId){
		$arrResult = null;
		$sql = "Exec Doc_DocDraftAssignGetSingle ";
		$sql .= "'" . $sentID . "'";
		$sql .= ",'" . $iDepartmentId . "'";
		//echo $sql;
		try{
			$arrResult = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrResult;
	}
	/**  
	* Ngay tao: 24/07/2010
	* Y nghia: Lay danh sach VB du thao can cap nhat y kien
	*/
	public function docDraftProcessGetAll($sFullTextSearch,$sStatus,$iOwnerId,$iDepartmentId,$iUserId,$sProcessType,$piCurrentPage,$piNumRowOnPage){
		$sql = "Doc_DocDraftProcessGetAll ";
		$sql = $sql . " '" . $sFullTextSearch . "'";
		$sql = $sql . ",'" . $sStatus . "'";	
		$sql = $sql . ",'" . $iOwnerId . "'";	
		$sql = $sql . ",'" . $iDepartmentId . "'";		
		$sql = $sql . ",'" . $iUserId . "'";	
		$sql = $sql . ",'" . $sProcessType . "'";		
		$sql = $sql . ",'" . $piCurrentPage . "'";	
		$sql = $sql . ",'" . $piNumRowOnPage . "'";	
		//echo  "<br>". $sql . "<br>"; 
		//exit;
		try{
			$arrSent = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrSent;		
	}

		/**
	*  
	* Ngay tao: 25/07/2010
	* Y nghia:	Cập nhật kết quả cho ý kiến xử lý VB dự thảo.
	*/
	public  function 	docDocDraftProcessUpdate($arrParameter){
		$psSql = "Exec Doc_DocDraftProcessUpdate  ";	
		$psSql .= "'"  . $arrParameter['PK_SENT_DOCUMENT'] . "'";
		$psSql .= ",'" . $arrParameter['C_WORK_ID'] . "'";
		$psSql .= ",'" . $arrParameter['FK_STAFF'] . "'";
		$psSql .= ",'" . $arrParameter['C_STAFF_POSITION_NAME'] . "'";
		$psSql .= ",'" . $arrParameter['FK_UNIT'] . "'";
		$psSql .= ",'" . $arrParameter['C_UNIT_NAME'] . "'";
		$psSql .= ",'" . $arrParameter['C_WORK_DATE'] . "'";			
		$psSql .= ",'" . $arrParameter['C_RESULT'] . "'";
		$psSql .= ",'" . $arrParameter['C_FILE_NAME'] . "'";
		$psSql .= ",'" . $arrParameter['C_PROCESS_STATUS'] . "'";
		//Thuc thi lenh SQL		
		//echo $psSql; exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($psSql) ; 
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrTempResult;		
	}
	/**
	*  
	* Ngay tao: 26/07/2010
	* Y nghia:Lấy thông tin kết quả dự thảo
	*/
	public function docDraftProcessGetSingle($sDocWorkId){
		$arrResult = null;
		$sql = "Exec Doc_DocDraftProcessGetSingle ";
		$sql .= "'" . $sDocWorkId . "'";
		//echo $sql;exit;
		try{
			$arrResult = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrResult;
	}
	/**
	*  
	* Ngay tao: 26/07/2010
	* Y nghia:Lấy Danh sach qua trinh xu ly VB du thao
	*/
	public function docDraftProcessProgressGetAll($sDocDraftId,$iDepartmentId){
		$arrResult = null;
		$sql = "Exec Doc_DocDraftProcessProgressGetAll ";
		$sql .= "'" . $sDocDraftId . "'";
		$sql .= ",'" . $iDepartmentId . "'";
		//echo $sql;//exit;
		try{
			$arrResult = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrResult;
	}
	/**
	*  
	* Ngay tao: 26/07/2010
	* Y nghia:xoa danh sach VB cong viec lien quan den ID VB du thao xin y kien
	*/
	public function docDraftProcessDelete($sListId ){
		// Bien luu trang thai
		$sql = "Exec Doc_DocDraftProcessDelete '" . $sListId . "'";	
		//echo $sql;exit; 
		// thuc hien cap nhat du lieu vao csdl
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
	* Ngay tao: 26/07/2010
	* Y nghia:Lay trang thai VB du thao xin y kien
	*/
	public function docDraftProcessStatus($sDocDraftId,$iDepartmentId ){
		$arrResult = null;
		// Bien luu trang thai
		$sql = "Exec Doc_DocDraftProcessStatus '" . $sDocDraftId . "'";	
		$sql .= ",'" . $iDepartmentId . "'";
		//echo $sql;
		// thuc hien cap nhat du lieu vao csdl
		try{
			$arrResult = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrResult;
	}
	
	/**
	*  
	* Ngay tao: 04/08/2010
	* Y nghia:	Cập nhật noi dung trinh ky/ phe duyet
	*/
	public  function 	docDraffSubmitOrderUpdate($arrParameter){
		$psSql = "Exec Doc_DocDraffSubmitOrderUpdate  ";	
		$psSql .= "'"  . $arrParameter['PK_SENT_DOCUMENT'] . "'";
		$psSql .= ",'" . $arrParameter['PK_DOC_WORK'] . "'";
		$psSql .= ",'" . $arrParameter['C_WORK_DATE'] . "'";
		$psSql .= ",'" . $arrParameter['C_SUBMIT_CONTENT'] . "'";
		$psSql .= ",'" . $arrParameter['C_FILE_NAME'] . "'";
		$psSql .= ",'" . $arrParameter['FK_STAFF'] . "'";
		$psSql .= ",'" . $arrParameter['C_STAFF_POSITION_NAME'] . "'";			
		$psSql .= ",'" . $arrParameter['FK_UNIT'] . "'";
		$psSql .= ",'" . $arrParameter['C_UNIT_NAME'] . "'";
		$psSql .= ",'" . $arrParameter['C_STATUS'] . "'";
		$psSql .= ",'" . $arrParameter['FK_LEADER'] . "'";
		$psSql .= ",'" . $arrParameter['C_LEADER_POSITION_NAME'] . "'";
		//Thuc thi lenh SQL		
		//echo $psSql; exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($psSql) ; 
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrTempResult;		
	}
	/**
	 * phuongtt
	 * Y nghia: Ham tra cuu van ban quan hoac don vi phat hanh
	 *
	 * @param unknown_type $iUnitId
	 * @param unknown_type $sDocType
	 * @param unknown_type $sDocCate
	 * @param unknown_type $iYear
	 * @param unknown_type $sFullTextSearch
	 * @param unknown_type $iPage
	 * @param unknown_type $iNumberRecordPerPage
	 * @return unknown
	 */
	public function DocSearchSentGetAll($iUnitId, $sDocType, $sDocCate, $iYear, $sFullTextSearch, $iPage, $iNumberRecordPerPage){		
		$sql = "Exec Doc_SearchSentGetAll ";
		$sql = $sql . "'" . $iUnitId . "'";
		$sql = $sql . ",'" . $sDocType . "'";
		$sql = $sql . ",'" . $sDocCate . "'";
		$sql = $sql . ",'" . $iYear . "'";
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
	* Ngay tao: 04/08/2010
	* Y nghia: Lay danh sach VB CHO DUYET/TRINH KY/DA DUYET
	*/
	public function docDraffSubmitOrderGetAll($sFullTextSearch,$sStatusList,$iOwnerId,$iValue,$iUserId,$piCurrentPage,$piNumRowOnPage){
		$sql = "Doc_DocDraffSubmitOrderGetAll ";
		$sql = $sql . " '" . $sFullTextSearch . "'";
		$sql = $sql . ",'" . $sStatusList . "'";	
		$sql = $sql . ",'" . $iOwnerId . "'";	
		$sql = $sql . ",'" . $iValue . "'";		
		$sql = $sql . ",'" . $iUserId . "'";			
		$sql = $sql . ",'" . $piCurrentPage . "'";	
		$sql = $sql . ",'" . $piNumRowOnPage . "'";	
		//echo  "<br>". $sql . "<br>"; 
		//exit;
		try{
			$arrSent = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrSent;		
	}
	/**  
	* Ngay tao: 05/08/2010
	* Y nghia: Lay ra danh sach qua trinh xu ly VB trinh ky
	*/
	public function docSubmitOrderProgressGetAll($sDocDraftId,$sWorkType){
		$sql = "Doc_DocSubmitOrderProcessGetAll ";
		$sql = $sql . " '" . $sDocDraftId . "'";
		$sql = $sql . ",'" . $sWorkType . "'";	;	
		//echo  "<br>". $sql . "<br>"; 
		//exit;
		try{
			$arrSent = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrSent;		
	}
		/**
	*  
	* Ngay tao: 04/08/2010
	* Y nghia:xem Lay ra thong tin co ban cua  mot VB du thao trinh ky
	*/
	public function docDraffWorkGetsingle($sentID){
		$arrResult = null;
		$sql = "Exec Doc_DocDraffWorkGetsingle ";
		$sql .= "'" . $sentID . "'";
		//echo $sql;exit;
		try{
			$arrResult = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrResult;
	}
	
	/**
	*  
	* Ngay tao: 04/08/2010
	* Y nghia:Lay ra thong tin co ban cua  mot cong viec trinh ky/phe duyet
	*/
	public function docDraffSubmitOrderGetsingle($sentID,$sWorkId){
		$arrResult = null;
		$sql = "Exec Doc_DocDraffSubmitOrderGetsingle ";
		$sql .= "'" . $sentID . "'";
		$sql .= ",'" . $sWorkId . "'";
		//echo $sql;exit;
		try{
			$arrResult = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrResult;
	}
	/**
	 *  
	 * Ngay tao: 09/08/2010 	
	 *  Lay danh sach bao cao 
	 */
	public function docSentListReport($arrParam,$sChecked){
		
		//Tao doi tuong trong trong thu vien dung chung
		$objLib = new Sys_Library();
		//
		$sHtmlRes = '<table class="list_table2"  width="99%" cellpadding="0" cellspacing="0" align="right" style="BORDER-TOP: #BACAD7 1px solid;">
					<col width="5%"><col width="95%">'		;				
		for ($i=0;$i<sizeof($arrParam);$i++) {
			$v_report_code = $arrParam[$i]['C_CODE'];
			$v_report_name =Sys_Publib_Library::_replaceBadChar($arrParam[$i]['C_NAME']);
			//Style
			if ($v_current_style_name == 'odd_row'){
				$v_current_style_name = 'round_row';
			}else{
				$v_current_style_name = 'odd_row';
			}				
			//						
			$v_report_checked = '';
			if ($sChecked!='' && $sChecked == $v_report_code)
			$v_report_checked = 'checked';
			// In danh sach
			$sHtmlRes = $sHtmlRes.'<tr class="'. $v_current_style_name.'">'.
								'<td align="center"><input type="radio" message="Phai xac dinh LOAI BAO CAO!" name="opt_reporttype_id" id="'. $v_report_code .'"  readonly="true"  value="'. $v_report_code.'"' . $v_report_checked.' onClick="btn_rad_onclick(this,document.getElementById(\'hdn_Report_id\'));document.forms[0].submit();"></td>
								<td colspan="10" style="padding-left:5px;"><label style="cursor:pointer;" for ="'.$v_report_code.'">'  .$v_report_name. '</label></td></tr>'; 
			
		}	
		
		$sHtmlRes = $sHtmlRes.'</table>'	;				
		return $sHtmlRes;
		//var_dump($sHtmlRes);
	}
	/**
	 *  
	 * Ngay tao: 09/08/2010 
	 *  Lay ten file xml dung de bao cao
	 */
	
	public function getFileNameXml($arrList,$sListCode){
		
		// Load thu vien xml
		Zend_Loader::loadClass("Sys_Publib_Xml");
		
		// Neu khong co file nao thi lay mac dinh
		$sFileNameXml = ""; 
		
		for ($i=0;$i<sizeof($arrList);$i++){			
			if ($arrList[$i]['C_CODE'] == $sListCode){
				$sFileNameXml =  Sys_Publib_Xml::_xmlGetXmlTagValue($arrList[$i]['C_XML_DATA'],'data_list','xml_file_name');
			}
		}
		return $sFileNameXml;		
	}
	/**
	 * Ngay tao: 09/08/2010 
	 * Lay danh sach nguoi ky theo don vi
	 */
	public function getSignByUnit($code,$arr_all_staff){
		$sql = "SysLib_ListGetAllbyCode ";
		$sql = $sql . "'" . $code . "', ''";
		//echo $sql . '<br>';
		try {
			$arrSel = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		$j = 0; $m = 0;
		for ($i=0;$i<sizeof($arrSel);$i++){	
			for ($m=0;$m<sizeof($arr_all_staff);$m++)	{	
				if ($arrSel[$i]['C_CODE'] == $arr_all_staff[$m]['id']){
					$arrResult[$j]['C_CODE'] = $arrSel[$i]['C_CODE'];
					$arrResult[$j]['C_NAME'] = $arrSel[$i]['C_NAME'];
					$j ++;
					$m = sizeof($arr_all_staff);
				}
			}	
		}
		return $arrResult;
	}	
	public function docSentRecevedOwnerGetAll($sDocSentId, $sDocType = 'VB_DI',$sOption = 'DON_VI'){
		$sql = "Exec [Doc_SentRecevedOwnerGetAll] ";
		$sql = $sql . "'" . $sDocSentId . "'";
		$sql = $sql . ",'" . $sDocType . "'";
		$sql = $sql . ",'" . $sOption . "'";
		//echo $sql; //exit;
		try{
			$arrSent = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrSent;
	}	
	public function DocSentReportVBDI01($sCode,$iOverid,$sName,$sFromDate,$sToDate,$sFromDateConvert,$sToDateConvert,$sNameunit){		
		$sql = "Exec Doc_DocSentReportVBDI01 ";
		$sql = $sql . "'" . $sCode . "'";
		$sql = $sql . ",'" . $iOverid;
		$sql = $sql . "','" . $sName . "'";
		$sql = $sql . ",'" . $sFromDate . "'";	
		$sql = $sql . ",'" . $sToDate . "'";
		$sql = $sql . ",'" . $sFromDateConvert . "'";
		$sql = $sql . ",'" . $sToDateConvert . "'";
		$sql = $sql . ",'" . $sNameunit . "'";
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
	public function DocSentReportVBDI05($sCode,$iOverid,$sName,$sFromDate,$sToDate,$sFromDateConvert,$sToDateConvert,$sNameunit){		
		$sql = "Exec Doc_DocSentReportVBDI05 ";
		$sql = $sql . "'" . $sCode . "'";
		$sql = $sql . ",'" . $iOverid;
		$sql = $sql . "','" . $sName . "'";
		$sql = $sql . ",'" . $sFromDate . "'";	
		$sql = $sql . ",'" . $sToDate . "'";
		$sql = $sql . ",'" . $sFromDateConvert . "'";
		$sql = $sql . ",'" . $sToDateConvert . "'";
		$sql = $sql . ",'" . $sNameunit . "'";
		//echo $sql . '<br>'; //exit;
		try{
			$arrResul = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}		
		return $arrResul;
	}
}?>
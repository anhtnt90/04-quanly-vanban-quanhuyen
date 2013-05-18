<?php
/**
* Nguoi tao: PHUONGTT
* Ngay tao: 08/06/2010
* Y nghia: Xu ly GUI - NHAN VB dien tu
*/

class work_modWork extends Sys_DB_Connection {
	/**
	 * Y nghia: Lay danh sach cong viec
	 *
	 * @param unknown_type $iOwnerId
	 * @param unknown_type $iStaffId
	 * @param unknown_type $iLeaderId
	 * @param unknown_type $sStatus
	 * @param unknown_type $sFromDate
	 * @param unknown_type $sToDate
	 * @param unknown_type $sFullTextSearch
	 * @param unknown_type $iPage
	 * @param unknown_type $iNumberRecordPerPage
	 * @return unknown
	 */
	public function DocDocWorkGetAll($iOwnerId, $iStaffId, $iLeaderId, $sStatus, $sFromDate,$sToDate, $sFullTextSearch, $iPage, $iNumberRecordPerPage){		
		if($sStatus == 'CAN_XU_LY')	$sStatus = 'DANG_XU_LY';
		$sql = "Exec Doc_DocWorkGetAll ";
		$sql = $sql . "'" . $iOwnerId . "'";
		$sql = $sql . ",'" . $iStaffId . "'";
		$sql = $sql . ",'" . $iLeaderId . "'";
		$sql = $sql . ",'" . $sStatus . "'";
		$sql = $sql . ",'" . $sFromDate . "'";
		$sql = $sql . ",'" . $sToDate . "'";
		$sql = $sql . ",'" . $sFullTextSearch . "'";
		$sql = $sql . ",'" . $iPage . "'";
		$sql = $sql . ",'" . $iNumberRecordPerPage . "'";
		$sql = $sql . ",''";
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
 * Enter description here...
 *
 * @param unknown_type $arrParameter		-- Mang luu cac gia tri truyen vao
 * @return unknown
 */
	public function DocWorkUpdate($arrParameter){
		$psSql = "Exec [dbo].[Doc_WorkUpdate] ";	
		$psSql .= "'" . $arrParameter['PK_WORK_MANAGE'] . "'";
		$psSql .= ",'" . $arrParameter['FK_UNIT'] . "'";
		$psSql .= ",'" . $arrParameter['C_LEADER'] . "'";
		$psSql .= ",'" . $arrParameter['C_LEADER_POSITION_NAME'] . "'";
		$psSql .= ",'" . $arrParameter['FK_CREATER'] . "'";
		$psSql .= ",'" . $arrParameter['C_CREATER_POSITION_NAME'] . "'";
		$psSql .= ",'" . $arrParameter['C_WORK_CONTENT'] . "'";			
		$psSql .= ",'" . $arrParameter['C_NOTES'] . "'";
		$psSql .= ",'" . $arrParameter['C_APPROVE_DATE'] . "'";
		$psSql .= ",'" . $arrParameter['C_WORK_STATUS'] . "'";
		$psSql .= ",'" . $arrParameter['C_APPOINTED_DATE'] . "'";
		$psSql .= ",'" . $arrParameter['C_STAFF_ID_LIST'] . "'";
		$psSql .= ",'" . $arrParameter['C_STAFF_POSITION_NAME_LIST'] . "'";
		$psSql .= ",'" . $arrParameter['C_UNIT_ID_BY_STAFF'] . "'";
		$psSql .= ",'" . $arrParameter['C_UNIT_ID_LIST'] . "'";
		$psSql .= ",'" . $arrParameter['C_UNIT_NAME_LIST'] . "'";
		$psSql .= ",'" . $arrParameter['C_STATUS_BY_UNIT'] . "'";
		$psSql .= ",'" . $arrParameter['C_XML_DATA'] . "'";
		$psSql .= ",'" . $arrParameter['NEW_FILE_ID_LIST'] . "'";
		//Thuc thi lenh SQL		
		//echo htmlspecialchars($psSql); exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($psSql); 
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrTempResult;		
	}
	
	/**
	 * Phuong thuc lay thong tin chi tiet mot van ban
	 *
	 * @param unknown_type $pSendReceiveDocumentId	id van ban dien tu
	 * @param unknown_type $sDocumentId				id van ban den hoac di
	 * @param unknown_type $sType					loai van ban: VB_DEN or VB_DI
	 * @return Mang
	 */
	public function DocWorkGetSingle($sWorkId){
		$sql = "Exec [dbo].[Doc_DocWorkGetSingle] ";
		$sql .= "'" . $sWorkId . "'";
		//echo $sql . '<br>'; //exit;
		try{
			$arrWork = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};		
		return $arrWork;
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
 * Phuong thuc xoa mot hoac nhieu van ban dien tu
 *
 * @param unknown_type $sSendReceiveDocumentIdList	-- Danh sach id van ban can xoa
 * @param unknown_type $iHasDeleteAllPermission		-- Quyen xoa van ban(1: co; 0: khong)
 * @return unknown
 */
	public function DocWorkDelete($sWorkIdList,$iHasDeleteAllPermission){
		$Result = null;			
		$sql = "Exec Doc_DocWorkDelete ";		
		$sql .= "'".$sWorkIdList ."'";	
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
 * Idea : Phuong Thuc lay danh sach VB DEN 
 *
	* @param  	@iFkUnit					Varchar(20)			-- Id vi su dung.
	* @param	@sFullTextSearch			Nvarchar(100)		-- Tu hoac cum tu can tim kiem
	* @param	@iPage						Int					-- So trang
	* @param	@iNumberRecordPerPage		Int					-- So ban ghi tren mot trang

 * 						
 * @return Mang chua danh sach VB DEN 
 */
	public function DocReceivedGetAll($iFkUnit,$sFullTextSearch, $iPage, $iNumberRecordPerPage, $sListId){		
		$sql = "Exec Doc_SendReceivedDocReceivedGetAll ";
		$sql = $sql . "'" . $iFkUnit . "'";
		$sql = $sql . ",'" . $sFullTextSearch . "'";
		$sql = $sql . ",'" . $iPage . "'";
		$sql = $sql . ",'" . $iNumberRecordPerPage . "'";	
		$sql = $sql . ",'" . $sListId . "'";	
		//echo $sql . '<br>'; exit;
		try{
			$arrResul = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}		
		return $arrResul;
	}
	/**
	 * Phuong thuc lay danh sach van ban di
	 *
	 * @param unknown_type $iFkUnit					-- id don vi su dung
	 * @param unknown_type $sFullTextSearch			-- Cum tu tim kiem
	 * @param unknown_type $iPage					-- Trang hien thoi
	 * @param unknown_type $iNumberRecordPerPage	-- So ban ghi tren trang
	 * @param unknown_type $sListId					-- Danh sach van ban da chon
	 * @return unknown
	 */
	public function DocSendGetAll($iFkUnit,$sFullTextSearch, $iPage, $iNumberRecordPerPage, $sListId){		
		$sql = "Exec Doc_SendReceivedDocSendGetAll ";
		$sql = $sql . "'" . $iFkUnit . "'";
		$sql = $sql . ",'" . $sFullTextSearch . "'";
		$sql = $sql . ",'" . $iPage . "'";
		$sql = $sql . ",'" . $iNumberRecordPerPage . "'";	
		$sql = $sql . ",'" . $sListId . "'";	
		//echo $sql . '<br>'; exit;
		try{
			$arrResul = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}		
		return $arrResul;
	}
	/**
	 * y nghia: Phuong thuc lay van cua can bo dang nhap hien thoi nhan duoc
	 *
	 * @param unknown_type $iCurrentStaffId
	 * @param unknown_type $iOwnerId
	 * @param unknown_type $iCurrentUnitId
	 * @param unknown_type $sFromDate
	 * @param unknown_type $sToDate
	 * @param unknown_type $sFullTextSearch
	 * @param unknown_type $iPage
	 * @param unknown_type $iNumberRecordPerPage
	 * @return unknown
	 */
	public function DocSendReceivedStaffGetAll($iCurrentStaffId,$iOwnerId,$iCurrentUnitId,$sFromDate,$sToDate,$sFullTextSearch, $iPage, $iNumberRecordPerPage, $ioption){		
		$sql = "Exec Doc_SendReceivedStaffGetAll ";
		$sql = $sql . "'" . $iCurrentStaffId . "'";
		$sql = $sql . ",'" . $iOwnerId . "'";
		$sql = $sql . ",'" . $iCurrentUnitId . "'";
		$sql = $sql . ",'" . $sFromDate . "'";	
		$sql = $sql . ",'" . $sToDate . "'";	
		$sql = $sql . ",'" . $sFullTextSearch . "'";
		$sql = $sql . ",'" . $iPage . "'";
		$sql = $sql . ",'" . $iNumberRecordPerPage . "'";
		$sql = $sql . ",'" . $ioption . "'";
		//echo $sql . '<br>'; //exit;
		try{
			$arrResul = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}		
		return $arrResul;
	}
	/**
	 * y nghia: phuong thuc lay thong tin noi nhan cua mot van ban dien tu
	 *
	 * @param unknown_type $pSendReceiveDocumentId
	 * @return unknown
	 */
	public function DocSendReceivedGetReceivedInfo($pSendReceiveDocumentId){		
		$sql = "Exec Doc_SendReceivedGetReceivedInfo ";
		$sql = $sql . "'" . $pSendReceiveDocumentId . "'";
		//echo $sql . '<br>'; //exit;
		try{
			$arrResul = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}		
		return $arrResul;
	}
	public function DocSendReceivedUpdateReceivedInfo($pSendReceiveDocumentId,$iCurrentStaffId,$iOwnerId,$sStaffPositionName,$iCurrentUnitId){		
		$sql = "Exec Doc_SendReceivedUpdateReceivedInfo ";
		$sql = $sql . "'" . $pSendReceiveDocumentId . "'";
		$sql = $sql . ",'" . $iCurrentStaffId . "'";
		$sql = $sql . ",'" . $iOwnerId . "'";
		$sql = $sql . ",'" . $sStaffPositionName . "'";
		$sql = $sql . ",'" . $iCurrentUnitId . "'";
		//echo $sql . '<br>'; //exit;
		try{
			$arrResul = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}		
		return $arrResul;
	}
	/**
	 * Idea : Phuong thuc lay so VB den
	 *
	 * @param unknown_type $pReceiveDocBook
	 * @param unknown_type $pOwnerCode
	 * @return so VB den
	 */
	public function DocReceivedGetNumber($sReceiveDocBook,$iUnitId){
		$sql = "Exec Doc_DocReceivedGetNumber";
		$sql .= "'" . $sReceiveDocBook . "'";
		$sql .= ",'" . $iUnitId . "'";
		//echo $sql . '<br>'; //exit;
		try{
			$arrDocNumber = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};		
		return $arrDocNumber;
	}
	
/**
 * Idea : Phuong them moi hieu chinh mot VB DEN 
 *
 * @param 
 	@sReceiveDocumentId			Varchar(50)			-- ID van ban
	,@iFkUnit					Int					-- Id don vi su dung
	,@sSymbol					Nvarchar(50)		-- So, ky hieu
	,@dReleaseDate				Varchar(30)			-- Ngay phat hanh
	,@sAgentcyGroup				Nvarchar(100)		-- Cap gui 
	,@sAgentcyName				Nvarchar(1000)		-- Noi gui
	,@sDocType					Nvarchar(100)		-- Loai van ban 
	,@sSubject					Nvarchar(2000)		-- Trich yeu van ban
	,@sDocBooks					Varchar(30)			-- So VB
	,@iNumDoc					Int					-- So VB
	,@dReceiveDate				Varchar(30)			-- Ngay den
	,@sNature					Nvarchar(100)		-- Tinh chat van ban
	,@sTextOfEmergency			Nvarchar(100)		-- Do mat van ban
	,@sTypeProcess				Varchar(50)			-- Hinh thuc xu ly
	,@sStatus					Varchar(50)			-- Trang thai xu ly van ban
	,@dAppointedDate			Varchar(30)			-- Han xu ly 
	,@sXmlData					Ntext				-- Luu thong tin khac cua van ban
	,@sNewAttachFileNameList	Nvarchar(1000)		-- Danh sach ten file them moi
 * @return Mang luu VB DEN
 */
	public function DocReceivedUpdate($arrParameter){
		$psSql = "Exec Doc_DocReceivedUpdate ";	
		$psSql .= "'" . $arrParameter['PK_RECEIVED_DOC'] . "'";
		$psSql .= ",'" . $arrParameter['FK_UNIT'] . "'";
		$psSql .= ",'" . $arrParameter['C_SYMBOL'] . "'";
		$psSql .= ",'" . $arrParameter['C_RELEASE_DATE'] . "'";
		$psSql .= ",'" . $arrParameter['C_AGENTCY_GROUP'] . "'";
		$psSql .= ",'" . $arrParameter['C_AGENTCY_NAME'] . "'";			
		$psSql .= ",'" . $arrParameter['C_DOC_TYPE'] . "'";
		$psSql .= ",'" . $arrParameter['C_SUBJECT'] . "'";
		$psSql .= ",'" . $arrParameter['C_TEXT_BOOK'] . "'";
		$psSql .= ",'" . $arrParameter['C_NUM'] . "'";
		$psSql .= ",'" . $arrParameter['C_RECEIVED_DATE'] . "'";
		$psSql .= ",'" . $arrParameter['C_NATURE'] . "'";
		$psSql .= ",'" . $arrParameter['C_TEXT_OF_EMERGENCY'] . "'";
		$psSql .= ",'" . $arrParameter['C_TYPE_PROCESSING'] . "'";
		$psSql .= ",'" . $arrParameter['C_STATUS'] . "'";
		$psSql .= ",'" . $arrParameter['C_APPOINTED_DATE'] . "'";
		$psSql .= ",'" . $arrParameter['C_XML_DATA'] . "'";
		$psSql .= ",'" . $arrParameter['ATTACH_FILE_NAME_LIST'] . "'";
		$psSql .= ",'" . $arrParameter['C_DOC_CATE'] . "'";
		//Thuc thi lenh SQL	
		//echo htmlspecialchars($psSql); exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($psSql) ; 
			$Result = $arrTempResult['NEW_ID'];
		}catch (Exception $e){
			echo $e->getMessage();
		};
		//Return result
		return $Result;		
	}
	/**
	 * Y nghia: Lay danh sach cong viec cua truong phong ban
	 *
	 * @param unknown_type $iOwnerId
	 * @param unknown_type $iStaffId
	 * @param unknown_type $iLeaderId
	 * @param unknown_type $sStatus
	 * @param unknown_type $sFromDate
	 * @param unknown_type $sToDate
	 * @param unknown_type $sFullTextSearch
	 * @param unknown_type $iPage
	 * @param unknown_type $iNumberRecordPerPage
	 * @return unknown
	 */
	public function DocWorkAssignGetAll($iOwnerId, $iStaffId, $iLeaderId, $iUnitId, $sStatus, $sFromDate,$sToDate, $sFullTextSearch, $iPage, $iNumberRecordPerPage){		
		$sql = "Exec Doc_DocWorkAssignGetAll ";
		$sql = $sql . "'" . $iOwnerId . "'";
		$sql = $sql . ",'" . $iStaffId . "'";
		$sql = $sql . ",'" . $iLeaderId . "'";
		$sql = $sql . ",'" . $iUnitId . "'";
		$sql = $sql . ",'" . $sStatus . "'";
		$sql = $sql . ",'" . $sFromDate . "'";
		$sql = $sql . ",'" . $sToDate . "'";
		$sql = $sql . ",'" . $sFullTextSearch . "'";
		$sql = $sql . ",'" . $iPage . "'";
		$sql = $sql . ",'" . $iNumberRecordPerPage . "'";
		//echo '<br>'.$sql . '<br>'; //exit;
		try{
			$arrResul = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrResul;
	}
	/**
	 * Cap nhat thong tin phan cong xu ly cong viec
	 *
	 * @param unknown_type $sWorkId
	 * @param unknown_type $iUnitId
	 * @param unknown_type $sIdea
	 * @param unknown_type $sAssignedDate
	 * @param unknown_type $sAppointedDate
	 * @param unknown_type $sStaffIdList
	 * @param unknown_type $sStaffPostionNameList
	 * @return unknown
	 */
	public function DocWorkAssignUpdate($sWorkId, $iUnitId,$iLeaderId,$sLeaderPositionName,$sIdea, $sAssignedDate, $sAppointedDate, $sStaffIdList,$sStaffPostionNameList){
		$sql = "Exec [dbo].[Doc_DocWorkAssignUpdate] ";	
		$sql = $sql . "'" . $sWorkId . "'";
		$sql = $sql . ",'" . $iUnitId . "'";
		$sql = $sql . ",'" . $iLeaderId . "'";
		$sql = $sql . ",'" . $sLeaderPositionName . "'";
		$sql = $sql . ",'" . $sIdea . "'";
		$sql = $sql . ",'" . $sAssignedDate . "'";
		$sql = $sql . ",'" . $sAppointedDate . "'";
		$sql = $sql . ",'" . $sStaffIdList . "'";
		$sql = $sql . ",'" . $sStaffPostionNameList . "'";
		//Thuc thi lenh SQL		
		//echo '<br>'.$sql; exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($sql); 
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrTempResult;		
	}
	/**
	 * Lay thong tin phan cong xu ly cong viec
	 *
	 * @param unknown_type $sWorkId
	 * @param unknown_type $iUnitId
	 * @return unknown
	 */
	public function DocWorkAssignGetSingle($sWorkId,$iUnitId){
		$sql = "Exec [dbo].[Doc_DocWorkAssignGetSingle] ";
		$sql .= "'" . $sWorkId . "'";
		$sql .= ",'" . $iUnitId . "'";
		//echo $sql . '<br>'; //exit;
		try{
			$arrWork = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};		
		return $arrWork;
	}
	/**
	 * Lay danh sach cong viec can xu ly hoac da xu ly
	 *
	 * @param unknown_type $iOwnerId
	 * @param unknown_type $iStaffId
	 * @param unknown_type $iLeaderId
	 * @param unknown_type $iUnitId
	 * @param unknown_type $sStatus
	 * @param unknown_type $sFromDate
	 * @param unknown_type $sToDate
	 * @param unknown_type $sFullTextSearch
	 * @param unknown_type $iPage
	 * @param unknown_type $iNumberRecordPerPage
	 * @return unknown
	 */
	public function DocWorkProcessGetAll($iOwnerId, $iStaffId, $iLeaderId, $iUnitId, $sStatus, $sFromDate,$sToDate, $sFullTextSearch, $iPage, $iNumberRecordPerPage){		
		$sql = "Exec Doc_DocWorkProcessGetAll ";
		$sql = $sql . "'" . $iOwnerId . "'";
		$sql = $sql . ",'" . $iStaffId . "'";
		$sql = $sql . ",'" . $iLeaderId . "'";
		$sql = $sql . ",'" . $iUnitId . "'";
		$sql = $sql . ",'" . $sStatus . "'";
		$sql = $sql . ",'" . $sFromDate . "'";
		$sql = $sql . ",'" . $sToDate . "'";
		$sql = $sql . ",'" . $sFullTextSearch . "'";
		$sql = $sql . ",'" . $iPage . "'";
		$sql = $sql . ",'" . $iNumberRecordPerPage . "'";
		//echo '<br>'.$sql . '<br>'; //exit;
		try{
			$arrResul = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrResul;
	}
	/**
	 * Lay Thong tin tien do xu ly cua mot cong viec
	 *
	 * @param unknown_type $sWorkId
	 * @return unknown
	 */
	public function DocWorkProcessResultGetAll($sWorkId){		
		$sql = "Exec Doc_DocWorkProcessResultGetAll ";
		$sql = $sql . "'" . $sWorkId . "'";
		//echo '<br>'.$sql . '<br>'; //exit;
		try{
			$arrResul = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrResul;
	}
	/**
	 * Y nghia: cap nhat thong tin xu ly cong viec
	 *
	 * @param unknown_type $arrParameter
	 * @return unknown
	 */
	public function DocWorkProcessUpdate($arrParameter){
		$psSql = "Exec [dbo].[Doc_DocWorkProcessUpdate] ";	
		$psSql .= "'" . $arrParameter['PK_DOC_WORK'] . "'";
		$psSql .= ",'" . $arrParameter['FK_DOC'] . "'";
		$psSql .= ",'" . $arrParameter['FK_UNIT'] . "'";
		$psSql .= ",'" . $arrParameter['C_UNIT_NAME'] . "'";
		$psSql .= ",'" . $arrParameter['FK_STAFF'] . "'";
		$psSql .= ",'" . $arrParameter['C_STAFF_POSITION_NAME'] . "'";
		$psSql .= ",'" . $arrParameter['C_WORK_DATE'] . "'";			
		$psSql .= ",'" . $arrParameter['C_RESULT'] . "'";
		$psSql .= ",'" . $arrParameter['C_PROCESS_STATUS'] . "'";
		$psSql .= ",'" . $arrParameter['NEW_FILE_ID_LIST'] . "'";
		//Thuc thi lenh SQL		
		//echo htmlspecialchars($psSql); exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($psSql); 
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrTempResult;		
	}
	/**
	 * Ham xoa mot hay nhieu tien do cong viec
	 *
	 * @param unknown_type $sWorkProcessIdList
	 * @param unknown_type $iHasDeleteAllPermission
	 * @return unknown
	 */
	public function DocWorkProcessDelete($sWorkProcessIdList,$iHasDeleteAllPermission){
		$Result = null;			
		$sql = "Exec Doc_DocWorkProcessDelete ";		
		$sql .= "'".$sWorkProcessIdList ."'";	
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
}
?>
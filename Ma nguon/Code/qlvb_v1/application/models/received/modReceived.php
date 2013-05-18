<?php

/**
 * Nguoi tao: phongtd
 * Ngay tao: 08/06/2010
 * Y nghia: Lop xu ly VB den
 */

class received_modReceived extends Sys_DB_Connection {
		
/**
 * Idea : Phuong lay danh sach VB DEN 
 *
	* @param  	@iFkUnit					Varchar(20)			-- Id vi su dung.
	* @param	@sFromDate					Varchar(30)			-- Tu ngay
	* @param	@sToDate					Varchar(30)			-- Den ngay
	* @param	@sFullTextSearch			Nvarchar(100)		-- Tu hoac cum tu can tim kiem
	* @param	@iPage						Int					-- So trang
	* @param	@iNumberRecordPerPage		Int					-- So ban ghi tren mot trang

 * 						
 * @return Mang chua danh sach VB DEN 
 */
	public function DocReceivedGetAll($iFkUnit,$sFromDate,$sToDate,$sFullTextSearch, $iPage, $iNumberRecordPerPage){		
		$sql = "Exec Doc_DocReceivedGetAll ";
		$sql = $sql . "'" . $iFkUnit . "'";
		$sql = $sql . ",'" . $sFromDate . "'";
		$sql = $sql . ",'" . $sToDate . "'";
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
 * Idea : Lay thong tin danh muc 
 *
	* @param  	@code					Varchar(50)			-- Ma loai danh muc
 * 						
 * @return Mang chua danh sach doi tuong loai danh muc
 */
	public function getPropertiesDocument($code,$sXml,$sValueNotGet){
		$sql = "SysLib_ListGetAllbyCode ";
		$sql = $sql . "'" . $code . "'";
		$sql = $sql . ",'" . $sXml . "'";
		$sql = $sql . ",'" . $sValueNotGet . "'";
		//echo '$sql'.$sql;
		try {
			$arrSel = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrSel;
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
	 * Idea : Phuong thuc lay thong tin mot VB DEN 
	 *
	 * @param varchar(50) $sReceiveDocumentId
	 * @return Mang chua thong tin mot VB DEN
	 */
	public function DocReceivedGetSingle($sReceiveDocumentId,$sUnitId){
		$sql = "Exec Doc_DocReceivedGetSingle ";
		$sql .= "'" . $sReceiveDocumentId . "'";
		$sql .= ",'" . $sUnitId . "'";
		//echo $sql . '<br>'; exit;
		try{
			$arrReceived = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};		
		return $arrReceived;
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
	 * Idea : Phuong Xoa thong tin mot hoac nhieu VB DEN 
	 *
	 * @param Nvarchar(4000) $pReceiveDocumentIdList
	 * @param Smallint = 0 $piHasDeleteAllPermission
	 * @return unknown
	 */
	public function DocReceivedDelete($sReceiveDocumentIdList,$iHasDeleteAllPermission){
		$Result = null;			
		$sql = "Exec Doc_DocReceivedDelete ";		
		$sql .= "'".$sReceiveDocumentIdList ."'";	
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
	public function getDetail($code,$xmlTag){
		$sql = "Doc_getDetailForGroupDocument ";
		$sql = $sql . " '" . $code . "'";
		$sql = $sql . " ,'" . $xmlTag . "'";
		//echo $sql;
		try {			
			$arrDetail = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrDetail;
	}
	/**
	 * Idea : Phuong thuc cap nhat thong tin phan phoi, phan cong xu ly VB den
	 *
	 * @param  
	 	 	@sReceiveDocumentId				Uniqueidentifier			-- ID van ban.	
			,@dImplementationDate			Varchar(30)					-- Ngay thuc hien
			,@sLeaderOfficeIdea				Nvarchar(1000)				-- Y kien Lanh dao van phong	
			,@sLeaderIdList					Varchar(100)				-- Danh sach Id Lanh dao	
			,@sLeaderPositionNameList		Nvarchar(2000)				-- Danh sach ten + chuc vu cua lanh dao	
			,@sLeaderIdeaList				Nvarchar(2000)				-- Danh sach y kien Lanh dao	
			,@sProcessUnitIdList			Varchar(100)				-- Danh sach Id phong ban xu ly		
			,@sProcessUnitNameList			Nvarchar(2000)				-- Danh sach ten phong ban	
			,@sProcessStaffIdList			Varchar(100)				-- Danh sach Id can bo xu ly	
			,@sProcessStaffNameList			Nvarchar(2000)				-- Danh sach ten + Chuc vu can bo xu ly VB	
			,@dAppointedDate				Varchar(30)					-- Han xu ly 
			,@sStatus						Varchar(50)					-- Trang thai xu ly van ban
			,@sDelimitor					Varchar(20) = ','			-- Ky ty phan tach giua cac ky tu	 
	 * @return Mang luu thong tin thong tin PHAN PHOI, PHAN CONG XU LY
	 */
	public function DocDistributionAssign($arrParameter){
		$psSql = "Exec Doc_DocDistributionAssign";	
		$psSql .= "'" . $arrParameter['FK_RECEIVED_DOC'] . "'";	
		$psSql .= ",'" . $arrParameter['C_PROCESSION_DATE'] . "'";	
		$psSql .= ",'" . $arrParameter['C_LEADER_OFFICE_IDEA'] . "'";	
		$psSql .= ",'" . $arrParameter['FK_LEADER_ID_LIST'] . "'";
		$psSql .= ",'" . $arrParameter['C_LEADER_POSITION_NAME_LIST'] . "'";
		$psSql .= ",'" . $arrParameter['C_IDEA_LIST'] . "'";
		$psSql .= ",'" . $arrParameter['C_UNIT_ID_LIST'] . "'";
		$psSql .= ",'" . $arrParameter['C_UNIT_NAME_LIST'] . "'";
		$psSql .= ",'" . $arrParameter['C_STAFF_ID_LIST'] . "'";
		$psSql .= ",'" . $arrParameter['C_STAFF_POSITION_NAME_LIST'] . "'"; 
		$psSql .= ",'" . $arrParameter['C_UNIT_BY_STAFF_ID_LIST'] . "'";
		$psSql .= ",'" . $arrParameter['C_PROCESS_STATUS_UNIT_LIST'] . "'";
		$psSql .= ",'" . $arrParameter['C_APPOINTED_DATE'] . "'";
		$psSql .= ",'" . $arrParameter['C_DELIMITOR'] . "'";
		//Thuc thi lenh SQL		
		//echo htmlspecialchars($psSql); exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($psSql) ; 
			$Result = $arrTempResult['RET_ERROR'];
		}catch (Exception $e){
			echo $e->getMessage();
		};
		//Return result
		return $Result;		
	}
	
	/**
	 * Idea : Lay danh sach VB den cho phan phoi, da phan phoi 
	 *
	* @param  	@iFkUnit					Varchar(20)			-- Id vi su dung.
				,@sFullTextSearch			Nvarchar(100)		-- Tu hoac cum tu can tim kiem
				,@sStatus					Varchar(50)			-- Trang thai van ban
				,@iPage						Int					-- So trang
				,@iNumberRecordPerPage		Int					-- So ban ghi tren mot trang
	
	 * 						
	 * @return Mang chua danh sach VB DEN 
	 */
	public function DocReceivedDistributionGetAll($iFkUnit,$sFullTextSearch, $sStatus,$iPage, $iNumberRecordPerPage){		
		$sql = "Exec Doc_DocReceivedDistributionGetAll ";
		$sql = $sql . "'" . $iFkUnit . "'";
		$sql = $sql . ",'" . $sFullTextSearch . "'";
		$sql = $sql . ",'" . $sStatus . "'";
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
	 * Idea : Phuong thuc cap nhat thong tin phan phoi VB den
	 *
	 * @param 
	 	 	@sReceiveDocumentId				Uniqueidentifier			-- ID van ban.	
			,@dImplementationDate			Varchar(30)					-- Ngay thuc hien
			,@sLeaderOfficeIdea				Nvarchar(1000)				-- Y kien Lanh dao van phong
			,@sLeaderIdList					Varchar(100)				-- Danh sach Id Lanh dao	
			,@sLeaderPositionNameList		Nvarchar(2000)				-- Danh sach ten + chuc vu cua lanh dao	
			,@sDelimitor					Varchar(20) = ','			-- Ky ty phan tach giua cac ky tu	 
	 * @return Mang luu thong tin thong tin PHAN PHOI VB
	 */
	public function DocReceivedDistributionUpdate($arrParameter){
		$psSql = "Exec Doc_DocReceivedDistributionUpdate ";	
		$psSql .= "'" . $arrParameter['FK_RECEIVED_DOC'] . "'";	
		$psSql .= ",'" . $arrParameter['C_DISTRIBUTION_DATE'] . "'";		
		$psSql .= ",'" . $arrParameter['C_LEADER_OFFICE_IDEA'] . "'";
		$psSql .= ",'" . $arrParameter['C_LEADER_ID_LIST'] . "'";
		$psSql .= ",'" . $arrParameter['C_LEADER_POSITION_NAME_LIST'] . "'";
		$psSql .= ",'" . $arrParameter['C_UNIT_ID_LIST'] . "'";
		$psSql .= ",'" . $arrParameter['C_UNIT_NAME_LIST'] . "'";
		$psSql .= ",'" . $arrParameter['C_STAFF_ID_LIST'] . "'";
		$psSql .= ",'" . $arrParameter['C_STAFF_NAME_LIST'] . "'";
		$psSql .= ",'" . $arrParameter['C_PROCESS_STATUS_UNIT_LIST'] . "'";
		$psSql .= ",'" . $arrParameter['C_UNIT_BY_STAFF_ID_LIST'] . "'";
		$psSql .= ",'" . $arrParameter['C_APPOINTED_DATE'] . "'";
		$psSql .= ",'" . $arrParameter['C_DELIMITOR'] . "'";
		//Thuc thi lenh SQL		
		//echo htmlspecialchars($psSql); exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($psSql) ; 
			$Result = $arrTempResult['RET_ERROR'];
		}catch (Exception $e){
			echo $e->getMessage();
		};
		//Return result
		return $Result;		
	}
	
	/**
	 * Idea : Phuong thuc lay thong tin PHAN PHOI VB
	 *
	 * @param varchar(50) $sReceiveDocumentId
	 * @return Mang chua thong tin PHAN PHOI VB
	 */
	public function DocReceivedDistributionGetSingle($sReceiveDocumentId){
		$sql = "Exec Doc_DocReceivedDistributionGetSingle ";
		$sql .= "'" . $sReceiveDocumentId . "'";
		//echo $sql . '<br>'; //exit;
		try{
			$arrReceived = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};		
		return $arrReceived;
	}
	/**
	 * Idea : Lay danh sach VB den cho phan cong, da phan cong (Cap DON VI)
	 *
	* @param  	
			@sLeaderId					Varchar(50)			-- Id Lanh dao phan cong
			,@sRoleLeader				Varchar(50)			-- Vai tro lanh dao phan cong
			,@iFkUnit					Varchar(20)			-- Id vi su dung.
			,@sFullTextSearch			Nvarchar(100)		-- Tu hoac cum tu can tim kiem
			,@sStatus					Varchar(50)			-- Trang thai van ban
			,@iPage						Int					-- So trang
			,@iNumberRecordPerPage		Int					-- So ban ghi tren mot trang
	
	 * 						
	 * @return Mang chua danh sach VB DEN 
	 */
	public function DocReceivedAssignGetAll($sLeaderId,$sRoleLeader,$iFkUnit,$sFullTextSearch, $sStatus,$iPage, $iNumberRecordPerPage, $sMainRoleLeaderGroup = '', $sSubRoleLeaderGroup = ''){		
		$sql = "Exec Doc_DocReceivedAssignGetAll ";
		$sql = $sql . "'" . $sLeaderId . "'";
		$sql = $sql . ",'" . $sRoleLeader . "'";
		$sql = $sql . ",'" . $iFkUnit . "'";
		$sql = $sql . ",'" . $sFullTextSearch . "'";
		$sql = $sql . ",'" . $sStatus . "'";
		$sql = $sql . ",'" . $iPage . "'";
		$sql = $sql . ",'" . $iNumberRecordPerPage . "'";
		$sql = $sql . ",'" . $sMainRoleLeaderGroup . "'";
		$sql = $sql . ",'" . $sSubRoleLeaderGroup . "'";	
		//echo $sql . '<br>'; //exit;
		try{
			$arrResul = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}		
		return $arrResul;
	}
	/**
	 * Idea : Phuong thuc cap nhat thong tin phan cong xu ly VB den (Cap DON VI)
	 *
	 * @param 
		@sReceiveDocumentId					Uniqueidentifier			-- ID van ban
		,@iFkUnit							Int							-- Id don vi su dung
		,@sLeaderId							Varchar(50)					-- Id Lanh dao phan cong
		,@sLeaderPositionName				Nvarchar(200)				-- Ten + chuc vu cua lanh dao	
		,@dImplementationDate				Varchar(30)					-- Ngay thuc hien
		,@sLeaderIdea						Nvarchar(1000)				-- Y kien chi dao
		,@sTypeProcessing					Varchar(50)					-- Hinh thuc xu ly
		,@sTypeAssign						Varchar(50)					-- Hinh thuc phan cong
		,@sStatus							Varchar(50)					-- Trang thai van ban
		,@sReceivePlaceProcessingIdList		Nvarchar(1000)				-- Danh sach Id noi nhan xu ly (Don vi; Phong ban; Can bo)
		,@sReceivePlaceProcessingNameList	Nvarchar(2000)				-- Danh sach Ten noi nhan xu ly (Don vi; Phong ban; Can bo)
		,@dAppointedDate					Varchar(30)					-- Han xu ly
		,@sReceiveStaffIdList				Nvarchar(1000)				-- Danh sach Id can bo nhan
		,@sReceiveStaffNameList				Nvarchar(2000)				-- Danh sach ten can bo nhan
		,@sReceiveUnitIdList				Nvarchar(1000)				-- Danh sach Id Don vi, Phong ban nhan
		,@sReceiveUnitNameList				Nvarchar(2000)				-- Danh sach ten Don vi, Phong ban nhan
		,@sDelimitor						Varchar(20) = ','			-- Ky ty phan tach giua cac ky tu
	 * @return Mang luu thong tin thong tin PHAN CONG XU LY VB 
	 */
	public function DocReceivedAssignUpdate($arrParameter){
		$psSql = "Exec Doc_DocReceivedAssignUpdate ";	
		$psSql .= "'" . $arrParameter['PK_RECEIVED_DOC'] . "'";	
		$psSql .= ",'" . $arrParameter['FK_UNIT'] . "'";
		$psSql .= ",'" .$arrParameter['FK_LEADER_ID'] . "'";
		$psSql .= ",'" . $arrParameter['C_LEADER_POSITION_NAME'] . "'";		
		$psSql .= ",'" .$arrParameter['C_ASSIGNED_DATE'] . "'";
		$psSql .= ",'" .$arrParameter['C_IDEA'] . "'";
		$psSql .= ",'" .$arrParameter['C_TYPE_PROCESSING'] . "'";
		$psSql .= ",'" . $arrParameter['C_TYPE_ASSIGN'] . "'";	
		$psSql .= ",'" . $arrParameter['C_STATUS'] . "'";	
		$psSql .= ",'" . $arrParameter['RECEIVE_PLACE_PROCESSING_IDLIST'] . "'";	
		$psSql .= ",'" . $arrParameter['RECEIVE_PLACE_PROCESSING_NAMELIST'] . "'";
		$psSql .= ",'" . $arrParameter['C_APPOINTED_DATE'] . "'";
		$psSql .= ",'" .$arrParameter['C_STAFF_ID_LIST'] . "'";
		$psSql .= ",'" .$arrParameter['C_STAFF_POSITION_NAME_LIST'] . "'";
		$psSql .= ",'" .$arrParameter['C_UNIT_ID_LIST'] . "'";
		$psSql .= ",'" .$arrParameter['C_UNIT_NAME_LIST'] . "'";
		$psSql .= ",'" .$arrParameter['C_DELIMITOR'] . "'";			
		//Thuc thi lenh SQL		
		//echo htmlspecialchars($psSql); exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($psSql) ; 
			$Result = $arrTempResult['RET_ERROR'];
		}catch (Exception $e){
			echo $e->getMessage();
		};
		//Return result
		return $Result;		
	}
	/**
	 * Idea : Phuong thuc lay thong tin PHAN CONG XU LY VB (Cap DON VI)
	 *
	 * @param 
	 	@sReceiveDocumentId		Varchar(50)		-- Id cua van ban den
		,@sLeaderId				Varchar(50)		-- Id Lanh dao phan cong
	 * @return Mang chua thong tin PHAN CONG XU LY VB
	 */
	public function DocReceivedAssignGetSingle($sReceiveDocumentId,$sLeaderId){
		$sql = "Exec Doc_DocReceivedAssignGetSingle ";
		$sql .= "'" . $sReceiveDocumentId . "'";
		$sql .= ",'" . $sLeaderId . "'";
		//echo $sql . '<br>'; //exit;
		try{
			$arrReceived = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};		
		return $arrReceived;
	}
	/**
	 * Idea : Lay danh sach VB den cho phan cong, da phan cong (Cap PHONG BAN)
	 *
	* @param  	
			@iUnitId					Varchar(20)			-- Id phong ban
			,@sFullTextSearch			Nvarchar(100)		-- Tu hoac cum tu can tim kiem
			,@sStatus					Varchar(50)			-- Trang thai van ban
			,@iPage						Int					-- So trang
			,@iNumberRecordPerPage		Int					-- So ban ghi tren mot trang
			
	 * 						
	 * @return Mang chua danh sach VB DEN 
	 */
	public function DocReceivedUnitAssignGetAll($iUnitId,$sFullTextSearch, $sStatus,$iPage, $iNumberRecordPerPage){		
		$sql = "Exec Doc_DocReceivedUnitAssignGetAll ";
		$sql = $sql . "'" . $iUnitId . "'";
		$sql = $sql . ",'" . $sFullTextSearch . "'";
		$sql = $sql . ",'" . $sStatus . "'";
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
	 * Idea : Phuong thuc cap nhat thong tin phan cong xu ly VB den (Cap PHONG BAN)
	 *
	 * @param 
		@sReceiveDocumentId					Uniqueidentifier			-- ID van ban
		,@iUnitId							Int							-- Id phong ban
		,@sLeaderId							Varchar(50)					-- Id Lanh dao phan cong
		,@sLeaderPositionName				Nvarchar(200)				-- Ten + chuc vu cua lanh dao	
		,@dImplementationDate				Varchar(30)					-- Ngay thuc hien
		,@sLeaderIdea						Nvarchar(1000)				-- Y kien chi dao
		,@sTypeProcessing					Varchar(50)					-- Hinh thuc xu ly
		,@sStatus							Varchar(50)					-- Trang thai van ban
		,@sStaffProcessMainIdList			Varchar(1000)				-- Danh sach Id can bo xu ly chinh
		,@sStaffProcessMainNameList			Nvarchar(2000)	            -- Danh sach ten can bo xu ly chinh
		,@sStaffCoordinateIdList			Varchar(1000)	            -- Danh sach Id can bo phoi hop xu ly
		,@sStaffCoordinateNameList			Nvarchar(2000)				-- Danh sach ten can bo phoi hop xu ly
		,@dAppointedDate					Varchar(30)					-- Han xu ly
		,@sDelimitor						Varchar(30)					-- Ky tu phan cach giua cac phan tu
	 * @return Mang luu thong tin thong tin PHAN CONG XU LY VB 
	 */
	public function DocReceivedUnitAssignUpdate($arrParameter){
		$psSql = "Exec Doc_DocReceivedUnitAssignUpdate ";	
		$psSql .= "'" . $arrParameter['FK_RECEIVED_DOC'] . "'";	
		$psSql .= ",'" . $arrParameter['FK_UNIT'] . "'";
		$psSql .= ",'" .$arrParameter['FK_LEADER_UNIT'] . "'";
		$psSql .= ",'" . $arrParameter['C_LEADER_UNIT_POSITION_NAME'] . "'";		
		$psSql .= ",'" .$arrParameter['C_SENT_DATE'] . "'";
		$psSql .= ",'" .$arrParameter['C_LEADER_UNIT_IDEA'] . "'";
		$psSql .= ",'" .$arrParameter['C_TYPE_PROCESSING'] . "'";
		$psSql .= ",'" . $arrParameter['C_PROCESS_STATUS'] . "'";		
		$psSql .= ",'" . $arrParameter['C_STAFF_PROCESS_MAIN_ID_LIST'] . "'";	
		$psSql .= ",'" . $arrParameter['C_STAFF_PROCESS_MAIN_NAME_LIST'] . "'";	
		$psSql .= ",'" .$arrParameter['C_STAFF_PROCESS_COORDINATE_ID_LIST'] . "'";
		$psSql .= ",'" .$arrParameter['C_STAFF_PROCESS_COORDINATE_NAME_LIST'] . "'";	
		$psSql .= ",'" . $arrParameter['C_APPOINTED_DATE'] . "'";
		$psSql .= ",'" .$arrParameter['C_DELIMITOR'] . "'";			
		//Thuc thi lenh SQL		
		//echo htmlspecialchars($psSql); exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($psSql) ; 
			$Result = $arrTempResult['RET_ERROR'];
		}catch (Exception $e){
			echo $e->getMessage();
		};
		//Return result
		return $Result;		
	}
	/**
	 * Idea : Phuong thuc lay thong tin PHAN CONG XU LY VB (Cap PHONG BAN)
	 *
	 * @param 
	 	@sReceiveDocumentId		Varchar(50)			-- Id cua van ban den
		,@iUnitId				Varchar(20)			-- Id phong ban
	 * @return Mang chua thong tin PHAN CONG XU LY VB
	 */
	public function DocReceivedUnitAssignGetSingle($sReceiveDocumentId,$iUnitId){
		$sql = "Exec Doc_DocReceivedUnitAssignGetSingle ";
		$sql .= "'" . $sReceiveDocumentId . "'";
		$sql .= ",'" . $iUnitId . "'";
		//echo $sql . '<br>'; //exit;
		try{
			$arrReceived = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};		
		return $arrReceived;
	}
	/**
	 * Idea : Lay danh sach VB den can bo can xu ly, da xu ly, phoi hop xu ly
	 *
	* @param  	
			@iUnitId					Varchar(20)			-- Id phong ban
			,@iStaffId					Varchar(20)			-- Id can bo xu ly
			,@sFullTextSearch			Nvarchar(100)		-- Tu hoac cum tu can tim kiem
			,@sStatus					Varchar(50)			-- Trang thai xu ly
			,@iPage						Int					-- So trang
			,@iNumberRecordPerPage		Int					-- So ban ghi tren mot trang
			
	 * 						
	 * @return Mang chua danh sach VB DEN 
	 */
	public function DocReceivedStaffProcessWorkGetAll($iUnitId,$iStaffId,$sFullTextSearch, $sStatus,$iPage, $iNumberRecordPerPage){		
		$sql = "Exec Doc_DocReceivedStaffProcessWorkGetAll ";
		$sql = $sql . "'" . $iUnitId . "'";
		$sql = $sql . ",'" . $iStaffId . "'";
		$sql = $sql . ",'" . $sFullTextSearch . "'";
		$sql = $sql . ",'" . $sStatus . "'";
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
	 * Idea : Lay chi tiet thong tin mot cong viec xu ly van ban den
	 *
	 * @param 
	 	@sPkDocWorkId			varchar(50)		-- Id cua cong viec xu ly van ban den
		,@iStaffId              varchar(20)		-- Id cua can bo cap nhat
	 * @return Mang chua thong tin KET QUA XU LY VB
	 */
	public function DocReceivedProcessWorkGetSingle($sPkDocWorkId,$iStaffId){
		$sql = "Exec Doc_DocReceivedProcessWorkGetSingle ";
		$sql .= "'" . $sPkDocWorkId . "'";
		$sql .= ",'" . $iStaffId . "'";
		//echo $sql . '<br>'; //exit;
		try{
			$arrWork = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};		
		return $arrWork;
	}
	/**
	 * Idea : Lay toan bo thong tin ket qua xu ly cua mot van ban den
	 *
	 * @param  @sReceiveDocumentId 	varchar(50)		-- Id cua van ban den 
	 	
	 * @return Mang chua toan bo thong tin KET QUA XU LY VB
	 */
	public function DocReceivedProcessWorkGetAll($sReceiveDocumentId){
		$sql = "Exec Doc_DocReceivedProcessWorkGetAll ";
		$sql .= "'" . $sReceiveDocumentId . "'";
		//echo $sql . '<br>'; //exit;
		try{
			$arrWorkAll = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};		
		return $arrWorkAll;
	}
	/**
	 * Idea : Phuong thuc Cap nhat thong tin ket qua xu ly van ban den 
	 *
	 * @param 
		@sPkDocWorkId				Varchar(50)					-- Id cua cong viec xu ly van ban den
		,@sReceiveDocumentId		Varchar(50)					-- ID van ban
		,@iUnitId					Int							-- Id phong ban
		,@sUnitName					Nvarchar(150)				-- Ten phong ban
		,@sStaffId					Varchar(50)					-- Id can bo thuc hien
		,@sStaffPositionName		Nvarchar(200)				-- Ten + Chuc vu cua can bo thuc hien
		,@dImplementationDate		Varchar(30)					-- Ngay thuc hien
		,@sResult					Nvarchar(1000)				-- Ket qua xu ly 
		,@sStatus					Varchar(50)					-- Trang thai xu ly
		,@sNewAttachFileNameList	Nvarchar(1000)				-- Danh sach ten file them moi
	 * @return Mang luu thong tin thong tin KET QUA XU LY VB 
	 */
	public function DocReceivedProcessWorkUpdate($arrParameter){
		$psSql = "Exec Doc_DocReceivedProcessWorkUpdate ";	
		$psSql .= "'" . $arrParameter['PK_DOC_WORK'] . "'";	
		$psSql .= ",'" . $arrParameter['FK_DOC'] . "'";
		$psSql .= ",'" .$arrParameter['FK_UNIT'] . "'";
		$psSql .= ",'" .$arrParameter['C_UNIT_NAME'] . "'";
		$psSql .= ",'" .$arrParameter['FK_STAFF'] . "'";
		$psSql .= ",'" . $arrParameter['C_STAFF_POSITION_NAME'] . "'";		
		$psSql .= ",'" .$arrParameter['C_WORK_DATE'] . "'";
		$psSql .= ",'" . $arrParameter['C_RESULT'] . "'";
		$psSql .= ",'" . $arrParameter['C_STATUS'] . "'";			
		$psSql .= ",'" . $arrParameter['ATTACH_FILE_NAME_LIST'] . "'";
		//Thuc thi lenh SQL		
		//echo htmlspecialchars($psSql); exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($psSql) ; 
			$Result = $arrTempResult['RET_ERROR'];
		}catch (Exception $e){
			echo $e->getMessage();
		};
		//Return result
		return $Result;		
	}
	/**
	 * Idea : Xoa mot hay nhieu cong viec xu ly VB den  
	 *
	 * @param @sPkDocWorkIdList	varchar(4000)	-- Danh sach Id cong viec xu ly van ban den
	 * 
	 * 
	 */
	public function DocReceivedProcessWorkDelete($sPkDocWorkIdList){
		$Result = null;			
		$sql = "Exec Doc_DocReceivedProcessWorkDelete ";		
		$sql .= "'".$sPkDocWorkIdList ."'";	
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
	 * Y nghia: Ham tra cuu van ban den
	 *
	 * @param unknown_type $iOwnerId
	 * @param unknown_type $iUnitId
	 * @param unknown_type $sDocType
	 * @param unknown_type $sDocCate
	 * @param unknown_type $iYear
	 * @param unknown_type $sFullTextSearch
	 * @param unknown_type $iPage
	 * @param unknown_type $iNumberRecordPerPage
	 * @return unknown
	 */
	public function DocSearchReceivedDistrictGetAll($iParentOwnerId, $iUnitId, $sDocType, $sDocCate, $iYear, $sFullTextSearch, $iPage, $iNumberRecordPerPage){		
		$sql = "Exec Doc_SearchReceivedDistrictGetAll ";
		$sql = $sql . "'" . $iParentOwnerId . "'";
		$sql = $sql . ",'" . $iUnitId . "'";
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
	public function DocSearchReceivedUnitGetAll($iUnitId, $sDocType, $sDocCate, $iYear, $sFullTextSearch, $iPage, $iNumberRecordPerPage){		
		$sql = "Exec Doc_SearchReceivedUnitGetAll ";
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
	 * Nguoi tao: NGHIAT
	 * Ngay tao: 16/08/2010 	
	 *  Lay danh sach bao cao VB den
	 */
	public function docReceivedListReport($arrParam,$sChecked){
		//Tao doi tuong trong trong thu vien dung chung
		$objLib = new Sys_Library();
		//
		$sHtmlRes = '<table class="list_table2"  width="98%" cellpadding="0" cellspacing="0" align="center" style="BORDER-TOP: #BACAD7 1px solid;">
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
								'<td align="center"><input type="radio" message="Phai xac dinh LOAI BAO CAO!" name="opt_reporttype_id" id="'. $v_report_code .'" readonly="true"  value="'. $v_report_code.'"' . $v_report_checked.' onClick="btn_rad_onclick(this,document.getElementById(\'hdn_Report_id\'));document.forms[0].submit();"></td>
								<td colspan="10" style="padding-left:5px;"><label style="cursor:pointer;" for ="'.$v_report_code.'">'  .$v_report_name. '</label></td></tr>'; 
			
		}	
		
		$sHtmlRes = $sHtmlRes.'</table>'	;				
		return $sHtmlRes;
		//var_dump($sHtmlRes);
	}
	/**
	 * Nguoi tao: NGHIAT
	 * Ngay tao: 19/08/2010 	
	 *  Cap nhat trang thai VB den chuyen du thao van ban
	 */
	public function docDraffReceivedUpdate($arrParameter){
		$psSql = "Exec Doc_DocDraffReceivedUpdate ";	
		$psSql .= "'" . $arrParameter['PK_RECEIVED_DOC'] . "'";	
		$psSql .= ",'" . $arrParameter['FK_UNIT'] . "'";
		$psSql .= ",'" .$arrParameter['C_SUBJECT'] . "'";
		$psSql .= ",'" .$arrParameter['C_DOC_CATE'] . "'";
		$psSql .= ",'" .$arrParameter['C_STATUS'] . "'";
		$psSql .= ",'" .$arrParameter['FK_DEPARTMENT'] . "'";
		$psSql .= ",'" .$arrParameter['C_UNIT_NAME'] . "'";
		$psSql .= ",'" .$arrParameter['FK_STAFF'] . "'";
		$psSql .= ",'" .$arrParameter['C_STAFF_POSITION_NAME'] . "'";
		//Thuc thi lenh SQL		
		//echo htmlspecialchars($psSql); exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($psSql) ; 
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrTempResult;	
	}
	/*
	 * cuongnh
	 */
	public function DocReceivedReportVBDEN01($sCode,$iOverid,$sName,$sFromDate,$sToDate,$sFromDateConvert,$sToDateConvert,$sNameunit){		
		$sql = "Exec Doc_DocReceivedReportVBDEN01 ";
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
	/*
	 * 
	 */
	public function DocReceivedReportVBDEN07($iOverid,$sFromDate,$sToDate,$sFromDateConvert,$sToDateConvert,$sNameunit){		
		$sql = "Exec Doc_DocReceivedReportVBDEN07 ";
		$sql = $sql . "'" . $iOverid;
		$sql = $sql . "','" . $sFromDate . "'";	
		$sql = $sql . ",'" . $sToDate . "'";
		$sql = $sql . ",'" . $sFromDateConvert . "'";
		$sql = $sql . ",'" . $sToDateConvert . "'";
		$sql = $sql . ",'" . $sNameunit . "'";
		echo $sql . '<br>'; //exit;
		try{
			$arrResul = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}		
		return $arrResul;
	}
}	
?>
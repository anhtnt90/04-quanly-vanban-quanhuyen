<?php
class statistics_modRop extends Sys_DB_Connection {		
	public function DocRopResultProcessReceivedGetAll($iOwnerId,$iLeaderId, $iUnitId, $sStatus, $sDocType, $sFromDate, $sToDate, $sFullTextSearch, $iPage, $iNumberRecordPerPage, $iOption){		
		$sql = "Exec Doc_RopResultProcessReceivedGetAll ";
		$sql = $sql . "'" . $iOwnerId . "'";
		$sql = $sql . ",'" . $iLeaderId . "'";
		$sql = $sql . ",'" . $iUnitId . "'";
		$sql = $sql . ",'" . $sStatus . "'";
		$sql = $sql . ",'" . $sDocType . "'";
		$sql = $sql . ",'" . $sFromDate . "'";
		$sql = $sql . ",'" . $sToDate . "'";
		$sql = $sql . ",'" . $sFullTextSearch . "'";
		$sql = $sql . ",'" . $iPage . "'";
		$sql = $sql . ",'" . $iNumberRecordPerPage . "'";
		$sql = $sql . ",'" . $iOption . "'";
		//echo '<br>'.$sql . '<br>'; //exit;
		try{
			$arrResul = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrResul;
	}
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
	public function DocRopReceivedGetSingle($sReceiveDocumentId){
		$sql = "Exec [dbo].[Doc_RopReceivedGetSingle] ";
		$sql .= "'" . $sReceiveDocumentId . "'";
		//echo $sql . '<br>'; //exit;
		try{
			$arrReceived = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};		
		return $arrReceived;
	}	
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
	public function DocRopGeneralResultReceivedGetAll($iOwnerId, $sFromDate, $sToDate){		
		$sql = "Exec Doc_RopGeneralResultReceivedGetAll ";
		$sql = $sql . "'" . $iOwnerId . "'";
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
	public function DocRopResultProcessUnitByStatusGetAll($iOwnerId, $iUnitId, $sFullTextSearch, $sFromDate, $sToDate, $sStatus, $iPage, $iNumberRecordPerPage){		
		$sql = "Exec Doc_RopResultProcessUnitByStatusGetAll ";
		$sql = $sql . "'" . $iOwnerId . "'";
		$sql = $sql . ",'" . $iUnitId . "'";
		$sql = $sql . ",'" . $sFullTextSearch . "'";
		$sql = $sql . ",'" . $sFromDate . "'";
		$sql = $sql . ",'" . $sToDate . "'";
		$sql = $sql . ",'" . $sStatus . "'";
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
	public function DocDocRopWorkGetAll($iOwnerId, $iStaffId, $iLeaderId, $sStatus, $sFromDate,$sToDate, $sFullTextSearch, $iPage, $iNumberRecordPerPage,$iUnitId){		
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
		$sql = $sql . ",'" . $iUnitId . "'";
		//echo '<br>'.$sql . '<br>'; //exit;
		try{
			$arrResul = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrResul;
	}	
	public function DocRopWorkGetSingle($sWorkId){
		$sql = "Exec [dbo].[Doc_DocRopWorkGetSingle] ";
		$sql .= "'" . $sWorkId . "'";
		//echo $sql . '<br>'; //exit;
		try{
			$arrWork = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};		
		return $arrWork;
	}	
	public function DocRopWorkProcessResultGetAll($sWorkId){		
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
}

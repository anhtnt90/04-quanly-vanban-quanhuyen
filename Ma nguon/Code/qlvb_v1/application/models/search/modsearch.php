<?php
class search_modsearch extends Sys_DB_Connection {	
	public function DocFullTextSearchDocGetAll($iOwnerId,$iYear, $sFullTextSearch, $iPage, $iNumberRecordPerPage){		
		$sql = "Exec Doc_FullTextSearchDocGetAll ";
		$sql = $sql . "'" . $iOwnerId . "'";
		$sql = $sql . ",'" . $iYear . "'";
		$sql = $sql . ",'" . $sFullTextSearch . "'";
		$sql = $sql . ",'" . $iPage . "'";
		$sql = $sql . ",'" . $iNumberRecordPerPage . "'";
		try{
			$arrDoc = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrDoc;
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
	public function DocFullTextSearchDocGetSingle($sDocumentId, $sType){
		$sql = "Exec [dbo].[Doc_FullTextSearchDocGetSingle] ";
		$sql .= "'" . $sDocumentId . "'";
		$sql .= ",'" . $sType . "'";	
		try{
			$arrDocSingle = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};		
		return $arrDocSingle;
	}
}

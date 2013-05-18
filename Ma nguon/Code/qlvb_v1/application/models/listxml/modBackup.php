<?php
class Listxml_modBackup extends Sys_DB_Connection {	
		
	/** Nguoi tao: NGHIAT
		* Ngay tao: 27/10/2010
		* Y nghia: Lay chi tiet mot TTHC
		* adodbExecSqlString: lay mang 1 chieu
		* adodbQueryDataInNameMode: lay mang da chieu
	*/
	public function eCSBackupHand($spath,$sDatabaseName,$sFileName){
		$arrResult = null;
		$sql = "Exec [sp_AutoBackupDb] ";
		$sql .= "'" . $spath . "'";
		$sql .= ",'" . $sDatabaseName . "'";
		$sql .= ",'" . $sFileName . "'";
		//echo $sql; exit;
		try{
			$arrResult = $this->adodbExecSqlString($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrResult;
	}
	public function getAllObjectbyListCode($sOwnerCode,$sCode, $optCache = ""){
		// Tao doi tuong xu ly du lieu
		$objConn = new  Sys_DB_Connection(); 
		$sql = "SysLib_ListGetAllbyListtypeCode ";
		$sql = $sql . " '" . $sOwnerCode . "'";
		$sql = $sql . " ,'" . $sCode . "'";
		//echo $sql . '<br>';exit;
		try {
			$arrObject = $objConn->adodbQueryDataInNameMode($sql,$optCache);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrObject;
	}
} 	
<?php 

/**
 * @see adodb.inc.php
 * Call Adodb library
 */
require_once 'Sys/adodb/adodb.inc.php';

/**
 *
 */
class Sys_DB_Connection extends Zend_Db {	
	/**
	 * 
	 */
	public static function connectADO($adapter, $config = array()){		
		global $adoConn;
		if($adapter == "MSSQL"){//Ket noi MS SQL server
			//Lay tham so ket noi CSDL
			$dbname 	= $config['dbname'];
			$username 	= $config['username'];
			$password 	= $config['password'];
			$host 		= $config['host'];			
			
			//Tao doi tuong ADODB
			$adoConn = NewADOConnection("ado_mssql");  // create a connection
			$connStr = "Provider=SQLOLEDB; Data Source=" . $host . ";Initial Catalog='" . $dbname . "'; User ID=" . $username . "; Password=" .$password;
			//call connect adodb
			$adoConn->Connect($connStr) or die("Hien tai he thong khong the ket noi vao CSDL duoc!");
		}
		return $adoConn;
	}
	
	/**
	 * Creater: HUNGVM
	 * Date: 
	 * Thuc thi hanh dong update / delete / getsingle / ...
	 * @param $sql : Xau SQL can thuc thi
	 * @return unknown
	 */
	public function adodbExecSqlString($sql){
		global $adoConn;
		$adoConn->SetFetchMode(ADODB_FETCH_ASSOC);
		$ArrSingleData = $adoConn->GetRow($sql); 
		return $ArrSingleData;
	}
	
	/**
	 * Creater: HUNGVM
	 * date:
	 * Lay tat ca thong tin trong CSDL, phan tu dang chi so bat dau tu 0,1,2,...
	 * @param $sql : Xau SQL can thuc thi
	 * @return Mang luu thong tin du lieu
	 */
	public function adodbQueryDataInNumberMode($sql){
		global $adoConn;
		$adoConn->SetFetchMode(ADODB_FETCH_NUM);
		$ArrAllData = $adoConn->GetAll($sql); 
		return $ArrAllData;
	}
	
	/**
	 * Creater: HUNGVM
	 * date:
	 * Lay tat ca thong tin trong CSDL, phan tu dang ten cot
	 * @param $sql : Xau SQL can thuc thi
	 * @return Mang luu thong tin du lieu
	 */
	public function adodbQueryDataInNameMode($sql){
		
		global $adoConn;		
		$adoConn->SetFetchMode(ADODB_FETCH_ASSOC);		
		$ArrAllData = $adoConn->GetArray($sql); 
		return $ArrAllData;
	}
}
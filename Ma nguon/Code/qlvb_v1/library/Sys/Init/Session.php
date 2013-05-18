<?php 

/**
 * @see 
 * 
 */
//Goi xu ly webservice
require_once 'Sys/nusoap/nusoap.php';

//Goi xu ly kieu session
require_once 'Zend/Session/Namespace.php';

//Goi cac ham dung chung
//require_once 'Sys/Publib/Library.php';

/**
 * Nguoi tao: HUNGVM 
 * Ngay tao: 17/11/2008
 * Noi dung: Tao lop Sys_Init_Session khoi tao cac bien session
 */
class Sys_Init_Session extends Zend_Session_Namespace {	

	//********************************************************************************
	//Ten phuong thuc		:getPersonalInfoOfAllStaff()
	//Chuc nang	: Lay thong tin ca nhan cua tat ca can bo (staff)
	//********************************************************************************/
	public function SesGetPersonalInfoOfAllStaff(){
		//global $p_arr_items, $p_level1_tag_name, $p_level2_tag_name_list, $p_delimitor;
		$p_arr_items = array();	
		$arrResul = array();
		$sql = "Exec " . Sys_Init_Config::_setDbLinkUser() . ".dbo.USER_GetPersonalInfoOfAllStaff ";
		//echo $sql . '<br>';
		try{
			$arrResul = Sys_DB_Connection::adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		if(sizeof($arrResul) > 0){
			foreach ($arrResul As $Resul){
				$arrStaff = array("id"					=>str_replace('{','',str_replace('}', '',$Resul['PK_STAFF']))
								  ,"name"				=>$Resul['C_NAME']
								  ,"code"				=>$Resul['C_CODE']
								  ,"unit_id"			=>str_replace('{','',str_replace('}', '',$Resul['FK_UNIT']))
								  ,"position_code"		=>$Resul['C_POSITION_CODE']
								  ,"position_name"		=>$Resul['C_POSITION_NAME']
								  ,"position_group_code"=>$Resul['C_POSITION_GROUP_CODE']
								  ,"address"			=>$Resul['C_ADDRESS']
								  ,"email"				=>$Resul['C_EMAIL']
								  ,"tel"				=>$Resul['C_TEL']
								  ,"tel_mobile"			=>$Resul['C_TEL_MOBILE']
								  ,"order"				=>$Resul['C_ORDER']
								  ,"ownerCode"			=>$Resul['C_OWNER_CODE']
								  );
				array_push($p_arr_items,$arrStaff);
			}
		}
		return $p_arr_items;
	}	
		
	//********************************************************************************
	//Ten ham		:SesGetDetailInfoOfAllUnit()
	//Chuc nang	: Lay thong tin chi tiet cua tat ca phong ban (unit)	
	//********************************************************************************/
	public function SesGetDetailInfoOfAllUnit(){
		$p_arr_items = array();
		$arrResul = array();
		$sql = "Exec " . Sys_Init_Config::_setDbLinkUser() . ".dbo.USER_GetDetailInfoOfAllUnit ";
		try{
			$arrResul = Sys_DB_Connection::adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		if(sizeof($arrResul) > 0){
			foreach ($arrResul As $Resul){
				$arrStaff = array("id"					=>str_replace('{','',str_replace('}', '',$Resul['PK_UNIT']))
								  ,"parent_id"			=>str_replace('{','',str_replace('}', '',$Resul['FK_UNIT']))
								  ,"name"				=>$Resul['C_NAME']
								  ,"code"				=>$Resul['C_CODE']
								  ,"address"			=>$Resul['C_ADDRESS']
								  ,"email"				=>$Resul['C_EMAIL']
								  ,"tel"				=>$Resul['C_TEL']
								  ,"order"				=>$Resul['C_ORDER']
								  ,"ownerCode"			=>$Resul['C_OWNER_CODE']
								  );
				array_push($p_arr_items,$arrStaff);
			}
		}
		return $p_arr_items;
	}	
	
	
	/**
	 * Creater :HUNGVM
	 * Date : 24/09/2009
	 * Idea : Tao phuong thuc lay tat ca quyen cua NSD hien thoi
	 * @param $sStaffIdList : Id can bo hien thoi
	 * @param $sDelimitor : Ky tu phan tach
	 * @return Mang danh sach quyen
	 */
	public function SesGetAllPermissionForSession($sStaffIdList, $sDelimitor = "!~~!"){
		//
		$ojbConnect = new  Sys_DB_Connection();
		$sql = "Doc_StaffPermissionGetAll ";
		$sql = $sql . "'" . $sStaffIdList . "'";				
		$sql = $sql . ",'" . $sDelimitor . "'";				
		//echo 'SesGetAllPermissionForSession:' . $sql . '<br>'; //exit;
		try{
			$arrResult = $ojbConnect->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		
		//Chuyen doi xau mo ta quyen -> mang mot chieu
		$arrElement = explode("!~~!",$arrResult[0]['C_PERMISSION_LIST']);	
		$arrPermission = array();
		for ($index = 0;$index<sizeof($arrElement);$index++){
			$arrTemp = explode("!*~*!",$arrElement[$index]);
			if (trim($arrTemp[0]) != ""){
				$arrPermission[trim($arrTemp[0])] = 1;
			}	
		}
		//var_dump($arrPermission);exit;
		return $arrPermission;
	}
	
	
	/**
	 * Creater : HUNGVM
	 * Date : 25/09/2009
	 * Idea : Tao phuong thuc lay quyen cua NSD hien thoi
	 *
	 */
	public function StaffPermisionGetAll($iLoginStaffId ){
		$arrPermission = self::SesGetAllPermissionForSession($iLoginStaffId);
		if (is_array($arrPermission) || $arrPermission == ""){
			return $arrPermission;			
		}
		return '';
	}
	/**
	 * Creater : HUNGVM
	 * Date : 25/09/2009
	 * hieu chinh: phuongtt - 18/08/2010
	 * Idea : Tao phuong thuc lay thong tin don vi su dung
	 *
	 * @param unknown_type $UnitId
	 */
	public function SesGetAllOwner($option=''){
		$arrOwner = array();
		$arrResult = array();
		$sql = "Exec " . Sys_Init_Config::_setDbLinkUser() . ".dbo.USER_OwnerGetAll ";
		$sql = $sql . "'".  "'";
		$sql = $sql . ",'" . 'DM_DON_VI_TRIEN_KHAI' . "'";
		//echo 'sql:' . $sql.'<br>';
		try{
			$arrResult = Sys_DB_Connection::adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		for ($index = 0;$index<sizeof($arrResult);$index++){
			$arr1Owner = array("name"=>$arrResult[$index]['C_NAME'],"code"=>$arrResult[$index]['C_CODE'],"order"=>$arrResult[$index]['C_ORDER'],"address"=>$arrResult[$index]['C_ADDRESS'],"email"=>$arrResult[$index]['C_EMAIL'],"phone"=>$arrResult[$index]['C_TELEPHONE']);
			array_push($arrOwner,$arr1Owner);
		}
		return $arrOwner;
	}
	
	/**
	 * Creater : HUNGVM
	 * Date : 26/09/2009
	 * Idea : Tao phuong thuc lay thong tin MA; TEN; DANH SACH ID cua can bo dang nhap hien thoi
	 *
	 * @return Mang luu thong tin Ma; Ten; list Id Staff cua can bo dang nhap hien thoi
	 */
	public function StaffOwnerInformationGet($iLoginStaffId = 0){		
		$iStaffId = intval($_SESSION['staff_id']);		
		//echo 'iLoginStaffIdxxxx:' . $iStaffId . '<br>';
		if (!isset($iStaffId) || $iStaffId == 0 || $iStaffId == ""){			
			$iStaffId = $iLoginStaffId;
		}		
		//echo 'staffId:' . $iStaffId . '<br>';exit;
		//Tao doi tuong trong thu vien dung chung
		$objLib = new Sys_Library();		
		//echo $iStaffId . '<test>';
		$arrTemp = $_SESSION['SesGetAllOwner'];
		if (is_null($arrTemp)){
			$arrTemp = self::SesGetAllOwner();
		}
		//var_dump($arrTemp);		
		//Lay thong tin Ma; Ten; List Staff ID cua NSD hien thoi
		for ($index = 0;$index<sizeof($arrTemp);$index++){
			if ($objLib->_listHaveElement($arrTemp[$index]['C_STAFF_ID_LIST'],$iStaffId,',')){
				$arrOwner['ID'] 					= $arrTemp[$index]['id'];
				$arrOwner['OWNER_CODE'] 			= $arrTemp[$index]['code'];
				$arrOwner['OWNER_NAME'] 			= $arrTemp[$index]['name'];
				$arrOwner['C_STAFF_ID_LIST'] 		= $arrTemp[$index]['C_STAFF_ID_LIST'];		
				$arrOwner['C_HAVE_TO_DISTRIBUTION'] = $arrTemp[$index]['C_HAVE_TO_DISTRIBUTION'];				
				return $arrOwner;
				break;
			}
		}
		//var_dump($arrOwner);
		return '';
	}
	
	/**
	 * Creter : HUNGVM
	 * Date : 24/06/2010
	 * Idea : Tra lai danh sach phong ban cua NSD hien thoi
	 *
	 * @param $arrListValue
	 * @return : Mang danh sach phong ban
	 */
	public function _getUnitsByRoomIdLevelOne($arrListValue){
	//var_dump($_SESSION['arr_all_unit']);
		$iStatus = 0;
		if($arrListValue[0] != 0){
			$i = 0;
			foreach($_SESSION['arr_all_unit'] as $objUnit){//Lay phong ban cap 0
				if (is_null($objUnit['parent_id']) || $objUnit['parent_id']=="" || $objUnit['parent_id']=="NULL"){/*
					$arrChildUnitRoot[$i]['id'] 		= 	$objUnit['id'];
					$arrChildUnitRoot[$i]['parent_id'] 	= 	NULL;
					$arrChildUnitRoot[$i]['name'] 		= 	$objUnit['name'];
					$arrChildUnitRoot[$i]['code'] 		= 	$objUnit['code'];
					$arrChildUnitRoot[$i]['address'] 	= 	$objUnit['address'];
					$arrChildUnitRoot[$i]['email'] 		= 	$objUnit['email'];
					$arrChildUnitRoot[$i]['tel'] 		= 	$objUnit['tel'];
					$arrChildUnitRoot[$i]['order']		= 	$objUnit['order'];
					$i++; // Them thong tin cua phong ban cap 1
					*/
				
					$arrChildUnitRoot[$i]['id'] 		= 	$arrListValue[0];
					$arrChildUnitRoot[$i]['parent_id'] 	= 	NULL;
					$arrChildUnitRoot[$i]['name'] 		= 	$arrListValue[2];
					$arrChildUnitRoot[$i]['code'] 		= 	$arrListValue[3];
					$arrChildUnitRoot[$i]['address'] 	= 	$arrListValue[4];
					$arrChildUnitRoot[$i]['email'] 		= 	$arrListValue[5];
					$arrChildUnitRoot[$i]['tel'] 		= 	$arrListValue[6];
					$arrChildUnitRoot[$i]['order']		= 	$arrListValue[7];
					$i++;
				}else{// Lay cac phong van con cua phong ban cap 1					
					if($objUnit['parent_id'] == $arrListValue[0]){
						$arrChildUnitRoot[$i]['id'] 			= 	$objUnit['id'];
						$arrChildUnitRoot[$i]['parent_id'] 	= 	$objUnit['parent_id'];
						$arrChildUnitRoot[$i]['name'] 		= 	$objUnit['name'];
						$arrChildUnitRoot[$i]['code'] 		= 	$objUnit['code'];
						$arrChildUnitRoot[$i]['address'] 	= 	$objUnit['address'];
						$arrChildUnitRoot[$i]['email'] 		= 	$objUnit['email'];
						$arrChildUnitRoot[$i]['tel'] 		= 	$objUnit['tel'];
						$arrChildUnitRoot[$i]['order']		= 	$objUnit['order'];
	                    $arrChildUnitRoot[$i]['permission'] 	=   $objUnit['permission'];
						$i++;												
					}	
				}	
			}
		}	
		return $arrChildUnitRoot;
	}
		
	/**
	 * Creater: HUNGVM
	 * Date : 24/06/2010
	 * Idea :  Lay toan bo thong tin(ID, NAME) cua don vi su dung
	 *
	 * @return unknown
	 */
	public function _getAllOwner(){
		//$sUnitLevel1Id="";
		$iRootId = _get_root_unit_id();
		$arrListOwner = array();
		$v_index = 0;
		foreach($_SESSION['arr_all_unit_keep'] as $objUnit){
			if (strcasecmp($objUnit['parent_id'], $iRootId) == 0){			
				$sParentName 		=  $objUnit['name'];
				$sParentCode 		=  $objUnit['code'];
				$arrListOwner[$v_index]['OWNER_CODE'] = $sParentCode;
				$arrListOwner[$v_index]['OWNER_NAME'] = $sParentName;
				$v_index++;
			}
		}
		return $arrListOwner;
	}

	/**
	 * Creater : HUNGVM
	 * Date : 24/06/2010
	 * Idea : Lay toan bo NSD cua mot don vi
	 *
	 * @param $arrUnit
	 * @return Mang danh sach can bo trong mang $arrUnit
	 */
	public function _getAllUser($arrUnit){
		$i = 0;
		$iCount = sizeof($arrUnit);
		for($j=0; $j < $iCount; $j++){
			foreach($_SESSION['arr_all_staff_keep'] as $objStaff){
				if($objStaff['unit_id'] == $arrUnit[$j]['id']){			
					$arrchildStaff[$i]['id'] 					= $objStaff['id'];
					$arrchildStaff[$i]['name'] 					= $objStaff['name'];
					$arrchildStaff[$i]['code'] 					= $objStaff['code'];				
					$arrchildStaff[$i]['unit_id'] 				= $objStaff['unit_id'];
					$arrchildStaff[$i]['position_code'] 		= $objStaff['position_code'];
					$arrchildStaff[$i]['position_name'] 		= $objStaff['position_name'];
					$arrchildStaff[$i]['position_group_code'] 	= $objStaff['position_group_code'];
					$arrchildStaff[$i]['address'] 				= $objStaff['address'];
					$arrchildStaff[$i]['email'] 				= $objStaff['email'];
					$arrchildStaff[$i]['tel'] 					= $objStaff['tel'];
					$arrchildStaff[$i]['order'] 				= $objStaff['order'];
	                $arrchildStaff[$i]['permission'] 			= $objStaff['permission'];	               
					$i++;
				}
			}
		}
		return $arrchildStaff;	
	}
	
	//********************************************************************************************************************
	//Nguoi tao	: Vu Manh Hung
	//Ngay tao	: 26/02/2008
	//Y nghia	: Lay TEN va ID cua don vi cap 1 cua mot nguoi bat ky trong don vi
	//********************************************************************************************************************
	
	/**
	 * Creater : HUNGVM
	 * Date : 24/06/2010
	 * Idea :  Lay TEN va ID cua don vi cap 1 cua mot nguoi bat ky trong don vi
	 *
	 * @param $userId : Id NSD hien thoi
	 * @return : Chuoi luu thong tin $sUnitLevel1Id . "|!~~!|" . $iParentId . "|!~~!|" . $sParentName . "|!~~!|" . $sParentCode . "|!~~!|" . $sParentAddress . "|!~~!|" . $sParentEmail. "|!~~!|" . $sParentOrder;	
	 */
	public function _getUnitLevelOneNameAndId($userId){
		$sUnitLevel1Id="";
		$iRootId = Sys_Library::_getRootUnitId($_SESSION['arr_all_unit']);
		$iParentId = Sys_Library::_getItemAttrById($_SESSION['arr_all_staff'],$userId,'unit_id');
		//echo $iParentId; exit;
		if ($iParentId!="" && !is_null($iParentId) && $iParentId!="NULL"){
			while(strcasecmp($iParentId,$iRootId)!=0){
				foreach($_SESSION['arr_all_unit'] as $objUnit){
					if (strcasecmp($objUnit['id'], $iParentId)==0){			
						$sUnitLevel1Id 		= $objUnit['id'];
						$iParentId 			=  $objUnit['parent_id'];
						$sParentName 		=  $objUnit['name'];
						$sParentCode 		=  $objUnit['code'];
						$sParentAddress 	=  $objUnit['address'];
						$sParentEmail 		=  $objUnit['email'];
						$sParentOrder		=  $objUnit['order'];
						break;
					}
				}
			}	
		}	
		return $sUnitLevel1Id . "|!~~!|" . $iParentId . "|!~~!|" . $sParentName . "|!~~!|" . $sParentCode . "|!~~!|" . $sParentAddress . "|!~~!|" . $sParentEmail. "|!~~!|" . $sParentOrder;	
	}
	/**
	 * Creter : HUNGVM
	 * Date : 30/06/2011
	 * Idea : Tra lai danh sach phong ban cua NSD hien thoi
	 * @param $sOwnerCode : ID don vi NSD hien thoi
	 */
	public function _getAllUnitsByCurrentStaff($sOwnerCode = ''){
		$arrChildUnitRoot = array();
		if($sOwnerCode != ""){
			$i = 0;
			foreach($_SESSION['arr_all_unit_keep'] as $objUnit){//Lay phong ban cap 0
				if (is_null($objUnit['parent_id']) || $objUnit['parent_id']=="" || $objUnit['parent_id']=="NULL"){
					$arrChildUnitRoot[$i]['id'] 		= 	$objUnit['id'];
					$arrChildUnitRoot[$i]['parent_id'] 	= 	NULL;
					$arrChildUnitRoot[$i]['name'] 		= 	$objUnit['name'];
					$arrChildUnitRoot[$i]['code'] 		= 	$objUnit['code'];
					$arrChildUnitRoot[$i]['address'] 	= 	$objUnit['address'];
					$arrChildUnitRoot[$i]['email'] 		= 	$objUnit['email'];
					$arrChildUnitRoot[$i]['tel'] 		= 	$objUnit['tel'];
					$arrChildUnitRoot[$i]['order']		= 	$objUnit['order'];
					$arrChildUnitRoot[$i]['ownerCode']	= 	$objUnit['ownerCode'];
					$i++;
				}else{// Lay cac phong van con cua phong ban cap 1					
					if($objUnit['ownerCode'] == $sOwnerCode){
						$arrChildUnitRoot[$i]['id'] 		= 	$objUnit['id'];
						$arrChildUnitRoot[$i]['parent_id'] 	= 	$objUnit['parent_id'];
						$arrChildUnitRoot[$i]['name'] 		= 	$objUnit['name'];
						$arrChildUnitRoot[$i]['code'] 		= 	$objUnit['code'];
						$arrChildUnitRoot[$i]['address'] 	= 	$objUnit['address'];
						$arrChildUnitRoot[$i]['email'] 		= 	$objUnit['email'];
						$arrChildUnitRoot[$i]['tel'] 		= 	$objUnit['tel'];
						$arrChildUnitRoot[$i]['order']		= 	$objUnit['order'];
						$arrChildUnitRoot[$i]['ownerCode']	= 	$objUnit['ownerCode'];
						$i++;												
					}	
				}	
			}
		}	
		return $arrChildUnitRoot;
	}
	/**
	 * Creater : HUNGVM
	 * Date : 24/06/2010
	 * Idea : Lay toan bo NSD cua mot don vi
	 * @param $sOwnerCode : Ma don vi trien khai
	 */
	public function _getAllUsersByCurrentOrg($sOwnerCode = ''){
		$i = 0;
		foreach($_SESSION['arr_all_staff_keep'] as $objStaff){
			if($objStaff['ownerCode'] == $sOwnerCode){			
				$arrchildStaff[$i]['id'] 					= $objStaff['id'];
				$arrchildStaff[$i]['name'] 					= $objStaff['name'];
				$arrchildStaff[$i]['code'] 					= $objStaff['code'];				
				$arrchildStaff[$i]['unit_id'] 				= $objStaff['unit_id'];
				$arrchildStaff[$i]['position_code'] 		= $objStaff['position_code'];
				$arrchildStaff[$i]['position_name'] 		= $objStaff['position_name'];
				$arrchildStaff[$i]['position_group_code'] 	= $objStaff['position_group_code'];
				$arrchildStaff[$i]['address'] 				= $objStaff['address'];
				$arrchildStaff[$i]['email'] 				= $objStaff['email'];
				$arrchildStaff[$i]['tel'] 					= $objStaff['tel'];
				$arrchildStaff[$i]['order'] 				= $objStaff['order'];
				$arrchildStaff[$i]['ownerCode'] 			= $objStaff['ownerCode'];
				$i++;
			}
		}
		return $arrchildStaff;	
	}
}
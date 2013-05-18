<?php 
 class Sys_Function_DocFunctions {	 		
	/**	 
	 * @param unknown_type $arrFileList
	 * @param unknown_type $piCountFile
	 * @param unknown_type $piMaxNumberAttachFile
	 * @param unknown_type $psHaveUpLoadFields
	 * @param unknown_type $size: do dai cua textbox chua file dinh kem
	 * @return unknown
	 */
	public function DocSentAttachFile($arrFileList, $piCountFile, $piMaxNumberAttachFile = 10, $psHaveUpLoadFields = true, $size, $urlModule = ''){		
		
		$psGotoUrlForDeleteFile = "javascript:delete_row(document.getElementsByName(\"tr_line_new\"),document.getElementsByName(\"chk_file_attach_new_id\"),document.getElementById(\"hdn_deleted_new_file_id_list\"));";
		$psGotoUrlForAddFile = "javascript:add_row(document.getElementsByName(\"tr_line_new\")," . $piMaxNumberAttachFile .");";
		
		$strHTML = $strHTML . "<table width='75%' cellpadding='0' cellspacing='0'><col width = '6%'><col width = '94%'>";	
		
		//Tao doi tuong thong tin config
		$objConfig = new Sys_Init_Config();
		
		//ID File dinh kem		
		if (($piCountFile>0) && ($arrFileList != '')){		
			// Goi thu tuc xu ly khi xoa cac file da co
			$psGotoUrlForDeleteFile = $psGotoUrlForDeleteFile . "delete_row_exist(document.getElementsByName(\"tr_line_exist\"),document.getElementsByName(\"chk_file_attach_exist_id\"),\"" . $_SERVER['REQUEST_URI'] . "\");";
			for ($index = 0; $index<$piCountFile; $index++){
				$sFileId = $arrFileList[$index]['PK_FILE'];
				$sFileName = $arrFileList[$index]['C_FILE_NAME']; 
				// Tach ten file ra
				if(strpos($sFileName,"!~!") == 0){
					$file_name = $sFileName;
				}else{
					$arrFilename = explode('!~!',$sFileName);					
					$file_name = $arrFilename[1];
					$file_id   = explode("_", $arrFilename[0]);
				}							
				//Get URL
				if($urlModule!=''){
					$sActionUrl = $objConfig->_setAttachFileUrlPath().$urlModule . $file_id[0] . "/" . $file_id[1] . "/" . $file_id[2] . "/" . $sFileName;
				}else{
					$sActionUrl = $objConfig->_setAttachFileUrlPath() . $file_id[0] . "/" . $file_id[1] . "/" . $file_id[2] . "/" . $sFileName;	
				}
				//
				$strHTML = $strHTML . "<tr id='tr_line_exist' name = 'tr_line_exist'><td colspan='2' class='normal_link'>";
				if ($psHaveUpLoadFields){
					$strHTML = $strHTML . "<input type='checkbox' name='chk_file_attach_exist_id' id = '' value='$sFileName'>";				
				}
				$strHTML = $strHTML . "<a href='$sActionUrl' > $file_name  </a></td></tr>";
			}
		}		
		//Them moi
		if ($psHaveUpLoadFields){
			//Vong lap hien thi cac file dinh kem se them vao van ban
			for($index = 0; $index<$piMaxNumberAttachFile; $index++){					
				if ($index < 1 ) {
					$v_str_show="block";
				}else{
					$v_str_show="none";
				}
				$strHTML = $strHTML . "<tr name = 'tr_line_new' id='tr_line_new' style='display:$v_str_show'><td><input type='checkbox' name='chk_file_attach_new_id' id = 'chk_file_attach_new_id' value=$index></td>";
				$strHTML = $strHTML . "<td><input class='textbox_file' type='file' name='FileName$index' id = 'FileName$index' optional='true' size = '" . $size . "'></td></tr>";
			}	
			$strHTML = $strHTML . "<tr align='center'><td colspan='2' align='center'><a onclick='$psGotoUrlForAddFile' class='small_link'>Th&#234;m file</a>";
			$strHTML = $strHTML . "	<a onclick='$psGotoUrlForDeleteFile' class='small_link'>X&#243;a file</a></td></tr>";
		}
			$strHTML = $strHTML . "</table>";
		//echo htmlspecialchars($strHTML);//exit;
		return $strHTML;
	}	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $arrFileList
	 * @param unknown_type $piCountFile
	 * @param unknown_type $piMaxNumberAttachFile
	 * @param unknown_type $psHaveUpLoadFields
	 * @param unknown_type $size
	 */
	public function DocSentAttachOneFile($arrFileList, $piCountFile, $piMaxNumberAttachFile = 10, $psHaveUpLoadFields = true, $size, $urlModule){		
		
		$psGotoUrlForDeleteFile = "javascript:delete_row(document.getElementsByName(\"tr_line_image_new\"),document.getElementsByName(\"chk_file_image_attach_new_id\"),document.getElementById(\"hdn_deleted_new_image_file_id_list\"));";
		$psGotoUrlForAddFile = "javascript:add_row(document.getElementsByName(\"tr_line_image_new\"),1);";
		
		$strHTML = $strHTML . "<table width='75%' cellpadding='0' cellspacing='0'>";	
		//Tao doi tuong thong tin config
		$objConfig = new Sys_Init_Config();
		
		//ID File dinh kem		
		if (($piCountFile>0) && ($arrFileList != '')){		
			$sFileId = $arrFileList[0]['PK_FILE'];
			$sFileName = $arrFileList[0]['C_FILE_NAME']; 
			// Tach ten file ra
			if(strpos($sFileName,"!~!") == 0){
				$file_name = $sFileName;
			}else{
				$arrFilename = explode('!~!',$sFileName);					
				$file_name = $arrFilename[1];
				$file_id   = explode("_", $arrFilename[0]);
			}							
			//Get URL
			// Goi thu tuc xu ly khi xoa cac file da co
			$psGotoUrlForDeleteFile = $psGotoUrlForDeleteFile . "delete_image_exist(document.getElementsByName(\"tr_line_image_exist\"),document.getElementsByName(\"tr_line_image_new\"),\"".$sFileName."\",\"" . $_SERVER['REQUEST_URI'] . "\",\"" . $urlModule . "\");";
			$linkImg = " <img src = '" . $objConfig->_setAttachFileUrlPath(). "../images/file_attach.gif'/>";
			$sActionUrl = $objConfig->_setAttachFileUrlPath(). $urlModule . $file_id[0] . "/" . $file_id[1] . "/" . $file_id[2] . "/" . $sFileName;		
			$strHTML = $strHTML . "<tr id='tr_line_image_exist' name = 'tr_line_image_exist' style='display:block'><td colspan='2' class='normal_link'>";
			$strHTML = $strHTML . $linkImg . "<a href='$sActionUrl' style='color:#0033CC'> $file_name  </a><a onclick='$psGotoUrlForDeleteFile' class='small_link'>&nbsp;&nbsp;&nbsp;&nbsp;X&#243;a</a></td></tr>";
			$strHTML = $strHTML . "<tr name = 'tr_line_image_new' id='tr_line_image_new' style='display:none'><td><input type='checkbox' name='chk_file_image_attach_new_id' id = 'chk_file_image_attach_new_id' value=0></td>";
			$strHTML = $strHTML . "<td><input class='textbox' type='file' name='FileImageName0' id = 'FileImageName0' optional='true' size = '" . $size . "'>";
			$strHTML = $strHTML . "	</td></tr>";
		}else{
			$strHTML = $strHTML . "<tr name = 'tr_line_image_new' id='tr_line_image_new' style='display:block'><td><input type='checkbox' name='chk_file_image_attach_new_id' id = 'chk_file_image_attach_new_id' value=0></td>";
			$strHTML = $strHTML . "<td><input class='textbox' type='file' name='FileImageName0' id = 'FileImageName0' optional='true' size = '" . $size . "'>";
			$strHTML = $strHTML . "	</td></tr>";
		}		
		$strHTML = $strHTML . "</table>";
		return $strHTML;
	}	
	/**	 
	 *
	 */		
public function CheckLogin($url){	
		//Tao bien Zend_Session_Namespace	
		if(!isset($_SESSION['varCheckLogin'])){						
			Zend_Loader::loadClass('Zend_Session_Namespace');
			$SesCheckLogin = new Zend_Session_Namespace('varCheckLogin');
		}
		$objConfig = new Sys_Init_Config();
		$sLoginUrl = $objConfig->_setUserLoginUrl();	
		//var_dump($_SESSION['staff_id']);
		//echo $url.'<br>'.$sLoginUrl; exit;
		if(($url!=$sLoginUrl) && ((!isset($_SESSION['staff_id']) || is_null($_SESSION['staff_id']) || $_SESSION['staff_id'] == ''))){?>
			<script type="text/javascript">
				 UrlRes = '<?php echo $objConfig->_setUserLoginUrl() ?>';					 
				 window.location = UrlRes; 					
			</script><?php	
		}
	}
	/**
	 * Get URI For Application	 
	 */
public function curPageURL() {
		$pageURL = 'http';
		if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
			$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			//Trong truong hop chay cong 8080
			$pageURL .= $_SERVER["SERVER_NAME"].":8080".$_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"].":8080" . $_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}
 	/** 	
 	 * @param unknown_type $arrLeader
 	 * @param unknown_type $leaderIdList
 	 * @param unknown_type $leaderIdeaList
 	 * @return Danh sach cac checkbox LANH DAO va danh sach Y KIEN CHI DAO tuong ung
 	 */
	public function generateUnitLeaderList($arrLeader, $leaderIdList = "" , $leaderIdeaList = ""){
		
		//var_dump($arrLeader);			
		$strHTML ="";	
		$strHTML .= $this->formHidden("ds_lanh_dao","",array("xml_data"=>"true", "optional" =>"true", "xml_tag_in_db"=>"ds_lanh_dao"));
		$strHTML .= $this->formHidden("ds_y_kien","",array("xml_data"=>"true", "optional" =>"true", "xml_tag_in_db"=>"ds_y_kien"));
		?>
		<table cellpadding="0" cellspacing="0" border="0" width="98%" align="center" class="list_table2" id="table1">
		<?php
		$arrConst = $this->arrConst; 
		$delimitor = $this->delimitor;//Lay ky tu phan cach giua cac phan tu
		//Hien thi cac cot cua bang hien thi du lieu
		$StrHeader = explode("!~~!",$this->GenerateHeaderTable("30%". $delimitor . "70%" 
										,$arrConst['_LANH_DAO_PHAN_CONG'] . $delimitor .$arrConst['_Y_KIEN_CHI_DAO']
										,$delimitor));
		echo $StrHeader[0];	
									
		echo $StrHeader[1]; //Hien thi <col width = 'xx'><..
		$v_current_style_name = "round_row";
		//Duyet cac phan tu mang danh sach LANH DAO DON VI 	
		for($i = 0; $i<sizeof($arrLeader); $i++){				
			//Checked gia tri
			$sChecked = "";
			$sIdea = "";				
			//Kiem tra xem Hieu chinh hay la them moi
			if(trim($leaderIdList) != ""){			
				//Danh sach Id Lanh dao luu trong CSDL
				$arrLeaderInDb = explode(",",$leaderIdList);				
				//Danh sach Y kien Lanh dao luu trong CSDL
				$arrIdeaInDb = explode("!#~$|*",$leaderIdeaList);							
				for ($index = 0;$index < sizeof($arrLeaderInDb);$index++){
					if ($arrLeaderInDb[$index] == $arrLeader[$i]['id']){
						$sChecked = "checked";
						$sIdea = $arrIdeaInDb[$index];
					}
				}
			}	
			$leaderId = $arrLeader[$i]['id'];
			
			if ($v_current_style_name == "odd_row"){
				$v_current_style_name = "round_row";
			}else{
				$v_current_style_name = "odd_row";					
			}
			
			$strHTML = $strHTML. "<tr class='<?=$v_current_style_name?>'>";	
			
			
			$strHTML = $strHTML. "<td style='margin-top:5px;'><input $sChecked type='checkbox' id='chk_multiple' name='chk_multiple'  xml_data='false' optional = 'true' value='$leaderId'  xml_tag_in_db_name =''>&nbsp;&nbsp;".$arrLeader[$i]['position_code'].' - '. $arrLeader[$i]['name']."</td>";						
			//$strHTML = $strHTML. "<td style='margin-top:5px;'><input $sChecked type='checkbox' id='$leaderId' name='chk_multiple'  xml_data='false' optional = 'true' value='$leaderId'  xml_tag_in_db_name =''>&nbsp;&nbsp;<label for = '$leaderId'>".$arrLeader[$i]['position_code'].' - '. $arrLeader[$i]['name']."</label></td>";
			
			//Y kien			
			$strHTML = $strHTML. "<td><input style='width:99.4%;margin-top:5px;'type='textbox' id='txt_multiple' name='txt_multiple' xml_data='false'  xml_tag_in_db_name ='' value='$sIdea' optional = 'true' ></td>";

			$strHTML = $strHTML."</tr>";		
		
		}
		$strHTML = $strHTML."<tr><td height='5'></td></tr>";
		$strHTML = $strHTML."</table>";	   
		return $strHTML;					
	}
	 	
 	/**
 	 *
 	 * @param  $pGroupUser
 	 * @param  $psPositionLeader
 	 * @return Danh sach LANH DAO
 	 */
	public function docGetAllUnitLeader($pGroupUser = "",$sSessionName = "arr_all_staff"){
		$i = 0;
		$pPositionGroupCode = $pGroupUser;
		if($pPositionGroupCode == ""){
			$pPositionGroupCode = "LANH_DAO_BO";
		}
		//Kiem tra CHuc vu thuoc nhom khong?
		foreach($_SESSION[$sSessionName] as $staff){	
			if(Sys_Library::_listHaveElement($pGroupUser,$staff['position_group_code'],",")){				
				$arrUnitLeader[$i] =  $staff;				
				$i++;				
			}
		}
		return $arrUnitLeader;	
	}	
	/**	
 	 * Idea : Ham tao chuoi HTML sinh ra danh sach cac multiple_checkbox cua cac DON VI
	 *
	 * @param unknown_type $arrUnit
	 * @param unknown_type $unitIdList
	 * @return Danh sach cac multiple_checkbox cua cac DON VI
	 */
	public function DocGenerateMultipleCheckbox($arrUnit, $unitIdList = "", $TagName = "ds_don_vi"){
		$strHTML ="";
		$strHTML = $strHTML . "<tr><td colspan='10' style='display:none;'><input type='text' id = '$TagName' name='$TagName' value='' hide='true'  xml_data='true' xml_tag_in_db='$TagName' optional='true' message=''></td></tr>";		
		//Dat style cho cac row
		$v_current_style_name == "round_row";
		
		//Duyet cac phan tu mang danh sach DON VI 			
		for($i = 0; $i<sizeof($arrUnit); $i++){				
			//Checked gia tri
			$sChecked = "";
			//Kiem tra xem Hieu chinh hay la them moi
			if(trim($unitIdList) != ""){
				//Danh sach Id DON VI luu trong CSDL
				$arrUnitInDb = explode(",",$unitIdList);
				for ($index = 0;$index < sizeof($arrUnitInDb);$index++){
					if ($arrUnitInDb[$index] == $arrUnit[$i]['id']){
						$sChecked = "checked";
					}
				}
			}			
			$unitId = $arrUnit[$i]['id'];
			if($i % 2 == 0 ){
				if ($v_current_style_name == "round_row"){
					$v_current_style_name = "odd_row";
				}else{
					$v_current_style_name = "round_row";
				}				
				$strHTML = $strHTML. "<tr class='" . $v_current_style_name . "'>";				
			}			
			$strHTML = $strHTML. "<td><input $sChecked  type='checkbox' id='chk_multiple_checkbox' name='chk_multiple_checkbox'  xml_data='true' optional = 'true' value='$unitId'  xml_tag_in_db_name ='$TagName'  nameUnit = '" . $arrUnit[$i]['name'] . "'>" . $arrUnit[$i]['name'] . "</td>";			
			if($i % 2 <> 0 ){				
				$strHTML = $strHTML. "</tr>";	
			}			
		}   
		return $strHTML;
	}
	
	/** 	
 	 * Idea : Ham tao chuoi HTML lay ra danh sach cac checkbox LANH DAO 
 	 * @param  $arrLeader
 	 * @param  $leaderIdList
 	 * @return Danh sach cac checkbox LANH DAO 
 	 */
	public function docGenerateLeaderList($arrLeader, $leaderIdList = "" ){
		$strHTML ="";	
		$strHTML .= $this->formHidden("ds_lanh_dao","",array("xml_data"=>"true", "optional" =>"true", "xml_tag_in_db"=>"ds_lanh_dao"));
		//Duyet cac phan tu mang danh sach LANH DAO DON VI 	
		for($i = 0; $i<sizeof($arrLeader); $i++){				
			//Checked gia tri
			$sChecked = "";
			//Kiem tra xem Hieu chinh hay la them moi
			if(trim($leaderIdList) != ""){			
				//Danh sach Id Lanh dao luu trong CSDL
				$arrLeaderInDb = explode(",",$leaderIdList);											
				for ($index = 0;$index < sizeof($arrLeaderInDb);$index++){
					if ($arrLeaderInDb[$index] == $arrLeader[$i]['id']){
						$sChecked = "checked";
					}
				}
			}	
			$leaderId = $arrLeader[$i]['id'];
			$strHTML = $strHTML. "<tr>";			
			$strHTML = $strHTML. "<td><input $sChecked type='checkbox' id='chk_multiple' name='chk_multiple'  xml_data='false' optional = 'true' value='$leaderId'  xml_tag_in_db_name ='' >".$arrLeader[$i]['position_name'].' - '. $arrLeader[$i]['name']."</td></tr>";			
		}   
		return $strHTML;					
	}	  
	 function doc_search_ajax($arrRes, $ColumnIdRes, $ColumnTexRes, $TextRes,$hndRes,$single = 1,$sColumName = "",$editable = 0){
		$sWebsitePart = Sys_Init_Config::_setWebSitePath();
		$sHtmlRes = '';
		$sHtmlRes = $sHtmlRes . ' <script type="text/javascript">  ';//
		$sHtmlNameId = '';
		$sHtmlNameId = $sHtmlNameId. '  var NameID'.$hndRes.' = new Array(' ;//
		$sHtmlNameText = '';
		$sHtmlNameText = $sHtmlNameText . ' var NameText'.$hndRes.' = new Array(';
		// Ghi Ma va ten ra mot mang
		foreach($arrRes as $arrTemp){
			$sTemp = "";
			if ($sColumName != ""){
				$sPositionCode = $arrTemp[$sColumName];
				if ($sPositionCode != ""){
					$sTemp = $sPositionCode . " - ";
				}
			}	
			 $sHtmlNameId = $sHtmlNameId .'"' . $arrTemp[$ColumnIdRes] . '",' ;
			 $sHtmlNameText = $sHtmlNameText .'"' . $sTemp . $arrTemp[$ColumnTexRes] . '",' ;
		}
		$sHtmlNameId = rtrim($sHtmlNameId,',') . '); ';
		$sHtmlNameText = rtrim($sHtmlNameText,',') . '); ';
		$sHtmlRes = $sHtmlRes . $sHtmlNameId . $sHtmlNameText .' ';
		$sHtmlRes = $sHtmlRes .' obj'.$hndRes.'= new actb(document.getElementById(\''.$TextRes.'\'),document.getElementById(\''.$hndRes.'\'),NameID'.$hndRes.',NameText'.$hndRes.',\'FillProduct'.$hndRes.'(\',\''.$single.'\',\''.$editable.'\',\''.$sWebsitePart.'\');';
		$sHtmlRes = $sHtmlRes .' function FillProduct'.$hndRes.'(v_id){}';
		$sHtmlRes = $sHtmlRes . '</script>';
		//echo htmlspecialchars($sHtmlRes);
		return  $sHtmlRes;
		
	}
	  /**	
	 * Idea : Lay ngay dau tien trong tuan
	 *
	 * @return Ngay dau tuan 
	 */
	
	function getFirstDayOfWeek($format = ""){
		$firstDayOfWeek = "";
		$currentWeek = date("W"); // thu tu tuan hien tai cua nam
		$currentYear = date("Y"); // nam hien tai
		$orderDate = 0; // xac dinh ngay dau tuan (thu 2)
		$firstDayOfWeek = Sys_Library::_getAnyDateOnWeekOfYear($currentYear,$currentWeek,$orderDate);
		return $firstDayOfWeek;
	}
	/**	
	 * @param $nameStrColor :  Tu cantim kiem(trich yeu, do mat, noi nhan, noi gui)
	 * @param $nameStrInput : Chuoi tu tim thay tu Tu can tim kiem
	 * @return Xau ki tu duoc to mau o Tu can tim kiem
	 */
	public function searchStringColor($nameStrColor,$nameStrInput){ 
		$i =0;
		$j =0;
		$arrSubject ="";
		$arrSubject = explode(" ",$nameStrInput);
		$arrSearch = explode(" ",$nameStrColor);
		for($i =0; $i < sizeof($arrSearch); $i ++){
			$nameStrOutput = "";
			for($j =0; $j < sizeof($arrSubject); $j ++){
				if(sizeof($arrSearch) > 1){
					$str = $arrSearch[$i];
				}else{
					$str = $nameStrColor;
				}
				if(Sys_Library::Lower2Upper(trim($arrSubject[$j])) == Sys_Library::Lower2Upper(trim($str))){
					$strText = "<label style = 'font-weight:bold; font-size:17px;'>" . $arrSubject[$j] . "</label>";
					$arrSubject[$j] = $strText;
				}
				$nameStrOutput .= $arrSubject[$j] . " ";
			}
		}
		return 	$nameStrOutput;	
	}
	
	/**
	 * Enter to mau tu khoa tim kiem..
	 *
	 * @param $nameStrColor :  Tu cantim kiem(so, ki tu,ngay thang nam)
	 * @param $nameStrInput : Chuoi tu tim thay tu Tu can tim kiem
	 * @return Xau ki tu duoc to mau o Tu can tim kiem
	 */
	public function searchCharColor($nameStrColor,$nameStrInput){ 
		$strText = "<label style = 'background-color:#99FF99'>" . $nameStrColor . "</label>";
		$nameStrOutput .= str_replace(Sys_Library::Lower2Upper(trim($nameStrColor)),Sys_Library::Lower2Upper(trim($strText)),trim($nameStrInput));
		return 	$nameStrOutput;	
	}
	
	/**	 
	 * Lay ra danh sach Ten + Chuc vu tu danh sach Id staff
	 */
	public function getNamePositionStaffByIdList($sStaffIdList){
		$arrStaffId = explode(',',$sStaffIdList);
		$sNamePositionStaffList= "";
		for($i=0;$i< sizeof($arrStaffId);$i++){  
			$sName = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$arrStaffId[$i],'name');  
			$sPosition = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$arrStaffId[$i],'position_code');
	    	$sNamePositionStaffList = $sNamePositionStaffList .'!#~$|*'. $sPosition . ' - ' . $sName;
	    }       
	    $sNamePositionStaffList = substr($sNamePositionStaffList,6); 
		return $sNamePositionStaffList;		
	}
	
	/**
	 * Lay ra danh sach Ten phong ban tu danh sach Id phong ban
	 */
	public function getNameUnitByIdUnitList($sUnitIdList){
		$arrUnitId = explode(',',$sUnitIdList);
		$sNameUnitList= "";
		//var_dump($_SESSION['arr_all_unit']);
		for($i=0;$i< sizeof($arrUnitId);$i++){  
			$sNameUnit = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_unit'],$arrUnitId[$i],'name');  
	    	$sNameUnitList = $sNameUnitList .'!#~$|*'. $sNameUnit;
	    }       
	    $sNameUnitList = substr($sNameUnitList,6); 
		return $sNameUnitList;		
	}
	
	/**
	 * Idea : Tao phuong thuc chuyen doi danh sach ten can bo thanh ID tuong ung
	 *
	 * @param $sStaffNameList : Chuoi luu danh sach ten can bo (phan tach boi dau ';')
	 * @return Danh sach ID can bo tuong ung voi list name
	 */
	public function convertStaffNameToStaffId($sStaffNameList = ""){
		$sStaffIdList = "";
		if (trim($sStaffNameList) != ""){
			//chuyen doi mang danh sach ten can bo ra mang mot chieu
			$arr_staff_name = explode(";",$sStaffNameList);
			for ($index = 0; $index<sizeof($arr_staff_name); $index++){
				foreach($_SESSION['arr_all_staff'] as $staff){
					$sStaffPositionName = $staff['position_code'] . " - " . $staff['name'];
					if (trim($sStaffPositionName) == trim($arr_staff_name[$index])){
						$sStaffIdList .= $staff['id'] . ",";
					}
				}
			}	
			$sStaffIdList = substr($sStaffIdList,0,strlen($sStaffIdList)-1); 
		}
		return $sStaffIdList;
	}
	
 //public function convertStaffIdToStaffName($sStaffNameList = ""){
 //Convert ID USer to Staff Name
 public function convertStaffIdToStaffName($sIdList = ""){
		$sTelMobileList = "";
		if (trim($sIdList) != ""){
			//chuyen doi mang danh sach ten can bo ra mang mot chieu
			$arrId = explode(",",$sIdList);
			for ($index = 0; $index<sizeof($arrId); $index++){
				foreach($_SESSION['arr_all_staff_keep'] as $id){
					if (trim($id['id']) == trim($arrId[$index])){
						$sTelMobileList .= $id['name'] . ",";
					}
				}
			}	
			$sTelMobileList = substr($sTelMobileList,0,-1); 
		}
		return $sTelMobileList;
	}
	
	
	/**
	 * Idea : Hien thi thong tin co ban cua mot VB
	 *
	 * @param $sDocumentId : Id cua VB
	 * @return Thong tin co ban cua Vb
	 */		
	public function DocShowInfoDocument($sDocumentId){	
		// Tao doi tuong xu ly du lieu
		$objConn = new  Sys_DB_Connection(); 		
		//Tao duoi tuong trong lop dung chung
		$objLib = new Sys_Library();
		//Lay cac gia tri const
		$ojbSysInitConfig = new Sys_Init_Config();
		$arrConst =	$ojbSysInitConfig->_setProjectPublicConst();
		//Id lanh phong ban 	
		$iUnitId = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');
		try {
			// Cau lenh sql
			$sql = "Exec Doc_DocReceivedGetSingle " . "'" . $sDocumentId . "'" . ",'" . $iUnitId . "'";
			// Thuc hien cau lenh sql
			$arrTemp = $objConn->adodbExecSqlString($sql); 
			//File dinh kem
			$strFileName = $arrTemp['C_FILE_NAME'];
			$sFile = '';
			if($strFileName != ''){
				$sFile 	= Sys_Library::_getAllFileAttach($strFileName,"!#~$|*","!~!",Sys_Init_Config::_setAttachFileUrlPath());	
			}
			//Lay y kien LANH DAO VAN PHONG
			$sLeaderOfficeIdea = $arrTemp['C_LEADER_OFFICE_IDEA'];
			//Lay danh sach y kien cua Lanh dao
			$sLeaderIdeaList = substr($arrTemp['C_IDEA_LIST'],6);
			//Lay danh sach Ten + Chuc vụ Lanh dao
			$sLeaderPositionNameList = substr($arrTemp['C_LEADER_POSITION_NAME_LIST'],0,-1);
			//Lay danh sach HAN XU LY
			$sAppointedDateList = substr($arrTemp['C_APPOINTED_DATE_LIST'],0,-1);
			// In ra ket qua
			$ResHtmlString = "<div class = 'large_title' style='padding-left: 1px; text-align: left; float: left;'>THÔNG TIN VĂN BẢN</div>";
			$ResHtmlString = $ResHtmlString . "<table class='table_detail_doc' border='1' width='98%'>";
			$ResHtmlString = $ResHtmlString . "<col width='22%'><col width='18%'><col width='10%'><col width='50%'>";
			//$ResHtmlString = $ResHtmlString . "<tr class='large_title'><td colspan='10'style = 'padding-left:0px;text-align:left;HEIGHT:18pt;'><b>" . "" . "</b></td></tr>";	
			$ResHtmlString = $ResHtmlString . "<tr class='normal_label'><td class='normal_label' style = 'padding-left:10px;HEIGHT: 18pt;'>" . $arrConst['_SO_KY_HIEU']."</td>";
			$ResHtmlString = $ResHtmlString . "<td class='normal_label' style = 'HEIGHT: 18pt;'align='left'>" .$objLib->_replaceBadChar($arrTemp['C_SYMBOL']) . "</td>"; 
			$ResHtmlString = $ResHtmlString . "<td class='normal_label' style = 'padding-left:10px;HEIGHT: 18pt;'>" . $arrConst['_NOI_GUI']."</td>";
			$ResHtmlString = $ResHtmlString . "<td class='normal_label' style = 'HEIGHT: 18pt;'align='left'>" .$objLib->_replaceBadChar($arrTemp['C_AGENTCY_NAME']) . "</td></tr>"; 
			$ResHtmlString = $ResHtmlString . "<tr class='normal_label'><td class='normal_label' style = 'padding-left:10px;HEIGHT: 18pt;'>".$arrConst['_TRICH_YEU']."</td>";
			$ResHtmlString = $ResHtmlString . "<td class='normal_label' style = 'HEIGHT: 18pt;'align='left' colspan='3'>" .$objLib->_replaceBadChar($arrTemp['C_SUBJECT']) . "</td></tr>";
			if($sFile != ''){
				$ResHtmlString = $ResHtmlString . "<tr class='normal_label'><td class='normal_label' style = 'padding-left:10px;HEIGHT: 18pt;'>".$arrConst['_FILE_DINH_KEM']."</td>";
				$ResHtmlString = $ResHtmlString . "<td style='color:#0000FF' class='normal_label' style = 'HEIGHT: 18pt;'align='left' colspan='3'>" .$sFile."</td></tr>";						
			}
			if($sLeaderIdeaList != ''){
				//Mang luu y kien 
				$arrLeaderIdea = explode('!#~$|*',$sLeaderIdeaList);
				//Mang luu Ten + Chuc vu
				$arrLeaderPositionName = explode(';',$sLeaderPositionNameList);
				//Mang luu Han xu ly
				$arrAppointedDate = explode(';',$sAppointedDateList);
				//Hien thi Ten + Chuc vu + Y kien + Han xu ly 
				$ResHtmlString = $ResHtmlString ."<tr class='normal_label'><td rowspan = '".sizeof($arrLeaderIdea)."' class='normal_label' style = 'padding-left:10px;HEIGHT: 18pt;'>".$arrConst['_Y_KIEN_CHI_DAO']."</td>";
				for ($index = 0; $index <sizeof($arrLeaderIdea); $index ++){
				//for ($index = sizeof($arrLeaderIdea)-1; $index >=0; $index --){
					if($arrAppointedDate[$index] !='' and $arrAppointedDate[$index] !='01/01/1900'){
						$ResHtmlString = $ResHtmlString . "<td  class='normal_label' style = 'HEIGHT: 18pt;'align='left' colspan='3'><I>" .$arrLeaderPositionName[$index].": ".$arrLeaderIdea[$index]."<font color= red> (Hạn xử lý: ".$arrAppointedDate[$index] .")"."</font></I></td></tr>";
					}else{
						$ResHtmlString = $ResHtmlString . "<td  class='normal_label' style = 'HEIGHT: 18pt;'align='left' colspan='3'><I>" .$arrLeaderPositionName[$index].": ".$arrLeaderIdea[$index]."</I></td></tr>";	
					}
				}
			}
			//Y kien Lanh dao van phong
			if($sLeaderOfficeIdea !=''){
				$ResHtmlString = $ResHtmlString . "<tr class='normal_label'><td class='normal_label' style = 'padding-left:10px;HEIGHT: 18pt;'>".$arrConst['_YK_LD_VP']."</td>";
				$ResHtmlString = $ResHtmlString . "<td class='normal_label' style = 'HEIGHT: 18pt;'align='left' colspan='3'><I>" .$sLeaderOfficeIdea."</I></td></tr>";						
			}
			//Y kien lanh dao cua Lanh dao phu trach (Lanh dao phong ban)
			$sLeaderUnitIdea = $arrTemp['C_LEADER_UNIT_IDEA'];
			if($sLeaderUnitIdea != null and $sLeaderUnitIdea !=''){
				$sPositionNameLeaderUnit			= $arrTemp['C_LEADER_UNIT_POSITION_NAME'];
				$ResHtmlString = $ResHtmlString . "<tr class='normal_label'><td class='normal_label' style = 'padding-left:10px;HEIGHT: 18pt;'>".$arrConst['_Y_KIEN_LANH_DAO_PHONG']."</td>";
					if($arrTemp['C_APPOINTED_DATE_UNIT'] !='' and $arrTemp['C_APPOINTED_DATE_UNIT'] !='01/01/1900'){
						$ResHtmlString = $ResHtmlString . "<td  class='normal_label' style = 'HEIGHT: 18pt;'align='left' colspan='3'><I>" .$sPositionNameLeaderUnit.": ".$sLeaderUnitIdea."<font color= red> (Hạn xử lý: ".$arrTemp['C_APPOINTED_DATE_UNIT'].")"."</font></I></td></tr>";
					}else{
						$ResHtmlString = $ResHtmlString . "<td  class='normal_label' style = 'HEIGHT: 18pt;'align='left' colspan='3'><I>" .$sPositionNameLeaderUnit.": ".$sLeaderUnitIdea."</I></td></tr>";	
					}						
			}
			$ResHtmlString = $ResHtmlString . "</table>";	
			// Tra lai gia tri
			return $ResHtmlString;							
		}catch (Exception $e){
			$e->getMessage();
		}
	}

	/**
	 * Idea : Tao phuong thuc chuyen doi danh sach ten phong ban thanh ID tuong ung
	 *
	 * @param $sUnitNameList : Chuoi luu danh sach ten phong ban (phan tach boi dau ';')
	 * @return Danh sach ID phong ban tuong ung voi list name
	 */
	public function convertUnitNameListToUnitIdList($sUnitNameList = ""){
		$sUnitIdList = "";
		if (trim($sUnitNameList) != ""){
			//chuyen doi mang danh sach ten can bo ra mang mot chieu
			$arrUnitName = explode(";",$sUnitNameList);
			//var_dump($_SESSION['arr_all_unit_keep']); //exit;
			for ($index = 0; $index<sizeof($arrUnitName); $index++){
				foreach($_SESSION['arr_all_unit_keep'] as $unit){
					if (trim($unit['name']) == trim($arrUnitName[$index])){
						$sUnitIdList .= $unit['id'] . ",";
					}
				}
			}	
			$sUnitIdList = substr($sUnitIdList,0,-1); 
		}
		return $sUnitIdList;
	}
	/**
	 * Idea : Lay danh sach can bo cua phong ban
	 * @param $iDepartmentId: ID cua phong can lay
	 * @return Mang chu danh sach can bo cua 1 phong ban
	 */
	function docGetAllDepartmentStaffId($iDepartmentId){
		$i = 0;
		foreach($_SESSION['arr_all_staff_keep'] as $staffId){	
			if ($staffId['unit_id'] == $iDepartmentId){
				$arrDepartmentStaffId[$i] =  $staffId;
				$i++;
			}
		}
		return $arrDepartmentStaffId;	
	}
	/**
	 * Idea : Lay danh sach can bo cua phong ban
	 * @param $positionGroupCode: Nhom lanh dao, $unitID ID phong ban hoac VP, UB
	 * @return Mang chu danh sach lanh dao cua mot phong, ban
	 */
	function docGetAllLeaderDepartment($positionGroupCode,$iDepartmentId){
		$k = 0;
		foreach($_SESSION['arr_all_staff_keep'] as $staffId){	
			if ($staffId['unit_id'] == $iDepartmentId and $staffId['position_group_code'] ==$positionGroupCode ){
				$arrDepartmentStaffId[$k] =  $staffId;
				$k++;
			}
		}
		return $arrDepartmentStaffId;	
	}
	/**	 
	 * Idea : Kiem tra xem NSD dang nhap hien thoi thuoc nhom nao. 1: Can bo du thao, 2: LD Phong ban,3: LD Van phong,4: LD Uy ban
	 * @param $iUserId: id NSD dang nhap hien thoi
	 * @return $iValue
	 */
	function docTestUser($iUserId){
		$ojbSysInitConfig = new Sys_Init_Config();
		$arrPositionConst =	$ojbSysInitConfig->_setLeaderPostionGroup();
		//$IdDistrict = $ojbSysInitConfig->_setParentOwnerId();
		$value = 1;
		//Kiem tra NSD hien thoi co nam trong nhom lanh dao don vi khong?
		$arrLDUyBan    = Sys_Function_DocFunctions::docGetAllUnitLeader($arrPositionConst['_CONST_POSITION_GROUP'],"arr_all_staff");
		if (is_array($arrLDUyBan)){
			foreach($arrLDUyBan as $staffId){	
				if ($staffId['id'] == $iUserId){
					return 4;
				}
			}
		}	
		//Kiem tra NSD hien thoi co nam trong nhom lanh dao Van phong khong?
		$arrLDVanPhong = Sys_Function_DocFunctions::docGetAllUnitLeader($arrPositionConst['_CONST_VAN_PHONG_GROUP'],"arr_all_staff");
		if (is_array($arrLDVanPhong)){
			foreach($arrLDVanPhong as $staffId){	
				if ($staffId['id'] == $iUserId){
					return 3;
				}
			}
		}	
		//Kiem tra NSD hien thoi co nam trong nhom lanh dao phong khong?
		$arrLDPhongBan = Sys_Function_DocFunctions::docGetAllUnitLeader($arrPositionConst['_CONST_PHONG_BAN_GROUP'],"arr_all_staff");
		if (is_array($arrLDPhongBan)){
			foreach($arrLDPhongBan as $staffId){	
				if ($staffId['id'] == $iUserId){
					return 2;
				}
			}
		}	
		/*	
		}else{
			$arrLDPhuongXa = Sys_Function_DocFunctions::docGetAllUnitLeader("LANH_DAO_PHUONG","arr_all_staff_keep");
			if (is_array($arrLDPhuongXa)){
				foreach($arrLDPhuongXa as $staffId){	
					if ($staffId['id'] == $iUserId){
						return 5;
					}
				}
			}	
		}
		*/
		return $value;	
	}
	/**
	 * Idea : Lay nguoi ky cua don vi dang nhap hien thoi
	 * @param $arrSigner: mang nguoi ky cua DANH_MUC_NGUOI_KY
	 * @return $arrResult
	 */
	function docGetSignByUnit($arrSigner){
		$j = 0; $m = 0;
		$arr_all_staff = $_SESSION['arr_all_staff'];
		for ($i=0;$i<sizeof($arrSigner);$i++){	
			for ($m=0;$m<sizeof($arr_all_staff);$m++)	{	
				if ($arrSigner[$i]['C_CODE'] == $arr_all_staff[$m]['id']){
					$arrResult[$j]['C_CODE'] = $arrSigner[$i]['C_CODE'];
					$arrResult[$j]['C_NAME'] = $arrSigner[$i]['C_NAME'];
					$j ++;
					$m = sizeof($arr_all_staff);
				}
			}	
		}
		return $arrResult;
	}
	function _get_item_attr_by_id($p_array, $p_id, $p_attr_name) {
		foreach($p_array as $staff){
			if (strcasecmp($staff['id'],$p_id)==0){
				return $staff[$p_attr_name];
			}
		}
		return "";
	}
	/**	 
	 * @param unknown_type $v_option
	 * @return unknown
	 */
	function doc_get_all_unit_permission_form_staffIdList($v_staff_id_list,$v_option = 'unit'){
		$arr_staff_id = explode(',',$v_staff_id_list);
		$v_return_string = "";
		if($v_option == 'unit'){
			for($i=0;$i< sizeof($arr_staff_id);$i ++){
				$v_return_string = $v_return_string . ',' . Sys_Function_DocFunctions::_get_item_attr_by_id($_SESSION['arr_all_staff_keep'], $arr_staff_id[$i],'unit_id');
			}
			$v_return_string = substr($v_return_string,1);
		}
		return $v_return_string;
		}
	/**	
	 * Idea : Tao phuong thuc Lay danh sach dien thoai tu danh sach ID NSD
	 *
	 * @param $sUnitNameList : Chuoi luu danh sach ten phong ban (phan tach boi dau ';')
	 * @return 
	 */
	public function convertIdListToTelMobileList($sIdList = ""){
		$sTelMobileList = "";
		if (trim($sIdList) != ""){
			//chuyen doi mang danh sach ten can bo ra mang mot chieu
			$arrId = explode(",",$sIdList);
			for ($index = 0; $index<sizeof($arrId); $index++){
				foreach($_SESSION['arr_all_staff_keep'] as $id){
					if (trim($id['id']) == trim($arrId[$index])){
						$sTelMobileList .= $id['tel_mobile'] . ",";
					}
				}
			}	
			$sTelMobileList = substr($sTelMobileList,0,-1); 
		}
		return $sTelMobileList;
	}
	/**
	 * Idea : Tao phuong thuc Lay Ten/chu vu/i tu So dien thoai NSD
	 */
	public function convertTelMobileToName($sTelMobile=""){
				foreach($_SESSION['arr_all_staff_keep'] as $name){
					if (trim($name['tel_mobile']) == trim($sTelMobile)){
						$sPositionName = $name['position_code'] . " - ".$name['name'];
						break;
					}	
				}
		return $sPositionName;

	}
	/**
	 * Idea : Chuoi HTML checkbox gui tin nhac thong bao nhac viec tuc thoi cho LD
	 */
	public function htmlCheckboxSms(){
		$sHtml	= "<input type='checkbox' name='SmsReminder'> Gửi thông báo nhắc việc>";
		return $sHtml;
	}
	/**
	 * Idea : Gui thong bao nhac viec moi qua SMS cho can bo duoc nhac
	 */
	public function sendSmsNewReminder($sPositionName,$sMsg){
		$iFkStaff = self::convertStaffNameToStaffId($sPositionName);
		$sTelMobile  = self::convertIdListToTelMobileList($iFkStaff);
		$iUnitId = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$iFkStaff,'unit_id');
		$sUnitName = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_unit'],$iUnitId,'name');
		$psSql = "Exec Doc_DocSmsSendUpdate ";	
		$psSql .= "'"  . $sTelMobile . "'";
		$psSql .= ",'" . $sMsg . "'";
		$psSql .= ",'Send'";
		$psSql .= ",'" . $sPositionName . "'";
		$psSql .= ",'" . $sUnitName . "'";		
		//echo $psSql; exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($psSql) ; 
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrTempResult;
		
	}
	 function write_td($v_count,$ar_data,$v_style){		
			$v_string_td = "";				
			if($v_style =='1'){
				for($i = 0; $i<$v_count; $i++){
					$v_string_td = $v_string_td."<td style='text-align:justify;' class='newBackgroud'>&nbsp;".Sys_Publib_Library ::_isbreakcontent($ar_data[$i])."</td>";			
				}			
			}else{		
				for($i = 0; $i<$v_count; $i++){
					$v_string_td = $v_string_td."<td style='text-align:justify;'>&nbsp;".Sys_Publib_Library ::_isbreakcontent($ar_data[$i])."</td>";			
			}	
		}
		return 	$v_string_td;			
	}	
 	public function getArrRecieveObj(){
 		$arrGroupConst =	Sys_Init_Config::_setReciveUserGroup();
       	$arrRecieveObj = array();
        $i=0;
		foreach($_SESSION['arr_all_staff_keep'] as $staffId){	
				$arrRecieveObj[$i]['id'] =  'STAFF|~|'.$staffId['id'];
				$unitname = trim(Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_unit_keep'],$staffId['unit_id'],'name'));
				//$arrRecieveObj[$i]['name'] =  $staffId['position_code'].' - '.$staffId['name'].' ('.$unitname.')';
				$arrRecieveObj[$i]['name'] =  $staffId['position_code'].' - '.$staffId['name'];
				$i++;
		}
		foreach($_SESSION['arr_all_unit_keep'] as $unitId){	
			if(($unitId['parent_id']!='')&&($unitId['id']!=Sys_Init_Config::_setParentOwnerId())){
				$arrRecieveObj[$i]['id'] =  'UNIT|~|'.$unitId['id'];
				$arrRecieveObj[$i]['name'] =  $unitId['name'];
				$i++;	
			}
		}
		//Tao nhom toan bo phong ban
		$arrRecieveObj[$i]['id'] =  'PHBA|~|FFFFFFFF-FFFF-FFFF-FFFF-FFFFFFFFFFFF';
		$arrRecieveObj[$i]['name'] =  $arrGroupConst['_FULL_UNIT'];
		$i++;
		//Tao nhom toan bo xa, thi tran
		$arrRecieveObj[$i]['id'] =  'PHXA|~|FFFFFFFF-FFFF-FFFF-FFFF-FFFFFFFFFFFF';
		$arrRecieveObj[$i]['name'] =  $arrGroupConst['_FULL_OWNER'];
		$i++;
		//Tao nhom toan bo lanh dao phong ban
		$arrRecieveObj[$i]['id'] =  'LAPB|~|FFFFFFFF-FFFF-FFFF-FFFF-FFFFFFFFFFFF';
		$arrRecieveObj[$i]['name'] =  $arrGroupConst['_FULL_LEDER_UNIT'];
		$i++;
		//Tao nhom toan bo lanh dao phuong xa
		$arrRecieveObj[$i]['id'] =  'LAPX|~|FFFFFFFF-FFFF-FFFF-FFFF-FFFFFFFFFFFF';
		$arrRecieveObj[$i]['name'] =  $arrGroupConst['_FULL_LEDER_OWNER'];
		return $arrRecieveObj;
	}	
 }	

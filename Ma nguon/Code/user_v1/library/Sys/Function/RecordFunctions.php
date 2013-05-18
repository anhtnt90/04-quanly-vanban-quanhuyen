<?php 
/**
 * @author :QUANGDD
 * @since : 22/10/2010
 * @see : Lop chua cac phuong thuc dung chung cho toan bo cac modul
 *@copyright :Sys VIET NAM JSC
 */ 
 class Sys_Function_RecordFunctions{
	/**
	 * Creater : Sys
	 * Date : 13/06/2009
	 * Idea : Tao phuong thuc kiem tra NSD hien thoi co ton tai trong he quan tri NSD khong?
	 *
	 */		
	public function CheckLogin(){		
		//Tao bien Zend_Session_Namespace	
		if(!isset($_SESSION['varCheckLogin'])){						
			//Tao Zend_Session_Namespace
			Zend_Loader::loadClass('Zend_Session_Namespace');
			$SesCheckLogin = new Zend_Session_Namespace('varCheckLogin');	
		}
		//Check Login
		if(((!isset($_SESSION['staff_id']) || is_null($_SESSION['staff_id']) || $_SESSION['staff_id'] == 0) && !isset($_SESSION['actionLogin']))){
			//Zend_Session::destroy();
			$objConfig = new Sys_Init_Config();
			$_SESSION['actionLogin'] = 1;
			//Kiem tra thong tin NSD?>
			<script type="text/javascript">
				 UrlRes = '<?php echo $objConfig->_setUserLoginUrl() ?>';					 
				 window.location = UrlRes; 					
			</script><?php	
		}	
		return $SesCheckLogin;
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
	 * Enter to mau tu khoa tim kiem..
	 *
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
					$strText = "<label style = 'background-color:#99FF99'>" . $arrSubject[$j] . "</label>";
					$arrSubject[$j] = $strText;
				}
				$nameStrOutput .= $arrSubject[$j] . " ";
			}
		}
		return 	$nameStrOutput;	
	}
 	/**
	 * Creater: KHOINV
	 * Date: 19/05/2011
	 * Discription: Tao folder
	 *
	 * @param $path: noi can tao folder
	 * @param $folderYear: tao ra folder nam
	 * @param $folderMonth: tao ra folder thang
	 * @return tra ve duong dan toi folder
	 */
	public function _createFolder($pathLink, $folderYear, $folderMonth, $sCurrentDay = ""){
		$sPath = '..' . str_replace("/","\\",$pathLink);
		//echo $sPath.$folderYear; //exit;
		if(!file_exists($sPath . $folderYear)){
			mkdir($sPath . $folderYear, 0777);	      
        	$sPath = $sPath . $folderYear;
            if(!file_exists($sPath . chr(92) . $folderMonth)){
        		mkdir($sPath . chr(92) . $folderMonth, 0777);
            }
		}else {
			$sPath = '..' . $sPath . $folderYear;
            if(!file_exists($sPath . chr(92) . $folderMonth)){
        		mkdir($sPath . chr(92) . $folderMonth, 0777);
        	}
		}
		//Tao ngay trong nam->thang
		if(!file_exists($sPath . chr(92) . $folderMonth . chr(92) . $sCurrentDay)){
			mkdir($sPath . chr(92) . $folderMonth . chr(92) . $sCurrentDay, 0777);
		}
		//
		$strReturn = '..' . $pathLink . $folderYear . '/' . $folderMonth . '/' . $sCurrentDay.'/';
		return $strReturn;
	}
 /**
	 * @editer: KHOINV
	 * @param: '@!~!@': phan tach cac file dinh kem khac nhau
	 * @since  : 17/02/2009
	 * @see : Upload Mot mang file attach len o cung
	 * @param :
	 * @param:	$iFileMaxNum: So file toi da de upload
	 * @param :	$sDir:		  Duong dan chua file can upload 
	 * @param :	$sVarName:    Ten cua bien trong <input type="upload" name='$sVarName'>
	 * @return :
	 * 			$sFileNameList:	Mang danh sach ten file da duoc upload len o cung
	 * 
	 * @package : Sys_Publib_Library
	 * 			
	 **/
	public  function _uploadFileAttachList($sListAttach, $sDir, $sVarName = "FileName", $sDelimitor = ","){	//echo $sListAttach;exit;	
		$path = self::_createFolder($sDir,date('Y'),date('m'),date('d'));//echo	'$path:'.$path;
		$sFileNameList = "";
		$arrAttach=explode(',',$sListAttach);
		$i=sizeof($arrAttach);
		if($i==0){
			return $sFileNameList;
		}
		for($index = 0;$index < $i; $index++){
			$random = self::_get_randon_number();
			$sAttachFileName = $sVarName. $index;
			$fodel=date("Y").'_'.date("m").'_'.date("d")."_".date("H").date("i").date("u").$random."!~!";
			$sFullFileName =  $fodel. self::_replaceBadChar($_FILES[$sAttachFileName]['name']);
			// Neu la file
			if($arrAttach[$index]!= "" && (is_file($_FILES[$sAttachFileName]['name'])||$_FILES[$sAttachFileName]['name']!='')){
				//echo 'full file name:'. $sFullFileName; exit;				
				//echo $sFullFileName;exit;
				move_uploaded_file($_FILES[$sAttachFileName]['tmp_name'], $path . self::_convertVNtoEN($sFullFileName));
				$sFileNameList .= $arrAttach[$index].':'. $sFullFileName . $sDelimitor;
			}			
		}
		// xu ly chuoi
		$sFileNameList = substr($sFileNameList,0,strlen($sFileNameList) - strlen($sDelimitor));
		// tra lai gia tri			
		return self::_convertVNtoEN($sFileNameList);			
	}
 	/**
	 * Creater: KHOINV
	 *
	 * @param $strText: chuoi ky tu can chuyen font tu VN sang EN
	 * @return tra ve chuoi khong dau
	 */
    function _convertVNtoEN($strText){
    	$vnChars = array("Ã¡","Ã ","áº£","Ã£","áº¡","Äƒ","áº¯","áº±","áº³","áºµ","áº·","Ã¢","áº¥","áº§","áº©","áº«","áº­","Ã©","Ã¨","áº»","áº½","áº¹","Ãª","áº¿","á»�","á»ƒ","á»…","á»‡","Ã­","Ã¬","á»‰","Ä©","á»‹","Ã³","Ã²","á»�","Ãµ","á»�","Ã´","á»‘","á»“","á»•","á»—","á»™","Æ¡","á»›","á»�","á»Ÿ","á»¡","á»£","Ãº","Ã¹","á»§","Å©","á»¥","Æ°","á»©","á»«","á»­","á»¯","á»±","Ã½","á»³","á»·","á»¹","á»µ","Ä‘","Ã�","ï»¿Ã€","áº¢","Ãƒ","áº ","Ä‚","áº®","áº°","áº²","áº´","áº¶","Ã‚","áº¤","áº¦","áº¨","áºª","áº¬","Ã‰","Ãˆ","áºº","áº¼","áº¸","ÃŠ","áº¾","á»€","á»‚","á»„","á»†","Ã�","ÃŒ","á»ˆ","Ä¨","á»Š","Ã“","Ã’","á»Ž","Ã•","á»Œ","Ã”","á»�","á»’","á»”","á»–","á»˜","Æ ","á»š","á»œ","á»ž","á» ","á»¢","Ãš","Ã™","á»¦","Å¨","á»¤","Æ¯","á»¨","á»ª","á»¬","á»®","á»°","Ã�","á»²","á»¶","á»¸","á»´","Ä�");
    	$enChars = array("a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","e","e","e","e","e","e","e","e","e","e","e","i","i","i","i","i","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","u","u","u","u","u","u","u","u","u","u","u","y","y","y","y","y","d","A","A","A","A","A","A","A","A","A","A","A","A","A","A","A","A","A","E","E","E","E","E","E","E","E","E","E","E","I","I","I","I","I","O","O","O","O","O","O","O","O","O","O","O","O","O","O","O","O","O","U","U","U","U","U","U","U","U","U","U","U","Y","Y","Y","Y","Y","D");
    	for($i =0; $i <sizeof($vnChars); $i ++){
			$strText = str_replace($vnChars[$i], $enChars[$i], $strText);
		}	
		return 	$strText; 
    }
    
    /**
	* Nguoi tao: QUANGDD
	* Ngay tao: 25/10/2010
	* Y nghia:Lay Mang danh muc doi tuong cua mot danh muc
	* Input: Ma danh muc
	* Output: Mang cac doi tuong cua loai danh muc ung voi ma truyen vao
	* $optCache = 1: Luu cache 
	*/
	public function getAllObjectbyListCode($sOwnerCode,$sCode, $optCache = ""){
		// Tao doi tuong xu ly du lieu
		$objConn = new  Sys_DB_Connection(); 
		$sql = "SysLib_ListGetAllbyListtypeCode ";
		$sql = $sql . " '" . $sOwnerCode . "'";
		$sql = $sql . " ,'" . $sCode . "'";
		//echo $sql . '<br>';//exit;
		try {
			$arrObject = $objConn->adodbQueryDataInNameMode($sql,$optCache);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrObject;
	}	
  /**
	 * Creater : phongtd
	 * Date : 20/05/2010
	 * Idea : Tim kiem gia tri trong mot mang   
	 *
	 * @param $arrRes : Mang gia tri
	 * @param $ColumnIdRes : Ma gia tri
	 * @param $ColumnTexRes : Ten gia tri
	 * @param $TextRes : Gia tri tim kiem
	 * @param $hndRes : Hidden luu gia tri
	 * @param $editable : 1 : duoc phep them moi doi tuong, 0: khong duoc phep them moi doi tuong
	 * @param $option : (Neu $option = 1 chi chon mot doi tuong ; $option = 0 thi duoc chon nhieu)
	 * @param $sColumName : Cot du lieu can bo sung them vao text hien thi tren doi tuong Auto Complete Text (vi du: truyen vao gia tri position_code hien thi Ma chuc vu - Ten can b. CT - Nguyen Van A)
	 * @return Xau html 
	 */
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
		$sHtmlRes = $sHtmlRes .' obj'.$hndRes.'= new actb(document.getElementById(\''.$TextRes.'\'),NameText'.$hndRes.',NameText'.$hndRes.',\'FillProduct'.$hndRes.'(\',\''.$single.'\',\''.$editable.'\',\''.$sWebsitePart.'\');';
		$sHtmlRes = $sHtmlRes .' function FillProduct'.$hndRes.'(v_id){}';
		$sHtmlRes = $sHtmlRes . '</script>';
		return  $sHtmlRes;
		
	}
 	/**
	 * Creater : KHOINV
	 * Date : 17/06/2011
	 * Idea : Tao phuong thuc chuyen doi danh sach ten phong ban thanh ID tuong ung
	 *
	 * @param $sUnitNameList : Chuoi luu danh sach ten phong ban (phan tach boi dau ';')
	 * 			$arrRes: mang du lieu
	 * 			$sCode: cot lay gia tri
	 * 			$sName: cot so sanh
	 * @return Danh sach ID phong ban tuong ung voi list name
	 */
	public function convertNameListToIdList($arrRes,$sCode,$sName,$sUnitNameList = ""){
		$sUnitIdList = "";
		if (trim($sUnitNameList) != ""){
			//chuyen doi mang danh sach ten can bo ra mang mot chieu
			$arrUnitName = explode(";",$sUnitNameList);
			//var_dump($arrUnitName); exit;
			for ($index = 0; $index<sizeof($arrUnitName); $index++){
				foreach($arrRes as $unit){
					if (trim($unit[$sName]) == trim($arrUnitName[$index])){
						$sUnitIdList .= $unit[$sCode] . ";";
					}
				}
			}	
			$sUnitIdList = substr($sUnitIdList,0,-1); 
		}
		return $sUnitIdList;
	}
 /*
	 * Nguoi sua: KHOINV
	 * MUC DICH: doi mau nhung tu tim kiem chua het 1 tu
	 * * @param $nameStrColor :  Tu cantim kiem(trich yeu, do mat, noi nhan, noi gui)
	 * @param $nameStrInput : Chuoi tu tim thay tu Tu can tim kiem
	 * @return Xau ki tu duoc to mau o Tu can tim kiem
	*/
 	public function searchStringColor2($nameStrColor,$nameStrInput){
		$i =0;
		$j =0;
		$nameStrOutput ="";
		$nameStrOutput2 ="";
		//$arrSubject = explode(" ",$nameStrInput);
		if(!is_null($nameStrColor) & $nameStrColor!='' & $nameStrInput!=''){
			//mang chua in hoa
			$arrSubject = explode($nameStrColor,$nameStrInput);
			//chuyen ve chu hoa de tim kiem
			$sSeach=Sys_Library::Lower2Upper($nameStrColor);
			$sStr=Sys_Library::Lower2Upper($nameStrInput);
			$arrSearch = explode($sSeach,$sStr);
			for($i =0; $i < sizeof($arrSearch); $i ++){
					//neu chuoi tim kiem co trong mang can tim kiem
					if(sizeof($arrSearch) > 1){
						//lay chuoi can doi mau
						$nameStrOutput .=substr($nameStrInput, strlen($nameStrOutput2),strlen($arrSearch[$i]))  ;
						$nameStrOutput2 .=substr($nameStrInput, strlen($nameStrOutput2),strlen($arrSearch[$i]))  ;
						if(strlen($nameStrInput)>strlen($nameStrOutput)){
							$sSeachColor=substr($nameStrInput, strlen($nameStrOutput),strlen($nameStrColor));
							$strText = "<label style = 'background-color:#99FF99'>" . $sSeachColor . "</label>";
							//$arrSubject[$j] = $strText;
							$nameStrOutput .=  $strText;
							$nameStrOutput2 .=  $sSeachColor;
						}
					}else{
						$nameStrOutput = $nameStrInput;
					}					
			}
		}
		else {
			$nameStrOutput=$nameStrInput;
		}
		return 	$nameStrOutput;	
	}
	//ham dung de gui email
	function smtpmailer($to,$to_name,$from,$pass,$from_name,$subject,$body){  	
		$mail = new Sys_Mail_Phpmailer();
		$mail->IsSMTP(); // set mailer to use SMTP
		$mail->Host = "smtp.gmail.com"; // specify main and backup server
		$mail->Port = 465; // set the port to use
		$mail->SMTPAuth = true; // turn on SMTP authentication
		$mail->SMTPSecure = 'ssl';
		$mail->Username = $from; // your SMTP username or your gmail username
		$mail->Password = $pass; // your SMTP password or your gmail password
		$name = $to_name; // Recipient's name
		$mail->From = $from;
		$mail->FromName = $from_name; // Name to indicate where the email came from when the recepient received
		$mail->AddAddress($to,$name);
		$mail->AddReplyTo($from,$from_name);
		$mail->CharSet  = 'UTF-8';
		$mail->WordWrap = 50; // set word wrap
		$mail->IsHTML(true); // send as HTML
		$mail->Subject = $subject;
		$mail->Body = $body; //HTML Body
		$mail->AltBody = $body; //Text Body
		if(!$mail->Send()){
			return false;
		}
		else{
			return true;
		}
}
 }	
 
<?php 

class Sys_Init_Config{
	
	public function _setCountInMenu(){
		return "4";
	}		
	public function setInfoEmail(){
		$mailboxes = array(
			array(				
				'enable'	=> true,
				'mailbox' 	=> '{imap.gmail.com:993/imap/ssl}INBOX',
				'username' 	=> '',
				'password' 	=> ''
			),	
		);
		return $mailboxes;
	}		
	function ReplaceImap($txt) {
		  $carimap = array("=C3=A9", "=C3=A8", "=C3=AA", "=C3=AB", "=C3=A7", "=C3=A0", "=20", "=C3=80", "=C3=89");
		  $carhtml = array("é", "è", "ê", "ë", "ç", "à", "&nbsp;", "À", "É");
		  $txt = str_replace($carimap, $carhtml, $txt);
		
		  return $txt;
	}	
	function decode_imap_text($str){
			    $result = '';
			    $decode_header = imap_mime_header_decode($str);
			    foreach ($decode_header AS $obj) {
			        $result .= htmlspecialchars(rtrim($obj->text, "\t"));
				}
			    return $result;
			}
	 function getdecodevalue($message,$coding) {
			switch($coding) {
			case 0:
			case 1:
			$message = imap_8bit($message);
			break;
			case 2:
			$message = imap_binary($message);
			break;
			case 3:
			case 5:
			$message=imap_base64($message);
			break;
			case 4:
			$message = imap_qprint($message);
			break;
			}
			return $message;
	}

	function getdataEmail($host,$login,$password,$savedirpath,$delete_emails=false) {
			$savedirpath = str_replace('\\', '/', $savedirpath);
			if (substr($savedirpath, strlen($savedirpath) - 1) != '/') {
			$savedirpath .= '/';
			}
			$mbox = imap_open ($host, $login, $password) or die("can't connect: " . imap_last_error());
			$message = array();
			$message["attachment"]["type"][0] = "text";
			$message["attachment"]["type"][1] = "multipart";
			$message["attachment"]["type"][2] = "message";
			$message["attachment"]["type"][3] = "application";
			$message["attachment"]["type"][4] = "audio";
			$message["attachment"]["type"][5] = "image";
			$message["attachment"]["type"][6] = "video";
			$message["attachment"]["type"][7] = "other";
			//print_r($message);
			$emails = imap_search($mbox,'ALL');
			foreach($emails as $email_number) {
				$structure = imap_fetchstructure($mbox, $email_number , FT_UID);
				$parts = $structure->parts;
				$fpos=2;
				for($i = 1; $i < count($parts); $i++) {
				$message["pid"][$i] = ($i);
				$part = $parts[$i];
				if($part->disposition == "ATTACHMENT") {
				$message["type"][$i] = $message["attachment"]["type"][$part->type] . "/" . strtolower($part->subtype);
				$message["subtype"][$i] = strtolower($part->subtype);
				$ext=$part->subtype;
				$params = $part->dparameters;
				$filename=$part->dparameters[0]->value;
				$mege="";
				$data="";
				$mege = imap_fetchbody($mbox,$email_number,$fpos);
				$filename="$filename";
				$fp=fopen($savedirpath.$filename,"w");
				$data=$this->getdecodevalue($mege,$part->type);
				//print_r($data);
				fputs($fp,$data);
				fclose($fp);
				$fpos+=1;
					}
				}
			}
	imap_close($mbox);
}

	
	
	public function _setCountInArticle(){
		return "10";
	}	
	/**
	 * 
	 */
	public function _setSizeOfImage(){
		return "130";
	}
	/***
	 * @see: Thiet ke Lien ke voi co so du lieu nguoi dung vao CSDL QT NSD
	*/
	public function _setDbLinkUser(){
		return "Sys_Provider.[user_v1]";
	}	
	/**
	 * Khoi tao bien xac dinh duong dan website
	 *	
	 */	
	
	/*
	 * khoi tao duong dan chay thuc te
	 * Domain Name: http://abc.com.vn
	 * */
	public function _setDomainNameUrl(){		
			return "http://localhost:8080";
	}
		
	public function _setWebSitePath(){		
		return "/qlvb_v1/";
	}
	
	/**
	 * Xac dinh duong dan URL toi thu muc chua ISA-LIB
	 *
	 * @return unknown
	 */
	public function _setLibUrlPath(){
		return self::_setWebSitePath() . "public/";
	}

	/**
	 * Xac dinh duong dan URL toi thu muc chua anh dung chung
	 *
	 * @return unknown
	 */
	public function _setImageUrlPath(){
		return self::_setLibUrlPath() . "images/";
	}

	/**
	 * Xac dinh duong dan URL den cac file trong thu muc dinh kem
	 *
	 * @return unknown
	 */
	public function _setAttachFileUrlPath(){
		return self::_setLibUrlPath() . "attach-file/";
	}
	/***
	 * @see: Ten CSDL su dung lay NSD
	*/
	public function _setUser(){
		return "[user_v1]";
	}
	/**
	 * Idea: Lay duong dan luu file XML
	 *
	 * @param $piLevel : Cap thu muc chua file XML
	 */
	public function _setXmlFileUrlPath($piLevel){
		switch($piLevel){
			//Duong dan toi thu muc chua cac file XML tinh tu thu muc goc
		 	 case 0;
				return "xml/";
				break;
			//Duong dan toi thu muc chua file XML tu thu muc hien tai. Thu muc hien tai la thu muc cap 1	
			 case 1;
				return "./xml/";
				break;
			//Duong dan toi thu muc chua file XML tu thu muc hien tai. Thu muc hien tai la thu muc cap 2	
			 case 2;
				return "../../xml/";
				break;
			//Duong dan toi thu muc chua file XML tu thu muc hien tai. Thu muc hien tai la thu muc cap 3	
			 case 3;
				return "../../../xml/";
				break;
			default: 
				return "";
				break;
		}	
	}
	
	/**
	 * Idea: Tao cac hang so dung chung cho viec xu ly JS
	 *
	 * @return Chuoi mo ta JS
	 */
	public function _setJavaScriptPublicVariable(){
		$arrConst = $this->_setProjectPublicConst();		
		$psHtml = "<script>\n";
		$psHtml = $psHtml . "_LIST_DELIMITOR='" . $arrConst['_CONST_LIST_DELIMITOR'] . "';\n";
		$psHtml = $psHtml . "_SUB_LIST_DELIMITOR='" . $arrConst['_CONST_SUB_LIST_DELIMITOR'] . "';\n";
		$psHtml = $psHtml . "_DECIMAL_DELIMITOR='" . $arrConst['_CONST_DECIMAL_DELIMITOR'] . "';\n";
		$psHtml = $psHtml . "_LIST_WORK_DAY_OF_WEEK='" . $arrConst['_CONST_LIST_WORK_DAY_OF_WEEK'] . "';\n";
		$psHtml = $psHtml . "_LIST_DAY_OFF_OF_YEAR='" . $arrConst['_CONST_LIST_DAY_OFF_OF_YEAR'] . "';\n";
		$psHtml = $psHtml . "_INCREASE_AND_DECREASE_DAY='" . $arrConst['_CONST_INCREASE_AND_DECREASE_DAY'] . "';\n";
		
		$psHtml = $psHtml . "_MODAL_DIALOG_MODE='" . $arrConst['_MODAL_DIALOG_MODE'] . "';\n";
		$psHtml = $psHtml . "_GET_HTTP_AND_HOST='" . $arrConst['_GET_HTTP_AND_HOST'] . "';\n";
		$psHtml = $psHtml . "_IMAGE_URL_PATH='" . $arrConst['_CONST_IMAGE_URL_PATH'] . "';\n";
		
		$psHtml = $psHtml . "</script>\n";		
		
		return $psHtml;
	}
	
	/**
	 * @see : Thuc hien viec lay URL day du cua ung dung
	 *
	 * @return unknown
	 */
	public function _getCurrentHttpAndHost(){		
		//
		$sCurrentHttpHost = 'http://'.$_SERVER['HTTP_HOST'].self::_setWebSitePath();	
		//
		return $sCurrentHttpHost;
	}

	/**
	 * Thuc hien lay ten cua don vi Goc
	 * Enter description here ...
	 */
	public function _setOnerName(){
		return  'SỞ THÔNG TIN VÀ TRUYỀN THÔNG PHÚ THỌ';
	}
	/*
	 * HAIDV
	 * Thuc hien gan ten don vi vao bieu mau in an
	 */
	public function _setOnerNameSmall(){
			return  'Phú Thọ,';
		}	
	/*
	 * HAIDV
	 * Thuc hien gan thong tin dia chi don vi
	 */	
	public function _setInfoAddressUnit(){		
		$arrInforAddress = array();
		$arrInforAddress = array("_UNIT_NAME" =>"SỞ THÔNG TIN VÀ TRUYỀN THÔNG PHÚ THỌ",
								 "_ADDRESS"   =>"Đường Nguyễn Tất Thành - P. Tân Dân - TP. Việt Trì - tỉnh Phú Thọ ",
								 "_PHONE"	  =>"",
								 "_FAX"		  =>"",
								 "_EMAIL"	  =>"",
								 "_WEBSITE"   =>"",																
								);
		return $arrInforAddress;
	}	
	/**
	 * Thuc hien lay ma cua don vi Goc
	 * Enter description here ...
	 */
	public function _setOnerCode(){
			return  'STTTT';
	}
	/**	 
	 * @return ID cua don vi cha
	 */
	public function _setParentOwnerId(){		
		return 'FF2FE79D-B4C5-48B2-B21B-D0B8DC0EBCDD';//ID DON VI SO THONG TIN TRUYEN THONG		
	}	
	public function _setLeaderPostionGroup(){
		$arrPublicPosition = array();
		$arrPublicPosition = array("_CONST_MAIN_LEADER_POSITION_GROUP"=>"CT,GD,TB,BT,CTH",
								"_CONST_SUB_LEADER_POSITION_GROUP"=>"PCT,PGD,PTB,TT,PCTH,GDS,PGDS",
								"_CONST_POSITION_GROUP"=>"LANH_DAO_UB_TINH,LANH_DAO_SO,LANH_DAO_UB_QUAN_HUYEN",
								"_CONST_VAN_PHONG_GROUP"=>"LANH_DAO_VP",
								"_CONST_PHONG_BAN_GROUP"=>"LANH_DAO_PHONG_BAN",
								"_CONST_PHUONG_XA_GROUP"=>"LANH_DAO_UB_PHUONG_XA"
								
								);
		return $arrPublicPosition;						
	}
	/*
	 * CUONGNH
	 * Lay hang so cho nhom nguoi nhan
	 */
	public function _setReciveUserGroup(){
		$arrConst = array();
		$arrConst = array("_FULL_UNIT"=>"Toàn bộ phòng ban thuộc sở",
							"_FULL_OWNER"=>"Toàn bộ xã thị trấn",
							"_FULL_LEDER_UNIT"=>"Toàn bộ lãnh đạo phòng ban thuộc sở",
							"_FULL_LEDER_OWNER"=>"Toàn bộ lãnh đạo xã thị trấn",
							);
		return $arrConst;						
	}
	/**
	 * @see : thuc hien lay don vi bao cao
	 * 
	 * 	*/
	public function _setOnerReportName(){
		return  'Huyện xxx, ';
	}
	/**
	 * @see : Thuc hien lay dia chi dang nhap cua user & Duong dan mac dinh vao ung dung
	 * 	*/
	public function _setUserLoginUrl(){
		return self::_getCurrentHttpAndHost() . "login/index/";		
	}	
	public function _setDefaultUrl(){		
		return self::_getCurrentHttpAndHost() . 'notification/addnote/';	
	}
	public function  _setWebServiceUrl(){
		return "";
	}
	public  function _setTimeOut(){
		return 1900;
	}
	public function _setAppCode(){
		return "WEB_BASE";
	}	
	public function _setUrlTempHeaderReport(){		
		return self::_getCurrentHttpAndHost() . "templates/report-template/";
	}
	public function _setUrlAjax(){		
		return self::_getCurrentHttpAndHost() . "application/";
	}
	public function _setProjectPublicConst(){
		$arrPublicConst = array();
		$arrPublicConst = array("_CONST_LIST_DELIMITOR"=>"!#~$|*",
								"_CONST_SUB_LIST_DELIMITOR"=>"!~~!",
								"_CONST_DECIMAL_DELIMITOR"=>",",
								"_CONST_IMAGE_URL_PATH"=>self::_setImageUrlPath(),
								"_CONST_LIST_DAY_OFF_OF_YEAR"=>"+/30/04,+/01/05,+/02/09,+/01/01,-/30/12,-/01/01,-/02/12,-/10/03",
								"_CONST_LIST_WORK_DAY_OF_WEEK"=>"2,3,4,5,6",								
								"_CONST_INCREASE_AND_DECREASE_DAY"=>"1",
								"_TEN_DANG_NHAP"			=>"Tên Đăng Nhập",
								"_MAT_KHAU"					=>"Mật khẩu",
								"_DANG_NHAP"				=>"Đăng nhập",
								"_VI_TRI_HIEN_THI"			=>"Vị trí hiển thị",		
								"_SO_THU_TU"				=>"Số thứ tự",			
								"_TIEU_DE"					=>"Tiêu đề",
								"_DIA_CHI_LIEN_KET"			=>"Địa chỉ liên kết",
								"_TINH_TRANG_HIEN_THI"		=>"Tình trạng hiển thị",		
								"_CAP_NHAT"					=>"Cập nhật",		
								"_TEN_CHUYEN_MUC"			=>"Tên chuyên mục",
								"_CAP_CHUYEN_MUC"			=>"Cấp chuyên mục",
								"_CHUYEN_MUC_GOC"			=>"Chuyên mục gốc",
								"_DIA_CHI_LIEN_KET"			=>"Địa chỉ liên kết",
								"_TIN_BAI_LIEN_QUAN"		=>"Tin bài liên quan",	
								"_VI_TRI_HIEN_THI"			=>"Vị trí hiển thị",	
								"_TINH_TRANG"				=>"Tình trạng hiển thị",		
								"_CHUYEN_MUC"				=>"Chuyên mục",
								"_PHONG_BAN_XU_LY"			=>"Phòng ban xử lý",
								"_Y_KIEN_LD_PHONG_BAN"		=>"Ý kiến lãnh đạo phòng ban",
								"_NGAY_GIAO_VIEC"			=>"Ngày giao việc",
								"_NOI_DUNG_CONG_VIEC"		=>"Nội dung công việc",
								"_TONG_SO_VB"				=>"Tổng số VB",
								"_DANG_XU_LY"				=>"Đang xử lý",
								"_DA_XU_LY_DUNG_HAN"		=>"Đã xử lý đúng hạn",
								"_DA_XU_LY_QUA_HAN"			=>"Đã xử lý quá hạn",
								"_QUA_HAN_CHUA_XU_LY"		=>"Quá hạn chưa xử lý",
								"_SO_KY_HIEU"				=>"Số/ký hiệu",
								"_LANH_DAO_GIAO_VIEC"		=>"Lãnh đạo giao việc",
								"_CHON"						=>"Chọn",
								"_STT"						=>"STT",
								"_NAM"						=>"Năm",
								"_NGAY_BAN_HANH"			=>"Ngày ban hành",
								"_NOI_BAN_HANH"				=>"Nơi ban hành",
								"_QUYEN_XEM"				=>"Quyền xem",
								"_NGUOI_TAO_LAP"			=>"Người tạo lập",
								"_TAT_CA"					=>"Tất cả",
								"_KHAC"						=>"Khác",
								"_MA_HO_SO"					=>"Mã hồ sơ",
								"_LAY_VB_LIEN_QUAN"			=>"Lấy VB liên quan",
								"_TEN_HO_SO"				=>"Tên hồ sơ",
								"_NGUOI_TAO"				=>"Người tạo",
								"_NGAY_TAO"					=>"Ngày tạo",
								"_NGAY_PHAT_HANH"			=>"Ngày phát hành",
								"_NGAY_PHAN_CONG"			=>"Ngày phân công",
								"_THEO_DOI_NHAN_VB"			=>"Theo dõi nhận VB",
								"_NGAY_DANG_KY"				=>"Ngày đăng ký",
								"_NGAY_SOAN_THAO"			=>"Ngày soạn thảo",
								"_NGAY_DU_THAO"				=>"Ngày dự thảo",
								"_NGAY_TRINH"				=>"Ngày trình",
								"_VAN_DE_TRINH"				=>"Vấn đề trình",
								"_SO_KY_HIEU"				=>"Số/ký hiệu",
								"_CAP_GUI"					=>"Cấp gửi",
								"_GUI"						=>"Gửi",
								"_NOI_GUI"					=>"Nơi gửi",
								"_NGAY_GUI"					=>"Ngày gửi",
								"_CHI_TIET_NGUOI_XEM"		=>"Chi tiết người xem",
								"_TINH_TRANG"				=>"Tình trạng",
								"_THOI_GIAN_GUI"			=>"Thời gian gửi",
								"_THOI_GIAN_NHAN"			=>"Thời gian nhận",
								"_LOAI_VAN_BAN"				=>"Loại văn bản",
								"_TRICH_YEU"				=>"Trích yếu",
								"_SO_VAN_BAN"				=>"Sổ văn bản",
								"_THOI_GIAN_HOP"			=>"Thời gian họp",
								"_NGAY_HOP"					=>"Ngày họp",
								"_DIA_DIEM_HOP"				=>"Địa điểm họp",
								"_SO_DEN"					=>"Số đến",
								"_NGAY_DEN"					=>"Ngày đến",
								"_TINH_CHAT"				=>"Tính chất",
								"_DO_KHAN"					=>"Độ khẩn",
								"_HINH_THUC_XU_LY"			=>"Hình thức xử lý",
								"_FILE_DINH_KEM"			=>"File đính kèm",
								"_LINH_VUC"					=>"Lĩnh vực",
								"_SO"						=>"Số",
								"_KY_HIEU"					=>"Ký hiệu",
								"_SO_BAN"					=>"Số bản",
								"_SO_TRANG"					=>"Số trang",
								"_CAP_KY_DUYET"				=>"Cấp ký, duyệt",
								"_NGUOI_KY"					=>"Người ký",
								"_PHONG_BAN_SOAN_THAO"		=>"Phòng ban soạn thảo",
								"_PHONG_BAN_DU_THAO"		=>"Phòng ban dự thảo",	
								"_CAN_BO_SOAN_THAO"			=>"Cán bộ soạn thảo",
								"_NOI_NHAN"					=>"Nơi nhận",
								"_GIA_SO"					=>"Giá số",
								"_HOP_CAP_SO"				=>"Hộp/cặp số",
								"_THONG_TIN_KHAC"			=>"Thông tin khác",
								"_CAN_BO_NHAN"				=>"Cán bộ nhận",
								"_DON_VI_PHONG_BAN_NHAN"	=>"Đơn vị, phòng ban nhận",
								"_NGAY_THUC_HIEN"			=>"Ngày thực hiện",
								"_EXPORT_WEB"				=>"Web",
								"_EXPORT_WORD"				=>"Word",
								"_EXPORT_EXCEL"				=>"Excel",
								"_TU_NGAY"					=>"Từ ngày",
								"_DEN_NGAY"					=>"Đến ngày",
								"_VAO_SO_VB_DEN"			=>"VÀO SỔ VĂN BẢN ĐẾN",
								"_VAO_SO_VB_DI"				=>"VÀO SỔ VĂN BẢN ĐI",
								"_TAO_VB_DIEN_TU"			=>"TẠO VĂN BẢN ĐI",
								"_DANH_SACH_VB_DEN"			=>"DANH SÁCH VĂN BẢN ĐẾN",
								"_DANH_SACH_VB_DI"			=>"DANH SÁCH VB ĐI",
								"_PHAN_PHOI_VB"				=>"Phân phối văn bản", 
								"_YK_LD_VP"					=>"Ý kiến tham mưu",
								"_YK_LD_DV"					=>"Ý kiến lãnh đạo đơn vị",
								"_LANH_DAO_NHAN_VB"			=>"Lãnh đạo nhận văn bản",
								"_DON_VI_XU_LY"				=>"Đơn vị xử lý",
								"_TRANG_THAI"				=>"Trạng thái",
								"_DANG_KY_PHAT_HANH"		=>"Đăng ký phát hành",
								"_CHUYEN_LANH_DAO"			=>"Chuyển lãnh đạo",
								"_LANH_DAO_PHAN_CONG"		=>"Lãnh đạo phân công",
								"_LANH_DAO_PHUONG_XA"		=>"Lãnh đạo phường xã",
								"_Y_KIEN_CHI_DAO"			=>"Ý kiến chỉ đạo",
								"_CHUYEN_PHONG_BAN"			=>"Chuyển phòng ban",
								"_CHUYEN_CAN_BO"			=>"Chuyển cán bộ",
								"_HAN_XU_LY"				=>"Hạn xử lý",
								"_HAN_TRA_LOI"				=>"Hạn trả lời",
								"_SO_NGAY"					=>"Số ngày",
								"_NGAY"						=>"Ngày",
								"_CHON"						=>"Chọn",	
								"_NOI_DANG_KY"				=>"Nơi đăng ký",
								"_CAP_SO"					=>"Cấp số",
								"_COQUAN_PHAT_HANH"			=>"Cơ quan phát hành",
								"_NOI_XU_LY"				=>"Nơi xử lý",
								"_HINH_THUC_PHAN_CONG"		=>"Hình thức phân công",
								"_NOI_NHAN_XU_LY"			=>"Nơi nhận xử lý",
								"_KET_QUA_XU_LY"			=>"Kết quả xử lý",
								"_TRAO_DOI_Y_KIEN"			=>"Trao đổi ý kiến",
								"_TRINH_KY"					=>"Trình ký",
								"_TRANG_THAI_TRINH_DUYET"	=>"Trạng thái phê duyệt",
								"_PHONG_BAN_Y_KIEN"			=>"Phòng ban cho ý kiến",
								"_CAN_BO_Y_KIEN"			=>"Cán bộ cho ý kiến",
								"_VB_LIEN_QUAN"				=>"Văn bản liên quan",
								"_VB_DU_THAO"				=>"Dự thảo văn bản",
								"_GHI_CHU"					=>"Ghi chú",
								"_DON_VI_PHAT_HANH"			=>"Đơn vị phát hành",
								"_Y_KIEN_CUA_CT"			=>"Ý kiến của Chủ tịch",
								"_CAN_BO_XLC"				=>"Cán bộ xử lý chính",
								"_CAN_BO_PHXL"				=>"Cán bộ phối hợp xử lý",
								"_TRANG_THAI_XU_LY"			=>"Trạng thái xử lý",
								"_DANG_XU_LY"				=>"Đang xử lý",
								"_DUYET_VAN_BAN"			=>"Duyệt văn bản",
								"_LANH_DAO_VP"				=>"Chuyển lãnh đạo văn phòng",
								"_TRINH_LANH_DAO_UB"		=>"Trình lãnh đạo đơn vị",
								"_TRA_LAI"					=>"Trả lại",
								"_KET_THUC_XU_LY"			=>"Kết thúc xử lý",
								"_Y_KIEN_LANH_DAO"			=>"Ý kiến lãnh đạo",
								"_KHOI_PHUC_XU_LY"			=>"Khôi phục xử lý",
								"_Y_KIEN_LANH_DAO_PHONG"	=>"Ý kiến lãnh đạo phòng ban",
								"_LANH_DAO_PHONG"			=>"Lãnh đạo phòng ban",
								"_LANH_DAO_UB"				=>"Lãnh đạo đơn vị",
								"_CAN_BO_XU_LY"				=>"Cán bộ xử lý",
								"_DON_VI_TRINH"				=>"Đơn vị trình",
								"_NOI_DUNG_TRINH"			=>"Nội dung trình",
								"_IN_PHIEU_TRINH_KY"		=>"In phiếu trình ký",
								"_GUI_VB_DIEN_TU"			=>"Gửi VB điện tử",
								//Dinh nghia cac nut
								"_LAY_VB_DEN"				=>"Lấy VB đến",
								"_LAY_VB_DI"				=>"Lấy VB đi",
								"_THEM_VB_KHAC"				=>"Thêm VB khác",
								"_GHI_THEM_MOI"				=>"Ghi&Thêm mới",
								"_GHI_THEM_TIEP"			=>"Ghi&Thêm tiếp",
								"_GHI_QUAY_LAI"				=>"Ghi&Quay lại",
								"_GHI_TAM"					=>"<U>G</U>hi tạm",
								"_QUAY_LAI"					=>"Quay lại",
								"_THEM"						=>"Thêm",
								"_SUA"						=>"Sửa",
								"_XOA"						=>"Xóa",
								"_XEM_CHI_TIET"				=>"Xem chi tiết",
								"_IN_PHIEU_XU_LY"			=>"In phiếu xử lý",
								"_IN"						=>"In",
								"_PHAN_PHOI"				=>"Phân phối",
								"_PHAN_CONG"				=>"Phân công",
								"_PHAN_CONG_XU_LY"			=>"Phân công xử lý",
								"_PHAN_PHOI_PHAN_CONG"		=>"Phân phối, phân công",
								"_XU_LY"					=>"Xử lý",
								"_DON_VI_XU_LY"				=>"Đơn vị xử lý",
								"_DON_VI_SOAN_THAO"			=>"Đơn vị soạn thảo",
								"_DON_VI_CHO_Y_KIEN"		=>"Đơn vị cho ý kiến",
								"_Y_KIEN"					=>"Ý kiến",
								"_KET_XUAT"					=>"Kết xuất",
								"_TIM_KIEM"					=>"Tìm kiếm",
								"_GHI"						=>"Ghi",
								"_GUI_TIN_SMS"				=>"Gửi tin SMS",
								"_CAP_NHAT_GUI_TIN_TU_DONG"	=>"Cập nhật gửi tin tự động",	
								"_TEN_CAN_BO"				=>"Tên cán bộ",			
								"_DON_VI"					=>"Đơn vị",	
								"_CONG_VIEC"				=>"Công việc",			
								"_GUI_TIN_TU_DONG"			=>"Gửi tin tự động",		
								"_DIEN_THOAI"				=>"Điện thoại",	
								"_NOI_DUNG"					=>"Nội dung",				
								"_THU_TU"					=>"Thứ tự",	
								//Lich cong tac
								"_SANG"						=>"Sáng",
								"_CHIEU"					=>"Chiều",	
								"_CA_NGAY"					=>"Cả ngày",
								"_BUOI"						=>"Buổi",																	
								"_TUAN"						=>"Tuần",
								"_CHU_NHAT"					=>"Chủ nhật",
								"_THOI_GIAN_BAT_DAU"		=>"Thời gian bắt đầu",
								"_THOI_GIAN_KET_THUC"		=>"Thời gian kết thúc",
								"_NGAY_TRONG_TUAN"			=>"Ngày trong tuần",
								"_TEN_CONG_VIEC"			=>"Tên công việc",
								"_NGUOI_CHU_TRi"			=>"Người chủ trì",
								"_DIA_DIEM"					=>"Địa điểm",
								"_CQ_CB_NOI_DUNG" 			=>"CQ Chuẩn bị nội dung",
								"_TP_THAM_DU" 				=>"Thành phần tham dự",								
								"_DUYET_LICH" 				=>"Duyệt lịch",								
								"_IN_LICH"					=>"In lịch",								
								"_THOI_GIAN"				=>"Thời gian",								
								"_NOI_DUNG_CONG_VIEC"		=>"Nội dung công việc",
								"_CHU_TRI"					=>"Chủ trì",
								"_DIA_DIEM"					=>"Địa điểm",
								"_TRANG_THAI"				=>"Trạng thái",
								"_IN_DANG_WEB"				=>"In dạng Web",
								"_IN_DANG_WORD"				=>"In dạng Word",
								"_DUYET_LICH"				=>"Duyệt lịch",
								//Thay doi mat khau nguoi su dung
								"_XAC_NHAN"					=>"Xác nhận",
								"_TEN_NGUOI_DUNG"			=>"Tên người dùng",
								"_TEN_DANG_NHAP"			=>"Tên đăng nhập",
								"_MK_DANG_DUNG"				=>"Mật khẩu đang dùng",
								"_MK_MOI"					=>"Mật khẩu mới",
								"_NHAC_LAI_MK"				=>"Nhắc lại mật khẩu mới",
								"_HUY"						=>"Hủy",															
								"_GET_HTTP_AND_HOST"=>self::_getCurrentHttpAndHost(),
								"_MODAL_DIALOG_MODE"=>"0");
		return 	$arrPublicConst;					
	}
	
	
function get_attached_file($mbox,$structure,$k,$mid)
{
        $encoding = $structure->parts[$k]->encoding;
        $fileName = strtolower($structure->parts[$k]->dparameters[0]->value);
        $fileSource = base64_decode(imap_fetchbody($mbox, $mid, $k+1));      
        $ext = substr($fileName, strrpos($fileName, '.') + 1);
        //die($ext);
        
        //get mime file type
        switch ($ext) {
        case "asf":
                $type = "video/x-ms-asf";
                break;
        case "avi":
                $type = "video/avi";
                break;
        case "flv":
                $type = "video/x-flv";
                break;
        case "fla":
                $type = "application/octet-stream";
                break;
        case "swf":
                $type = "application/x-shockwave-flash";
                break;          
        case "doc":
                $type = "application/msword";
                break;
        case "docx":
                $type = "application/msword";
                break;
        case "zip":
                $type = "application/zip";
                break;
        case "xls":
                $type = "application/vnd.ms-excel";
                break;
        case "gif":
                $type = "image/gif";
                break;
        case "jpg" || "jpeg":
                $type = "image/jpg";
                break;
        case "png":
                $type = "image/png";
                break;          
        case "wav":
                $type = "audio/wav";
                break;
        case "mp3":
                $type = "audio/mpeg3";
                break;
        case "mpg" || "mpeg":
                $type = "video/mpeg";
                break;
        case "rtf":
                $type = "application/rtf";
                break;
        case "htm" || "html":
                $type = "text/html";
                break;
        case "xml":
                $type = "text/xml";
                break;  
        case "xsl":
                $type = "text/xsl";
                break;
        case "css":
                $type = "text/css";
                break;
        case "php":
                $type = "text/php";
                break;
        case "txt":
                $type = "text/txt";
                break;
        case "asp":
                $type = "text/asp";
                break;
        case "pdf":
                $type = "application/pdf";
                break;
        case "psd":
                $type = "application/octet-stream";
                break;
        default:
                $type = "application/octet-stream";
        }
        
        //download file
        header('Content-Description: File Transfer');
        header('Content-Type: ' .$type);
        header('Content-Disposition: attachment; filename='.$fileName);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($fileSource));
        ob_clean();
        flush();
        echo $fileSource;
	}
}


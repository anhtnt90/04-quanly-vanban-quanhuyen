<?php 

/**
 * Nguoi tao: Sys 
 * Ngay tao: 10/01/2009
 * Noi dung: Tao lop Sys_Init_Config dung de config cau hinh lien quan den ho thong
 */

class Sys_Init_Config{	
	
	/**
	 * Khoi tao bien xac dinh duong dan website
	 *	
	 */	
	public function _setWebSitePath(){		
		return "/user_v1/";
	}
	/**
	 * Khoi tao bien xac dinh duong dan website
	 *	
	 */	
	public function _getOwnerCode(){		
		return "STTTT";
	}
	/**
	 * Khoi tao bien xac dinh duong dan website
	 *	
	 */	
	public function _getUnit(){		
		return "2";
	}
	/**
	 * Lay ra gia tri quyen quan tri he thong
	 *	
	 */	
	public function _setPermisstionSystem($iOption = 0){
		switch($iOption){
		 	 case 1;	//Quyen quan tri toan he thong
				return "ADMIN_SYSTEM";
				break;	
			 case 2;	//Quyen quan tri cap mot don vi trien khai
				return "ADMIN_OWNER";
				break;
			default: 
				return "";
				break;
		}			
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
	 * @see : thuc hien lay don vi bao cao
	 * 
	 * 	*/
	public function _setOnerName(){
		return  'SỞ THÔNG TIN TRUYỀN THÔNG TỈNH PHÚ THỌ';
	}
		/**
	 * Creater: Sys
	 * Date : 03/08/2010
	 * Idea : Tao phuong thuc dat gia tri ID cua don vi cap cha, o day la ID cua don vi Quan/Huyen
	 *
	 * @return ID cua don vi cha
	 */
	public function _setParentOwnerId(){		
		return 262;
	}

	/**
	 * HAIDV
	 * Thuc hien lay ten don vi, dua vao cac tai lieu in an, bao cao	
	 * 	*/
	public function _setOnerReportName(){
		return  'Phú Thọ, ';
	}
	/*
	 * HAIDV
	 * Thuc hien gan thong tin dia chi don vi
	 */	
	public function _setInfoAddressUnit(){		
		$arrInforAddress = array();
		$arrInforAddress = array("_UNIT_NAME" =>"THANH TRA TỈNH PHÚ THỌ",
								 "_ADDRESS"   =>"Đường Nguyễn Tất Thành - P. Tân Dân - TP. Việt Trì - tỉnh Phú Thọ",
								 "_PHONE"	  =>"0210.3846.317",
								 "_FAX"		  =>"0210.3842.829",
								 "_EMAIL"	  =>"",
								 "_WEBSITE"   =>"",																
								);
		return $arrInforAddress;
	}	
	/**
	 * @see : Thuc hien lay dia chi dang nhap cua user & Duong dan mac dinh vao ung dung
	 * 	*/
	public function _setUserLoginUrl(){
		return self::_getCurrentHttpAndHost() . "login/index/";//"user/position/index/";	
	}
	
	/**
	 * @see : Lay duong dan toi cho dat webservice
	 * 
	 * 	*/
	public function  _setWebServiceUrl(){
		return "";
	}
	/**
	 * Thuc hien lay gan gia tri time out
	 * 	*/
	
	public  function _setTimeOut(){
		return 1900;
	}
	/***
	 * @see: Thuc hien gan ma ung dung
		*/
	public function _setAppCode(){
		return "Sys-USER-STTTT";//Ma ứng dụng, mã này sẽ thay đổi theo từng đơn vị triển khai theo chuẩn Sys-USER-XXX: Trong đó XXX là tên viết tắt của đơn vị triển khai
	}
	
	/**
	 * Thuc hien lay duong dan den file khai bao phan Header cho bao cao
	 *
	 * @return unknown
	 */
	public function _setUrlTempHeaderReport(){		
		return self::_getCurrentHttpAndHost() . "templates/report-template/";
	}

	/**
	 * Creater: Sys
	 * Date : 21/09/2009
	 * Idea : Tao phuong thuc lay dia chi xu ly AJAX (Vi du: http://Sys:8080/Sys-doc-boxd/application/....)
	 *
	 * @return Dia chi URL
	 */
	public function _setUrlAjax(){		
		return self::_getCurrentHttpAndHost() . "application/";
	}

	
	/**
	 * Idea: Tao phuong thuc khoi tao cac hang so dung chung
	 *
	 * @return Mang luu thong tin cac hang so dung chung
	 */
	public function _setProjectPublicConst(){
		$arrPublicConst = array();
		$arrPublicConst = array("_CONST_LIST_DELIMITOR"=>"!#~$|*",
								"_CONST_SUB_LIST_DELIMITOR"=>"!~~!",
								"_CONST_DECIMAL_DELIMITOR"=>",",
								"_CONST_IMAGE_URL_PATH"=>self::_setImageUrlPath(),
								//DInh nghia bien xac dinh cac ngay le nghi trong nam
								"_CONST_LIST_DAY_OFF_OF_YEAR"=>"+/30/04,+/01/05,+/02/09,+/01/01,-/30/12,-/01/01,-/02/12,-/10/03",
								//Dinh nghia bien quy dinh cac ngay lam viec trong tuan
								"_CONST_LIST_WORK_DAY_OF_WEEK"=>"2,3,4,5,6",								
								//Dinh nghia hang so cho phep tang len hay giam di so ngay hien giai quyet
								//Neu = 1 thi viec tinh so ra so ngay giai quyet bat dau tu ngay hien thoi
								// = 0 thi tang len 01 ngay; = 2 thi luoi ngay giai quyet di 01 ngay,...
								"_CONST_INCREASE_AND_DECREASE_DAY"=>"1",
								//Menu Top
								"_DANH_MUC"					=>"Danh mục",
								"_CO_CAU_TO_CHUC"			=>"Cơ Cấu tổ chức",
								"_THOAT"					=>"Thoát",
								//Menu Left
								"_CO_CAU_PHONG_BAN"			=>"Cơ cấu phòng ban",
								"_NHOM_CHUC_VU"				=>"Nhóm chức vụ",
								"_CHUC_VU"					=>"Chức vụ",
								//Danh sach Chức vụ
								"_MA_CHUC_VU"				=>"Mã chức vụ",
								"_TEN_CHUC_VU"				=>"Tên chức vụ",
								"_THU_TU"					=>"Thứ tự",
								"_TT_HOAT_DONG"				=>"Tình trạng",
								"_GHI_CHU"					=>"Ghi chú",
								//Danh sach Nhóm Chức vụ
								"_MA_NHOM_CHUC_VU"			=>"Mã nhóm chức vụ",
								"_TEN_NHOM_CHUC_VU"			=>"Tên nhóm chức vụ",
								"_THU_TU"					=>"Thứ tự",
								"_TT_HOAT_DONG"				=>"Tình trạng",
								//Danh mục
								"_DM_LOAI"					=>"Loại danh mục",
								"_DM_DOITUONG"				=>"Danh mục đối tượng",
								"_DM_QUYEN"					=>"Danh mục quyền",
								"_SAOLUU_PHUCHOI"			=>"Sao lưu – Phục hồi DL",
								//co cau phong ban
								"_DIA_CHI"					=>"Địa chỉ",
								"_DIEN_THOAI_NOI_BO"		=>"Điện thoại nội bộ",
								"_TEN_PHONG_BAN"			=>"Tên phòng ban",
								"_MA_PHONG_BAN"				=>"Mã phòng ban",
								"_DIEN_THOAI_CO_DINH"		=>"Điện thoại cố định",
								"_DIEN_THOAI_CO_QUAN"		=>"Điện thoại cơ quan",
								"_DIEN_THOAI_DI_DONG"		=>"Điện thoại di động",
								"_DIEN_THOAI_NHA_RIENG"		=>"Điện thoại nhà riêng",
								"_FAX"						=>"Số FAX",
								"_EMAIL"					=>"Địa chỉ Email",
								"_GHI_THEM_MOI"				=>"Ghi & Thêm mới",
								"_GHI_QUAY_LAI"				=>"Ghi & Quay lại",
								"_MA_CAN_BO"				=>"Mã cán bộ",
								"_TEN_CAN_BO"				=>"Tên cán bộ",
								"_NGAY_SINH"				=>"Ngày sinh",
								"_GIOI_TINH"				=>"Giới tính",
								"_CHUC_DANH"				=>"Chức danh",
								"_VAI_TRO"					=>"Vai trò",
								"_QT_HE_THONG"				=>"Quản trị hệ thống",
								"_QT_DON_VI_TRIEN_KHAI"		=>"Quản trị đơn vị triển khai",
								"_NGUOI_SU_DUNG"	 		=>"Người sử dụng",
								"_HOAT_DONG"	 			=>"Hoạt động",
								"_XAC_LAP_MAT_KHAU"	 		=>"Xác lập mật khẩu mới",
								"_RESET_MAT_KHAU"	 		=>"Reset lại mật khẩu",
								//Reset password
								"_MESSAGE_EMAIL"			=>"Xin chào <B>#FULLNAME#</B>! <br>Mật khẩu đăng nhập phần mềm của bạn đã được thay đổi thành <B>#NEWPASSWORD#</B>. <br>Đề nghị bạn thay đổi mật khẩu trong lần đăng nhập tiếp theo.<br>Mọi thắc mắc xin vui long liên hệ với Bộ phận quản trị mạng: <br><br>",
								"_EMAIL_PUBLIC"				=>"khoinv@Sys.com.vn",
								"_PASS_WORD_EMAIL"			=>"123456",
								"_ADMIN_USER"				=>"Bộ phận quản trị mạng",
								"_TITLE_EMAIL"				=>"Thông báo về việc thay đổi mật khẩu truy cập phần mềm",
								"_DEFAUL_PASS_WORD"			=>"123456",
								//Đăng nhập
								"_DANG_NHAP"				=>"Đăng nhập",								
								"_TEN_DANG_NHAP"			=>"Tên đăng nhập",
								"_MAT_KHAU"					=>"Mật khẩu",
								"_TIM_KIEM"					=>"Tìm kiếm",
								"_GHI"						=>"Ghi",
								"_THEM"						=>"Thêm",
								"_THEM_PHONG_BAN"			=>"Thêm phòng ban",
								"_SUA"						=>"Sửa",
								"_XOA"						=>"Xóa",
								"_QUAY_LAI"					=>"Quay lại",
								"_DON_VI_TRIEN_KHAI"		=>"Đơn vị triển khai",
								"_THEM_CAN_BO"				=>"Thêm cán bộ",
								"_PHONG_BAN"				=>"Thuộc phòng ban",
								"_IN_DANG_WEB"				=>"In dạng WEB",
								"_IN_DANG_EXCEL"			=>"In dạng Excel",
								"_TEN_CONG_TY"				=>"CÔNG TY CỔ PHẦN CÔNG NGHỆ TIN HỌC Sys VIỆT NAM<br>
															Tel (84-4) 287 2290 - Fax (84-4) 287 2290    Email: contact@Sys.com.vn   Website: http://www.Sys.com.vn",
								"_GET_HTTP_AND_HOST"=>self::_getCurrentHttpAndHost(),
								"_MODAL_DIALOG_MODE"=>"0");
		return 	$arrPublicConst;					
	}
	
}


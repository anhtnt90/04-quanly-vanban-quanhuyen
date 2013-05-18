<?php
/**
 * Class Xu ly thong thong tin loai danh muc
 */
class Listxml_smsController extends  Zend_Controller_Action {

	public $_ArchivesStaffPermission;
	public $_DistributionPermission;
	public $_AssignPermission;
	
	public function init(){
		Sys_Function_RecordFunctions::CheckLogin();
		//Load cau hinh thu muc trong file config.ini
        $tempDirApp = Zend_Registry::get('conDirApp');
		$this->_dirApp = $tempDirApp->toArray();
		$this->view->dirApp = $tempDirApp->toArray();		
		//Cau hinh cho Zend_layoutasdfsdfsd
		Zend_Layout::startMvc(array(
			    'layoutPath' => $this->_dirApp['layout'],
			    'layout' => 'index'			    
			    ));	
		//Load ca thanh phan cau vao trang layout (index.phtml)
		$response = $this->getResponse();		
		//Load cau hinh thu muc trong file config.ini de lay ca hang so dung chung
        $tempConstPublic = Zend_Registry::get('ConstPublic');
		$this->_ConstPublic = $tempConstPublic->toArray();			
		//Ky tu dac biet phan tach giua cac phan tu
		$this->view->delimitor 			= $this->_ConstPublic['delimitor'];		
		//Lay duong dan thu muc goc (path directory root)
		$this->view->baseUrl = $this->_request->getBaseUrl() . "/public/";	
		//Goi lop Listxml_modProject
		Zend_Loader::loadClass('Sms_modSms');		
		//Tao doi tuong XML
		Zend_Loader::loadClass('Sys_Publib_Xml');
		$objDocFun = new Sys_Function_DocFunctions();	
		//Lay cac hang so su dung trong JS public
		Zend_Loader::loadClass('Sys_Init_Config');
		Zend_Loader::loadClass('Sys_Init_Session');
		$objConfig = new Sys_Init_Config();
		$this->view->JSPublicConst = $objConfig->_setJavaScriptPublicVariable();		
		/* Dung de load file Js va css		/*/
		// Goi lop public		
		$objPublicLibrary = new Sys_Library();			
			// Load tat ca cac file Js va Css
		$this->view->LoadAllFileJsCss = Sys_Publib_Library::_getAllFileJavaScriptCss('','js','sent.js',',','js') .Sys_Publib_Library::_getAllFileJavaScriptCss('','js','jsUser.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','ajax.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','jquery-1.4.2.min.js,jQuery.equalHeights.js',',','js'). Sys_Publib_Library::_getAllFileJavaScriptCss('','js/LibSearch','actb_search.js,common_search.js',',','js');
		
		//-------------Lay ma giai doan thuc hien-------------------------	
		$sPeriodCode = $this->_request->getParam('period',"");
		$PeriodCode = "";//$objDocFun->DocGetPeriodParameter($sPeriodCode);			
		$this->view->periodCode = $PeriodCode['periodCode']; //Chuyen thong tin ma giai doan vao VIEW
		$this->view->periodStep = $PeriodCode['periodStep']; //Chuyen thong tin bien xac dinh giai doan  thuc hien vao VIEW
		//Dinh nghia current modul code
		$this->view->currentModulCode = "LIST";
		//Hien thi file template
		$response->insert('header', $this->view->renderLayout('header.phtml','./application/views/scripts/'));    //Hien thi header 
		$response->insert('left', $this->view->renderLayout('left.phtml','./application/views/scripts/'));    //Hien thi header 		    
        $response->insert('footer', $this->view->renderLayout('footer.phtml','./application/views/scripts/'));  	 //Hien thi footer     
	}	
	/**
	 * Idea : Phuong thuc hien thi danh sach
	 *
	 */
	public function indexAction(){
		$objSession = new Sys_Init_Session();
		$objFunction =	new	Sys_Function_DocFunctions()	;
		$objSms = new Sms_modSms();
		$ojbSysInitConfig = new Sys_Init_Config();
		//Lay cac gia tri const
		$this->view->arrConst =	$ojbSysInitConfig->_setProjectPublicConst();
		//Xu ly Autocomplete
		$this->view->search_textselectbox = $objFunction->doc_search_ajax($_SESSION['arr_all_staff'],"id","name","sFullTextSearch","hdn_name",1,"position_code");
		//Nhan bien truyen vao tu form
		$sFullTextSearch = trim($this->_request->getParam('sFullTextSearch',''));
		$this->view->sFullTextSearch = $sFullTextSearch;
		//Lay ID don vi
		$iOwnerId = $_SESSION['OWNER_ID'];
		
		$this->view->bodyTitle = 'DANH SÁCH CÁN BỘ NHẬN TIN SMS';
		
		// Lay toan bo tham so truyen tu form			
		$arrInput = $this->_request->getParams();		

		//Phan trang
		$piCurrentPage = $this->_request->getParam('hdn_current_page',0);		
		if ($piCurrentPage <= 1){
			$piCurrentPage = 1;
		}
		//echo $piCurrentPage;exit;
		$this->view->currentPage = $piCurrentPage; //Gan gia tri vao View		
		//Lay thong tin quy dinh so row / page
		$piNumRowOnPage = $this->_request->getParam('hdn_record_number_page');
		if($piNumRowOnPage == ''){
			$piNumRowOnPage = 15;
		}
		//THUC HIEN TRUY VAN LAY DU LIEU CAN NHAC VIEC
		$arrSmsUser = $objSms->docSmsUserGetAll($sFullTextSearch,$piCurrentPage,$piNumRowOnPage);
		$this->view->arrSmsUser = $arrSmsUser;
		//Mang luu thong tin tong so ban ghi tim thay
		$psCurrentPage = $arrSms[0]['C_TOTAL'];				
		//Hien thi thong tin man hinh danh sach nay co bao nhieu ban ghi va hien thi Radio "Chon tat ca"; "Bo chon tat ca"
		if (count($arrSms) > 0){
			$this->view->sdocpertotal = "Danh sách có ".sizeof($arrSms).'/'.$psCurrentPage." cán bộ";
			//Sinh xau HTML mo ta so trang (Trang 1; Trang 2;...)
			$this->view->generateStringNumberPage = Sys_Publib_Library::_generateStringNumberPage($psCurrentPage, $piCurrentPage, $piNumRowOnPage,$pUrl) ;		
			//quy dinh so record/page	
			$this->view->generateHtmlSelectBoxPage = Sys_Publib_Library::_generateChangeRecordNumberPage($piNumRowOnPage,"../index/?htn_leftModule=WAIT" );
		}
	}
	public function addAction(){
		$objSession = new Sys_Init_Session();
		$objFunction =	new	Sys_Function_DocFunctions()	;
		$objSms = new Sms_modSms();
		$ojbSysInitConfig = new Sys_Init_Config();
		$objFilter = new Zend_Filter();	
		//Lay cac gia tri const
		$this->view->arrConst =	$ojbSysInitConfig->_setProjectPublicConst();
		//AutoComplete Can bo nhan tin SMS
		$this->view->search_textselectbox = $objFunction->doc_search_ajax($_SESSION['arr_all_staff'],"id","name","C_POSITON_NAME","hdn_name",1,"position_code");
		//Phan trang
		$piCurrentPage = $this->_request->getParam('hdn_current_page',0);		
		if ($piCurrentPage <= 1){
			$piCurrentPage = 1;
		}
		//echo $piCurrentPage;exit;
		$this->view->currentPage = $piCurrentPage; //Gan gia tri vao View		
		//Lay thong tin quy dinh so row / page
		$piNumRowOnPage = $this->_request->getParam('hdn_record_number_page');
		if($piNumRowOnPage == ''){
			$piNumRowOnPage = 15;
		}	
		if($this->_request->getParam('C_POSITON_NAME','') != ""){	
			$sPositionName = $this->_request->getParam('C_POSITON_NAME','');
			$iFkStaff = $objFunction->convertStaffNameToStaffId($sPositionName);
			$iUnitId  = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$iFkStaff,'unit_id');
			$sUnitName= Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_unit'],$iUnitId,'name');
			$iAutoSms = $this->_request->getParam('C_AUTO_SMS','');
			$sTelMobile = $objFunction->convertIdListToTelMobileList($iFkStaff);
			$arrParameter = array(	
								'PK_DOC_SMS_USER'				=>'',	
								'FK_STAFF'						=>$iFkStaff,
								'C_UNIT_NAME'					=>$sUnitName,									
								'C_POSITON_NAME'				=>$sPositionName,
								'C_ORDER'						=>'',
								'C_AUTO_SMS'					=>$iAutoSms,
								'C_TEL_MOBILE'					=>$sTelMobile
							);						
									
			$arrResult = "";	
			$arrResult = $objSms->docSmsUserUpdate($arrParameter);	
		}			
		//Hien thi thong tin man hinh danh sach nay co bao nhieu ban ghi va hien thi Radio "Chon tat ca"; "Bo chon tat ca"
		if (count($arrSmsSend) > 0){
			$this->view->sdocpertotal = "Danh sách có ".sizeof($arrSmsSend).'/'.$psCurrentPage." tin nhắn";
			//Sinh xau HTML mo ta so trang (Trang 1; Trang 2;...)
			$this->view->generateStringNumberPage = Sys_Publib_Library::_generateStringNumberPage($psCurrentPage, $piCurrentPage, $piNumRowOnPage,$pUrl) ;		
			//quy dinh so record/page	
			$this->view->generateHtmlSelectBoxPage = Sys_Publib_Library::_generateChangeRecordNumberPage($piNumRowOnPage,"../send/?htn_leftModule=SENT" );
		}
		$this->view->bodyTitle = 'CẬP NHẬT CÁN BỘ GỬI TIN SMS';
		$psOption = $this->_request->getParam('hdh_option','');
		//Truong hop ghi va them moi
		if ($psOption == "GHI_THEMMOI"){
			//Ghi va quay lai chinh form voi noi dung rong						
			$this->_redirect('sms/user/add/');				
		}				

		//Truong hop ghi va quay lai
		if ($psOption == "GHI_QUAYLAI"){
			//Tro ve trang index						
			$this->_redirect('sms/user/index/' );
		}
			
	}
	public function editAction(){
		$objSession = new Sys_Init_Session();
		$objFunction =	new	Sys_Function_DocFunctions()	;
		$objSms = new Sms_modSms();
		$ojbSysInitConfig = new Sys_Init_Config();
		$objFilter = new Zend_Filter();	
		//Lay cac gia tri const
		$this->view->arrConst =	$ojbSysInitConfig->_setProjectPublicConst();
		//Lay ID can sua
		$sSmsUserID = $this->_request->getParam('hdn_object_id','');
		$this->view->sSmsUserID = $sSmsUserID;
		//AutoComplete Can bo nhan tin SMS
		$this->view->search_textselectbox = $objFunction->doc_search_ajax($_SESSION['arr_all_staff'],"id","name","C_POSITON_NAME","hdn_name",1,"position_code");
		//Phan trang
		$piCurrentPage = $this->_request->getParam('hdn_current_page',0);		
		if ($piCurrentPage <= 1){
			$piCurrentPage = 1;
		}
		//echo $piCurrentPage;exit;
		$this->view->currentPage = $piCurrentPage; //Gan gia tri vao View		
		//Lay thong tin quy dinh so row / page
		$piNumRowOnPage = $this->_request->getParam('hdn_record_number_page');
		if($piNumRowOnPage == ''){
			$piNumRowOnPage = 15;
		}	
		if($this->_request->getParam('hdn_sms_user','') == 1){	
			//$sPositionName = $this->_request->getParam('C_POSITON_NAME','');
			//$iFkStaff = $objFunction->convertStaffNameToStaffId($sPositionName);
			//$iUnitId  = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$iFkStaff,'unit_id');
			//$sUnitName= Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_unit'],$iUnitId,'name');
			$iOrder = $this->_request->getParam('C_ORDER','');
			$iAutoSms = $this->_request->getParam('C_AUTO_SMS','');
			$sTelMobile = $this->_request->getParam('C_TEL_MOBILE','');
			$arrParameter = array(	
								'PK_DOC_SMS_USER'				=>$sSmsUserID,	
								'FK_STAFF'						=>'',
								'C_UNIT_NAME'					=>'',									
								'C_POSITON_NAME'				=>'',
								'C_ORDER'						=>$iOrder,
								'C_AUTO_SMS'					=>$iAutoSms,
								'C_TEL_MOBILE'					=>$sTelMobile
							);						
									
			$arrResult = "";	
			$arrResult = $objSms->docSmsUserUpdate($arrParameter);	
		}			
		//Hien thi thong tin man hinh danh sach nay co bao nhieu ban ghi va hien thi Radio "Chon tat ca"; "Bo chon tat ca"
		if (count($arrSmsSend) > 0){
			$this->view->sdocpertotal = "Danh sách có ".sizeof($arrSmsSend).'/'.$psCurrentPage." tin nhắn";
			//Sinh xau HTML mo ta so trang (Trang 1; Trang 2;...)
			$this->view->generateStringNumberPage = Sys_Publib_Library::_generateStringNumberPage($psCurrentPage, $piCurrentPage, $piNumRowOnPage,$pUrl) ;		
			//quy dinh so record/page	
			$this->view->generateHtmlSelectBoxPage = Sys_Publib_Library::_generateChangeRecordNumberPage($piNumRowOnPage,"../send/?htn_leftModule=SENT" );
		}
		$this->view->bodyTitle = 'CẬP NHẬT CÁN BỘ GỬI TIN SMS';
		$psOption = $this->_request->getParam('hdh_option','');
		//Truong hop ghi va quay lai
		if ($psOption == "GHI_QUAYLAI"){
			//Tro ve trang index						
			$this->_redirect('sms/user/index/' );
		}
		$arrSmsUserSingle = $objSms->docSmsUserGetSingle($sSmsUserID);
		$this->view->arrSmsUserSingle = $arrSmsUserSingle;	
	}
	public function deleteAction(){	
		$objSms = new Sms_modSms();	
		//Lay Id doi tuong can xoa
		$sListId = $this->_request->getParam('hdn_object_id_list',"");	
		//Goi phuong thuc xoa doi tuong
		$objSms->docSmsUserDelete($sListId);
		$this->_redirect('sms/user/index/');	
	}
}	
?>
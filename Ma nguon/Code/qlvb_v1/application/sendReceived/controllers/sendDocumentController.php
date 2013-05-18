<?php
/**
 * Nguoi tao: phuongtt
 * Ngay tao: 08/09/2010
 * Y nghia: Class Xu ly GUI-NHAN VB dien tu
 */	
class sendReceived_sendDocumentController extends  Zend_Controller_Action {
	//Bien public luu quyen
	public $_publicPermission;
	public function init(){
		//Load cau hinh thu muc trong file config.ini
        $tempDirApp = Zend_Registry::get('conDirApp');
		$this->_dirApp = $tempDirApp->toArray();
		$this->view->dirApp = $tempDirApp->toArray();
		//Cau hinh cho Zend_layout
		Zend_Layout::startMvc(array(
			    'layoutPath' => $this->_dirApp['layout'],
			    'layout' => 'index'			    
			    ));	
		//Load ca thanh phan cau vao trang layout (index.phtml)
		$response = $this->getResponse();
		//Lay cac hang so su dung trong JS public
		Zend_Loader::loadClass('Sys_Init_Config');
		$objConfig = new Sys_Init_Config();
		$this->view->UrlAjax = $objConfig->_setUrlAjax();	
		
		//Load cau hinh thu muc trong file config.ini de lay ca hang so dung chung
        $tempConstPublic = Zend_Registry::get('ConstPublic');
		$this->_ConstPublic = $tempConstPublic->toArray();
		
		//Lay so dong tren man hinh danh sach
		$this->view->NumberRowOnPage 	= $this->_ConstPublic['NumberRowOnPage'];		
		
		//Ky tu dac biet phan tach giua cac phan tu
		$this->view->delimitor 			= $this->_ConstPublic['delimitor'];
		
		//Lay duong dan thu muc goc (path directory root)
		$this->view->baseUrl = $this->_request->getBaseUrl() . "/public/";	
				//Goi lop Listxml_modList
		Zend_Loader::loadClass('dashboard_modWebMenu');
		//Lay tat ca cac chuyen muc
		$objWebMenu = new dashboard_modWebMenu();
		$arrResul = $objWebMenu->WebMenuGetAll('4',$_SESSION['OWNER_CODE'],'3','1');
		$this->view->arrMenu = $arrResul;	
		$sliidvisit = $this->_request->getParam('sliid','');
		if ($sliidvisit == "" || is_null($sliidvisit) || !isset($sliidvisit)){
			$sliidvisit = Sys_Library::_getCookie("headervisit");
		}else{
			Sys_Library::_createCookie("headervisit",$sliidvisit);
		}
		$this->view->sliidvisit = $sliidvisit;	
		$sleftmenu = $this->_request->getParam('sleftmenu','');
		$this->view->sleftmenu = $sleftmenu;	
		//Goi lop modSendReceived
		Zend_Loader::loadClass('sendReceived_modSendReceived');
		
		
		$this->view->JSPublicConst = $objConfig->_setJavaScriptPublicVariable();		
		
		// Load tat ca cac file Js va Css
		$JSandStyle = Sys_Publib_Library::_getAllFileJavaScriptCss('','js','sendreceived.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','ajax.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','jquery-1.4.2.min.js,jQuery.equalHeights.js',',','js'). Sys_Publib_Library::_getAllFileJavaScriptCss('','js/LibSearch','actb_search.js,common_search.js',',','js');
		$JSandStyle.= Sys_Publib_Library::_getAllFileJavaScriptCss('','js','ui/i18n/jquery.ui.datepicker-vi.js',',','js');
		$JSandStyle.= Sys_Publib_Library::_getAllFileJavaScriptCss('','js','ui/jquery-ui-1.8.14.custom.min.js',',','js');
		$JSandStyle.= Sys_Publib_Library::_getAllFileJavaScriptCss('','style','themes/redmond/jquery-ui-1.8.15.custom.css',',','css');
		$this->view->LoadAllFileJsCss = $JSandStyle;
		//Lay tra tri trong Cookie
		$sGetValueInCookie = Sys_Library::_getCookie("showHideMenu");
		
		//Neu chua ton tai thi khoi tao
		if ($sGetValueInCookie == "" || is_null($sGetValueInCookie) || !isset($sGetValueInCookie)){
			Sys_Library::_createCookie("showHideMenu",1);
			Sys_Library::_createCookie("ImageUrlPath",$this->_request->getBaseUrl() . "/public/images/close_left_menu.gif");
			//Mac dinh hien thi menu trai
			$this->view->hideDisplayMeneLeft = 1;// = 1 : hien thi menu
			//Hien thi anh dong menu trai
			$this->view->ShowHideimageUrlPath = $this->_request->getBaseUrl() . "/public/images/close_left_menu.gif";
		}else{//Da ton tai Cookie
			/*
			Lay gia tri trong Cookie, neu gia tri trong Cookie = 1 thi hien thi menu, truong hop = 0 thi an menu di
			*/
			if ($sGetValueInCookie != 0){
				$this->view->hideDisplayMeneLeft = 1;// = 1 : hien thi menu
			}else{
				$this->view->hideDisplayMeneLeft = "";// = "" : an menu
			}
			//Lay dia chi anh trong Cookie
			$this->view->ShowHideimageUrlPath = Sys_Library::_getCookie("ImageUrlPath");
		}

		
		// Ham lay thong tin nguoi dang nhap hien thi tai Lefmenu
		$this->view->InforStaff = Sys_Publib_Library::_InforStaff();
		
		//Dinh nghia current modul code
		$this->view->currentModulCode = "SENDRECEIVED";
		//Lay Quyen cap nhat VB dien tu
		//$this->_publicPermission = Sys_Function_DocFunctions::Doc_ArchivesStaff($_SESSION['arrStaffPermission'],$_SESSION['staff_id']);
		//echo $this->_publicPermission;
		$psshowModalDialog = $this->_request->getParam('showModalDialog',"");
		$pcurrentModulCodeForLeft = $this->_request->getParam('htn_leftModule',"");
		if($pcurrentModulCodeForLeft != '')
				$this->view->currentModulCodeForLeft = $pcurrentModulCodeForLeft;
		else 	$this->view->currentModulCodeForLeft = 'SENT-DOCUMENT';
		$this->view->showModelDialog = $psshowModalDialog;
		if ($psshowModalDialog != 1){
			//Hien thi file template
			$response->insert('header', $this->view->renderLayout('twd_header.phtml','template/'));    
			$response->insert('left', $this->view->renderLayout('twd_left.phtml','template/'));  	    
	        $response->insert('footer', $this->view->renderLayout('twd_footer.phtml','template/'));
		}
		//echo $psshowModalDialog; exit;
  	}	
	/**
	 * Idea : Phuong thuc hien thi danh sach
	 *
	 */
	public function indexAction(){
		// Tao doi tuong Zend_Filter
		$objFilter = new Zend_Filter();
		$ojbSysLib = new Sys_Library();
		//Lay ID cua NSD dang nhap hien thoi
		$StaffId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
		//Lay ID phong ban cua NSD dang nhap hien thoi
		$pUrl = $_SERVER['REQUEST_URI'];
		// Tieu de tim kiem
		$this->view->bodyTitleSearch = "DANH SÁCH VĂN BẢN ĐI";				
		// Tieu de man hinh danh sach
		$this->view->bodyTitle = "DANH SÁCH VĂN BẢN ĐI";
		//Bat dau lay vet tim kiem tu session
		//Bat dau lay vet tim kiem tu session
		$sfromDate = $this->_request->getParam('txtfromDate','');
		$stoDate = $this->_request->getParam('txttoDate','');
		$sfullTextSearch = $this->_request->getParam('txtfullTextSearch','');
		$iCurrentPage = $this->_request->getParam('hdn_current_page',0);
		$iNumRowOnPage = $this->_request->getParam('hdn_record_number_page',0);
		/*
		if($sfromDate == '')
			$sfromDate = '1/1/'.date("Y");
		if($stoDate == '')
			$stoDate = date("d/m/Y");
		*/
		if($iCurrentPage < 1)
			$iCurrentPage = 1;
		if($iNumRowOnPage == 0)
			$iNumRowOnPage = 15;
		//Neu ton tai gia tri tim kiem tron session thi lay trong session
		if(isset($_SESSION['seArrParameter'])){
			$Parameter 			= $_SESSION['seArrParameter'];
			$sfullTextSearch	= $Parameter['chuoiTimKiem'];
			$sfromDate			= $Parameter['tuNgay'];
			$stoDate			= $Parameter['denNgay'];
			$iCurrentPage		= $Parameter['trangHienThoi'];
			$iNumRowOnPage		= $Parameter['soBanGhiTrenTrang'];
			unset($_SESSION['seArrParameter']);
		}
		//Day cac gia tri tim kiem ra view
		$sfullTextSearch = $ojbSysLib->_replaceBadChar($sfullTextSearch);
		$this->view->sFullTextSearch 	= $sfullTextSearch;
		$this->view->fromDate 			= $sfromDate;
		$this->view->toDate				= $stoDate;
		$this->view->iCurrentPage 		= $iCurrentPage;
		$this->view->iNumRowOnPage 		= $iNumRowOnPage;
		
		//Lay cac hang so dung chung
		$arrCount = Sys_Init_Config::_setProjectPublicConst();
		$this->view->arrCount = $arrCount;
		// Lay toan bo tham so truyen tu form			
		$arrInput = $this->_request->getParams();
		$objSendReceive = new sendReceived_modSendReceived();
		//Lay MA DON VI NSD dang nhap hien thoi
		$sOwnerCode = $_SESSION['OWNER_CODE'];
		$arrResul = $objSendReceive->DocSendReceivedGetAll($StaffId ,$_SESSION['OWNER_ID'],$sfullTextSearch,$iCurrentPage,$iNumRowOnPage,Sys_Library::_ddmmyyyyToYYyymmdd($sfromDate),Sys_Library::_ddmmyyyyToYYyymmdd($stoDate));			
		$iNumberRecord = $arrResul[0]['C_TOTAL_RECORD'];	
		$sdocpertotal ="Danh sách này không có văn bản nào";
		//Hien thi thong tin man hinh danh sach nay co bao nhieu ban ghi va hien thi Radio "Chon tat ca"; "Bo chon tat ca"
		$this->view->SelectDeselectAll = Sys_Publib_Library::_selectDeselectAll(sizeof($arrResul), $iNumberRecord);
		if (count($arrResul) > 0){
			$this->view->sdocpertotal = "Danh sách có: ".sizeof($arrResul).'/'.$iNumberRecord." văn bản";
			//Sinh xau HTML mo ta so trang (Trang 1; Trang 2;...)
			$this->view->generateStringNumberPage = Sys_Publib_Library::_generateStringNumberPage($iNumberRecord, $iCurrentPage, $iNumRowOnPage,$pUrl) ;
			//Sinh chuoi HTML mo ta tong so trang (Trang 1; Trang 2;...) va quy dinh so record/page
			$this->view->generateHtmlSelectBoxPage = Sys_Publib_Library::_generateChangeRecordNumberPage($iNumRowOnPage,"index");
		}
		$this->view->arrResul = $arrResul;
		$this->view->NumberRowOnPage 	= $this->_ConstPublic['NumberRowOnPage'];	
		$this->view->iCountElement = count($this->view->arrAllRecord);
	}
	/**
	 * Idea : Phuong thuc them moi mot VB
	 *
	 */
	public function addAction(){
		$this->view->bodyTitle = 'TẠO VĂN BẢN ĐI';
		$arrInput = $this->_request->getParams();
		$objDocFun = new Sys_Function_DocFunctions();
		$objSendReceive = new sendReceived_modSendReceived();
		$ojbXmlLib = new Sys_Publib_Xml();
		$ojbSysLib = new Sys_Library();
		$objFilter = new Zend_Filter();
		$ojbSysInitConfig = new Sys_Init_Config();	
		//$this->view->currentModulCodeForLeft = "SEND-DOCUMENT";
		//Lay ID cua NSD dang nhap hien thoi
		$StaffId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
		//Lay ID phong ban cua NSD dang nhap hien thoi
		$piUnitId = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');	
		//Lay TEN cua NSD dang nhap hien thoi
		$sStaffName = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'name');
		//Lay CHUC VU phong ban cua NSD dang nhap hien thoi
		$sStaffPosition = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'position_code');			
		if(!isset($_SESSION['seArrParameter'])){
			//Lay gia tri tim kiem tren form
			$sfullTextSearch 	= $this->_request->getParam('txtfullTextSearch','');
			$sfromDate 			= $this->_request->getParam('txtfromDate','');
			$stoDate 			= $this->_request->getParam('txttoDate','');
			$iCurrentPage		= $this->_request->getParam('hdn_current_page',0);
			$iNumRowOnPage 		= $this->_request->getParam('hdn_record_number_page',0);
			if ($iCurrentPage <= 1){
				$iCurrentPage = 1;
			}
			$iNumRowOnPage = $this->_request->getParam('cbo_nuber_record_page',0);
			if ($iNumRowOnPage == 0)
				$iNumRowOnPage = 15;
			$arrParaSet = array("trangHienThoi"=>$iCurrentPage, "soBanGhiTrenTrang"=>$iNumRowOnPage,"chuoiTimKiem"=>$sfullTextSearch,"tuNgay"=>$sfromDate,"denNgay"=>$stoDate);
			$_SESSION['seArrParameter'] = $arrParaSet;	
		}
		 //Lay thong tin history back
		$this->view->historyBack = '../index/';	
		//Lay tham so cau hinh
		$sysLibUrlPath = $ojbSysInitConfig->_setLibUrlPath();
		$url_path_calendar = $sysLibUrlPath . 'sys-calendar/';
		$this->view->urlCalendar = $url_path_calendar;
		//Lay thong tin tu danh muc
		$arrUrgent = $objSendReceive->getPropertiesDocument('DM_DO_KHAN_VAN_BAN');
		$this->view->arrUrgent = $arrUrgent;
		
		$arrLoaiVB = $objSendReceive->getPropertiesDocument('DM_LOAI_VAN_BAN');
		$this->view->arrLoaiVB = $arrLoaiVB;
		$arrSigner = $objSendReceive->getPropertiesDocument('DM_NGUOI_KY');
		$arrSigner = $objDocFun->docGetSignByUnit($arrSigner);
		$this->view->arrSigner = $arrSigner;
		//Lay cac hang so dung chung
		$arrCount = Sys_Init_Config::_setProjectPublicConst();
		$this->view->arrCount = $arrCount;
		//Goi ham thuc hien lay thong tin cho selectbox
		// Goi ham search lay ra loai van ban
		$this->view->search_textselectbox_doc_type = Sys_Function_DocFunctions::doc_search_ajax($arrLoaiVB,"C_CODE","C_NAME","C_DOC_TYPE","hdn_doc_type",1,'',1);
		// Goi ham search lay ra nguoi ky
		$this->view->search_textselectbox_signer = Sys_Function_DocFunctions::doc_search_ajax($arrSigner,"C_CODE","C_NAME","C_SIGNER_POSITION_NAME","hdn_signer_position_name",1,'',1);
		// Goi ham search lay ra toan bo thong tin can bo nhan
		$this->view->search_textselectbox_staff = Sys_Function_DocFunctions::doc_search_ajax($_SESSION['arr_all_staff_keep'],"id","name","C_STAFF_ID_LIST","hdn_staff_id_list",0,'position_code');
		// Goi ham search lay ra toan bo thong don vi, phong ban nhan
		$this->view->search_textselectbox_unit = Sys_Function_DocFunctions::doc_search_ajax($_SESSION['arr_all_unit_keep'],"id","name","C_UNIT_ID_LIST","hdn_unit_id_list",0);

		//Lay thong tin file dinh kem
		$arrFileNameUpload = $ojbSysLib->_uploadFileList(10,$this->_request->getBaseUrl() . "/public/attach-file/",'FileName','!#~$|*');
		$arFileAttach = $objSendReceive->DOC_GetAllDocumentFileAttach($arrReceived[0]['FK_DOC'],'','T_DOC_SEND_RECEIVE_DOCUMENT');	
		$this->view->AttachFile = $objDocFun->DocSentAttachFile($arFileAttach,sizeof($arFileAttach),10,true,30);	
		
		//Tuy chon ung voi cac truong hop update du lieu	
		$sOption = $this->_request->getParam('hdh_option','');
		$this->view->option = $sOption;
		//Mang luu du lieu update
		//Mang luu tham so update in database	
		//Kiem tra truong hop neu la giay moi thi ko cap nhat thong tin giay moi
		$sdocType = $this->_request->getParam('C_DOC_TYPE','');
		$shours = $this->_request->getParam('C_HOURS','');
		$saddress = $this->_request->getParam('C_ADDRESS','');
		$sdate = Sys_Library::_ddmmyyyyToYYyymmdd($this->_request->getParam('C_DATE',''));
		if($sdocType !="Giấy mời"){
			$shours ="";
			$saddress="";
			$sdate = "";
		}
		$sStaffList = $objDocFun->convertStaffNameToStaffId($this->_request->getParam('C_STAFF_ID_LIST',''));
		$sUnitList = $objDocFun->convertUnitNameListToUnitIdList($this->_request->getParam('C_UNIT_ID_LIST',''));
		if($sStaffList != '' or $sUnitList != '')
				$sSendReceivedStatus = 'DA_GUI';
		else 	$sSendReceivedStatus = 'CHUA_GUI';
		$arrParameter = array(	
								'PK_SEND_RECEIVE'				=>'',	
								'FK_UNIT'						=>$_SESSION['OWNER_ID'],			
								'FK_CREATER'					=>$StaffId,
								'FK_CREATER_POSITION_NAME'		=>$sStaffPosition . ' - ' . $sStaffName,
								'C_DOC_TYPE'					=>$sdocType,
								'C_SYMBOL'						=>$this->_request->getParam('C_SYMBOL',''),
								'C_DATE'						=>$sdate,
								'C_RELEASE_DATE'				=>Sys_Library::_ddmmyyyyToYYyymmdd($this->_request->getParam('C_RELEASE_DATE','')),
								'C_HOURS'						=>$shours,
								'C_ADDRESS'						=>$saddress,																	
								'C_SUBJECT'						=>$this->_request->getParam('C_SUBJECT',''),
								'C_TEXT_OF_EMERGENCY'			=>Sys_Library::_getNameByCode($arrUrgent,$this->_request->getParam('C_TEXT_OF_EMERGENCY',''),'C_NAME','C_CODE'),
								'C_SIGNER_POSITION_NAME'		=>$this->_request->getParam('C_SIGNER_POSITION_NAME',''),
								'C_NUMBER_SHEET'				=>$this->_request->getParam('C_NUMBER_SHEET',''),
								'C_NUMBER_PAGE'					=>$this->_request->getParam('C_NUMBER_PAGE',''),
								'C_OTHER'						=>$this->_request->getParam('C_OTHER',''),	
								'C_STAFF_ID_LIST'				=>$sStaffList,
								'C_STAFF_NAME_LIST'				=>str_replace(';','!#~$|*',$this->_request->getParam('C_STAFF_ID_LIST','')),
								'C_UNIT_ID_LIST'				=>$sUnitList,
								'C_UNIT_NAME_LIST'				=>str_replace(';','!#~$|*',$this->_request->getParam('C_UNIT_ID_LIST','')),
								'NEW_FILE_ID_LIST'				=>$arrFileNameUpload,
								'FK_DOC_LIST'					=>'',
								'FK_DOC'						=>'',
								'C_TYPE'						=>'',
								'C_XML_DATA'					=>'',
								'C_SEND_RECEIVED_STATUS'		=>$sSendReceivedStatus,
							);	
		//Bien luu gia tri tra ve cua ham update ID cua van ban duoc THEM MOI hoac CHINH SUA
		$arrResult = "";
		if ($this->_request->getParam('C_SUBJECT','') != ""){
			$arrResult = $objSendReceive->DocSendReceivedUpdate($arrParameter);
		
			//Truong hop ghi va them moi
			if ($sOption == "GHI_THEMMOI"){
				//Ghi va quay lai chinh form voi noi dung rong						
				$this->_redirect('sendReceived/sendDocument/add/?htn_leftModule=SEND-DOCUMENT');
			}	
			//Truong hop ghi va them tiep
			if ($sOption == "GHI_THEMTIEP"){
				$this->currentModulCodeForLeft = 'SEND-DOCUMENT';
				$this->view->sSendReceiveDocumentId = $arrResult['NEW_ID'];
				$this->view->option = $sOption;
				//Them van ban moi va giu lai noi dung thong tin tren form					
				$this->_redirect('sendReceived/sendDocument/edit/hdn_object_id/' . $arrResult['NEW_ID'].'?htn_leftModule=SEND-DOCUMENT');
			}
			//Truong hop ghi nhan
			if ($sOption == "GHI_TAM"){
				$this->currentModulCodeForLeft = 'SEND-DOCUMENT';
				$this->view->sSendReceiveDocumentId = $arrResult['NEW_ID'];
				$this->view->option = $sOption;
				//Ghi va quay lai chinh form voi noi dung rong						
				$this->_redirect('sendReceived/sendDocument/edit/hdn_object_id/' . $arrResult['NEW_ID'].'?htn_leftModule=SEND-DOCUMENT');
			}
			//Truong hop ghi va quay lai
			if ($sOption == "GHI_QUAYLAI"){
				//Tro ve trang index						
				$this->_redirect('sendReceived/sendDocument/index/?htn_leftModule=SENT-DOCUMENT');	
			}	
		}					
	}
	public function editAction(){
		$this->view->bodyTitle = 'TẠO VĂN BẢN ĐI';
		$objDocFun = new Sys_Function_DocFunctions();
		$objSendReceive = new sendReceived_modSendReceived();
		$ojbXmlLib = new Sys_Xml();
		$ojbSysLib = new Sys_Library();
		$objFilter = new Zend_Filter();
		$ojbSysInitConfig = new Sys_Init_Config();	
		//Lay ID cua NSD dang nhap hien thoi
		$StaffId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
		//Lay ID phong ban cua NSD dang nhap hien thoi
		$piUnitId = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');	
		//Lay TEN cua NSD dang nhap hien thoi
		$sStaffName = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'name');
		//Lay CHUC VU phong ban cua NSD dang nhap hien thoi
		$sStaffPosition = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'position_code');		
		if(!isset($_SESSION['seArrParameter'])){
			//Lay gia tri tim kiem tren form
			$sfullTextSearch 	= $this->_request->getParam('txtfullTextSearch','');
			$sfromDate 			= $this->_request->getParam('txtfromDate','');
			$stoDate 			= $this->_request->getParam('txttoDate','');
			$iCurrentPage		= $this->_request->getParam('hdn_current_page',0);
			$iNumRowOnPage 		= $this->_request->getParam('hdn_record_number_page',0);
			if ($iCurrentPage <= 1){
				$iCurrentPage = 1;
			}
			$iNumRowOnPage = $this->_request->getParam('cbo_nuber_record_page',0);
			if ($iNumRowOnPage == 0)
				$iNumRowOnPage = 15;
			$arrParaSet = array("trangHienThoi"=>$iCurrentPage, "soBanGhiTrenTrang"=>$iNumRowOnPage,"chuoiTimKiem"=>$sfullTextSearch,"tuNgay"=>$sfromDate,"denNgay"=>$stoDate);
			$_SESSION['seArrParameter'] = $arrParaSet;	
		}
		//Lay cac hang so dung chung
		$arrCount = Sys_Init_Config::_setProjectPublicConst();
		$this->view->arrCount = $arrCount;
		//Lay thong tin history back
		$this->view->historyBack = '../index/';	
		//Lay tham so cau hinh
		$sysLibUrlPath = $ojbSysInitConfig->_setLibUrlPath();
		$url_path_calendar = $sysLibUrlPath . 'sys-calendar/';
		$this->view->urlCalendar = $url_path_calendar;
		//lay danh sach do khan van ban trong danh muc
		$arrUrgent = $objSendReceive->getPropertiesDocument('DM_DO_KHAN_VAN_BAN');
		$this->view->arrUrgent = $arrUrgent;
		//lay danh sach loai van ban trong danh muc
		$arrLoaiVB = $objSendReceive->getPropertiesDocument('DM_LOAI_VAN_BAN');
		$this->view->arrLoaiVB = $arrLoaiVB;
		//lay danh sach nguoi ky trong danh muc
		$arrSigner = $objSendReceive->getPropertiesDocument('DM_NGUOI_KY');
		$this->view->arrSigner = $arrSigner;
		// Goi ham search lay ra loai van ban
		$this->view->search_textselectbox_doc_type = Sys_Function_DocFunctions::doc_search_ajax($arrLoaiVB,"C_CODE","C_NAME","C_DOC_TYPE","hdn_doc_type",1,'',1);
		// Goi ham search lay ra nguoi ky
		$this->view->search_textselectbox_signer = Sys_Function_DocFunctions::doc_search_ajax($arrSigner,"C_CODE","C_NAME","C_SIGNER_POSITION_NAME","hdn_signer_position_name",1,'',1);
		// Goi ham search lay ra toan bo thong tin can bo nhan
		$this->view->search_textselectbox_staff = Sys_Function_DocFunctions::doc_search_ajax($_SESSION['arr_all_staff_keep'],"id","name","C_STAFF_ID_LIST","hdn_staff_id_list",0,'position_code');
		// Goi ham search lay ra toan bo thong don vi, phong ban nhan
		$this->view->search_textselectbox_unit = Sys_Function_DocFunctions::doc_search_ajax($_SESSION['arr_all_unit_keep'],"id","name","C_UNIT_ID_LIST","hdn_unit_id_list",0);
		//Lay id van ban tu view
		$pSendReceiveDocumentId = $this->_request->getParam('hdn_object_id','');
		$this->view->pSendReceiveDocumentId = $pSendReceiveDocumentId;
		//Tuy chon ung voi cac truong hop update du lieu	
		$sOption = $this->_request->getParam('hdh_option','');
		//echo "<script>alert('".$sOption."')</script>";
		$this->view->option = $sOption;
		
		if ($sOption == "QUAY_LAI"){
			$this->_redirect('sendReceived/sendDocument/index/?htn_leftModule=SENT-DOCUMENT');
		}
		//Lay thong tin file dinh kem
		$arrFileNameUpload = $ojbSysLib->_uploadFileList(10,$this->_request->getBaseUrl() . "/public/attach-file/",'FileName','!#~$|*');
		$pSendReceiveDocumentIdTemp = $pSendReceiveDocumentId;
		if($sOption == "GHI_THEMTIEP"){
			$pSendReceiveDocumentIdTemp = "";
		}	
		//Lay file da dinh kem tu truoc
		if($sOption != "GHI_TAM"){
			$arFileAttach = $objSendReceive->DOC_GetAllDocumentFileAttach($pSendReceiveDocumentIdTemp,'','T_DOC_SEND_RECEIVE_DOCUMENT');	
			$this->view->AttachFile = $objDocFun->DocSentAttachFile($arFileAttach,sizeof($arFileAttach),10,true,45);	
		}
		//Mang luu thong tin chi tiet cua mot van ban
		$arrSendReceived = $objSendReceive->DocSendReceivedGetSingle($pSendReceiveDocumentId,'','');
		$this->view->arrSendReceived = $arrSendReceived;
		if($pSendReceiveDocumentId != '' && $pSendReceiveDocumentId != null  && $sOption != "QUAY_LAI"){
				//Neu la ghi va them tiep thi gan ID VB lay duoc = "" de them moi mot VB
				if ($sOption == "GHI_THEMTIEP"){
					$pSendReceiveDocumentId = "";
				}
				//Mang luu tham so update in database	
				//Kiem tra truong hop neu la giay moi thi ko cap nhat thong tin giay moi
				$sdocType = $this->_request->getParam('C_DOC_TYPE','');
				$shours = $this->_request->getParam('C_HOURS','');
				$saddress = $this->_request->getParam('C_ADDRESS','');
				$sdate = Sys_Library::_ddmmyyyyToYYyymmdd($this->_request->getParam('C_DATE',''));
				if($sdocType !="Giấy mời"){
					$shours = '';
					$saddress = '';
					$sdate = '';
				}
				echo $this->_request->getParam('C_STAFF_ID_LIST','');
				$sStaffList = $objDocFun->convertStaffNameToStaffId($this->_request->getParam('C_STAFF_ID_LIST',''));
				$sUnitList = $objDocFun->convertUnitNameListToUnitIdList($this->_request->getParam('C_UNIT_ID_LIST',''));
				if($sStaffList != '' or $sUnitList != '')
						$sSendReceivedStatus = 'DA_GUI';
				else 	$sSendReceivedStatus = 'CHUA_GUI';
				$arrParameter = array(										
										'PK_SEND_RECEIVE'				=>$pSendReceiveDocumentId,	
										'FK_UNIT'						=>$_SESSION['OWNER_ID'],			
										'FK_CREATER'					=>$StaffId,
										'FK_CREATER_POSITION_NAME'		=>$sStaffPosition . ' - ' . $sStaffName,
										'C_DOC_TYPE'					=>$sdocType,
										'C_SYMBOL'						=>$this->_request->getParam('C_SYMBOL',''),
										'C_DATE'						=>$sdate,
										'C_RELEASE_DATE'				=>Sys_Library::_ddmmyyyyToYYyymmdd($this->_request->getParam('C_RELEASE_DATE','')),
										'C_HOURS'						=>$shours,
										'C_ADDRESS'						=>$saddress,																	
										'C_SUBJECT'						=>$this->_request->getParam('C_SUBJECT',''),
										'C_TEXT_OF_EMERGENCY'			=>Sys_Library::_getNameByCode($arrUrgent,$this->_request->getParam('C_TEXT_OF_EMERGENCY',''),'C_NAME','C_CODE'),
										'C_SIGNER_POSITION_NAME'		=>$this->_request->getParam('C_SIGNER_POSITION_NAME',''),
										'C_NUMBER_SHEET'				=>$this->_request->getParam('C_NUMBER_SHEET',''),
										'C_NUMBER_PAGE'					=>$this->_request->getParam('C_NUMBER_PAGE',''),
										'C_OTHER'						=>$this->_request->getParam('C_OTHER',''),	
										'C_STAFF_ID_LIST'				=>$sStaffList,
										'C_STAFF_NAME_LIST'				=>str_replace(';','!#~$|*',$this->_request->getParam('C_STAFF_ID_LIST','')),
										'C_UNIT_ID_LIST'				=>$sUnitList,
										'C_UNIT_NAME_LIST'				=>str_replace(';','!#~$|*',$this->_request->getParam('C_UNIT_ID_LIST','')),
										'NEW_FILE_ID_LIST'				=>$arrFileNameUpload,
										'FK_DOC_LIST'					=>'',
										'FK_DOC'						=>'',
										'C_TYPE'						=>'',
										'C_XML_DATA'					=>'',
										'C_SEND_RECEIVED_STATUS'		=>$sSendReceivedStatus,
									);
				$arrResult = "";
		}
		if ($this->_request->getParam('C_SUBJECT','') != ""){
			$arrResult = $objSendReceive->DocSendReceivedUpdate($arrParameter);
				//Luu gia tri												
				//$arrParaSet = array("sel_page"=>$piCurrentPage, "cbo_nuber_record_page"=>$piNumRowOnPage);
				//var_dump($arrParaSet); exit;
				//$_SESSION['seArrParameter'] = $arrParaSet;
				//$this->_request->setParams($arrParaSet);
			
				//Truong hop ghi va them moi
				if ($sOption == "GHI_THEMMOI"){
					//Ghi va quay lai chinh form voi noi dung rong		
					$this->_redirect('sendReceived/sendDocument/add/?htn_leftModule=SEND-DOCUMENT');
				}	
				
				//Truong hop ghi va them tiep
				if ($sOption == "GHI_THEMTIEP"){
					$this->view->currentModulCodeForLeft = 'SEND-DOCUMENT';
					$this->view->option = $sOption;
					//Lay ID VB vua moi insert vao DB
					$this->view->pSendReceiveDocumentId = $arrResult['NEW_ID'];
					//Lay thong tin van ban vua them moi va hien thi ra view
					$arrSendReceived = $objSendReceive->DocSendReceivedGetSingle($arrResult['NEW_ID'],'','');
					$this->view->arrSendReceived = $arrSendReceived;
				}

				//Truong hop ghi tam
				if ($sOption == "GHI_TAM"){
					$this->view->currentModulCodeForLeft = 'SEND-DOCUMENT';
					//Lay ID VB vua moi insert vao DB
					$this->view->pReceiveDocumentId = $arrResult['NEW_ID'];
					$this->view->option = $sOption;
					//Lay thong tin van ban vua them moi va hien thi ra view
					$arrSendReceived = $objSendReceive->DocSendReceivedGetSingle($arrResult['NEW_ID'],'','');
					$this->view->arrSendReceived = $arrSendReceived;
					//Lay file da dinh kem tu truoc
					$arFileAttach = $objSendReceive->DOC_GetAllDocumentFileAttach($arrResult['NEW_ID'],'','T_DOC_SEND_RECEIVE_DOCUMENT');	
					$this->view->AttachFile = $objDocFun->DocSentAttachFile($arFileAttach,sizeof($arFileAttach),10,true,45);
				}
	
				//Truong hop ghi va quay lai
				if ($sOption == "GHI_QUAYLAI"){				
					$this->_redirect('sendReceived/sendDocument/index/?htn_leftModule=SENT-DOCUMENT');	
				}	
		}
	}
	public function deleteAction(){
		$objDocFun = new Sys_Function_DocFunctions();
		$objSendReceive = new sendReceived_modSendReceived();
		$ojbSysInitConfig = new Sys_Init_Config();	
		$SendRecievedDocumentIdList = $this->_request->getParam('hdn_object_id_list','');
		$sRetError = $objSendReceive->DocSendReceivedDelete($SendRecievedDocumentIdList,1);
		echo $arrResult;
		if($sRetError != null || $sRetError != '' ){
			echo "<script type='text/javascript'>alert('$sRetError')</script>";
		}
		else 
			$this->_redirect('sendReceived/sendDocument/index/');	

	}
	public function viewAction(){
		$objDocFun = new Sys_Function_DocFunctions();
		$objSendReceive = new sendReceived_modSendReceived();
		$ojbSysInitConfig = new Sys_Init_Config();	
		$this->view->bodyTitle = "VĂN BẢN ĐI";
		//lay danh sach nguoi ky trong danh muc
		$arrSigner = $objSendReceive->getPropertiesDocument('DM_NGUOI_KY');
		$this->view->arrSigner = $arrSigner;
		//lay danh sach do khan van ban trong danh muc
		$arrUrgent = $objSendReceive->getPropertiesDocument('DM_DO_KHAN_VAN_BAN');
		$this->view->arrUrgent = $arrUrgent;
		//lay danh sach loai van ban trong danh muc
		$arrLoaiVB = $objSendReceive->getPropertiesDocument('DM_LOAI_VAN_BAN');
		$this->view->arrLoaiVB = $arrLoaiVB;
		//Lay id van ban tu view
		$sSendReceiveDocumentId = $this->_request->getParam('hdn_object_id','');
		$this->view->sSendReceiveDocumentId = $sSendReceiveDocumentId;
		$SendRecievedDocumentIdList = $this->_request->getParam('hdn_object_id_list','');
		$sRetError = $objSendReceive->DocSendReceivedDelete($SendRecievedDocumentIdList,1);
		//Mang luu thong tin chi tiet cua mot van ban
		$sType = $this->_request->getParam('hdn_type','');
		$this->view->pType = $sType;
		$sDocId = $this->_request->getParam('hdn_doc_id','');
		$this->view->pDocId = $sDocId;
		$arrSendReceived = $objSendReceive->DocSendReceivedGetSingle($sSendReceiveDocumentId,$sDocId,$sType);
		$this->view->arrSendReceived = $arrSendReceived;
		//Lay gia tri tim kiem tren form
		if(!isset($_SESSION['seArrParameter'])){
			$sfullTextSearch 	= $this->_request->getParam('txtfullTextSearch','');
			$sfromDate 			= $this->_request->getParam('txtfromDate','');
			$stoDate 			= $this->_request->getParam('txttoDate','');
			$iCurrentPage		= $this->_request->getParam('hdn_current_page',0);
			$iNumRowOnPage 		= $this->_request->getParam('hdn_record_number_page',0);
			if ($iCurrentPage <= 1){
				$iCurrentPage = 1;
			}
			$iNumRowOnPage = $this->_request->getParam('cbo_nuber_record_page',0);
			if ($iNumRowOnPage == 0)
				$iNumRowOnPage = 15;
			$arrParaSet = array("trangHienThoi"=>$iCurrentPage, "soBanGhiTrenTrang"=>$iNumRowOnPage,"chuoiTimKiem"=>$sfullTextSearch,"tuNgay"=>$sfromDate,"denNgay"=>$stoDate);
			$_SESSION['seArrParameter'] = $arrParaSet;	
		}
	}
	public function printAction(){
		$objDocFun = new Sys_Function_DocFunctions();
		$objSendReceive = new sendReceived_modSendReceived();
		$ojbSysInitConfig = new Sys_Init_Config();	
		$this->view->bodyTitle = "VĂN BẢN ĐI";
		//lay danh sach nguoi ky trong danh muc
		$arrSigner = $objSendReceive->getPropertiesDocument('DM_NGUOI_KY');
		$this->view->arrSigner = $arrSigner;
		//lay danh sach do khan van ban trong danh muc
		$arrUrgent = $objSendReceive->getPropertiesDocument('DM_DO_KHAN_VAN_BAN');
		$this->view->arrUrgent = $arrUrgent;
		//lay danh sach loai van ban trong danh muc
		$arrLoaiVB = $objSendReceive->getPropertiesDocument('DM_LOAI_VAN_BAN');
		$this->view->arrLoaiVB = $arrLoaiVB;
		//Lay id van ban tu view
		$pSendReceiveDocumentId = $this->_request->getParam('hdn_object_id','');
		$sType = $this->_request->getParam('hdn_type','');
		$sDocId = $this->_request->getParam('hdn_doc_id','');
		//Mang luu thong tin chi tiet cua mot van ban
		$arrSendReceived = $objSendReceive->DocSendReceivedGetSingle($pSendReceiveDocumentId,$sDocId,$sType);
		$this->view->sSendReceiveDocumentId = $pSendReceiveDocumentId;
		$this->view->arrSendReceived = $arrSendReceived;
		//Lay file dinh kem
		$strFileName 				= $arrSendReceived[0]['C_FILE_NAME'];
		$sFile 						= Sys_Library::_getAllFileAttach($strFileName,"!#~$|*","!~!",$this->_request->getBaseUrl() . "/public/attach-file/");
		//Lay duong dan
		$path = $_SERVER['SCRIPT_FILENAME'];
		$path = substr($path, 0, -9);
		if($arrSendReceived[0]['C_DOC_TYPE'] != 'Giấy mời')
			$my_report = str_replace("/", "\\", $path) . "rpt\\sendRecieved\\sendReceived.rpt";
		else 
			$my_report = str_replace("/", "\\", $path) . "rpt\\sendRecieved\\sendReceived_giaymoi.rpt";
		// Tao doi tuong Crystal 9
		$COM_Object = "CrystalDesignRunTime.Application.9";		
		$crapp= new COM($COM_Object) or die("Unable to Create Object");
		$creport = $crapp->OpenReport($my_report, 1);		
		$creport->ReadRecords();
		// Truyen tham so vao
		//Lay ten can bo nhan dua vao ma can bo trong cot C_STAFF_ID_LIST
		$sstaffName = str_replace(';','; ',$arrSendReceived[0]['C_STAFF_NAME_LIST']);
		//Lay ten don vi, phong ban nhan dua vao don vi, phong ban trong cot C_UNIT_ID_LIST
		$sunitName = str_replace(';','; ',$arrSendReceived[0]['C_UNIT_NAME_LIST']);
		if($arrSendReceived[0]['C_DOC_TYPE'] != 'Giấy mời'){
			$creport->ParameterFields(1)->SetCurrentValue($arrSendReceived[0]['C_DOC_TYPE']);
			$creport->ParameterFields(2)->SetCurrentValue($arrSendReceived[0]['C_SEND_DATE']);
			$creport->ParameterFields(3)->SetCurrentValue(Sys_Library::_yyyymmddToDDmmyyyy($arrSendReceived[0]['C_RELEASE_DATE']));
			$creport->ParameterFields(4)->SetCurrentValue($arrSendReceived[0]['C_SYMBOL']);
			$creport->ParameterFields(5)->SetCurrentValue($arrSendReceived[0]['C_SUBJECT']);
			$creport->ParameterFields(6)->SetCurrentValue($arrSendReceived[0]['C_TEXT_OF_EMERGENCY']);
			$creport->ParameterFields(7)->SetCurrentValue($arrSendReceived[0]['C_SIGNER_POSITION_NAME']);
			$creport->ParameterFields(8)->SetCurrentValue($arrSendReceived[0]['C_NUMBER_SHEET']);
			$creport->ParameterFields(9)->SetCurrentValue($arrSendReceived[0]['C_NUMBER_PAGE']);
			$creport->ParameterFields(10)->SetCurrentValue($arrSendReceived[0]['C_OTHER']);
			$creport->ParameterFields(11)->SetCurrentValue($sstaffName);
			$creport->ParameterFields(12)->SetCurrentValue($sunitName);
			$creport->ParameterFields(13)->SetCurrentValue((string)$sFile); 
		}else{
			$creport->ParameterFields(1)->SetCurrentValue($arrSendReceived[0]['C_DOC_TYPE']);
			$creport->ParameterFields(2)->SetCurrentValue($arrSendReceived[0]['C_SEND_DATE']);
			$creport->ParameterFields(3)->SetCurrentValue(Sys_Library::_yyyymmddToDDmmyyyy($arrSendReceived[0]['C_RELEASE_DATE']));
			$creport->ParameterFields(4)->SetCurrentValue($arrSendReceived[0]['C_SYMBOL']);
			$creport->ParameterFields(5)->SetCurrentValue($arrSendReceived[0]['C_HOURS']);
			$creport->ParameterFields(6)->SetCurrentValue(Sys_Library::_yyyymmddToDDmmyyyy($arrSendReceived[0]['C_DATE']));
			$creport->ParameterFields(7)->SetCurrentValue($arrSendReceived[0]['C_ADDRESS']);
			$creport->ParameterFields(8)->SetCurrentValue($arrSendReceived[0]['C_SUBJECT']);
			$creport->ParameterFields(9)->SetCurrentValue($arrSendReceived[0]['C_TEXT_OF_EMERGENCY']);
			$creport->ParameterFields(10)->SetCurrentValue($arrSendReceived[0]['C_SIGNER_POSITION_NAME']);
			$creport->ParameterFields(11)->SetCurrentValue($arrSendReceived[0]['C_NUMBER_SHEET']);
			$creport->ParameterFields(12)->SetCurrentValue($arrSendReceived[0]['C_NUMBER_PAGE']);
			$creport->ParameterFields(13)->SetCurrentValue($arrSendReceived[0]['C_OTHER']);
			$creport->ParameterFields(14)->SetCurrentValue($sstaffName);
			$creport->ParameterFields(15)->SetCurrentValue($sunitName);
			$creport->ParameterFields(16)->SetCurrentValue((string)$sFile); 
		}
		//Ten file
		$report_file = 'sendReceived.doc';
		// Duong dan file report
		$my_report_file = str_replace("/", "\\", $path) . "public\\" . $report_file;
		//export to PDF process
		$creport->ExportOptions->DiskFileName=$my_report_file; //export to file 
		$creport->ExportOptions->PDFExportAllPages=true;
		$creport->ExportOptions->DestinationType = 1; // export to file
		$creport->ExportOptions->FormatType= 14;
		$creport->Export(false);
		$my_report_file = 'http://'.$_SERVER['HTTP_HOST'].':8080/'.$this->_request->getBaseUrl() .'/public/' . $report_file;
		$this->view->my_report_file = $my_report_file;
	}
	
	/**
	 * nguoi tao: phuongtt
	 * ngay tao: 28/06/2010
	 * y nghia: phuong thuc lay mot van ban den
	 */
	public function getreceivedAction(){
		//An MeneLeft , MenuHeader , MenuFooter	
		$this->view->hideDisplayMeneLeft = ""; 
		$this->view->hideDisplayMenuHeader ="";
		$this->view->hideDisplayMenuFooter = "";
		$this->view->bodyTitle = "LẤY VĂN BẢN ĐẾN";
		$objSendReceive = new sendReceived_modSendReceived();
		$sUrl = $_SERVER['REQUEST_URI'];			
		//Lay cac hang so dung chung
		$arrConst = Sys_Init_Config::_setProjectPublicConst();
		$this->view->arrConst = $arrConst;
		// Tao doi tuong Zend_Filter
		$objFilter = new Zend_Filter();
		$ojbSysLib = new Sys_Library();
		$objDocFun = new Sys_Function_DocFunctions();
		// Lay toan bo tham so truyen tu form			
		$arrInput = $this->_request->getParams();		
		$objReceive = new sendReceived_modSendReceived();
		//Lay TEN cua NSD dang nhap hien thoi
		$sStaffName = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'name');
		//Lay CHUC VU phong ban cua NSD dang nhap hien thoi
		$sStaffPosition = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'position_code');			
		//Lay ID cua NSD dang nhap hien thoi
		$StaffId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
		//Lay ID phong ban cua NSD dang nhap hien thoi
		$piUnitId = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');	
		// Goi ham search lay ra toan bo thong tin can bo nhan
		$this->view->search_textselectbox_staff = Sys_Function_DocFunctions::doc_search_ajax($_SESSION['arr_all_staff_keep'],"id","name","C_STAFF_ID_LIST","hdn_staff_id_list",0,'position_code');
		// Goi ham search lay ra toan bo thong don vi, phong ban nhan
		$this->view->search_textselectbox_unit = Sys_Function_DocFunctions::doc_search_ajax($_SESSION['arr_all_unit_keep'],"id","name","C_UNIT_ID_LIST","hdn_unit_id_list",0);
		$sStaffList = $objDocFun->convertStaffNameToStaffId($objFilter->filter($arrInput['C_STAFF_ID_LIST']));
		$sUnitList = $objDocFun->convertUnitNameListToUnitIdList($objFilter->filter($arrInput['C_UNIT_ID_LIST']));
		if($sStaffList !='' OR $sUnitList != '')
				$sSendReceivedStatus = 'DA_GUI';
		else 	$sSendReceivedStatus = 'CHUA_GUI';
		$arrParameter = array(										
							'PK_SEND_RECEIVE'				=>'',	
							'FK_UNIT'						=>$_SESSION['OWNER_ID'],			
							'FK_CREATER'					=>$StaffId,
							'FK_CREATER_POSITION_NAME'		=>$sStaffPosition . ' - ' . $sStaffName,
							'C_DOC_TYPE'					=>'',
							'C_SYMBOL'						=>'',
							'C_DATE'						=>'',
							'C_RELEASE_DATE'				=>'',
							'C_HOURS'						=>'',
							'C_ADDRESS'						=>'',																	
							'C_SUBJECT'						=>'',
							'C_TEXT_OF_EMERGENCY'			=>'',
							'C_SIGNER_POSITION_NAME'		=>'',
							'C_NUMBER_SHEET'				=>'',
							'C_NUMBER_PAGE'					=>'',
							'C_OTHER'						=>$this->_request->getParam('C_OTHER',''),	
							'C_STAFF_ID_LIST'				=>$sStaffList,
							'C_STAFF_NAME_LIST'				=>str_replace(';','!#~$|*',$this->_request->getParam('C_STAFF_ID_LIST','')),
							'C_UNIT_ID_LIST'				=>$sUnitList,
							'C_UNIT_NAME_LIST'				=>str_replace(';','!#~$|*',$this->_request->getParam('C_UNIT_ID_LIST','')),
							'NEW_FILE_ID_LIST'				=>'',
							'FK_DOC_LIST'					=>$this->_request->getParam('hdn_object_id_list',''),
							'FK_DOC'						=>'',
							'C_TYPE'						=>'VB_DEN',
							'C_XML_DATA'					=>'',
							'C_SEND_RECEIVED_STATUS'		=>$sSendReceivedStatus,
							);
		$arrResult = "";
		if ($this->_request->getParam('hdn_save','') == 'GHI_TAM' || $this->_request->getParam('hdn_save','') == 'GUI'){
			$arrResult = $objSendReceive->DocSendReceivedUpdate($arrParameter);
		}
	}
	/**
	 * nguoi tao: phuongtt
	 * ngay tao: 28/06/2010
	 * y nghia: phuong thuc hieu chinh lay van ban den
	 */
	public function editreceivedAction(){
		//An MeneLeft , MenuHeader , MenuFooter	
		$this->view->hideDisplayMeneLeft = ""; 
		$this->view->hideDisplayMenuHeader ="";
		$this->view->hideDisplayMenuFooter = "";
		$this->view->bodyTitle = "HIỆU CHỈNH LẤY VĂN BẢN ĐẾN";
		$objSendReceive = new sendReceived_modSendReceived();
		$sUrl = $_SERVER['REQUEST_URI'];			
		//Lay cac hang so dung chung
		$arrConst = Sys_Init_Config::_setProjectPublicConst();
		$this->view->arrConst = $arrConst;
		// Tao doi tuong Zend_Filter
		$objFilter = new Zend_Filter();
		$ojbSysLib = new Sys_Library();
		$objDocFun = new Sys_Function_DocFunctions();
		// Lay toan bo tham so truyen tu form			
		$arrInput = $this->_request->getParams();		
		$objReceive = new sendReceived_modSendReceived();
		//Lay TEN cua NSD dang nhap hien thoi
		$sStaffName = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'name');
		//Lay CHUC VU phong ban cua NSD dang nhap hien thoi
		$sStaffPosition = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'position_code');			
		//Lay ID cua NSD dang nhap hien thoi
		$StaffId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
		//Lay ID phong ban cua NSD dang nhap hien thoi
		$piUnitId = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');	
		// Goi ham search lay ra toan bo thong tin can bo nhan
		$this->view->search_textselectbox_staff = Sys_Function_DocFunctions::doc_search_ajax($_SESSION['arr_all_staff_keep'],"id","name","C_STAFF_ID_LIST","hdn_staff_id_list",0,'position_code');
		// Goi ham search lay ra toan bo thong don vi, phong ban nhan
		$this->view->search_textselectbox_unit = Sys_Function_DocFunctions::doc_search_ajax($_SESSION['arr_all_unit_keep'],"id","name","C_UNIT_ID_LIST","hdn_unit_id_list",0);
		$sStaffList = $objDocFun->convertStaffNameToStaffId($objFilter->filter($arrInput['C_STAFF_ID_LIST']));
		$sUnitList = $objDocFun->convertUnitNameListToUnitIdList($objFilter->filter($arrInput['C_UNIT_ID_LIST']));
		$sSendReceiveDocumentId = $this->_request->getParam('hdn_object_id','');
		$sDocId 				= $this->_request->getParam('hdn_doc_id','');
		$sType					= $this->_request->getParam('hdn_type','');
		$this->view->sDocId = $sDocId;
		$this->view->sSendReceiveDocumentId = $sSendReceiveDocumentId;
		//Mang luu thong tin chi tiet cua mot van ban
		$arrSendReceived = $objSendReceive->DocSendReceivedGetSingle($sSendReceiveDocumentId,$sDocId,$sType);
		$this->view->arrSendReceived = $arrSendReceived;
		if($sStaffList !='' OR $sUnitList != '')
				$sSendReceivedStatus = 'DA_GUI';
		else 	$sSendReceivedStatus = 'CHUA_GUI';
		$sDocIdOld 				= $this->_request->getParam('hdn_doc_id_old','');
		$sDocIdList				= $this->_request->getParam('hdn_object_id_list','');
		$sDocIdTemp = '';
		if($sDocId != '')
				$sDocIdTemp = $sDocId;
		else 	$sDocIdTemp = $sDocIdOld;
		if(strchr($sDocIdList,$sDocIdTemp) == ''){
			if ($this->_request->getParam('hdn_save','') == 'GHI_TAM' || $this->_request->getParam('hdn_save','') == 'GUI'){
				$objSendReceive->DocSendReceivedDelete($sSendReceiveDocumentId,1);//exit;
			}
			$sSendReceiveDocumentId = '';
			$sDocIdTemp = '';
		}
		if(stripos($sDocIdList,$sDocIdTemp) >= 0 && $sDocIdTemp != ''){	
			$numstr = strlen($sDocIdList);
			$sDocIdOldcmp =$sDocIdTemp.',';
			$sDocIdList = str_replace($sDocIdOldcmp,'',$sDocIdList);
			if($numstr == strlen($sDocIdList)){
				$sDocIdOldcmp =','.$sDocIdTemp;
				$sDocIdList = str_replace($sDocIdOldcmp,'',$sDocIdList);
			}
		}
		if($sDocIdTemp == $sDocIdList)
			$sDocIdList = '';
		$arrParameter = array(										
							'PK_SEND_RECEIVE'				=>$sSendReceiveDocumentId,	
							'FK_UNIT'						=>$_SESSION['OWNER_ID'],			
							'FK_CREATER'					=>$StaffId,
							'FK_CREATER_POSITION_NAME'		=>$sStaffPosition . ' - ' . $sStaffName,
							'C_DOC_TYPE'					=>'',
							'C_SYMBOL'						=>'',
							'C_DATE'						=>'',
							'C_RELEASE_DATE'				=>'',
							'C_HOURS'						=>'',
							'C_ADDRESS'						=>'',																	
							'C_SUBJECT'						=>'',
							'C_TEXT_OF_EMERGENCY'			=>'',
							'C_SIGNER_POSITION_NAME'		=>'',
							'C_NUMBER_SHEET'				=>'',
							'C_NUMBER_PAGE'					=>'',
							'C_OTHER'						=>$this->_request->getParam('C_OTHER',''),	
							'C_STAFF_ID_LIST'				=>$sStaffList,
							'C_STAFF_NAME_LIST'				=>str_replace(';','!#~$|*',$this->_request->getParam('C_STAFF_ID_LIST','')),
							'C_UNIT_ID_LIST'				=>$sUnitList,
							'C_UNIT_NAME_LIST'				=>str_replace(';','!#~$|*',$this->_request->getParam('C_UNIT_ID_LIST','')),
							'NEW_FILE_ID_LIST'				=>'',
							'FK_DOC_LIST'					=>$sDocIdList,
							'FK_DOC'						=>$sDocIdTemp,
							'C_TYPE'						=>'VB_DEN',
							'C_XML_DATA'					=>'',
							'C_SEND_RECEIVED_STATUS'		=>$sSendReceivedStatus,
							);
		$arrResult = "";
		if ($this->_request->getParam('hdn_save','') == 'GHI_TAM' || $this->_request->getParam('hdn_save','') == 'GUI'){
			$arrResult = $objSendReceive->DocSendReceivedUpdate($arrParameter);
		}
	}
	/**
	 * nguoi tao: phuongtt
	 * ngay tao: 28/06/2010
	 * y nghia: phuong thuc lay mot van ban di
	 */
	public function getsendAction(){
		//An MeneLeft , MenuHeader , MenuFooter	
		$this->view->hideDisplayMeneLeft = ""; 
		$this->view->hideDisplayMenuHeader ="";
		$this->view->hideDisplayMenuFooter = "";
		$this->view->bodyTitle = "LẤY VĂN BẢN ĐI";
		$objSendReceive = new sendReceived_modSendReceived();
		$sUrl = $_SERVER['REQUEST_URI'];			
		//Lay cac hang so dung chung
		$arrConst = Sys_Init_Config::_setProjectPublicConst();
		$this->view->arrConst = $arrConst;
		// Tao doi tuong Zend_Filter
		$objFilter = new Zend_Filter();
		$ojbSysLib = new Sys_Library();
		$objDocFun = new Sys_Function_DocFunctions();
		// Lay toan bo tham so truyen tu form			
		$arrInput = $this->_request->getParams();		
		$objReceive = new sendReceived_modSendReceived();
		//Lay TEN cua NSD dang nhap hien thoi
		$sStaffName = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'name');
		//Lay CHUC VU phong ban cua NSD dang nhap hien thoi
		$sStaffPosition = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'position_code');			
		//Lay ID cua NSD dang nhap hien thoi
		$StaffId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
		//Lay ID phong ban cua NSD dang nhap hien thoi
		$piUnitId = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');	
		// Goi ham search lay ra toan bo thong tin can bo nhan
		$this->view->search_textselectbox_staff = Sys_Function_DocFunctions::doc_search_ajax($_SESSION['arr_all_staff_keep'],"id","name","C_STAFF_ID_LIST","hdn_staff_id_list",0,'position_code');
		// Goi ham search lay ra toan bo thong don vi, phong ban nhan
		$this->view->search_textselectbox_unit = Sys_Function_DocFunctions::doc_search_ajax($_SESSION['arr_all_unit_keep'],"id","name","C_UNIT_ID_LIST","hdn_unit_id_list",0);
		$sStaffList = $objDocFun->convertStaffNameToStaffId($objFilter->filter($arrInput['C_STAFF_ID_LIST']));
		$sUnitList = $objDocFun->convertUnitNameListToUnitIdList($objFilter->filter($arrInput['C_UNIT_ID_LIST']));
		if($sStaffList !='' OR $sUnitList != '')
				$sSendReceivedStatus = 'DA_GUI';
		else 	$sSendReceivedStatus = 'CHUA_GUI';
		$arrParameter = array(										
							'PK_SEND_RECEIVE'				=>'',	
							'FK_UNIT'						=>$_SESSION['OWNER_ID'],			
							'FK_CREATER'					=>$StaffId,
							'FK_CREATER_POSITION_NAME'		=>$sStaffPosition . ' - ' . $sStaffName,
							'C_DOC_TYPE'					=>'',
							'C_SYMBOL'						=>'',
							'C_DATE'						=>'',
							'C_RELEASE_DATE'				=>'',
							'C_HOURS'						=>'',
							'C_ADDRESS'						=>'',																	
							'C_SUBJECT'						=>'',
							'C_TEXT_OF_EMERGENCY'			=>'',
							'C_SIGNER_POSITION_NAME'		=>'',
							'C_NUMBER_SHEET'				=>'',
							'C_NUMBER_PAGE'					=>'',
							'C_OTHER'						=>$this->_request->getParam('C_OTHER',''),	
							'C_STAFF_ID_LIST'				=>$sStaffList,
							'C_STAFF_NAME_LIST'				=>str_replace(';','!#~$|*',$this->_request->getParam('C_STAFF_ID_LIST','')),
							'C_UNIT_ID_LIST'				=>$sUnitList,
							'C_UNIT_NAME_LIST'				=>str_replace(';','!#~$|*',$this->_request->getParam('C_UNIT_ID_LIST','')),
							'NEW_FILE_ID_LIST'				=>'',
							'FK_DOC_LIST'					=>$this->_request->getParam('hdn_object_id_list',''),
							'FK_DOC'						=>'',
							'C_TYPE'						=>'VB_DI',
							'C_XML_DATA'					=>'',
							'C_SEND_RECEIVED_STATUS'		=>$sSendReceivedStatus,
							);
		$arrResult = "";
		if ($this->_request->getParam('hdn_save','') == 'GHI_TAM' || $this->_request->getParam('hdn_save','') == 'GUI'){
			$arrResult = $objSendReceive->DocSendReceivedUpdate($arrParameter);
		}
	}
	/**
	 * nguoi tao: phuongtt
	 * ngay tao: 28/06/2010
	 * y nghia: phuong thuc hieu chinh lay van ban den
	 */
	public function editsendAction(){
		//An MeneLeft , MenuHeader , MenuFooter	
		$this->view->hideDisplayMeneLeft = ""; 
		$this->view->hideDisplayMenuHeader ="";
		$this->view->hideDisplayMenuFooter = "";
		$this->view->bodyTitle = "HIỆU CHỈNH LẤY VĂN BẢN ĐI";
		$objSendReceive = new sendReceived_modSendReceived();
		$sUrl = $_SERVER['REQUEST_URI'];			
		//Lay cac hang so dung chung
		$arrConst = Sys_Init_Config::_setProjectPublicConst();
		$this->view->arrConst = $arrConst;
		// Tao doi tuong Zend_Filter
		$objFilter = new Zend_Filter();
		$ojbSysLib = new Sys_Library();
		$objDocFun = new Sys_Function_DocFunctions();
		// Lay toan bo tham so truyen tu form			
		$arrInput = $this->_request->getParams();		
		$objReceive = new sendReceived_modSendReceived();
		//Lay TEN cua NSD dang nhap hien thoi
		$sStaffName = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'name');
		//Lay CHUC VU phong ban cua NSD dang nhap hien thoi
		$sStaffPosition = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'position_code');			
		//Lay ID cua NSD dang nhap hien thoi
		$StaffId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
		//Lay ID phong ban cua NSD dang nhap hien thoi
		$piUnitId = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');	
		// Goi ham search lay ra toan bo thong tin can bo nhan
		$this->view->search_textselectbox_staff = Sys_Function_DocFunctions::doc_search_ajax($_SESSION['arr_all_staff_keep'],"id","name","C_STAFF_ID_LIST","hdn_staff_id_list",0,'position_code');
		// Goi ham search lay ra toan bo thong don vi, phong ban nhan
		$this->view->search_textselectbox_unit = Sys_Function_DocFunctions::doc_search_ajax($_SESSION['arr_all_unit_keep'],"id","name","C_UNIT_ID_LIST","hdn_unit_id_list",0);
		$sStaffList = $objDocFun->convertStaffNameToStaffId($objFilter->filter($arrInput['C_STAFF_ID_LIST']));
		$sUnitList = $objDocFun->convertUnitNameListToUnitIdList($objFilter->filter($arrInput['C_UNIT_ID_LIST']));
		$sSendReceiveDocumentId = $this->_request->getParam('hdn_object_id','');
		$sDocId 				= $this->_request->getParam('hdn_doc_id','');
		$sType					= $this->_request->getParam('hdn_type','');
		$this->view->sDocId = $sDocId;
		$this->view->sSendReceiveDocumentId = $sSendReceiveDocumentId;
		//Mang luu thong tin chi tiet cua mot van ban
		$arrSendReceived = $objSendReceive->DocSendReceivedGetSingle($sSendReceiveDocumentId,$sDocId,$sType);
		$this->view->arrSendReceived = $arrSendReceived;
		if($sStaffList !='' OR $sUnitList != '')
				$sSendReceivedStatus = 'DA_GUI';
		else 	$sSendReceivedStatus = 'CHUA_GUI';
		$sDocIdOld 				= $this->_request->getParam('hdn_doc_id_old','');
		$sDocIdList				= $this->_request->getParam('hdn_object_id_list','');
		$sDocIdTemp = '';
		if($sDocId != '')
				$sDocIdTemp = $sDocId;
		else 	$sDocIdTemp = $sDocIdOld;
		if(strchr($sDocIdList,$sDocIdTemp) == ''){
			if ($this->_request->getParam('hdn_save','') == 'GHI_TAM' || $this->_request->getParam('hdn_save','') == 'GUI'){
				$objSendReceive->DocSendReceivedDelete($sSendReceiveDocumentId,1);
			}
			$sSendReceiveDocumentId = '';
			$sDocIdTemp = '';
		}
		if(stripos($sDocIdList,$sDocIdTemp) >= 0 && $sDocIdTemp != ''){	
			$numstr = strlen($sDocIdList);
			$sDocIdOldcmp =$sDocIdTemp.',';
			$sDocIdList = str_replace($sDocIdOldcmp,'',$sDocIdList);
			if($numstr == strlen($sDocIdList)){
				$sDocIdOldcmp =','.$sDocIdTemp;
				$sDocIdList = str_replace($sDocIdOldcmp,'',$sDocIdList);
			}
		}
		if($sDocIdTemp == $sDocIdList)
			$sDocIdList = '';
		$arrParameter = array(										
							'PK_SEND_RECEIVE'				=>$sSendReceiveDocumentId,	
							'FK_UNIT'						=>$_SESSION['OWNER_ID'],			
							'FK_CREATER'					=>$StaffId,
							'FK_CREATER_POSITION_NAME'		=>$sStaffPosition . ' - ' . $sStaffName,
							'C_DOC_TYPE'					=>'',
							'C_SYMBOL'						=>'',
							'C_DATE'						=>'',
							'C_RELEASE_DATE'				=>'',
							'C_HOURS'						=>'',
							'C_ADDRESS'						=>'',																	
							'C_SUBJECT'						=>'',
							'C_TEXT_OF_EMERGENCY'			=>'',
							'C_SIGNER_POSITION_NAME'		=>'',
							'C_NUMBER_SHEET'				=>'',
							'C_NUMBER_PAGE'					=>'',
							'C_OTHER'						=>$this->_request->getParam('C_OTHER',''),	
							'C_STAFF_ID_LIST'				=>$sStaffList,
							'C_STAFF_NAME_LIST'				=>str_replace(';','!#~$|*',$this->_request->getParam('C_STAFF_ID_LIST','')),
							'C_UNIT_ID_LIST'				=>$sUnitList,
							'C_UNIT_NAME_LIST'				=>str_replace(';','!#~$|*',$this->_request->getParam('C_UNIT_ID_LIST','')),
							'NEW_FILE_ID_LIST'				=>'',
							'FK_DOC_LIST'					=>$sDocIdList,
							'FK_DOC'						=>$sDocIdTemp,
							'C_TYPE'						=>'VB_DI',
							'C_XML_DATA'					=>'',
							'C_SEND_RECEIVED_STATUS'		=>$sSendReceivedStatus,
							);
		$arrResult = "";
		if ($this->_request->getParam('hdn_save','') == 'GHI_TAM' || $this->_request->getParam('hdn_save','') == 'GUI'){
			$arrResult = $objSendReceive->DocSendReceivedUpdate($arrParameter);
		}
	}
	public function ropreceivedAction(){
		$objDocFun = new Sys_Function_DocFunctions();
		$objSendReceive = new sendReceived_modSendReceived();
		$ojbSysInitConfig = new Sys_Init_Config();	
		//Lay cac hang so dung chung
		$arrConst = Sys_Init_Config::_setProjectPublicConst();
		$this->view->arrConst = $arrConst;
		//lay danh sach nguoi ky trong danh muc
		$arrSigner = $objSendReceive->getPropertiesDocument('DM_NGUOI_KY');
		$this->view->arrSigner = $arrSigner;
		//lay danh sach do khan van ban trong danh muc
		$arrUrgent = $objSendReceive->getPropertiesDocument('DM_DO_KHAN_VAN_BAN');
		$this->view->arrUrgent = $arrUrgent;
		//lay danh sach loai van ban trong danh muc
		$arrLoaiVB = $objSendReceive->getPropertiesDocument('DM_LOAI_VAN_BAN');
		$this->view->arrLoaiVB = $arrLoaiVB;
		//Lay id van ban tu view
		$sSendReceiveDocumentId = $this->_request->getParam('hdn_object_id','');
		//echo 'ok'. $sSendReceiveDocumentId;
		$this->view->sSendReceiveDocumentId = $sSendReceiveDocumentId;
		//$SendRecievedDocumentIdList = $this->_request->getParam('hdn_object_id_list','');
		//$sRetError = $objSendReceive->DocSendReceivedDelete($SendRecievedDocumentIdList,1);
		//Lay ID cua NSD dang nhap hien thoi
		$iCurrentStaffId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
		//Lay TEN cua NSD dang nhap hien thoi
		$sStaffPositionName = str_replace('!#~$|*',',',$objDocFun->getNamePositionStaffByIdList($iCurrentStaffId));
		//Lay ID phong ban cua NSD dang nhap hien thoi
		$iCurrentUnitId = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');	
		//if($Resul == null)
		//	$Resul =$objSendReceive->DocSendReceivedUpdateReceivedInfo($sSendReceiveDocumentId,$iCurrentStaffId,$sStaffPositionName,$iCurrentUnitId);
		//Mang luu thong tin chi tiet cua mot van ban
		$sType = $this->_request->getParam('hdn_type','');
		$sDocId = $this->_request->getParam('hdn_doc_id','');
		$arrSendReceived = $objSendReceive->DocSendReceivedGetSingle($sSendReceiveDocumentId,$sDocId,$sType);
		$this->view->arrSendReceived = $arrSendReceived;
		$arrInfoReceived = $objSendReceive->DocSendReceivedGetReceivedInfo($sSendReceiveDocumentId);
		$this->view->arrInfoReceived = $arrInfoReceived;
	}
}?>
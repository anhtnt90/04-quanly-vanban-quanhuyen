<?php
/**
 * Nguoi tao: phuongtt
 * Ngay tao: 08/09/2010
 * Y nghia: Class Xu ly GUI-NHAN VB dien tu
 */	
class sendReceived_receivedDocumentController extends  Zend_Controller_Action {
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
		else 	$this->view->currentModulCodeForLeft = 'RECEIVED-DOCUMENT';
		$this->view->showModelDialog = $psshowModalDialog;
		if ($psshowModalDialog != 1){
			//Hien thi file template
			$response->insert('header', $this->view->renderLayout('twd_header.phtml','template/'));    
			$response->insert('left', $this->view->renderLayout('twd_left.phtml','template/'));  	    
	        $response->insert('footer', $this->view->renderLayout('twd_footer.phtml','template/'));
		}
  	}	
	/**
	 * Idea : Phuong thuc hien thi danh sach
	 *
	 */
	public function indexAction(){
		//Lay ID cua NSD dang nhap hien thoi
		$StaffId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
		//Lay ID phong ban cua NSD dang nhap hien thoi
		$piUnitId = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');	
		$pUrl = $_SERVER['REQUEST_URI'];
		// Tieu de tim kiem
		$this->view->bodyTitleSearch = "DANH SÁCH VĂN BẢN ĐẾN";				
		// Tieu de man hinh danh sach
		$this->view->bodyTitle = "DANH SÁCH VĂN BẢN ĐẾN";
		//Bat dau lay vet tim kiem tu session
		if(isset($_SESSION['seArrParameter']['tuNgay']) || $_SESSION['seArrParameter']['tuNgay'] != ""){
			$sfromDate = $this->_request->getParam('txtfromDate','');
			if($sfromDate != $_SESSION['seArrParameter']['tuNgay'] && $sfromDate != "")
				$_SESSION['seArrParameter']['tuNgay'] = $sfromDate;
			$sfromDate = $_SESSION['seArrParameter']['tuNgay'];
			$this->view->fromDate = $_SESSION['seArrParameter']['tuNgay'];
		}else{
			$sfromDate = $this->_request->getParam('txtfromDate','');
			//if($sfromDate =="")
				//$sfromDate = '1/1/'.date("Y");
			$this->view->fromDate = $sfromDate;
		}
		if(isset($_SESSION['seArrParameter']['denNgay']) || $_SESSION['seArrParameter']['denNgay'] != ""){
			$stoDate = $this->_request->getParam('txttoDate','');
			if($stoDate != $_SESSION['seArrParameter']['denNgay'] && $stoDate != ""){
				$_SESSION['seArrParameter']['denNgay'] = $stoDate;
			}
			$stoDate = $_SESSION['seArrParameter']['denNgay'];
			$this->view->toDate = $_SESSION['seArrParameter']['denNgay'];
		}else {
			$stoDate = $this->_request->getParam('txttoDate','');
			//if($stoDate == "")
				//$stoDate = date("d/m/Y");
			$this->view->toDate = $stoDate;
		}
		//Lay thong tin chuoi tim kiem trong session hoac tren form (uu tien tren form)
		if(isset($_SESSION['seArrParameter']['chuoiTimKiem']) || $_SESSION['seArrParameter']['chuoiTimKiem'] != ""){
			$sfullTextSearch = $this->_request->getParam('txtfullTextSearch','');
			if($sfullTextSearch != ' ')
				$sfullTextSearch = trim($sfullTextSearch);
			if($sfullTextSearch != $_SESSION['seArrParameter']['chuoiTimKiem'] && $sfullTextSearch != ""){
					$_SESSION['seArrParameter']['chuoiTimKiem'] = $sfullTextSearch;
					//if value string to search is New string then curent page = 1
					$_SESSION['seArrParameter']['trangHienThoi'] = "1";
					$iCurrentPageNew = "1";
			}
			$sfullTextSearch = $_SESSION['seArrParameter']['chuoiTimKiem'];
			$sfullTextSearch = Sys_Publib_Library::_replaceBadChar($sfullTextSearch);
			$this->view->sfullTextSearch = $sfullTextSearch;
		}else {
			$sfullTextSearch = $this->_request->getParam('txtfullTextSearch','');
			$sfullTextSearch = Sys_Publib_Library::_replaceBadChar($sfullTextSearch);
			$this->view->sfullTextSearch = $sfullTextSearch;
		}
		//Get info of number records on page and curent page
		if($iCurrentPageNew =="1") $iCurrentPage = "1";
		else{
			if(isset($_SESSION['seArrParameter']['trangHienThoi']) && $_SESSION['seArrParameter']['trangHienThoi'] != ""){
				$iCurrentPage = $this->_request->getParam('hdn_current_page',0);
				if($iCurrentPage != $_SESSION['seArrParameter']['trangHienThoi'] && $iCurrentPage != "")
					$_SESSION['seArrParameter']['trangHienThoi'] = $iCurrentPage;
				$iCurrentPage = $_SESSION['seArrParameter']['trangHienThoi'];
				$this->view->currentPage = $_SESSION['seArrParameter']['trangHienThoi'];
			}else {
				$iCurrentPage = $this->_request->getParam('hdn_current_page',0);
				if($iCurrentPage == "")
					$iCurrentPage = 1;
				$this->view->currentPage = $iCurrentPage;
			}
		}
		if($iCurrentPageNew =="1") $iNumRowOnPage = "15";
		else{
			if(isset($_SESSION['seArrParameter']['soBanGhiTrenTrang']) && $_SESSION['seArrParameter']['soBanGhiTrenTrang'] != ""){
				$iNumRowOnPage = $this->_request->getParam('hdn_record_number_page',0);
				//echo $_SESSION['seArrParameter']['soBanGhiTrenTrang']; exit;
				if($iNumRowOnPage != $_SESSION['seArrParameter']['soBanGhiTrenTrang'] && $iNumRowOnPage != ""){
					$_SESSION['seArrParameter']['soBanGhiTrenTrang'] = $iNumRowOnPage;
				}
				$iNumRowOnPage = $_SESSION['seArrParameter']['soBanGhiTrenTrang'];
				//echo $iNumRowOnPage; exit;
				$this->view->numRowOnPage = $_SESSION['seArrParameter']['soBanGhiTrenTrang'];
			}else {
				$iNumRowOnPage = $this->_request->getParam('hdn_record_number_page',0);
				if($iNumRowOnPage == "")
					$iNumRowOnPage = 15;
				$this->view->numRowOnPage = $iNumRowOnPage;
			}
		}//Ket thuc viec lay vet tim kiem
		//Lay cac hang so dung chung
		$arrCount = Sys_Init_Config::_setProjectPublicConst();
		$this->view->arrCount = $arrCount;
		// Tao doi tuong Zend_Filter
		$objFilter = new Zend_Filter();
		$ojbSysLib = new Sys_Library();
		// Lay toan bo tham so truyen tu form			
		$arrInput = $this->_request->getParams();
		$objSendReceive = new sendReceived_modSendReceived();
		//Lay MA DON VI NSD dang nhap hien thoi
		$sOwnerCode = $_SESSION['OWNER_CODE'];
		//Neu la xem file dinh kem
		$sSendReceiveDocumentId = $this->_request->getParam('hdn_getreceivedinfor','');
		if ($sSendReceiveDocumentId != ''){
			$iCurrentStaffId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
			//Lay TEN cua NSD dang nhap hien thoi
			$sStaffPositionName = str_replace('!#~$|*',',',Sys_Function_DocFunctions::getNamePositionStaffByIdList($iCurrentStaffId));
			//Lay ID phong ban cua NSD dang nhap hien thoi
			$iCurrentUnitId = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');
			$Resul =$objSendReceive->DocSendReceivedUpdateReceivedInfo($sSendReceiveDocumentId,$iCurrentStaffId,$_SESSION['OWNER_ID'],$sStaffPositionName,$iCurrentUnitId);
		}
		//Lay quyen gui nhan van ban
		$permission = $_SESSION['arrStaffPermission']['CHUYEN_VBDIENTU_THANH_VBDEN'];
		$arrResul = $objSendReceive->DocSendReceivedStaffGetAll($StaffId, $_SESSION['OWNER_ID'], $piUnitId, Sys_Library::_ddmmyyyyToYYyymmdd($sfromDate), Sys_Library::_ddmmyyyyToYYyymmdd($stoDate), $sfullTextSearch, $iCurrentPage, $iNumRowOnPage,$permission);			
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
		//Luu gia tri												
		$arrParaSet = array("trangHienThoi"=>$iCurrentPage, "soBanGhiTrenTrang"=>$iNumRowOnPage,"tuNgay"=>$sfromDate,"denNgay"=>$stoDate,"chuoiTimKiem"=>$sfullTextSearch);
		$_SESSION['seArrParameter'] = $arrParaSet;
	}
	public function viewAction(){
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
		$this->view->sSendReceiveDocumentId = $sSendReceiveDocumentId;
		//$SendRecievedDocumentIdList = $this->_request->getParam('hdn_object_id_list','');
		//$sRetError = $objSendReceive->DocSendReceivedDelete($SendRecievedDocumentIdList,1);
		//Lay ID cua NSD dang nhap hien thoi
		$iCurrentStaffId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
		//Lay TEN cua NSD dang nhap hien thoi
		$sStaffPositionName = str_replace('!#~$|*',',',$objDocFun->getNamePositionStaffByIdList($iCurrentStaffId));
		//Lay ID phong ban cua NSD dang nhap hien thoi
		$iCurrentUnitId = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');	
		if($Resul == null)
			$Resul =$objSendReceive->DocSendReceivedUpdateReceivedInfo($sSendReceiveDocumentId,$iCurrentStaffId,$_SESSION['OWNER_ID'],$sStaffPositionName,$iCurrentUnitId);
		//Mang luu thong tin chi tiet cua mot van ban
		$sType = $this->_request->getParam('hdn_type','');
		$sDocId = $this->_request->getParam('hdn_doc_id','');
		$arrSendReceived = $objSendReceive->DocSendReceivedGetSingle($sSendReceiveDocumentId,$sDocId,$sType);
		$this->view->arrSendReceived = $arrSendReceived;
		$arrInfoReceived = $objSendReceive->DocSendReceivedGetReceivedInfo($sSendReceiveDocumentId);
		$this->view->arrInfoReceived = $arrInfoReceived;
	}
	public function addreceivedAction(){
		$this->view->bodyTitle = 'VÀO SỔ VĂN BẢN ĐẾN';
		$arrInput = $this->_request->getParams();
		$objDocFun = new Sys_Function_DocFunctions();
		$objSendReceive = new sendReceived_modSendReceived();
		$ojbXmlLib = new Sys_Publib_Xml();
		$ojbSysLib = new Sys_Library();
		$objFilter = new Zend_Filter();
		$ojbSysInitConfig = new Sys_Init_Config();	
		$arrConst = $ojbSysInitConfig->_setProjectPublicConst();
		$this->view->arrConst = $arrConst;
		 //Lay thong tin history back
		$this->view->historyBack = $this->_request->getParam('hdn_history_back','');
		//Lay tham so cau hinh
		$sysLibUrlPath = $ojbSysInitConfig->_setLibUrlPath();
		$url_path_calendar = $sysLibUrlPath . 'sys-calendar/';
		$this->view->urlCalendar = $url_path_calendar;
		$arrSel = $objSendReceive->getPropertiesDocument('DM_TINH_CHAT_VB','','');
		$this->view->arrSel = $arrSel;
		//var_dump($arrSel);
		$arrUrgent = $objSendReceive->getPropertiesDocument('DM_DO_KHAN_VAN_BAN','','');
		$this->view->arrUrgent = $arrUrgent;
		
		$arrInputBooks = $objSendReceive->getPropertiesDocument('DM_SO_VAN_BAN_DEN','','');
		$this->view->arrInputBooks = $arrInputBooks;
		
		$arrAgentcyGroup = $objSendReceive->getPropertiesDocument('DM_CAP_NOI_GUI_VAN_BAN','','');
		$this->view->arrAgentcyGroup = $arrAgentcyGroup;
		
		$arrAgentcyName = $objSendReceive->getPropertiesDocument('DM_NOI_GUI_VAN_BAN','','');
		$this->view->arrAgentcyName = $arrAgentcyName;
		
		$arrDocType = $objSendReceive->getPropertiesDocument('DM_LOAI_VAN_BAN','','');
		$this->view->arrDocType = $arrDocType;
		
		$arrSigner = $objSendReceive->getPropertiesDocument('DM_NGUOI_KY','','');
		$this->view->arrSigner = $arrSigner;
		//Lay danh sach cac linh vuc
		$arrDocCate = $objSendReceive->getPropertiesDocument('DM_LINH_VUC_VAN_BAN');
		//
		$arrProcessType = $objSendReceive->getPropertiesDocument('DM_HINH_THUC_XU_LY','','');
		$this->view->arrProcessType = $arrProcessType;
		//Lay thong tin trang hien thoi
		$iCurrentPage = $this->_request->getParam('hdn_current_page',0);
		$this->view->currentPage	= $iCurrentPage;	
		//Lay thong tin quy dinh so row / page
		$iNumRowOnPage = $this->_request->getParam('hdn_record_number_page',0);
		$this->view->numRowOnPage	= $iNumRowOnPage;	
		//Tieu chi tim kiem
		$sFullTextSearch = $this->_request->getParam('FullTextSearch','');
		$dFromDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($this->_request->getParam('fromDate',''));
		$dToDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($this->_request->getParam('toDate',''));
		//Luu cac gia tri tim kiem duoc nhap vao tu form 
        $this->view->fromDate = $ojbSysLib->_yyyymmddToDDmmyyyy($dFromDate);
        $this->view->toDate =  $ojbSysLib->_yyyymmddToDDmmyyyy($dToDate);
        $this->view->FullTextSearch = $sFullTextSearch;
		// Goi ham search
		$this->view->search_textselectbox_agentcy_group = Sys_Function_DocFunctions::doc_search_ajax($arrAgentcyGroup,"C_CODE","C_NAME","C_AGENTCY_GROUP","hdn_agentcy_group",1,'',1);
		$this->view->search_textselectbox_agentcy_name = Sys_Function_DocFunctions::doc_search_ajax($arrAgentcyName,"C_CODE","C_NAME","C_AGENTCY_NAME","hdn_agentcy_name",1,'',1);
		$this->view->search_textselectbox_doc_type = Sys_Function_DocFunctions::doc_search_ajax($arrDocType,"C_CODE","C_NAME","C_DOC_TYPE","hdn_doc_type",1,'',1);
		// Goi ham textselectbox lay ra nguoi ky
		$this->view->search_doc_cate_name = $objDocFun->doc_search_ajax($arrDocCate,"C_CODE","C_NAME","C_DOC_CATE","hdn_doc_cate_name",1,"",1);
		
		//Gan quyen sang VIEW
		$this->view->PermissionUser = $this->_publicPermission;
		//Lay Id van ban
		$sReceiveDocumentId = $this->_request->getParam('hdn_object_id','');		
		$this->view->sReceiveDocumentId = $sReceiveDocumentId;
		//Lay thong tin file dinh kem
		$arFileAttach = $objSendReceive->DOC_GetAllDocumentFileAttach($sReceiveDocumentId,'','T_DOC_SEND_RECEIVE_DOCUMENT');
		$arrFileNameUpload = $ojbSysLib->_uploadFileList(10,$this->_request->getBaseUrl() . "/public/attach-file/",'FileName','!#~$|*');
		if(!is_null($arFileAttach) && $arFileAttach != '')
			foreach ($arFileAttach As $fileAttach)
				$arrFileNameUpload .= '!#~$|*'.$fileAttach['C_FILE_NAME'];
		$this->view->AttachFile = $objDocFun->DocSentAttachFile($arFileAttach,sizeof($arFileAttach),10,true,25);	
		//Mang luu thong tin van ban
		$Result = '';
		$sType = $this->_request->getParam('hdn_type','');
		$sDocId = $this->_request->getParam('hdn_doc_id','');
		$arrReceived = $objSendReceive->DocSendReceivedGetSingle($sReceiveDocumentId,$sDocId,$sType);
		$this->view->arrReceived = $arrReceived;
		//Thuc hien upload file len o cung toi da 10 file
			$arrInput = $this->_request->getParams();	
			//var_dump($arrInput);
			$sXmlTagValueList = $this->_request->getParam('hdn_XmlTagValueList','');
			//Tao xau XML luu CSDL
			if ($sXmlTagValueList != ""){
				$arrXmlTagValue = explode("|{*^*}|",$sXmlTagValueList);
				if($arrXmlTagValue[0] != "" && $arrXmlTagValue[1] != ""){
					//Danh sach THE
					$sXmlTagList = $arrXmlTagValue[0];
					//Danh sach GIA TRI
					$sXmlValueList = $arrXmlTagValue[1];
					//Tao xau XML luu CSDL					
					$sXmlStringInDb = $ojbXmlLib->_xmlGenerateXmlDataString($sXmlTagList, $sXmlValueList);					
				}
			}
			if($objFilter->filter($arrInput['C_SUBJECT'])!= ""){
				$sStatus = $arrReceived[0]['C_STATUS'];
				if ($sStatus == ''){
					$sStatus = 'CHO_PHAN_PHOI';	
				}
				//Mang luu tham so update in database	
				$arrParameter = array(	
									'PK_RECEIVED_DOC'				=>'',										
									'FK_UNIT'						=>$_SESSION['OWNER_ID'],
									'C_SYMBOL'						=>$objFilter->filter($arrInput['C_SYMBOL']),
									'C_RELEASE_DATE'				=>$ojbSysLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['C_RELEASE_DATE'])),
									'C_AGENTCY_GROUP'				=>$objFilter->filter($arrInput['C_AGENTCY_GROUP']),
									'C_AGENTCY_NAME'				=>$objFilter->filter($arrInput['C_AGENTCY_NAME']),
									'C_DOC_TYPE'					=>$objFilter->filter($arrInput['C_DOC_TYPE']),
									'C_DOC_CATE'					=>$objFilter->filter($arrInput['C_DOC_CATE']),
									'C_SUBJECT'						=>$objFilter->filter($arrInput['C_SUBJECT']),
									'C_TEXT_BOOK'					=>$objFilter->filter($arrInput['C_TEXT_BOOK']),
									'C_NUM'							=>$objFilter->filter($arrInput['C_NUM']),
									'C_RECEIVED_DATE'				=>$ojbSysLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['C_RECEIVED_DATE'])),
									'C_NATURE'						=>$objFilter->filter($arrInput['C_NATURE']),
									'C_TEXT_OF_EMERGENCY'			=>$objFilter->filter($arrInput['C_TEXT_OF_EMERGENCY']),
									'C_TYPE_PROCESSING'				=>$objFilter->filter($arrInput['C_TYPE_PROCESSING']),
									'C_STATUS'						=>$sStatus,	
									'C_XML_DATA'					=>$sXmlStringInDb,
									'ATTACH_FILE_NAME_LIST'			=>$arrFileNameUpload
							);
								
				$Result = "";
				if($objFilter->filter($arrInput['C_SUBJECT']) != ''){				
					$Result = $objSendReceive->DocReceivedUpdate($arrParameter);												
					$arrParaSet = array("hdn_current_page"=>$iCurrentPage,"hdn_record_number_page"=>$iNumRowOnPage,"FullTextSearch"=>$sFullTextSearch, "fromDate"=>$dFromDate, "toDate"=>$dToDate);
					//var_dump($arrParaSet); exit;
					$_SESSION['seArrParameter'] = $arrParaSet;
					$this->_request->setParams($arrParaSet);	
					
					//Lay ID cua NSD dang nhap hien thoi
					$iCurrentStaffId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
					//Lay TEN cua NSD dang nhap hien thoi
					$sStaffPositionName = str_replace('!#~$|*',',',$objDocFun->getNamePositionStaffByIdList($iCurrentStaffId));
					//Lay ID phong ban cua NSD dang nhap hien thoi
					$iCurrentUnitId = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');	
					//UPdate trang thai da xem
					$Result = $objSendReceive->DocSendReceivedUpdateReceivedInfo($sReceiveDocumentId,$iCurrentStaffId,$_SESSION['OWNER_ID'],$sStaffPositionName,$iCurrentUnitId);
	
					//Thuc hien URL
					$this->_redirect('sendReceived/receivedDocument/index/?htn_leftModule=RECEIVED-DOCUMENT');
				}
			
		}		
	}
}?>
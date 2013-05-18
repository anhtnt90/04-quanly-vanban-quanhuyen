<?php
/**
 * Nguoi tao: phongtd
 * Ngay tao: 08/06/2010
 * Y nghia: Class Xu ly VB den
 */	
class received_documentsController extends  Zend_Controller_Action {
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
		//Lay cac gia tri const
		$ojbSysInitConfig = new Sys_Init_Config();
		$this->view->arrConst =	$ojbSysInitConfig->_setProjectPublicConst();
		//Lay so dong tren man hinh danh sach
		$this->view->NumberRowOnPage 	= $this->_ConstPublic['NumberRowOnPage'];		
		
		//Ky tu dac biet phan tach giua cac phan tu
		$this->view->delimitor 			= $this->_ConstPublic['delimitor'];
		
		//Lay duong dan thu muc goc (path directory root)
		$this->view->baseUrl = $this->_request->getBaseUrl() . "/public/";	
				//Goi lop Listxml_modList
		Zend_Loader::loadClass('dashboard_modWebMenu');
		Zend_Loader::loadClass('authorized_modauthorized');
		Zend_Loader::loadClass('Zend_Feed');
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
		//Goi lop modReceived
		Zend_Loader::loadClass('received_modReceived');
		
		
		$this->view->JSPublicConst = $objConfig->_setJavaScriptPublicVariable();		
		
		// Load tat ca cac file Js va Css
		$JSandStyle = Sys_Publib_Library::_getAllFileJavaScriptCss('','js','received.js,util.js,js_calendar.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','ajax.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','jquery-1.4.2.min.js,jQuery.equalHeights.js',',','js'). Sys_Publib_Library::_getAllFileJavaScriptCss('','js/LibSearch','actb_search.js,common_search.js',',','js');						
		//Thuc hien lay CSS va JS cho DatetimePicker					
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
		$this->view->currentModulCode = "RECEIVED";
		$this->view->currentModulCodeForLeft = "DOCUMENT-RECEIVED-DOC";
		//Lay Quyen cap nhat VB DEN
		//$this->_publicPermission = Sys_Function_DocFunctions::Doc_ArchivesStaff($_SESSION['arrStaffPermission'],$_SESSION['staff_id']);
		//echo $this->_publicPermission;
	
			$response->insert('header', $this->view->renderLayout('twd_header.phtml','template/'));    
			$response->insert('left', $this->view->renderLayout('twd_left.phtml','template/'));  	    
	        $response->insert('footer', $this->view->renderLayout('twd_footer.phtml','template/'));
  	}	
	/**
	 * Idea : Phuong thuc hien thi man hinh danh sach
	 *
	 */
	public function indexAction(){	
		//Lay URL 
		$sUrl = $_SERVER['REQUEST_URI'];
		$this->view->sUrl = $sUrl;			
		// Tieu de man hinh danh sach
		$this->view->bodyTitle = "DANH SÁCH VĂN BẢN ĐẾN";
		
		// Tao doi tuong Zend_Filter
		$objFilter = new Zend_Filter();
		$ojbSysLib = new Sys_Library();
		$objDocFun = new Sys_Function_DocFunctions();
		// Lay toan bo tham so truyen tu form			
		$arrInput = $this->_request->getParams();		
		
		$objReceive = new received_modReceived();
		$dReceivedDate = date("d/m/Y");
		$this->view->ReceivedDate = $dReceivedDate;
		
		$iCurrentPage = $this->_request->getParam('hdn_current_page','');		
		if ($iCurrentPage <= 1){
			$iCurrentPage = 1;
		}
		//Lay thong tin quy dinh so row / page
		$iNumRowOnPage = 15;
		$iNumRowOnPage = $objFilter->filter($arrInput['hdn_record_number_page']);		
		if ($iNumRowOnPage <= $this->view->NumberRowOnPage){
			$iNumRowOnPage = $this->view->NumberRowOnPage;
		}		
		//Tieu chi tim kiem
		$sFullTextSearch = $this->_request->getParam('FullTextSearch','');
		$dFromDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($this->_request->getParam('fromDate',''));
		$dToDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($this->_request->getParam('toDate',''));
		//Lay gia tri trong session
		if (isset($_SESSION['seArrParameter'])){
			$arrParaInSession = $_SESSION['seArrParameter'];
			//Tieu chi tim kiem
			$sFullTextSearch = $arrParaInSession['FullTextSearch'];
			$dFromDate = $arrParaInSession['fromDate'];
			$dToDate = $arrParaInSession['toDate'];
			//Trang hien thoi
			$iCurrentPage = $arrParaInSession['hdn_current_page'];
			//So record/page
			$iNumRowOnPage = $arrParaInSession['hdn_record_number_page'];	
			//Xoa gia tri trong session
			unset($_SESSION['seArrParameter']);								
		}
		if($iCurrentPage == 0 || $iCurrentPage =="" ||$iCurrentPage == null){
			$iCurrentPage =1;
		}
		if($iNumRowOnPage == 0 || $iNumRowOnPage =="" ||$iNumRowOnPage == null){
			$iNumRowOnPage =15;
		}
		$this->view->currentPage = $iCurrentPage; //Gan gia tri vao View
		$this->view->numRowOnPage = $iNumRowOnPage; //Gan gia tri vao View
		
		//Mac dinh tu ngay (Tu dau tuan) den ngay (den ngay hien tai)
		/*
		if($dFromDate == '' and $dToDate == '' and $sFullTextSearch == ''){
			$dFromDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($objDocFun->getFirstDayOfWeek() );
			$dToDate = date("Y/m/d");
		}	
		*/
		//Thuc hien lay du lieu	
		$sFullTextSearch = $ojbSysLib->_replaceBadChar($sFullTextSearch);
		$arrResul = $objReceive->DocReceivedGetAll($_SESSION['OWNER_ID'], $dFromDate, $dToDate, trim($sFullTextSearch), $iCurrentPage, $iNumRowOnPage);
		$iNumberRecord = $arrResul[0]['C_TOTAL_RECORD'];
		$sdocpertotal ="Không có văn bản nào";
		//Phan trang
		if (count($arrResul) > 0){
			$this->view->sdocpertotal = "Tổng có ".sizeof($arrResul).'/'.$iNumberRecord." văn bản";
			//Sinh xau HTML mo ta so trang (Trang 1; Trang 2;...)
			$this->view->generateStringNumberPage = Sys_Publib_Library::_generateStringNumberPage($iNumberRecord, $iCurrentPage, $iNumRowOnPage,$sUrl) ;
			//Sinh chuoi HTML mo ta tong so trang (Trang 1; Trang 2;...) va quy dinh so record/page
			$this->view->generateHtmlSelectBoxPage = Sys_Publib_Library::_generateChangeRecordNumberPage($iNumRowOnPage,$sUrl);
		}

		//var_dump($arrResul);
		$this->view->arrResul = $arrResul;
		//
		$this->view->NumberRowOnPage 	= $this->_ConstPublic['NumberRowOnPage'];	
		$this->view->iCountElement = count($this->view->arrAllRecord);
		
		//Luu cac gia tri tim kiem duoc nhap vao tu form 
        $this->view->fromDate = $ojbSysLib->_yyyymmddToDDmmyyyy($dFromDate);
        $this->view->toDate =  $ojbSysLib->_yyyymmddToDDmmyyyy($dToDate);
        $this->view->FullTextSearch = $sFullTextSearch;
	}
	/**
	 * Idea : Phuong thuc them moi mot VB
	 *
	 */
	public function addAction(){
		$this->view->bodyTitle = 'VÀO SỔ VĂN BẢN ĐẾN';
		$arrInput = $this->_request->getParams();
		$objDocFun = new Sys_Function_DocFunctions();
		$objReceive = new received_modReceived();
		$ojbXmlLib = new Sys_Publib_Xml();
		$ojbSysLib = new Sys_Library();
		$objFilter = new Zend_Filter();
		$ojbSysInitConfig = new Sys_Init_Config();	
		 //Lay thong tin history back
		$this->view->historyBack = $this->_request->getParam('hdn_history_back','');
		//Lay tham so cau hinh
		$sysLibUrlPath = $ojbSysInitConfig->_setLibUrlPath();
		$url_path_calendar = $sysLibUrlPath . 'sys-calendar/';
		$this->view->urlCalendar = $url_path_calendar;
		
		$arrSel = $objReceive->getPropertiesDocument('DM_TINH_CHAT_VB','','');
		$this->view->arrSel = $arrSel;
		
		$arrUrgent = $objReceive->getPropertiesDocument('DM_DO_KHAN_VAN_BAN','','');
		$this->view->arrUrgent = $arrUrgent;
		
		$arrInputBooks = $objReceive->getPropertiesDocument('DM_SO_VAN_BAN_DEN','','');
		$this->view->arrInputBooks = $arrInputBooks;
		
		$arrAgentcyGroup = $objReceive->getPropertiesDocument('DM_CAP_NOI_GUI_VAN_BAN','','');
		$this->view->arrAgentcyGroup = $arrAgentcyGroup;
		
		$arrAgentcyName = $objReceive->getPropertiesDocument('DM_NOI_GUI_VAN_BAN','','');
		$this->view->arrAgentcyName = $arrAgentcyName;
		
		//Lay danh sach cac linh vuc
		$arrDocCate = $objReceive->getPropertiesDocument('DM_LINH_VUC_VAN_BAN','','');
		
		$arrDocType = $objReceive->getPropertiesDocument('DM_LOAI_VAN_BAN','','');
		$this->view->arrDocType = $arrDocType;
		
		$arrSigner = $objReceive->getPropertiesDocument('DM_NGUOI_KY','','');
		$this->view->arrSigner = $arrSigner;
		
		$arrProcessType = $objReceive->getPropertiesDocument('DM_HINH_THUC_XU_LY','','');
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
		
		//Tuy chon ung voi cac truong hop update du lieu	
		$sOption = $this->_request->getParam('hdh_option','');
		$this->view->option = $sOption;
			
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
		$this->view->AttachFile = $objDocFun->DocSentAttachFile(array(),0,10,true,25);
		
		if ($objFilter->filter($arrInput['C_SUBJECT']) != ""){			
			$sStatus = 'CHO_PHAN_PHOI';	
			//Thuc hien upload file len o cung toi da 10 file
			$arrFileNameUpload = $ojbSysLib->_uploadFileList(10,$this->_request->getBaseUrl() . "/public/attach-file/",'FileName','!#~$|*');
			//var_dump($arrFileNameUpload); exit;
			//Mang luu tham so update in database	
			$arrParameter = array(	
								'PK_RECEIVED_DOC'				=>'',										
								'FK_UNIT'						=>$_SESSION['OWNER_ID'],
								'C_SYMBOL'						=>$ojbSysLib->_replaceBadChar($objFilter->filter($arrInput['C_SYMBOL'])),
								'C_RELEASE_DATE'				=>$ojbSysLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['C_RELEASE_DATE'])),
								'C_AGENTCY_GROUP'				=>$objFilter->filter($arrInput['C_AGENTCY_GROUP']),
								'C_AGENTCY_NAME'				=>$objFilter->filter($arrInput['C_AGENTCY_NAME']),
								'C_DOC_TYPE'					=>$objFilter->filter($arrInput['C_DOC_TYPE']),
								'C_DOC_CATE'					=>$objFilter->filter($arrInput['C_DOC_CATE']),
								'C_SUBJECT'						=>$ojbSysLib->_replaceBadChar($objFilter->filter($arrInput['C_SUBJECT'])),
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
			//var_dump($arrParameter);exit;				
			$Result = $objReceive->DocReceivedUpdate($arrParameter);				
			//Luu gia tri												
			$arrParaSet = array("hdn_current_page"=>$iCurrentPage,"hdn_record_number_page"=>$iNumRowOnPage,"FullTextSearch"=>$sFullTextSearch, "fromDate"=>$dFromDate, "toDate"=>$dToDate);
			//var_dump($arrParaSet); exit;
			$_SESSION['seArrParameter'] = $arrParaSet;
			$this->_request->setParams($arrParaSet);

			//Truong hop ghi va them moi
			if ($sOption == "GHI_THEMMOI"){
				//Ghi va quay lai chinh form voi noi dung rong						
				$this->_redirect('received/documents/add/');
			}	
			
			//Truong hop ghi va them tiep
			if ($sOption == "GHI_THEMTIEP"){
				$this->view->sReceiveDocumentId = $Result;
				$this->view->option = $sOption; 
				//Them van ban moi va giu lai noi dung thong tin tren form						
				//$this->_redirect('received/documents/edit/hdn_object_id/' .$Result.'/hdh_option/GHI_THEMTIEP');
				echo "<script type='text/javascript'> actionUrl('../edit/');</script>";
			}
			//Truong hop ghi tam
			if ($sOption == "GHI_TAM"){
				$this->view->sReceiveDocumentId = $Result;
				$this->view->option = $sOption;
				//Them van ban moi va giu lai noi dung thong tin tren form						
				$this->_redirect('received/documents/edit/hdn_object_id/' .$Result);
				//echo "<script type='text/javascript'> actionUrl('../edit/');</script>";
			}

			//Truong hop ghi va quay lai
			if ($sOption == "GHI_QUAYLAI"){
				//Tro ve trang index						
				$this->_redirect('received/documents/index/');
				//echo "<script type='text/javascript'> actionUrl('../edit/');</script>";	
			}	
				
		}
	}
	/**
	 * Idea : Phuong thuc hieu chinh mot VB
	 *
	 */
	public function editAction(){		
		$this->view->bodyTitle = 'VÀO SỔ VĂN BẢN ĐẾN';
		$arrInput = $this->_request->getParams();
		$objDocFun = new Sys_Function_DocFunctions();
		$objReceive = new received_modReceived();
		$ojbXmlLib = new Sys_Publib_Xml();
		$ojbSysLib = new Sys_Library();
		$objFilter = new Zend_Filter();
		$ojbSysInitConfig = new Sys_Init_Config();	
		 //Lay thong tin history back
		$this->view->historyBack = $this->_request->getParam('hdn_history_back','');
		//Lay tham so cau hinh
		$sysLibUrlPath = $ojbSysInitConfig->_setLibUrlPath();
		$url_path_calendar = $sysLibUrlPath . 'sys-calendar/';
		$this->view->urlCalendar = $url_path_calendar;
		$arrSel = $objReceive->getPropertiesDocument('DM_TINH_CHAT_VB','','');
		$this->view->arrSel = $arrSel;
		
		$arrUrgent = $objReceive->getPropertiesDocument('DM_DO_KHAN_VAN_BAN','','');
		$this->view->arrUrgent = $arrUrgent;
		
		$arrInputBooks = $objReceive->getPropertiesDocument('DM_SO_VAN_BAN_DEN','','');
		$this->view->arrInputBooks = $arrInputBooks;
		
		$arrAgentcyGroup = $objReceive->getPropertiesDocument('DM_CAP_NOI_GUI_VAN_BAN','','');
		$this->view->arrAgentcyGroup = $arrAgentcyGroup;
		
		$arrAgentcyName = $objReceive->getPropertiesDocument('DM_NOI_GUI_VAN_BAN','','');
		$this->view->arrAgentcyName = $arrAgentcyName;
		
		$arrDocType = $objReceive->getPropertiesDocument('DM_LOAI_VAN_BAN','','');
		$this->view->arrDocType = $arrDocType;
		
		$arrSigner = $objReceive->getPropertiesDocument('DM_NGUOI_KY','','');
		$this->view->arrSigner = $arrSigner;
		//Lay danh sach cac linh vuc
		$arrDocCate = $objReceive->getPropertiesDocument('DM_LINH_VUC_VAN_BAN','','');
		
		$arrProcessType = $objReceive->getPropertiesDocument('DM_HINH_THUC_XU_LY','','');
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
		//Tuy chon ung voi cac truong hop update du lieu	
		$sOption = $this->_request->getParam('hdh_option','');
		$this->view->option = $sOption;
		if ($sOption == "QUAY_LAI"){
			$this->_redirect('received/documents/index/');
		}
		//Lay thong tin file dinh kem
		$arrFileNameUpload = $ojbSysLib->_uploadFileList(10,$this->_request->getBaseUrl() . "/public/attach-file/",'FileName','!#~$|*');
		
		$sReceiveDocumentIdTemp = $sReceiveDocumentId;
		if($sOption == "GHI_THEMTIEP"){
			$sReceiveDocumentIdTemp = "";
		}	
		//Lay file da dinh kem tu truoc
		if($sOption != "GHI_TAM"){
			$arFileAttach = $objReceive->DOC_GetAllDocumentFileAttach($sReceiveDocumentIdTemp,'','T_DOC_RECEIVED_DOCUMENT');	
			$this->view->AttachFile = $objDocFun->DocSentAttachFile($arFileAttach,sizeof($arFileAttach),10,true,25);	
		}
		//Mang luu thong tin van ban
		$Result = '';
		$arrReceived = $objReceive->DocReceivedGetSingle($sReceiveDocumentId,$_SESSION['OWNER_ID']);
		$this->view->arrReceived = $arrReceived;
		if($sReceiveDocumentId != '' && $sReceiveDocumentId != null && $sOption != "QUAY_LAI"){
			//Neu la ghi va them tiep thi gan ID VB lay duoc = "" de them moi mot VB
			$sCheckAdd = $this->_request->getParam('hdh_checkadd','');
			if($sCheckAdd=='add'){
				$sReceiveDocumentId = "";
			}
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
									'PK_RECEIVED_DOC'				=>$sReceiveDocumentId,										
									'FK_UNIT'						=>$_SESSION['OWNER_ID'],
									'C_SYMBOL'						=>$ojbSysLib->_replaceBadChar($objFilter->filter($arrInput['C_SYMBOL'])),
									'C_RELEASE_DATE'				=>$ojbSysLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['C_RELEASE_DATE'])),
									'C_AGENTCY_GROUP'				=>$objFilter->filter($arrInput['C_AGENTCY_GROUP']),
									'C_AGENTCY_NAME'				=>$objFilter->filter($arrInput['C_AGENTCY_NAME']),
									'C_DOC_TYPE'					=>$objFilter->filter($arrInput['C_DOC_TYPE']),
									'C_DOC_CATE'					=>$objFilter->filter($arrInput['C_DOC_CATE']),
									'C_SUBJECT'						=>$ojbSysLib->_replaceBadChar($objFilter->filter($arrInput['C_SUBJECT'])),
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
					$Result = $objReceive->DocReceivedUpdate($arrParameter);	
					//var_dump($arrResult);	exit;				
					//Luu gia tri												
					$arrParaSet = array("hdn_current_page"=>$iCurrentPage,"hdn_record_number_page"=>$iNumRowOnPage,"FullTextSearch"=>$sFullTextSearch, "fromDate"=>$dFromDate, "toDate"=>$dToDate);
					//var_dump($arrParaSet); exit;
					$_SESSION['seArrParameter'] = $arrParaSet;
					$this->_request->setParams($arrParaSet);	
				}
			}
		}		
		//Truong hop ghi va them moi
		if ($sOption == "GHI_THEMMOI"){
			//Ghi va quay lai chinh form voi noi dung rong						
			$this->_redirect('received/documents/add/');
		}	
		
		//Truong hop ghi va them tiep
		if ($sOption == "GHI_THEMTIEP"){
			$this->view->option = $sOption;
			$this->view->sCheckAdd = 'add';
			//Lay ID VB vua moi insert vao DB
			if($Result == ''){
				$Result = $this->_request->getParam('hdn_object_id','');
			}
			$this->view->sReceiveDocumentId = $Result;
			//Lay thong tin van ban vua them moi va hien thi ra view
			$arrReceived = $objReceive->DocReceivedGetSingle($Result,$_SESSION['OWNER_ID']);
			$this->view->arrReceived = $arrReceived;
		}
		
		//Truong hop ghi nhan
		if ($sOption == "GHI_TAM"){
			//Lay ID VB vua moi insert vao DB
			$this->view->sReceiveDocumentId = $Result;
			$this->view->option = $sOption;
			//Lay thong tin van ban vua them moi va hien thi ra view
			$arrReceived = $objReceive->DocReceivedGetSingle($Result);
			$this->view->arrReceived = $arrReceived;
			//Lay ra File dinh kem da cap nhat vao truoc do
			$arFileAttach = $objReceive->DOC_GetAllDocumentFileAttach($Result,'','T_DOC_RECEIVED_DOCUMENT');	
			$this->view->AttachFile = $objDocFun->DocSentAttachFile($arFileAttach,sizeof($arFileAttach),10,true,25);
		}

		//Truong hop ghi va quay lai
		if ($sOption == "GHI_QUAYLAI"){
			//Tro ve trang index						
			$this->_redirect('received/documents/index/');
			//echo "<script type='text/javascript'> actionUrl('../edit/');</script>";	
		}	
	}
	/**
	 * Idea : Phuong thuc xoa mot VB
	 *
	 */
	public function deleteAction(){
		// Tao doi tuong cho lop xu ly du lieu lien quan modul	
		$objReceive = new received_modReceived();
		$ojbSysLib = new Sys_Library();
		// Thuc hien lay du lieu tu form 		
		if($this->_request->isPost()){	
			// Lay toan bo tham so truyen tu form			
			$arrInput = $this->_request->getParams();				
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
			//Lay Id doi tuong VB can xoa
			$sReceiveDocumentIdList = $this->_request->getParam('hdn_object_id_list',"");	
			if ($sReceiveDocumentIdList != ""){
				$sRetError = $objReceive->DocReceivedDelete($sReceiveDocumentIdList,1);
				// Neu co loi			
				if($sRetError != null || $sRetError != '' ){											
					echo "<script type='text/javascript'>";
					echo "alert('$sRetError');\n";				
					echo "</script>";
				}else {		
					//Luu gia tri												
					$arrParaSet = array("hdn_current_page"=>$iCurrentPage,"hdn_record_number_page"=>$iNumRowOnPage,"FullTextSearch"=>$sFullTextSearch, "fromDate"=>$dFromDate, "toDate"=>$dToDate);
					$_SESSION['seArrParameter'] = $arrParaSet;
					$this->_request->setParams($arrParaSet);
					//Tro ve trang index												
					$this->_redirect('received/documents/index/');				
				}
			}
		}	
	
	}
	/**
	 * Idea : Phuong thuc lay thong tin chi tiet VB
	 *
	 */
	function viewAction(){
		$this->view->bodyTitle = 'VĂN BẢN ĐẾN';
		$ojbSysLib = new Sys_Library();
		$objDocFun = new Sys_Function_DocFunctions();
		// Tao doi tuong cho lop xu ly du lieu lien quan modul	
		$objReceive = new received_modReceived();
		$ojbSysInitConfig = new Sys_Init_Config();	
		//Lay id van ban tu view
		$sReceiveDocumentId = $this->_request->getParam('hdn_object_id','');		
		$this->view->sReceiveDocumentId = $sReceiveDocumentId;
		//Mang luu thong tin chi tiet cua mot van ban
		$arrReceived = $objReceive->DocReceivedGetSingle($sReceiveDocumentId,$_SESSION['OWNER_ID']);
		$this->view->arrReceived = $arrReceived;
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
	}
	/**
	 * Idea : Phuong thuc In thong tin chi tiet VB
	 *
	 */
	public function printdocAction(){
		//Tao doi tuong ham dung chung
		$objDocFun = new Sys_Function_DocFunctions();	
		$ojbSysInitConfig = new Sys_Init_Config();
		//Tao doi tuong Sys_lib
		$ojbSysLib = new Sys_Library();	
		// Tao doi tuong Zend_Filter
		$filter = new Zend_Filter();		
		// Tao doi tuong cho lop xu ly du lieu lien quan modul	
		$objReceive = new received_modReceived();	
		//Lay id van ban tu view
		$sReceiveDocumentId = $this->_request->getParam('hdn_object_id','');		
		$this->view->sReceiveDocumentId = $sReceiveDocumentId;
		//Mang luu thong tin chi tiet cua mot van ban
		$arrReceived = $objReceive->DocReceivedGetSingle($sReceiveDocumentId,$_SESSION['OWNER_ID']);
		$this->view->arrReceived = $arrReceived;
		//Lay file dinh kem
		$strFileName 				= $arrReceived[0]['C_FILE_NAME'];
		$sFile 						= Sys_Library::_getAllFileAttach($strFileName,"!#~$|*","!~!",$this->_request->getBaseUrl() . "/public/attach-file/");
		//Lay duong dan
		$path = $_SERVER['SCRIPT_FILENAME'];
		$path = substr($path, 0, -9);
		$my_report = str_replace("/", "\\", $path) . "rpt\\received\\received.rpt";
		//echo $my_report;exit;
		// Tao doi tuong Crystal 9
		$COM_Object = "CrystalDesignRunTime.Application.9";		
		$crapp= new COM($COM_Object) or die("Unable to Create Object");
		$creport = $crapp->OpenReport($my_report, 1);	
		//$creport->DiscardSavedData;		
		$creport->ReadRecords();
		// Truyen tham so vao
		$creport->ParameterFields(1)->SetCurrentValue($arrReceived[0]['C_SYMBOL']);
		$creport->ParameterFields(2)->SetCurrentValue($arrReceived[0]['C_RELEASE_DATE']);
		$creport->ParameterFields(3)->SetCurrentValue($arrReceived[0]['C_AGENTCY_GROUP']);
		$creport->ParameterFields(4)->SetCurrentValue($arrReceived[0]['C_AGENTCY_NAME']);
		$creport->ParameterFields(5)->SetCurrentValue($arrReceived[0]['C_DOC_TYPE']);
		$creport->ParameterFields(6)->SetCurrentValue($arrReceived[0]['C_SUBJECT']);
		$creport->ParameterFields(7)->SetCurrentValue($arrReceived[0]['C_TEXT_BOOK_NAME']);
		$creport->ParameterFields(8)->SetCurrentValue($arrReceived[0]['C_NUM']);
		$creport->ParameterFields(9)->SetCurrentValue($arrReceived[0]['C_RECEIVED_DATE']);
		$creport->ParameterFields(10)->SetCurrentValue($arrReceived[0]['C_NATURE_NAME']);
		$creport->ParameterFields(11)->SetCurrentValue($arrReceived[0]['C_TEXT_OF_EMERGENCY_NAME']);
		$creport->ParameterFields(12)->SetCurrentValue($arrReceived[0]['C_TYPE_PROCESSING_NAME']);
		$creport->ParameterFields(13)->SetCurrentValue((string)$sFile); 	
		//Ten file
		$report_file = 'received.doc';
		// Duong dan file report
		$my_report_file = str_replace("/", "\\", $path) . "public\\" . $report_file;
		$this->view->my_report_file = $my_report_file;
		//export to PDF process
		$creport->ExportOptions->DiskFileName=$my_report_file; //export to file 
		$creport->ExportOptions->PDFExportAllPages=true;
		$creport->ExportOptions->DestinationType = 1; // export to file
		$creport->ExportOptions->FormatType= 14;
		$creport->Export(false);
		$my_report_file = 'http://'.$_SERVER['HTTP_HOST'].$this->_request->getBaseUrl() .'/public/' . $report_file;
		$this->view->my_report_file = $my_report_file;
		
	}
	/**
	 * Idea : Phuong thuc In phieu xu ly VB
	 *
	 */
	public function printtransferredAction(){
		//Tao doi tuong ham dung chung
		$objDocFun = new Sys_Function_DocFunctions();	
		$ojbSysInitConfig = new Sys_Init_Config();
		//Tao doi tuong Sys_lib
		$ojbSysLib = new Sys_Library();	
		// Tao doi tuong Zend_Filter
		$filter = new Zend_Filter();		
		// Tao doi tuong cho lop xu ly du lieu lien quan modul	
		$objReceive = new received_modReceived();	
		//Lay id van ban tu view
		$sReceiveDocumentId = $this->_request->getParam('hdn_object_id','');		
		$this->view->sReceiveDocumentId = $sReceiveDocumentId;
		//Mang luu thong tin chi tiet cua mot van ban
		$arrReceived = $objReceive->DocReceivedGetSingle($sReceiveDocumentId,$_SESSION['OWNER_ID']);
		$this->view->arrReceived = $arrReceived;
		//Lay duong dan
		$path = $_SERVER['SCRIPT_FILENAME'];
		$path = substr($path, 0, -9);
		$my_report = str_replace("/", "\\", $path) . "rpt\\received\\phieuxuly.rpt";
		// Tao doi tuong Crystal 9
		$COM_Object = "CrystalDesignRunTime.Application.9";		
		$crapp= new COM($COM_Object) or die("Unable to Create Object");
		$creport = $crapp->OpenReport($my_report, 1);	
		$creport->DiscardSavedData;		
		//$creport->ReadRecords();
		// Truyen tham so vao
		//var_dump($arrReceived);exit;
		$creport->ParameterFields(1)->SetCurrentValue((int)$arrReceived[0]['C_NUM']);
		$creport->ParameterFields(2)->SetCurrentValue($arrReceived[0]['C_TEXT_BOOK_NAME']);
		$creport->ParameterFields(3)->SetCurrentValue($arrReceived[0]['C_RECEIVED_DATE']);
		$creport->ParameterFields(4)->SetCurrentValue($arrReceived[0]['C_AGENTCY_NAME']);
		$creport->ParameterFields(5)->SetCurrentValue($arrReceived[0]['C_SUBJECT']); 	
		//Ten file
		$report_file = 'phieuxuly.doc';
		// Duong dan file report
		$my_report_file = str_replace("/", "\\", $path) . "public\\" . $report_file;
		$this->view->my_report_file = $my_report_file;
		//export to PDF process
		$creport->ExportOptions->DiskFileName=$my_report_file; //export to file 
		$creport->ExportOptions->PDFExportAllPages=true;
		$creport->ExportOptions->DestinationType = 1; // export to file
		$creport->ExportOptions->FormatType= 14;
		$creport->Export(false);
		$my_report_file = 'http://'.$_SERVER['HTTP_HOST'].$this->_request->getBaseUrl() .'/public/' . $report_file;
		$this->view->my_report_file = $my_report_file;
		//echo $my_report_file;exit;
	}
	/**
	 * Idea : Phuong thuc hien cap nhat thong tin PHAN PHOI,PHAN CONG XU LY
	 *
	 */
	public function distributionassignAction(){
		$this->view->bodyTitle = 'CẬP NHẬT THÔNG TIN PHÂN PHỐI, PHÂN CÔNG XỬ LÝ';
		$arrInput = $this->_request->getParams();
		$objDocFun = new Sys_Function_DocFunctions();
		$objReceive = new received_modReceived();
		$ojbXmlLib = new Sys_Publib_Xml();
		$ojbSysLib = new Sys_Library();
		$objFilter = new Zend_Filter();
		$ojbSysInitConfig = new Sys_Init_Config();	
		
		 //Lay thong tin history back
		$this->view->historyBack = '../index/';	
		//Lay tham so cau hinh
		$sysLibUrlPath = $ojbSysInitConfig->_setLibUrlPath();
		$url_path_calendar = $sysLibUrlPath . 'sys-calendar/';
		$this->view->urlCalendar = $url_path_calendar;
		//
		//if($_SESSION['OWNER_ID'] != Sys_Init_Config::_setParentOwnerId())
		//		$arrLeader = $objDocFun->docGetAllUnitLeader('LANH_DAO_SO,LANH_DAO_UB_QUAN_HUYEN','arr_all_staff');
		//else 	
		$arrPositionConst =	$ojbSysInitConfig->_setLeaderPostionGroup();
		$arrLeader = $objDocFun->docGetAllUnitLeader($arrPositionConst['_CONST_POSITION_GROUP'],'arr_all_staff');
		
		$objAuthoried = new authorized_modauthorized();
		$arrAuthori = $objAuthoried->Authorized_getAll();
		$this->view->arrAuthori = $arrAuthori;
				
		$this->view->arrLeader = $arrLeader;
		//var_dump($arrLeader);
		
		// Goi ham search lay ra toan bo thong tin can bo nhan
		$this->view->search_textselectbox_staff = Sys_Function_DocFunctions::doc_search_ajax($_SESSION['arr_all_staff'],"id","name","C_STAFF_ID_LIST","hdn_staff_id_list",0,"position_code");
		// Goi ham search lay ra toan bo thong don vi, phong ban nhan
		$this->view->search_textselectbox_unit = Sys_Function_DocFunctions::doc_search_ajax($_SESSION['arr_all_unit'],"id","name","C_UNIT_ID_LIST","hdn_unit_id_list",0);
		//Lay id cua van ban
		$sReceiveDocumentId = $this->_request->getParam('hdn_object_id','');		
		$this->view->sReceiveDocumentId = $sReceiveDocumentId;
		$arrReceived = $objReceive->DocReceivedGetSingle($sReceiveDocumentId,$_SESSION['OWNER_ID']);
		//var_dump($arrReceived); //exit;
		$this->view->arrReceived = $arrReceived;
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
	    //    
		if($sReceiveDocumentId != '' && $sReceiveDocumentId != null && $this->_request->isPost()){
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
			//echo htmlspecialchars($sXmlStringInDb);
			
			//Danh sach LANH DAO DAO DON VI cho Y KIEN CHI DAO
			$sLeaderIdList = substr($objFilter->filter($arrInput['ds_lanh_dao']),0,-1);
			//Lay danh sach Y KIEN LANH DAO
			$sLeaderIdeaList = substr($objFilter->filter($arrInput['ds_y_kien']),0,-6);
			//Lay danh sach ten PHONG BAN XU LY
			$sUnitNameList = $objFilter->filter($arrInput['C_UNIT_ID_LIST']);
			//Lay danh sach Id PHONG BAN XU LY
			$sUnitIdList = $objDocFun->convertUnitNameListToUnitIdList($sUnitNameList);
			//Lay danh sach ten CAN BO XU LY 
			$sStaffNameList = $objFilter->filter($arrInput['C_STAFF_ID_LIST']);
			//Lay danh sach Id CAN BO XU LY
			$sStaffIdList = $objDocFun->convertStaffNameToStaffId($sStaffNameList);
			$sUnitByStaffIdList = $objDocFun->doc_get_all_unit_permission_form_staffIdList($sStaffIdList);
			$sProcessStatusUnitList = '';
			if($sUnitIdList != ''){
				$arrUnitIdList = explode(',',$sUnitIdList);
				$arrUnitByStaffIdList = explode(',',$sUnitByStaffIdList);
				for($i = 0; $i < sizeof($arrUnitIdList) - 1; $i++){
					if(in_array($arrUnitIdList[$i],$arrUnitByStaffIdList))
							$sProcessStatusUnitList .= 'CAN_XU_LY,';
					else 	$sProcessStatusUnitList .= 'CHO_PHAN_CONG,';
				}
				if(in_array($arrUnitIdList[$i],$arrUnitByStaffIdList))
						$sProcessStatusUnitList .= 'CAN_XU_LY';
				else 	$sProcessStatusUnitList .= 'CHO_PHAN_CONG';	
			}
			//var_dump($_SESSION['OWNER_ID']);
			if($_SESSION['OWNER_ID'] != Sys_Init_Config::_setParentOwnerId()){
				$sUnitIdList   = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');
				$sUnitNameList = $objDocFun->getNameUnitByIdUnitList($sUnitIdList,'');
				if($sStaffIdList != '')
						$sProcessStatusUnitList = 'CAN_XU_LY';
				else 	$sProcessStatusUnitList = 'CHO_PHAN_CONG';
			}
			//Mang luu tham so update in database	
			$arrParameter = array(	
								'FK_RECEIVED_DOC'				=>$sReceiveDocumentId,	
								'C_PROCESSION_DATE'				=>$ojbSysLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['C_PROCESSION_DATE'])),
								'C_LEADER_OFFICE_IDEA'			=>$ojbSysLib->_replaceBadChar($objFilter->filter($arrInput['C_LEADER_OFFICE_IDEA'])),
								'FK_LEADER_ID_LIST'				=>$sLeaderIdList,
								'C_LEADER_POSITION_NAME_LIST'	=>$objDocFun->getNamePositionStaffByIdList($sLeaderIdList),
								'C_IDEA_LIST'					=>$sLeaderIdeaList,
								'C_UNIT_ID_LIST'				=>$sUnitIdList,
								'C_UNIT_NAME_LIST'				=>str_replace(";","!#~$|*",$sUnitNameList),
								'C_STAFF_ID_LIST'				=>$sStaffIdList,
								'C_STAFF_POSITION_NAME_LIST'	=>str_replace(";","!#~$|*",$sStaffNameList),
								'C_UNIT_BY_STAFF_ID_LIST'		=>$sUnitByStaffIdList,
								'C_PROCESS_STATUS_UNIT_LIST'    =>$sProcessStatusUnitList,
								'C_APPOINTED_DATE'				=>$ojbSysLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['C_APPOINTED_DATE'])),
								'C_DELIMITOR'					=>'!#~$|*'
						);
							
			$arrResult = "";
			if($sLeaderIdList != ''){		
				$arrResult = $objReceive->DocDistributionAssign($arrParameter);				
				//Luu gia tri												
				$arrParaSet = array("hdn_current_page"=>$iCurrentPage,"hdn_record_number_page"=>$iNumRowOnPage,"FullTextSearch"=>$sFullTextSearch, "fromDate"=>$dFromDate, "toDate"=>$dToDate);
				$_SESSION['seArrParameter'] = $arrParaSet;
				$this->_request->setParams($arrParaSet);
				//Tro ve trang index												
				$this->_redirect('received/documents/index/');	
			}
		}
	}
	public function rssAction(){
		//Tao doi tuong ham dung chung
		$objDocFun = new Sys_Function_DocFunctions();	
		$ojbSysInitConfig = new Sys_Init_Config();
		//Tao doi tuong Sys_lib
		$ojbSysLib = new Sys_Library();	
		// Tao doi tuong Zend_Filter
		$filter = new Zend_Filter();		
		// Tao doi tuong cho lop xu ly du lieu lien quan modul	
		$objReceive = new received_modReceived();	
		//Lay id van ban tu view
		$sReceiveDocumentId = $this->_request->getParam('hdn_object_id','');		
		$this->view->sReceiveDocumentId = $sReceiveDocumentId;
		//Mang luu thong tin chi tiet cua mot van ban
		$arrReceived = $objReceive->DocReceivedGetSingle($sReceiveDocumentId,$_SESSION['OWNER_ID']);
		$strFileName = $arrReceived[0]['C_FILE_NAME'];
		$sFile  = Sys_Library::_getAllFileAttach($strFileName,"!#~$|*","!~!",$this->_request->getBaseUrl() . "/public/attach-file/");				
		$sFile2 = Sys_Library::_getAllFileAttachForXMLRSS($strFileName,"!#~$|*","!~!",$this->_request->getBaseUrl() . "/public/attach-file/");				
		$domainname = $ojbSysInitConfig->_setDomainNameUrl();
		//echo $domainname.$sFile2; exit;
		//var_dump($arrReceived); exit;
		$feedData = array(
            'title'=>'THÔNG TIN XUẤT BẢN XML',
            'description'=>'',
            'link'=>'',
            'charset'=>'utf8',
            'entries'=>array(
                array(
                    'title'=>'Trích yếu văn bản: '.$arrReceived[0]['C_SUBJECT'],
                    'file_actach'=>'File đánh kèm:'.$sFile,
                    'description'=>'Số ký hiệu: '.$arrReceived[0]['C_SYMBOL']. 'Ngày đến: '.$arrReceived[0]['C_RECEIVED_DATE'],                  
                    'link'=>$domainname.$sFile2                
                ),                
          	  )
        	);                                    
        //create our feed object and import the data
        $feed = Zend_Feed::importArray ( $feedData, 'rss' );         
        // set the Content Type of the document
        header ( 'Content-type: text/xml' );         
        // echo the contents of the RSS xml document
        echo $feed->send(); exit;
		
	}
	
	
}?>
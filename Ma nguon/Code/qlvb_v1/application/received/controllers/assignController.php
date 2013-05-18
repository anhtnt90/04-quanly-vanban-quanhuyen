<?php
/**
 * Nguoi tao: phongtd
 * Ngay tao: 06/07/2010
 * Y nghia: Class Xu ly PCXL VB den
 */	
class received_assignController extends  Zend_Controller_Action {
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
		//Zend_Loader::loadClass('Sys_Init_Config');
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
		//Modul chuc nang				
		$this->view->currentModulCodeForLeft = "ASSIGN-RECEIVED-DOC";			
		//Lay trang thai left menu
		$this->view->getStatusLeftMenu = $this->_request->getParam('status','');
		//echo 'status = '.$this->_request->getParam('status','');	
		//Lay Quyen PHAN CONG XU LY VB DEN
		//$this->_publicPermission = Sys_Function_DocFunctions::Doc_AssignDocument($_SESSION['arrStaffPermission'],$_SESSION['staff_id']);	
		//Gan quyen PCXL sang VIEW
		//$this->view->PermissionAssigner = $this->_publicPermission;
		
		//Mang quyen cua NSD hien thoi
		$arrPermission = $_SESSION['arrStaffPermission'];
				
		$response->insert('header', $this->view->renderLayout('twd_header.phtml','template/'));    
		$response->insert('left', $this->view->renderLayout('twd_left.phtml','template/'));  	    
        $response->insert('footer', $this->view->renderLayout('twd_footer.phtml','template/'));
  	}	
	/**
	 * Idea : Phuong thuc hien thi danh sach
	 *
	 */
	public function indexAction(){
		//Lay URL	
		$sUrl = $_SERVER['REQUEST_URI'];
		//Lay trang thai VB
		$sStatus = $this->_request->getParam('status','');
		$this->view->sStatus = $sStatus;	
		$sLabelAssign = "CHỜ PHÂN CÔNG";
		if ($sStatus == "DA_PHAN_CONG"){
				$sLabelAssign = "ĐÃ PHÂN CÔNG";
			}
		// Tieu de man hinh danh sach
		$this->view->bodyTitle = "DANH SÁCH VĂN BẢN ĐẾN ". $sLabelAssign; 
		// Tao doi tuong Zend_Filter
		$objFilter = new Zend_Filter();
		$ojbSysLib = new Sys_Library();
		$objDocFun = new Sys_Function_DocFunctions();
		//Lay cac gia tri const
		$ojbSysInitConfig = new Sys_Init_Config();
		$arrPositionConst =	$ojbSysInitConfig->_setLeaderPostionGroup();
		//var_dump($arrPositionConst);
		// Lay toan bo tham so truyen tu form			
		$arrInput = $this->_request->getParams();		
		$objReceive = new received_modReceived();
		
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
		//Lay gia tri trong session
		if (isset($_SESSION['seArrParameter'])){
			$arrParaInSession = $_SESSION['seArrParameter'];
			//Tieu chi tim kiem
			$sFullTextSearch = $arrParaInSession['FullTextSearch'];
			//Trang hien thoi
			$piCurrentPage = $arrParaInSession['hdn_current_page'];
			//So record/page
			$piNumRowOnPage = $arrParaInSession['hdn_record_number_page'];	
			//Xoa gia tri trong session
			unset($_SESSION['seArrParameter']);								
		}
		$this->view->currentPage = $iCurrentPage; //Gan gia tri vao View
		$this->view->numRowOnPage = $iNumRowOnPage; //Gan gia tri vao View	
		//Mang quyen cua NSD hien thoi
		$arrPermission = $_SESSION['arrStaffPermission'];
		$sFullTextSearch = $ojbSysLib->_replaceBadChar($sFullTextSearch);
		//Phan cong xu ly VB Cap DON VI	
		if ($arrPermission['CAP_NHAT_PCXL_VB_DV']){
			//Id lanh dao phan cong 	
			$sLeaderId = $_SESSION['staff_id'];
			$sRoleLeader = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'position_code');
			$sStatusAssign = $sStatus;
			//Thuc hien lay du lieu	
			$arrResul = $objReceive->DocReceivedAssignGetAll($sLeaderId,$sRoleLeader,$_SESSION['OWNER_ID'],trim($sFullTextSearch),$sStatusAssign,$iCurrentPage,$iNumRowOnPage,$arrPositionConst['_CONST_MAIN_LEADER_POSITION_GROUP'],$arrPositionConst['_CONST_SUB_LEADER_POSITION_GROUP']);
		}
		//Phan cong xu ly VB Cap PHONG BAN, PHUONG XA
		if ($arrPermission['PCXL_VB_DEN_PB'] || $arrPermission['PCXL_VBDEN_PX']){
			//Id lanh phong ban 	
			$iUnitId = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');
			$sStatusAssign = $sStatus;
			//Thuc hien lay du lieu	
			$arrResul = $objReceive->DocReceivedUnitAssignGetAll($iUnitId,trim($sFullTextSearch),$sStatusAssign,$iCurrentPage,$iNumRowOnPage);
		}
		$iNumberRecord = $arrResul[0]['C_TOTAL_RECORD'];
		$sdocpertotal ="Danh sách này không có văn bản nào";
		//Phan trang
		if (count($arrResul) > 0){
			$this->view->sdocpertotal = "Danh sách có ".sizeof($arrResul).'/'.$iNumberRecord." văn bản";
			//Sinh xau HTML mo ta so trang (Trang 1; Trang 2;...)
			$this->view->generateStringNumberPage = Sys_Publib_Library::_generateStringNumberPage($iNumberRecord, $iCurrentPage, $iNumRowOnPage,$sUrl) ;
			//Sinh chuoi HTML mo ta tong so trang (Trang 1; Trang 2;...) va quy dinh so record/page
			$this->view->generateHtmlSelectBoxPage = Sys_Publib_Library::_generateChangeRecordNumberPage($iNumRowOnPage,$sUrl);
		}
		$this->view->arrResul = $arrResul;
		//
		$this->view->NumberRowOnPage 	= $this->_ConstPublic['NumberRowOnPage'];	
		$this->view->iCountElement = count($this->view->arrAllRecord);
		
		//Luu cac gia tri tim kiem duoc nhap vao tu form 
        $this->view->FullTextSearch = $sFullTextSearch;
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
		//$arrReceived = $objReceive->DocReceivedGetSingle($sReceiveDocumentId);
		$arrReceived = $objReceive->DocReceivedGetSingle($sReceiveDocumentId,$_SESSION['OWNER_ID']);
		$this->view->arrReceived = $arrReceived;
		//Lay trang thai VB
		$sStatus = $this->_request->getParam('status','');
		$this->view->sStatus = $sStatus;
		//Lay thong tin history back
		$this->view->historyBack = '../index/status/'.$sStatus;
		//Lay thong tin trang hien thoi
		$iCurrentPage = $this->_request->getParam('hdn_current_page',0);
		$this->view->currentPage	= $piCurrentPage;	
		//Lay thong tin quy dinh so row / page
		$iNumRowOnPage = $this->_request->getParam('hdn_record_number_page',0);
		$this->view->numRowOnPage	= $piNumRowOnPage;	
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
		//Lay danh sach y kien cua Lanh dao
		$sLeaderIdeaList = substr($arrReceived[0]['C_IDEA_LIST'],6);
		//Lay danh sach Ten + Chuc vụ Lanh dao
		$sLeaderPositionNameList = substr($arrReceived[0]['C_LEADER_POSITION_NAME_LIST'],0,-2);
		$sPresidentIdea = '';
		//Lay y kien cua Chu Tich
		if($sLeaderIdeaList != ''){
			//Mang luu y kien 
			$arrLeaderIdea = explode('!#~$|*',$sLeaderIdeaList);
			//Mang luu Ten + Chuc vu
			$arrLeaderPositionName = explode('; ',$sLeaderPositionNameList);
			//Duyet theo chuc vu 
			for ($index = 0; $index<sizeof($arrLeaderPositionName); $index++){
				if (substr($arrLeaderPositionName[$index],0,2) == 'CT'){
					$sPresidentIdea  = $arrLeaderIdea[$index];
				}
			}
			
		}
		//Lay file dinh kem
		$strFileName 				= $arrReceived[0]['C_FILE_NAME'];
		$sFile 						= Sys_Library::_getAllFileAttach($strFileName,"!#~$|*","!~!",$this->_request->getBaseUrl() . "/public/attach-file/");
		//Lay duong dan
		$path = $_SERVER['SCRIPT_FILENAME'];
		$path = substr($path, 0, -9);
		$my_report = str_replace("/", "\\", $path) . "rpt\\received\\doc_received_info.rpt";
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
		$creport->ParameterFields(14)->SetCurrentValue($arrReceived[0]['C_LEADER_OFFICE_IDEA']);
		$creport->ParameterFields(15)->SetCurrentValue($sPresidentIdea);
		$creport->ParameterFields(16)->SetCurrentValue($arrReceived[0]['C_PROCESS_UNIT_NAME_LIST']);	
		//Ten file
		$report_file = 'doc_received_info.doc';
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
	 * Idea : Phuong thuc cap nhat thong tin PHAN CONG XU LY VB DEN (Cap DON VI)
	 * 
	 */
	public function editAction(){
		$this->view->bodyTitle = 'CẬP NHẬT THÔNG TIN PHÂN CÔNG XỬ LÝ';
		$arrInput = $this->_request->getParams();
		$objDocFun = new Sys_Function_DocFunctions();
		$objReceive = new received_modReceived();
		$ojbXmlLib = new Sys_Publib_Xml();
		$ojbSysLib = new Sys_Library();
		$objFilter = new Zend_Filter();
		$ojbSysInitConfig = new Sys_Init_Config();	
		//Lay trang thai VB
		$sStatus = $this->_request->getParam('status','');
		$this->view->sStatus = $sStatus;
		//Lay thong tin history back
		$this->view->historyBack = '../index/status/'.$sStatus;	
		//Lay tham so cau hinh
		$sysLibUrlPath = $ojbSysInitConfig->_setLibUrlPath();
		$url_path_calendar = $sysLibUrlPath . 'sys-calendar/';
		$this->view->urlCalendar = $url_path_calendar;
		$arrProcessType = $objReceive->getPropertiesDocument('DM_HINH_THUC_XU_LY','','');
		$this->view->arrProcessType = $arrProcessType; 
		//Lay chuc vu cua Lanh dao
		$sRoleLeader = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'position_code');
		//Neu la Chu Tich (CT)
		if($sRoleLeader=='CT'){
			$arrAssignType = $objReceive->getPropertiesDocument('DM_HINH_THUC_PHAN_CONG','','');
			$this->view->arrAssignType = $arrAssignType;
		}else{
			$arrAssignType = $objReceive->getPropertiesDocument('DM_HINH_THUC_PHAN_CONG','','CHUYEN_PCT');
			$this->view->arrAssignType = $arrAssignType;
		}  
		//Danh sach Lanh dao
		//$arrLeader = $objDocFun->docGetAllUnitLeader('LANH_DAO_UB_TINH,LANH_DAO_SO,LANH_DAO_UB_QUAN_HUYEN','arr_all_staff');
		$arrPositionConst =	$ojbSysInitConfig->_setLeaderPostionGroup();
		$arrLeader = $objDocFun->docGetAllUnitLeader($arrPositionConst['_CONST_POSITION_GROUP'],'arr_all_staff');
		$this->view->arrLeader = $arrLeader;
		//Noi nhan xu ly VB --> Lanh dao
		$this->view->search_textselectbox_leader = Sys_Function_DocFunctions::doc_search_ajax($arrLeader,"id","name","C_LEADER_POSITION_NAME_LIST","hdn_leader_id_list",0,'position_code');
		//Noi nhan xu ly --> Don vi, phong ban 
		$this->view->search_textselectbox_process_unit = Sys_Function_DocFunctions::doc_search_ajax($_SESSION['arr_all_unit_keep'],"id","name","C_UNIT_NAME_LIST","hdn_process_unit_id_list",0);
		//Can bo nhan VB
		$this->view->search_textselectbox_staff = Sys_Function_DocFunctions::doc_search_ajax($_SESSION['arr_all_staff'],"id","name","C_STAFF_RECEIVED_LIST","hdn_staff_id_list",0,"position_code");
		//Don vi, phong ban nhan VB
		$this->view->search_textselectbox_unit = Sys_Function_DocFunctions::doc_search_ajax($_SESSION['arr_all_unit_keep'],"id","name","C_UNIT_RECEIVED_LIST","hdn_unit_id_list",0);

		//Lay id cua van ban
		$sReceiveDocumentId = $this->_request->getParam('hdn_object_id','');		
		$this->view->sReceiveDocumentId = $sReceiveDocumentId;
		$arrReceived = $objReceive->DocReceivedAssignGetSingle($sReceiveDocumentId,$_SESSION['staff_id']);
		$this->view->arrReceived = $arrReceived;
		//Lay thong tin trang hien thoi
		$iCurrentPage = $this->_request->getParam('hdn_current_page',0);
		$this->view->currentPage	= $piCurrentPage;	
		//Lay thong tin quy dinh so row / page
		$iNumRowOnPage = $this->_request->getParam('hdn_record_number_page',0);
		$this->view->numRowOnPage	= $piNumRowOnPage;	
		//Tieu chi tim kiem
		$sFullTextSearch = $this->_request->getParam('FullTextSearch','');
		//Luu cac gia tri tim kiem duoc nhap vao tu form 
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
			//Lay hinh thuc xu ly VB
			$sTypeProcessing = $objFilter->filter($arrInput['C_TYPE_PROCESSING']);
			//Lay hinh thuc phan cong 
			$sTypeAssign = $objFilter->filter($arrInput['C_TYPE_ASSIGN']);
			//Lay trang thai PHAN CONG VB
			$sStatusAssign = 'CHO_PHAN_CONG';
			if ($sTypeProcessing == 'SAO_LUC'){
				$sStatusAssign = 'SAO_LUC';
				$sTypeAssign = '';
			}
			if ($sTypeProcessing == 'NHAN_DE_BIET'){
				$sStatusAssign = 'VB_LUU';
				$sTypeAssign = '';
			}
			//Lay thong tin noi nhan xu ly
			$sReceivePlaceProcessingIdList = '';
			$sReceivePlaceProcessingNameList = '';
			//Truong hop phan cong  --> Chuyen PCT (Truong hop VB duoc Chu Tich giao cho cac Pho Chu Tich chuyen trach xu ly)
			if($sTypeProcessing == 'VB_PHAI_XU_LY' And $sTypeAssign == 'CHUYEN_PCT'){
				$sStatusAssign = 'PHAN_PCT';
				//Danh sach ten LANH DAO nhan xu ly VB
				$sReceivePlaceProcessingNameList = substr($objFilter->filter($arrInput['C_LEADER_POSITION_NAME_LIST']),0,-2);
				//Danh sach Id LANH DAO nhan xu ly VB
				$sReceivePlaceProcessingIdList = $objDocFun->convertStaffNameToStaffId($sReceivePlaceProcessingNameList);
			}
			//Truong hop phan cong --> Chuyen DON VI, PHONG BAN xu ly VB
			if($sTypeProcessing == 'VB_PHAI_XU_LY' And $sTypeAssign == 'CHUYEN_DONVI_PHONGBAN'){
				//Danh sach ten DON VI, PHONG BAN nhan xu ly VB
				$sReceivePlaceProcessingNameList = substr($objFilter->filter($arrInput['C_UNIT_NAME_LIST']),0,-2);
				//Danh sach Id DON VI, PHONG BAN nhan xu ly VB
				$sReceivePlaceProcessingIdList = $objDocFun->convertUnitNameListToUnitIdList($sReceivePlaceProcessingNameList);
			}
			//Lay thong tin noi nhan VB --> Truon hop SO LUC chuyen VB 
			$sStaffIdList = '';
			$sUnitIdList  = '';
			if($sTypeProcessing == 'SAO_LUC'){
				//Danh sach ten CAN BO nhan VB
				$sStaffNameList = substr($objFilter->filter($arrInput['C_STAFF_RECEIVED_LIST']),0,-2);
				//Danh sach Id CAN BO nhan VB
				$sStaffIdList = $objDocFun->convertStaffNameToStaffId($sStaffNameList);
				//Lay danh sach ten PHONG BAN nhan VB
				$sUnitNameList = substr($objFilter->filter($arrInput['C_UNIT_RECEIVED_LIST']),0,-2);
				//Lay danh sach Id PHONG BAN nhan VB
				$sUnitIdList = $objDocFun->convertUnitNameListToUnitIdList($sUnitNameList);
			}
			//Mang luu tham so update in database	
			$arrParameter = array(	
								'PK_RECEIVED_DOC'					=>$sReceiveDocumentId,
								'FK_UNIT'							=>$_SESSION['OWNER_ID'],	
								'FK_LEADER_ID'						=>$_SESSION['staff_id'],
								'C_LEADER_POSITION_NAME'			=>$objDocFun->getNamePositionStaffByIdList($_SESSION['staff_id']),
								'C_ASSIGNED_DATE'					=>$ojbSysLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['C_ASSIGNED_DATE'])),
								'C_IDEA'                        	=>$ojbSysLib->_replaceBadChar($objFilter->filter($arrInput['C_IDEA'])),
								'C_TYPE_PROCESSING'					=>$sTypeProcessing,
								'C_TYPE_ASSIGN'						=>$sTypeAssign,
								'C_STATUS'							=>$sStatusAssign,
								'RECEIVE_PLACE_PROCESSING_IDLIST'	=>$sReceivePlaceProcessingIdList,
								'RECEIVE_PLACE_PROCESSING_NAMELIST'	=>str_replace(";","!#~$|*",$sReceivePlaceProcessingNameList),
								'C_APPOINTED_DATE'					=>$ojbSysLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['C_APPOINTED_DATE'])),
								'C_STAFF_ID_LIST'					=>$sStaffIdList,
								'C_STAFF_POSITION_NAME_LIST'		=>str_replace(";","!#~$|*",$sStaffNameList),
								'C_UNIT_ID_LIST'					=>$sUnitIdList,
								'C_UNIT_NAME_LIST'					=>str_replace(";","!#~$|*",$sUnitNameList),
								'C_DELIMITOR'						=>'!#~$|*'
						);
							
			$arrResult = "";
			if($objFilter->filter($arrInput['C_IDEA']) != ''){			
				$arrResult = $objReceive->DocReceivedAssignUpdate($arrParameter);			
				//Luu gia tri												
				$arrParaSet = array("hdn_current_page"=>$iCurrentPage,"hdn_record_number_page"=>$iNumRowOnPage,"FullTextSearch"=>$sFullTextSearch);
				//var_dump($arrParaSet); exit;
				$_SESSION['seArrParameter'] = $arrParaSet;
				$this->_request->setParams($arrParaSet);
				//Tro ve trang index												
				$this->_redirect('received/assign/index/status/'.$sStatus);	
			}
		}	
	}
	/**
	 * Idea : Phuong thuc cap nhat thong tin PHAN CONG XU LY VB DEN (Cap PHONG BAN)
	 * 
	 */
	public function editunitAction(){
		$this->view->bodyTitle = 'CẬP NHẬT THÔNG TIN PHÂN CÔNG XỬ LÝ';
		$arrInput = $this->_request->getParams();
		$objDocFun = new Sys_Function_DocFunctions();
		$objReceive = new received_modReceived();
		$ojbXmlLib = new Sys_Publib_Xml();
		$ojbSysLib = new Sys_Library();
		$objFilter = new Zend_Filter();
		$ojbSysInitConfig = new Sys_Init_Config();	
		//Lay trang thai VB
		$sStatus = $this->_request->getParam('status','');
		$this->view->sStatus = $sStatus;
		//Lay thong tin history back
		$this->view->historyBack = '../index/status/'.$sStatus;	
		//Lay tham so cau hinh
		$sysLibUrlPath = $ojbSysInitConfig->_setLibUrlPath();
		$url_path_calendar = $sysLibUrlPath . 'sys-calendar/';
		$this->view->urlCalendar = $url_path_calendar;
		$arrProcessType = $objReceive->getPropertiesDocument('DM_HINH_THUC_XU_LY','','SAO_LUC');
		$this->view->arrProcessType = $arrProcessType;  
		//Id lanh phong ban 	
		$iUnitId = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');
		$arrDepartmentStaffId = Sys_Function_DocFunctions::docGetAllDepartmentStaffId($iUnitId);
		//Can bo xu ly chinh 
		$this->view->search_textselectbox_staff_main_process = Sys_Function_DocFunctions::doc_search_ajax($arrDepartmentStaffId,"id","name","C_STAFF_PROCESS_MAIN_NAME_LIST","hdn_staff_main_process_id_list",0,"position_code");
		//Can bo phoi hop xu ly
		$this->view->search_textselectbox_staff_coordinate_process = Sys_Function_DocFunctions::doc_search_ajax($arrDepartmentStaffId,"id","name","C_STAFF_PROCESS_COORDINATE_NAME_LIST","hdn_staff_coordinate_process_id_list",0,"position_code");
		//Lay id cua van ban
		$sReceiveDocumentId = $this->_request->getParam('hdn_object_id','');		
		$this->view->sReceiveDocumentId = $sReceiveDocumentId;
		$arrReceived = $objReceive->DocReceivedUnitAssignGetSingle($sReceiveDocumentId,$iUnitId);
		$this->view->arrReceived = $arrReceived;
		//Lay thong tin trang hien thoi
		$iCurrentPage = $this->_request->getParam('hdn_current_page',0);
		$this->view->currentPage	= $piCurrentPage;	
		//Lay thong tin quy dinh so row / page
		$iNumRowOnPage = $this->_request->getParam('hdn_record_number_page',0);
		$this->view->numRowOnPage	= $piNumRowOnPage;	
		//Tieu chi tim kiem
		$sFullTextSearch = $this->_request->getParam('FullTextSearch','');
		//Luu cac gia tri tim kiem duoc nhap vao tu form 
        $this->view->FullTextSearch = $sFullTextSearch;
       	
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
			//Lay hinh thuc xu ly VB
			$sTypeProcessing = $objFilter->filter($arrInput['C_TYPE_PROCESSING']);
			//Lay trang thai PHAN CONG VB
			$sStatusAssign = 'CAN_XU_LY';
			if ($sTypeProcessing == 'NHAN_DE_BIET'){
				$sStatusAssign = 'VB_LUU';
			}
			//Lay thong tin CAN BO xu ly
			$sStaffProcessMainIdList = '';
			$sStaffCoordinateIdList = '';
			if($sTypeProcessing == 'VB_PHAI_XU_LY'){
				//Danh sach ten CAN BO xu ly chinh
				$sStaffProcessMainNameList = $objFilter->filter($arrInput['C_STAFF_PROCESS_MAIN_NAME_LIST']);
				//Danh sach id CAN BO xu ly chinh
				$sStaffProcessMainIdList = $objDocFun->convertStaffNameToStaffId($sStaffProcessMainNameList);
				//Danh sach ten CAN BO phoi hop xu ly 
				//echo $objFilter->filter($arrInput['C_STAFF_PROCESS_COORDINATE_NAME_LIST']);
				$sStaffCoordinateNameList = $objFilter->filter($arrInput['C_STAFF_PROCESS_COORDINATE_NAME_LIST']);
				//Danh sach id CAN BO phoi hop xu ly
				$sStaffCoordinateIdList = $objDocFun->convertStaffNameToStaffId($sStaffCoordinateNameList);
			}
			//Mang luu tham so update in database	
			$arrParameter = array(	
								'FK_RECEIVED_DOC'						=>$sReceiveDocumentId,
								'FK_UNIT'								=>$iUnitId,	
								'FK_LEADER_UNIT'						=>$_SESSION['staff_id'],
								'C_LEADER_UNIT_POSITION_NAME'			=>$objDocFun->getNamePositionStaffByIdList($_SESSION['staff_id']),
								'C_SENT_DATE'							=>$ojbSysLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['C_SENT_DATE'])),
								'C_LEADER_UNIT_IDEA'               	    =>$objFilter->filter($arrInput['C_LEADER_UNIT_IDEA']),
								'C_TYPE_PROCESSING'						=>$sTypeProcessing,
								'C_PROCESS_STATUS'						=>$sStatusAssign,
								'RECEIVE_PLACE_PROCESSING_IDLIST'		=>$sReceivePlaceProcessingIdList,
								'RECEIVE_PLACE_PROCESSING_NAMELIST'		=>str_replace(";","!#~$|*",$sReceivePlaceProcessingNameList),						
								'C_STAFF_PROCESS_MAIN_ID_LIST'			=>$sStaffProcessMainIdList,
								'C_STAFF_PROCESS_MAIN_NAME_LIST'		=>str_replace(";","!#~$|*",$sStaffProcessMainNameList),
								'C_STAFF_PROCESS_COORDINATE_ID_LIST'	=>$sStaffCoordinateIdList,
								'C_STAFF_PROCESS_COORDINATE_NAME_LIST'	=>str_replace(";","!#~$|*",$sStaffCoordinateNameList),								
								'C_APPOINTED_DATE'						=>$ojbSysLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['C_APPOINTED_DATE'])),
								'C_DELIMITOR'							=>'!#~$|*'
						);
							
			$arrResult = "";
			if($objFilter->filter($arrInput['C_TYPE_PROCESSING']) != ''){			
				$arrResult = $objReceive->DocReceivedUnitAssignUpdate($arrParameter);			
				//Luu gia tri												
				$arrParaSet = array("hdn_current_page"=>$iCurrentPage,"hdn_record_number_page"=>$iNumRowOnPage,"FullTextSearch"=>$sFullTextSearch);
				//var_dump($arrParaSet); exit;
				$_SESSION['seArrParameter'] = $arrParaSet;
				$this->_request->setParams($arrParaSet);
				//Tro ve trang index												
				$this->_redirect('received/assign/index/status/'.$sStatus);	
			}
		}	
	}
}
?>
<?php
/**
 * Nguoi tao: phongtd
 * Ngay tao: 26/07/2010
 * Y nghia: Class Xu ly cap nhat thong tin ket qua xu ly VB
 */	
class received_processController extends  Zend_Controller_Action {
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
		$this->view->currentModulCodeForLeft = "PROCESS-DOC";			
		//Lay trang thai left menu
		$this->view->getStatusLeftMenu = $this->_request->getParam('status','');
				
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
		$sLabelAssign = "CẦN XỬ LÝ";
		if ($sStatus == "DA_XU_LY"){
				$sLabelAssign = "ĐÃ XỬ LÝ";
			}
		if ($sStatus == "PHXL"){
				$sLabelAssign = "PHỐI HỢP XỬ LÝ";
			}	
		// Tieu de man hinh danh sach
		$this->view->bodyTitle = "DANH SÁCH VĂN BẢN ĐẾN ". $sLabelAssign; 
		// Tao doi tuong Zend_Filter
		$objFilter = new Zend_Filter();
		$ojbSysLib = new Sys_Library();
		$objDocFun = new Sys_Function_DocFunctions();
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
		
		//Id lanh phong ban 	
		$iUnitId = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');
		$sStatusAssign = $sStatus;
		//Thuc hien lay du lieu	
		$sFullTextSearch = $ojbSysLib->_replaceBadChar($sFullTextSearch);
		$arrResul = $objReceive->DocReceivedStaffProcessWorkGetAll($iUnitId,$_SESSION['staff_id'],trim($sFullTextSearch),$sStatusAssign,$iCurrentPage,$iNumRowOnPage);
		//Phan trang
		$iNumberRecord = $arrResul[0]['C_TOTAL_RECORD'];
		$sdocpertotal ="Danh sách này không có văn bản nào";
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
		$arrReceived = $objReceive->DocReceivedGetSingle($sReceiveDocumentId,$_SESSION['staff_id']);
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
		$arrReceived = $objReceive->DocReceivedGetSingle($sReceiveDocumentId,$_SESSION['staff_id']);
		$this->view->arrReceived = $arrReceived;
		//Lay danh sach y kien cua Lanh dao
		$sLeaderIdeaList = substr($arrReceived[0]['C_IDEA_LIST'],6);
		//Lay danh sach Ten + Chuc vụ Lanh dao
		$sLeaderPositionNameList = substr($arrReceived[0]['C_LEADER_POSITION_NAME_LIST'],0,-2);
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
		$my_report_file = 'http://'.$_SERVER['HTTP_HOST'].':8080/'.$this->_request->getBaseUrl() .'/public/' . $report_file;
		$this->view->my_report_file = $my_report_file;
		
	}
	/**
	 * Idea : Phuong thuc cap nhat thong tin PHAN CONG XU LY VB DEN (Cap DON VI)
	 * 
	 */
	public function editAction(){
		$this->view->bodyTitle = 'CẬP NHẬT KẾT QUẢ XỬ LÝ VĂN BẢN';
		$this->view->WorkTitle = 'QUÁ TRÌNH XỬ LÝ VĂN BẢN';
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
		$this->view->historyBack = '../../index/status/'.$sStatus;	
		//Lay tham so cau hinh
		$sysLibUrlPath = $ojbSysInitConfig->_setLibUrlPath();
		$url_path_calendar = $sysLibUrlPath . 'sys-calendar/';
		$this->view->urlCalendar = $url_path_calendar;
		
		//Lay id cua van ban
		$sReceiveDocumentId = $this->_request->getParam('hdn_object_id','');		
		$this->view->sReceiveDocumentId = $sReceiveDocumentId;
		//Lay id cua cong viec
		$sPkDocWorkId = $this->_request->getParam('hdn_work_id','');		
		$this->view->sPkDocWorkId = $sPkDocWorkId;
		
		//Lay noi dung chi tiet mot cong viec
		$arrWork = $objReceive->DocReceivedProcessWorkGetSingle($sPkDocWorkId,$_SESSION['staff_id']);
		$this->view->arrWork = $arrWork;
		
		//Lay file da dinh kem tu truoc
		$arFileAttach = $objReceive->DOC_GetAllDocumentFileAttach($sPkDocWorkId,'','T_DOC_WORK');	
		$this->view->AttachFile = $objDocFun->DocSentAttachFile($arFileAttach,sizeof($arFileAttach),10,true,60);	
		//Thuc hien upload file len o cung toi da 10 file
		$arrFileNameUpload = $ojbSysLib->_uploadFileList(10,$this->_request->getBaseUrl() . "/public/attach-file/",'FileName','!#~$|*');
		
		//Lay thong tin trang hien thoi
		$iCurrentPage = $this->_request->getParam('hdn_current_page',0);
		$this->view->currentPage	= $iCurrentPage;	
		//Lay thong tin quy dinh so row / page
		$iNumRowOnPage = $this->_request->getParam('hdn_record_number_page',0);
		$this->view->numRowOnPage	= $iNumRowOnPage;	
		//Tieu chi tim kiem
		$sFullTextSearch = $this->_request->getParam('FullTextSearch','');
		//Luu cac gia tri tim kiem duoc nhap vao tu form 
        $this->view->FullTextSearch = $sFullTextSearch;  
		$arrInput = $this->_request->getParams();	
		//Lay thong tin CAN BO cap nhat 
		$sStaffId = $_SESSION['staff_id'];
		$sPositionName = $objDocFun ->getNamePositionStaffByIdList($sStaffId); 
		$sUnitId = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$sStaffId,'unit_id');
		$sUnitName = $objDocFun ->getNameUnitByIdUnitList($sUnitId);
		$sStatusProcess= $objFilter->filter($arrInput['C_PROCESS_STATUS']);
		if($sStatus == 'PHXL'){
			$sStatusProcess = 'DANG_XU_LY';
		}
		//Mang luu tham so update in database	
		$arrParameter = array(	
							'PK_DOC_WORK'						=>$sPkDocWorkId,
							'FK_DOC'							=>$sReceiveDocumentId,	
							'FK_UNIT'							=>$sUnitId,
							'C_UNIT_NAME'						=>$sUnitName,
							'FK_STAFF'							=>$sStaffId,
							'C_STAFF_POSITION_NAME'             =>$sPositionName,
							'C_WORK_DATE'						=>$ojbSysLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['C_WORK_DATE'])),
							'C_RESULT'            				=>$ojbSysLib->_replaceBadChar($objFilter->filter($arrInput['C_RESULT'])),
							'C_STATUS'							=>$sStatusProcess,
							'ATTACH_FILE_NAME_LIST'				=>$arrFileNameUpload
					);
						
		$arrResult = "";
		if($objFilter->filter($arrInput['C_RESULT']) != ''){			
			$arrResult = $objReceive->DocReceivedProcessWorkUpdate($arrParameter);			
			//Luu gia tri												
			$arrParaSet = array("hdn_current_page"=>$iCurrentPage,"hdn_record_number_page"=>$iNumRowOnPage,"FullTextSearch"=>$sFullTextSearch,"status"=>$sStatus);
			$_SESSION['seArrParameter'] = $arrParaSet;
			$this->_request->setParams($arrParaSet);
			//Tro ve trang edit
			if($sStatus == 'CAN_XU_LY' and $sStatusProcess == 'DANG_XU_LY'){												
				$this->_redirect('received/process/edit/status/CAN_XU_LY'.'?hdn_object_id='.$sReceiveDocumentId);
			}
			if($sStatus == 'CAN_XU_LY' and $sStatusProcess == 'DA_XU_LY'){												
				$this->_redirect('received/process/edit/status/DA_XU_LY'.'?hdn_object_id='.$sReceiveDocumentId);
			}
			if($sStatus == 'DA_XU_LY' and $sStatusProcess == 'KHOI_PHUC_XU_LY'){												
				$this->_redirect('received/process/edit/status/CAN_XU_LY'.'?hdn_object_id='.$sReceiveDocumentId);
			}
			if($sStatus == 'PHXL'){												
				$this->_redirect('received/process/edit/status/PHXL'.'?hdn_object_id='.$sReceiveDocumentId);
			}		
		}
		//Lay toan bo thong tin qua trinh xu ly cua mot van ban den
		$arrWorkAll = $objReceive->DocReceivedProcessWorkGetAll($sReceiveDocumentId);
		$this->view->arrWorkAll = $arrWorkAll;
	}
	/**
	 * Idea : Phuong thuc xoa mot VB
	 *
	 */
	public function deleteAction(){
		// Tao doi tuong cho lop xu ly du lieu lien quan modul	
		$objReceive = new received_modReceived();
		$ojbSysLib = new Sys_Library();
		//Lay id cua van ban
		$sReceiveDocumentId = $this->_request->getParam('hdn_object_id','');		
		$this->view->sReceiveDocumentId = $sReceiveDocumentId;
		//Lay trang thai VB
		$sStatus = $this->_request->getParam('status','');
		$this->view->sStatus = $sStatus;				
		//Lay Id cong viec xu ly VB can xoa
		$sPkDocWorkIdList = $this->_request->getParam('hdn_object_id_list',"");	
		if ($sPkDocWorkIdList != ""){
			$sRetError = $objReceive->DocReceivedProcessWorkDelete($sPkDocWorkIdList);
			$this->_redirect('received/process/edit/status/'.$sStatus.'?hdn_object_id='.$sReceiveDocumentId);	
		}	
	
	}
	public function draffAction(){
		$arrInput = $this->_request->getParams();
		$objDocFun = new Sys_Function_DocFunctions();
		$objReceive = new received_modReceived();
		$ojbXmlLib = new Sys_Publib_Xml();
		$ojbSysLib = new Sys_Library();
		$objFilter = new Zend_Filter();
		$ojbSysInitConfig = new Sys_Init_Config();	
		//Lay id cua van ban
		$sReceiveDocumentId = $this->_request->getParam('hdn_object_id','');	
		//Lay mang chua thong tin VB
		//$arrReceived = $objReceive->DocReceivedGetSingle($sReceiveDocumentId);
		$arrReceived = $objReceive->DocReceivedGetSingle($sReceiveDocumentId,$_SESSION['OWNER_ID']);	
		//var_dump($arrReceived);
		//EXIT;
		//lay ID va ten don vi soan thao 
		$iDepartmentId  = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');
		$sDepartmentName= Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_unit'],$iDepartmentId,'name');
		$iUserId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
		$sDraffPosition = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$iUserId,'position_code');
		//Mang luu tham so update in database	
		$arrParameter = array(	
							'PK_RECEIVED_DOC'				=>$arrReceived[0]['PK_RECEIVED_DOC'],										
							'FK_UNIT'						=>$_SESSION['OWNER_ID'],
							'C_SUBJECT'						=>$arrReceived[0]['C_SUBJECT'],
							'C_DOC_CATE'					=>$$arrReceived[0]['C_DOC_CATE'],
							'C_STATUS'						=>'VB_DU_THAO',
							'FK_DEPARTMENT'					=>$iDepartmentId,
							'C_UNIT_NAME'					=>$sDepartmentName,
							'FK_STAFF'						=>$iUserId,
							'C_STAFF_POSITION_NAME'			=>$sDraffPosition . ' - ' . $ojbSysLib->_getItemAttrById($_SESSION['arr_all_staff'],$iUserId,'name'),
					);			
		$arrResult = $objReceive->docDraffReceivedUpdate($arrParameter);
		//var_dump($arrResult); exit;	
		if ($arrResult['NEW_ID'] == "VB_DU_THAO"){
			echo "<script type='text/javascript'>";
			echo "alert('Van ban nay da duoc chuyen du thao');\n";					
			echo "history.back();\n";
			echo "</script>";
		}else{		
			$this->_redirect('sent/draff/edit/?hdn_object_id='.$arrResult['NEW_ID']);	
		}
		
	}	
}?>
<?php
/**
 * Nguoi tao: phongtd
 * Ngay tao: 26/06/2010
 * Y nghia: Class Xu ly PPVB den
 */	
class received_distributionController extends  Zend_Controller_Action {	
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
		Zend_Loader::loadClass('web_modWebMenu');
		//Lay tat ca cac chuyen muc
		$objWebMenu = new web_modWebMenu();
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
		$this->view->LoadAllFileJsCss = Sys_Publib_Library::_getAllFileJavaScriptCss('','js','received.js,js_calendar.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','sys-js','ajax.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','jquery-1.4.2.min.js',',','js'). Sys_Publib_Library::_getAllFileJavaScriptCss('','js/LibSearch','actb_search.js,common_search.js',',','js');

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
		$this->view->currentModulCodeForLeft = "DISTRIBUTION-RECEIVED-DOC";
		//Lay trang thai left menu
		$this->view->getStatusLeftMenu = $this->_request->getParam('status','');
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
		//Lay trang thai VB
		$sStatus = $this->_request->getParam('status','');
		$this->view->sStatus = $sStatus;	
		$sLabelDistribution = "CHỜ PHÂN PHỐI";
		if ($sStatus == "DA_PHAN_PHOI"){
			$sLabelDistribution = "ĐÃ PHÂN PHỐI";
		}			
		// Tieu de man hinh danh sach
		$this->view->bodyTitle = "DANH SÁCH VĂN BẢN ĐẾN ". $sLabelDistribution;
		
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
		//Thuc hien lay du lieu	
		$sFullTextSearch = $ojbSysLib->_replaceBadChar($sFullTextSearch);
		$arrResul = $objReceive->DocReceivedDistributionGetAll($_SESSION['OWNER_ID'],trim($sFullTextSearch),$sStatus,$iCurrentPage,$iNumRowOnPage);
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

		//var_dump($arrResul);
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
		//Lay file dinh kem
		$strFileName 				= $arrReceived[0]['C_FILE_NAME'];
		$sFile 						= Sys_Library::_getAllFileAttach($strFileName,"!#~$|*","!~!",$this->_request->getBaseUrl() . "/public/attach-file/");
		//Lay duong dan
		$path = $_SERVER['SCRIPT_FILENAME'];
		$path = substr($path, 0, -9);
		$my_report = str_replace("/", "\\", $path) . "rpt\\received\\received.rpt";
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
	 * Idea : Phuong thuc hien cap nhat thong tin PHAN PHOI VB
	 *
	 */
	public function editAction(){
		$this->view->bodyTitle = 'CẬP NHẬT THÔNG TIN PHÂN PHỐI VĂN BẢN';
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
		//var_dump($_SESSION['arr_all_staff']);
		//var_dump($_SESSION['arr_all_staff']);
		$arrPositionConst = $ojbSysInitConfig->_setLeaderPostionGroup();
		$arrLeader = $objDocFun->docGetAllUnitLeader($arrPositionConst['_CONST_POSITION_GROUP'],'arr_all_staff');
		$this->view->arrLeader = $arrLeader;
		// Goi ham search lay ra toan bo thong tin Lanh dao nhan VB
		$this->view->search_textselectbox_leader = Sys_Function_DocFunctions::doc_search_ajax($arrLeader,"id","name","C_LEADER_POSITION_NAME_LIST","hdn_leader_id_list",0,'position_code');
		$this->view->search_textselectbox_unit = Sys_Function_DocFunctions::doc_search_ajax($_SESSION['arr_all_unit_keep'],"id","name","C_UNIT_NAME_LIST","hdn_unit_id_list",0);
		$this->view->search_textselectbox_staff = Sys_Function_DocFunctions::doc_search_ajax($_SESSION['arr_all_staff_keep'],"id","name","C_STAFF_NAME_LIST","hdn_staff_id_list",0,'position_code');
		//Lay id cua van ban
		$sReceiveDocumentId = $this->_request->getParam('hdn_object_id','');		
		$this->view->sReceiveDocumentId = $sReceiveDocumentId;
		$arrReceived = $objReceive->DocReceivedDistributionGetSingle($sReceiveDocumentId);
		//var_dump($arrReceived); //exit;
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
			$sLeaderNameList = $objFilter->filter($arrInput['C_LEADER_POSITION_NAME_LIST']);
			$sLeaderIdList = $objDocFun->convertStaffNameToStaffId($sLeaderNameList);
			
			$sUnitNamelist = '';
			$sUnitIdList = '';
			$sStaffNameList = '';
			$sStaffIdList = '';
			$sUnitByStaffIdList = '';
			$sProcessStatusUnitList = '';
			$arrPermission = $_SESSION['arrStaffPermission'];
			if($arrPermission['CAP_NHAT_PCXL_VB_DV']){
				$sUnitNamelist	 = $objFilter->filter($arrInput['C_UNIT_NAME_LIST']);
				$sUnitIdList	 = $objDocFun->convertUnitNameListToUnitIdList($sUnitNamelist);
				
				$sStaffNameList	 = $objFilter->filter($arrInput['C_STAFF_NAME_LIST']);
				$sStaffIdList	 = $objDocFun->convertStaffNameToStaffId($sStaffNameList);
				
				$sUnitByStaffIdList = $objDocFun->doc_get_all_unit_permission_form_staffIdList($sStaffIdList);
				$sProcessStatusUnitList = '';
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
			//Mang luu tham so update in database	
			$arrParameter = array(	
								'FK_RECEIVED_DOC'				=>$sReceiveDocumentId,	
								'C_DISTRIBUTION_DATE'			=>$ojbSysLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['C_DISTRIBUTION_DATE'])),
								'C_LEADER_OFFICE_IDEA'			=>$ojbSysLib->_replaceBadChar($objFilter->filter($arrInput['C_LEADER_OFFICE_IDEA'])),
								'C_LEADER_ID_LIST'				=>$sLeaderIdList,
								'C_LEADER_POSITION_NAME_LIST'	=>str_replace(";","!#~$|*",$sLeaderNameList),
								'C_UNIT_ID_LIST'				=>$sUnitIdList,
								'C_UNIT_NAME_LIST'				=>str_replace(";","!#~$|*",$sUnitNamelist),
								'C_STAFF_ID_LIST'				=>$sStaffIdList,
								'C_STAFF_NAME_LIST'				=>str_replace(";","!#~$|*",$sStaffNameList),
								'C_PROCESS_STATUS_UNIT_LIST'	=>$sProcessStatusUnitList,
								'C_UNIT_BY_STAFF_ID_LIST'		=>$sUnitByStaffIdList,
								'C_APPOINTED_DATE'				=>$ojbSysLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['C_APPOINTED_DATE'])),
								'C_DELIMITOR'					=>'!#~$|*'
						);
							
			$arrResult = "";
			if($sLeaderIdList != ''){			
				$arrResult = $objReceive->DocReceivedDistributionUpdate($arrParameter);					
				//Luu gia tri												
				$arrParaSet = array("hdn_current_page"=>$iCurrentPage,"hdn_record_number_page"=>$iNumRowOnPage,"FullTextSearch"=>$sFullTextSearch);
				$_SESSION['seArrParameter'] = $arrParaSet;
				$this->_request->setParams($arrParaSet);
				//Tro ve trang index												
				$this->_redirect('received/distribution/index/status/'.$sStatus);	
			}
		}
	}
}
?>
<?php
class sent_processController extends  Zend_Controller_Action {
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
		
		Zend_Loader::loadClass('Sent_modSent');		
		Zend_Loader::loadClass('Listxml_modList');
		//Lay cac hang so su dung trong JS public
		Zend_Loader::loadClass('Sys_Init_Config');
		$objConfig = new Sys_Init_Config();
		$this->view->JSPublicConst = $objConfig->_setJavaScriptPublicVariable();		
		//Dia chi URL doc file xu ly AJAX
		$this->view->UrlAjax = $objConfig->_setUrlAjax();	
		// Load tat ca cac file Js va Css
		$JSandStyle = Sys_Publib_Library::_getAllFileJavaScriptCss('','js','js_calendar.js',',','js').Sys_Publib_Library::_getAllFileJavaScriptCss('','js','util.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','sent.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','ajax.js,jquery-1.4.2.min.js,jquery-1.4.2.js,jQuery.equalHeights.js',',','js'). Sys_Publib_Library::_getAllFileJavaScriptCss('','js/LibSearch','actb_search.js,common_search.js',',','js');
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
		//Lay lich ngay/thang/nam
		$sysLibUrlPath = $objConfig->_setLibUrlPath();
		$url_path_calendar = $sysLibUrlPath . 'sys-calendar/';
		$this->view->urlCalendar = $url_path_calendar;	
		//Dinh nghia current modul code
		$this->view->currentModulCode = "SENT";
		$this->view->currentModulCodeForLeft = "PROCESS";
		//Lay trang thai left menu
		$this->view->getStatusLeftMenu = $this->_request->getParam('modul','');
		$psshowModalDialog = $this->_request->getParam('showModalDialog',"");
		if ($psshowModalDialog != 1){
		//Hien thi file template
		$response->insert('header', $this->view->renderLayout('twd_header.phtml','template/'));    
			$response->insert('left', $this->view->renderLayout('twd_left.phtml','template/'));  	    
	        $response->insert('footer', $this->view->renderLayout('twd_footer.phtml','template/'));        
		}				
  	}
	/**
	 * Creater: Tran Nghia
	 * Idea : Phuong thuc hien thi danh sach
	 *
	 */
	public function indexAction(){
		$iUserId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
		$iDepartmentId  = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');
		$iOwnerId = $_SESSION['OWNER_ID'];
		$arrInput = $this->_request->getParams();
		//lay modul left
		$getStatusFromMnuLeft = $this->_request->getParam('modul','');
		$this->view->getModulLeft = $getStatusFromMnuLeft;
		if($getStatusFromMnuLeft =='XU_LY_CHINH'){
			$this->view->bodyTitle = 'DANH SÁCH VĂN BẢN DỰ THẢO (XỬ LÝ CHÍNH)';
		}else{
			$this->view->bodyTitle = 'DANH SÁCH VĂN BẢN DỰ THẢO (PHỐI HỢP XỬ LÝ)';
		}
			// Tao doi tuong 
		$ojbSysLib = new Sys_Library();
		$objSent = new Sent_modSent();	
		$objFunction =	new	Sys_Function_DocFunctions()	;
		$ojbSysInitConfig = new Sys_Init_Config();
		//Lay cac gia tri const
		$this->view->arrConst =	$ojbSysInitConfig->_setProjectPublicConst();
		$this->view->hdn_object_id = $this->_request->getParam('hdn_object_id',0);	
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
		//Duong dan url
		$pUrl = $_SERVER['REQUEST_URI'];
		$this->view->numRowOnPage = $piNumRowOnPage; //Gan gia tri vao View	
		//Nhan bien truyen vao tu form
		$sFullTextSearch = trim($this->_request->getParam('FullTextSearch',''));
		$sFullTextSearch = $ojbSysLib->_replaceBadChar($sFullTextSearch);
		$this->view->sFullTextSearch = $sFullTextSearch;
		//
		$sStatus = 'VB_DU_THAO';
		if($getStatusFromMnuLeft =='XU_LY_CHINH'){
			$sProcessType	= 'XU_LY_CHINH';
		}else{
			$sProcessType	= 'PHXL';
		}
		// Xu li query lay du lieu
		//if($sFullTextSearch !=''){
			$arrSent = $objSent->docDraftProcessGetAll($sFullTextSearch,$sStatus,$iOwnerId,$iDepartmentId,$iUserId,$sProcessType,$piCurrentPage,$piNumRowOnPage);
		//}
		$this->view->arrSent = $arrSent;
		//Mang luu thong tin tong so ban ghi tim thay
		$psCurrentPage = $arrSent[0]['C_TOTAL'];				
		if (count($arrSent) > 0){
			$this->view->sdocpertotal = "Danh sách có ".sizeof($arrSent).'/'.$psCurrentPage." văn bản";
			//Sinh xau HTML mo ta so trang (Trang 1; Trang 2;...)
			$this->view->generateStringNumberPage = Sys_Publib_Library::_generateStringNumberPage($psCurrentPage, $piCurrentPage, $piNumRowOnPage,$pUrl) ;		
			//quy dinh so record/page	
			$this->view->generateHtmlSelectBoxPage = Sys_Publib_Library::_generateChangeRecordNumberPage($piNumRowOnPage,"../index/" );
		}

	}
	
	public function addAction(){	
		$iUserId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
		$this->view->iUserId = $iUserId;
		$iDepartmentId  = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');
		$sGetModulLeft = $this->_request->getParam('hdn_function_modul',0);
		$iValue = $this->_request->getParam('iValue',0);			
		$this->view->iValue = $iValue;
		$this->view->getModulLeft = $sGetModulLeft;
		$this->view->bodyTitle = 'CẬP NHẬT KẾT QUẢ DỰ THẢO';
		$this->view->bodyTitle2 = 'DANH SÁCH QUÁ TRÌNH XỬ LÝ VĂN BẢN DỰ THẢO';
		$arrInput = $this->_request->getParams();
		//Tao doi tuong 
		$objSent   = new Sent_modSent();
		$objFilter = new Zend_Filter();	
		$objDocFun = new Sys_Function_DocFunctions();
		$ojbXmlLib = new Sys_Publib_Xml();
		$ojbSysLib = new Sys_Library();
		$objList   = new Listxml_modList();
		$ojbSysInitConfig = new Sys_Init_Config();
		//Khai bao const
		$this->view->arrConst =	$ojbSysInitConfig->_setProjectPublicConst();
		//Lay file da dinh kem tu truoc
		$arFileAttach = $objSent->DOC_GetAllDocumentFileAttach($sentID,'','T_DOC_SENT_DOCUMENT');	
		$this->view->AttachFile = $objDocFun->DocSentAttachFile($arFileAttach,sizeof($arFileAttach),10,true,61);	
		//Thuc hien upload file len o cung toi da 10 file
		$arrFileNameUpload = $ojbSysLib->_uploadFileList(10,$this->_request->getBaseUrl() . "/public/attach-file/",'FileName','!#~$|*');
		//Request ID VB di
		$sentID = $this->_request->getParam('hdn_object_id','');
		$this->view->sentID = $sentID;
		$arrSent = $objSent->docDraftGetSingle($sentID,'','');
		$this->view->arrSent = $arrSent;
		$psOption = $this->_request->getParam('hdh_option','');
		//Mang luu du lieu update
		$arrParameter = array(	
								'PK_SENT_DOCUMENT'							=>$sentID,		
								'C_WORK_ID'									=>'',	
								'FK_STAFF'									=>$iUserId,
								'C_STAFF_POSITION_NAME'						=>$objDocFun->getNamePositionStaffByIdList($iUserId),
								'FK_UNIT'									=>$iDepartmentId,
								'C_UNIT_NAME'								=>$objDocFun->getNameUnitByIdUnitList($iDepartmentId),
								'C_WORK_DATE'								=>$ojbSysLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['C_WORK_DATE'])).' '.date("h:i:s A"),
								'C_RESULT'									=>$sFullTextSearch = $ojbSysLib->_replaceBadChar($objFilter->filter($arrInput['C_RESULT'])),	
								'C_FILE_NAME'								=>$arrFileNameUpload,
								'C_PROCESS_STATUS'							=>$objFilter->filter($arrInput['C_PROCESS_STATUS']),
							);					
		if($objFilter->filter($arrInput['C_RESULT']) != ""){				
			$arrResult = "";	
			$arrResult = $objSent->docDocDraftProcessUpdate($arrParameter);	
			//$arrResult['NEW_ID'];
		}
		//Trang thai VB
		$arrStatus = $objSent->docDraftProcessStatus($sentID,$iDepartmentId);
		$this->view->sMainProcessStatus = 	$arrStatus[0]['C_MAIN_PROCESS_STATUS'];
		$this->view->sProcessStatus = 	$arrStatus[0]['C_PROCESS_STATUS'];
		//Lay danh sach qua trinh xu ly VB du thao
		$arrProcess = $objSent->docDraftProcessProgressGetAll($sentID,$iDepartmentId);	
		//Loc Cong viec gan day va Cong viec truoc theo ten can bo cho y kien
		$j = 0; $k= 0;$iFkStaff = 0;
		for ($index =0;$index<sizeof($arrProcess);$index++){
			if($iFkStaff != $arrProcess[$index]['FK_STAFF']){
				$arrProcessNew[$j]['PK_DOC_WORK'] = $arrProcess[$index]['PK_DOC_WORK'];			
				$arrProcessNew[$j]['C_WORK_DATE'] = $arrProcess[$index]['C_WORK_DATE'];
				$arrProcessNew[$j]['C_RESULT'] = $arrProcess[$index]['C_RESULT'];
				$arrProcessNew[$j]['C_STAFF_POSITION_NAME'] = $arrProcess[$index]['C_STAFF_POSITION_NAME'];
				$arrProcessNew[$j]['C_UNIT_NAME'] = $arrProcess[$index]['C_UNIT_NAME'];
				$arrProcessNew[$j]['C_FILE_NAME'] = $arrProcess[$index]['C_FILE_NAME'];
				$arrProcessNew[$j]['FK_STAFF'] = $arrProcess[$index]['FK_STAFF'];
				$j = $j +1;
			}else{
				$arrProcessOld[$k]['PK_DOC_WORK'] = $arrProcess[$index]['PK_DOC_WORK'];
				$arrProcessOld[$k]['C_WORK_DATE'] = $arrProcess[$index]['C_WORK_DATE'];
				$arrProcessOld[$k]['C_RESULT'] = $arrProcess[$index]['C_RESULT'];
				$arrProcessOld[$k]['C_STAFF_POSITION_NAME'] = $arrProcess[$index]['C_STAFF_POSITION_NAME'];
				$arrProcessOld[$k]['C_UNIT_NAME'] = $arrProcess[$index]['C_UNIT_NAME'];
				$arrProcessOld[$k]['C_FILE_NAME'] = $arrProcess[$index]['C_FILE_NAME'];
				$arrProcessOld[$k]['FK_STAFF'] = $arrProcess[$index]['FK_STAFF'];
				$k = $k +1;
			}
			$iFkStaff =  $arrProcess[$index]['FK_STAFF'];
		}
		$arrAssign	=	$objSent->docAssignGetSingle($sentID,$iDepartmentId);	
		//var_dump($arrAssign);
		$this->view->arrAssign = $arrAssign;	
		// Dua ra man hinh danh sach 
		$this->view->arrProcessNew = $arrProcessNew;
		//var_dump($arrProcessNew);
		$this->view->arrProcessOld = $arrProcessOld;

	}
	public function editAction(){	
		$iUserId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
		$this->view->iUserId = $iUserId;
		$iDepartmentId  = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');
		$sGetModulLeft = $this->_request->getParam('hdn_function_modul',0);
		$this->view->getModulLeft = $sGetModulLeft;
		$this->view->bodyTitle = 'CẬP NHẬT KẾT QUẢ DỰ THẢO';
		$this->view->bodyTitle2 = 'DANH SÁCH QUÁ TRÌNH XỬ LÝ VĂN BẢN DỰ THẢO';
		$arrInput = $this->_request->getParams();
		//Tao doi tuong 
		$objSent   = new Sent_modSent();
		$objFilter = new Zend_Filter();	
		$objDocFun = new Sys_Function_DocFunctions();
		$ojbXmlLib = new Sys_Publib_Xml();
		$ojbSysLib = new Sys_Library();
		$objList   = new Listxml_modList();
		$ojbSysInitConfig = new Sys_Init_Config();
		//Khai bao const
		$this->view->arrConst =	$ojbSysInitConfig->_setProjectPublicConst();

		//Request ID VB di
		$sentID= $this->_request->getParam('hdn_object_id','');
		$workID= $this->_request->getParam('hdn_work_id','');		
		$this->view->sentID = $sentID;
		$this->view->workID = $workID;
		$arrSent = $objSent->docDraftGetSingle($sentID,'','');
		$this->view->arrSent = $arrSent;
		//Thuc hien upload file len o cung toi da 10 file
		$arrFileNameUpload = $ojbSysLib->_uploadFileList(10,$this->_request->getBaseUrl() . "/public/attach-file/",'FileName','!#~$|*');
		$psOption = $this->_request->getParam('hdh_option','');
		//Mang luu du lieu update
		$arrParameter = array(	
								'PK_SENT_DOCUMENT'							=>$sentID,		
								'C_WORK_ID'									=>$workID,	
								'FK_STAFF'									=>$iUserId,
								'C_STAFF_POSITION_NAME'						=>$objDocFun->getNamePositionStaffByIdList($iUserId),
								'FK_UNIT'									=>$iDepartmentId,
								'C_UNIT_NAME'								=>$objDocFun->getNameUnitByIdUnitList($iDepartmentId),
								'C_WORK_DATE'								=>$ojbSysLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['C_WORK_DATE'])).' '.date("h:i:s A"),
								'C_RESULT'									=>$sFullTextSearch = $ojbSysLib->_replaceBadChar($objFilter->filter($arrInput['C_RESULT'])),	
								'C_FILE_NAME'								=>$arrFileNameUpload,
								'C_PROCESS_STATUS'							=>$objFilter->filter($arrInput['C_PROCESS_STATUS']),
							);					
		if($objFilter->filter($arrInput['C_RESULT']) != ""){				
			$arrResult = "";	
			$arrResult = $objSent->docDocDraftProcessUpdate($arrParameter);	
			//$arrResult['NEW_ID'];
		}
		$arrWork = $objSent->docDraftProcessGetSingle($workID);
		$this->view->arrWork = $arrWork;
		//Lay danh sach qua trinh xu ly VB du thao
		$arrProcess = $objSent->docDraftProcessProgressGetAll($sentID,$iDepartmentId);	
		//Lay trang thai VB	
		$arrStatus = $objSent->docDraftProcessStatus($sentID,$iDepartmentId);	
		$this->view->sMainProcessStatus = 	$arrStatus[0]['C_MAIN_PROCESS_STATUS'];
		$this->view->sProcessStatus = 	$arrStatus[0]['C_PROCESS_STATUS'];
		//Lay file da dinh kem tu truoc
		$arFileAttach = $objSent->DOC_GetAllDocumentFileAttach($workID,'','T_DOC_WORK');	
		$this->view->AttachFile = $objDocFun->DocSentAttachFile($arFileAttach,sizeof($arFileAttach),10,true,61);	
		//Loc Cong viec gan day va Cong viec truoc theo ten can bo cho y kien
			$j = 0; $k= 0;$iFkStaff = 0;
			for ($index =0;$index<sizeof($arrProcess);$index++){
				if($iFkStaff != $arrProcess[$index]['FK_STAFF']){
					$arrProcessNew[$j]['PK_DOC_WORK'] = $arrProcess[$index]['PK_DOC_WORK'];	
					$arrProcessNew[$j]['C_WORK_DATE'] = $arrProcess[$index]['C_WORK_DATE'];
					$arrProcessNew[$j]['C_RESULT'] = $arrProcess[$index]['C_RESULT'];
					$arrProcessNew[$j]['C_STAFF_POSITION_NAME'] = $arrProcess[$index]['C_STAFF_POSITION_NAME'];
					$arrProcessNew[$j]['C_UNIT_NAME'] = $arrProcess[$index]['C_UNIT_NAME'];
					$arrProcessNew[$j]['C_FILE_NAME'] = $arrProcess[$index]['C_FILE_NAME'];
					$arrProcessNew[$j]['FK_STAFF'] = $arrProcess[$index]['FK_STAFF'];
					$j = $j +1;
				}else{
					$arrProcessOld[$k]['PK_DOC_WORK'] = $arrProcess[$index]['PK_DOC_WORK'];
					$arrProcessOld[$k]['C_WORK_DATE'] = $arrProcess[$index]['C_WORK_DATE'];
					$arrProcessOld[$k]['C_RESULT'] = $arrProcess[$index]['C_RESULT'];
					$arrProcessOld[$k]['C_STAFF_POSITION_NAME'] = $arrProcess[$index]['C_STAFF_POSITION_NAME'];
					$arrProcessOld[$k]['C_UNIT_NAME'] = $arrProcess[$index]['C_UNIT_NAME'];
					$arrProcessOld[$k]['C_FILE_NAME'] = $arrProcess[$index]['C_FILE_NAME'];
					$arrProcessOld[$k]['FK_STAFF'] = $arrProcess[$index]['FK_STAFF'];
					$k = $k +1;
				}
				$iFkStaff =  $arrProcess[$index]['FK_STAFF'];
			}
		$arrAssign	=	$objSent->docAssignGetSingle($sentID,$iDepartmentId);	
		//var_dump($arrAssign);
		$this->view->arrAssign = $arrAssign;	
		// Dua ra man hinh danh sach 
		$this->view->arrProcessNew = $arrProcessNew;
		//var_dump($arrProcessNew);
		$this->view->arrProcessOld = $arrProcessOld;
		//var_dump($arrProcessOld);

	}
	public function viewAction(){	
		$this->view->bodyTitle = 'CHI TIẾT VĂN BẢN DỰ THẢO';
		$ojbSysLib 		  = new Sys_Library();
		$objSent      	  = new Sent_modSent();	
		$objDocFun  	  = new Sys_Function_DocFunctions();
		$ojbSysInitConfig = new Sys_Init_Config();
		$sGetModulLeft = $this->_request->getParam('hdn_function_modul',0);
		$this->view->getModulLeft = $sGetModulLeft;
			//lay ID va ten don vi soan thao 
		$iOwnerName = $objDocFun->getNameUnitByIdUnitList($_SESSION['OWNER_ID']);
		$this->view->iOwnerName = $iOwnerName;
		$iDepartmentId  = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');
		$iUserId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
		$arrNature = $objSent->getPropertiesDocument('DM_TINH_CHAT_VB');
		$this->view->arrNature = $arrNature;
		//Lay cac gia tri const
		$this->view->arrConst =	$ojbSysInitConfig->_setProjectPublicConst();
		//Nhan bien truyen vao tu form
		$sFullTextSearch = $this->_request->getParam('FullTextSearch','');
		$this->view->sFullTextSearch = $sFullTextSearch;
		//Lay Id doi tuong 
		$sentID = $this->_request->getParam('hdn_object_id','');	
		$this->view->sentID = $sentID;
		//echo $sentID;
		//Lay thong tin VB di va gui ra View
		$arrSent = $objSent->docDraftGetSingle($sentID,$iUserId,$iDepartmentId);
		$this->view->arrSent = $arrSent;
		$arrRelate=$objSent->docRelateGetAll($sentID,'','');
		$this->view->arrRelate = $arrRelate;
		//Tuy chon ung voi cac truong hop update du lieu	
		$psOption = $this->_request->getParam('hdh_option','');
		$this->view->option = $psOption;
		
	}
	public function printAction(){	
		$objDocFun 		  = new Sys_Function_DocFunctions();	
		$ojbSysInitConfig = new Sys_Init_Config();
		$ojbSysLib        = new Sys_Library();	
		$filter           = new Zend_Filter();	
		$objSent  	      = new Sent_modSent();	
		$objList  	      = new Listxml_modList();		
		$sentID = $this->_request->getParam('hdn_object_id','');	
			//lay ID va ten don vi soan thao 
		$iDepartmentId  = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');
		$sDepartmentName= Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_unit'],$iDepartmentId,'name');
		$this->view->sDepartmentName = $sDepartmentName;
		$iUserId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
		$sUserId = Sys_Function_DocFunctions::getNamePositionStaffByIdList($iUserId);
		//Lay thong tin VB di va gui ra View
		$arrSent = $objSent->docDraftGetSingle($sentID,$iUserId,$iDepartmentId);
		//Lay file dinh kem
		$strFileName 				= $arrSent[0]['C_FILE_NAME'];
		$sFile 						= Sys_Library::_getAllFileAttach($strFileName,"!#~$|*","!~!",$this->_request->getBaseUrl() . "/public/attach-file/");
		//Lay duong dan
		$path = $_SERVER['SCRIPT_FILENAME'];
		$path = substr($path, 0, -9);
		$my_report = str_replace("/", "\\", $path) . "rpt\\sent\\draff.rpt";
		// Tao doi tuong Crystal 9
		$COM_Object = "CrystalDesignRunTime.Application.9";		
		$crapp= new COM($COM_Object) or die("Unable to Create Object");
		$creport = $crapp->OpenReport($my_report, 1);	
		//$creport->DiscardSavedData;
		$creport->ReadRecords();
							
		//Lay danh sach so VB di
		$arrInputBooks = $objSent->getPropertiesDocument('DM_SO_VAN_BAN_DI');
		//Lay danh sach tinh chat VB
		$arrNature = $objSent->getPropertiesDocument('DM_TINH_CHAT_VB');
		//
		$arrUnitName = explode('; !#~$|*', $arrSent[0]['C_IDEA_NAME']);
		// Truyen tham so vao		
		$creport->ParameterFields(1)->SetCurrentValue($arrSent[0]['C_DOC_TYPE']);
		$creport->ParameterFields(2)->SetCurrentValue($arrSent[0]['C_SENT_DATE']);	
		$creport->ParameterFields(3)->SetCurrentValue($arrSent[0]['C_SUBJECT']);
		$creport->ParameterFields(4)->SetCurrentValue($arrSent[0]['C_DOC_CATE']);
		$creport->ParameterFields(5)->SetCurrentValue(Sys_Library::_getNameByCode($arrNature,$arrSent[0]['C_NATURE'],'C_NAME'));
		$creport->ParameterFields(6)->SetCurrentValue($arrSent[0]['C_TEXT_OF_EMERGENCY']);
		$creport->ParameterFields(7)->SetCurrentValue($arrSent[0]['SO_BAN']);
		$creport->ParameterFields(8)->SetCurrentValue($arrSent[0]['SO_TRANG']);
		$creport->ParameterFields(9)->SetCurrentValue((string)$sFile); 
		$creport->ParameterFields(10)->SetCurrentValue($sDepartmentName);
		$creport->ParameterFields(11)->SetCurrentValue($sUserId);
		$creport->ParameterFields(12)->SetCurrentValue($arrSent[0]['C_RECEIVE_PLACE']);
		$creport->ParameterFields(13)->SetCurrentValue($arrSent[0]['C_SIGNER_POSITION_NAME']); //ng uoi ky
		$creport->ParameterFields(14)->SetCurrentValue($arrUnitName[1]);//chuyen vien soan thao
		$creport->ParameterFields(15)->SetCurrentValue($arrUnitName[0]);	
		$creport->ParameterFields(16)->SetCurrentValue($arrSent[0]['C_APPOINTED_DATE']);
		//Ten file
		$report_file = 'draff.doc';
		// Duong dan file report
		$my_report_file = str_replace("/", "\\", $path) . "public\\" . $report_file;
		//export to PDF process
		$creport->ExportOptions->DiskFileName=$my_report_file; //export to file 
		$creport->ExportOptions->PDFExportAllPages=true;
		$creport->ExportOptions->DestinationType = 1; // export to file
		$creport->ExportOptions->FormatType= 14;
		$creport->Export(false);
		$my_report_file = 'http://'.$_SERVER['HTTP_HOST'].':8080/sys-doc-v3.0/public/' . $report_file;
		$this->view->my_report_file = $my_report_file;
		//
		//Luu cac gia tri can thiet de luu vet truoc khi thuc hien (ID loai danh muc; Trang hien thoi; So record/page)
		$arrParaSet = array("hdn_id_listtype"=>$iListTypeId, "sel_page"=>$piCurrentPage, "cbo_nuber_record_page"=>$piNumRowOnPage,"hdn_filter_xml_tag_list"=>$psFilterXmlTagList,"hdn_filter_xml_value_list"=>$psFilterXmlValueList);						
		//Luu gia tri vao bien session de indexAction lay lai ket qua chuyen cho View (Dieu kien loc)					
		$_SESSION['seArrParameter'] = $arrParaSet;
		//Luu bien ket qua
		$this->_request->setParams($arrParaSet);

		//Tro ve trang index												
		//$this->_redirect('Invitation/CreateInvitation/index/');
	}
	public function deleteAction(){	
		$objSent   = new Sent_modSent();	
		//Lay Id doi tuong can xoa
		$sListId = $this->_request->getParam('hdn_object_id_list',"");
		$sSentId = $this->_request->getParam('hdn_object_id',"");
			
		//Goi phuong thuc xoa doi tuong
		$objSent->docDraftProcessDelete($sListId);
		$sGetModulLeft = $this->_request->getParam('modul',0);
		$this->_redirect('sent/process/add/modul/'.$sGetModulLeft.'/?hdn_object_id='.$sSentId);	
	}	
} ?>
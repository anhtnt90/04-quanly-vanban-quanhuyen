<?php
/**
 * Nguoi tao: phongtd
 * Ngay tao: 15/09/2009
 * Y nghia: Class Xu ly VB den
 */	
class SentReceive_ReceivedocumentController extends  Zend_Controller_Action {
	
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
		Zend_Loader::loadClass('Received_modReceived');
		
		
		$this->view->JSPublicConst = $objConfig->_setJavaScriptPublicVariable();		
		
		// Load tat ca cac file Js va Css
		$this->view->LoadAllFileJsCss = Sys_Publib_Library::_getAllFileJavaScriptCss('','js','received.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','ajax.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','jquery-1.4.2.min.js',',','js'). Sys_Publib_Library::_getAllFileJavaScriptCss('','js/LibSearch','actb_search.js,common_search.js',',','js');

		// Ham lay thong tin nguoi dang nhap hien thi tai Lefmenu
		$this->view->InforStaff = Sys_Publib_Library::_InforStaff();
		
		//Dinh nghia current modul code
		$this->view->currentModulCode = "SENTRECEIVED";
		$this->view->currentModulCodeForLeft = "DOCUMENT-SENTRECEIVED-DOC";
		//Lay Quyen cap nhat VB DEN
		$this->_publicPermission = Sys_Function_DocFunctions::Doc_ArchivesStaff($_SESSION['arrStaffPermission'],$_SESSION['staff_id']);
		//echo $this->_publicPermission;
	
		$response->insert('header', $this->view->renderLayout('twd_header.phtml','template/'));    
			$response->insert('left', $this->view->renderLayout('twd_left.phtml','template/'));  	    
	        $response->insert('footer', $this->view->renderLayout('twd_footer.phtml','template/'));
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
		$this->view->bodyTitleSearch = "TÌM KIẾM VĂN BẢN";				
		// Tieu de man hinh danh sach
		$this->view->bodyTitle = "DANH SÁCH VĂN BẢN ĐẾN";
		
		// Tao doi tuong Zend_Filter
		$objFilter = new Zend_Filter();
		$ojbSysLib = new Sys_Library();
		// Lay toan bo tham so truyen tu form			
		$arrInput = $this->_request->getParams();		
		
		$objReceive = new Received_modReceived();
		$ReceivedDate = date("d/m/Y");
		$this->view->ReceivedDate = $ReceivedDate;
		
		$piCurrentPage = $this->_request->getParam('hdn_current_page',0);		
		if ($piCurrentPage <= 1){
			$piCurrentPage = 1;
		}
		$this->view->currentPage = $piCurrentPage; //Gan gia tri vao View
		
		//Lay thong tin quy dinh so row / page
		$piNumRowOnPage = 15;
		$piNumRowOnPage = $objFilter->filter($arrInput['hdn_record_number_page']);		
		if ($piNumRowOnPage <= $this->view->NumberRowOnPage){
			$piNumRowOnPage = $this->view->NumberRowOnPage;
		}		
		$this->view->numRowOnPage = $piNumRowOnPage; //Gan gia tri vao View
		
		$pSymbol = $this->_request->getParam('txtNumSymbol','');
		$pFromDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($this->_request->getParam('fromDate',''));
		$pToDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($this->_request->getParam('toDate',''));
		$pSubject = $this->_request->getParam('txtSubject','');
		
		//Lay MA DON VI NSD dang nhap hien thoi
		$sOwnerCode = $_SESSION['OWNER_CODE'];
		//Lay Quyen cap nhat VB DEN		
		$pRoles = "";
		if ($this->_publicPermission){
			$pRoles = 'NHOM_VANTHU';
			$StaffId = "";
			if($this->_publicPermission == 1){ //Neu la VT BO				
				$sOwnerCode = 'BXD';
				//$this->view->bodyTitle = "DANH SÁCH VĂN BẢN ĐẾN BỘ";
			}
		}	
		
		//Kiem tra xem NSD co duoc ban quyen tren UNG DUNG khong?
		if($sOwnerCode == '' && $sOwnerCode == null){
			$sOwnerCode = 'NO';
		}
			
		$arrResul = $objReceive->ReceiveDocumentGetAll($StaffId ,'',$pFromDate,$pToDate, '', $pSymbol, $pSubject,$sOwnerCode,$piCurrentPage,$piNumRowOnPage, $pRoles,'','');			
		$psNumberRecord = $arrResul[0]['C_TOTAL_RECORD'];	
		
		//Hien thi thong tin man hinh danh sach nay co bao nhieu ban ghi va hien thi Radio "Chon tat ca"; "Bo chon tat ca"
		$this->view->SelectDeselectAll = Sys_Publib_Library::_selectDeselectAll(sizeof($arrResul), $psNumberRecord);
		
		if (count($arrResul) > 0){
			//Sinh xau HTML mo ta so trang (Trang 1; Trang 2;...)
			$this->view->generateStringNumberPage = Sys_Publib_Library::_generateStringNumberPage($psNumberRecord, $piCurrentPage, $piNumRowOnPage,$pUrl) ;
			//Sinh chuoi HTML mo ta tong so trang (Trang 1; Trang 2;...) va quy dinh so record/page
			$this->view->generateHtmlSelectBoxPage = Sys_Publib_Library::_generateChangeRecordNumberPage($piNumRowOnPage,"index");
		}

		//var_dump($arrResul);
		$this->view->arrResul = $arrResul;
		
		$this->view->NumberRowOnPage 	= $this->_ConstPublic['NumberRowOnPage'];	
		$this->view->iCountElement = count($this->view->arrAllRecord);
	}
	/**
	 * Idea : Phuong thuc them moi mot VB
	 *
	 */
	public function addAction(){
		$this->view->bodyTitle = 'VÀO SỔ VĂN BẢN ĐẾN';
		$arrInput = $this->_request->getParams();
		$objDocFun = new Sys_Function_DocFunctions();
		$objReceive = new Received_modReceived();
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
		
		$arrSel = $objReceive->getPropertiesDocument('DM_TINH_CHAT_VB');
		$this->view->arrSel = $arrSel;
		
		$arrUrgent = $objReceive->getPropertiesDocument('DM_DO_KHAN_VAN_BAN');
		$this->view->arrUrgent = $arrUrgent;
		
		$arrInputBooks = $objReceive->getPropertiesDocument('DM_SO_VAN_BAN_DEN');
		$this->view->arrInputBooks = $arrInputBooks;
		$arrSentLevel = $objReceive->getPropertiesDocument('DM_CAP_NOI_GUI_VAN_BAN');
		$this->view->arrSentLevel = $arrSentLevel;
		
		$arrSentPlace = $objReceive->getPropertiesDocument('DM_NOI_GUI_VAN_BAN');
		$this->view->arrSentPlace = $arrSentPlace;
		
		$arrNhomVB = $objReceive->getPropertiesDocument('DM_NHOM_LOAI_VB');
		$this->view->arrNhomVB = $arrNhomVB;
		
		$arrLoaiVB = $objReceive->getPropertiesDocument('DM_LOAI_VAN_BAN');
		$this->view->arrLoaiVB = $arrLoaiVB;
		
		$arrSigner = $objReceive->getPropertiesDocument('DM_NGUOI_KY');
		$this->view->arrSigner = $arrSigner;
		
		$arrLinhVuc = $objReceive->getPropertiesDocument('DM_LINH_VUC_VAN_BAN');
		$this->view->arrLinhVuc = $arrLinhVuc;
		
		$arrProcessType = $objReceive->getPropertiesDocument('DM_HINH_THUC_XU_LY');
		$this->view->arrProcessType = $arrProcessType;
		
		$arrOwnerUser = $objReceive->getPropertiesDocument('DM_DONVI_SUDUNG');
		$this->view->arrOwnerUser = $arrOwnerUser;
		// Goi ham search
		$this->view->search_textselectbox_received_place = Sys_Function_DocFunctions::doc_search_ajax($arrOwnerUser,"C_CODE","C_NAME","C_RECEIVED_PLACE","hdn_received_place");

		
		//Gan quyen sang VIEW
		$this->view->PermissionUser = $this->_publicPermission;
		
		//Tuy chon ung voi cac truong hop update du lieu	
		$psOption = $this->_request->getParam('hdh_option','');
		$this->view->option = $psOption;
		//echo "option:" . $psOption . "<br>";
		
		//Lay MA DON VI NSD dang nhap hien thoi
		$sOwnerCode = $_SESSION['OWNER_CODE'];
		$this ->view->Owner = $sOwnerCode;
		//Lay Quyen cap nhat VB DEN		
		$pRoles = "";
		if($this->_publicPermission == 1){//Neu la VT BO
			$pRoles = 'NHOM_VTBO';
			$sOwnerCode = 'BXD';
			$this ->view->Owner = $sOwnerCode;
			$arrLeader = $objDocFun->docGetAllUnitLeader('LANH_DAO_BO','arr_all_staff');
			$this->view->arrLeader = $arrLeader;
			
			//Lay mang chua DON VI SU DUNG tu DM_DONVI_SUDUNG			
			$UnitList = $_SESSION['SesGetAllOwner'];
			$this->view->UnitList = $UnitList;
		}
		
		if($this->_publicPermission != 1){//Neu khong phai la VT BO
			//Lay Lanh dao DON VI
			$arrLeader = $objDocFun->docGetAllUnitLeader('LANH_DAO_CUC,LANH_DAO_VU,LANH_DAO_TT_VIEN,LANH_DAO_DONVI','arr_all_staff_department');
			$this->view->arrLeader = $arrLeader;
			
			//Lay mang chua danh sach NHAN VIEN			
			$arrStaffDepartment = $_SESSION['arr_all_staff_department'];
			$this->view->arrStaffDepartment = $arrStaffDepartment;
			
			//Phong ban thuoc don vi
			$this->view->UnitList = $_SESSION['arr_all_department'];			
		}	
		
		$psXmlTagValueList = $this->_request->getParam('hdn_XmlTagValueList','');
		//Tao xau XML luu CSDL
		if ($psXmlTagValueList != ""){
			$arrXmlTagValue = explode("|{*^*}|",$psXmlTagValueList);
			if($arrXmlTagValue[0] != "" && $arrXmlTagValue[1] != ""){
				//Danh sach THE
				$psXmlTagList = $arrXmlTagValue[0];
				//Danh sach GIA TRI
				$psXmlValueList = $arrXmlTagValue[1];
				//Tao xau XML luu CSDL					
				$psXmlStringInDb = $ojbXmlLib->_xmlGenerateXmlDataString($psXmlTagList, $psXmlValueList);					
			}
		}
		
		$this->view->AttachFile = $objDocFun->DocSentAttachFile(array(),0,10,true,70);
		
		if ($objFilter->filter($arrInput['C_SUBJECT']) != ""){	
				
				$pStatus = 'CHO_PHAN_PHOI';	
				//Danh sach LANH DAO DAO DON VI cho Y KIEN CHI DAO
				$sLeaderIdList = substr($objFilter->filter($arrInput['ds_lanh_dao']),0,-1);
				if($sLeaderIdList != null || $sLeaderIdList !="" ){
					$pStatus = 'CHO_PHAN_CONG';	
				}
				//Lay danh sach Y KIEN LANH DAO
				$sLeaderIdeaList = substr($objFilter->filter($arrInput['ds_y_kien']),0,-6);
				//Lay ID cua DON VI XU LY CHINH
				$pMainProcessUnitId = $objFilter->filter($arrInput['FK_PROCESS_UNIT']);
				if($pMainProcessUnitId != null || $pMainProcessUnitId !="" ){
					$pStatus = 'DA_PHAN_CONG';	
				}
				if($objFilter->filter($arrInput['FK_PROCESSOR'] != "")){
					$pStatus = 'CAN_XU_LY';
				}
				//Lay danh sach DON VI PHOI HOP XU LY 
				$pCombineProcessUnitIdList = $objFilter->filter($arrInput['ds_don_vi']);
				//Danh sach ID cac DON VI XU LY 
				$pUnitIdList = $pMainProcessUnitId . "," . $pCombineProcessUnitIdList; 	
				//Danh sach MA cac DON VI XU LY
				$pProcessUnitCodeList = substr($objDocFun -> docGetUnitCodeListByUnitIdList($pUnitIdList),6);	
				//Thuc hien upload file len o cung toi da 10 file
				$arrFileNameUpload = $ojbSysLib->_uploadFileList(10,$this->_request->getBaseUrl() . "/public/attach-file/",'FileName','@!~!@');
				//Mang luu tham so update in database		
				$arrParameter = array(	
									'PK_RECEIVED_DOC'				=>'',										
									'C_SYMBOL'						=>$objFilter->filter($arrInput['C_SYMBOL']),
									'C_SEND_LEVEL'					=>$objFilter->filter($arrInput['C_SEND_LEVEL']),
									'C_SENDING_PLACE'				=>$objFilter->filter($arrInput['cap_noi_gui_van_ban']),
									'C_DOC_CATE'					=>$objFilter->filter($arrInput['C_DOC_CATE']),
									'C_DOC_TYPE'					=>$objFilter->filter($arrInput['nhom_loai_vb']),
									'C_SUBJECT'						=>$objFilter->filter($arrInput['C_SUBJECT']),
									'C_DOCUMENT_BOOKS'				=>$objFilter->filter($arrInput['C_DOCUMENT_BOOKS']),
									'C_NUM_DOCUMENT'				=>$objFilter->filter($arrInput['C_NUM_DOCUMENT']),
									'C_RECEIVED_DATE'				=>$ojbSysLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['C_RECEIVED_DATE'])),
									'C_PROCESSING_TYPE'				=>$objFilter->filter($arrInput['C_PROCESSING_TYPE']),
									'C_STATUS'						=>$pStatus,	
									'C_XML_DATA'					=>$psXmlStringInDb,
									'C_OWNER_CODE'					=>$sOwnerCode,
									'DELETED_EXIST_FILE_ID_LIST'	=>$sDeletedExistFileIdList,
									'NEW_FILE_ID_LIST'				=>$arrFileNameUpload,
									'NEW_FILE_SUBJECT_LIST'			=>'',
									'FK_STAFF_ID'					=>$sLeaderIdList,
									'C_POSITION_NAME'				=>'null',
									'C_LEVEL'						=>'BO_XD',
									'C_IDEA_CONTENT'				=>$sLeaderIdeaList,
									'C_APPOINTED_DATE'				=>$ojbSysLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['han_xu_ly'])),
									'FK_PROCESS_UNIT'				=>$pMainProcessUnitId,
									'FK_UNIT_ID_LIST'				=>$pCombineProcessUnitIdList,
									'FK_UNIT_CODE_LIST'				=>$pProcessUnitCodeList,
									'CONST_LIST_DELIMITOR'			=>'!#~$|*',
									'C_ROLES'						=>$pRoles,
									'FK_PROCESSOR'					=>$objFilter->filter($arrInput['FK_PROCESSOR']),
									'FK_COMBINE_ID_LIST'			=>$ojbXmlLib->_xmlGetXmlTagValue($psXmlStringInDb,'data_list','canbo_phoihop'),	
									'C_MEETING_DATE'				=>$this->_request->getParam('C_MEETING_DATE',''),
									'C_TIME'						=>$this->_request->getParam('C_TIME',''),
									'C_ADDRESS'						=>$this->_request->getParam('C_ADDRESS',''),
									'C_MEETING_PEOPLE'				=>$this->_request->getParam('C_MEETING_PEOPLE',''),
									'C_RECEIVED_PLACE'				=>$this->_request->getParam('C_RECEIVED_PLACE',''),
									'C_DATE_OUT'					=>$ojbSysLib->_ddmmyyyyToYYyymmdd($this->_request->getParam('ngay_phat_hanh',''))
							);
								
				$arrResult = "";
				//var_dump($arrParameter);exit;				
				$arrResult = $objReceive->ReceiveDocumentUpdate($arrParameter);				
				//Luu gia tri												
				$arrParaSet = array("sel_page"=>$piCurrentPage, "cbo_nuber_record_page"=>$piNumRowOnPage);
				//var_dump($arrParaSet); exit;
				$_SESSION['seArrParameter'] = $arrParaSet;
				$this->_request->setParams($arrParaSet);
			
				//Truong hop ghi va them moi
				if ($psOption == "GHI_THEMMOI"){
					//Ghi va quay lai chinh form voi noi dung rong						
					$this->_redirect('Received/documents/add/');
				}	
				
				//Truong hop ghi va them tiep
				if ($psOption == "GHI_THEMTIEP"){
					$this->view->pReceiveDocumentId = $arrResult;
					//
					$this->view->option = $psOption;
					//Ghi va quay lai chinh form voi noi dung rong						
					$this->_redirect('Received/documents/edit/hdn_object_id/' . $arrResult);
				}

				//Truong hop ghi nhan
				if ($psOption == "GHI_NHAN"){
					//Lay ID VB vua moi insert vao DB
					$this->view->pReceiveDocumentId = $arrResult;
					//
					$this->view->option = $psOption;
					//Ghi va quay lai chinh form voi noi dung rong						
					$this->_redirect('Received/documents/edit/hdn_object_id/' . $arrResult);
				}
	
				//Truong hop ghi va quay lai
				if ($psOption == "GHI_QUAYLAI"){
					//Tro ve trang index						
					$this->_redirect('Received/documents/index/');	
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
		$objReceive = new Received_modReceived();
		$ojbXmlLib = new Sys_Xml();
		$ojbSysLib = new Sys_Library();
		$objFilter = new Zend_Filter();
		$ojbSysInitConfig = new Sys_Init_Config();	
		//Lay thong tin history back
		$this->view->historyBack = 'index/';	
		//Lay tham so cau hinh
		$sysLibUrlPath = $ojbSysInitConfig->_setLibUrlPath();
		$url_path_calendar = $sysLibUrlPath . 'sys-calendar/';
		$this->view->urlCalendar = $url_path_calendar;
		
		$arrSel = $objReceive->getPropertiesDocument('DM_TINH_CHAT_VB');
		$this->view->arrSel = $arrSel;
		
		$arrUrgent = $objReceive->getPropertiesDocument('DM_DO_KHAN_VAN_BAN');
		$this->view->arrUrgent = $arrUrgent;
		
		$arrInputBooks = $objReceive->getPropertiesDocument('DM_SO_VAN_BAN_DEN');
		$this->view->arrInputBooks = $arrInputBooks;
		
		$arrSentLevel = $objReceive->getPropertiesDocument('DM_CAP_NOI_GUI_VAN_BAN');
		$this->view->arrSentLevel = $arrSentLevel;
		
		$arrSentPlace = $objReceive->getPropertiesDocument('DM_NOI_GUI_VAN_BAN');
		$this->view->arrSentPlace = $arrSentPlace;
		
		$arrNhomVB = $objReceive->getPropertiesDocument('DM_NHOM_LOAI_VB');
		$this->view->arrNhomVB = $arrNhomVB;
		
		$arrLoaiVB = $objReceive->getPropertiesDocument('DM_LOAI_VAN_BAN');
		$this->view->arrLoaiVB = $arrLoaiVB;
		
		$arrSigner = $objReceive->getPropertiesDocument('DM_NGUOI_KY');
		$this->view->arrSigner = $arrSigner;
		
		$arrLinhVuc = $objReceive->getPropertiesDocument('DM_LINH_VUC_VAN_BAN');
		$this->view->arrLinhVuc = $arrLinhVuc;
		$arrOwnerUser = $objReceive->getPropertiesDocument('DM_DONVI_SUDUNG');
		$this->view->arrOwnerUser = $arrOwnerUser;
		// Goi ham search
		$this->view->search_textselectbox_received_place = Sys_Function_DocFunctions::doc_search_ajax($arrOwnerUser,"C_CODE","C_NAME","C_RECEIVED_PLACE","hdn_received_place");
		$arrProcessType = $objReceive->getPropertiesDocument('DM_HINH_THUC_XU_LY');
		$this->view->arrProcessType = $arrProcessType;
		
		//Tuy chon ung voi cac truong hop update du lieu	
		$psOption = $this->_request->getParam('hdh_option','');
		$this->view->option = $psOption;
		//echo "option:" . $psOption . "<br>";
		
		//Gan quyen sang VIEW
		$this->view->PermissionUser = $this->_publicPermission;
		
		//Lay MA DON VI NSD dang nhap hien thoi
		$sOwnerCode = $_SESSION['OWNER_CODE'];
		$this ->view->Owner = $sOwnerCode;
		//Lay Quyen cap nhat VB DEN		
		$pRoles = "";
		if($this->_publicPermission == 1){//Neu la VT BO
			$pRoles = 'NHOM_VTBO';
			$sOwnerCode = 'BXD';
			$this ->view->Owner = $sOwnerCode;
			$arrLeader = $objDocFun->docGetAllUnitLeader('LANH_DAO_BO','arr_all_staff');
			$this->view->arrLeader = $arrLeader;
			
			//Lay mang chua DON VI SU DUNG tu DM_DONVI_SUDUNG			
			$UnitList = $_SESSION['SesGetAllOwner'];
			$this->view->UnitList = $UnitList;
		}		
		if($this->_publicPermission != 1){//Neu khong phai la VT BO
			//Lay Lanh dao DON VI
			$arrLeader = $objDocFun->docGetAllUnitLeader('LANH_DAO_CUC,LANH_DAO_VU,LANH_DAO_TT_VIEN,LANH_DAO_DONVI','arr_all_staff_department');
			$this->view->arrLeader = $arrLeader;
			
			//Lay mang chua danh sach NHAN VIEN			
			$arrStaffDepartment = $_SESSION['arr_all_staff_department'];
			$this->view->arrStaffDepartment = $arrStaffDepartment;
			
			//Phong ban thuoc don vi
			$this->view->UnitList = $_SESSION['arr_all_department'];			
		}		
		//
		$pReceiveDocumentId = $this->_request->getParam('hdn_object_id','');		
		$this->view->pReceiveDocumentId = $pReceiveDocumentId;
		$arrReceived = $objReceive->ReceiveDocumentGetSingle($pReceiveDocumentId);
		//var_dump($arrReceived);
		$this->view->arrReceived = $arrReceived;
		//Thuc hien upload file len o cung toi da 10 file
		$arrFileNameUpload = $ojbSysLib->_uploadFileList(10,$this->_request->getBaseUrl() . "/public/attach-file/",'FileName','@!~!@');
		$arFileAttach = $objReceive->DOC_GetAllDocumentFileAttach($arrReceived[0]['FK_DOC'],'','T_DOC_RECEIVED_DOCUMENT');	
		$this->view->AttachFile = $objDocFun->DocSentAttachFile($arFileAttach,sizeof($arFileAttach),10,true,45);	
		if($pReceiveDocumentId != '' && $pReceiveDocumentId != null && $this->_request->isPost() && $psOption != "QUAY_LAI"){
			$arrInput = $this->_request->getParams();	
			$psXmlTagValueList = $this->_request->getParam('hdn_XmlTagValueList','');
			//Tao xau XML luu CSDL
			if ($psXmlTagValueList != ""){
				$arrXmlTagValue = explode("|{*^*}|",$psXmlTagValueList);
				if($arrXmlTagValue[0] != "" && $arrXmlTagValue[1] != ""){
					//Danh sach THE
					$psXmlTagList = $arrXmlTagValue[0];
					//Danh sach GIA TRI
					$psXmlValueList = $arrXmlTagValue[1];
					//Tao xau XML luu CSDL					
					$psXmlStringInDb = $ojbXmlLib->_xmlGenerateXmlDataString($psXmlTagList, $psXmlValueList);					
				}
			}
			if ($objFilter->filter($arrInput['C_SUBJECT']) != ""){						
				$pStatus = 'CHO_PHAN_PHOI';	
				//Danh sach LANH DAO DAO DON VI cho Y KIEN CHI DAO
				$sLeaderIdList = substr($objFilter->filter($arrInput['ds_lanh_dao']),0,-1);
				if($sLeaderIdList != null || $sLeaderIdList !="" ){
					$pStatus = 'CHO_PHAN_CONG';	
				}
				//Lay danh sach Y KIEN LANH DAO
				$sLeaderIdeaList = substr($objFilter->filter($arrInput['ds_y_kien']),0,-6);
				//Lay ID cua DON VI XU LY CHINH
				$pMainProcessUnitId = $objFilter->filter($arrInput['FK_PROCESS_UNIT']);
				if($pMainProcessUnitId != null || $pMainProcessUnitId !="" ){
					$pStatus = 'DA_PHAN_CONG';	
				}
				if($objFilter->filter($arrInput['FK_PROCESSOR'] != "")){
					$pStatus = 'CAN_XU_LY';
				}
				//Lay danh sach DON VI PHOI HOP XU LY 
				$pCombineProcessUnitIdList = $objFilter->filter($arrInput['ds_don_vi']);
				//Danh sach ID cac DON VI XU LY 
				$pUnitIdList = $pMainProcessUnitId . "," . $pCombineProcessUnitIdList; 	
				//Danh sach MA cac DON VI XU LY
				$pProcessUnitCodeList = substr($objDocFun -> docGetUnitCodeListByUnitIdList($pUnitIdList),6);	
				
				//Neu la ghi va them tiep thi gan ID VB lay duoc = "" de them moi mot VB
				if ($psOption == "GHI_THEMTIEP"){
					$pReceiveDocumentId = "";
				}
			
				//Mang luu tham so update in database		
				$arrParameter = array(	
									'PK_RECEIVED_DOC'				=>$pReceiveDocumentId,										
									'C_SYMBOL'						=>$objFilter->filter($arrInput['C_SYMBOL']),
									'C_SEND_LEVEL'					=>$objFilter->filter($arrInput['C_SEND_LEVEL']),
									'C_SENDING_PLACE'				=>$objFilter->filter($arrInput['cap_noi_gui_van_ban']),
									'C_DOC_CATE'					=>$objFilter->filter($arrInput['C_DOC_CATE']),
									'C_DOC_TYPE'					=>$objFilter->filter($arrInput['nhom_loai_vb']),
									'C_SUBJECT'						=>$objFilter->filter($arrInput['C_SUBJECT']),
									'C_DOCUMENT_BOOKS'				=>$objFilter->filter($arrInput['C_DOCUMENT_BOOKS']),
									'C_NUM_DOCUMENT'				=>$objFilter->filter($arrInput['C_NUM_DOCUMENT']),
									'C_RECEIVED_DATE'				=>$ojbSysLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['C_RECEIVED_DATE'])),
									'C_PROCESSING_TYPE'				=>$objFilter->filter($arrInput['C_PROCESSING_TYPE']),
									'C_STATUS'						=>$pStatus,	
									'C_XML_DATA'					=>$psXmlStringInDb,
									'C_OWNER_CODE'					=>$sOwnerCode,
									'DELETED_EXIST_FILE_ID_LIST'	=>$sDeletedExistFileIdList,
									'NEW_FILE_ID_LIST'				=>$arrFileNameUpload,
									'NEW_FILE_SUBJECT_LIST'			=>'',
									'FK_STAFF_ID'					=>$sLeaderIdList,
									'C_POSITION_NAME'				=>'null',
									'C_LEVEL'						=>'BO_XD',
									'C_IDEA_CONTENT'				=>$sLeaderIdeaList,
									'C_APPOINTED_DATE'				=>$ojbSysLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['han_xu_ly'])),
									'FK_PROCESS_UNIT'				=>$pMainProcessUnitId,
									'FK_UNIT_ID_LIST'				=>$pCombineProcessUnitIdList,
									'FK_UNIT_CODE_LIST'				=>$pProcessUnitCodeList, 
									'CONST_LIST_DELIMITOR'			=>'!#~$|*',
									'C_ROLES'						=>trim($pRoles),
									'FK_PROCESSOR'					=>$objFilter->filter($arrInput['FK_PROCESSOR']),
									'FK_COMBINE_ID_LIST'			=>$ojbXmlLib->_xmlGetXmlTagValue($psXmlStringInDb,'data_list','canbo_phoihop'),	
									'C_MEETING_DATE'				=>$this->_request->getParam('C_MEETING_DATE',''),
									'C_TIME'						=>$this->_request->getParam('C_TIME',''),
									'C_ADDRESS'						=>$this->_request->getParam('C_ADDRESS',''),
									'C_MEETING_PEOPLE'				=>$this->_request->getParam('C_MEETING_PEOPLE',''),
									'C_RECEIVED_PLACE'				=>$this->_request->getParam('C_RECEIVED_PLACE',''),
									'C_DATE_OUT'					=>$ojbSysLib->_ddmmyyyyToYYyymmdd($this->_request->getParam('ngay_phat_hanh',''))
							);
								
				$arrResult = "";
				//echo htmlspecialchars($psXmlStringInDb) . '<br>';
				//var_dump($arrParameter);exit;
				if($objFilter->filter($arrInput['C_SUBJECT']) != ''){				
					$arrResult = $objReceive->ReceiveDocumentUpdate($arrParameter);	
					//var_dump($arrResult);		exit;				
					//Luu gia tri												
					$arrParaSet = array("sel_page"=>$piCurrentPage, "cbo_nuber_record_page"=>$piNumRowOnPage);
					//var_dump($arrParaSet); exit;
					$_SESSION['seArrParameter'] = $arrParaSet;
					$this->_request->setParams($arrParaSet);
				
					
				}			
			}
		}	
		//Truong hop ghi va them moi
		if ($psOption == "GHI_THEMMOI"){
			//Ghi va quay lai chinh form voi noi dung rong						
			$this->_redirect('Received/documents/add/');
		}	
		
		//Truong hop ghi va them tiep
		if ($psOption == "GHI_THEMTIEP"){
			$this->view->pReceiveDocumentId = $arrResult;
			//echo $arrResult;
			//exit;
			$this->view->option = $psOption;
			//Ghi va quay lai chinh form voi noi dung rong						
			$this->_redirect('Received/documents/edit/hdn_object_id/' . $arrResult);
		}
		
		//Truong hop ghi nhan
		if ($psOption == "GHI_NHAN"){
			//Lay ID VB vua moi insert vao DB
			$this->view->pReceiveDocumentId = $arrResult;
			//
			$this->view->option = $psOption;
			//Ghi va quay lai chinh form voi noi dung rong						
			$this->_redirect('Received/documents/edit/hdn_object_id/' . $arrResult);
		}
		
		//Truong hop ghi va quay lai
		if ($psOption == "GHI_QUAYLAI"){
			//Tro ve trang index						
			$this->_redirect('Received/documents/index/');	
		}	
		if ($psOption == "QUAY_LAI"){
			//Tro ve trang index						
			$this->_redirect('Received/documents/index/');	
		}	
	}
	/**
	 * Idea : Phuong thuc xoa mot VB
	 *
	 */
	public function deleteAction(){
		
		// Tao doi tuong cho lop xu ly du lieu lien quan modul	
		$objReceive = new Received_modReceived();
		// Thuc hien lay du lieu tu form 		
		if($this->_request->isPost()){
			
			// Lay toan bo tham so truyen tu form			
			$arrInput = $this->_request->getParams();
						
			//Lay thong tin trang hien thoi
			$piCurrentPage = $this->_request->getParam('hdn_current_page',0);
			$this->view->currentPage	= $piCurrentPage;
			
			//Lay thong tin quy dinh so row / page
			$piNumRowOnPage = $this->_request->getParam('hdn_record_number_page',0);
			$this->view->numRowOnPage	= $piNumRowOnPage;
									
			//Lay Id doi tuong VB can xoa
			$pReceiveDocumentIdList = $this->_request->getParam('hdn_object_id_list',"");
			//echo $pReceiveDocumentIdList; exit;
			
			if ($pReceiveDocumentIdList != ""){
				$psRetError = $objReceive->ReceiveDocumentDelete($pReceiveDocumentIdList,1);
				// Neu co loi			
				if($psRetError != null || $psRetError != '' ){											
					echo "<script type='text/javascript'>";
					echo "alert('$psRetError');\n";				
					echo "</script>";
				}else {		
						//Luu cac gia tri can thiet de luu vet truoc khi thuc hien (ID loai danh muc; Trang hien thoi; So record/page)
						$arrParaSet = array("hdn_id_listtype"=>$iListTypeId, "sel_page"=>$piCurrentPage, "cbo_nuber_record_page"=>$piNumRowOnPage,"hdn_filter_xml_tag_list"=>$psFilterXmlTagList,"hdn_filter_xml_value_list"=>$psFilterXmlValueList);						
						//Luu gia tri vao bien session de indexAction lay lai ket qua chuyen cho View (Dieu kien loc)					
						$_SESSION['seArrParameter'] = $arrParaSet;
						//Luu bien ket qua
						$this->_request->setParams($arrParaSet);
	
						//Tro ve trang index												
						$this->_redirect('Received/documents/index/');				
					}
			}
		}		
	}
	/**
	 * Idea : Phuong thuc lay NGUOI XU LY BAO CAO
	 *
	 */
	public function signAction(){
		$this->view->bodyTitle = 'CHỌN NGƯỜI HỌP';

		//An MeneLeft , MenuHeader , MenuFooter	
		$this->view->hideDisplayMeneLeft = "none"; 
		$this->view->hideDisplayMenuHeader = "none";
		$this->view->hideDisplayMenuFooter = "none";
		
		//Lay  thong tin trinh duyet
		$objBrower = new Sys_Publib_Browser();
		$brwName = $objBrower->Name;
		$this->view->brwName = $brwName ;
		
		//Tao doi tuong XML
		$ojbXmlLib = new Sys_Publib_Xml();	
		
		//Tao doi tuong Sys_lib
		$ojbSysLib = new Sys_Library();
		
		// Tao doi tuong Zend_Filter
		$filter = new Zend_Filter();	
					
		// Lay toan bo tham so truyen tu form			
		$arrInput = $this->_request->getParams();
		
		//Lay ten file XML
		$psFileName = $this->_request->getParam('hdn_xml_file','');
		//Neu khong ton tai file XML thi doc file XML mac dinh
		if($psFileName == "" || !is_file($psFileName)){
			$psFileName = Sys_Init_Config::_setXmlFileUrlPath(1) . "exeReports/nguoi_hop.xml";
		}
		
		$psXmlStr = "";
		$arrGetSingleList = array();
		$this->view->generateFormHtml = $ojbXmlLib->_xmlGenerateFormfield($psFileName, 'update_row', $psXmlStr, $arrGetSingleList,true,true);

		//Lay danh sash THE va GIA TRI tuong ung mo ta chuoi XML, cau truc bien hdn_XmlTagValueList luu TagList|{*^*}|ValueList		
		$psXmlTagValueList = $this->_request->getParam('hdn_XmlTagValueList','');	
		//Tao xau XML luu CSDL
		if ($psXmlTagValueList != ""){
			$arrXmlTagValue = explode("|{*^*}|",$psXmlTagValueList);
			if($arrXmlTagValue[0] != "" && $arrXmlTagValue[1] != ""){
				//Danh sach THE
				$psXmlTagList = $arrXmlTagValue[0];
				//Danh sach GIA TRI
				$psXmlValueList = $arrXmlTagValue[1];
				//Tao xau XML luu CSDL					
				$psXmlStringInDb = $ojbXmlLib->_xmlGenerateXmlDataString($psXmlTagList, $psXmlValueList);					
			}
		}		
		// Thuc hien tao mot mang de day vao view
		$this->view->arrInput = $arrInput;	
		//Lay thong tin history back
		$this->view->historyBack = 'add';
	}
}?>
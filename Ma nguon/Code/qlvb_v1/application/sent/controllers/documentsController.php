<?php
class sent_documentsController extends  Zend_Controller_Action {
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
		Zend_Loader::loadClass('Zend_Feed');		
		Zend_Loader::loadClass('Listxml_modList');
		//Lay cac hang so su dung trong JS public
		Zend_Loader::loadClass('Sys_Init_Config');
		$objConfig = new Sys_Init_Config();
		$this->view->JSPublicConst = $objConfig->_setJavaScriptPublicVariable();		
		//Dia chi URL doc file xu ly AJAX
		$this->view->UrlAjax = $objConfig->_setUrlAjax();	
		// Load tat ca cac file Js va Css
		$JSandStyle = Sys_Publib_Library::_getAllFileJavaScriptCss('','js','util.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','sent.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','ajax.js,jquery-1.4.2.min.js,jQuery.equalHeights.js',',','js'). Sys_Publib_Library::_getAllFileJavaScriptCss('','js/LibSearch','actb_search.js,common_search.js',',','js');
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
		//Lay lich ngay/thang/nam
		$sysLibUrlPath = $objConfig->_setLibUrlPath();
		$url_path_calendar = $sysLibUrlPath . 'sys-calendar/';
		$this->view->urlCalendar = $url_path_calendar;	
		//Dinh nghia current modul code
		$this->view->currentModulCode = "SENT";
		$this->view->currentModulCodeForLeft = "DOCUMENTS";
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
		$StaffId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
		$piUnitId = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');
		$arrInput = $this->_request->getParams();
		// Tao doi tuong 
		$ojbSysLib = new Sys_Library();
		$objSent = new Sent_modSent();	
		$objFunction =	new	Sys_Function_DocFunctions()	;
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
		$fromDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($this->_request->getParam('fromDate',''));
		$toDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($this->_request->getParam('toDate',''));
		$sFullTextSearch = $this->_request->getParam('FullTextSearch','');
		//Mac dinh de hien list danh sach trong tuan
		/*
		if($fromDate == '' and $toDate == '' and $sFullTextSearch == ''){
			$fromDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($objFunction->getFirstDayOfWeek() );
			$toDate = date("Y/m/d");
		}	
		*/
		$sFullTextSearch = $ojbSysLib->_replaceBadChar($sFullTextSearch);
		$this->view->sFullTextSearch = $sFullTextSearch;
		$this->view->fromDate =$ojbSysLib->_ddmmyyyyToYYyymmdd($fromDate);
		$this->view->toDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($toDate);
		// Xu li query lay du lieu
		$arrSent = $objSent->docSentGetAll($fromDate,$toDate,$sFullTextSearch,'DA_BAN_HANH',$_SESSION['OWNER_ID'],$piCurrentPage,$piNumRowOnPage);
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
		$this->view->bodyTitle = 'CẬP NHẬT VĂN BẢN ĐI';
		$arrInput = $this->_request->getParams();
		//Tao doi tuong 
		$objSent   = new Sent_modSent();
		$objFilter = new Zend_Filter();	
		$objDocFun = new Sys_Function_DocFunctions();
		$ojbXmlLib = new Sys_Publib_Xml();
		$ojbSysLib = new Sys_Library();
		$objList   = new Listxml_modList();
		$ojbSysInitConfig = new Sys_Init_Config();
		//Lay cac gia tri const
		$this->view->arrConst =	$ojbSysInitConfig->_setProjectPublicConst();
		//Lay danh sach so VB di
		$arrInputBooks = $objSent->getPropertiesDocument('DM_SO_VAN_BAN_DI');
		$this->view->arrInputBooks = $arrInputBooks;
		//Lay danh sach tinh chat VB
		$arrNature = $objSent->getPropertiesDocument('DM_TINH_CHAT_VB');
		$this->view->arrNature = $arrNature;
		//Lay danh sach do mat VB
		$arrTextOfEmergency = $objSent->getPropertiesDocument('DM_DO_KHAN_VAN_BAN');		
		$this->view->arrTextOfEmergency = $arrTextOfEmergency;
		//Lay tat ca danh sach nguoi ky van ban	
		$arrSigner = $objSent->getSignByUnit('DM_NGUOI_KY',$_SESSION['arr_all_staff']);
		$this->view->arrSigner = $arrSigner;
		// Goi ham textselectbox lay ra nguoi ky
		//$this->view->search_textselectbox_signer = $objDocFun->doc_search_ajax($arrSigner,"C_CODE","C_NAME","C_SIGNER_POSITION_NAME","hdn_signer_position_name",1,"",1);
		// Goi ham search lay ra toan bo thong don vi, phong ban nhan VB
		$this->view->search_textselectbox_ownerCodeList = Sys_Function_DocFunctions::doc_search_ajax($_SESSION['arr_all_unit_keep'],"id","name","C_SENT_OWNER_CODE_LIST","hdn_received_place",0);
				
		//Lay danh sach cac linh vuc
		$arrDocCate = $objSent->getPropertiesDocument('DM_LINH_VUC_VAN_BAN');
			// Goi ham textselectbox lay ra nguoi ky
		$this->view->search_doc_cate_name = $objDocFun->doc_search_ajax($arrDocCate,"C_CODE","C_NAME","C_DOC_CATE","hdn_doc_cate_name",1,"",1);
		//Lay ra loai VB
		$arrDocType = $objSent->getPropertiesDocument('DM_LOAI_VAN_BAN');
		$this->view->arrDocType = $arrDocType;
		$this->view->search_doc_type_name = $objDocFun->doc_search_ajax($arrDocType,"PK_LIST","C_NAME","C_DOC_TYPE","hdn_doc_type_name",1,'',1);
		//Tao xau XML luu CSDL
		$psXmlTagValueList = $this->_request->getParam('hdn_XmlTagValueList','');
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
		//Thuc hien upload file len o cung toi da 10 file
		$arrFileNameUpload = $ojbSysLib->_uploadFileList(10,$this->_request->getBaseUrl() . "/public/attach-file/",'FileName','!#~$|*');
		//lay chuc danh nguoi soan thao van ban
		$draffPosition = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$objFilter->filter($arrInput['FK_STAFF']),'position_code');
		//Tuy chon ung voi cac truong hop update du lieu	
		$psOption = $this->_request->getParam('hdh_option','');
		$this->view->option = $psOption;
		//Lay mang chua id loai VB truyen vao
		$arrGetSingleDocTypeList 	= 	$objList->getSingleList($objFilter->filter($arrInput['hdn_doc_type_name']));
		If($arrGetSingleDocTypeList['C_NAME'] == ''){
			$sDoctype = $objFilter->filter($arrInput['C_DOC_TYPE']);
		}else{
			$sDoctype = $arrGetSingleDocTypeList['C_NAME'];
		}
		//Mang luu du lieu update
		$arrParameter = array(	
								'PK_SENT_DOCUMENT'				=>'',										
								'C_TEXT_BOOK'					=>$objFilter->filter($arrInput['C_TEXT_BOOK']),
								'C_SENT_DATE'					=>$ojbSysLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['C_SENT_DATE'])),
								'C_DOC_TYPE'					=>$sDoctype,	
								'C_NUMBER'						=>$objFilter->filter($arrInput['C_NUMBER']),	
								'C_SYMBOL'						=>$objFilter->filter($arrInput['C_SYMBOL']),
								'C_TEXT_OF_EMERGENCY'			=>$objFilter->filter($arrInput['C_TEXT_OF_EMERGENCY']),																	
								'C_NATURE'						=>$objFilter->filter($arrInput['C_NATURE']),
								'C_XML_DATA'					=>$psXmlStringInDb,
								'C_SUBJECT'						=>$ojbSysLib->_replaceBadChar($objFilter->filter($arrInput['C_SUBJECT'])),
								'C_RECEIVE_PLACE'				=>$objFilter->filter($arrInput['C_RECEIVE_PLACE']),
								'FK_SIGNER'						=>$objFilter->filter($arrInput['C_SIGNER_POSITION_NAME']), 
								'C_SIGNER_POSITION_NAME'		=>$objDocFun->getNamePositionStaffByIdList($objFilter->filter($arrInput['C_SIGNER_POSITION_NAME'])),	
								'C_DOC_CATE'					=>$objFilter->filter($arrInput['C_DOC_CATE']),
								'C_STATUS'						=>'DA_BAN_HANH',
								'FK_UNIT_TAOVB'					=>$_SESSION['OWNER_ID'],
								'C_OWNER_NAME'					=>$_SESSION['OWNER_NAME'],
								'FK_UNIT_SOANTHAO'				=>$objFilter->filter($arrInput['FK_UNIT_SOANTHAO']),
								'C_UNIT_NAME'					=>Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_unit'],$objFilter->filter($arrInput['FK_UNIT_SOANTHAO']),'name'),
								'FK_STAFF'						=>$objFilter->filter($arrInput['FK_STAFF']),
								'C_STAFF_POSITION_NAME'			=>$draffPosition . ' - ' . $ojbSysLib->_getItemAttrById($_SESSION['arr_all_staff'],$objFilter->filter($arrInput['FK_STAFF']),'name'),
								'C_FILE_NAME'					=>$arrFileNameUpload,
								'C_DOC_TYPE_ID'					=>$arrGetSingleDocTypeList['PK_LIST']
							);	
		if($this->_request->getParam('C_SUBJECT','') != ""){
			var_dump($arrParameter);								
			$arrResult = $objSent->docSentUpdate($arrParameter);	
		}	
		$sRetError = $arrResult['RET_ERROR'];
		if ($sRetError != ''){
			//Neu trung so/ki hieu se tra ve man hinh add
			echo "<script type='text/javascript'>";
			echo "alert('$sRetError');\n";					
			echo "</script>";
			$arrSent = array(
					'0'	=>	array(
						'PK_SENT_DOCUMENT'				=>'',										
						'C_TEXT_BOOK'					=>$objFilter->filter($arrInput['C_TEXT_BOOK']),
						'C_SENT_DATE'					=>$ojbSysLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['C_SENT_DATE'])),
						'C_DOC_TYPE'					=>$sDoctype,
						'C_NUMBER'						=>$objFilter->filter($arrInput['C_NUMBER']),	
						'C_SYMBOL'						=>$objFilter->filter($arrInput['C_SYMBOL']),
						'C_TEXT_OF_EMERGENCY'			=>$objFilter->filter($arrInput['C_TEXT_OF_EMERGENCY']),																	
						'C_NATURE'						=>$objFilter->filter($arrInput['C_NATURE']),
						'C_SUBJECT'						=>$ojbSysLib->_replaceBadChar($objFilter->filter($arrInput['C_SUBJECT'])),
						'C_RECEIVE_PLACE'				=>$objFilter->filter($arrInput['C_RECEIVE_PLACE']),
						'FK_SIGNER'						=>$objFilter->filter($arrInput['FK_SIGNER']),
						'C_SIGNER_POSITION_NAME'		=>$objFilter->filter($arrInput['C_SIGNER_POSITION_NAME']),
						'C_DOC_CATE'					=>$objFilter->filter($arrInput['C_DOC_CATE']),
						'FK_UNIT_TAOVB'					=>$_SESSION['OWNER_ID'],
						'FK_UNIT'						=>$objFilter->filter($arrInput['FK_UNIT_SOANTHAO']),
						'C_UNIT_NAME'					=>Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_unit'],$objFilter->filter($arrInput['FK_UNIT_SOANTHAO']),'name'),
						'FK_STAFF'						=>$objFilter->filter($arrInput['FK_STAFF']),
						'C_DOC_TYPE_ID'					=>$arrGetSingleDocTypeList['PK_LIST'],
						'SO_BAN'						=>$ojbXmlLib->_xmlGetXmlTagValue($psXmlStringInDb,"data_list","so_ban")  ,
						'SO_TRANG'						=>$ojbXmlLib->_xmlGetXmlTagValue($psXmlStringInDb,"data_list","so_trang")  ,
						'GIA_SO'						=>$ojbXmlLib->_xmlGetXmlTagValue($psXmlStringInDb,"data_list","gia_so")  ,
						'CAP_SO'						=>$ojbXmlLib->_xmlGetXmlTagValue($psXmlStringInDb,"data_list","cap_so")  
								),	
						);
						$this->view->sDocType = $objFilter->filter($arrInput['C_DOC_TYPE']);	
		}else{
			//Xu ly truong hop Gui VB dien tu
			if($this->_request->getParam('C_SUBJECT','') != ""){
				//Don vi, phong ban nhan VB
				$sOwnerCodeList = $objDocFun->convertUnitNameListToUnitIdList($this->_request->getParam('C_SENT_OWNER_CODE_LIST',''));
				//Lay ID cua NSD dang nhap hien thoi
				$StaffId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
				//Lay TEN cua NSD dang nhap hien thoi
				$sStaffName = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'name');
				//Lay CHUC VU phong ban cua NSD dang nhap hien thoi
				$sStaffPosition = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'position_code');		
				//Lay ID VB DI can gui
				$sSentDocumentId = $arrResult['NEW_ID'];
				$arrParameter = array(	
								'PK_SEND_RECEIVE'				=>'',	
								'FK_UNIT'						=>$_SESSION['OWNER_ID'],			
								'FK_CREATER'					=>$StaffId,
								'FK_CREATER_POSITION_NAME'		=>$sStaffPosition . ' - ' . $sStaffName,
								'C_DOC_TYPE'					=>$sDoctype,
								'C_SYMBOL'						=>$objFilter->filter($arrInput['C_NUMBER']) . $objFilter->filter($arrInput['C_SYMBOL']),
								'C_RELEASE_DATE'				=>$ojbSysLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['C_SENT_DATE'])),
								//Ngay hop---------------------------
								'C_DATE'						=>'',
								'C_HOURS'						=>'',
								'C_ADDRESS'						=>'',	
								//-----------------------------------																	
								'C_SUBJECT'						=>$ojbSysLib->_replaceBadChar($objFilter->filter($arrInput['C_SUBJECT'])),
								'C_TEXT_OF_EMERGENCY'			=>$objFilter->filter($arrInput['C_TEXT_OF_EMERGENCY']),
								'C_SIGNER_POSITION_NAME'		=>$objFilter->filter($arrInput['C_SIGNER_POSITION_NAME']),
								'C_NUMBER_SHEET'				=>$ojbXmlLib->_xmlGetXmlTagValue($psXmlStringInDb,"data_list","so_ban"),
								'C_NUMBER_PAGE'					=>$ojbXmlLib->_xmlGetXmlTagValue($psXmlStringInDb,"data_list","so_trang"),
								'C_OTHER'						=>'',
								//Gui can bo	
								'C_STAFF_ID_LIST'				=>'',
								'C_STAFF_NAME_LIST'				=>'',
								//Gui Don vi - Phong ban
								'C_UNIT_ID_LIST'				=>$sOwnerCodeList,
								'C_UNIT_NAME_LIST'				=>str_replace(';','!#~$|*',$this->_request->getParam('C_SENT_OWNER_CODE_LIST','')),
								'NEW_FILE_ID_LIST'				=>$arrFileNameUpload,
								'FK_DOC_LIST'					=>'',
								'FK_DOC'						=>$sSentDocumentId,
								'C_TYPE'						=>'VB_DI',
								'C_XML_DATA'					=>'',
								'C_SEND_RECEIVED_STATUS'		=>'DA_GUI'
							);	
				//var_dump($arrParameter);
				//exit;
				//Goi lop modSendReceived
				Zend_Loader::loadClass('sendReceived_modSendReceived');
				$objSendReceive = new sendReceived_modSendReceived();
				$arrResult = $objSendReceive->DocSendReceivedUpdate($arrParameter);//Goi update
			}
			
			//Truong hop ghi va them moi
			if ($psOption == "GHI_THEMMOI"){
				//Ghi va quay lai chinh form voi noi dung rong						
				$this->_redirect('sent/documents/add/');				
			}				
			//Truong hop ghi va them tiep
			if ($psOption == "GHI_THEMTIEP"){
				$sentID	=	$arrResult['NEW_ID'];
				//Lay phuong thuc do ra man hinh
				$arrSent = $objSent->docSentGetSingle($sentID);
				$sentID = '';					
				//So cua loai VB tu dong tang them 1
				$this->view->ghi_themtiep = 1;
			}else{
				$this->view->ghi_themtiep = 0;
			}
			//Truong hop ghi nhan
			if ($psOption == "GHI_TAM"){
				//
				$sentID	=	$arrResult['NEW_ID'];
				//Lay phuong thuc do ra man hinh
				$arrSent = $objSent->docSentGetSingle($sentID);				
			}

			//Truong hop ghi va quay lai
			if ($psOption == "GHI_QUAYLAI"){
				//Tro ve trang index						
				$this->_redirect('sent/documents/index/');	
			}
		}	
		//Lay file da dinh kem tu truoc
		$arFileAttach = $objSent->DOC_GetAllDocumentFileAttach($sentID,'','T_DOC_SENT_DOCUMENT');	
		$this->view->AttachFile = $objDocFun->DocSentAttachFile($arFileAttach,sizeof($arFileAttach),10,true,30);	
		//Truyen id ra man hinh
		$this->view->sentID = $sentID;
		//
		$this->view->arrSent = $arrSent;
	}
	
	public function editAction(){	
		$this->view->bodyTitle = 'CẬP NHẬT VĂN BẢN ĐI';
		$arrInput = $this->_request->getParams();
		//Tao doi tuong 
		$objSent   = new Sent_modSent();
		$objFilter = new Zend_Filter();	
		$ojbXmlLib = new Sys_Publib_Xml();
		$ojbSysLib = new Sys_Library();
		$objDocFun = new Sys_Function_DocFunctions();
		$objList   = new Listxml_modList();
		$ojbSysInitConfig = new Sys_Init_Config();
		//Lay cac gia tri const
		$this->view->arrConst =	$ojbSysInitConfig->_setProjectPublicConst();
		//Nhan bien truyen vao tu form
		$fromDate = $this->_request->getParam('fromDate','');
		$toDate = $this->_request->getParam('toDate','');
		$sFullTextSearch = $this->_request->getParam('FullTextSearch','');
		//echo $fromDate.'--'.$toDate.'---'.$sFullTextSearch;exit;
		//Mac dinh de hien list danh sach trong tuan
		if($fromDate == '' and $toDate == '' and $sFullTextSearch == ''){
			$fromDate = $objDocFun->getFirstDayOfWeek() ;
			$toDate = date("d/m/Y");
		}	
		$this->view->sFullTextSearch = $sFullTextSearch;
		$this->view->fromDate = $fromDate;
		$this->view->toDate = $toDate;
		
		//Lay id VB can chinh sua
		$sentID = $this->_request->getParam('hdn_object_id','');	
		//Lay thong tin don vi nhan VB dien tu
		$arrSentReceivedOwner = $objSent->docSentRecevedOwnerGetAll($sentID,'VB_DI','DON_VI');
		$this->view->arrSentReceivedOwner = $arrSentReceivedOwner;
		//var_dump($arrSentReceivedOwner);
		//
		$ghi_themtiep = $this->_request->getParam('ghi_themtiep','');	
		$this->view->ghi_themtiep = $ghi_themtiep;
		//echo $sentID;
		//Lay danh sach so VB di
		$arrInputBooks = $objSent->getPropertiesDocument('DM_SO_VAN_BAN_DI');
		$this->view->arrInputBooks = $arrInputBooks;
		//Lay danh sach tinh chat VB
		$arrNature = $objSent->getPropertiesDocument('DM_TINH_CHAT_VB');
		$this->view->arrNature = $arrNature;
		//Lay danh sach do mat VB
		$arrTextOfEmergency = $objSent->getPropertiesDocument('DM_DO_KHAN_VAN_BAN');		
		$this->view->arrTextOfEmergency = $arrTextOfEmergency;
		//Lay tat ca danh sach nguoi ky van ban	
		$arrSigner = $objSent->getSignByUnit('DM_NGUOI_KY',$_SESSION['arr_all_staff']);
		$this->view->arrSigner = $arrSigner;
		// Goi ham search lay ra nguoi ky
		//$this->view->search_textselectbox_signer = $objDocFun->doc_search_ajax($arrSigner,"C_CODE","C_NAME","C_SIGNER_POSITION_NAME","hdn_signer_position_name",1,"",1);
		// Goi ham search lay ra toan bo thong don vi, phong ban nhan VB
		$this->view->search_textselectbox_ownerCodeList = Sys_Function_DocFunctions::doc_search_ajax($_SESSION['arr_all_unit_keep'],"id","name","C_SENT_OWNER_CODE_LIST","hdn_received_place",0);
		
		//Lay danh sach cac linh vuc
		$arrDocCate = $objSent->getPropertiesDocument('DM_LINH_VUC_VAN_BAN');
		$this->view->search_doc_cate_name = $objDocFun->doc_search_ajax($arrDocCate,"C_CODE","C_NAME","C_DOC_CATE","hdn_doc_cate_name");
		//Lay ra loai VBB
		$arrDocType = $objSent->getPropertiesDocument('DM_LOAI_VAN_BAN');
		$this->view->arrDocType = $arrDocType;
		//$this->view->search_doc_type_name = $objDocFun->doc_search_ajax($arrDocType,"C_CODE","C_NAME","C_DOC_TYPE","hdn_doc_type_name");
		//Tao xau XML luu CSDL
		$psXmlTagValueList = $this->_request->getParam('hdn_XmlTagValueList','');
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
		//Lay mang chua id loai VB truyen vao
		$arrGetSingleDocTypeList 	= 	$objList->getSingleList($objFilter->filter($arrInput['C_DOC_TYPE']));
		//lay chuc danh nguoi soan thao van ban
		$draffPosition = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$objFilter->filter($arrInput['FK_STAFF']),'position_code');
		//Thuc hien upload file len o cung toi da 10 file
		$arrFileNameUpload = $ojbSysLib->_uploadFileList(10,$this->_request->getBaseUrl() . "/public/attach-file/",'FileName','!#~$|*');
		$psOption = $this->_request->getParam('hdh_option','');
		//echo $sentID. '<br>';
		//Mang luu du lieu update
		$arrParameter = array(	
								'PK_SENT_DOCUMENT'				=>$sentID,										
								'C_TEXT_BOOK'					=>$objFilter->filter($arrInput['C_TEXT_BOOK']),
								'C_SENT_DATE'					=>$ojbSysLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['C_SENT_DATE'])),
								'C_DOC_TYPE'					=>$objFilter->filter($arrInput['C_DOC_TYPE']),	
								'C_NUMBER'						=>$objFilter->filter($arrInput['C_NUMBER']),	
								'C_SYMBOL'						=>$objFilter->filter($arrInput['C_SYMBOL']),
								'C_TEXT_OF_EMERGENCY'			=>$objFilter->filter($arrInput['C_TEXT_OF_EMERGENCY']),																	
								'C_NATURE'						=>$objFilter->filter($arrInput['C_NATURE']),
								'C_XML_DATA'					=>$psXmlStringInDb,
								'C_SUBJECT'						=>$ojbSysLib->_replaceBadChar($objFilter->filter($arrInput['C_SUBJECT'])),
								'C_RECEIVE_PLACE'				=>$objFilter->filter($arrInput['C_RECEIVE_PLACE']),
								'FK_SIGNER'						=>$objFilter->filter($arrInput['C_SIGNER_POSITION_NAME']), 
								'C_SIGNER_POSITION_NAME'		=>$objDocFun->getNamePositionStaffByIdList($objFilter->filter($arrInput['C_SIGNER_POSITION_NAME'])),	
								'C_DOC_CATE'					=>$objFilter->filter($arrInput['C_DOC_CATE']),
								'C_STATUS'						=>'DA_BAN_HANH',
								'FK_UNIT_TAOVB'					=>$_SESSION['OWNER_ID'],
								'FK_UNIT_SOANTHAO'				=>$objFilter->filter($arrInput['FK_UNIT_SOANTHAO']),
								'C_UNIT_NAME'					=>Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_unit'],$objFilter->filter($arrInput['FK_UNIT_SOANTHAO']),'name'),
								'FK_STAFF'						=>$objFilter->filter($arrInput['FK_STAFF']),
								'C_STAFF_POSITION_NAME'			=>$draffPosition . ' - ' . $ojbSysLib->_getItemAttrById($_SESSION['arr_all_staff'],$objFilter->filter($arrInput['FK_STAFF']),'name'),
								'C_FILE_NAME'					=>$arrFileNameUpload,
								'C_DOC_TYPE_ID'					=>$arrGetSingleDocTypeList['PK_LIST']
							);	
		if($this->_request->getParam('C_SUBJECT','') != ""){								
			$arrResult = "";	
			$arrResult = $objSent->docSentUpdate($arrParameter);	
			$sRetError = $arrResult['RET_ERROR'];	
		}	
		if($sentID == ''){
			$sentID = $arrResult['NEW_ID'];
		}
		//Lay thong tin VB di va gui ra View
		$arrSent = $objSent->docSentGetSingle($sentID);
		$this->view->arrSent = $arrSent;
		if ($sRetError != ''){
			//Neu trung so/ki hieu se tra ve man hinh add
			echo "<script type='text/javascript'>";
			echo "alert('$sRetError');\n";					
			echo "</script>";
		}else{
			
				//Truong hop ghi va them moi
			if ($psOption == "GHI_THEMMOI"){
				//Ghi va quay lai chinh form voi noi dung rong						
				$this->_redirect('sent/documents/add/');
				
			}	
			
			//Truong hop ghi va them tiep
			if ($psOption == "GHI_THEMTIEP"){
				$sentID = '';
			}

			//Truong hop ghi nhan
			if ($psOption == "GHI_TAM"){
				$sentID = $sentID;

			}

			//Truong hop ghi va quay lai
			if ($psOption == "GHI_QUAYLAI"){
				//Tro ve trang index						
				$this->_redirect('sent/documents/index/');	
			}
		}				
		//Lay file da dinh kem tu truoc
		$arFileAttach = $objSent->DOC_GetAllDocumentFileAttach($sentID,'','T_DOC_SENT_DOCUMENT');	
		$this->view->AttachFile = $objDocFun->DocSentAttachFile($arFileAttach,sizeof($arFileAttach),10,true,30);	
		//Truyen id ra man hinh
		$this->view->sentID = $sentID;
	}
	
	
	public function deleteAction(){	
		echo "OK"; exit;
		$objSent   = new Sent_modSent();	
		//Lay Id doi tuong can xoa
		$sListId = $this->_request->getParam('hdn_object_id_list',"");	
		//Goi phuong thuc xoa doi tuong
		$objSent->docSentDelete($sListId);
		$this->_redirect('sent/documents/index/');	
	}	
	
	
	public function viewAction(){	
		$this->view->bodyTitle = 'CHI TIẾT VĂN BẢN ĐI';
		$ojbSysLib = new Sys_Library();
		$objSent   = new Sent_modSent();	
		$objDocFun = new Sys_Function_DocFunctions();
		$objFunction =	new	Sys_Function_DocFunctions()	;
		$ojbSysInitConfig = new Sys_Init_Config();
		//Lay cac gia tri const
		$this->view->arrConst =	$ojbSysInitConfig->_setProjectPublicConst();
		//Nhan bien truyen vao tu form
		$fromDate = $this->_request->getParam('fromDate','');
		$toDate = $this->_request->getParam('toDate','');
		$sFullTextSearch = $this->_request->getParam('FullTextSearch','');
		//echo $fromDate.'--'.$toDate.'---'.$sFullTextSearch;exit;
		//Mac dinh de hien list danh sach trong tuan
		if($fromDate == '' and $toDate == '' and $sFullTextSearch == ''){
			$fromDate = $objFunction->getFirstDayOfWeek() ;
			$toDate = date("d/m/Y");
		}	
		$this->view->sFullTextSearch = $sFullTextSearch;
		$this->view->fromDate = $fromDate;
		$this->view->toDate = $toDate;
		//Lay Id doi tuong 
		$sentID = $this->_request->getParam('hdn_object_id','');	
		$this->view->sentID = $sentID;
		//echo $sentID;
		//Lay thong tin VB di va gui ra View
		$arrSent = $objSent->docSentGetSingle($sentID);
		$this->view->arrSent = $arrSent;
		//Tuy chon ung voi cac truong hop update du lieu	
		$psOption = $this->_request->getParam('hdh_option','');
		$this->view->option = $psOption;
		
	}	
	public function printAction(){	
		$objDocFun = new Sys_Function_DocFunctions();	
		$ojbSysInitConfig = new Sys_Init_Config();
		$ojbSysLib = new Sys_Library();	
		$filter = new Zend_Filter();	
		$objSent   = new Sent_modSent();	
		$objList   = new Listxml_modList();		
		$sentID = $this->_request->getParam('hdn_object_id','');	
		//Lay thong tin VB di va gui ra View
		$arrSent = $objSent->docSentGetSingle($sentID);
		$this->view->arrSent = $arrSent;	
		$this->view->sentID = $sentID;
		//Lay file dinh kem
		$strFileName 				= $arrSent[0]['C_FILE_NAME'];
		$sFile 						= Sys_Library::_getAllFileAttach($strFileName,"!#~$|*","!~!",$this->_request->getBaseUrl() . "/public/attach-file/");
		//Lay duong dan
		$path = $_SERVER['SCRIPT_FILENAME'];
		$path = substr($path, 0, -9);
		$my_report = str_replace("/", "\\", $path) . "rpt\\sent\\sent.rpt";
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
		
		// Truyen tham so vao		
		$creport->ParameterFields(1)->SetCurrentValue(Sys_Library::_getNameByCode($arrInputBooks,$arrSent[0]['C_TEXT_BOOK'],'C_NAME'));
		$creport->ParameterFields(2)->SetCurrentValue($arrSent[0]['C_SENT_DATE']);	
		$creport->ParameterFields(3)->SetCurrentValue($arrSent[0]['C_DOC_TYPE']);
		$creport->ParameterFields(4)->SetCurrentValue($arrSent[0]['C_NUMBER'].'/'.$arrSent[0]['C_SYMBOL']);
		$creport->ParameterFields(5)->SetCurrentValue(Sys_Library::_getNameByCode($arrNature,$arrSent[0]['C_NATURE'],'C_NAME'));
		$creport->ParameterFields(6)->SetCurrentValue($arrSent[0]['SO_BAN']);
		$creport->ParameterFields(7)->SetCurrentValue($arrSent[0]['SO_TRANG']);
		$creport->ParameterFields(8)->SetCurrentValue($arrSent[0]['GIA_SO']);
		$creport->ParameterFields(9)->SetCurrentValue($arrSent[0]['CAP_SO']);
		$creport->ParameterFields(10)->SetCurrentValue($arrSent[0]['C_SUBJECT']);
		$creport->ParameterFields(11)->SetCurrentValue($arrSent[0]['C_RECEIVE_PLACE']);
		$creport->ParameterFields(12)->SetCurrentValue($arrSent[0]['C_DOC_CATE']);
		$creport->ParameterFields(13)->SetCurrentValue($arrSent[0]['C_UNIT_NAME']); //don vi soan thao
		$creport->ParameterFields(14)->SetCurrentValue($arrSent[0]['C_STAFF_POSITION_NAME']);//chuyen vien soan thao
		$creport->ParameterFields(15)->SetCurrentValue((string)$sFile); 	
		$creport->ParameterFields(16)->SetCurrentValue($arrSent[0]['C_TEXT_OF_EMERGENCY']); 	
		//Ten file
		$report_file = 'sent.doc';
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
public function rssAction(){
		//Tao doi tuong ham dung chung
		$objDocFun = new Sys_Function_DocFunctions();	
		$ojbSysInitConfig = new Sys_Init_Config();
		//Tao doi tuong Sys_lib
		$ojbSysLib = new Sys_Library();	
		// Tao doi tuong Zend_Filter
		$filter = new Zend_Filter();		
		// Tao doi tuong cho lop xu ly du lieu lien quan modul	
		$objReceive = new Sent_modSent();	
		//Lay id van ban tu view
		$sReceiveDocumentId = $this->_request->getParam('hdn_object_id','');		
		$this->view->sReceiveDocumentId = $sReceiveDocumentId;
		//Mang luu thong tin chi tiet cua mot van ban
		$arrReceived = $objReceive->docSentGetSingle($sReceiveDocumentId,$_SESSION['OWNER_ID']);
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
                    'description'=>'Số ký hiệu: '.$arrReceived[0]['C_NUMBER'].$arrReceived[0]['C_SYMBOL']. '   Ngày phát hành: '.$arrReceived[0]['C_SENT_DATE'],                  
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
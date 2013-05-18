<?php
class sent_draffController extends  Zend_Controller_Action {
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
		$JSandStyle = Sys_Publib_Library::_getAllFileJavaScriptCss('','js','util.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','sent.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','ajax.js,jquery-1.4.2.min.js,jquery-1.4.2.js,jQuery.equalHeights.js',',','js'). Sys_Publib_Library::_getAllFileJavaScriptCss('','js/LibSearch','actb_search.js,common_search.js',',','js');
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
		$this->view->currentModulCodeForLeft = "DRAFF";
		//Lay trang thai left menu
		$this->view->getStatusLeftMenu = $this->_request->getParam('modul','');
		$psshowModalDialog = $this->_request->getParam('showModalDialog',"");
		$this->view->showModelDialog = $psshowModalDialog;
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
		//
		$this->view->bodyTitle = 'DANH SÁCH VĂN BẢN DỰ THẢO';
		//lay modul left
		$getStatusFromMnuLeft = $this->_request->getParam('modul','');
		$this->view->getModulLeft = $getStatusFromMnuLeft;
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
		// Xu li query lay du lieu
		$arrSent = $objSent->docDraftGetAll($sFullTextSearch,'VB_DU_THAO',$iOwnerId,$iDepartmentId,$iUserId,$piCurrentPage,$piNumRowOnPage);
		$this->view->arrSent = $arrSent;
		//Mang luu thong tin tong so ban ghi tim thay
		$psCurrentPage = $arrSent[0]['C_TOTAL'];				
		//Hien thi thong tin man hinh danh sach nay co bao nhieu ban ghi va hien thi Radio "Chon tat ca"; "Bo chon tat ca"
		if (count($arrSent) > 0){
			$this->view->sdocpertotal = "Danh sách có ".sizeof($arrSent).'/'.$psCurrentPage." văn bản";
			//Sinh xau HTML mo ta so trang (Trang 1; Trang 2;...)
			$this->view->generateStringNumberPage = Sys_Publib_Library::_generateStringNumberPage($psCurrentPage, $piCurrentPage, $piNumRowOnPage,$pUrl) ;		
			//quy dinh so record/page	
			$this->view->generateHtmlSelectBoxPage = Sys_Publib_Library::_generateChangeRecordNumberPage($piNumRowOnPage,"../index/" );
		}
		$_SESSION['list_id_sent'] ='';
		$_SESSION['list_id_received'] ='';
	}
	
	public function addAction(){	
		$sGetModulLeft = $this->_request->getParam('hdn_function_modul',0);
		$this->view->getModulLeft = $sGetModulLeft;
		$this->view->bodyTitle = 'CẬP NHẬT VĂN BẢN DỰ THẢO';
		$arrInput = $this->_request->getParams();
		//Tao doi tuong 
		$objSent   = new Sent_modSent();
		$objFilter = new Zend_Filter();	
		$objDocFun = new Sys_Function_DocFunctions();
		$ojbXmlLib = new Sys_Publib_Xml();
		$ojbSysLib = new Sys_Library();
		$objList   = new Listxml_modList();
		$ojbSysInitConfig = new Sys_Init_Config();
		//Lay ID Cap Quan/huyen cua don vi trien khai
		$this->view->IdDistrict = $ojbSysInitConfig->_setParentOwnerId();
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
		//Lay danh sach cap duyet
		$arrApproval = $objSent->getPropertiesDocument('CAP_DUYET');
		$this->view->arrApproval = $arrApproval;
		//Lay tat ca danh sach nguoi ky van ban	
		$arrSigner = $objSent->getSignByUnit('DM_NGUOI_KY',$_SESSION['arr_all_staff']);
		$this->view->arrSigner = $arrSigner;
		//var_dump($arrSigner);
		//$this->view->search_textselectbox_signer = $objDocFun->doc_search_ajax($arrSigner,"C_CODE","C_NAME","C_SIGNER_POSITION_NAME","hdn_signer_position_name",1,"",1);
		//Lay danh sach cac linh vuc
		$arrDocCate = $objSent->getPropertiesDocument('DM_LINH_VUC_VAN_BAN');
			// Goi ham textselectbox lay ra nguoi ky
		$this->view->search_doc_cate_name = $objDocFun->doc_search_ajax($arrDocCate,"C_CODE","C_NAME","C_DOC_CATE","hdn_doc_cate_name",1,'',1);
		//Lay ra loai VB
		$arrDocType = $objSent->getPropertiesDocument('DM_LOAI_VAN_BAN');
		//$this->view->arrDocType = $arrDocType;
			$this->view->search_doc_type_name = $objDocFun->doc_search_ajax($arrDocType,"C_CODE","C_NAME","C_DOC_TYPE","hdn_doc_type_name",1,'',1);
		//Can bo cho y kien	
		$this->view->search_idea_staft_name = $objDocFun->doc_search_ajax($_SESSION['arr_all_staff'],"id","name","C_IDEA_STAFT_NAME","hdn_idea_staft_name",0,"position_code");
		//Phong ban cho y kien
		$this->view->search_idea_unit_name = $objDocFun->doc_search_ajax($_SESSION['arr_all_unit'],"id","name","C_IDEA_UNIT_NAME","hdn_idea_unit_name",0);
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
		
		//Lay cac id VB den-di lien quan
		$sListid = $this->_request->getParam('hdn_list_id','');
		If($sListid !=''){	
			$arrListid = explode(',',$sListid);
			//var_dump($arrListid);
			//echo "<br>";
			$arrListidReceived = explode(',',$_SESSION['list_id_received']);
			//var_dump($arrListidReceived);
			//echo "<br>";
			$strDen = '';
			$strDi = '';
			for($j=0; $j<sizeof($arrListid); $j++){			
				$test = 0;
				for($k=0;$k<sizeof($arrListidReceived);$k++){
					if(trim($arrListid[$j]) == trim($arrListidReceived[$k])){				
						$strDen = $strDen . trim($arrListid[$j]).',';
						$test = 1;
					}
				}
				if($test == 0){
					$strDi = $strDi. trim($arrListid[$j]).','; 
				}
			}
			
		}	
		//exit;
		if($strDen ==''){
			$strDen = $_SESSION['list_id_received'];
		}
		if($strDi ==''){
			$strDi = $_SESSION['list_id_sent'];
		}
		$arrRelate=$objSent->docRelateGetAll('',$strDen,$strDi);
		$this->view->arrRelate = $arrRelate;
	//	var_dump($arrRelate);
		//lay ID va ten don vi soan thao 
		$iDepartmentId  = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');
		$this->view->iDepartmentId = $iDepartmentId;
		$sDepartmentName= Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_unit'],$iDepartmentId,'name');
		$iUserId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
		$this->view->iUserId = $iUserId;
		//Thuc hien upload file len o cung toi da 10 file
		$arrFileNameUpload = $ojbSysLib->_uploadFileList(10,$this->_request->getBaseUrl() . "/public/attach-file/",'FileName','!#~$|*');
		//lay chuc danh nguoi soan thao van ban
		$sDraffPosition = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$iUserId,'position_code');
		//Tuy chon ung voi cac truong hop update du lieu	
		$psOption = $this->_request->getParam('hdh_option','');

		$this->view->option = $psOption;
		//Lay mang chua id loai VB truyen vao
		$arrGetSingleEmergencyList 	= 	$objList->getSingleList($objFilter->filter($arrInput['C_TEXT_OF_EMERGENCY']));
		//Lay ten tu id truyen vao
		$sStaffIdList = "";

		//chuyen doi mang danh sach ten can bo ra mang mot chieu
		$arr_staff_name = explode(";",$objFilter->filter($arrInput['C_IDEA_STAFT_NAME']));

		$sStaffIdList = substr($sStaffIdList,0,strlen($sStaffIdList)-1); 
		
		$sIdeaListId = $objDocFun->convertStaffNameToStaffId($objFilter->filter($arrInput['C_IDEA_STAFT_NAME']));
		if($sIdeaListId != ''){
			$arrIdeaListId = explode(',',$sIdeaListId);
			$sIdeaListFkunit = '';
			for($k=0;$k <sizeof($arrIdeaListId);$k++){
				$sIdeaListFkunit  = $sIdeaListFkunit . Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$arrIdeaListId[$k],'unit_id').',';
			}
		}	
		//var_dump($arrSigner);
		//exit;
		//echo Sys_Publib_Library ::_getCodeByName($arrSigner,$objFilter->filter($arrInput['C_SIGNER_POSITION_NAME']),'C_CODE');
		//exit;
		$iDepartmentId  = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');
		//Mang luu du lieu update
		$arrParameter = array(	
								'PK_SENT_DOCUMENT'				=>'',										
								'C_DOC_TYPE'					=>$objFilter->filter($arrInput['C_DOC_TYPE']),
								'C_SENT_DATE'					=>$ojbSysLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['C_SENT_DATE'])),
								'C_SUBJECT'						=>$objFilter->filter($arrInput['C_SUBJECT']),
								'C_DOC_CATE'					=>$objFilter->filter($arrInput['C_DOC_CATE']),
								'C_NATURE'						=>$objFilter->filter($arrInput['C_NATURE']),	
								'C_TEXT_OF_EMERGENCY'			=>$objFilter->filter($arrInput['C_TEXT_OF_EMERGENCY']),																	
								'C_XML_DATA'					=>$psXmlStringInDb,
								'C_RECEIVE_PLACE'				=>$objFilter->filter($arrInput['C_RECEIVE_PLACE']),
								'FK_SIGNER'						=>$objFilter->filter($arrInput['C_SIGNER_POSITION_NAME']), 
								'C_SIGNER_POSITION_NAME'		=>$objDocFun->getNamePositionStaffByIdList($objFilter->filter($arrInput['C_SIGNER_POSITION_NAME'])),
								'C_RECEIVE_LIST_ID'				=>$strDen,
								'C_SENT_LIST_ID'				=>$strDi,
								'C_RELATE_LIST_ID'				=>'',
								'C_NOTE'						=>$objFilter->filter($arrInput['C_NOTE']),
								'FK_UNIT_TAOVB'					=>$_SESSION['OWNER_ID'],
								'FK_UNIT_SOANTHAO'				=>$iDepartmentId,
								'C_UNIT_NAME'					=>$sDepartmentName,
								'FK_STAFF'						=>$iUserId,
								'C_STAFF_POSITION_NAME'			=>$sDraffPosition . ' - ' . $ojbSysLib->_getItemAttrById($_SESSION['arr_all_staff'],$iUserId,'name'),
								'C_IDEA_UNIT_ID'				=>$objDocFun->convertUnitNameListToUnitIdList($objFilter->filter($arrInput['C_IDEA_UNIT_NAME'])),
								'C_IDEA_UNIT_NAME'				=>$objFilter->filter($arrInput['C_IDEA_UNIT_NAME']),
								'C_IDEA_STAFT_ID'				=>$objDocFun->convertStaffNameToStaffId($objFilter->filter($arrInput['C_IDEA_STAFT_NAME'])),
								'C_IDEA_STAFT_NAME'				=>$objFilter->filter($arrInput['C_IDEA_STAFT_NAME']),
								'C_FILE_NAME'					=>$arrFileNameUpload,
								'C_STATUS'						=>'VB_DU_THAO',	
								'C_DELIMITOR'					=>'!#~$|*',
								'C_APPOINTED_DATE'				=>$ojbSysLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['C_APPOINTED_DATE'])),
								'C_IDEA_STAFT_FKUNIT'			=>$sIdeaListFkunit
							);						
		if($this->_request->getParam('C_SUBJECT','') != ""){	
			//var_dump($arrParameter); exit;							
			$arrResult = "";	
			$arrResult = $objSent->docDraftUpdate($arrParameter);	
			$_SESSION['list_id_sent'] ='';
			$_SESSION['list_id_received'] ='';
		}
		//Truong hop ghi va them moi
		if ($psOption == "GHI_THEMMOI"){
			//Ghi va quay lai chinh form voi noi dung rong						
			$this->_redirect('sent/draff/add');				
		}				
		//Truong hop ghi va them tiep
		if ($psOption == "GHI_THEMTIEP"){
			$sentID	=	$arrResult['NEW_ID'];
			//Lay phuong thuc do ra man hinh
			$arrSent = $objSent->docDraftGetSingle($sentID);
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
			$arrSent = $objSent->docDraftGetSingle($sentID);				
		}

		//Truong hop ghi va quay lai
		if ($psOption == "GHI_QUAYLAI"){
			//Tro ve trang index						
			$this->_redirect('sent/draff/index' );
		}
			
		//Lay file da dinh kem tu truoc
		$arFileAttach = $objSent->DOC_GetAllDocumentFileAttach($sentID,'','T_DOC_SENT_DOCUMENT');	
		$this->view->AttachFile = $objDocFun->DocSentAttachFile($arFileAttach,sizeof($arFileAttach),10,true,30);	
		//Truyen id ra man hinh
		$this->view->sentID = $sentID;
		//
		$this->view->arrSent = $arrSent;
	}
	/**
	 * nguoi tao: nghiat
	 * ngay tao: 07/07/2010
	 * y nghia: phuong thuc luu danh sach ID VB den lien quan
	 */
	public function getreceivedAction(){
		//Nhan bien truyen vao tu form
		$sFullTextSearch = trim($this->_request->getParam('FullTextSearch',''));
		$this->view->sFullTextSearch = $sFullTextSearch;
		//An MeneLeft , MenuHeader , MenuFooter	
		$this->view->hideDisplayMeneLeft = ""; 
		$this->view->hideDisplayMenuHeader ="";
		$this->view->hideDisplayMenuFooter = "";
		$this->view->bodyTitle = "LẤY VĂN BẢN ĐẾN";			
		$sentID =  $this->_request->getParam('sentID',"");							
		$this->view->sentID = $sentID;
		//Lay cac hang so dung chung
		$arrConst = Sys_Init_Config::_setProjectPublicConst();
		$this->view->arrConst = $arrConst;
		$arrInput = $this->_request->getParams();
		$sListIdReceived = $this->_request->getParam('hdn_object_id_list','');
	}
	/**
	 * nguoi tao: nghiat
	 * ngay tao: 07/07/2010
	 * y nghia: phuong thuc luu danh sach ID VB di lien quan
	 */
	public function getsendAction(){
		//Nhan bien truyen vao tu form
		$sFullTextSearch = trim($this->_request->getParam('FullTextSearch',''));
		$this->view->sFullTextSearch = $sFullTextSearch;
		//An MeneLeft , MenuHeader , MenuFooter	
		$this->view->hideDisplayMeneLeft = ""; 
		$this->view->hideDisplayMenuHeader ="";
		$this->view->hideDisplayMenuFooter = "";
		$this->view->bodyTitle = "LẤY VĂN BẢN ĐI";
		$sentID =  $this->_request->getParam('sentID',"");							
		$this->view->sentID = $sentID;
		//Lay cac hang so dung chung
		$arrConst = Sys_Init_Config::_setProjectPublicConst();
		$this->view->arrConst = $arrConst;
		// Lay toan bo tham so truyen tu form			
		$arrInput = $this->_request->getParams();		
		$sListIdSent= $this->_request->getParam('hdn_object_id_list','');
	}
	public function editAction(){	
		$this->view->bodyTitle = 'CẬP NHẬT VĂN BẢN DỰ THẢO';
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
		$sFullTextSearch = $this->_request->getParam('FullTextSearch','');
		$this->view->sFullTextSearch = $sFullTextSearch;	
		//Lay ten don vi
		$this->view->sUnitName = $objDocFun->getNameUnitByIdUnitList($_SESSION['OWNER_ID']);
		//---
		$sGetModulLeft = $this->_request->getParam('hdn_function_modul',0);
		$this->view->getModulLeft = $sGetModulLeft;	
		//Lay id VB can chinh sua
		$sentID = $this->_request->getParam('hdn_object_id','');
		//echo 	$sentID;
		$ghi_themtiep = $this->_request->getParam('ghi_themtiep','');	
		$this->view->ghi_themtiep = $ghi_themtiep;
		//Lay danh sach cap duyet
		$arrApproval = $objSent->getPropertiesDocument('CAP_DUYET');
		$this->view->arrApproval = $arrApproval;
		//Lay danh sach so VB di
		$arrInputBooks = $objSent->getPropertiesDocument('DM_SO_VAN_BAN_DI');
		$this->view->arrInputBooks = $arrInputBooks;
		//Lay danh sach tinh chat VB
		$arrNature = $objSent->getPropertiesDocument('DM_TINH_CHAT_VB');
		$this->view->arrNature = $arrNature;
		//Lay danh sach do mat VB
		$arrTextOfEmergency = $objSent->getPropertiesDocument('DM_DO_KHAN_VAN_BAN');		
		$this->view->arrTextOfEmergency = $arrTextOfEmergency;
		//lay ID va ten don vi soan thao 
		$iDepartmentId  = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');
		$this->view->iDepartmentId = $iDepartmentId;
		$sDepartmentName= Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_unit'],$iDepartmentId,'name');
		//Lay tat ca danh sach nguoi ky van ban	
		$arrSigner = $objSent->getSignByUnit('DM_NGUOI_KY',$_SESSION['arr_all_staff']);
		$this->view->arrSigner = $arrSigner;
		//$this->view->search_textselectbox_signer = $objDocFun->doc_search_ajax($arrSigner,"C_CODE","C_NAME","C_SIGNER_POSITION_NAME","hdn_signer_position_name",1,"",1);
		//Lay danh sach cac linh vuc
		$arrDocCate = $objSent->getPropertiesDocument('DM_LINH_VUC_VAN_BAN');
		$this->view->search_doc_cate_name = $objDocFun->doc_search_ajax($arrDocCate,"C_CODE","C_NAME","C_DOC_CATE","hdn_doc_cate_name",1,'',1);
		//Lay ra loai VBB
		$arrDocType = $objSent->getPropertiesDocument('DM_LOAI_VAN_BAN');
		$this->view->arrDocType = $arrDocType;
		$this->view->search_doc_type_name = $objDocFun->doc_search_ajax($arrDocType,"C_CODE","C_NAME","C_DOC_TYPE","hdn_doc_type_name",1,'',1);
			//Can bo cho y kien	
		$this->view->search_idea_staft_name = $objDocFun->doc_search_ajax($_SESSION['arr_all_staff'],"id","name","C_IDEA_STAFT_NAME","hdn_idea_staft_name",0,"position_code");
		//Phong ban cho y kien
		$this->view->search_idea_unit_name = $objDocFun->doc_search_ajax($_SESSION['arr_all_unit'],"id","name","C_IDEA_UNIT_NAME","hdn_idea_unit_name",0);
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
		//Lay cac id VB den-di lien quan
		$sListid = $this->_request->getParam('hdn_list_id','');
		If($sListid !=''){	
			$arrListid = explode(',',$sListid);
			$arrListidReceived = explode(',',$_SESSION['list_id_received']);
			$arrListidSent = explode(',',$_SESSION['list_id_sent']);
			$strDen = '';
			$strDi = '';
			$strLuu = '';
			for($j=0; $j<sizeof($arrListid); $j++){			
				$test = 0;
				for($k=0;$k<sizeof($arrListidReceived);$k++){
					if(trim($arrListid[$j]) == trim($arrListidReceived[$k])){				
						$strDen = $strDen . trim($arrListid[$j]).',';
						$test = 1;
					}
				}
				for($m=0;$m<sizeof($arrListidSent);$m++){
					if(trim($arrListid[$j]) == trim($arrListidSent[$m])){				
						$strDi = $strDi. trim($arrListid[$j]).',';
						$test = 1;
					}
				}
				if($test == 0){
					$strLuu = $strLuu. trim($arrListid[$j]).','; 
				}
			}
			$strLuu = substr($strLuu,0,-1);
			
		}	
		if($strDen ==''){
			$strDen = $_SESSION['list_id_received'];
		}
		if($strDi ==''){
			$strDi = $_SESSION['list_id_sent'];
		}	
		//Lay ID Cap Quan/huyen cua don vi trien khai
		$this->view->IdDistrict = $ojbSysInitConfig->_setParentOwnerId();
		//lay ID va ten don vi soan thao 
		$iDepartmentId  = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');
		$this->view->iDepartmentId = $iDepartmentId;
		$sDepartmentName= Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_unit'],$iDepartmentId,'name');
		$iUserId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
		$this->view->iUserId = $iUserId;
		$sDraffPosition = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$iUserId,'position_code');	
		$sStaffIdList = substr($sStaffIdList,0,strlen($sStaffIdList)-1); 
		//Thuc hien upload file len o cung toi da 10 file
		$arrFileNameUpload = $ojbSysLib->_uploadFileList(10,$this->_request->getBaseUrl() . "/public/attach-file/",'FileName','!#~$|*');
		$psOption = $this->_request->getParam('hdh_option','');
		
		$sIdeaListId = $objDocFun->convertStaffNameToStaffId($objFilter->filter($arrInput['C_IDEA_STAFT_NAME']));
		if($sIdeaListId != ''){
			$arrIdeaListId = explode(',',$sIdeaListId);
			$sIdeaListFkunit = '';
			for($k=0;$k <sizeof($arrIdeaListId);$k++){
				$sIdeaListFkunit  = $sIdeaListFkunit . Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$arrIdeaListId[$k],'unit_id').',';
			}
		}
		//Mang luu du lieu update
		$arrParameter = array(	
								'PK_SENT_DOCUMENT'				=>$sentID,										
								'C_DOC_TYPE'					=>$objFilter->filter($arrInput['C_DOC_TYPE']),
								'C_SENT_DATE'					=>$ojbSysLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['C_SENT_DATE'])),
								'C_SUBJECT'						=>$objFilter->filter($arrInput['C_SUBJECT']),
								'C_DOC_CATE'					=>$objFilter->filter($arrInput['C_DOC_CATE']),
								'C_NATURE'						=>$objFilter->filter($arrInput['C_NATURE']),	
								'C_TEXT_OF_EMERGENCY'			=>$objFilter->filter($arrInput['C_TEXT_OF_EMERGENCY']),																	
								'C_XML_DATA'					=>$psXmlStringInDb,
								'C_RECEIVE_PLACE'				=>$objFilter->filter($arrInput['C_RECEIVE_PLACE']),
								'FK_SIGNER'						=>$objFilter->filter($arrInput['C_SIGNER_POSITION_NAME']), 
								'C_SIGNER_POSITION_NAME'		=>$objDocFun->getNamePositionStaffByIdList($objFilter->filter($arrInput['C_SIGNER_POSITION_NAME'])),
								'C_RECEIVE_LIST_ID'				=>$strDen,
								'C_SENT_LIST_ID'				=>$strDi,
								'C_RELATE_LIST_ID'				=>$strLuu,
								'C_NOTE'						=>$objFilter->filter($arrInput['C_NOTE']),
								'FK_UNIT_TAOVB'					=>$_SESSION['OWNER_ID'],
								'FK_UNIT_SOANTHAO'				=>$iDepartmentId,
								'C_UNIT_NAME'					=>$sDepartmentName,
								'FK_STAFF'						=>$iUserId,
								'C_STAFF_POSITION_NAME'			=>$sDraffPosition . ' - ' . $ojbSysLib->_getItemAttrById($_SESSION['arr_all_staff'],$iUserId,'name'),
								'C_IDEA_UNIT_ID'				=>$objDocFun->convertUnitNameListToUnitIdList($objFilter->filter($arrInput['C_IDEA_UNIT_NAME'])),
								'C_IDEA_UNIT_NAME'				=>$objFilter->filter($arrInput['C_IDEA_UNIT_NAME']),
								'C_IDEA_STAFT_ID'				=>$objDocFun->convertStaffNameToStaffId($objFilter->filter($arrInput['C_IDEA_STAFT_NAME'])),
								'C_IDEA_STAFT_NAME'				=>$objFilter->filter($arrInput['C_IDEA_STAFT_NAME']),
								'C_FILE_NAME'					=>$arrFileNameUpload,
								'C_STATUS'						=>'VB_DU_THAO',	
								'C_DELIMITOR'					=>'!#~$|*',
								'C_APPOINTED_DATE'				=>$ojbSysLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['C_APPOINTED_DATE'])),
								'C_IDEA_STAFT_FKUNIT'			=>$sIdeaListFkunit
							);	
		if($this->_request->getParam('C_SUBJECT','') != ""){								
			$arrResult = "";	
			$arrResult = $objSent->docDraftUpdate($arrParameter);	
			$sRetError = $arrResult['RET_ERROR'];	
			$_SESSION['list_id_sent'] ='';
			$_SESSION['list_id_received'] ='';
		}						
		//Lay thong tin VB di va gui ra View
		if($sentID == ''){
			$sentID = $arrResult['NEW_ID'];
		}
		$arrSent = $objSent->docDraftGetSingle($sentID,$iUserId,$iDepartmentId);
		$this->view->arrSent = $arrSent;	
		$arrRelate=$objSent->docRelateGetAll($sentID,'','');
		$this->view->arrRelate = $arrRelate;
		//var_dump($arrRelate);
		//Truong hop ghi va them moi
		if ($psOption == "GHI_THEMMOI"){
			//Ghi va quay lai chinh form voi noi dung rong						
			$this->_redirect('sent/draff/add');
			
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
			$this->_redirect('sent/draff/index' );	
		}
					
		//Lay file da dinh kem tu truoc
		$arFileAttach = $objSent->DOC_GetAllDocumentFileAttach($sentID,'','T_DOC_SENT_DOCUMENT');	
		$this->view->AttachFile = $objDocFun->DocSentAttachFile($arFileAttach,sizeof($arFileAttach),10,true,45);	
		//Truyen id ra man hinh
		$this->view->sentID = $sentID;
	}
	public function deleteAction(){	
		$objSent   = new Sent_modSent();	
		//Lay Id doi tuong can xoa
		$sListId = $this->_request->getParam('hdn_object_id_list',"");	
		//Goi phuong thuc xoa doi tuong
		$objSent->docSentDelete($sListId);
		$this->_redirect('sent/draff/index');	
	}	
	public function viewAction(){	
		$this->view->bodyTitle = 'CHI TIẾT VĂN BẢN DỰ THẢO';
		$ojbSysLib 		  = new Sys_Library();
		$objSent      	  = new Sent_modSent();	
		$objDocFun  	  = new Sys_Function_DocFunctions();
		$ojbSysInitConfig = new Sys_Init_Config();
			//lay ID va ten don vi soan thao 
		$iDepartmentId  = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');
		$sDepartmentName= Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_unit'],$iDepartmentId,'name');
		$this->view->sDepartmentName = $sDepartmentName;
		$iUserId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
		$sUserId = Sys_Function_DocFunctions::getNamePositionStaffByIdList($iUserId);
		$this->view->sUserId = $sUserId;
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
		//$arrParaSet = array("hdn_id_listtype"=>$iListTypeId, "sel_page"=>$piCurrentPage, "cbo_nuber_record_page"=>$piNumRowOnPage,"hdn_filter_xml_tag_list"=>$psFilterXmlTagList,"hdn_filter_xml_value_list"=>$psFilterXmlValueList);						
		//Luu gia tri vao bien session de indexAction lay lai ket qua chuyen cho View (Dieu kien loc)					
		//$_SESSION['seArrParameter'] = $arrParaSet;
		//Luu bien ket qua
		//$this->_request->setParams($arrParaSet);

		//Tro ve trang index												
		//$this->_redirect('Invitation/CreateInvitation/index/');
	}
	/**
	 * nguoi tao: nghiat
	 * ngay tao: 03/08/2010
	 * y nghia: phuong thuc trinh ky
	 */
	public function submitorderAction(){
		$this->view->bodyTitle1 = 'THÔNG TIN VĂN BẢN DỰ THẢO';
		$this->view->bodyTitle2 = 'CẬP NHẬT NỘI DUNG TRÌNH KÝ';
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
		$arrPositionConst =	$ojbSysInitConfig->_setLeaderPostionGroup();
		//Lay id VB du thao can Trinh ky
		$sentID = $this->_request->getParam('hdn_object_id','');
		$this->view->sentID = $sentID;
		//Lay ID Cap Quan/huyen cua don vi trien khai
		$IdDistrict = $_SESSION['OWNER_ID'];//$ojbSysInitConfig->_setParentOwnerId();
		$this->view->IdDistrict = $IdDistrict;
		if($IdDistrict == $_SESSION['OWNER_ID']){
			//lay ID va ten don vi cua can bo du thao -Cap quan huyen
			$iDepartmentId  = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');
			$this->view->iDepartmentId = $iDepartmentId;
			$arrPb = $objDocFun->docGetAllLeaderDepartment($arrPositionConst['_CONST_PHONG_BAN_GROUP'],$iDepartmentId);
			//var_dump($arrPb);
			$arrVp = $objDocFun->docGetAllUnitLeader($arrPositionConst['_CONST_VAN_PHONG_GROUP'],"arr_all_staff");
			//var_dump($arrVp);
			$arrUb = $objDocFun->docGetAllUnitLeader($arrPositionConst['_CONST_POSITION_GROUP'],"arr_all_staff");					
			// Kiem tra neu Mang danh sach lanh dao phong ban rong thi chuyen sang cho mang Lanh dao VP
			if($arrPb == ''){				
				$arrPb = $arrVp;
			}			
			//var_dump($arrPb);			
			//$this->view->search_doc_lanh_dao_vp = $objDocFun->doc_search_ajax($arrVp,"id","name","C_VP_NAME","hdn_vp_name",0,"position_code");			
			$this->view->search_doc_lanh_dao_pb = $objDocFun->doc_search_ajax($arrPb,"id","name","C_PB_NAME","hdn_pb_name",0,"position_code");
			$this->view->search_doc_lanh_dao_ub = $objDocFun->doc_search_ajax($arrUb,"id","name","C_UB_NAME","hdn_ub_name",0,"position_code");
			$this->view->search_doc_lanh_dao_px = '';
			
		}else{
			//Cap Phuong Xa
			$arrPx = $objDocFun->docGetAllUnitLeader($arrPositionConst['_CONST_PHUONG_XA_GROUP'],"arr_all_staff");
			$k = 0;
			for($i = 0;$i < sizeof($arrPx);$i++){
				if($arrPx[$i]['unit_id'] == $_SESSION['OWNER_ID']){
					$arrPxNSD[$k]['name'] = $arrPx[$i]['name'] ;
					$arrPxNSD[$k]['position_code'] = $arrPx[$i]['position_code'] ;
					$k++;
				}
			}
			$this->view->search_doc_lanh_dao_pb = '';
			$this->view->search_doc_lanh_dao_ub = '';
			$this->view->search_doc_lanh_dao_px = $objDocFun->doc_search_ajax($arrPxNSD,"id","name","C_PX_NAME","hdn_px_name",0,"position_code");
		}
		//Lay file da dinh kem tu truoc
		$arFileAttach = $objSent->DOC_GetAllDocumentFileAttach('','','T_DOC_SENT_DOCUMENT');	
		$this->view->AttachFile = $objDocFun->DocSentAttachFile($arFileAttach,sizeof($arFileAttach),10,true,61);	
		//Thuc hien upload file len o cung toi da 10 file
		$arrFileNameUpload = $ojbSysLib->_uploadFileList(10,$this->_request->getBaseUrl() . "/public/attach-file/",'FileName','!#~$|*');
		//Lay thong tin co ban cua van ban du thao
		$arrSent = $objSent->docDraftGetSingle($sentID,'','');
		$this->view->arrSent = $arrSent;	
		
		//Lay id, ten-chuc vu ng dang nhap
		$iUserId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
		$sDraffPositionName = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$iUserId,'position_code'). ' - ' . $ojbSysLib->_getItemAttrById($_SESSION['arr_all_staff'],$iUserId,'name');
		//ID va Ten phong ban
		$iDepartmentId  = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');
		$sDepartmentName= Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_unit'],$iDepartmentId,'name');
		//Lay ten va ID lanh dao duoc trinh ky
		$sLeaderName = $objFilter->filter($arrInput['C_PB_NAME']);
		if($objFilter->filter($arrInput['C_UB_NAME']) != ''){
			$sLeaderName = $objFilter->filter($arrInput['C_UB_NAME']);
		}
		if($objFilter->filter($arrInput['C_PX_NAME']) != ''){
			$sLeaderName = $objFilter->filter($arrInput['C_PX_NAME']);
		}
		//echo 'xxx:' . $sLeaderName.'<br>';
		$iLeaderId = $objDocFun->convertStaffNameToStaffId($sLeaderName);
		//Cap nhat noi dung trinh ky
		$arrParameter = array(	
								'PK_SENT_DOCUMENT'				=>$sentID,			
								'PK_DOC_WORK'					=>'',								
								'C_WORK_DATE'					=>$ojbSysLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['C_WORK_DATE'])).' '.date("h:i:s A"),
								'C_SUBMIT_CONTENT'				=>$objFilter->filter($arrInput['C_SUBMIT_CONTENT']),
								'C_FILE_NAME'					=>$arrFileNameUpload,
								'FK_STAFF'						=>$iUserId,
								'C_STAFF_POSITION_NAME'			=>$sDraffPositionName,
								'FK_UNIT'						=>$iDepartmentId,
								'C_UNIT_NAME'					=>$sDepartmentName,
								'C_STATUS'						=>$objFilter->filter($arrInput['C_STATUS']),
								'FK_LEADER'						=>$iLeaderId,	
								'C_LEADER_POSITION_NAME'		=>$sLeaderName																									
							);			
		if($this->_request->getParam('C_SUBMIT_CONTENT','') != ""){								
			$arrResult = "";	
			$arrResult = $objSent->docDraffSubmitOrderUpdate($arrParameter);	
		}				
		//Truong hop ghi va quay lai
		$psOption = $this->_request->getParam('hdh_option','');
		if ($psOption == "GHI"){
			//Tro ve trang index						
			$this->_redirect('sent/draff/index' );
		}
						
	}
		
} ?>
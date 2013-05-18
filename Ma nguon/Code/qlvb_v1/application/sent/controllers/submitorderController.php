<?php
class sent_submitorderController extends  Zend_Controller_Action {
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
		$this->view->currentModulCodeForLeft = "SUBMITORDER";
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
		//var_dump($_SESSION['arr_all_staff']);
		$iUserId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
		$iDepartmentId  = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');
		$iOwnerId = $_SESSION['OWNER_ID'];
		$arrInput = $this->_request->getParams();
		//lay modul left
		$getStatusFromMnuLeft = $this->_request->getParam('modul','');
		$this->view->getModulLeft = $getStatusFromMnuLeft;
		if($getStatusFromMnuLeft =='CBDT_DA_TRINH_KY'){
			$this->view->bodyTitle = 'DANH SÁCH VĂN BẢN ĐÃ TRÌNH KÝ';
		}
		if($getStatusFromMnuLeft =='CBDT_DA_DUYET'){
			$this->view->bodyTitle = 'DANH SÁCH VĂN BẢN ĐÃ DUYỆT';
		}
		if($getStatusFromMnuLeft =='DA_TRINH_KY'){
			$this->view->bodyTitle = 'DANH SÁCH VĂN BẢN ĐÃ TRÌNH KÝ';
		}
		if($getStatusFromMnuLeft =='DA_DUYET'){
			$this->view->bodyTitle = 'DANH SÁCH VĂN BẢN ĐÃ DUYỆT';
		}
		if($getStatusFromMnuLeft =='CHO_DUYET'){
			$this->view->bodyTitle = 'DANH SÁCH VĂN BẢN CHỜ DUYỆT';
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
		$this->view->sFullTextSearch = $sFullTextSearch;
		
		//----------------------------
		//Gia tri Value truyen vao 1:Can bo du thao, 2: LD PB, 3: LD VP, 4: LD UB
		$iValue = $objFunction->docTestUser($iUserId);
		//Cac Truong hop ung voi NSD la Can bo du thao
		if($iValue == 1 && $getStatusFromMnuLeft =='CBDT_DA_TRINH_KY'){
			//Trang thai trinh ky
			$sStatusList = 'TRINH_LDPX,LDPX_TRALAI,TRINH_LDPB,LDPB_TRALAI,CHUYEN_LDVP,LDVP_TRALAI,TRINH_LDUB,LDUB_TRALAI';
		}
		//Cac Truong hop ung voi NSD la Lanh dao Phong ban
		elseif($iValue == 2 && $getStatusFromMnuLeft =='CHO_DUYET'){
			//Trang thai trinh ky
			$sStatusList = 'TRINH_LDPB';
		}
		elseif($iValue == 2 && $getStatusFromMnuLeft =='DA_TRINH_KY'){
			//Trang thai trinh ky
			$sStatusList = 'CHUYEN_LDVP,TRINH_LDUB,LDUB_TRALAI,LDVP_TRALAI';
		}
		//Cac Truong hop ung voi NSD la Lanh dao Van phong
		elseif($iValue == 3 && $getStatusFromMnuLeft =='CHO_DUYET'){
			//Trang thai trinh ky
			$sStatusList = 'CHUYEN_LDVP';
		}
		elseif($iValue == 3 && $getStatusFromMnuLeft =='DA_TRINH_KY'){
			//Trang thai trinh ky
			$sStatusList = 'LDUB_TRALAI,TRINH_LDUB';
		}
		elseif($iValue == 4 && $getStatusFromMnuLeft =='CHO_DUYET'){
			//Trang thai trinh ky
			$sStatusList = 'TRINH_LDUB';
		}
		elseif($iValue == 4 && $getStatusFromMnuLeft =='DA_TRINH_KY'){
			//Trang thai trinh ky
			$sStatusList = 'ABC';
		}
		elseif($iValue == 5 && $getStatusFromMnuLeft =='CHO_DUYET'){
			//Trang thai trinh ky
			$sStatusList = 'TRINH_LDPX';
		}
		
		elseif($getStatusFromMnuLeft =='DA_DUYET' or $getStatusFromMnuLeft == 'CBDT_DA_DUYET'){
			//Trang thai trinh ky
			$sStatusList = 'CHO_DANG_KY,CHO_BAN_HANH,DA_BAN_HANH';
		}else{
			$sStatusList = 'ABC';
		}
		$this->view->iValue = $iValue;
		// Xu li du lieu
		$arrSent = $objSent->docDraffSubmitOrderGetAll($sFullTextSearch,$sStatusList,$iOwnerId,$iValue,$iUserId,$piCurrentPage,$piNumRowOnPage);
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
		//Tao doi tuong 
		$objSent   = new Sent_modSent();
		$objFilter = new Zend_Filter();	
		$objDocFun = new Sys_Function_DocFunctions();
		$ojbXmlLib = new Sys_Publib_Xml();
		$ojbSysLib = new Sys_Library();
		$objList   = new Listxml_modList();
		$ojbSysInitConfig = new Sys_Init_Config();
		//Lay id, ten-chuc vu ng dang nhap
		$iUserId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
		$this->view->iUserId = $iUserId;

		$sDraffPositionName = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$iUserId,'position_code'). ' - ' . $ojbSysLib->_getItemAttrById($_SESSION['arr_all_staff'],$iUserId,'name');
		//ID va Ten phong ban
		$iDepartmentId  = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');
		$sDepartmentName= Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_unit'],$iDepartmentId,'name');
				
		$getStatusFromMnuLeft = $this->_request->getParam('modul','');
		$sGetModulLeft = $this->_request->getParam('hdn_function_modul',0);
		$this->view->getModulLeft = $sGetModulLeft;
		//Lay gia tri value
		$iValue = $this->_request->getParam('hdn_ivalue','');
		$this->view->iValue = $iValue;
		$this->view->bodyTitle1 = 'THÔNG TIN VĂN BẢN DỰ THẢO';
		if($iValue == 1){
			$this->view->bodyTitle2 = 'CẬP NHẬT NỘI DUNG TRÌNH KÝ';
		}else{
			$this->view->bodyTitle2 = 'CẬP NHẬT NỘI DUNG PHÊ DUYỆT';
		}
		$this->view->bodyTitle3 = 'QUÁ TRÌNH XỬ LÝ VĂN BẢN TRÌNH KÝ';
		$arrInput = $this->_request->getParams();
		//Request ID VB di
		$sentID = $this->_request->getParam('hdn_object_id','');
		$this->view->sentID = $sentID;
		//Khai bao const
		$this->view->arrConst =	$ojbSysInitConfig->_setProjectPublicConst();
		$arrPositionConst =	$ojbSysInitConfig->_setLeaderPostionGroup();
		//Lay file da dinh kem tu truoc
		$arFileAttach = $objSent->DOC_GetAllDocumentFileAttach($sentID,'','T_DOC_SENT_DOCUMENT');	
		$this->view->AttachFile = $objDocFun->DocSentAttachFile($arFileAttach,sizeof($arFileAttach),10,true,61);	
		//Thuc hien upload file len o cung toi da 10 file
		$arrFileNameUpload = $ojbSysLib->_uploadFileList(10,$this->_request->getBaseUrl() . "/public/attach-file/",'FileName','!#~$|*');

		//Lay ID Cap Quan/huyen cua don vi trien khai
		$IdDistrict = $_SESSION['OWNER_ID'];//$ojbSysInitConfig->_setParentOwnerId();
		$this->view->IdDistrict = $IdDistrict;
		//echo $sDepartmentName;
		if($IdDistrict == $_SESSION['OWNER_ID']){
			//Lay danh sach lanh dao PB,UB,VP
			$arrPb = $objDocFun->docGetAllLeaderDepartment($arrPositionConst['_CONST_PHONG_BAN_GROUP'],$iDepartmentId);
			$arrUb = $objDocFun->docGetAllUnitLeader($arrPositionConst['_CONST_POSITION_GROUP'],"arr_all_staff");
			$arrVp = $objDocFun->docGetAllUnitLeader($arrPositionConst['_CONST_VAN_PHONG_GROUP'],"arr_all_staff");
			if(sizeof($arrPb) > 0 ){
				$this->view->search_doc_lanh_dao_pb = $objDocFun->doc_search_ajax($arrPb,"id","name","C_PB_NAME","hdn_pb_name",0,"position_code");
			}	
			$this->view->search_doc_lanh_dao_ub = $objDocFun->doc_search_ajax($arrUb,"id","name","C_UB_NAME","hdn_ub_name",0,"position_code");
			$this->view->search_doc_lanh_dao_vp = $objDocFun->doc_search_ajax($arrVp,"id","name","C_VP_NAME","hdn_vp_name",0,"position_code");
		}else{
			//Cap Phuong Xa
			$arrPx = $objDocFun->docGetAllUnitLeader("_CONST_PHUONG_XA_GROUP","arr_all_staff");
			$k = 0;
			for($i = 0;$i < sizeof($arrPx);$i++){
				if($arrPx[$i]['unit_id'] == $_SESSION['OWNER_ID']){
					$arrPxNSD[$k]['name'] = $arrPx[$i]['name'] ;
					$arrPxNSD[$k]['position_code'] = $arrPx[$i]['position_code'] ;
					$k++;
				}
			}
			$this->view->search_doc_lanh_dao_vp = '';
			$this->view->search_doc_lanh_dao_pb = '';
			$this->view->search_doc_lanh_dao_ub = '';
			$this->view->search_doc_lanh_dao_px = $objDocFun->doc_search_ajax($arrPxNSD,"id","name","C_PX_NAME","hdn_px_name",0,"position_code");
		}	
		$psOption = $this->_request->getParam('hdh_option','');
		
		//Lay ten va ID lanh dao duoc trinh ky
		$sLeaderName = $objFilter->filter($arrInput['C_PB_NAME']);
		if($objFilter->filter($arrInput['C_UB_NAME']) != ''){
			$sLeaderName = $objFilter->filter($arrInput['C_UB_NAME']);
		}
		if($objFilter->filter($arrInput['C_VP_NAME']) != ''){
			$sLeaderName = $objFilter->filter($arrInput['C_VP_NAME']);
		}
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
		//
		$psOption = $this->_request->getParam('hdh_option','');
		//Truong hop ghi va quay lai
		if ($psOption == "GHI"){
			//Tro ve trang index						
			$this->_redirect('sent/submitorder/index/modul/'.$sGetModulLeft);	
		}	
		//Lay thong tin co ban VB du thao
		$arrSent = $objSent->docDraffWorkGetsingle($sentID);
		$this->view->arrSent = $arrSent;
		//Lay danh sach qua trinh xu ly VB du thao
		$arrProcess = $objSent->docSubmitOrderProgressGetAll($sentID,'TRINHKY_VBDUTHAO');
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
		//var_dump($arrProcess);
		$arrAssign	=	$objSent->docAssignGetSingle($sentID,$iDepartmentId);	
		//var_dump($arrAssign);
		$this->view->arrAssign = $arrAssign;	
		// Dua ra man hinh danh sach 
		$this->view->arrProcessNew = $arrProcessNew;
		//var_dump($arrProcessNew);
		$this->view->arrProcessOld = $arrProcessOld;
	}
	public function editAction(){	
		//Tao doi tuong 
		$objSent   = new Sent_modSent();
		$objFilter = new Zend_Filter();	
		$objDocFun = new Sys_Function_DocFunctions();
		$ojbXmlLib = new Sys_Publib_Xml();
		$ojbSysLib = new Sys_Library();
		$objList   = new Listxml_modList();
		$ojbSysInitConfig = new Sys_Init_Config();
		//Lay id, ten-chuc vu ng dang nhap
		$iUserId = Sys_Publib_Library::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'id');
		$this->view->iUserId = $iUserId;
		$sDraffPositionName = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$iUserId,'position_code'). ' - ' . $ojbSysLib->_getItemAttrById($_SESSION['arr_all_staff'],$iUserId,'name');
		//ID va Ten phong ban
		$iDepartmentId  = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');
		$sDepartmentName= Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_unit'],$iDepartmentId,'name');
		$getStatusFromMnuLeft = $this->_request->getParam('modul','');
		$sGetModulLeft = $this->_request->getParam('hdn_function_modul',0);
		$this->view->getModulLeft = $sGetModulLeft;
		//Lay gia tri value
		$iValue = $this->_request->getParam('hdn_ivalue','');
		$this->view->iValue = $iValue;
		$this->view->bodyTitle1 = 'THÔNG TIN VĂN BẢN DỰ THẢO';
		if($iValue == 1){
			$this->view->bodyTitle2 = 'CẬP NHẬT NỘI DUNG TRÌNH KÝ';
		}else{
			$this->view->bodyTitle2 = 'CẬP NHẬT NỘI DUNG PHÊ DUYỆT';
		}
		$this->view->bodyTitle3 = 'QUÁ TRÌNH XỬ LÝ VĂN BẢN TRÌNH KÝ';
		$arrInput = $this->_request->getParams();

		//Khai bao const
		$this->view->arrConst =	$ojbSysInitConfig->_setProjectPublicConst();	
		//Request ID VB di
		$sentID = $this->_request->getParam('hdn_object_id','');
		$this->view->sentID = $sentID;
		//ID dau viec can chinh sua
		$workID= $this->_request->getParam('hdn_work_id','');
		$this->view->workID = $workID;
		//Thuc hien upload file len o cung toi da 10 file
		$arrFileNameUpload = $ojbSysLib->_uploadFileList(10,$this->_request->getBaseUrl() . "/public/attach-file/",'FileName','!#~$|*');
		//Lay ID Cap Quan/huyen cua don vi trien khai
		$IdDistrict = $ojbSysInitConfig->_setParentOwnerId();
		$this->view->IdDistrict = $IdDistrict;
		if($IdDistrict == $_SESSION['OWNER_ID']){
			//Lay danh sach lanh dao PB,UB,VP
			$arrPb = $objDocFun->docGetAllLeaderDepartment('LANH_DAO_PHONG_BAN',$iDepartmentId);
			if(sizeof($arrPb) > 0 ){
				$this->view->search_doc_lanh_dao_pb = $objDocFun->doc_search_ajax($arrPb,"id","name","C_PB_NAME","hdn_pb_name",0,"position_code");
			}
			$arrUb = $objDocFun->docGetAllUnitLeader("LANH_DAO_UB","arr_all_staff");
			$arrVp = $objDocFun->docGetAllUnitLeader("LANH_DAO_VP","arr_all_staff");
			$this->view->search_doc_lanh_dao_ub = $objDocFun->doc_search_ajax($arrUb,"id","name","C_UB_NAME","hdn_ub_name",0,"position_code");
			$this->view->search_doc_lanh_dao_vp = $objDocFun->doc_search_ajax($arrVp,"id","name","C_VP_NAME","hdn_vp_name",0,"position_code");
		}else{
			//Cap Phuong Xa
			$arrPx = $objDocFun->docGetAllUnitLeader("LANH_DAO_PHUONG","arr_all_staff");
			$k = 0;
			for($i = 0;$i < sizeof($arrPx);$i++){
				if($arrPx[$i]['unit_id'] == $_SESSION['OWNER_ID']){
					$arrPxNSD[$k]['name'] = $arrPx[$i]['name'] ;
					$arrPxNSD[$k]['position_code'] = $arrPx[$i]['position_code'] ;
					$k++;
				}
			}
			$this->view->search_doc_lanh_dao_vp = '';
			$this->view->search_doc_lanh_dao_pb = '';
			$this->view->search_doc_lanh_dao_ub = '';
			$this->view->search_doc_lanh_dao_px = $objDocFun->doc_search_ajax($arrPxNSD,"id","name","C_PX_NAME","hdn_px_name",0,"position_code");
		}	
		$psOption = $this->_request->getParam('hdh_option','');
		//Lay ten va ID lanh dao duoc trinh ky
		$sLeaderName = $objFilter->filter($arrInput['C_PB_NAME']);
		if($objFilter->filter($arrInput['C_UB_NAME']) != ''){
			$sLeaderName = $objFilter->filter($arrInput['C_UB_NAME']);
		}
		if($objFilter->filter($arrInput['C_VP_NAME']) != ''){
			$sLeaderName = $objFilter->filter($arrInput['C_VP_NAME']);
		}
		if($objFilter->filter($arrInput['C_PX_NAME']) != ''){
			$sLeaderName = $objFilter->filter($arrInput['C_PX_NAME']);
		}
		$iLeaderId = $objDocFun->convertStaffNameToStaffId($sLeaderName);
		//Cap nhat noi dung trinh ky
		$arrParameter = array(	
								'PK_SENT_DOCUMENT'				=>$sentID,			
								'PK_DOC_WORK'					=>$workID,								
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
		//Lay thong tin co ban vd du thao
		$arrSent = $objSent->docDraffWorkGetsingle($sentID);
		$this->view->arrSent = $arrSent;
		//Lay thong tin cong viec can chinh sua
		$arrWork = $objSent->docDraffSubmitOrderGetsingle($sentID,$workID);
		$this->view->arrWork = $arrWork;
		//Lay danh sach qua trinh xu ly VB du thao
		$arrProcess = $objSent->docSubmitOrderProgressGetAll($sentID,'TRINHKY_VBDUTHAO');
		//Lay file da dinh kem tu truoc
		$arFileAttach = $objSent->DOC_GetAllDocumentFileAttach($sentID,'','T_DOC_SENT_DOCUMENT');	
		$this->view->AttachFile = $objDocFun->DocSentAttachFile($arFileAttach,sizeof($arFileAttach),10,true,61);
		//
		$arFileAttach2 = $objSent->DOC_GetAllDocumentFileAttach($workID,'','T_DOC_WORK');	
		$this->view->AttachFile2 = $objDocFun->DocSentAttachFile($arFileAttach2,sizeof($arFileAttach2),10,true,61);
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
	public function viewAction(){	
		$this->view->bodyTitle = 'CHI TIẾT VĂN BẢN DỰ THẢO';
		$ojbSysLib 		  = new Sys_Library();
		$objSent      	  = new Sent_modSent();	
		$objDocFun  	  = new Sys_Function_DocFunctions();
		$ojbSysInitConfig = new Sys_Init_Config();
		$sGetModulLeft = $this->_request->getParam('hdn_function_modul',0);
		$this->view->getModulLeft = $sGetModulLeft;
			//lay ID va ten don vi soan thao 
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
		//Lay don vi
		$iOwnerName = $objDocFun->getNameUnitByIdUnitList($_SESSION['OWNER_ID']);
		$this->view->iOwnerName = $iOwnerName;
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
		$iValue = $this->_request->getParam('hdn_ivalue','');	
		//Goi phuong thuc xoa doi tuong
		$objSent->docDraftProcessDelete($sListId);
		$sGetModulLeft = $this->_request->getParam('hdn_function_modul',0);
		$this->_redirect('sent/submitorder/add/modul/'.$sGetModulLeft.'?hdn_object_id='.$sSentId.'&hdn_ivalue='.$iValue);	
	}	
} ?>
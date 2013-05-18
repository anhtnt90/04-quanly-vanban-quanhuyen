<?php
class calendar_UnitController extends  Zend_Controller_Action {
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
		Zend_Loader::loadClass('Sent_modSent');	
		//Goi lop Listxml_modList
		
		Zend_Loader::loadClass('dashboard_modWebMenu');
		//Lay tat ca cac chuyen muc		
		$objWebMenu = new dashboard_modWebMenu();
		$arrResul = $objWebMenu->WebMenuGetAll('4',$_SESSION['OWNER_CODE'],'3','1');
		$this->view->arrMenu = $arrResul;					
		$sliidvisit = $this->_request->getParam('sliid','');
		//Neu khong co gia tri thì lay trong cookie	
		if ($sliidvisit == "" || is_null($sliidvisit) || !isset($sliidvisit)){
			$sliidvisit = Sys_Library::_getCookie("headervisit");
		}else{
			Sys_Library::_createCookie("headervisit",$sliidvisit);
		}		
		$this->view->sliidvisit = $sliidvisit;	
		$sleftmenu = $this->_request->getParam('sleftmenu','');		
		if ($sleftmenu == "" || is_null($sleftmenu) || !isset($sleftmenu)){
			$sleftmenu = Sys_Library::_getCookie("leftvisit");
		}else{
			Sys_Library::_createCookie("leftvisit",$sleftmenu);
		}		
		$this->view->sleftmenu = $sleftmenu;				
		Zend_Loader::loadClass('calendar_modcalendarUnit');			
		$this->view->JSPublicConst = $objConfig->_setJavaScriptPublicVariable();					
		// Load tat ca cac file Js va Css
		$this->view->LoadAllFileJsCss = Sys_Publib_Library::_getAllFileJavaScriptCss('','js','util.js,js_calendar.js,jsSchedule.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','ajax.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','jquery-1.4.2.min.js,jQuery.equalHeights.js',',','js'). Sys_Publib_Library::_getAllFileJavaScriptCss('','js/LibSearch','actb_search.js,common_search.js',',','js');
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
		$this->view->currentModulCode = "SCHEDULE_UNIT";				
		//Modul chuc nang		
		$this->view->currentModulCodeForLeft = "SCHEDULE_UNIT_WEEK";				
		$this->view->currentUpdate = "SCHEDULE_UNIT";			
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
	 * Idea : Phuong thuc hien thi Lich ca nhan
	 *
	 */
	public function indexAction(){					
		//Lay URL	
		$sUrl = $_SERVER['REQUEST_URI'];
		
		//Lay trang thai 
		$sStatus = $this->_request->getParam('status','');
		$this->view->sStatus = $sStatus;					
		// Tao doi tuong Zend_Filter
		$objFilter = new Zend_Filter();
		$ojbSysLib = new Sys_Library();
		$objDocFun = new Sys_Function_DocFunctions();
		//Lay cac gia tri const
		$ojbSysInitConfig = new Sys_Init_Config();
		$sParentOwnerCode = $ojbSysInitConfig->_setOnerCode();		
		$arrPositionConst =	$ojbSysInitConfig->_setLeaderPostionGroup();	
		$arrCount = Sys_Init_Config::_setProjectPublicConst();
		$this->view->arrCount = $arrCount;		
		$this->view->NumberRowOnPage 	= $this->_ConstPublic['NumberRowOnPage'];	
		$this->view->iCountElement = count($this->view->arrAllRecord);		
		// KHAI BAO VA LAY CAC GIA TRI CHO UC-LICH DON VI						
		$this->view->bodyTitle = "LỊCH CÔNG TÁC TUẦN CỦA ĐƠN VỊ";		
		$arrInput = $this->_request->getParams();								
		$objSchedule = new calendar_modcalendarUnit();	
		$v_staff_id =$_SESSION['staff_id'];							
		$iCurrentUpdate = $this->_request->getParam('htn_schedule_update','');						
		$iYear       	= $this->_request->getParam('hdn_year','');
		//$iWeek 	  		= $this->_request->getParam('hdn_week','');	
		//echo "WEEK " .  $iWeek;
		//Lay gia tri trong session
		if (isset($_SESSION['seArrParameter'])){
			$arrParaInSession = $_SESSION['seArrParameter'];
			$iEdit = $arrParaInSession['hdn_edit'];
			$iApprove = $arrParaInSession['hdn_approve'];
			$sOwner_name = $arrParaInSession['hdn_owner_code'];	
			$iWeek = $arrParaInSession['hdn_week'];		
			//Xoa gia tri trong session
			unset($_SESSION['seArrParameter']);								
		}else{
			if($this->_request->getParam('menuid','')!=''){
				// LAY ID CHUYEN MUC
				$sMenuID = $this->_request->getParam('menuid','');		
				$arrMenu = $objSchedule->WebArticlePermissionCheck($v_staff_id,'EDIT_APPROVE',0);
				$iEdit =0;
				$iApprove =0;	
				foreach ($arrMenu as $Resuft) {			
					if($sMenuID == $Resuft['PK_WEB_MENU']){
						$iEdit = $Resuft['C_EDIT'];
						$iApprove = $Resuft['C_APPROVE'];
						break;
					}							
				}	
				$sOwner_name  	 = $this->_request->getParam('hdn_schedule','');
			}else{
				$iEdit 			= $objFilter->filter($arrInput['hdn_edit']);
				$iApprove 		= $objFilter->filter($arrInput['hdn_approve']);
				$sOwner_name 		= $objFilter->filter($arrInput['hdn_owner_code']);
				$iWeek = $objFilter->filter($arrInput['hdn_week']);  
			}
		}
		if($sOwner_name==''){
			$sOwner_name = $_SESSION['OWNER_CODE'];					
		}
		$this->view->iEdit = $iEdit;	
		$this->view->iApprove = $iApprove;		
		$this->view->sOwner_name = $sOwner_name;

		
		if($iYear ==''){
			$iYear = date('Y');		
		}
		if($iWeek ==''){			
			$iWeek = date("W");
		}		

		$this->view->iWeek = $iWeek;
		$arryWekk = $ojbSysLib->Generate_weeks_of_year($iYear, -1, $iWeek);
		$this->view->arryWekk = $arryWekk;		
		$arryYear = $ojbSysLib->_generate_year_input(2008,date("Y")+1,$iYear);
		$this->view->arryYear = $arryYear;							
		$arrDateInWeek = $ojbSysLib->_generate_days_on_week_of_year($iYear,$iWeek,'true','');		
		$this->view->arrDateInWeek = $arrDateInWeek;									
		if($iEdit){
			$arrySchedule_Unit = $objSchedule->ScheduleUnitGetAll($iWeek,$iYear,$sOwner_name,0);	
		}else{
			$arrySchedule_Unit = $objSchedule->ScheduleUnitGetAll($iWeek,$iYear,$sOwner_name,1);
		}				
		$this->view->arrySchedule_Unit = $arrySchedule_Unit;																		
	}		
	public function addAction(){		
		//Lay URL	
		$sUrl = $_SERVER['REQUEST_URI'];
		//Lay trang thai 
		$sStatus = $this->_request->getParam('status','');
		$this->view->sStatus = $sStatus;					
		// Tao doi tuong Zend_Filter
		$objSent   = new Sent_modSent();
		$objFilter = new Zend_Filter();
		$ojbSysLib = new Sys_Library();
		$objDocFun = new Sys_Function_DocFunctions();
		//Lay cac gia tri const
		$ojbSysInitConfig = new Sys_Init_Config();
		$arrPositionConst =	$ojbSysInitConfig->_setLeaderPostionGroup();			
		$arrCount = Sys_Init_Config::_setProjectPublicConst();
		$this->view->arrCount = $arrCount;		
		$this->view->NumberRowOnPage 	= $this->_ConstPublic['NumberRowOnPage'];	
		$this->view->iCountElement = count($this->view->arrAllRecord);		
		$arrSigner = $objSent->getSignByUnit('DM_NGUOI_KY',$_SESSION['arr_all_staff']);				
		$arrWardsLeader 	= $objDocFun->docGetAllUnitLeader($arrPositionConst['_CONST_PHUONG_XA_GROUP'],"arr_all_staff");
		//Xac dinh nguoi chu tri la o cap Quan,huyen,tp
		if($arrSigner){
			$this->view->search_textselectbox_signer = $objDocFun->doc_search_ajax($arrSigner,"C_CODE","C_NAME","C_NAME_JOINER","hdn_signer_position_name",0,"",1);
		}
		//Xac dinh nguoi chu tri la o cap phuong xa
		if($arrWardsLeader){
			$this->view->search_textselectbox_signer = $objDocFun->doc_search_ajax($arrWardsLeader,"position_code","name","C_NAME_JOINER","hdn_signer_position_name",0,"",1);			
		}					
		// KHAI BAO VA LAY CAC GIA TRI CHO UC-LICH DON VI
		$this->view->bodyTitle = "CẬP NHẬT THÔNG TIN LỊCH CÔNG TÁC TUẦN";		
		$arrInput = $this->_request->getParams();								
		$objScheduleUnit = new schedule_modscheduleUnit();	
		$iCreate_Staff_Id =$_SESSION['staff_id'];				
		$iCurrentPage = $this->_request->getParam('hdn_current_page','');		
		$iCurrentUpdate = $this->_request->getParam('htn_schedule_update','');						
		$iYear       	= $this->_request->getParam('hdn_year','');
		$iDay			= $objFilter->filter($arrInput['hdn_day']);		
		$iCheckApprove	= $objFilter->filter($arrInput['hdn_approve_schedule']);							
		//echo $iSchedule_type_owner; exit;		
		//Lay gia tri trong session
		if (isset($_SESSION['seArrParameter'])){
			$arrParaInSession = $_SESSION['seArrParameter'];
			$iEdit = $arrParaInSession['hdn_edit'];
			$iApprove = $arrParaInSession['hdn_approve'];
			$iWeek = $arrParaInSession['hdn_week'];	
			//Xoa gia tri trong session
			//unset($_SESSION['seArrParameter']);								
		}else{
			$iEdit 			= $objFilter->filter($arrInput['hdn_edit']);
			$iApprove 		= $objFilter->filter($arrInput['hdn_approve']);
			$sOwner_name 	= $objFilter->filter($arrInput['hdn_owner_code']);
		}
		$this->view->iEdit = $iEdit;	
		$this->view->iApprove = $iApprove;	
		if($this->_request->getParam('hdn_week','') !=''){		
			$iWeek 	  		= $this->_request->getParam('hdn_week','');	
			//$iWeek = date("W");
		}		
		$this->view->iWeek = $iWeek;
		$arrParaSet = array("hdn_edit"=>$iEdit,"hdn_approve"=>$iApprove,"hdn_owner_code"=>$sOwner_name,"hdn_week"=>$iWeek);
		//var_dump($arrParaSet); exit;
		$_SESSION['seArrParameter'] = $arrParaSet;
		$this->_request->setParams($arrParaSet);		
		$sApprove_id	="";
		$iStatus		="";
		if($iCheckApprove =='1'){
			$sApprove_id = $_SESSION['staff_id'];	
			$iStatus = '1';			
		}		
		$sOwner_name = $_SESSION['OWNER_CODE'];	
		$iOneDay ='';						
		if($iDay =='0')
			$iDay ="THU_2"; $iOneDay =0; 
		if($iDay =='1')
			$iDay ="THU_3"; $iOneDay =1; 
		if($iDay =='2')
			$iDay ="THU_4";	$iOneDay =2; 
		if($iDay =='3')
			$iDay ="THU_5";	$iOneDay =3; 
		if($iDay =='4')
			$iDay ="THU_6";	$iOneDay =4; 
		if($iDay =='5')
			$iDay ="THU_7";	$iOneDay =5; 
		if($iDay =='6')
			$iDay ="THU_8";	$iOneDay =6; 					
		if($iYear ==''){
			$iYear = date('Y');		
		}
				
		$arryWekk = $ojbSysLib->Generate_weeks_of_year($iYear, -1, $iWeek);
		$this->view->arryWekk = $arryWekk;		
		$arryYear = $ojbSysLib->_generate_year_input(2008,date("Y")+1,$iYear);
		$this->view->arryYear = $arryYear;							
		$arrDayInWeek = $ojbSysLib->_generate_days($iYear,$iWeek,false,0);
		$this->view->arrDayInWeek = $arrDayInWeek;	
		$iOption = $objFilter->filter($arrInput['hdh_option']); 				
		//UPDATE VAO CSDL		
			if ($objFilter->filter($arrInput['C_WORK_NAME']) !=""){													
				$arrParameter = array(	
									'PK_SCHEDULE_UNIT'					=>'',										
									'FK_CREATE_STAFF'					=>$iCreate_Staff_Id,
									'FK_APPROVE_STAFF'					=>$sApprove_id,
									'FK_JOINER_ID_LIST'					=>$objFilter->filter($arrInput['FK_JOINER_ID_LIST']),
									'C_NAME_JOINER'						=>$objFilter->filter($arrInput['C_NAME_JOINER']),
									'C_WEEK'							=>$iWeek,
									'C_YEAR'							=>$iYear,
									'C_DAY'								=>$iDay,
									'C_DAY_PART'						=>$objFilter->filter($arrInput['hdn_part_time']),									
									'C_START_TIME'						=>$objFilter->filter($arrInput['C_START_TIME']),
									'C_FINISH_TIME'						=>$objFilter->filter($arrInput['C_FINISH_TIME']),
									'C_WORK_NAME'						=>$objFilter->filter($arrInput['C_WORK_NAME']),
									'C_WORK_CONTENT'					=>$objFilter->filter($arrInput['C_WORK_CONTENT']),								
									'C_PLACE'							=>$objFilter->filter($arrInput['C_PLACE']),
									'C_PREPARE_ORGAN'					=>$objFilter->filter($arrInput['C_PREPARE_ORGAN']),
									'C_ATTENDING'						=>$objFilter->filter($arrInput['C_ATTENDING']),
									'C_OWNER_CODE'						=>$sOwner_name,
									'C_STATUS'							=>$iStatus,
							);
				$Result = "";			
				$Result = $objScheduleUnit->ScheduleUnitUpdate($arrParameter);														
			}
			//Truong hop ghi va them moi
			if ($iOption == "GHI_THEM_MOI"){
				//Ghi va them moi				
				$this->_redirect('schedule/unit/add/');
			}
				//Ghi va quay lai from danh sach
			if ($iOption == "GHI_QUAYLAI"){					
				$this->_redirect('schedule/unit/index/');
			}						
		}
	public  function editAction(){			
		$sUrl = $_SERVER['REQUEST_URI'];
		//Lay trang thai 
		$sStatus = $this->_request->getParam('status','');
		$this->view->sStatus = $sStatus;					
		// Tao doi tuong Zend_Filter
		$objFilter = new Zend_Filter();
		$ojbSysLib = new Sys_Library();
		$objDocFun = new Sys_Function_DocFunctions();
		$objSent   = new Sent_modSent();
		//Lay cac gia tri const
		$ojbSysInitConfig = new Sys_Init_Config();
		$arrPositionConst =	$ojbSysInitConfig->_setLeaderPostionGroup();	
		$arrCount = Sys_Init_Config::_setProjectPublicConst();
		$this->view->arrCount = $arrCount;		
		$this->view->NumberRowOnPage 	= $this->_ConstPublic['NumberRowOnPage'];	
		$this->view->iCountElement = count($this->view->arrAllRecord);		
		// KHAI BAO VA LAY CAC GIA TRI CHO UC-LICH DON VI
		$this->view->bodyTitle = "CẬP NHẬT THÔNG TIN LỊCH CÔNG TÁC TUẦN";					
		$arrInput = $this->_request->getParams();								
		$objSchedule = new schedule_modscheduleUnit();	
		$v_staff_id =$_SESSION['staff_id'];				
		$iCurrentPage = $this->_request->getParam('hdn_current_page','');		
		$iCurrentUpdate = $this->_request->getParam('htn_schedule_update','');						
		$iYear       	= $this->_request->getParam('hdn_year','');
		$iWeek 	  		= $this->_request->getParam('hdn_week','');
		$iOption = $objFilter->filter($arrInput['hdh_option']);									
		$iCheckApprove	= $objFilter->filter($arrInput['hdn_approve_schedule']);
		//lay quyen
		$iEdit 			= $objFilter->filter($arrInput['hdn_edit']);
		$iApprove 		= $objFilter->filter($arrInput['hdn_approve']);
		$sOwner_name 	= $objFilter->filter($arrInput['hdn_owner_code']);
		if($sOwner_name!=$_SESSION['OWNER_CODE']){
			$iEdit = 0;
			$iApprove = 0;
		}
		//echo $sOwner_name;
		$this->view->iEdit = $iEdit;	
		$this->view->iApprove = $iApprove;	
		$this->view->sOwner_name = $sOwner_name;
		$this->view->iWeek = $iWeek;	
		$arrParaSet = array("hdn_edit"=>$iEdit,"hdn_approve"=>$iApprove,"hdn_owner_code"=>$sOwner_name,"hdn_week"=>$iWeek);
		//var_dump($arrParaSet); exit;
		$_SESSION['seArrParameter'] = $arrParaSet;
		$this->_request->setParams($arrParaSet);		
		$sApprove_id	="";
		$iStatus		="";
		if($iCheckApprove =='1'){
			$sApprove_id = $_SESSION['staff_id'];	
			$iStatus = '1';			
		}		
		
		
		
		$arrSigner = $objSent->getSignByUnit('DM_NGUOI_KY',$_SESSION['arr_all_staff']);				
		$arrWardsLeader 	= $objDocFun->docGetAllUnitLeader($arrPositionConst['_CONST_PHUONG_XA_GROUP'],"arr_all_staff");
		//Xac dinh nguoi chu tri la o cap quan huyen tp
		if($arrSigner){
			$this->view->search_textselectbox_signer = $objDocFun->doc_search_ajax($arrSigner,"C_CODE","C_NAME","C_NAME_JOINER","hdn_signer_position_name",0,"",1);
		}
		//Xac dinh nguoi chu tri la o cap phuong xa
		if($arrWardsLeader){
			$this->view->search_textselectbox_signer = $objDocFun->doc_search_ajax($arrWardsLeader,"position_code","name","C_NAME_JOINER","hdn_signer_position_name",0,"",1);			
		}			
		if($iYear ==''){
			$iYear = date('Y');		
		}
		if($iWeek ==''){			
			$iWeek = date("W");		
		}
		if($iYear ==''){
			$iYear = date('Y');		
		}					
		$arryWekk = $ojbSysLib->Generate_weeks_of_year($iYear, -1, $iWeek);
		$this->view->arryWekk = $arryWekk;		
		$arryYear = $ojbSysLib->_generate_year_input(2008,date("Y")+1,$iYear);
		$this->view->arryYear = $arryYear;							
		$arrDateInWeek = $ojbSysLib->_generate_days_on_week_of_year($iYear,$iWeek,'true','');		
		$this->view->arrDateInWeek = $arrDateInWeek;							
		$iScheduleID =  $this->_request->getParam('hdn_object_id','');		
		$arrResulSingle = $objSchedule->ScheduleUnitGetSingle($iScheduleID);			
		$iDay = $arrResulSingle[0]['C_DAY'];
		$sDay_after_change	= $objFilter->filter($arrInput['hdn_day']);																
			if($iDay =='THU_2')
				$iDay ="0";
			if($iDay =='THU_3')
				$iDay ="1"; 
			if($iDay =='THU_4')
				$iDay ="2";	
			if($iDay =='THU_5')
				$iDay ="3";	
			if($iDay =='THU_6')
				$iDay ="4";	
			if($iDay =='THU_7')
				$iDay ="5";	
			if($iDay =='THU_8')
				$iDay ="6";					
			if($sDay_after_change =='0')
				$sDay ="THU_2";
			if($sDay_after_change =='1')
				$sDay ="THU_3"; 
			if($sDay_after_change =='2')
				$sDay ="THU_4";	
			if($sDay_after_change =='3')
				$sDay ="THU_5";	
			if($sDay_after_change =='4')
				$sDay ="THU_6";	
			if($sDay_after_change =='5')
				$sDay ="THU_7";	
			if($sDay_after_change =='6')
				$sDay ="THU_8";
		if($sDay_after_change ==''){
			$arrDayInWeek = $ojbSysLib->_generate_days($iYear,$iWeek,false,$iDay);	
		}else{
			$arrDayInWeek = $ojbSysLib->_generate_days($iYear,$iWeek,false,$sDay_after_change);	
		}																			
		$this->view->arrDayInWeek = $arrDayInWeek;																							
	
		if($iOption =='_CAP_NHAT'){			
			$arrParameter = array(	
									'PK_SCHEDULE_UNIT'					=>$iScheduleID,
									'FK_CREATE_STAFF'					=>$iCreate_Staff_Id,
									'FK_APPROVE_STAFF'					=>$sApprove_id,
									'FK_JOINER_ID_LIST'					=>$objFilter->filter($arrInput['FK_JOINER_ID_LIST']),
									'C_NAME_JOINER'						=>$objFilter->filter($arrInput['C_NAME_JOINER']),
									'C_WEEK'							=>$iWeek,
									'C_YEAR'							=>$iYear,
									'C_DAY'								=>$sDay,
									'C_DAY_PART'						=>$objFilter->filter($arrInput['hdn_part_time']),									
									'C_START_TIME'						=>$objFilter->filter($arrInput['C_START_TIME']),
									'C_FINISH_TIME'						=>$objFilter->filter($arrInput['C_FINISH_TIME']),
									'C_WORK_NAME'						=>$objFilter->filter($arrInput['C_WORK_NAME']),
									'C_WORK_CONTENT'					=>$objFilter->filter($arrInput['C_WORK_CONTENT']),								
									'C_PLACE'							=>$objFilter->filter($arrInput['C_PLACE']),
									'C_PREPARE_ORGAN'					=>$objFilter->filter($arrInput['C_PREPARE_ORGAN']),
									'C_ATTENDING'						=>$objFilter->filter($arrInput['C_ATTENDING']),
									'C_OWNER_CODE'						=>$_SESSION['OWNER_CODE'],
									'C_STATUS'							=>$iCheckApprove,
							);
				$Result = "";			
				$Result = $objSchedule->ScheduleUnitUpdate($arrParameter);				
				$this->_redirect('schedule/unit/index/');
		}			
		$arrResulSingle = $objSchedule->ScheduleUnitGetSingle($iScheduleID);
		$this->view->arrResulSingle = $arrResulSingle;					
}
	public  function approveAction(){
		// Tao doi tuong cho lop xu ly du lieu lien quan modul	
		$objSchedule = new schedule_modscheduleUnit();
		$objFilter = new Zend_Filter();	
		$ojbSysLib = new Sys_Library();
		$iYear       	= $this->_request->getParam('hdn_year','');
		$iWeek 	  		= $this->_request->getParam('hdn_week','');
		$sOwner_name = $_SESSION['OWNER_CODE'];	
		$sApprove_id = $_SESSION['staff_id'];		
		$arrInput = $this->_request->getParams();
		$iScheduleID_list	= $objFilter->filter($arrInput['hdn_object_id_list']);	
		$iCheckApprove	= $objFilter->filter($arrInput['hdn_approve_schedule']);		
		$sApprove_id = $_SESSION['staff_id'];	
		$iStatus = 1;
		//lay quyen
		$iEdit 			= $objFilter->filter($arrInput['hdn_edit']);
		$iApprove 		= $objFilter->filter($arrInput['hdn_approve']);
		$this->view->iEdit = $iEdit;	
		$this->view->iApprove = $iApprove;	
		$this->view->iWeek = $iWeek;
		$arrParaSet = array("hdn_edit"=>$iEdit,"hdn_approve"=>$iApprove,"hdn_week"=>$iWeek);
		//var_dump($arrParaSet); exit;
		$_SESSION['seArrParameter'] = $arrParaSet;
		$this->_request->setParams($arrParaSet);					
		if($iScheduleID_list <> ''){		
			$Result = $objSchedule->ScheduleUnitApprove($iScheduleID_list,$sApprove_id,$iStatus);	
		}	
		$this->_redirect('schedule/unit/index/');							
	}			
	public function deleteAction(){	
		// Tao doi tuong cho lop xu ly du lieu lien quan modul	
		$objSchedule = new schedule_modscheduleUnit();
		$objFilter = new Zend_Filter();	
		$ojbSysLib = new Sys_Library();
		$iYear       	= $this->_request->getParam('hdn_year','');
		$iWeek 	  		= $this->_request->getParam('hdn_week','');
		$sOwner_name = $_SESSION['OWNER_CODE'];										
		if($iYear ==''){
			$iYear = date('Y');		
		}
		if($iWeek ==''){			
			$iWeek = date("W");		
		}
		if($iYear ==''){
			$iYear = date('Y');		
		}	
		//lay quyen
		$iEdit 			= $this->_request->getParam('hdn_edit','');
		$iApprove 		= $this->_request->getParam('hdn_approve',''); 
		$sOwner_name 	= $this->_request->getParam('hdn_owner_code');
		$this->view->iEdit = $iEdit;			
		$this->view->iApprove = $iApprove;	
		$this->view->iWeek = $iWeek;
		// Lay ID cua Lich				
		if($this->_request->isPost()){	
			$arrParaSet = array("hdn_edit"=>$iEdit,"hdn_approve"=>$iApprove,"hdn_owner_code"=>$sOwner_name,"hdn_week"=>$iWeek);
			//var_dump($arrParaSet); exit;
			$_SESSION['seArrParameter'] = $arrParaSet;
			$this->_request->setParams($arrParaSet);
			// Lay toan bo tham so truyen tu form			
			$arrInput = $this->_request->getParams();							
			//Lay Id doi tuong can xoa
			$iScheduleID_list	= $objFilter->filter($arrInput['hdn_object_id_list']);				
			if ($iScheduleID_list != ""){
				$sRetError = $objSchedule->ScheduleUnitDelete($iScheduleID_list);
				// Neu co loi			
				if($sRetError != null || $sRetError != '' ){											
					echo "<script type='text/javascript'>";
					echo "alert('$sRetError');\n";				
					echo "</script>";
				}else {		
					//Tro ve trang index												
					$this->_redirect('schedule/unit/index/');				
				}
			}
		}	
	}
	public function printAction(){
		//echo "OK"; exit; 
		$ojbSysLib = new Sys_Library();
		$objDocFun = new Sys_Function_DocFunctions();		
		$ojbSysInitConfig = new Sys_Init_Config();	
		$objFilter = new Zend_Filter();
		$v_SitePath = $ojbSysInitConfig->_setWebSitePath();	
		$arrInput = $this->_request->getParams();
		$objSchedule = new schedule_modscheduleUnit();					
		$iOwnerId = $_SESSION['OWNER_ID'];		
		$sNameUnit = $objDocFun->getNameUnitByIdUnitList($iOwnerId);
		//echo $sNameUnit;// exit;
		$path = $_SERVER['SCRIPT_FILENAME'];
		$path = substr($path, 0, -9);	
		$path = str_replace("/", "\\", $path) . "templates\\schedule\\Unit\ScheduleUnit.htm";
		//echo $path; exit;
		$v_html_header = $ojbSysLib->_read_file($path);
		$v_exporttype = $this->_request->getParam('hdn_exporttype','');
		//echo $v_exporttype; exit;				
		if($v_exporttype == 1){
		   $report_file = 'Lich_UBND.html';
		}elseif ($v_exporttype == 2) {
		   $report_file = 'Lich_UBND.doc';
		}					
		$sConten = '';			
		$iStaff_id =$_SESSION['staff_id'];				
		$iCurrentPage = $this->_request->getParam('hdn_current_page','');		
		$iCurrentUpdate = $this->_request->getParam('htn_schedule_update','');								
		$iYear       = $this->_request->getParam('hdn_year','');					
		$iWeek 	  = $this->_request->getParam('hdn_week','');														
		$iDate 	  = $this->_request->getParam('hdn_date',''); 							
		$sTitle   = "<b>Lịch công tác tuần của cơ quan<b>";
		//Lay thong tin ve Tuan trong mot Nam
		if($iYear ==''){
			$iYear = date('Y');		
		}
		if($iYear ==''){			
			$iWeek = date("W");
		}	
		$sOwner_name = $_SESSION['OWNER_CODE'];		
		$sOwner = $ojbSysInitConfig->_setOnerName();			
		$titleDay = split("/",date('d/m/Y'));		
		$sDate_html = "<i>" .  $ojbSysInitConfig->_setOnerNameSmall(). ", ng&#224;y ".$titleDay[0]." th&#225;ng ".$titleDay[1]." n&#259;m ".$titleDay[2]. "<i>";				
		$sChedule_type_owner =  $this->_request->getParam('hdn_print_for_owner',''); 			
		if($sChedule_type_owner){
			$sOwner_name = $sChedule_type_owner;
		}	
		$arrCount = Sys_Init_Config::_setProjectPublicConst();
		$arrySchedule_Unit = $objSchedule->ScheduleUnitGetAll($iWeek,$iYear,$sOwner_name,1);		
		//Dinh nghia cac ngay trong tuan
		$arrDayInWeek = array();	
		$arrDayInWeek['1']['C_CODE'] ='THU_2'; 		$arrDayInWeek['1']['C_NAME'] ='Th&#7913; hai';
		$arrDayInWeek['2']['C_CODE'] ='THU_3'; 		$arrDayInWeek['2']['C_NAME'] ='Th&#7913; ba';
		$arrDayInWeek['3']['C_CODE'] ='THU_4'; 		$arrDayInWeek['3']['C_NAME'] ='Th&#7913; t&#432;';
		$arrDayInWeek['4']['C_CODE'] ='THU_5'; 		$arrDayInWeek['4']['C_NAME'] ='Th&#7913; n&#259;m';
		$arrDayInWeek['5']['C_CODE'] ='THU_6'; 		$arrDayInWeek['5']['C_NAME'] ='Th&#7913; s&#225;u';
		$arrDayInWeek['6']['C_CODE'] ='THU_7'; 		$arrDayInWeek['6']['C_NAME'] ='Th&#7913; b&#7843;y';
		$arrDayInWeek['7']['C_CODE'] ='THU_8'; 		$arrDayInWeek['7']['C_NAME'] ='Ch&#7911; nh&#7853;t';				
		$arrDay = array();
		$arrPartDay = array();
		for ($i=1; $i<= 7; $i++){
			for ($j=0; $j< sizeof($arrySchedule_Unit) ; $j++){			
				if($arrDayInWeek[$i]['C_CODE']==$arrySchedule_Unit[$j]['C_DAY']){
					$arrDay[$i] = $arrDay[$i] + 1;
						if($arrySchedule_Unit[$j]['C_DAY_PART']=='BUOI_SANG'){
							$arrPartDay[0][$i] = $arrPartDay['0'][$i] + 1;
						}
						if($arrySchedule_Unit[$j]['C_DAY_PART']=='BUOI_CHIEU'){
							$arrPartDay[1][$i] = $arrPartDay['1'][$i] + 1;
					}		
				}
			}
		}
		// rowspan thu trong tuan 
		$arrThursday[0] = $arrDay[1]; //3
		$arrThursday[$arrDay[1]] = $arrDay[2];
		$arrThursday[$arrDay[1]+$arrDay[2]] = $arrDay[3];
		$arrThursday[$arrDay[1]+$arrDay[2]+$arrDay[3]] = $arrDay[4];
		$arrThursday[$arrDay[1]+$arrDay[2]+$arrDay[3]+$arrDay[4]] = $arrDay[5];
		$arrThursday[$arrDay[1]+$arrDay[2]+$arrDay[3]+$arrDay[4]+$arrDay[5]] = $arrDay[6];
		$arrThursday[$arrDay[1]+$arrDay[2]+$arrDay[3]+$arrDay[4]+$arrDay[5]+$arrDay[6]] = $arrDay[7];
		// rowspan thu va buoi sang trong tuan
		$arrAm[0] = $arrPartDay[0][1]; //3
		$arrAm[$arrPartDay[0][1]+$arrPartDay[1][1]] = $arrPartDay[0][2];//2
		$arrAm[$arrPartDay[0][1]+$arrPartDay[1][1]+$arrPartDay[0][2]+$arrPartDay[1][2]] = $arrPartDay[0][3];
		$arrAm[$arrPartDay[0][1]+$arrPartDay[1][1]+$arrPartDay[0][2]+$arrPartDay[1][2]+$arrPartDay[0][3]+$arrPartDay[1][3]] = $arrPartDay[0][4];
		$arrAm[$arrPartDay[0][1]+$arrPartDay[1][1]+$arrPartDay[0][2]+$arrPartDay[1][2]+$arrPartDay[0][3]+$arrPartDay[1][3]+$arrPartDay[0][4]+$arrPartDay[1][4]] = $arrPartDay[0][5];
		$arrAm[$arrPartDay[0][1]+$arrPartDay[1][1]+$arrPartDay[0][2]+$arrPartDay[1][2]+$arrPartDay[0][3]+$arrPartDay[1][3]+$arrPartDay[0][4]+$arrPartDay[1][4]+$arrPartDay[0][5]+$arrPartDay[1][5]] = $arrPartDay[0][6];
		$arrAm[$arrPartDay[0][1]+$arrPartDay[1][1]+$arrPartDay[0][2]+$arrPartDay[1][2]+$arrPartDay[0][3]+$arrPartDay[1][3]+$arrPartDay[0][4]+$arrPartDay[1][4]+$arrPartDay[0][5]+$arrPartDay[1][5]+$arrPartDay[0][6]+$arrPartDay[1][6]] = $arrPartDay[0][7];
		// rowspan thu va buoi chieu trong tuan
		$arrPm[$arrPartDay[0][1]] = $arrPartDay[1][1];
		$arrPm[$arrPartDay[0][1]+$arrPartDay[1][1]+$arrPartDay[0][2]] = $arrPartDay[1][2];
		$arrPm[$arrPartDay[0][1]+$arrPartDay[1][1]+$arrPartDay[0][2]+$arrPartDay[1][2]+$arrPartDay[0][3]] = $arrPartDay[1][3];
		$arrPm[$arrPartDay[0][1]+$arrPartDay[1][1]+$arrPartDay[0][2]+$arrPartDay[1][2]+$arrPartDay[0][3]+$arrPartDay[1][3]+$arrPartDay[0][4]] = $arrPartDay[1][4];
		$arrPm[$arrPartDay[0][1]+$arrPartDay[1][1]+$arrPartDay[0][2]+$arrPartDay[1][2]+$arrPartDay[0][3]+$arrPartDay[1][3]+$arrPartDay[0][4]+$arrPartDay[1][4]+$arrPartDay[0][5]] = $arrPartDay[1][5];
		$arrPm[$arrPartDay[0][1]+$arrPartDay[1][1]+$arrPartDay[0][2]+$arrPartDay[1][2]+$arrPartDay[0][3]+$arrPartDay[1][3]+$arrPartDay[0][4]+$arrPartDay[1][4]+$arrPartDay[0][5]+$arrPartDay[1][5]+$arrPartDay[0][6]] = $arrPartDay[1][6];
		$arrPm[$arrPartDay[0][1]+$arrPartDay[1][1]+$arrPartDay[0][2]+$arrPartDay[1][2]+$arrPartDay[0][3]+$arrPartDay[1][3]+$arrPartDay[0][4]+$arrPartDay[1][4]+$arrPartDay[0][5]+$arrPartDay[1][5]+$arrPartDay[0][6]+$arrPartDay[1][6]+$arrPartDay[0][7]] = $arrPartDay[1][7];
				$sConten = $sConten .'<table class="schedule_staff" cellpadding="0" cellspacing="0" width="100%" id="table1">';								
					$sConten = $sConten .'<col width="8%">';
					$sConten = $sConten .'<col width="8%">';
					$sConten = $sConten .'<col width="33%">';
					$sConten = $sConten .'<col width="18%">';
					$sConten = $sConten .'<col width="18%">';
					//$sConten = $sConten .'<col width="10%">';
					$sConten = $sConten .'<tr class="header" style="height:25px;">';						
						$sConten = $sConten .'<td colspan="2" class="title" style="padding:0px; font-weight:bold; border-top: solid 1px #000000;" align="center">'.$arrCount['_THOI_GIAN'] .'</td>';		
						$sConten = $sConten .'<td class="title" style="padding:0px; font-weight:bold; border-top: solid 1px #000000;" align="center">'.$arrCount['_NOI_DUNG_CONG_VIEC'] .'</td>';
						$sConten = $sConten .'<td class="title" style="padding:0px; font-weight:bold; border-top: solid 1px #000000;" align="center">'.$arrCount['_CHU_TRI'] .'</td>';
						$sConten = $sConten .'<td class="title" style="padding:0px; font-weight:bold; border-top: solid 1px #000000;" align="center">'.$arrCount['_DIA_DIEM'] .'</td>';
					//	$sConten = $sConten .'<td class="title" style="padding:0px; font-weight:bold; border-top: solid 1px #000000;" align="center">'.$arrCount['_TRANG_THAI'] .'</td>';
				  $sConten = $sConten .'</tr>	';												
				  for ($i=1; $i<= 7 ; $i++){	
					if($arrDay[$i]>0){						
						for ($j=0; $j< sizeof($arrySchedule_Unit) ; $j++){														
							$sContent ='';
							$iStatus  ='';
							$iStarttime  =  $arrySchedule_Unit[$j]['C_START_TIME']; 
							$iFinishtime = $arrySchedule_Unit[$j]['C_FINISH_TIME']; 									
							$sStarttime  = "<font color='#000000' size='3'><i><u>" . $iStarttime ."</i></u></font>";
							$sFinishtime = "<font color='#000000' size='3'><i><u> -> " . $iFinishtime ."</i></u></font>&nbsp;";				
							if($iFinishtime == ''){
								$sStarttime = "<font color='#000000' size='3'><i><u>" . $iStarttime ."</i></u></font>&nbsp;";
								$sFinishtime ='';
							}				
					$iC_name =   $ojbSysLib->_replaceBadChar($arrySchedule_Unit[$j]['C_WORK_NAME']);																								
					$sContent = $sStarttime.$sFinishtime.$iC_name;																		
					$iStatus = $arrySchedule_Unit[$j]['C_STATUS']; 
					$sStatus='';
					if($iStatus == 1){ $sStatus ='Đã duyệt'; }else{ $sStatus ='Chưa duyệt'; }
					if($arrDayInWeek[$i]['C_CODE']==$arrySchedule_Unit[$j]['C_DAY']){
					 $sConten = $sConten .'<tr>';		 		    	 							
					 if($j==0||$j==$arrDay[1]||$j==$arrDay[1]+$arrDay[2]||$j==$arrDay[1]+$arrDay[2]+$arrDay[3]||$j==$arrDay[1]+$arrDay[2]+$arrDay[3]+$arrDay[4]
							||$j==$arrDay[1]+$arrDay[2]+$arrDay[3]+$arrDay[4]+$arrDay[5]||$j==$arrDay[1]+$arrDay[2]+$arrDay[3]+$arrDay[4]+$arrDay[5]+$arrDay[6]
					){ 						
							$sConten = $sConten .'<td rowspan=' . $arrThursday[$j].'><i>' .$arrDayInWeek[$i]['C_NAME'] .'</td>';
					}					
					if(($arrySchedule_Unit[$j]['C_DAY_PART']=='BUOI_SANG')&& ($j==0||$j==$arrPartDay[0][1]+$arrPartDay[1][1]
							||$j== $arrPartDay[0][1]+$arrPartDay[1][1]+$arrPartDay[0][2]+$arrPartDay[1][2]
							||$j== $arrPartDay[0][1]+$arrPartDay[1][1]+$arrPartDay[0][2]+$arrPartDay[1][2]+$arrPartDay[0][3]+$arrPartDay[1][3]
							||$j== $arrPartDay[0][1]+$arrPartDay[1][1]+$arrPartDay[0][2]+$arrPartDay[1][2]+$arrPartDay[0][3]+$arrPartDay[1][3]+$arrPartDay[0][4]+$arrPartDay[1][4]
							||$j== $arrPartDay[0][1]+$arrPartDay[1][1]+$arrPartDay[0][2]+$arrPartDay[1][2]+$arrPartDay[0][3]+$arrPartDay[1][3]+$arrPartDay[0][4]+$arrPartDay[1][4]+$arrPartDay[0][5]+$arrPartDay[1][5]
							||$j== $arrPartDay[0][1]+$arrPartDay[1][1]+$arrPartDay[0][2]+$arrPartDay[1][2]+$arrPartDay[0][3]+$arrPartDay[1][3]+$arrPartDay[0][4]+$arrPartDay[1][4]+$arrPartDay[0][5]+$arrPartDay[1][5]+$arrPartDay[0][6]+$arrPartDay[1][6]
						)){ 																									
						$sConten = $sConten .'<td rowspan='.$arrAm[$j].'><i>Buổi sáng</td>'; 						
					}
					if(($arrySchedule_Unit[$j]['C_DAY_PART']=='BUOI_CHIEU')&& ($j==$arrPartDay[0][1]||$j==$arrPartDay[0][1]+$arrPartDay[1][1]+$arrPartDay[0][2]
							||$j==  $arrPartDay[0][1]+$arrPartDay[1][1]+$arrPartDay[0][2]+$arrPartDay[1][2]+$arrPartDay[0][3]
							||$j==  $arrPartDay[0][1]+$arrPartDay[1][1]+$arrPartDay[0][2]+$arrPartDay[1][2]+$arrPartDay[0][3]+$arrPartDay[1][3]+$arrPartDay[0][4]
							||$j==  $arrPartDay[0][1]+$arrPartDay[1][1]+$arrPartDay[0][2]+$arrPartDay[1][2]+$arrPartDay[0][3]+$arrPartDay[1][3]+$arrPartDay[0][4]+$arrPartDay[1][4]+$arrPartDay[0][5]
							||$j==  $arrPartDay[0][1]+$arrPartDay[1][1]+$arrPartDay[0][2]+$arrPartDay[1][2]+$arrPartDay[0][3]+$arrPartDay[1][3]+$arrPartDay[0][4]+$arrPartDay[1][4]+$arrPartDay[0][5]+$arrPartDay[1][5]+$arrPartDay[0][6]
							||$j==  $arrPartDay[0][1]+$arrPartDay[1][1]+$arrPartDay[0][2]+$arrPartDay[1][2]+$arrPartDay[0][3]+$arrPartDay[1][3]+$arrPartDay[0][4]+$arrPartDay[1][4]+$arrPartDay[0][5]+$arrPartDay[1][5]+$arrPartDay[0][6]+$arrPartDay[1][6]+$arrPartDay[0][7]
						)){ 			
						$sConten = $sConten .'<td rowspan='.$arrPm[$j].'><i>Buổi chiều</td>'; 														
						}
						$sConten = $sConten .'<td style="text-align:justify;"> '.$sContent. '&nbsp;</td>'; 		
						$sConten = $sConten .'<td style="text-align:justify;"> '.$ojbSysLib->_replaceBadChar($arrySchedule_Unit[$j]['C_NAME_JOINER']).'&nbsp;</td>';
						$sConten = $sConten .'<td style="text-align:justify;"> '.$ojbSysLib->_replaceBadChar($arrySchedule_Unit[$j]['C_PLACE']).'&nbsp;</td>';
						//$sConten = $sConten .'<td style="text-align:center;">'.$sStatus.'&nbsp;</td>';					   		  
						$sConten = $sConten .'</tr>';	 														
					}	
				}	
			}				
		}						
		$sConten = $sConten .'</table>';							
		//echo $sConten; exit;			
		$v_resul = str_replace("#TITLE#",$sTitle,$v_html_header);				
		$v_resul = str_replace("#WEEK#",$iDate,$v_resul);
		$v_resul = str_replace("#UNIT_NAME#",$sNameUnit,$v_resul);		
		$v_resul = str_replace("#DATE#",$sDate_html,$v_resul);		
		$v_resul = str_replace("#OWNER_NAME#",$sOwner,$v_resul);					
		$v_resul = str_replace("#CONTENT#",$sConten,$v_resul);							
		//$v_resul = str_replace("#TEST#",$iC_name,$v_resul);		
		// Duong dan file report	
		$path = $_SERVER['SCRIPT_FILENAME'];
		$path = substr($path, 0, -9);	
		$my_report_file = str_replace("/", "\\", $path) . "public\\" . $report_file;
		//echo $v_resul; exit;
		$ojbSysLib->_write_file($my_report_file,$v_resul);
		// doc file pdf len trinh duyet				
		$my_report_file = 'http://'.$_SERVER['HTTP_HOST'].$v_SitePath.'public/'.$report_file;
		//echo $my_report_file; exit;
		$this->view->my_report_file = $my_report_file; 			
	} 																	
}
?>
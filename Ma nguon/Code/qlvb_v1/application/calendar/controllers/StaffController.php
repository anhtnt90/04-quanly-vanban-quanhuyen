<?php
class calendar_StaffController extends  Zend_Controller_Action {	
	public $_publicPermission;
	public function init(){				
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
		Zend_Loader::loadClass('calendar_modcalendarStaff');			
		$this->view->JSPublicConst = $objConfig->_setJavaScriptPublicVariable();					
		// Load tat ca cac file Js va Css
		$this->view->LoadAllFileJsCss = Sys_Publib_Library::_getAllFileJavaScriptCss('','js','util.js,js_calendar.js,jsSchedule.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','ajax.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','sys-js','jquery-1.4.2.min.js,jQuery.equalHeights.js',',','js'). Sys_Publib_Library::_getAllFileJavaScriptCss('','js/LibSearch','actb_search.js,common_search.js',',','js');
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
		$this->view->currentModulCode = "SCHEDULE_STAFF";				
		//Modul chuc nang						
		$this->view->currentModulCodeForLeft ="SCHEDULE_STAFF";
		$this->view->getStatusLeftMenu = $this->_request->getParam('status','');
		$this->view->currentUpdate = "SCHEDULE_STAFF";			
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
		$arrPositionConst =	$ojbSysInitConfig->_setLeaderPostionGroup();		
		// Tieu de man hinh danh sach
		$this->view->bodyTitle = "LỊCH LÀM VIỆC CÁ NHÂN";
		//$this->view->bodyTitle = "Lịch làm việc của đồng chí ".$ojbSysLib->_InforStaff(); 				
		$arrInput = $this->_request->getParams();						
		//var_dump($arrInput);
		$objSchedule = new calendar_modcalendarStaff();	
		$v_staff_id =$_SESSION['staff_id'];				
		$iCurrentPage = $this->_request->getParam('hdn_current_page','');		
		$iCurrentUpdate = $this->_request->getParam('htn_schedule_update','');						
		$v_year       = $this->_request->getParam('hdn_year','');
		$v_week 	  = $this->_request->getParam('hdn_week','');		
		$arrySchedule_Staff = $objSchedule->Schedule_StaffGetSingle($v_staff_id,$v_week,$v_year );	
		$this->view->arrySchedule_Staff = $arrySchedule_Staff;		
		//var_dump($arrySchedule_Staff);								
		//Lay thong tin ve Tuan trong mot Nam
		if($v_year ==''){
			$v_year = date('Y');		
		}
		if($v_week ==''){			
			$v_week = date("W");
		}								
		$arryWekk = $ojbSysLib->Generate_weeks_of_year($v_year, -1, $v_week);
		$this->view->arryWekk = $arryWekk;
		//Lay thong tin Nam
		$arryYear = $ojbSysLib->_generate_year_input(2008,date("Y")+1,$v_year);
		$this->view->arryYear = $arryYear;							
		$arrDateInWeek = $ojbSysLib->_generate_days_on_week_of_year($v_year,$v_week,'true','');		
		$this->view->arrDateInWeek = $arrDateInWeek;		
		$arrCount = Sys_Init_Config::_setProjectPublicConst();
		$this->view->arrCount = $arrCount;		
		$this->view->NumberRowOnPage 	= $this->_ConstPublic['NumberRowOnPage'];	
		$this->view->iCountElement = count($this->view->arrAllRecord);
		if($iCurrentUpdate == 'SCHEDULE_STAFF_UPDATE'){										
			$scheduleID = $this->_request->getParam('hdn_object_id','');
			$this->view->sscheduleID = $scheduleID;			
			/*LAY DU LIEU THU 2*/
			$v_mon_am = $this->_request->getParam('C_MON_AM','');
			$v_mon_pm = $this->_request->getParam('C_MON_PM','');
			$v_mon = $ojbSysLib->_replaceBadChar($v_mon_am."!~~!".$v_mon_pm);
			/*LAY DU LIEU THU 3*/
			$v_tue_am = $this->_request->getParam('C_TUE_AM','');
			$v_tue_pm = $this->_request->getParam('C_TUE_PM','');
			$v_tue = $ojbSysLib->_replaceBadChar($v_tue_am."!~~!".$v_tue_pm);
			/*LAY DU LIEU THU 4*/
			$v_wed_am = $this->_request->getParam('C_WED_AM','');
			$v_wed_pm = $this->_request->getParam('C_WED_PM','');
			$v_wed = $ojbSysLib->_replaceBadChar($v_wed_am."!~~!".$v_wed_pm);			
			/*LAY DU LIEU THU 5*/
			$v_thu_am = $this->_request->getParam('C_THU_AM','');
			$v_thu_pm = $this->_request->getParam('C_THU_PM','');
			$v_thu = $ojbSysLib->_replaceBadChar($v_thu_am."!~~!".$v_thu_pm);			
			/*LAY DU LIEU THU 6*/
			$v_fri_am = $this->_request->getParam('C_FRI_AM','');
			$v_fri_pm = $this->_request->getParam('C_FRI_PM','');
			$v_fri = $ojbSysLib->_replaceBadChar($v_fri_am."!~~!".$v_fri_pm);
			/*LAY DU LIEU THU 7*/
			$v_sat_am = $this->_request->getParam('C_SAT_AM','');
			$v_sat_pm = $this->_request->getParam('C_SAT_PM','');
			$v_sat = $ojbSysLib->_replaceBadChar($v_sat_am."!~~!".$v_sat_pm);
			/*LAY DU LIEU NGAY CN*/
			$v_sun_am = $this->_request->getParam('C_SUN_AM','');
			$v_sun_pm = $this->_request->getParam('C_SUN_PM','');
			$v_sun = $ojbSysLib->_replaceBadChar($v_sun_am."!~~!".$v_sun_pm);							
			$arrParameter = array(	
									'PK_SCHEDULE_STAFF'			=>$scheduleID,		
									'FK_STAFF'					=>$v_staff_id,	
									'C_WEEK'					=>$v_week,
									'C_YEAR'					=>$v_year,
									'C_MON'						=>$v_mon,
									'C_TUE'						=>$v_tue,
									'C_WED'						=>$v_wed,
									'C_THU'						=>$v_thu,
									'C_FRI'						=>$v_fri,
									'C_SAT'						=>$v_sat,
									'C_SUN'						=>$v_sun,
								);								
			$arrySchedule_Staff = "";							
			if($arrParameter !=''){
				$arrySchedule_Staff = $objSchedule->Schedule_StaffUpdate($arrParameter);									
			}				
			$v_year       = $this->_request->getParam('hdn_year','');
			$v_week       = $this->_request->getParam('hdn_week','');		
			$this->view->v_year= $v_year;
			$this->view->v_week= $v_week;				
		}	
		$arrySchedule_Staff = $objSchedule->Schedule_StaffGetSingle($v_staff_id,$v_week,$v_year );	
		$this->view->arrySchedule_Staff = $arrySchedule_Staff;			
	}	
	
public function departmentsAction(){				
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
			$arrPositionConst =	$ojbSysInitConfig->_setLeaderPostionGroup();		
			// Tieu de man hinh danh sach
			$this->view->bodyTitle = "LỊCH LÀM VIỆC PHÒNG BAN";					
			//$this->view->bodyTitle = "Lịch làm việc của đồng chí ".$ojbSysLib->_InforStaff(); 				
			$arrInput = $this->_request->getParams();									
			$iDepartmentId  = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');
			$this->view->iDepartmentId = $iDepartmentId;
			
			$arrDepartmentLeader = $objDocFun->docGetAllLeaderDepartment($arrPositionConst['_CONST_PHONG_BAN_GROUP'],$iDepartmentId);								
			$this->view->arrDepartmentLeader = $arrDepartmentLeader;																
			$arrVp = $objDocFun->docGetAllUnitLeader($arrPositionConst['_CONST_VAN_PHONG_GROUP'],"arr_all_staff");
			$this->view->arrVp = $arrVp;
			if($arrDepartmentLeader == ''){
			
				$arrDepartmentLeader = $arrVp;
			}
					
			$v_count_leader = sizeof($arrDepartmentLeader);			
			$v_list_id_leader =$arrDepartmentLeader[0]['id'];
			for($i=1;$i<$v_count_leader;$i++){		
				$v_list_id_leader = $v_list_id_leader.','.$arrDepartmentLeader[$i]['id'];			
			}									
			$objSchedule = new calendar_modcalendarStaff();									   							
			$iCurrentPage = $this->_request->getParam('hdn_current_page','');		
			$iCurrentUpdate = $this->_request->getParam('htn_schedule_update','');						
			$v_year       = $this->_request->getParam('hdn_year','');
			$v_week 	  = $this->_request->getParam('hdn_week','');																		
			if($v_year ==''){
				$v_year = date('Y');		
			}
			if($v_week ==''){			
				$v_week = date("W");
			}						
			$arrySchedule_Staff = $objSchedule->Schedule_LeaderGetAll($v_list_id_leader,$v_week,$v_year );			
			$this->view->arrySchedule_Staff = $arrySchedule_Staff;						
			$arryWekk = $ojbSysLib->Generate_weeks_of_year($v_year, -1, $v_week);
			$this->view->arryWekk = $arryWekk;
			//Lay thong tin Nam
			$arryYear = $ojbSysLib->_generate_year_input(2008,date("Y")+1,$v_year);
			$this->view->arryYear = $arryYear;							
			$arrDateInWeek = $ojbSysLib->_generate_days_on_week_of_year($v_year,$v_week,'true','');		
			$this->view->arrDateInWeek = $arrDateInWeek;		
			$arrCount = Sys_Init_Config::_setProjectPublicConst();
			$this->view->arrCount = $arrCount;							
	}
public function unitleaderAction(){				
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
			$arrPositionConst =	$ojbSysInitConfig->_setLeaderPostionGroup();		
			// Tieu de man hinh danh sach
			$this->view->bodyTitle = "LỊCH LÀM VIỆC LÃNH ĐẠO ĐƠN VỊ";					
			//$this->view->bodyTitle = "Lịch làm việc của đồng chí ".$ojbSysLib->_InforStaff(); 				
			$arrInput = $this->_request->getParams();									
			$iDepartmentId  = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');
			$this->view->iDepartmentId = $iDepartmentId;							
			//Mang lay lanh dao phong ban theo ID cua nguoi dang nhap
			$arrDepartmentLeader = $objDocFun->docGetAllLeaderDepartment($arrPositionConst['_CONST_LEADER_POSITION_GROUP_MAIN'],$iDepartmentId);
			//Mang lay lanh dao UBND Phuong xa
			$arrWardsLeader 	= $objDocFun->docGetAllUnitLeader($arrPositionConst['_CONST_PHUONG_XA_GROUP'],"arr_all_staff");
			$this->view->arrWardsLeader = $arrWardsLeader;
			//Mang lay lanh dao UBND Quan,huyen,TP			
			$arrUnitLeader = $objDocFun->docGetAllUnitLeader($arrPositionConst['_CONST_POSITION_GROUP'],"arr_all_staff");
			//Mang lay lanh dao VP
			$arrVp = $objDocFun->docGetAllUnitLeader($arrPositionConst['_CONST_VAN_PHONG_GROUP'],"arr_all_staff");			
			$this->view->arrUnitLeader = $arrUnitLeader;
			if($arrUnitLeader ==''){				
				$arrUnitLeader =$arrWardsLeader;
			}						
			$v_count_leader = sizeof($arrUnitLeader);
			$v_list_id_leader =$arrUnitLeader[0]['id'];
			for($i=1;$i<$v_count_leader;$i++){		
				$v_list_id_leader = $v_list_id_leader.','.$arrUnitLeader[$i]['id'];			
			}					
			$objSchedule = new calendar_modcalendarStaff();										
			$iCurrentPage = $this->_request->getParam('hdn_current_page','');		
			$iCurrentUpdate = $this->_request->getParam('htn_schedule_update','');						
			$v_year       = $this->_request->getParam('hdn_year','');
			$v_week 	  = $this->_request->getParam('hdn_week','');																		
			if($v_year ==''){
				$v_year = date('Y');		
			}
			if($v_week ==''){			
				$v_week = date("W");
			}						
			$arrySchedule_Staff = $objSchedule->Schedule_LeaderGetAll($v_list_id_leader,$v_week,$v_year );			
			$this->view->arrySchedule_Staff = $arrySchedule_Staff;						
			$arryWekk = $ojbSysLib->Generate_weeks_of_year($v_year, -1, $v_week);
			$this->view->arryWekk = $arryWekk;
			//Lay thong tin Nam
			$arryYear = $ojbSysLib->_generate_year_input(2008,date("Y")+1,$v_year);
			$this->view->arryYear = $arryYear;							
			$arrDateInWeek = $ojbSysLib->_generate_days_on_week_of_year($v_year,$v_week,'true','');		
			$this->view->arrDateInWeek = $arrDateInWeek;		
			$arrCount = Sys_Init_Config::_setProjectPublicConst();
			$this->view->arrCount = $arrCount;							
	}
	public function printtodocAction(){
		echo 'OK'; exit;
		$ojbSysLib = new Sys_Library();
		$objDocFun = new Sys_Function_DocFunctions();
		$objSchedule = new calendar_modcalendarStaff();	
		$ojbSysInitConfig = new Sys_Init_Config();	
		$v_SitePath = $ojbSysInitConfig->_setWebSitePath();					
		$iOwnerId = $_SESSION['OWNER_ID'];		
		$path = $_SERVER['SCRIPT_FILENAME'];
		$path = substr($path, 0, -9);	
		$path = str_replace("/", "\\", $path) . "templates\\schedule\\Staff\\staff.htm";
		echo $path; exit;
		$v_html_header = $ojbSysLib->_read_file($path);
		$v_exporttype = $this->_request->getParam('hdn_exporttype','');
		//echo $v_exporttype; exit;				
		if($v_exporttype == 1){
		   $report_file = 'Lich_LD.html';
		}elseif ($v_exporttype == 2) {
		   $report_file = 'Lich_LD.doc';
		}					
		$v_conten = '';
		//echo $v_html_header; exit;	
		$v_staff_id =$_SESSION['staff_id'];				
		$iCurrentPage = $this->_request->getParam('hdn_current_page','');		
		$iCurrentUpdate = $this->_request->getParam('htn_schedule_update','');								
		$v_year       = $this->_request->getParam('hdn_year','');					
		$v_week 	  = $this->_request->getParam('hdn_week','');														
		$v_date 	  = $this->_request->getParam('hdn_date',''); 
		$v_title_staff = "LỊCH LÀM VIỆC CÁ NHÂN";							
		//Lay thong tin ve Tuan trong mot Nam
		if($v_year ==''){
			$v_year = date('Y');		
		}
		if($v_week ==''){			
			$v_week = date("W");
		}					
		$arrySchedule_Staff = $objSchedule->Schedule_StaffGetSingle($v_staff_id,$v_week,$v_year );			
		$arrDateInWeek = $ojbSysLib->_generate_days_on_week_of_year($v_year,$v_week,'true','');
		$arry_date_in_week = explode('!@#',$arrDateInWeek);
		$this->view->arrySchedule_Staff = $arrySchedule_Staff;				
		//echo $arrySchedule_Staff[0]['C_MON']; exit;		
		$v_mon = explode('!~~!',$arrySchedule_Staff[0]['C_MON']); // THU 2
		$v_tue = explode('!~~!',$arrySchedule_Staff[0]['C_TUE']); // THU 3
		$v_wed = explode('!~~!',$arrySchedule_Staff[0]['C_WED']); // THU 4
		$v_thu = explode('!~~!',$arrySchedule_Staff[0]['C_THU']); // THU 5
		$v_fri = explode('!~~!',$arrySchedule_Staff[0]['C_FRI']); // THU 6
		$v_sat = explode('!~~!',$arrySchedule_Staff[0]['C_SAT']); // THU 7
		$v_sun = explode('!~~!',$arrySchedule_Staff[0]['C_SUN']); // CN																
		//echo $v_wed[1]; exit;		
		$v_staff_name = explode("-",$InforStaff = Sys_Publib_Library::_InforStaff());			
		$v_resul = str_replace("#date#",$v_date,$v_html_header);		
		$v_resul = str_replace("#staff_name#",$v_staff_name[0],$v_resul);				
		$v_resul = str_replace("#v_mon_am#",$ojbSysLib->_isbreakcontent($v_mon[0]),$v_resul);
		$v_resul = str_replace("#v_mon_pm#",$ojbSysLib->_isbreakcontent($v_mon[1]),$v_resul);				
		$v_resul = str_replace("#v_tue_am#",$ojbSysLib->_isbreakcontent($v_tue[0]),$v_resul);
		$v_resul = str_replace("#v_tue_pm#",$ojbSysLib->_isbreakcontent($v_tue[1]),$v_resul);				
		$v_resul = str_replace("#v_wed_am#",$ojbSysLib->_isbreakcontent($v_wed[0]),$v_resul);
		$v_resul = str_replace("#v_wed_pm#",$ojbSysLib->_isbreakcontent($v_wed[1]),$v_resul);
		$v_resul = str_replace("#v_thu_am#",$ojbSysLib->_isbreakcontent($v_thu[0]),$v_resul);
		$v_resul = str_replace("#v_thu_pm#",$ojbSysLib->_isbreakcontent($v_thu[1]),$v_resul);		
		$v_resul = str_replace("#v_fri_am#",$ojbSysLib->_isbreakcontent($v_fri[0]),$v_resul);
		$v_resul = str_replace("#v_fri_pm#",$ojbSysLib->_isbreakcontent($v_fri[1]),$v_resul);
		$v_resul = str_replace("#v_sat_am#",$ojbSysLib->_isbreakcontent($v_sat[0]),$v_resul);
		$v_resul = str_replace("#v_sat_pm#",$ojbSysLib->_isbreakcontent($v_sat[1]),$v_resul);		
		$v_resul = str_replace("#v_sun_am#",$ojbSysLib->_isbreakcontent($v_sun[0]),$v_resul);
		$v_resul = str_replace("#v_sun_pm#",$ojbSysLib->_isbreakcontent($v_sun[1]),$v_resul);			
		$v_resul = str_replace("#C_MON#",$arry_date_in_week[0],$v_resul);
		$v_resul = str_replace("#C_TUE#",$arry_date_in_week[1],$v_resul);
		$v_resul = str_replace("#C_WED#",$arry_date_in_week[2],$v_resul);
		$v_resul = str_replace("#C_THU#",$arry_date_in_week[3],$v_resul);
		$v_resul = str_replace("#C_FRI#",$arry_date_in_week[4],$v_resul);
		$v_resul = str_replace("#C_SAT#",$arry_date_in_week[5],$v_resul);		
		$v_resul = str_replace("#C_SUN#",$arry_date_in_week[6],$v_resul);
		$v_resul = str_replace("#week_date#",$v_date,$v_resul);
		$v_resul = str_replace("#TITLE#",$v_title_staff,$v_resul);		
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
public function printleaderAction(){
		$ojbSysLib = new Sys_Library();
		$objDocFun = new Sys_Function_DocFunctions();
		$objSchedule = new calendar_modcalendarStaff();	
		$ojbSysInitConfig = new Sys_Init_Config();
		$v_SitePath = $ojbSysInitConfig->_setWebSitePath();							
		$iOwnerId = $_SESSION['OWNER_ID'];		
		$path = $_SERVER['SCRIPT_FILENAME'];
		$path = substr($path, 0, -9);	
		$path = str_replace("/", "\\", $path) . "templates\\schedule\\Staff\\leader.html";
		//echo $path; exit;
		$v_html_header = $ojbSysLib->_read_file($path);	
		$v_exporttype = $this->_request->getParam('hdn_exporttype','');
		//echo $v_exporttype; exit;				
		if($v_exporttype == 1){
		   $report_file = 'Lich_LD.html';
		}elseif ($v_exporttype == 2) {
		   $report_file = 'Lich_LD.doc';
		}								
		$v_conten = '';				
		//LAY CAC BIEN AN
		$v_staff_id =$_SESSION['staff_id'];				
		$iCurrentPage = $this->_request->getParam('hdn_current_page','');		
		$iCurrentUpdate = $this->_request->getParam('htn_schedule_update','');								
		$v_year       = $this->_request->getParam('hdn_year','');					
		$v_week 	  = $this->_request->getParam('hdn_week','');														
		$v_date 	  = $this->_request->getParam('hdn_date',''); 
		$v_type 	  = $this->_request->getParam('hdn_schedule_type','');
		$sOwner = $ojbSysInitConfig->_setOnerName();	
		$titleDay = split("/",date('d/m/Y'));		
		$sDate_html = "<i>" .  $ojbSysInitConfig->_setOnerNameSmall(). ", ng&#224;y ".$titleDay[0]." th&#225;ng ".$titleDay[1]." n&#259;m ".$titleDay[2]. "<i>";						
		$v_title	  = '';
		if($v_year ==''){
				$v_year = date('Y');		
			}
			if($v_week ==''){			
				$v_week = date("W");
			}																												
		/* LAY THONG TIN */
		$arrPositionConst =	$ojbSysInitConfig->_setLeaderPostionGroup();		
		// LAY ID CUA NGUOI DANG NHAP HIEN THOI
		$iDepartmentId  = Sys_Publib_Library ::_getItemAttrById($_SESSION['arr_all_staff'],$_SESSION['staff_id'],'unit_id');								
		//DANH SACH LANH DAO PHONG BAN LAY THEO ID CUA NSD
		//$arrDepartmentLeader = $objDocFun->docGetAllLeaderDepartment($arrPositionConst['_CONST_LEADER_POSITION_GROUP_MAIN'],$iDepartmentId);																	
		$arrDepartmentLeader = $objDocFun->docGetAllLeaderDepartment($arrPositionConst['_CONST_PHONG_BAN_GROUP'],$iDepartmentId);													
		//DANH SACH LANH DAO UBND
		$arrUnitLeader = $objDocFun->docGetAllUnitLeader($arrPositionConst['_CONST_POSITION_GROUP'],"arr_all_staff");
		// LAY DANH SACH LANH DAO VAN PHONG
		$arrVp = $objDocFun->docGetAllUnitLeader($arrPositionConst['_CONST_VAN_PHONG_GROUP'],"arr_all_staff");
		$arrWardsLeader 	= $objDocFun->docGetAllUnitLeader($arrPositionConst['_CONST_PHUONG_XA_GROUP'],"arr_all_staff");					
		/*****************/		
		//GOI HAM SINH TUAN TRONG NAM
		$arrDateInWeek = $ojbSysLib->_generate_days_on_week_of_year($v_year,$v_week,'true','');
		$arry_date_in_week = explode('!@#',$arrDateInWeek);						
		$arrCount = Sys_Init_Config::_setProjectPublicConst();
		$this->view->arrCount = $arrCount;				
		if($arrDepartmentLeader ==''){			
			$arrDepartmentLeader = $arrVp;			
		}
		if($arrUnitLeader ==''){			
			$arrUnitLeader = $arrWardsLeader;
		}	
		//LICH_LANH_DAO_PB
		if($v_type == 'LICH_LANH_DAO_UB'){
			$v_count_leader = sizeof($arrUnitLeader);			
			$v_list_id_leader =$arrUnitLeader[0]['id'];
			for($i=1;$i<$v_count_leader;$i++){		
				$v_list_id_leader = $v_list_id_leader.','.$arrUnitLeader[$i]['id'];			
			}				
				$arrySchedule_Staff = $objSchedule->Schedule_LeaderGetAll($v_list_id_leader,$v_week,$v_year );						
		
		}elseif ($v_type == 'LICH_LANH_DAO_PB') {					
			$v_count_leader = sizeof($arrDepartmentLeader);				
			$v_list_id_leader =$arrDepartmentLeader[0]['id'];
			for($i=1;$i<$v_count_leader;$i++){		
				$v_list_id_leader = $v_list_id_leader.','.$arrDepartmentLeader[$i]['id'];			
			}				
				$arrySchedule_Staff = $objSchedule->Schedule_LeaderGetAll($v_list_id_leader,$v_week,$v_year );														
		}
		$v_col_string = '<col width="12%"><col width="8%">';
		$v_col_width = 80/$v_count_leader;		
		for($i = 0; $i< sizeof($arrUnitLeader); $i++){
			$v_col_string = $v_col_string.'<col width="'.$v_col_width.'%">';
			$v_list_id_leader = $v_list_id_leader.$arrUnitLeader[$i]['id'].',';						
			$arr_staff[$i] = $arrySchedule_Staff[$i]['FK_STAFF']; // THU 2
			
			$arr_mon[$i] = explode('!~~!',$arrySchedule_Staff[$i]['C_MON']); // THU 2
			$arr_mon_am[$i] = $arr_mon[$i][0];
			$arr_mon_pm[$i] = $arr_mon[$i][1];	
						
			$arr_tue[$i] = explode('!~~!',$arrySchedule_Staff[$i]['C_TUE']); // THU 3
			$arr_tue_am[$i] = $arr_tue[$i][0];
			$arr_tue_pm[$i] = $arr_tue[$i][1];
	
			$arr_wed[$i] = explode('!~~!',$arrySchedule_Staff[$i]['C_WED']); // THU 4
			$arr_wed_am[$i] = $arr_wed[$i][0];
			$arr_wed_pm[$i] = $arr_wed[$i][1];
			
			$arr_thu[$i] = explode('!~~!',$arrySchedule_Staff[$i]['C_THU']); // THU 5
			$arr_thu_am[$i] = $arr_thu[$i][0];
			$arr_thu_pm[$i] = $arr_thu[$i][1];
	
			$arr_fri[$i] = explode('!~~!',$arrySchedule_Staff[$i]['C_FRI']); // THU 6
			$arr_fri_am[$i] = $arr_fri[$i][0];
			$arr_fri_pm[$i] = $arr_fri[$i][1];
	
			$arr_sat[$i] = explode('!~~!',$arrySchedule_Staff[$i]['C_SAT']); // THU 7
			$arr_sat_am[$i] = $arr_sat[$i][0];
			$arr_sat_pm[$i] = $arr_sat[$i][1];
	
			$arr_sun[$i] = explode('!~~!',$arrySchedule_Staff[$i]['C_SUN']); // CN
			$arr_sun_am[$i] = $arr_sun[$i][0];
			$arr_sun_pm[$i] = $arr_sun[$i][1];
			}								
			$v_conten =$v_conten.'<table class="schedule_staff" boder="1" cellpadding="0" cellspacing="0" width="100%" id="table1">';
			//Hien thi so Col
			$v_conten =$v_conten.$v_col_string;
			$v_conten =$v_conten.'<tr><th colspan="2" scope="col">Thời gian</th>';									 			
			if($v_type == 'LICH_LANH_DAO_UB'){			
				for($i = 0; $i< sizeof($arrUnitLeader); $i++){								
			    	$v_conten = $v_conten. '<th scope="col">'.$arrUnitLeader[$i]['position_code'].'-'.$arrUnitLeader[$i]['name'].'</th>';
			   }       
			}elseif ($v_type == 'LICH_LANH_DAO_PB') {
				for($i = 0; $i< sizeof($arrDepartmentLeader); $i++){								
			    	$v_conten = $v_conten. '<th scope="col">'.$arrDepartmentLeader[$i]['position_code'].'-'.$arrDepartmentLeader[$i]['name'].'</th>';
			   } 
			}						  
			$v_conten = $v_conten.'</tr><tr>';
			$v_conten = $v_conten.'<td rowspan="2"><i>'.$arry_date_in_week[0].'</i></td>';
			$v_conten = $v_conten.'<td>'.$arrCount['_SANG'].'</td>';
			$v_conten = $v_conten.$objDocFun->write_td($v_count_leader,$arr_mon_am,''); 			
			$v_conten = $v_conten.'</tr> <tr> <td>'.$arrCount['_CHIEU'].'</td>';
			$v_conten = $v_conten.$objDocFun->write_td($v_count_leader,$arr_mon_pm,''); 
			$v_conten = $v_conten.'</tr> <tr class="newBackgroud"><td rowspan="2"><i>'. $arry_date_in_week[1].'</i></td>';
			$v_conten = $v_conten.'<td>'.$arrCount['_SANG'].'</td>';
			$v_conten = $v_conten.$objDocFun->write_td($v_count_leader,$arr_tue_am,'');			  	
			$v_conten = $v_conten.'</tr> <tr> <td class="newBackgroud">'.$arrCount['_CHIEU'].'</td>';
			$v_conten = $v_conten.$objDocFun->write_td($v_count_leader,$arr_tue_pm,'1');
			$v_conten = $v_conten.'</tr> <tr> <td rowspan="2"><i>'.$arry_date_in_week[2].'</i></td><td>'.$arrCount['_SANG'].'</td>';
			$v_conten = $v_conten.$objDocFun->write_td($v_count_leader,$arr_wed_am,'');
			$v_conten = $v_conten.'</tr> <tr> <td>'.$arrCount['_CHIEU'].'</td>';
			$v_conten = $v_conten.$objDocFun->write_td($v_count_leader,$arr_wed_pm,''); 
			$v_conten = $v_conten.'</tr> <tr class="newBackgroud"> <td rowspan="2"><i>'.$arry_date_in_week[3].'</i></td><td>'.$arrCount['_SANG'].'</td>';
			$v_conten = $v_conten.$objDocFun->write_td($v_count_leader,$arr_thu_am,''); 
			$v_conten = $v_conten.'</tr><tr> <td class="newBackgroud">'.$arrCount['_CHIEU'].'</td>';
			$v_conten = $v_conten.$objDocFun->write_td($v_count_leader,$arr_thu_pm,'1'); 
			$v_conten = $v_conten.'</tr> <tr> <td rowspan="2"><i>'.$arry_date_in_week[4].'</i></td><td>'.$arrCount['_SANG'].'</td>';
			$v_conten = $v_conten.$objDocFun->write_td($v_count_leader,$arr_fri_am,'');
			$v_conten = $v_conten.'</tr> <tr> <td>'.$arrCount['_CHIEU'].'</td>';
			$v_conten = $v_conten.$objDocFun->write_td($v_count_leader,$arr_fri_pm,''); 
			$v_conten = $v_conten.'</tr> <tr class="newBackgroud"><td rowspan="2"><i>'.$arry_date_in_week[5].'</i></td> <td>'.$arrCount['_SANG'].'</td>';
			$v_conten = $v_conten.$objDocFun->write_td($v_count_leader,$arr_sat_am,'');
			$v_conten = $v_conten. '</tr> <tr> <td class="newBackgroud">'.$arrCount['_CHIEU'].'</td>';
			$v_conten = $v_conten.$objDocFun->write_td($v_count_leader,$arr_sat_pm,'1'); 
			$v_conten = $v_conten.'</tr> <tr> <td rowspan="2"><i>'.$arry_date_in_week[6].'</i></td><td>'.$arrCount['_SANG'].'</td>';
			$v_conten = $v_conten.$objDocFun->write_td($v_count_leader,$arr_sun_am,''); 
			$v_conten = $v_conten.'</tr><tr> <td>'.$arrCount['_CHIEU'].'</td>';
			$v_conten = $v_conten.$objDocFun->write_td($v_count_leader,$arr_sun_pm,''); 
			$v_conten = $v_conten.'</tr> </table>';																													
			if($v_type == 'LICH_LANH_DAO_UB'){			
				$v_title ="LỊCH CÔNG TÁC TUẦN CỦA LÃNH ĐẠO ĐƠN VỊ";	
			}elseif ($v_type == 'LICH_LANH_DAO_PB') {
				$v_title ="LỊCH CÔNG TÁC TUẦN CỦA LÃNH ĐẠO PHÒNG BAN";	
			}									
			$v_resul = str_replace("#WEEK#",$v_date,$v_html_header);
			$v_resul = str_replace("#TITLE#",$v_title,$v_resul);	
			$v_resul = str_replace("#DATE#",$sDate_html,$v_resul);		
			$v_resul = str_replace("#OWNER_NAME#",$sOwner,$v_resul);									
			$v_resul = str_replace("#CONTENBODY#",$v_conten,$v_resul);												
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
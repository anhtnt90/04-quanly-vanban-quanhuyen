<?php
/**
 * Class Xu ly thong thong tin loai danh muc
 */
class received_reportsController extends  Zend_Controller_Action {	
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
		//Goi lop modReceived
		Zend_Loader::loadClass('received_modReceived');		
		Zend_Loader::loadClass('Listxml_modList');
		//Lay cac hang so su dung trong JS public
		Zend_Loader::loadClass('Sys_Init_Config');
		$objConfig = new Sys_Init_Config();
		$this->view->JSPublicConst = $objConfig->_setJavaScriptPublicVariable();		
		//Dia chi URL doc file xu ly AJAX
		$this->view->UrlAjax = $objConfig->_setUrlAjax();	
		// Load tat ca cac file Js va Css
		$JSandStyle = Sys_Publib_Library::_getAllFileJavaScriptCss('','js','util.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','sent.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','ajax.js,jquery-1.4.2.min.js,jQuery.equalHeights.js',',','js'). Sys_Publib_Library::_getAllFileJavaScriptCss('','js/LibSearch','actb_search.js,common_search.js',',','js');
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
		$this->view->currentModulCode = "RECEIVED";
		$this->view->currentModulCodeForLeft = "REPORTS";
		$psshowModalDialog = $this->_request->getParam('showModalDialog',"");
		if ($psshowModalDialog != 1){
		$response->insert('header', $this->view->renderLayout('twd_header.phtml','template/'));    
		$response->insert('left', $this->view->renderLayout('twd_left.phtml','template/'));  	    
        $response->insert('footer', $this->view->renderLayout('twd_footer.phtml','template/'));       
		}				
	}

	/* Index	*/
	public function indexAction(){
		// Tieu de man hinh danh sach
		$this->view->bodyTitle = 'Kết xuất báo cáo';

		// Lay toan bo tham so truyen tu form
		$arrInput = $this->_request->getParams();
		$objFilter = new Zend_Filter();	
		$ojbSysLib = new Sys_Library();
		$objReceive = new received_modReceived();
		$objFunction =	new	Sys_Function_DocFunctions()	;
		//Tao doi tuong XML
		$objXmlLib = new Sys_Publib_Xml();
		//Lay doi tuong dinh nghia bien const
		$ojbSysInitConfig = new Sys_Init_Config();
		//Lay cac gia tri const
		$this->view->arrConst =	$ojbSysInitConfig->_setProjectPublicConst();
		//Lay ra loai VB
		$arrDocType = $objReceive->getPropertiesDocument('DM_LOAI_VAN_BAN','','');
		$this->view->search_doc_type_name = $objFunction->doc_search_ajax($arrDocType,"C_CODE","C_NAME","C_DOC_TYPE","hdn_doc_type_name",0,"",1);															
		//Lay noi gui
		$arrAgentcyName = $objReceive->getPropertiesDocument('DM_NOI_GUI_VAN_BAN','','');
		$this->view->arrAgentcyName = $arrAgentcyName;
		$this->view->search_textselectbox_agentcy_name = Sys_Function_DocFunctions::doc_search_ajax($arrAgentcyName,"C_CODE","C_NAME","C_AGENTCY_NAME","hdn_agentcy_name",0,"",1);
		//Lay nguoi xu ly
		$this->view->search_textselectbox_staff_process = Sys_Function_DocFunctions::doc_search_ajax($_SESSION['arr_all_staff'],"id","name","C_STAFF_POSITION_NAME","hdn_staff_process_id_list",0,"position_code");
		//Lay phong ban
		$this->view->search_textselectbox_unit_name = $objFunction->doc_search_ajax($_SESSION['arr_all_unit'],"id","name","C_UNIT_NAME","hdn_unit_name",0);
		//Lay danh sach so VB di
		$arrInputBooks = $objReceive->getPropertiesDocument('DM_SO_VAN_BAN_DEN','','');
		$this->view->arrInputBooks = $arrInputBooks;
		$sFilterXmlValueList = $this->_request->getParam('hdn_filter_xml_value_list',"");
		$this->view->filterXmlValueList = $sFilterXmlValueList;

		//Tao chuoi loc $sFilterXmlTagList + $sFilterXmlValueList
		$sFilterXmlString = $objXmlLib->_xmlGenerateXmlDataString($sFilterXmlTagList,$sFilterXmlValueList);
		// Lay lai ma bao cao
		$sCodeReport = $this->_request->getParam('hdn_Report_id',"");
		$this->view->sCodeReport = $sCodeReport; 
		//LAY CAC GIA TRI REQUEST
		//Nhan bien truyen vao tu form
		$fromDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($this->_request->getParam('fromDate',''));
		$this->view->fromDate =$ojbSysLib->_ddmmyyyyToYYyymmdd($fromDate);
		$toDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($this->_request->getParam('toDate',''));
		$this->view->toDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($toDate);
		//Lay cac tieu thuc
		$sDocType = $objFilter->filter($arrInput['C_DOC_TYPE']);
		$sSigner  = $objFilter->filter($arrInput['C_SIGNER_POSITION_NAME']);
		$sDepartment = $objFilter->filter($arrInput['C_IDEA_UNIT_NAME']);
		$sTextBook = $objFilter->filter($arrInput['C_TEXT_BOOK']);
		//Lay danh sach cac loai bao cao vb di
		$arrReporList = $objReceive->getPropertiesDocument('DM_BAO_CAO_VBDEN','','');
		$this->view->arrReporList = $arrReporList;
		//Hien thi danh sach bao cao ra view
		$this->view->sHtmlRes = $objReceive->docReceivedListReport($arrReporList,$sCodeReport);
	}
	/**
	 * thuc hien in	
	*/
	/*
	 public function printviewAction(){
		$ojbSysLib = new Sys_Library();
		$objDocFun = new Sys_Function_DocFunctions();
		$iOwnerId = $_SESSION['OWNER_ID'];
		// Lay lai ma bao cao
		$sCodeList = $this->_request->getParam('hdn_Report_id',"");
		$this->view->sCodeList = $sCodeList;
		
		// Lay dinh dang ket xuat bao cao
		$sExportType = $this->_request->getParam('hdn_exporttype',"");
		//echo $sExportType;exit;
		$this->view->sExportType = $sExportType; 
			if($sExportType ==''){
				$sExportType = 14;
			}
		$sCodeReport = $this->_request->getParam('hdn_code_report',"");	
		if($sCodeReport ==''){
			$sCodeReport = 'VBDEN07';
		}
		//Nhan bien truyen vao tu form
		$dFromDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($this->_request->getParam('hdn_fromdate',""));	
		$dToDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($this->_request->getParam('hdn_todate',""));	
		$sDocType = $this->_request->getParam('hdn_doc_type_name',"");
		$sAgentcyName = $this->_request->getParam('hdn_agentcy_name',"");
		$sProcessStaffName = $this->_request->getParam('hdn_staff_process_id_list',"");
		$sUnitName = $this->_request->getParam('hdn_unit_name',"");
		$sTextBook = $this->_request->getParam('hdn_text_book',"");
		$sFilterXmlValueList = $this->_request->getParam('hdn_filter_xml_value_list',"");
		$this->view->filterXmlValueList = $sFilterXmlValueList;
		// Duong dan file rpt
		$path = $_SERVER['SCRIPT_FILENAME'];
		$path = substr($path, 0, -9);		
		$my_report = str_replace("/", "\\", $path) . "rpt\\received\\" . $sCodeReport . ".rpt";
		$sName ='';
		// Tao doi tuong Crystal 9
		$COM_Object = "CrystalDesignRunTime.Application.9";		
		$crapp= new COM($COM_Object) or die("Unable to Create Object");
		$creport = $crapp->OpenReport($my_report, 1);						
		//Ket noi CSDL SQL theo kieu ADODB
		$connectSQL = new Zend_Config_Ini('./config/config.ini','dbmssql');
		$arrConn = $connectSQL->db->config->toArray();
		$creport->Database->Tables(1)->SetLogOnInfo($arrConn['host'], $arrConn['dbname'], $arrConn['username'], $arrConn['password']);
		$creport->EnableParameterPrompting = 0;
		
		//- DiscardSavedData - to refresh then read records		
		$creport->ReadRecords();
			//var_dump($arrFilterXmlValue);exit;	
		if($sCodeReport =='VBDEN01' or $sCodeReport =='VBDEN02' or $sCodeReport =='VBDEN03' or $sCodeReport =='VBDEN04'or $sCodeReport =='VBDEN05'){
			$sName = trim($sDocType.$sAgentcyName.$sProcessStaffName.$sUnitName.$sTextBook);
			//echo $iOwnerId; exit;
			$z = $creport->ParameterFields(1)->SetCurrentValue($sCodeReport);
			$z = $creport->ParameterFields(2)->SetCurrentValue($iOwnerId);
			$z = $creport->ParameterFields(3)->SetCurrentValue($sName);
			$z = $creport->ParameterFields(4)->SetCurrentValue($dFromDate);
			$z = $creport->ParameterFields(5)->SetCurrentValue($dToDate);
			$z = $creport->ParameterFields(6)->SetCurrentValue($this->_request->getParam('hdn_fromdate',""));
			$z = $creport->ParameterFields(7)->SetCurrentValue($this->_request->getParam('hdn_todate',""));
			$z = $creport->ParameterFields(8)->SetCurrentValue($objDocFun->getNameUnitByIdUnitList($iOwnerId));
		}elseif ($sCodeReport =='VBDEN06'){
			//echo $sTextBook.','.$iOwnerId.','.$sDocType.','.$sDocType.','.$dFromDate.','.$dToDate.','.$this->_request->getParam('hdn_fromdate',"").','.$this->_request->getParam('hdn_todate',"").'';exit;
			$z = $creport->ParameterFields(1)->SetCurrentValue($sTextBook);
			$z = $creport->ParameterFields(2)->SetCurrentValue($iOwnerId);
			$z = $creport->ParameterFields(3)->SetCurrentValue($sDocType);
			$z = $creport->ParameterFields(4)->SetCurrentValue($dFromDate);
			$z = $creport->ParameterFields(5)->SetCurrentValue($dToDate);
			$z = $creport->ParameterFields(6)->SetCurrentValue($this->_request->getParam('hdn_fromdate',""));
			$z = $creport->ParameterFields(7)->SetCurrentValue($this->_request->getParam('hdn_todate',""));
			$z = $creport->ParameterFields(8)->SetCurrentValue($objDocFun->getNameUnitByIdUnitList($iOwnerId));
		}elseif($sCodeReport =='VBDEN07'){
			$z = $creport->ParameterFields(1)->SetCurrentValue($iOwnerId);
			$z = $creport->ParameterFields(2)->SetCurrentValue($dFromDate);
			$z = $creport->ParameterFields(3)->SetCurrentValue($dToDate);
			$z = $creport->ParameterFields(4)->SetCurrentValue($this->_request->getParam('hdn_fromdate',""));
			$z = $creport->ParameterFields(5)->SetCurrentValue($this->_request->getParam('hdn_todate',""));
			$z = $creport->ParameterFields(8)->SetCurrentValue($objDocFun->getNameUnitByIdUnitList($iOwnerId));
		}
		// Dinh dang file report ket xuat
		$report_file = 'report.pdf';
		if ($sExportType == 31){
			$report_file = 'report.pdf';
		}
		elseif ($sExportType == 14){
			$report_file = 'report.doc';
		}
		elseif ($sExportType == 29){
			$report_file = 'report.xls';
		}
		 
		// Duong dan file report	
		$my_report_file = str_replace("/", "\\", $path) . "public\\" . $report_file;
		//echo $my_report_file; exit;
		//export to PDF process
		$creport->ExportOptions->DiskFileName=$my_report_file; //export to file 
		$creport->ExportOptions->PDFExportAllPages=true;
		$creport->ExportOptions->DestinationType = 1; // export to file
		$creport->ExportOptions->FormatType= $sExportType; // Type file
		$creport->Export(false);
		
		// doc file pdf len trinh duyet				
		$my_report_file = 'http://'.$_SERVER['HTTP_HOST'].Sys_Init_Config::_setWebSitePath().'public/'.$report_file;
		//echo $my_report_file;exit;
		$this->view->my_report_file = $my_report_file; 		
		 
	}
	*/
	/**
	 * cuongnh
	 * hieu chinh haidv
	 * Enter description here ...
	 */
 	public function printviewAction(){
 	 	//	echo "OK";	exit;
		$ojbSysLib = new Sys_Library();
		$objDocFun = new Sys_Function_DocFunctions();
		$iOwnerId = $_SESSION['OWNER_ID'];
		// Lay lai ma bao cao
		$sCodeList = $this->_request->getParam('hdn_Report_id',"");
		$this->view->sCodeList = $sCodeList;
		
		// Lay dinh dang ket xuat bao cao
		$sExportType = $this->_request->getParam('hdn_exporttype',"");
		//echo $sExportType;exit;
		$this->view->sExportType = $sExportType; 
		if($sExportType ==''){
			$sExportType = 14;
		}
		$sCodeReport = $this->_request->getParam('hdn_code_report',"");	
		if($sCodeReport ==''){
			$sCodeReport = 'VBDEN07';
		}
		//Nhan bien truyen vao tu form
		$dFromDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($this->_request->getParam('hdn_fromdate',""));	
		$dToDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($this->_request->getParam('hdn_todate',""));	
		$sDocType = $this->_request->getParam('hdn_doc_type_name',"");
		$sAgentcyName = $this->_request->getParam('hdn_agentcy_name',"");
		$sProcessStaffName = $this->_request->getParam('hdn_staff_process_id_list',"");
		$sUnitName = $this->_request->getParam('hdn_unit_name',"");
		$sTextBook = $this->_request->getParam('hdn_text_book',"");
		$sFilterXmlValueList = $this->_request->getParam('hdn_filter_xml_value_list',"");
		$this->view->filterXmlValueList = $sFilterXmlValueList;
		//Lay file template
		$path = $_SERVER['SCRIPT_FILENAME'];
		$path = substr($path, 0, -9);	
		$sTemplateUrl = str_replace("/", "\\", $path) . "templates\\report-template\\received\\" . $sCodeReport . "_template.htm";
		$v_html_header = $ojbSysLib->_read_file($sTemplateUrl);
		//echo $v_html_header; exit;
		//Tao lop xu ly doi tuong
		$objReceive = new received_modReceived();
		$v_conten = '';
		$v_resul = '';
		if($sCodeReport =='VBDEN01' or $sCodeReport =='VBDEN02' or $sCodeReport =='VBDEN03' or $sCodeReport =='VBDEN04'or $sCodeReport =='VBDEN05'){
			$sName = trim($sDocType.$sAgentcyName.$sProcessStaffName.$sUnitName.$sTextBook);
			$arrResul = $objReceive->DocReceivedReportVBDEN01($sCodeReport,$iOwnerId,$sName,$dFromDate,$dToDate,'','',$objDocFun->getNameUnitByIdUnitList($iOwnerId));
			if($sCodeReport =='VBDEN05'){
				for($index = 0;$index < sizeof($arrResul);$index++){
					$v_conten = $v_conten.'<tr><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_RECEIVED_DATE'].'</td><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_NUM'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_AGENTCY_NAME'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SYMBOL'].'</td><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_RELEASE_DATE'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_DOC_TYPE'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SUBJECT'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_LEADER_POSITION_NAME'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_UNIT_NAME'].'</td></tr>';
				}
				$v_resul = str_replace("#UNIT_NAME#",$objDocFun->getNameUnitByIdUnitList($iOwnerId),$v_html_header);
				$v_resul = str_replace("#BOOK_NAME#",$arrResul[0]['C_TEXT_BOOK'],$v_resul);
				$v_resul = str_replace("#FROM_DATE#",$this->_request->getParam('hdn_fromdate',""),$v_resul);
				$v_resul = str_replace("#TO_DATE#",$this->_request->getParam('hdn_todate',""),$v_resul);
				$v_resul = str_replace("#CONTEN#",$v_conten,$v_resul);
			}	
			if($sCodeReport =='VBDEN01'){
				//echo $objDocFun->getNameUnitByIdUnitList($iOwnerId); exit;
				$v_resul = str_replace("#UNIT_NAME#",$objDocFun->getNameUnitByIdUnitList($iOwnerId),$v_html_header);
				$v_resul = str_replace("#FROM_DATE#",$this->_request->getParam('hdn_fromdate',""),$v_resul);
				$v_resul = str_replace("#TO_DATE#",$this->_request->getParam('hdn_todate',""),$v_resul);
				$v_tr_group = '';
				for($index = 0;$index < sizeof($arrResul);$index++){
					if($v_tr_group != $arrResul[$index]['C_DOC_TYPE']){
						$v_tr_group = $arrResul[$index]['C_DOC_TYPE'];
						$v_conten = $v_conten.'<tr><td colspan="8" style="padding-left:3px;padding-right:3px;"><b>'.$arrResul[$index]['C_DOC_TYPE'].'</b></td></tr>';
						$v_conten = $v_conten.'<tr><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_RECEIVED_DATE'].'</td><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_NUM'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_AGENTCY_NAME'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SYMBOL'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SUBJECT'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_LEADER_POSITION_NAME'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_UNIT_NAME'].'</td><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_STATUS'].'</td></tr>';
					}else{
						$v_conten = $v_conten.'<tr><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_RECEIVED_DATE'].'</td><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_NUM'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_AGENTCY_NAME'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SYMBOL'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SUBJECT'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_LEADER_POSITION_NAME'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_UNIT_NAME'].'</td><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_STATUS'].'</td></tr>';	
					}
				}
				$v_resul = str_replace("#CONTEN#",$v_conten,$v_resul);
			}
			if($sCodeReport =='VBDEN02'){
				$v_resul = str_replace("#UNIT_NAME#",$objDocFun->getNameUnitByIdUnitList($iOwnerId),$v_html_header);
				$v_resul = str_replace("#FROM_DATE#",$this->_request->getParam('hdn_fromdate',""),$v_resul);
				$v_resul = str_replace("#TO_DATE#",$this->_request->getParam('hdn_todate',""),$v_resul);
				$v_tr_group = '';
				for($index = 0;$index < sizeof($arrResul);$index++){
					if($v_tr_group != $arrResul[$index]['C_AGENTCY_NAME']){
						$v_tr_group = $arrResul[$index]['C_AGENTCY_NAME'];
						$v_conten = $v_conten.'<tr><td colspan="8" style="padding-left:3px;padding-right:3px;"><b>'.$arrResul[$index]['C_AGENTCY_NAME'].'</b></td></tr>';
						$v_conten = $v_conten.'<tr><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_RECEIVED_DATE'].'</td><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_NUM'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SYMBOL'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_DOC_TYPE'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SUBJECT'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_LEADER_POSITION_NAME'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_UNIT_NAME'].'</td><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_STATUS'].'</td></tr>';
					}else{
						$v_conten = $v_conten.'<tr><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_RECEIVED_DATE'].'</td><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_NUM'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SYMBOL'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_DOC_TYPE'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SUBJECT'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_LEADER_POSITION_NAME'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_UNIT_NAME'].'</td><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_STATUS'].'</td></tr>';	
					}
				}
				$v_resul = str_replace("#CONTEN#",$v_conten,$v_resul);
			}
			if($sCodeReport =='VBDEN03'){
				$v_resul = str_replace("#UNIT_NAME#",$objDocFun->getNameUnitByIdUnitList($iOwnerId),$v_html_header);
				$v_resul = str_replace("#FROM_DATE#",$this->_request->getParam('hdn_fromdate',""),$v_resul);
				$v_resul = str_replace("#TO_DATE#",$this->_request->getParam('hdn_todate',""),$v_resul);
				$v_tr_group = '';
				for($index = 0;$index < sizeof($arrResul);$index++){
					if($v_tr_group != $arrResul[$index]['C_STAFF_NAME']){
						$v_tr_group = $arrResul[$index]['C_STAFF_NAME'];
						$v_conten = $v_conten.'<tr><td colspan="7" style="padding-left:3px;padding-right:3px;"><b>'.$arrResul[$index]['C_STAFF_NAME'].'</b></td></tr>';
						$v_conten = $v_conten.'<tr><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_RECEIVED_DATE'].'</td><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_NUM'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SYMBOL'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_DOC_TYPE'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SUBJECT'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_LEADER_POSITION_NAME'].'</td><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_STATUS'].'</td></tr>';
					}else{
						$v_conten = $v_conten.'<tr><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_RECEIVED_DATE'].'</td><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_NUM'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SYMBOL'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_DOC_TYPE'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SUBJECT'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_LEADER_POSITION_NAME'].'</td><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_STATUS'].'</td></tr>';	
					}
				}
				$v_resul = str_replace("#CONTEN#",$v_conten,$v_resul);
			}
			if($sCodeReport =='VBDEN04'){
				$v_resul = str_replace("#UNIT_NAME#",$objDocFun->getNameUnitByIdUnitList($iOwnerId),$v_html_header);
				$v_resul = str_replace("#FROM_DATE#",$this->_request->getParam('hdn_fromdate',""),$v_resul);
				$v_resul = str_replace("#TO_DATE#",$this->_request->getParam('hdn_todate',""),$v_resul);
				$v_tr_group = '';
				for($index = 0;$index < sizeof($arrResul);$index++){
					if($v_tr_group != $arrResul[$index]['C_UNIT_NAME']){
						$v_tr_group = $arrResul[$index]['C_UNIT_NAME'];
						$v_conten = $v_conten.'<tr><td colspan="7" style="padding-left:3px;padding-right:3px;"><b>'.$arrResul[$index]['C_UNIT_NAME'].'</b></td></tr>';
						$v_conten = $v_conten.'<tr><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_RECEIVED_DATE'].'</td><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_NUM'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SYMBOL'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_DOC_TYPE'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SUBJECT'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_LEADER_POSITION_NAME'].'</td><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_STATUS'].'</td></tr>';
					}else{
						$v_conten = $v_conten.'<tr><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_RECEIVED_DATE'].'</td><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_NUM'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SYMBOL'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_DOC_TYPE'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SUBJECT'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_LEADER_POSITION_NAME'].'</td><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_STATUS'].'</td></tr>';	
					}
				}
				$v_resul = str_replace("#CONTEN#",$v_conten,$v_resul);
			}
		}elseif ($sCodeReport =='VBDEN06'){		
			$arrResul = $objReceive->DocReceivedReportVBDEN01($sTextBook,$iOwnerId,$sDocType,$dFromDate,$dToDate,$this->_request->getParam('hdn_fromdate',""),$this->_request->getParam('hdn_todate',""),$objDocFun->getNameUnitByIdUnitList($iOwnerId));
				$v_resul = str_replace("#UNIT_NAME#",$objDocFun->getNameUnitByIdUnitList($iOwnerId),$v_html_header);	
				$v_resul = str_replace("#FROM_DATE#",$this->_request->getParam('hdn_fromdate',""),$v_resul);
				$v_resul = str_replace("#TO_DATE#",$this->_request->getParam('hdn_todate',""),$v_resul);
				$v_resul = str_replace("#BOOK_NAME#",$arrResul[0]['C_TEXT_BOOK'],$v_resul);
				$v_tr_group = '';
				for($index = 0;$index < sizeof($arrResul);$index++){
					if($v_tr_group != $arrResul[$index]['C_DOC_TYPE']){
						$v_tr_group = $arrResul[$index]['C_DOC_TYPE'];
						$v_conten = $v_conten.'<tr><td colspan="8" style="padding-left:3px;padding-right:3px;"><b>'.$arrResul[$index]['C_DOC_TYPE'].'</b></td></tr>';
						$v_conten = $v_conten.'<tr><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_RECEIVED_DATE'].'</td><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_NUM'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SYMBOL'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_DOC_TYPE'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SUBJECT'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_LEADER_POSITION_NAME'].'</td><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_STATUS'].'</td></tr>';
					}else{
						$v_conten = $v_conten.'<tr><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_RECEIVED_DATE'].'</td><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_NUM'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SYMBOL'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_DOC_TYPE'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_SUBJECT'].'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_LEADER_POSITION_NAME'].'</td><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_STATUS'].'</td></tr>';	
					}
				}
				$v_resul = str_replace("#CONTEN#",$v_conten,$v_resul);
		}elseif($sCodeReport =='VBDEN07'){			
				$arrResul = $objReceive->DocReceivedReportVBDEN07($iOwnerId,$dFromDate,$dToDate,$this->_request->getParam('hdn_fromdate',""),$this->_request->getParam('hdn_todate',""),$objDocFun->getNameUnitByIdUnitList($iOwnerId));
				$v_resul = str_replace("#UNIT_NAME#",$objDocFun->getNameUnitByIdUnitList($iOwnerId),$v_html_header);
				$v_resul = str_replace("#FROM_DATE#",$this->_request->getParam('hdn_fromdate',""),$v_resul);
				$v_resul = str_replace("#TO_DATE#",$this->_request->getParam('hdn_todate',""),$v_resul);
				$v_tr_group = '';
				for($index = 0;$index < sizeof($arrResul);$index++){
					$v_conten = $v_conten.'<tr><td align="center" style="padding-left:3px;padding-right:3px;">'.($index + 1).'</td><td style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['C_UNIT_NAME'].'</td><td align="center" style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['TONG_SO_VB'].'</td><td align="center" style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['DANG_XU_LY'].'</td><td align="center" style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['DA_XU_LY_DUNG_HAN'].'</td><td align="center" style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['DA_XU_LY_QUA_HAN'].'</td><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['QUA_HAN_TRUOC_KY_BC'].'</td><td align="center"  style="padding-left:3px;padding-right:3px;">'.$arrResul[$index]['QUA_HAN_TRONG_KY_BC'].'</td></tr>';	
				}
				$v_resul = str_replace("#CONTEN#",$v_conten,$v_resul);
		}
		//var_dump($arrResul);
		//echo $v_resul; 
		//exit;		 
 		// Dinh dang file report ket xuat
		$report_file = 'report.doc';
		if ($sExportType == 31){
			$report_file = 'report.htm';
		}
		elseif ($sExportType == 14){
			$report_file = 'report.doc';
		}
		$my_report_file = str_replace("/", "\\", $path) . "public\\export\\" . $report_file;
		$ojbSysLib->_write_file($my_report_file,$v_resul);		
		$my_report_file = 'http://'.$_SERVER['HTTP_HOST'].Sys_Init_Config::_setWebSitePath().'public/export/'.$report_file;
		$this->view->my_report_file = $my_report_file; 	
	}
}
?>
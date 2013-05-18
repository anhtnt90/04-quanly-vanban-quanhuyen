<?php
/**
 * Nguoi tao: phongtd
 * Ngay tao: 17/11/2009
 * Y nghia: Class Xu ly Report DocReceived
 */	
class Received_reportController extends  Zend_Controller_Action {
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
		Zend_Loader::loadClass('Received_modReceivedReports');
		Zend_Loader::loadClass('Sent_documentSent');
		
		$this->view->JSPublicConst = $objConfig->_setJavaScriptPublicVariable();		
		
		// Load tat ca cac file Js va Css
		$this->view->LoadAllFileJsCss = Sys_Publib_Library::_getAllFileJavaScriptCss('','js','reports.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','ajax.js',',','js');
		
		// Ham lay thong tin nguoi dang nhap hien thi tai Lefmenu
		$this->view->InforStaff = Sys_Publib_Library::_InforStaff();
		
		//Dinh nghia current modul code
		$this->view->currentModulCode = "RECEIVED";
		$this->view->currentModulCodeForLeft = "REPORT-RECEIVED-DOC";
		
		$response->insert('header', $this->view->renderLayout('twd_header.phtml','template/'));    
			$response->insert('left', $this->view->renderLayout('twd_left.phtml','template/'));  	    
	        $response->insert('footer', $this->view->renderLayout('twd_footer.phtml','template/'));
  	}
  	
	/**
	 * Idea : Phuong thuc hien thi danh sach
	 *
	 */
	
	public function indexAction(){
		// Tieu de man hinh danh sach
		$this->view->bodyTitle = 'Kết xuất báo cáo';

		// Lay toan bo tham so truyen tu form
		$arrInput = $this->_request->getParams();
		//var_dump($arrInput);
		// Tao doi tuong Zend_Filter
		$objFilter = new Zend_Filter();

		// Tao doi tuong cho lop tren
		$objReport = new Received_modReceivedReports() ;
		
		$objSent = new Sent_documentSent();

		//Tao doi tuong XML
		$objXmlLib = new Sys_Publib_Xml();
		//Lay danh sach cac THE mo ta tieu tri loc + dung cho nut tim kiem submit tai form
		$sFilterXmlTagList = $this->_request->getParam('hdn_filter_xml_tag_list',"");
		$this->view->filterXmlTagList = $sFilterXmlTagList;

		//Lay danh sach cac gia tri tuong ung mo ta tieu tri loc
		$sFilterXmlValueList = $this->_request->getParam('hdn_filter_xml_value_list',"");
		$this->view->filterXmlValueList = $sFilterXmlValueList;

		//Tao chuoi loc $sFilterXmlTagList + $sFilterXmlValueList
		$sFilterXmlString = $objXmlLib->_xmlGenerateXmlDataString($sFilterXmlTagList,$sFilterXmlValueList);
		
		// Lay lai trang thai ban dau khi mot form khac submit den
		if (isset($_SESSION['seArrParameter'])){
			$arrParaInSession = $_SESSION['seArrParameter'];
			//Trang hien thoi
			$iCurrentPage = $arrParaInSession['sel_page'];
			//So record/page
			$iNumRowOnPage = $arrParaInSession['cbo_nuber_record_page'];
			//Lay thanh sach cac the
			$sFilterXmlTagList = $arrParaInSession['hdn_filter_xml_tag_list'];
			//Lay danh sach gia tri tuong ung
			$sFilterXmlValueList = $arrParaInSession['hdn_filter_xml_value_list'];
			//Tao xau XML mo ta cac tieu tri loc
			$sFilterXmlString = $objXmlLib->_xmlGenerateXmlDataString($sFilterXmlTagList,$sFilterXmlValueList);
			//Xoa gia tri trong session
			unset($_SESSION['seArrParameter']);
		}
		//$arrUnit = $objSent->getPropertiesDocument('DM_DONVI_SUDUNG');
		$arrUnit = $objSent->_getDepartment();
		$this->view->arrUnit = $arrUnit;
		// Lay lai ma bao cao
		$sCodeList = $this->_request->getParam('hdn_Report_id',"");
		$this->view->sCodeList = $sCodeList; 		
		
		// Lay dinh dang ket xuat bao cao
		$sExportType = $this->_request->getParam('hdn_exporttype',"");
		$this->view->sExportType = $sExportType; 
		
		//Lay ra cac bao cao
		$arrReporList = $objReport->getAllReportByReportType('DM_BAO_CAO_VB_DEN');
		
		//Hien thi danh sach bao cao ra view
		$this->view->sHtmlRes = $objReport->showListReport($arrReporList,$sCodeList);

		// Lay ten file XML
		$sFileNameXml = $objReport->getFileNameXml($arrReporList,$sCodeList);

		$sxmlFileName = "xml/report/".$sFileNameXml;

		// Gan vao view ten file xml
		$this->view->xmlFileName= $sFileNameXml;
		
		//Tao form hien thi tieu tri loc
		$this->view->generateFilterForm = $objXmlLib->_xmlGenerateFormfield($sxmlFileName, 'filter_row', $sFilterXmlString, null, true, false);
	}
	/**
	 * thuc hien in	
	*/
	
	public function printviewAction(){	
		// Lay lai ma bao cao
		$sCodeList = $this->_request->getParam('hdn_Report_id',"");
		$this->view->sCodeList = $sCodeList;
		
		// Lay dinh dang ket xuat bao cao
		$sExportType = $this->_request->getParam('hdn_exporttype',"");
		$this->view->sExportType = $sExportType; 
		if($sExportType ==''){
			$sExportType = 14;
		}		
		//Lay danh sach cac THE mo ta tieu tri loc + dung cho nut tim kiem submit tai form
		$sFilterXmlTagList = $this->_request->getParam('hdn_filter_xml_tag_list',"");
		$this->view->filterXmlTagList = $sFilterXmlTagList;
		
		//Lay danh sach cac gia tri tuong ung mo ta tieu tri loc
		$sFilterXmlValueList = $this->_request->getParam('hdn_filter_xml_value_list',"");
		$this->view->filterXmlValueList = $sFilterXmlValueList;
		
		//Chuyen cac tieu thuc loc vao mang
		$arrFilterXmlValue = explode("!~~!",$sFilterXmlValueList);
		//Lay thoi gian tu ngay 
		$pFromDate = Sys_Library::_ddmmyyyyToYYyymmdd($arrFilterXmlValue[0]) ;
		//Lay thoi gian den ngay
		$pToDate = Sys_Library::_ddmmyyyyToYYyymmdd($arrFilterXmlValue[1]) ;
				
		//Lay Quyen cap nhat VB DEN
		$PermissionArchives = Sys_Function_DocFunctions::Doc_ArchivesStaff($_SESSION['arrStaffPermission'],$_SESSION['staff_id']);
		//Lay Quyen PHAN PHOI VB DEN
		$PermissionDistribution = Sys_Function_DocFunctions::Doc_DistributionDocument($_SESSION['arrStaffPermission'],$_SESSION['staff_id']);	
		//Lay Quyen PHAN CONG XU LY VB DEN
		$sOwnerCode = $_SESSION['OWNER_CODE'];
		$PermissionAssign = Sys_Function_DocFunctions::Doc_AssignDocument($_SESSION['arrStaffPermission'],$_SESSION['staff_id']);	
		if($PermissionArchives == 1 || $PermissionDistribution == 1 || $PermissionAssign ==1 ){ //Neu la VT BO, LANH DAO BO				
			$sOwnerCode = 'BXD';
		}
		// Duong dan file rpt
		$path = $_SERVER['SCRIPT_FILENAME'];
		$path = substr($path, 0, -9);
		//$my_report ='D:\\PROJECTS\\VertrigoServ\\www\\sys-doc-boxd\\rpt\\report\\'.$sCodeList.'.rpt';
		$my_report = str_replace("/", "\\", $path) . "rpt\\report\\" . $sCodeList . ".rpt";
		//Lay MA DON VI NSD dang nhap hien thoi
		try{
			$unitName = $this->_request->getParam('unitName','');
			if($unitName != ""){
				$sOwnerCode = $unitName;
			}
		}catch (Exception $e){;}
			
		// Tao doi tuong Crystal 9
		$COM_Object = "CrystalDesignRunTime.Application.9";		
		$crapp= new COM($COM_Object) or die("Unable to Create Object");
		$creport = $crapp->OpenReport($my_report, 1);	
		
		//- Set database logon info - must have
		//Ket noi CSDL SQL theo kieu ADODB
		$connectSQL = new Zend_Config_Ini('./config/config.ini','dbmssql');
		$arrConn = $connectSQL->db->config->toArray();
		$creport->Database->Tables(1)->SetLogOnInfo($arrConn['host'], $arrConn['dbname'], $arrConn['username'], $arrConn['password']);
		$creport->EnableParameterPrompting = 0;
		
		//- DiscardSavedData - to refresh then read records
		//$creport->DiscardSavedData;		
		$creport->ReadRecords();
		// Truyen tham so vao
		$z = $creport->ParameterFields(1)->SetCurrentValue($pFromDate);
		$z = $creport->ParameterFields(2)->SetCurrentValue($pToDate);
		$z = $creport->ParameterFields(3)->SetCurrentValue($sOwnerCode);
		//$z = $creport->ParameterFields(4)->SetCurrentValue($arrFilterXmlValue[0]);
		//$z = $creport->ParameterFields(5)->SetCurrentValue($arrFilterXmlValue[1]);
		
		// Dinh dang file report ket xuat
		$report_file = 'report' . mt_rand(1,1000000) . '.doc';
		if ($sExportType == 31){
			$report_file = 'report' . mt_rand(1,1000000) . '.pdf';
		}
		elseif ($sExportType == 14){
			$report_file = 'report' . mt_rand(1,1000000) . '.doc';
		}
		elseif ($sExportType == 29){
			$report_file = 'report' . mt_rand(1,1000000) . '.xls';
		}
		 
		// Duong dan file report	
		//$my_report_file = "D:\\PROJECTS\\VertrigoServ\\www\\sys-doc-boxd\\public\\".$report_file;
		$my_report_file = str_replace("/", "\\", $path) . "public\\" . $report_file;
		//echo $my_report_file; exit;
		$creport->ExportOptions->DiskFileName=$my_report_file; //export to file 
		$creport->ExportOptions->PDFExportAllPages=true;
		$creport->ExportOptions->DestinationType = 1; // export to file
		$creport->ExportOptions->FormatType= $sExportType; // Type file
		$creport->Export(false);
		
		// doc file pdf len trinh duyet		
		//$this->_redirect($my_report_file);  
		$my_report_file = 'http://'.$_SERVER['HTTP_HOST'].'/sys-doc-boxd/public/'.$report_file;
		$this->view->my_report_file = $my_report_file; 
		 
	}
	
}
?>
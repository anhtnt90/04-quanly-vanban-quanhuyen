<?php
/**
 * Class Xu ly thong thong tin loai danh muc
 */
class Reports_reportsController extends  Zend_Controller_Action {
	public function init(){
		//Load cau hinh thu muc trong file config.ini
        $tempDirApp = Zend_Registry::get('conDirApp');
		$this->_dirApp = $tempDirApp->toArray();
		$this->view->dirApp = $tempDirApp->toArray();
		
		//Cau hinh cho Zend_layoutasdfsdfsd
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
		$this->view->NumberRowOnPage    = $this->_ConstPublic['NumberRowOnPage'];		
		
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
		//Goi lop Listxml_modProject
		Zend_Loader::loadClass('Reports_modReports');
		//echo 'hoang van toan';
		//Tao doi tuong XML
		Zend_Loader::loadClass('Sys_Publib_Xml');
		
		//Lay cac hang so su dung trong JS public
		Zend_Loader::loadClass('Sys_Init_Config');
		$objConfig = new Sys_Init_Config();
		$this->view->JSPublicConst = $objConfig->_setJavaScriptPublicVariable();		
		
		/* Dung de load file Js va css		/*/
		// Goi lop public
		Zend_Loader::loadClass(Sys_Publib_Library);
		$objPublicLibrary = new Sys_Library();
		
		// Load tat ca cac file Js va Css		
		$JSandStyle = $objPublicLibrary->_getAllFileJavaScriptCss('','sys-js','reports.js',',','js');				
		$JSandStyle.= Sys_Publib_Library::_getAllFileJavaScriptCss('','sys-js','ui/i18n/jquery.ui.datepicker-vi.js',',','js');
		$JSandStyle.= Sys_Publib_Library::_getAllFileJavaScriptCss('','sys-js','ui/jquery-ui-1.8.14.custom.min.js',',','js');
		$JSandStyle.= Sys_Publib_Library::_getAllFileJavaScriptCss('','sys-style','themes/redmond/jquery-ui-1.8.15.custom.css',',','css');
		$this->view->LoadAllFileJsCss = $JSandStyle;	
		/* Ket thuc*/
		
		//Dinh nghia current modul code
		$this->view->currentModulCode = "REPORTS";
		$this->view->currentModulCodeForLeft = "REPORTS";
		
		//Bien xac dinh An/Hien menu trai cua he thong; Neu $this->view->hideDisplayMeneLeft = none thi An; = "" thi hien menu trai	
		$this->view->hideDisplayMeneLeft = "none";
		
		//Hien thi file template
		$response->insert('header', $this->view->renderLayout('header.phtml','./application/views/scripts/'));    	//Hien thi header 
		//$response->insert('left', $this->view->renderLayout('left.phtml','./application/views/scripts/'));    		//Hien thi header 		    
        $response->insert('footer', $this->view->renderLayout('footer.phtml','./application/views/scripts/'));  	//Hien thi footer

        
		// lay session khi dang nhap		
		Sys_Function_DocFunctions::DocCheckLogin();	

	}


	/* Index	*/


	public function indexAction(){

		// Tieu de man hinh danh sach
		$this->view->bodyTitle = 'Kết xuất báo cáo';

		// Lay toan bo tham so truyen tu form
		$arrInput = $this->_request->getParams();
		//var_dump($arrInput);

		// Tao doi tuong Zend_Filter
		$objFilter = new Zend_Filter();

		// Tao doi tuong cho lop tren
		$objReport = new Reports_modReports() ;

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
		//echo '$sFilterXmlString = ' . $sFilterXmlString;

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

		// Lay lai ma bao cao
		$sCodeList = $this->_request->getParam('hdn_Report_id',"");

		$this->view->sreportId = $sCodeList; 
	//echo $this->view->sreportId .'<br>'; 
		//Lay ra cac bao cao
		$arrReporList = $objReport->getAllReportByReportType('DM_BAO_CAO');
		//var_dump($arrReporList);
		

		//Hien thi danh sach bao cao ra view
		$this->view->sHtmlRes = $objReport->showListReport($arrReporList,$sCodeList);

		// Lay ten file XML
		$sFileNameXml = $objReport->getFileNameXml($arrReporList,$sCodeList);
	//echo '$sFileNameXml = '. $sFileNameXml;
		$sxmlFileName = "xml/report/".$sFileNameXml;
	//echo '$sxmlFileName = '. $sxmlFileName; //exit;
		// Gan vao view ten file xml
		$this->view->xmlFileName= $sFileNameXml;
		

		//Tao form hien thi tieu tri loc
		$this->view->generateFilterForm = $objXmlLib->_xmlGenerateFormfield($sxmlFileName, 'filter_row', $sFilterXmlString, null, true, false);


		//Thuc hien lay thong tin cua doi tuong nay day ra view de thuc hien cho java
		//$this->view->arrResultJava = Sys_Function_CBCCLibrary::getAllDepartment('NQ','thuoc_phong_ban');

	}
	/**
	 * thuc hien in	
	*/
	public function genGenerateBody(){
		
	}
	public function printviewAction(){
		
		// Tieu de man hinh danh sach
		$this->view->bodyTitle = 'Kết xuất báo cáo';

		// Lay toan bo tham so truyen tu form
		$arrInput = $this->_request->getParams();
		//var_dump($arrInput);

		// Tao doi tuong Zend_Filter
		$objFilter = new Zend_Filter();

		// Tao doi tuong cho lop tren
		$objReport = new Reports_modReports() ;

		//Tao doi tuong XML
		$objXmlLib = new Sys_Publib_Xml();


		//Lay danh sach cac THE mo ta tieu tri loc + dung cho nut tim kiem submit tai form
		$sFilterXmlTagList = $this->_request->getParam('hdn_filter_xml_tag_list',"");
		$this->view->filterXmlTagList = $sFilterXmlTagList;
		//Lay danh sach cac gia tri tuong ung mo ta tieu tri loc
		$sFilterXmlValueList = $this->_request->getParam('hdn_filter_xml_value_list',"");
		$this->view->filterXmlValueList = $sFilterXmlValueList;
		//echo $this->view->filterXmlValueList . '<br>';
		//Tao chuoi loc $sFilterXmlTagList + $sFilterXmlValueList
		$sFilterXmlString = $objXmlLib->_xmlGenerateXmlDataString($sFilterXmlTagList,$sFilterXmlValueList);
		echo '<br>$sFilterXmlString = ' . $sFilterXmlString . '<br>';
		//exit;

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


		// Lay lai ten file xml
		$sXmlFileNameTemp  = $this->_request->getParam('hdn_xml_file',"");
		$sXmlFileName = "xml/report/".$sXmlFileNameTemp;
		
		//echo '<br>$sXmlFileName = ' . $sXmlFileName . '<br>';// exit;
		// Tao ra phan header cho bao cao
		$this->view->generateHeaderReport = $objXmlLib->_xmlGenerateReportHeader($sXmlFileName,'filter_row','col', $sFilterXmlString);		
				
		// Mang chua thong tin
		$arrResult = $objReport->getAllReportProject($sFilterXmlString,$sXmlFileName);
		
		//var_dump($arrResult). '<br>'; exit;		
		// Cot chua noi dung cua bao cao
		$v_colume_name_of_xml_string = 'C_XML_DATA';
		
		// Tao phan body cho bao cao
		$this->view->generateBodyReport = $objXmlLib->_xmlGenerateReportBody($sXmlFileName,'col',$arrResult,$v_colume_name_of_xml_string) ;		
		//echo $this->view->generateBodyReport;exit;
				
		// Tao footer cho bao cao
		$this->view->generateFooterReport = $objXmlLib->_xmlGenerateReportFooter($sXmlFileName,'col');
		//echo $this->view->generateFooterReport;exit;
		// Dua noi dung ra file
		// Chuoi html vua tao
		$sHtmlString = 	$this->view->generateHeaderReport.$this->view->generateBodyReport .$this->view->generateFooterReport ;
		//echo $sHtmlString; exit;
		//doc file style
		// Doc file css
		$objConfig = new Sys_Init_Config();
		$sStyleName =$objConfig->_getCurrentHttpAndHost(). "public/sys-style/Report/report_style.css";
		//echo $sStyleName; exit;

		// Phan dau cua chuoi html
		$sHtmlContent = '<html xmlns:o="urn:schemas-microsoft-com:office:office"
					xmlns:x="urn:schemas-microsoft-com:office:excel">
					<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';

		//
		$objSysLib = new  Sys_Library();
		$sStyleContent = $objSysLib->_readFile($sStyleName);
		
		$sHtmlContent = $sHtmlContent.'<style type=text/css>'.$sStyleContent.'</style>';
		$sHtmlContent = $sHtmlContent.'
		<!--[if gte mso 9]><xml>
		 <x:ExcelWorkbook>
		  <x:ExcelWorksheets>
		   <x:ExcelWorksheet>
			<x:Name>Report</x:Name>
			<x:WorksheetOptions>
			 <x:Print>
			  <x:ValidPrinterInfo/>
			  <x:HorizontalResolution>600</x:HorizontalResolution>
			  <x:VerticalResolution>600</x:VerticalResolution>
			 </x:Print>
			 <x:PageBreakZoom>100</x:PageBreakZoom>
			 <x:Selected/>
			 <x:Panes>
			  <x:Pane>
			   <x:Number>0</x:Number>
			   <x:ActiveRow>0</x:ActiveRow>
			   <x:ActiveCol>0</x:ActiveCol>
			  </x:Pane>
			 </x:Panes>
			 <x:ProtectContents>False</x:ProtectContents>
			 <x:ProtectObjects>False</x:ProtectObjects>
			 <x:ProtectScenarios>False</x:ProtectScenarios>
			</x:WorksheetOptions>
			<x:Sorting>
			 <x:Sort>ma</x:Sort>
			</x:Sorting>
		   </x:ExcelWorksheet>
		  </x:ExcelWorksheets>
		  <x:WindowHeight>9345</x:WindowHeight>
		  <x:WindowWidth>15180</x:WindowWidth>
		  <x:WindowTopX>120</x:WindowTopX>
		  <x:WindowTopY>60</x:WindowTopY>
		  <x:ProtectStructure>False</x:ProtectStructure>
		  <x:ProtectWindows>False</x:ProtectWindows>
		 </x:ExcelWorkbook>
		</xml><![endif]-->';
		$sHtmlContent = $sHtmlContent.'</head><body>'.$sHtmlString.'</body>';
		$sHtmlContent = $sHtmlContent.'</html>';

		//echo  htmlspecialchars($sStyleContent);
		//echo  htmlspecialchars($sHtmlContent);
		//exit();
		//echo  $sHtmlContent;exit;


		// Lay kieu dinh dang

		$v_exporttype = $this->_request->getParam('hdn_exporttype',"1");

		// Thuc hien xuat ra theo dinh dang
		switch($v_exporttype) {
			case 1;
			$sExportFileName = "report.htm";
			break;
			case 2;
			$sHtmlContent = str_replace('text/html','application/msword',$sHtmlContent);
			$sExportFileName = "report.doc";
			break;
			case 3;
			$sExportFileName = "report.xls";
			$sHtmlContent = str_replace('text/html','application/vnd.ms-excel',$sHtmlContent);
			break;
			default:
				$sExportFileName = "report.htm";
				break;
		}
		//echo  $sHtmlContent;exit;
		// Tao ra file
		$objSysLib->_writeFile('public/' . $sExportFileName,$sHtmlContent);
		//echo $sHtmlContent;exit;
		// Lay duong dan file
		$this->view->sExportFileName = $objConfig->_getCurrentHttpAndHost().'public/'. $sExportFileName;
		//echo $this->view->sExportFileName;

	}

}
?>
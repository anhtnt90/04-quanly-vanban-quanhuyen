<?php

/**
 * Creater : NGHIAT
 * Date : 18/10/2010
 * Idea : Class Xu ly thong thong doi tuong danh muc
 */
class Listxml_backupController extends  Zend_Controller_Action {
		
	//Phuong thuc init()
	public function init(){
		//Sys_Function_DocFunctions::CheckLogin();
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
		
		
		//Duong dan file JS xu ly modul
		$this->view->baseJavaUrl = "sys-js/jsList.js";
		
		//Goi lop Listxml_modList
		Zend_Loader::loadClass('Listxml_modBackup');
		Zend_Loader::loadClass('Zend_Config_Xml');
		//Lay cac hang so su dung trong JS public
		Zend_Loader::loadClass('Sys_Init_Config');
		$objConfig = new Sys_Init_Config();
		$this->view->JSPublicConst = $objConfig->_setJavaScriptPublicVariable();		
		
		//Tao doi tuong XML
		Zend_Loader::loadClass('Sys_Publib_Xml');		
		
		// Load tat ca cac file Js va Css
		$this->view->LoadAllFileJsCss = Sys_Publib_Library::_getAllFileJavaScriptCss('','js','jsList.js,jquery-1.4.2.min.js,jquery.simplemodal.js',',','js')
										.Sys_Publib_Library::_getAllFileJavaScriptCss('','style','simpleModal.css',',','css');;										
		/* Ket thuc*/
		
		//Dinh nghia current modul code
		$this->view->currentModulCode = "LIST";
		$this->view->currentModulCodeForLeft = "BACKUP";		
		
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
			
		$response->insert('header', $this->view->renderLayout('twd_header.phtml','template/'));    
		$response->insert('left', $this->view->renderLayout('twd_left_list.phtml','template/'));  	    
        $response->insert('footer', $this->view->renderLayout('twd_footer.phtml','template/'));
	}
	public function manualbackupAction(){
		// Tieu de cua Form cap  nhat
		$this->view->bodyTitle = 'SAO LƯU DỮ LIỆU THỦ CÔNG';
		$RecordFunctions 	= new Sys_Function_DocFunctions();
		$objBackup 			= new Listxml_modBackup();
		$objConfig			= new Sys_Init_Config();
		$ojbXmlLib 			= new Sys_Publib_Xml();
		$connectSQL = new Zend_Config_Ini('./config/config.ini','dbmssql');
		$arrConfig = $connectSQL->db->config->toArray();					
		$sDatabase = $arrConfig['dbname'];
		$this->view->sDatabaseName = '['.$sDatabase.']';
		$arrResult = $objBackup->getAllObjectbyListCode($_SESSION['OWNER_CODE'],'DM_TS_HT');
		//var_dump($arrResult);
		$this->view->urlbackup=$objConfig->_setWebSitePath().'listxml/backup/backupdatabase/';
		$sPath = $ojbXmlLib->_xmlGetXmlTagValue('<?xml version="1.0" encoding="UTF-8"?>'.$arrResult[0]['C_XML_DATA'],'data_list','path_backup');		
		$this->view->sPathbackup = $sPath;			
	   //  Thuc hien hieu chinh danh muc
	   $isUpdate = $this->_request->getParam('hdn_update','');
	   $sFileName = $this->_request->getParam('txt_fileName','');
	   if($isUpdate == '1'){	
	   		$objBackup->eCSBackupHand($sPath,$sDatabase,$sFileName);
		}	  
				
	}
	public function backupdatabaseAction(){			
		//Goi cac doi tuong	
		
		$objBackup					= new Listxml_modBackup();
		$sFileName = $this->_request->getParam('fileName','');
		$sPathbackup = $this->_request->getParam('path','');
		if(is_dir($sPathbackup)){
			$sDatabaseName = $this->_request->getParam('database','');
			$objBackup->eCSBackupHand($sPathbackup,$sDatabaseName,$sFileName);		
			echo 'Sao lưu dữ liệu thành công';		
		}else{
			echo 'Thư mục '.$sPathbackup.' không tồn tại';
		}
		exit;
	}	
}
?>
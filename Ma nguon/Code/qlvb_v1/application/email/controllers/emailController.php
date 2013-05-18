<?php
//echo "OK"; exit;
/**
 * Nguoi tao: HAIDV
 * Ngay tao: 20/07/2011
 * Y nghia: Class xu ly Lich ca nhan 
 */	
class email_emailController extends  Zend_Controller_Action {
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

		//Goi lop Listxml_modList
		Zend_Loader::loadClass('dashboard_modWebMenu');
		Zend_Loader::loadClass('received_modReceived');
		//Lay tat ca cac chuyen muc
		$objWebMenu = new dashboard_modWebMenu();
		$arrResul = $objWebMenu->WebMenuGetAll('4',$_SESSION['OWNER_CODE'],'3','1');
		$this->view->arrMenu = $arrResul;	
		/*
		//Lay nhac viec cho cong viec
		$arrTaskNoty = $objWebMenu->TaskWorkNotyGetAll($_SESSION['staff_id']);
		if($arrTaskNoty[0]['SENT'] > 0){
			$this->view->TaskSent = '<span style="color:#FF0000;"> ('.$arrTaskNoty[0]['SENT'].')</span>';	
		}else{
			$this->view->TaskSent = '';	
		}
		if($arrTaskNoty[0]['REC'] > 0){
			$this->view->TaskRec = '<span style="color:#FF0000;"> ('.$arrTaskNoty[0]['REC'].')</span>';	
		}else{
			$this->view->TaskRec = '';	
		}
		*/
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
		$this->view->JSPublicConst = $objConfig->_setJavaScriptPublicVariable();					
		// Load tat ca cac file Js va Css
		//$this->view->LoadAllFileJsCss = Sys_Publib_Library::_getAllFileJavaScriptCss('','js','util.js,js_calendar.js,jsSchedule.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','ajax.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','sys-js','jquery-1.4.2.min.js,jQuery.equalHeights.js',',','js'). Sys_Publib_Library::_getAllFileJavaScriptCss('','js/LibSearch','actb_search.js,common_search.js',',','js');
		$JSandStyle = Sys_Publib_Library::_getAllFileJavaScriptCss('','js','received.js,util.js,js_calendar.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','ajax.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','jquery-1.4.2.min.js,jQuery.equalHeights.js',',','js'). Sys_Publib_Library::_getAllFileJavaScriptCss('','js/LibSearch','actb_search.js,common_search.js',',','js');						
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
		//Dinh nghia current modul code
		$this->view->currentModulCode = "RECEIVED";				
		//Modul chuc nang						
		$this->view->currentModulCodeForLeft ="EMAIL-RECEIVED-DOC";
		$this->view->getStatusLeftMenu = $this->_request->getParam('status','');			
		//Lay trang thai left menu
		$this->view->getStatusLeftMenu = $this->_request->getParam('status','');
		//echo 'status = '.$this->_request->getParam('status','');	
		//Lay Quyen PHAN CONG XU LY VB DEN
		//$this->_publicPermission = Sys_Function_DocFunctions::Doc_AssignDocument($_SESSION['arrStaffPermission'],$_SESSION['staff_id']);	
		//Gan quyen PCXL sang VIEW
		//$this->view->PermissionAssigner = $this->_publicPermission;		
		//Mang quyen cua NSD hien thoi
		$arrPermission = $_SESSION['arrStaffPermission'];				
		//Hien thi file template
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
		$this->view->bodyTitle = "DANH SÁCH VĂN BẢN QUA EMAIL";
		//$this->view->bodyTitle = "Lịch làm việc của đồng chí ".$ojbSysLib->_InforStaff(); 				
		$arrInput = $this->_request->getParams();	
		$this->view->mailboxes = $ojbSysInitConfig->setInfoEmail();			
	}	
	
public function readAction(){		
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
		$objReceive = new received_modReceived();
		$this->view->bodyTitle = "NHẬP VĂN BẢN ĐẾN TỪ EMAIL";
		$arrSel = $objReceive->getPropertiesDocument('DM_TINH_CHAT_VB','','');
		$this->view->arrSel = $arrSel;
		
		$arrUrgent = $objReceive->getPropertiesDocument('DM_DO_KHAN_VAN_BAN','','');
		$this->view->arrUrgent = $arrUrgent;
		
		$arrInputBooks = $objReceive->getPropertiesDocument('DM_SO_VAN_BAN_DEN','','');
		$this->view->arrInputBooks = $arrInputBooks;
		
		$arrAgentcyGroup = $objReceive->getPropertiesDocument('DM_CAP_NOI_GUI_VAN_BAN','','');
		$this->view->arrAgentcyGroup = $arrAgentcyGroup;
		
		$arrAgentcyName = $objReceive->getPropertiesDocument('DM_NOI_GUI_VAN_BAN','','');
		$this->view->arrAgentcyName = $arrAgentcyName;
		
		//Lay danh sach cac linh vuc
		$arrDocCate = $objReceive->getPropertiesDocument('DM_LINH_VUC_VAN_BAN','','');
		
		$arrDocType = $objReceive->getPropertiesDocument('DM_LOAI_VAN_BAN','','');
		$this->view->arrDocType = $arrDocType;
		
		$arrSigner = $objReceive->getPropertiesDocument('DM_NGUOI_KY','','');
		$this->view->arrSigner = $arrSigner;
		
		$arrProcessType = $objReceive->getPropertiesDocument('DM_HINH_THUC_XU_LY','','');
		$this->view->AttachFile = $objDocFun->DocSentAttachFile(array(),0,10,true,25);
		$this->view->arrProcessType = $arrProcessType;
		//Lay thong tin trang hien thoi
		$iCurrentPage = $this->_request->getParam('hdn_current_page',0);
		$this->view->currentPage	= $iCurrentPage;	
		//Lay thong tin quy dinh so row / page
		$iNumRowOnPage = $this->_request->getParam('hdn_record_number_page',0);
		$this->view->numRowOnPage	= $iNumRowOnPage;	
		//Tieu chi tim kiem
		$sFullTextSearch = $this->_request->getParam('FullTextSearch','');
		$dFromDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($this->_request->getParam('fromDate',''));
		$dToDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($this->_request->getParam('toDate',''));
		//Luu cac gia tri tim kiem duoc nhap vao tu form 
        $this->view->fromDate = $ojbSysLib->_yyyymmddToDDmmyyyy($dFromDate);
        $this->view->toDate =  $ojbSysLib->_yyyymmddToDDmmyyyy($dToDate);
        $this->view->FullTextSearch = $sFullTextSearch;
		// Goi ham search
		$this->view->search_textselectbox_agentcy_group = Sys_Function_DocFunctions::doc_search_ajax($arrAgentcyGroup,"C_CODE","C_NAME","C_AGENTCY_GROUP","hdn_agentcy_group",1,'',1);
		$this->view->search_textselectbox_agentcy_name = Sys_Function_DocFunctions::doc_search_ajax($arrAgentcyName,"C_CODE","C_NAME","C_AGENTCY_NAME","hdn_agentcy_name",1,'',1);
		$this->view->search_textselectbox_doc_type = Sys_Function_DocFunctions::doc_search_ajax($arrDocType,"C_CODE","C_NAME","C_DOC_TYPE","hdn_doc_type",1,'',1);
		// Goi ham textselectbox lay ra nguoi ky
		$this->view->search_doc_cate_name = $objDocFun->doc_search_ajax($arrDocCate,"C_CODE","C_NAME","C_DOC_CATE","hdn_doc_cate_name",1,"",1);
		
		
		$mailboxes = $ojbSysInitConfig->setInfoEmail();
		$stream = @imap_open($mailboxes[0]['mailbox'],$mailboxes[0]['username'],$mailboxes[0]['password']);		
		$email_id = $this->_request->getParam('hdn_object_id','');
		$message = imap_fetchbody($stream,$email_id,2);		
		$overview = imap_fetch_overview($stream,$email_id,0);		
		$text = $ojbSysInitConfig->ReplaceImap($message);				
		$subject = $ojbSysInitConfig->decode_imap_text($overview[0]->subject);
		$this->view->subject = $subject;
		$structure = imap_fetchstructure($stream, $email_id);
		$emails = imap_search($stream,'ALL');
		$path = 'D:\temp';
		/* useful only if the above search is set to 'ALL' */
		$max_emails = 16;
		$email_number = $email_id;
		/* if any emails found, iterate through each email */
		if($emails) {
		     $count = 1;
		    /* put the newest emails on top */
		    rsort($emails);
		    /* for every email... */
		   // foreach($emails as $email_number) 
		    //{
		        /* get information specific to this email */
		        $overview = imap_fetch_overview($stream,$email_number,0);
		        /* get mail message */
		        $message = imap_fetchbody($stream,$email_number,2);
		        /* get mail structure */
		        $structure = imap_fetchstructure($stream, $email_number);
		        $attachments = array();
		        /* if any attachments found... */
		        if(isset($structure->parts) && count($structure->parts)) 
		        {
		            for($i = 0; $i < count($structure->parts); $i++) 
		            {
		                $attachments[$i] = array(
		                    'is_attachment' => false,
		                    'filename' => '',
		                    'name' => '',
		                    'attachment' => ''
		                );
		 
		                if($structure->parts[$i]->ifdparameters) 
		                {
		                    foreach($structure->parts[$i]->dparameters as $object) 
		                    {
		                        if(strtolower($object->attribute) == 'filename') 
		                        {
		                            $attachments[$i]['is_attachment'] = true;
		                            $attachments[$i]['filename'] = $object->value;
		                        }
		                    }
		                } 
		                if($structure->parts[$i]->ifparameters) 
		                {
		                    foreach($structure->parts[$i]->parameters as $object) 
		                    {
		                        if(strtolower($object->attribute) == 'name') 
		                        {
		                            $attachments[$i]['is_attachment'] = true;
		                            $attachments[$i]['name'] = $object->value;
		                        }
		                    }
		                } 
		                if($attachments[$i]['is_attachment']) 
		                {
		                    $attachments[$i]['attachment'] = imap_fetchbody($stream, $email_number, $i+1);
		 
		                    /* 4 = QUOTED-PRINTABLE encoding */
		                    if($structure->parts[$i]->encoding == 3) 
		                    { 
		                        $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
		                    }
		                    /* 3 = BASE64 encoding */
		                    elseif($structure->parts[$i]->encoding == 4) 
		                    { 
		                        $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
		                    }
		                }
		            }
		        //}        
		        foreach($attachments as $attachment)
		        {
		            if($attachment['is_attachment'] == 1)
		            {
		                $filename = $attachment['name'];
		                if(empty($filename)) $filename = $attachment['filename'];
		 
		                if(empty($filename)) $filename = time() . ".dat";
		 
		                /* prefix the email number to the filename in case two emails
		                 * have the attachment with the same file name.
		                 */
						$fileName = $email_number . "-" . $filename; 
		                $fp = fopen($email_number . "-" . $filename, "w+");	
						//var_dump($attachment['attachment']);
		                fwrite($fp, $attachment['attachment']);
		                fclose($fp);
		            }
		        }
		        if($count++ >= $max_emails) break;
		    } 
		} 
		imap_close($stream);				
	}
	
	
	
public function addAction(){
		
		$this->view->bodyTitle = 'VÀO SỔ VĂN BẢN ĐẾN';
		$arrInput = $this->_request->getParams();
		$objDocFun = new Sys_Function_DocFunctions();
		$objReceive = new received_modReceived();
		$ojbXmlLib = new Sys_Publib_Xml();
		$ojbSysLib = new Sys_Library();
		$objFilter = new Zend_Filter();
		$ojbSysInitConfig = new Sys_Init_Config();	
		 //Lay thong tin history back
		$this->view->historyBack = $this->_request->getParam('hdn_history_back','');
		//Lay tham so cau hinh
		$sysLibUrlPath = $ojbSysInitConfig->_setLibUrlPath();
		$url_path_calendar = $sysLibUrlPath . 'sys-calendar/';
		$this->view->urlCalendar = $url_path_calendar;
		
		$arrSel = $objReceive->getPropertiesDocument('DM_TINH_CHAT_VB','','');
		$this->view->arrSel = $arrSel;
		
		$arrUrgent = $objReceive->getPropertiesDocument('DM_DO_KHAN_VAN_BAN','','');
		$this->view->arrUrgent = $arrUrgent;
		
		$arrInputBooks = $objReceive->getPropertiesDocument('DM_SO_VAN_BAN_DEN','','');
		$this->view->arrInputBooks = $arrInputBooks;
		
		$arrAgentcyGroup = $objReceive->getPropertiesDocument('DM_CAP_NOI_GUI_VAN_BAN','','');
		$this->view->arrAgentcyGroup = $arrAgentcyGroup;
		
		$arrAgentcyName = $objReceive->getPropertiesDocument('DM_NOI_GUI_VAN_BAN','','');
		$this->view->arrAgentcyName = $arrAgentcyName;
		
		//Lay danh sach cac linh vuc
		$arrDocCate = $objReceive->getPropertiesDocument('DM_LINH_VUC_VAN_BAN','','');
		
		$arrDocType = $objReceive->getPropertiesDocument('DM_LOAI_VAN_BAN','','');
		$this->view->arrDocType = $arrDocType;
		
		$arrSigner = $objReceive->getPropertiesDocument('DM_NGUOI_KY','','');
		$this->view->arrSigner = $arrSigner;
		
		$arrProcessType = $objReceive->getPropertiesDocument('DM_HINH_THUC_XU_LY','','');
		$this->view->arrProcessType = $arrProcessType;
		//Lay thong tin trang hien thoi
		$iCurrentPage = $this->_request->getParam('hdn_current_page',0);
		$this->view->currentPage	= $iCurrentPage;	
		//Lay thong tin quy dinh so row / page
		$iNumRowOnPage = $this->_request->getParam('hdn_record_number_page',0);
		$this->view->numRowOnPage	= $iNumRowOnPage;	
		//Tieu chi tim kiem
		$sFullTextSearch = $this->_request->getParam('FullTextSearch','');
		$dFromDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($this->_request->getParam('fromDate',''));
		$dToDate = $ojbSysLib->_ddmmyyyyToYYyymmdd($this->_request->getParam('toDate',''));
		//Luu cac gia tri tim kiem duoc nhap vao tu form 
        $this->view->fromDate = $ojbSysLib->_yyyymmddToDDmmyyyy($dFromDate);
        $this->view->toDate =  $ojbSysLib->_yyyymmddToDDmmyyyy($dToDate);
        $this->view->FullTextSearch = $sFullTextSearch;
		// Goi ham search
		$this->view->search_textselectbox_agentcy_group = Sys_Function_DocFunctions::doc_search_ajax($arrAgentcyGroup,"C_CODE","C_NAME","C_AGENTCY_GROUP","hdn_agentcy_group",1,'',1);
		$this->view->search_textselectbox_agentcy_name = Sys_Function_DocFunctions::doc_search_ajax($arrAgentcyName,"C_CODE","C_NAME","C_AGENTCY_NAME","hdn_agentcy_name",1,'',1);
		$this->view->search_textselectbox_doc_type = Sys_Function_DocFunctions::doc_search_ajax($arrDocType,"C_CODE","C_NAME","C_DOC_TYPE","hdn_doc_type",1,'',1);
		// Goi ham textselectbox lay ra nguoi ky
		$this->view->search_doc_cate_name = $objDocFun->doc_search_ajax($arrDocCate,"C_CODE","C_NAME","C_DOC_CATE","hdn_doc_cate_name",1,"",1);
		
		//Gan quyen sang VIEW
		$this->view->PermissionUser = $this->_publicPermission;
		
		//Tuy chon ung voi cac truong hop update du lieu	
		$sOption = $this->_request->getParam('hdh_option','');
		$this->view->option = $sOption;
			
		$sXmlTagValueList = $this->_request->getParam('hdn_XmlTagValueList','');
		//Tao xau XML luu CSDL
		if ($sXmlTagValueList != ""){
			$arrXmlTagValue = explode("|{*^*}|",$sXmlTagValueList);
			if($arrXmlTagValue[0] != "" && $arrXmlTagValue[1] != ""){
				//Danh sach THE
				$sXmlTagList = $arrXmlTagValue[0];
				//Danh sach GIA TRI
				$sXmlValueList = $arrXmlTagValue[1];
				//Tao xau XML luu CSDL					
				$sXmlStringInDb = $ojbXmlLib->_xmlGenerateXmlDataString($sXmlTagList, $sXmlValueList);					
			}
		}
		$this->view->AttachFile = $objDocFun->DocSentAttachFile(array(),0,10,true,25);
		
		if ($objFilter->filter($arrInput['C_SUBJECT']) != ""){			
			$sStatus = 'CHO_PHAN_PHOI';	
			//Thuc hien upload file len o cung toi da 10 file
			$arrFileNameUpload = $ojbSysLib->_uploadFileList(10,$this->_request->getBaseUrl() . "/public/attach-file/",'FileName','!#~$|*');
			//var_dump($arrFileNameUpload); exit;
			//Mang luu tham so update in database	
			$arrParameter = array(	
								'PK_RECEIVED_DOC'				=>'',										
								'FK_UNIT'						=>$_SESSION['OWNER_ID'],
								'C_SYMBOL'						=>$ojbSysLib->_replaceBadChar($objFilter->filter($arrInput['C_SYMBOL'])),
								'C_RELEASE_DATE'				=>$ojbSysLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['C_RELEASE_DATE'])),
								'C_AGENTCY_GROUP'				=>$objFilter->filter($arrInput['C_AGENTCY_GROUP']),
								'C_AGENTCY_NAME'				=>$objFilter->filter($arrInput['C_AGENTCY_NAME']),
								'C_DOC_TYPE'					=>$objFilter->filter($arrInput['C_DOC_TYPE']),
								'C_DOC_CATE'					=>$objFilter->filter($arrInput['C_DOC_CATE']),
								'C_SUBJECT'						=>$ojbSysLib->_replaceBadChar($objFilter->filter($arrInput['C_SUBJECT'])),
								'C_TEXT_BOOK'					=>$objFilter->filter($arrInput['C_TEXT_BOOK']),
								'C_NUM'							=>$objFilter->filter($arrInput['C_NUM']),
								'C_RECEIVED_DATE'				=>$ojbSysLib->_ddmmyyyyToYYyymmdd($objFilter->filter($arrInput['C_RECEIVED_DATE'])),
								'C_NATURE'						=>$objFilter->filter($arrInput['C_NATURE']),
								'C_TEXT_OF_EMERGENCY'			=>$objFilter->filter($arrInput['C_TEXT_OF_EMERGENCY']),
								'C_TYPE_PROCESSING'				=>$objFilter->filter($arrInput['C_TYPE_PROCESSING']),
								'C_STATUS'						=>$sStatus,	
								'C_XML_DATA'					=>$sXmlStringInDb,
								'ATTACH_FILE_NAME_LIST'			=>$arrFileNameUpload
						);
							
			$Result = "";
			//var_dump($arrParameter);exit;				
			$Result = $objReceive->DocReceivedUpdate($arrParameter);				
			//Luu gia tri												
			$arrParaSet = array("hdn_current_page"=>$iCurrentPage,"hdn_record_number_page"=>$iNumRowOnPage,"FullTextSearch"=>$sFullTextSearch, "fromDate"=>$dFromDate, "toDate"=>$dToDate);
			//var_dump($arrParaSet); exit;
			$_SESSION['seArrParameter'] = $arrParaSet;
			$this->_request->setParams($arrParaSet);
			//Truong hop ghi va quay lai
			if ($sOption == "GHI_QUAYLAI"){									
				$this->_redirect('received/documents/index/');			
			}	
				
		}
	}
	
}
?>
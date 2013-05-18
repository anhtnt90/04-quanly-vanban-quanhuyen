<?php
/**
 * Nguoi tao: QUANGDD
 * Ngay tao: 09/11/2010
 * Y nghia: Class xu ly Chuc vu Can bo
 */	
class user_positionController extends  Zend_Controller_Action {
	/**
	 * (non-PHPdoc)
	 * @see Zend_Controller_Action::init()
	 */
	public $objConfig;
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
		//Ky tu dac biet phan tach giua cac phan tu
		$this->view->delimitor 	= "!~~!";	
		//Load cau hinh thu muc trong file config.ini de lay ca hang so dung chung
        $tempConstPublic = Zend_Registry::get('ConstPublic');
		$this->_ConstPublic = $tempConstPublic->toArray();	
		
		//Lay duong dan thu muc goc (path directory root)
		$this->view->baseUrl = $this->_request->getBaseUrl() . "/public/";	
			
		//Goi lop user_modPosition
		Zend_Loader::loadClass('user_modPosition');
		
		//Lay cac hang so su dung trong JS public
		$objConfig = new Sys_Init_Config();
		$this->objConfig = $objConfig;
		$this->view->JSPublicConst = $objConfig->_setJavaScriptPublicVariable();		
		
		//Tao doi tuong XML
		Zend_Loader::loadClass('Sys_Publib_Xml');		
		
		// Load tat ca cac file Js va Css
		$this->view->LoadAllFileJsCss = Sys_Publib_Library::_getAllFileJavaScriptCss('','js','position.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','jquery-1.4.3.min.js,ajax.js',',','js');;																
				
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
		
		//Dinh nghia current modul code
		$this->view->currentModulCode = "ORG";
		
		//Hien thi file template
		$response->insert('header', $this->view->renderLayout('header.phtml','./application/views/scripts/'));    	//Hien thi header 
		$response->insert('left', $this->view->renderLayout('left.phtml','./application/views/scripts/'));    		//Hien thi header 		    
        $response->insert('footer', $this->view->renderLayout('footer.phtml','./application/views/scripts/'));  
	}	
	/**
	 * Idea : Phuong thuc hien thi danh sach
	 */
	public function indexAction(){		
		//Xac dinh left module
		$this->view->currentModulCodeForLeft = "POSITION";
		//Hien thi left menu
		$this->_response->insert('left', $this->view->renderLayout('left.phtml','./application/views/scripts/')); 	
		//Goi cac doi tuong
		$objPosition 	= new user_modPosition();
		$objFunction	= new Sys_Function_RecordFunctions();		
		// Tao doi tuong Zend_Filter
		$filter = new Zend_Filter();	
		//Tieu de man hinh danh sach
		$this->view->bodyTitle = 'DANH SÁCH CHỨC VỤ CÁN BỘ';
		//Lay cac hang so dung chung
		$arrConst = $this->objConfig->_setProjectPublicConst();
		//var_dump($arrConst);
		$this->view->arrConst = $arrConst;
		// Lay toan bo tham so truyen tu form			
		$arrInput = $this->_request->getParams();	
		//Lay nhom chuc vu
		$this->view->arrPositionGroup = $objPosition->UserPositionGroupGetAll('','HOAT_DONG');
		//Lay trang thai
		$arrStatus = $objFunction->getAllObjectbyListCode($_SESSION['OWNER_CODE'],'DM_TINH_TRANG',1);
		$this->view->arrStatus = $arrStatus;
		//
		session_start();
		if (isset($_SESSION['seArrParameter'])){
				$arrParaInSession		= $_SESSION['seArrParameter'];
				$sFullTextSearch 		= $arrParaInSession['hdn_fulltextsearch'];
				$sFkPositionGroupId		= $arrParaInSession['hdn_filter_positiongroup'];
				$sStatus				= $arrParaInSession['hdn_filter_status'];
				unset($_SESSION['seArrParameter']);								
		}else {
			$sFullTextSearch		= $filter->filter($arrInput['txtFullTextSearch'],'');
			$sFkPositionGroupId 	= $filter->filter($arrInput['C_FILTER_POSITION_GROUP'],'');
			$sStatus				= $filter->filter($arrInput['C_FILTER_STATUS'],'');	
		}
		//Lay du lieu trong CSDL			
		$this->view->sPositionGroup 		= $sFkPositionGroupId;
		$this->view->txtFullTextSearch 		= $sFullTextSearch;
		$this->view->sStatus				= $sStatus;
		$arrResult = $objPosition->UserPositionGetAll($sFkPositionGroupId,$sFullTextSearch,$sStatus);
		$this->view->arrResult = $arrResult;
		//var_dump($arrResult);
	}
	
	/**
	 * @author : Sys
	 * @tutorial : Tao phuong thuc xu ly Them moi mot chuc vu
	 */
	public function addAction(){ 
		//Xac dinh left module
		$this->view->currentModulCodeForLeft = "POSITION";
		//Hien thi left menu
		$this->_response->insert('left', $this->view->renderLayout('left.phtml','./application/views/scripts/')); 
		//Goi cac doi tuong
		$objPosition 			 	= new user_modPosition();
		$objFunction	     		= new Sys_Function_RecordFunctions();	
		$ojbSysLib					= new Sys_library();
		// Tao doi tuong Zend_Filter
		$objFilter 					= new Zend_Filter();
		// Lay toan bo tham so truyen tu form			
		$arrInput = $this->_request->getParams();
		//var_dump($arrInput);
		//Lay nhom chuc vu
		$this->view->arrPositionGroup = $objPosition->UserPositionGroupGetAll('','HOAT_DONG');
		//Get Order
		$iOrder = $ojbSysLib->_getNextValue("T_USER_POSITION","C_ORDER","1 = 1");
		$this->view->iOrder = $iOrder;
		//echo $iOrder . '<br>';
		//Request tham so			
		$sFkPositionGroupId = $objFilter->filter($arrInput['C_POSITION_GROUP'],'');
		$sStatus 			= $objFilter->filter($arrInput['C_STATUS'],'');
				//
		$sFilterPositionGroup		=$this->_request->getParam('hdn_filter_positiongroup',"");
		$this->view->C_FILTER_POSITION_GROUP=$sFilterPositionGroup;
		$sFilterStatus				=$this->_request->getParam('hdn_filter_status',"");
		$this->view->C_FILTER_STATUS=$sFilterStatus;
		$sFullTextSearch			= $this->_request->getParam('hdn_fulltextsearch',"");
		$this->view->txtFullTextSearch=$sFullTextSearch;		
		//var_dump($arrPositionSingle);
		$sSetStatus = "KHONG_HOAT_DONG";
		if ($sStatus){
			$sSetStatus = "HOAT_DONG";
		}
		//Luu cac bien trong tieu chi tim kiem vao sesion
		$arrParaSet = array("hdn_fulltextsearch"=>$sFullTextSearch,"hdn_filter_positiongroup"=>$sFilterPositionGroup,"hdn_filter_status"=>$sFilterStatus);
		@session_start();
		$_SESSION['seArrParameter'] = $arrParaSet;
		$this->_request->setParams($arrParaSet);	
	
		//Tieu de man hinh danh sach
		$this->view->bodyTitle = 'THÊM MỚI MỘT CHỨC VỤ';
		//Lay cac hang so dung chung
		$arrConst = $this->objConfig->_setProjectPublicConst();
		//var_dump($arrConst);
		$this->view->arrConst = $arrConst;
	
		//Mang luu tham so update in database
		$arrParameter = array(									
								'PK_POSITION'					=>'',
								'FK_POSITION_GROUP'				=>$sFkPositionGroupId,
								'C_CODE'						=>trim($ojbSysLib->_replaceBadChar($objFilter->filter($arrInput['C_POSITION_CODE']))),
								'C_NAME'						=>trim($ojbSysLib->_replaceBadChar($objFilter->filter($arrInput['C_POSITION_NAME']))),
								'C_ORDER'						=>intval($objFilter->filter($arrInput['C_ORDER'])),
								'C_STATUS'						=>$sSetStatus
						);
		$arrResult = "";
		//Goi lenh update
		if ($objFilter->filter($arrInput['C_POSITION_CODE']) != ""){		
			//Update
			$arrResult = $objPosition->UserPositionUpdate($arrParameter);							
			// Neu add khong thanh cong			
			if($arrResult['RET_ERROR'] != null || $arrResult['RET_ERROR'] != '' ){											
				echo "<script type='text/javascript'>";
				echo "alert('" . $arrResult['RET_ERROR'] . "');\n";				
				echo "</script>";
			}else {			
					//Tro ve trang index						
					$this->_redirect('user/position/index/');						
				}
		}	
	}
	/**
	 * @author : Sys
	 * @tutorial : Tao phuong thuc xu ly SUA moi mot chuc vu
	 */
	public function editAction(){ 
		//Xac dinh left module
		$this->view->currentModulCodeForLeft = "POSITION";
		//Hien thi left menu
		$this->_response->insert('left', $this->view->renderLayout('left.phtml','./application/views/scripts/')); 
		//Goi cac doi tuong
		$objPosition 			 	= new user_modPosition();
		$objFunction	     		= new Sys_Function_RecordFunctions();	
		$ojbSysLib					= new Sys_library();
		// Tao doi tuong Zend_Filter
		$objFilter 					= new Zend_Filter();
		//Tieu de man hinh danh sach
		$this->view->bodyTitle = 'HIỆU CHỈNH MỚI MỘT CHỨC VỤ';
		//Lay cac hang so dung chung
		$arrConst = $this->objConfig->_setProjectPublicConst();
		//var_dump($arrConst);
		$this->view->arrConst = $arrConst;
		// Lay toan bo tham so truyen tu form			
		$arrInput = $this->_request->getParams();	
		//var_dump($arrInput);
		//Lay nhom chuc vu
		$this->view->arrPositionGroup = $objPosition->UserPositionGroupGetAll('','HOAT_DONG');
		//Request tham so			
		$sFkPositionGroupId = $objFilter->filter($arrInput['C_POSITION_GROUP'],'');
		$sPositionId		=$this->_request->getParam('hdn_object_id',"");
		$this->view->sPositionId = $sPositionId;
		$sStatus 				= $objFilter->filter($arrInput['C_STATUS'],'');
		//
		$sFilterPositionGroup		=$this->_request->getParam('hdn_filter_positiongroup',"");
		$this->view->C_FILTER_POSITION_GROUP=$sFilterPositionGroup;
		$sFilterStatus				=$this->_request->getParam('hdn_filter_status',"");
		$this->view->C_FILTER_STATUS=$sFilterStatus;
		$sFullTextSearch			= $this->_request->getParam('hdn_fulltextsearch',"");
		$this->view->txtFullTextSearch=$sFullTextSearch;		
		//Lay thong tin chi tiet CHuc vu
		$arrPositionSingle = $objPosition->UserPositionGetSingle($sPositionId);
		$this->view->arrPositionSingle = $arrPositionSingle;
		//var_dump($arrPositionSingle);
		$sSetStatus = "KHONG_HOAT_DONG";
		if ($sStatus){
			$sSetStatus = "HOAT_DONG";
		}
		//Luu cac bien trong tieu chi tim kiem vao sesion
		$arrParaSet = array("hdn_fulltextsearch"=>$sFullTextSearch,"hdn_filter_positiongroup"=>$sFilterPositionGroup,"hdn_filter_status"=>$sFilterStatus);
		//var_dump($arrParaSet); exit;
		@session_start();
		$_SESSION['seArrParameter'] = $arrParaSet;
		$this->_request->setParams($arrParaSet);	
		//Mang luu tham so update in database
		$arrParameter = array(									
								'PK_POSITION'					=>$sPositionId,
								'FK_POSITION_GROUP'				=>$sFkPositionGroupId,
								'C_CODE'						=>trim($ojbSysLib->_replaceBadChar($objFilter->filter($arrInput['C_POSITION_CODE']))),
								'C_NAME'						=>trim($ojbSysLib->_replaceBadChar($objFilter->filter($arrInput['C_POSITION_NAME']))),
								'C_ORDER'						=>intval($objFilter->filter($arrInput['C_ORDER'])),
								'C_STATUS'						=>$sSetStatus
						);
		$arrResult = "";
		//Goi lenh update
		if ($objFilter->filter($arrInput['C_POSITION_CODE']) != ""){		
			//Update
			$arrResult = $objPosition->UserPositionUpdate($arrParameter);							
			// Neu add khong thanh cong			
			if($arrResult['RET_ERROR'] != null || $arrResult['RET_ERROR'] != '' ){											
				echo "<script type='text/javascript'>";
				echo "alert('" . $arrResult['RET_ERROR'] . "');\n";				
				echo "</script>";
			}else {	
				//Tro ve trang index						
				$this->_redirect('user/position/index/');						
			}
		}	
	}
	/**
	 * Creater : Sys
	 * Date : 17/06/2011
	 * Idea : Tao phuong thuc xu ly XOA mot hoac nhieu CHUC VU
	 */
	public function deleteAction(){
		//Goi cac doi tuong
		$objPosition 			 	= new user_modPosition();
		$sPositionIdList			= $this->_request->getParam(hdn_object_id_list,'');// Lay gia tri bien can xoa tu V(V->C)
		//
		$sFilterPositionGroup		=$this->_request->getParam('hdn_filter_positiongroup',"");
		$this->view->C_FILTER_POSITION_GROUP=$sFilterPositionGroup;
		$sFilterStatus				=$this->_request->getParam('hdn_filter_status',"");
		$this->view->C_FILTER_STATUS=$sFilterStatus;
		$sFullTextSearch			= $this->_request->getParam('hdn_fulltextsearch',"");
		$this->view->txtFullTextSearch=$sFullTextSearch;		
		//Luu cac bien trong tieu chi tim kiem vao sesion
		$arrParaSet = array("hdn_fulltextsearch"=>$sFullTextSearch,"hdn_filter_positiongroup"=>$sFilterPositionGroup,"hdn_filter_status"=>$sFilterStatus);
		@session_start();
		$_SESSION['seArrParameter'] = $arrParaSet;
		$this->_request->setParams($arrParaSet);	
		
		$arrResult	= $objPosition->UserPositionDelete($sPositionIdList);//Goi function delete trong mod bang bien $sRetError(thong bao loi)
		// Neu Delete khong thanh cong			
		if($arrResult['RET_ERROR'] != null || $arrResult['RET_ERROR'] != '' ){											
			echo "<script type='text/javascript'>";
			echo "alert('" . $arrResult['RET_ERROR'] . "');\n";				
			echo "</script>";
		}else {			
			//Tro ve trang index						
			$this->_redirect('user/position/index/');						
		}
	}	
}?>

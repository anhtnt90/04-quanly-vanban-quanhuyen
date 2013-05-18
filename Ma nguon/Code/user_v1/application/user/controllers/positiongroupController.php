<?php
/**
 * Nguoi tao: TuyenNH
 * Ngay tao: 16/06/2011
 * Y nghia: Class xu ly nhom Chuc vu Can bo
 */	
class user_positiongroupController extends  Zend_Controller_Action {
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
		//Load class user_modPositionGroup
		Zend_Loader::loadClass('user_modPositionGroup');
		Zend_Loader::loadClass('Sys_Init_Config');
		//Lay duong dan thu muc goc (path directory root)
		$this->view->baseUrl = $this->_request->getBaseUrl() . "/public/";
		//Lay cac hang so su dung trong JS public
		$objConfig = new Sys_Init_Config();
		$this->view->JSPublicConst = $objConfig->_setJavaScriptPublicVariable();		
		$this->view->UrlAjax = $objConfig->_setUrlAjax();
		//Tao doi tuong XML
		Zend_Loader::loadClass('Sys_Publib_Xml');		
		// Load tat ca cac file Js va Css
		$this->view->LoadAllFileJsCss = Sys_Publib_Library::_getAllFileJavaScriptCss('','js','position.js,jquery-1.4.3.min.js,ajax.js',',','js');																		
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
		$response->insert('left', $this->view->renderLayout('left.phtml','./application/views/scripts/'));    		//Hien thi left		    
        $response->insert('footer', $this->view->renderLayout('footer.phtml','./application/views/scripts/'));  	//Hien thi footer
	}	
	/**
	 * Idea : Phuong thuc hien thi danh sach
	 */
	public function indexAction(){		
		//Xac dinh left module
		$this->view->currentModulCodeForLeft = "POSITION-GROUP";
		//Hien thi left menu
		$this->_response->insert('left', $this->view->renderLayout('left.phtml','./application/views/scripts/')); 	
		//Goi cac doi tuong
		$objInitConfig 			 	= new Sys_Init_Config();
		$objRecordFunction	     	= new Sys_Function_RecordFunctions();
		$objPositionGroup			= new user_modPositionGroup();	
		//Tao doi tuong Zend_Filter
		$filter = new Zend_Filter();
		//Tieu de man hinh danh sach
		$this->view->bodyTitle = 'DANH SÁCH NHÓM CHỨC VỤ';
		//Lay cac hang so dung chung
		$arrConst =$objInitConfig->_setProjectPublicConst();
		$this->view->arrConst= $arrConst;//C-> V(arrConst)
		//lay toan bo tham so truyen tu form
		$arrInput= $this-> _request-> getParams();
		//lay du lieu trong CSDL
		//lay thong tin tim kiem trong session
		@session_start();
		if (isset($_SESSION['seArrParameter'])){
			$arrParaInSession = $_SESSION['seArrParameter'];
			$sFullTextSearch = $arrParaInSession['hdn_fulltextsearch'];
			unset($_SESSION['seArrParameter']);								
		}else {
			$sFullTextSearch=$filter->filter($arrInput['txtFullTextSearch'],'');
		}
		$this-> view-> txtFullTextSearch= $sFullTextSearch;
		//var_dump($arrInput);
		//Lay nhom chuc vu
		$arrResult= $objPositionGroup->UserPositionGroupGetAll($sFullTextSearch,'');
		$this-> view-> arrResult=$arrResult;// Note "=" not "->" ^^
	}
	/**
	 * @author : TuyenNH
	 * date: 13/06/2011
	 * @tutorial : Tao phuong thuc xu ly Them moi mot chuc vu
	 */
	public function addAction(){ 
		//Xac dinh left module
		$this->view->currentModulCodeForLeft = "POSITION-GROUP";
		//Hien thi left menu
		$this->_response->insert('left', $this->view->renderLayout('left.phtml','./application/views/scripts/')); 
		//Goi cac doi tuong
		$objPositionGroup 			 	= new user_modPositionGroup();
		$objFunction	     			= new Sys_Function_RecordFunctions();	
		$objInitConfig					= new Sys_Init_Config();
		$ojbSysLib					= new Sys_library();
		// Tao doi tuong Zend_Filter
		$objFilter 					= new Zend_Filter();
		//Tieu de man hinh danh sach
		$this->view->bodyTitle = 'THÊM MỚI MỘT NHÓM CHỨC VỤ';
		//Lay cac hang so dung chung
		$arrConst =$objInitConfig->_setProjectPublicConst();
		//Lay cac hang so dung chung
		$this->view->arrConst = $arrConst;
		// Lay toan bo tham so truyen tu form			
		$arrInput = $this->_request->getParams();
		//Get Order
		$iOrder = $ojbSysLib->_getNextValue("T_USER_POSITION_GROUP","C_ORDER","1 = 1");
		$this->view->iOrder = $iOrder;
		//Request tham so			
		$sStatus 			= $objFilter->filter($arrInput['C_STATUS'],'');
		$sSetStatus = "KHONG_HOAT_DONG";
		if ($sStatus){//Checked
			$sSetStatus = "HOAT_DONG";
		}
		$sFullTextSearch = $this->_request->getParam('hdn_fulltextsearch',"");
		$this->view->txtFullTextSearch=$sFullTextSearch;
		//Luu giá trị tìm kiếm vào biến session để sau khi thực hiện xong một trong tác thao tác thêm/sửa/xóa thì tại màn hình danh sách request lại được đúng giá trị
		$arrParaSet = array("hdn_fulltextsearch"=>$sFullTextSearch);
		@session_start();
		$_SESSION['seArrParameter'] = $arrParaSet;
		$this->_request->setParams($arrParaSet);
		//Mang luu tham so update trong database
		$arrParameter = array(									
								'PK_POSITION_GROUP'				=>'',
								'C_CODE'						=>trim($ojbSysLib->_replaceBadChar($objFilter->filter($arrInput['C_POSITION_GROUP_CODE']))),
								'C_NAME'						=>trim($ojbSysLib->_replaceBadChar($objFilter->filter($arrInput['C_POSITION_GROUP_NAME']))),
								'C_ORDER'						=>intval($objFilter->filter($arrInput['C_ORDER'])),
								'C_STATUS'						=>$sSetStatus
						);
		$arrResult = "";
		//Goi lenh update
		if ($objFilter->filter($arrInput['C_POSITION_GROUP_CODE']) != ""){		
			//Update
			$arrResult = $objPositionGroup->UserPositionGroupUpdate($arrParameter);							
			// Neu add khong thanh cong			
			if($arrResult['RET_ERROR'] != null || $arrResult['RET_ERROR'] != '' ){											
				echo "<script type='text/javascript'>";
				echo "alert('" . $arrResult['RET_ERROR'] . "');\n";				
				echo "</script>";
			}else {			
					//Tro ve trang index						
					$this->_redirect('user/positiongroup/index/');						
				}
		}
	}
/**
 * Idea: Chinh sua mot nhom chuc vu
 * Nguoi tao: TuyenNH
 * Ngay tao: 16/06/2011
 * Enter description here ...
 */
	public function editAction(){ 
		//Xac dinh left module
		$this->view->currentModulCodeForLeft = "POSITION_GROUP";
		//Hien thi left menu
		$this->_response->insert('left', $this->view->renderLayout('left.phtml','./application/views/scripts/')); 
		//Goi cac doi tuong
		$objPositionGroup			= new user_modPositionGroup();
		$objFunction	     		= new Sys_Function_RecordFunctions();	
		$ojbSysLib					= new Sys_library();
		$objConfig					= new Sys_Init_Config();
		// Tao doi tuong Zend_Filter
		$objFilter 					= new Zend_Filter();
		//Tieu de man hinh danh sach
		$this->view->bodyTitle = 'HIỆU CHỈNH MỘT NHÓM CHỨC VỤ';
		//Lay cac hang so dung chung
		$arrConst = $objConfig->_setProjectPublicConst();
		//var_dump($arrConst);
		$this->view->arrConst = $arrConst;
		// Lay toan bo tham so truyen tu form			
		$arrInput = $this->_request->getParams();
		//Lay nhom chuc vu
		$this->view->arrPositionGroup = $objPositionGroup->UserPositionGroupGetAll('','HOAT_DONG');
		//Request tham so			
		$sPositionGroupId		=$this->_request->getParam('hdn_object_id',"");
		$sFullTextSearch		=$this->_request->getParam('hdn_fulltextsearch',"");
		$this->view->txtFullTextSearch=$sFullTextSearch;
		$this->view->sPositionGroupId = $sPositionGroupId;
		$sStatus 			= $objFilter->filter($arrInput['C_STATUS'],'');
		//Luu giá trị tìm kiếm vào biến session để sau khi thực hiện xong một trong tác thao tác thêm/sửa/xóa thì tại màn hình danh sách request lại được đúng giá trị
		$arrParaSet = array("hdn_fulltextsearch"=>$sFullTextSearch);
		//var_dump($arrParaSet); exit;
		@session_start();
		$_SESSION['seArrParameter'] = $arrParaSet;
		$this->_request->setParams($arrParaSet);

		//Lay thong tin chi tiet CHuc vu
		$arrPositionGroupSingle = $objPositionGroup->UserPositionGroupGetSingle($sPositionGroupId);
		$this->view->arrPositionGroupSingle = $arrPositionGroupSingle;
		//var_dump($arrPositionGroupSingle[C_STATUS]);
		$sSetStatus = "KHONG_HOAT_DONG";
		if ($sStatus){
			$sSetStatus = "HOAT_DONG";
		}
		//Mang luu tham so update in database
		$arrParameter = array(									
								'PK_POSITION_GROUP'				=>$sPositionGroupId,
								'C_CODE'						=>trim($ojbSysLib->_replaceBadChar($objFilter->filter($arrInput['C_POSITION_GROUP_CODE']))),
								'C_NAME'						=>trim($ojbSysLib->_replaceBadChar($objFilter->filter($arrInput['C_POSITION_GROUP_NAME']))),
								'C_ORDER'						=>intval($objFilter->filter($arrInput['C_ORDER'])),
								'C_STATUS'						=>$sSetStatus
						);
		$arrResult = "";
		//Goi lenh update
		if ($objFilter->filter($arrInput['C_POSITION_GROUP_CODE']) != ""){		
			//Update
			$arrResult = $objPositionGroup->UserPositionGroupUpdate($arrParameter);							
			// Neu add khong thanh cong	
			if($arrResult['RET_ERROR'] != null || $arrResult['RET_ERROR'] != '' ){											
				echo "<script type='text/javascript'>";
				echo "alert('" . $arrResult['RET_ERROR'] . "');\n";			
				echo "</script>";
			}else {	
					//Tro ve trang index					
					$this->_redirect('user/positiongroup/index/');					
				}
		}
	}
	/**
	 * Idea: Xoa mot danh sach nhom chuc vu
	 * Nguoi tao: TuyenNH
	 * Ngay tao: 16/06/2011
	 * Enter description here ...
	 */
	public function deleteAction(){
		//Goi cac doi tuong
		$objPositionGroup		= new user_modPositionGroup();// Tạo thể hiện của lớp mod
		$sFullTextSearch		=$this->_request->getParam('hdn_fulltextsearch',"");
		$this->view->txtFullTextSearch=$sFullTextSearch;
		//Luu giá trị tìm kiếm vào biến session để sau khi thực hiện xong một trong tác thao tác thêm/sửa/xóa thì tại màn hình danh sách request lại được đúng giá trị
		$arrParaSet = array("hdn_fulltextsearch"=>$sFullTextSearch);
		@session_start();
		$_SESSION['seArrParameter'] = $arrParaSet;
		$this->_request->setParams($arrParaSet);
		
		$sPositionGroupIdList = $this->_request->getParam(hdn_object_id_list,'');// Lay gia tri bien can xoa tu V(V->C)
		$sRetError= $objPositionGroup-> UserPositionGroupDelete($sPositionGroupIdList);//Goi function delete trong mod bang bien $sRetError(thong bao loi)
		if($sRetError != null || $sRetError != '' ){
			echo "<script type='text/javascript'> alert('Xoá nhóm chức vụ thành công')</script>";
		}
		else 
			$this->_helper->redirector->gotoUrl('/user/positiongroup/index/');
	}
}?>

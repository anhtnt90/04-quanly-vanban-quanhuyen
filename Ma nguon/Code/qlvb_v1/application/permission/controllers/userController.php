<?php

class permission_userController extends  Zend_Controller_Action {
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
	
		//Goi lop modHandle
		//Zend_Loader::loadClass('Handle_modHandle');
		//Lay cac hang so su dung trong JS public
		Zend_Loader::loadClass('Sys_Init_Config');
		//Zend_Loader::loadClass('Sys_Init_Session');
		$objConfig = new Sys_Init_Config();
		$this->view->UrlAjax = $objConfig->_setUrlAjax();	
		$this->view->JSPublicConst = $objConfig->_setJavaScriptPublicVariable();		
		
		// Load tat ca cac file Js va Css
		$this->view->LoadAllFileJsCss = Sys_Publib_Library::_getAllFileJavaScriptCss('','js','jsUser.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','ajax.js',',','js') . Sys_Publib_Library::_getAllFileJavaScriptCss('','js','jquery-1.4.2.min.js,jQuery.equalHeights.js',',','js'). Sys_Publib_Library::_getAllFileJavaScriptCss('','js/LibSearch','actb_search.js,common_search.js',',','js');
		// Ham lay thong tin nguoi dang nhap hien thi tai Lefmenu
		//$this->view->InforStaff = Sys_Publib_Library::_InforStaff();
		
		//Dinh nghia Package "QUYEN"
		$this->view->currentModulCode = "LIST";				
		//Modul chuc nang				
		$this->view->currentModulCodeForLeft = "LIST-USER";										
		//Hien thi file template
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
	
	/**	 
	 * Idea : Hien thin danh sach NSD cho ban quyen
	 *
	 */
	public function indexAction(){	

		// Tieu de man hinh danh sach
		$this->view->bodyTitle = "DANH SÁCH NGƯỜI SỬ DỤNG";
		//
		$arrInput = $this->_request->getParams();
		// Tao doi tuong Zend_Filter
		$objFilter = new Zend_Filter();
		//Thu vien dung chung
		$ojbSysLib = new Sys_Library();
		// Lay toan bo tham so truyen tu form			
		$arrInput = $this->_request->getParams();				
		//Goi lop modHandle
		Zend_Loader::loadClass('permission_modUserPermission');
		$objPermission = new permission_modUserPermission();
		$arrUserPermission = $objPermission->PermissionGroupGetAll('DM_NHOM_QUYEN','quyen_thuocnhom');								
		$this->view->arrUserPermission = $arrUserPermission;
		//var_dump($arrUserPermission);
		//$arrUserPermissionName = $objPermission->PermissionGroupStaffGetName('');		
		//var_dump($arrUserPermission);						
		$piCurrentPage = $this->_request->getParam('hdn_current_page',0);		
		if ($piCurrentPage <= 1){
			$piCurrentPage = 1;
		}
		$this->view->currentPage = $piCurrentPage; //Gan gia tri vao View
		// Goi ham search de hien thi ra Complete Textbox
		//var_dump($_SESSION['SesGetAllOwner']);
		$this->view->search_textselectbox_unit_name = Sys_Function_DocFunctions::doc_search_ajax($_SESSION['SesGetAllOwner'],"id","name","C_OWNER","hdn_owner",1);
		//Lay thong tin quy dinh so row / page
		$piNumRowOnPage = $objFilter->filter($arrInput['cbo_nuber_record_page']);		
		if ($piNumRowOnPage <= $this->view->NumberRowOnPage){
			$piNumRowOnPage = $this->view->NumberRowOnPage;
		}			
		$sOwnerName 	= $this->_request->getParam('C_OWNER','');
		$sUnitId		= $this->_request->getParam('C_UNIT','');

		if(isset($_SESSION['seArrParameter'])){
			$Parameter 			= $_SESSION['seArrParameter'];
			$sUnitId			= $Parameter['idphongban'];
			$sOwnerName			= $Parameter['tendonvi'];
			unset($_SESSION['seArrParameter']);
		}
		$sOwnerId 		= Sys_Function_DocFunctions::convertUnitNameListToUnitIdList($sOwnerName);
		
		//Mang luu danh sach can bo tim duoc
		$arrAllStaff = array();
		//Kiem tra neu khong nhap phong ban thi lay tat ca cac can bo cua don vi duoc chon
		//-> Lay danh sach phong ban cua don vi duoc chon
		if ($sUnitId == '' || is_null($sUnitId)) { 
			$arrUnit = array();
			//var_dump($_SESSION['arr_all_unit_keep']);
			//echo $sOwnerId.'<br>'; 
			//var_dump(arr_all_unit_keep);
			foreach($_SESSION['arr_all_unit_keep'] as $objUnit){
				//echo $objUnit['parent_id'].'<br>';
				if($objUnit['parent_id'] == $sOwnerId){
					array_push($arrUnit,$objUnit['id']);
				}
			}
			//var_dump($arrUnit);
			//Truong hop don vi co phong ban
			if (sizeof($arrUnit)) {
				//->Lay danh sach can bo nam trong cac don vi tim duoc
				foreach($_SESSION['arr_all_staff_keep'] as $objStaff){
					if(in_array($objStaff['unit_id'],$arrUnit)){
						$arrAllStaff1 = array("id"=>$objStaff['id'],"name"=>$objStaff['name'],"position_code"=>$objStaff['position_code'],"position_name"=>$objStaff['position_name'],"unit_id"=>$objStaff['unit_id']);
						array_push($arrAllStaff,$arrAllStaff1);
					}
				}
			//Truong hop don vi khong co phong ban
			}else {
				if(!is_null($sOwnerId) && $sOwnerId != "")
					//->Lay danh sach can bo nam trong cac don vi tim duoc
					foreach($_SESSION['arr_all_staff_keep'] as $objStaff){
						if($objStaff['unit_id'] == $sOwnerId){
							$arrAllStaff1 = array("id"=>$objStaff['id'],"name"=>$objStaff['name'],"position_code"=>$objStaff['position_code'],"position_name"=>$objStaff['position_name'],"unit_id"=>$objStaff['unit_id']);
							array_push($arrAllStaff,$arrAllStaff1);
						}
					}
			}
			
		}else{
			foreach($_SESSION['arr_all_staff_keep'] as $objStaff){
				if($objStaff['unit_id'] == $sUnitId){
					$arrAllStaff1 = array("id"=>$objStaff['id'],"name"=>$objStaff['name'],"position_code"=>$objStaff['position_code'],"position_name"=>$objStaff['position_name'],"unit_id"=>$objStaff['unit_id']);
					array_push($arrAllStaff,$arrAllStaff1);
				}
			}
		}
		//Day tieu chi tim kiem ra view
		$this->view->sOwnerName = $sOwnerName;
		$this->view->sUnitId 	= $sUnitId;
		$this->view->arrAllStaff = $arrAllStaff;
		$arrUnit = array();
		if(!is_null($sOwnerId) && $sOwnerId != "")
			foreach($_SESSION['arr_all_unit_keep'] as $objUnit){
				if($objUnit['parent_id'] == $sOwnerId){
					$arr1Unit = array("id"=>$objUnit['id'],"name"=>$objUnit['name'],"code"=>$objUnit['code'],"address"=>$objUnit['address'],"email"=>$objUnit['email'],"order"=>$objUnit['order']);
					array_push($arrUnit,$arr1Unit);
				}
			}
		$this->view->arrUnit = $arrUnit;		
		//echo $arrAllStaff[0]['id'];		
	}
	/**
	 * Idea: Thuc hien phuong thuc Action cap nhat quyen NSD
	 */
	public function addAction(){
		// Tieu de cua Form cap  nhat
		$this->view->bodyTitle = 'CẬP NHẬT QUYỀN NGƯỜI SỬ DỤNG';
		
		//Tao doi tuong Sys_lib
		$ojbSysLib = new Sys_Library();
		
		//Goi lop modHandle
		Zend_Loader::loadClass('permission_modUserPermission');
				
		// Thuc hien lay du lieu tu form 		
		if($this->_request->isPost()){				
			// Lay toan bo tham so truyen tu form			
			$arrInput = $this->_request->getParams();
			
			$objPermission = new permission_modUserPermission();
			$arrUserPermission = $objPermission->PermissionGroupGetAll('DM_NHOM_QUYEN','quyen_thuocnhom');
			//var_dump($arrUserPermission);
			
			$psFilterXmlTagList = $this->_request->getParam('hdn_filter_xml_tag_list',"");	
			$this->view->filterXmlTagList = $psFilterXmlTagList;
			$psFilterXmlValueList = $this->_request->getParam('hdn_filter_xml_value_list',"");
			$this->view->filterXmlValueList = $psFilterXmlValueList;

			//Lay thong tin quy dinh so row / page
			$piNumRowOnPage = $this->_request->getParam('hdn_record_number_page',0);
			$this->view->numRowOnPage	= $piNumRowOnPage;

			//Lay danh sach ID can bo duoc chon
			$iStaffIdList = $this->_request->getParam('hdn_object_id_list',"");
			$this->view->iStaffIdList = $iStaffIdList;				
			$this->view->staffInformation = $this->getStaffInformation($iStaffIdList);
			//Quyen luu trong DB (Da ban quyen)
			$arrPermissionInDB = $objPermission->StaffPermissionGetAll($iStaffIdList);
			//Lay danh muc quyen chuc nang			
			$arrPermissionObject = $objPermission->objectOfListtypeGetAll('DM_QUYEN','');
			//Hien thi nhom quyen NSD
			$this->view->displayUserPermission = $this->generatePermissionGroup($arrUserPermission,$arrPermissionInDB,$arrPermissionObject);
			//echo  $this->generatePermissionGroup($arrUserPermission,$arrPermissionInDB,$arrPermissionObject); exit;
			//Lay danh sach ID quyen
			$userPermissionIdList = $this->_request->getParam('hdn_permission_id_list',"");
			//echo $userPermissionIdList;
			$this->view->userPermissionIdList = $userPermissionIdList;		
			//Mang luu tham so update in database
			$arrParameter = array(	'PK_PERMISSION'						=>'',								
									'FK_STAFF_ID_LIST'					=>$iStaffIdList,
									'FK_PERMISSION_ID_LIST'				=>$userPermissionIdList,
									'CONST_LIST_DELIMITOR'				=>"!~~!"
							);
							
			$arrResult = "";		
			if (isset($_REQUEST['btn_update']) || $this->_request->getParam('hdn_is_update',"")){		
				$arrResult = $objPermission->StaffPermissionUpdate($arrParameter);							
				// Neu add khong thanh cong			
				if($arrResult != null || $arrResult != '' ){											
					echo "<script type='text/javascript'>";
					echo "alert('$arrResult');\n";				
					echo "</script>";
				}else{
						//Tro ve trang index						
					$this->_redirect('/permission/user/index/');						
				}
			}else {
				//Lay gia tri tim kiem tren form
				$sOwnerName 	= $this->_request->getParam('C_OWNER','');
				$sUnitId		= $this->_request->getParam('C_UNIT','');
				$arrParaSet = array("idphongban"=>$sUnitId, "tendonvi"=>$sOwnerName);
				$_SESSION['seArrParameter'] = $arrParaSet;
			}
		}
	}
	
	/**	
	 * Idea : Tao phuong thuc hien thi thong tin NSD da chon de ban quyen
	 *
	 * @param unknown_type $sStaffIdList
	 */
	private function getStaffInformation($sStaffIdList){
		//Sinh Header		
		$sStrHtml = "<table cellpadding='0' cellspacing='0' border='0' width='100%' class='list_table2' align='center'>";
			
		$delimitor = "!~~!";
		//Hien thi cac cot cua bang hien thi du lieu
		$strHeaer = Sys_Library::_generateHeaderTable("5%" . $delimitor . "40%" . $delimitor . "20%" . $delimitor . "35%"
											,"TT" . $delimitor . "Tên người sử dụng" . $delimitor . "Chức vụ" . $delimitor . "Phòng ban"
											,$delimitor);
		$StrHeader = explode("!~~!",$strHeaer);
		$sStrHtml .= $StrHeader[0];
		$sStrHtml .= $StrHeader[1];//Hien thi <col width = 'xx'><...
				
		//Kieu style
		$v_current_style_name = "round_row";	

		if ($sStaffIdList != ""){
			$arrStaff = explode($delimitor,$sStaffIdList);
			for ($index = 0;$index < sizeof($arrStaff);$index++){
				// en can bo
				$sStaffName = Sys_Library::_getItemAttrById($_SESSION['arr_all_staff_keep'],$arrStaff[$index],'name') . "&nbsp;";				
				//Chuc vu
				$sPositionName = Sys_Library::_getItemAttrById($_SESSION['arr_all_staff_keep'],$arrStaff[$index],'position_name') . "&nbsp;";							
				//----------------Lay thong tin Phong ban----------------------------
				//Id
				$sUnitId = Sys_Library::_getItemAttrById($_SESSION['arr_all_staff_keep'],$arrStaff[$index],'unit_id');
				//Name
				$sUnitName = Sys_Library::_getItemAttrById($_SESSION['arr_all_unit_keep'],$sUnitId,'name') . "&nbsp;";				
				//-------------------------------------------------------------------
				// su dung style
				if ($v_current_style_name == "odd_row"){
					$v_current_style_name = "round_row";
				}else{
					$v_current_style_name = "odd_row";					
				}				
				$sStrHtml .= "<tr class='$v_current_style_name'>";					
				$sStrHtml .= "<td align='center' class='normal_label'>" . ($index+1) . "</td>";					
				$sStrHtml .= "<td align='left' style='padding-left:3px;padding-right:3px; cursor: pointer;' class='normal_label'>" . $sStaffName . "</td>";
				$sStrHtml .= "<td align='left' style='padding-left:3px;padding-right:3px; cursor: pointer;' class='normal_label'>" . $sPositionName . "</td>";
				$sStrHtml .= "<td align='left' style='padding-left:3px;padding-right:3px; cursor: pointer;' class='normal_label'>" . $sUnitName . "</td>";
				$sStrHtml .= "</tr>";
			}
		}		
		$sStrHtml .= "</table>";
		return $sStrHtml;
	}
	
	/**	 
	 * Idea : Tao phuong thuc hien thi nhom quyen
	 *
	 * @param $arrPermission : Mang luu toan bo nhom quyen cua NSD
	 * @param $arrPermissionInDB : Mang luu thong tin quyen cua NSD da duoc ban
	 * @return unknown
	 */
	private function generatePermissionGroup($arrPermission = array(), $arrPermissionInDB = array(), $arrPermissionObject = array()){
		
		$sPermissionList = $arrPermissionInDB[0]['C_PERMISSION_LIST'];
		//echo $sPermissionList;
		$v_chk_enduser_id_onclick = "onchildclick(this)";
		//echo $sPermissionList;
		//Sinh Header		
		$sStrHtml = "<table cellpadding='0' cellspacing='0' border='0' width='100%' class='list_table2' align='center'>";
		$delimitor = "!~~!";
		//Hien thi cac cot cua bang hien thi du lieu
		$strHeaer = Sys_Library::_generateHeaderTable("5%" . $delimitor . "10%" . $delimitor . "85%"
											,"#" . $delimitor . "" . $delimitor . "Nhóm quyền"
											,$delimitor);
		$StrHeader = explode("!~~!",$strHeaer);
		$sStrHtml .= $StrHeader[0];
		//$sStrHtml .= $StrHeader[1];//Hien thi <col width = 'xx'><...

		$icount = sizeof($arrPermission);
		if ($icount >0){
			$sOldGroupName = "";
			for ($index = 0;$index<$icount; $index++){
				//Ma nhom
				$sGroupCode = $arrPermission[$index]['C_CODE'];
				//Ten nhom
				$sGroupName = $arrPermission[$index]['C_NAME'];
				
				//Nhom cu
				
				//Ma Quyen chuc nang
				$sFunctionPermission = $arrPermission[$index]['XML_TAG_IN_DB'];
				//Chuyen doi xau => Mang mot chieu
				if (trim($sFunctionPermission) != ""){
					$arrFunctionPermission = explode(",",$sFunctionPermission);
				}
				//Dia chi URL khi thuc hien onclick tren mot dong
				$sOnclick = "item_onclick('"  . $v_item_id . "')";
				//Onclick tren anh cua doi tuong
				$v_img_url_onclick = "show_enduser_on_unit(this)";
				//
				if ($v_current_style_name == "odd_row"){
					$v_current_style_name = "round_row";
				}else{
					$v_current_style_name = "odd_row";
				}
				if ($sGroupName != $sOldGroupName){					
					$sStrHtml .= "<tr style='background:#DBDEE9'>";					
					$sStrHtml .= "<td align='center'><input type='checkbox' name='chk_item_id' value='" . $sGroupCode . "' onClick=\"" . "checkElementsGroup(document.getElementsByName('chk_item_id'),this);" . "\"></td>";					
					$sStrHtml .= "<td align='center'><img unit='$sGroupCode' id='img_permission' src='" . $this->_request->getBaseUrl() . "/public/" . "images/open.gif" . "' class='normal_image' status='on' onClick=\"" . $v_img_url_onclick ."\">&nbsp;</td>";
					$sStrHtml .= "<td align='left' class='normal_label'><b>" . $sGroupName . "&nbsp;</b></td>";
					$sStrHtml .= "</tr>";
					$sOldGroupName = $sGroupName;
				}
				//Hien thi quyen chuc nang cua nhom
			
				for ($j = 0; $j<sizeof($arrFunctionPermission);$j++){
					// su dung style
					if ($v_current_style_name == "odd_row"){
						$v_current_style_name = "round_row";
					}else{
						$v_current_style_name = "odd_row";					
					}		
					//Ma quyen chuc nang
					$sCode = $arrFunctionPermission[$j];
					$sStrHtml .= "<tr unit='$sGroupCode' id='tr_permission' name='tr_permission' value='" . $sCode . "' class='" . $v_current_style_name. "'>";
					$sStrHtml .= "<td>&nbsp;&nbsp;</td>";
					$sStrHtml .= "<td align='center'>";
					$schecked = "";					
					if (Sys_Library::_listHaveElement($sPermissionList,$sCode. "!*~*!" . $sGroupCode,'!~~!')){
						$schecked = "checked";
					}
					$sStrHtml .= "<input type='checkbox' $schecked name='chk_item_id' parent='" . $sGroupCode .  "' value='" . $sCode . "!*~*!" . $sGroupCode . "' onClick=\"" . $v_chk_enduser_id_onclick . "\"></td>";
					$sStrHtml .= "<td align='left' onclick=\"" . $sOnclick . "\">";
					$sStrHtml .= "&nbsp;" . $arrPermissionObject[$sCode] . "&nbsp;" . "</td></tr>";					
				}	
			}	
		}
		if ($v_current_style_name == "odd_row"){
			$v_next_style_name = "round_row";
		}else{
			$v_next_style_name = "odd_row";
		}
		$sStrHtml .="</table>";
		return $sStrHtml;
	}
}
?>
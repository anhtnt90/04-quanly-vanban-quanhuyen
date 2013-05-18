<?php
class exit_IndexController extends  Zend_Controller_Action {
	public function init(){
		//echo "OK"; exit;
		//Load cau hinh thu muc trong file config.ini
        $tempDirApp = Zend_Registry::get('conDirApp');
		$this->_dirApp = $tempDirApp->toArray();
		$this->view->dirApp = $tempDirApp->toArray();		
		//Cau hinh cho Zend_layoutasdfsdfsd
		Zend_Layout::startMvc(array(
			    'layoutPath' => $this->_dirApp['layout'],
			    'layout' => 'index'			    
			    ));	
		
		//Lay duong dan thu muc goc (path directory root)
		$this->view->baseUrl = $this->_request->getBaseUrl() . "/public/";
	}	
	public function indexAction(){
		Zend_Loader::loadClass('Zend_Session');
		Zend_Loader::loadClass('Sys_Init_Config');
		Sys_Library::_createCookie("leftvisit",0);
		Sys_Library::_createCookie("headervisit",0);
		Zend_Session::destroy();
		$sReURL = Sys_Init_Config::_setUserLoginUrl();?>
		<script>
			window.location.href = '<?=$sReURL;?>';
		</script>
		<?php
	}
}
?>
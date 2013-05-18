<?php
/**
 * Class Xu ly thong thong tin loai danh muc
 */
class logout_indexController extends  Zend_Controller_Action {
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
		
		//Lay duong dan thu muc goc (path directory root)
		$this->view->baseUrl = $this->_request->getBaseUrl() . "/public/";

	}	
	/**
	 * Creater : Sys
	 * Date : 27/09/2009
	 * Idea : Tao phuong thuc hien xu ly logout khoi he thong
	 *
	 */
	public function indexAction(){
		Zend_Loader::loadClass('Zend_Session');		
		Zend_Session::destroy();
		$sReURL = Sys_Init_Config::_setUserLoginUrl();?>
		<script>
			window.location.href = '<?=$sReURL;?>';
		</script>
		<?php
	}
}
?>
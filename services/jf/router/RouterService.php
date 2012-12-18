<?php
namespace services\jf\router;

/**
 * @author Terry
 */
class RouterService extends \lib\jf\core\BaseService implements \lib\jf\app\IResourceManager {
	/**
	 * @var \lib\jf\Context
	 */
	private $context;
	
	private $keyapp;
	
	private $requestedApp;
	
	protected function __init() {
		$this->context = \lib\jf\Context::getContext();
		$this->keyapp = $this->cfg->getValue('keyapp');
		$this->requestedApp = isset($_REQUEST[$this->keyapp]) ? $_REQUEST[$this->keyapp] : $this->context->getConfiguration()->getValue('routerSettings/default_app');
		
		$this->__route();
	}
	
	function getRequestedApp() {
		return $this->requestedApp;
	}
	
	function setRequestedApp($appName) {
		$this->requestedApp = $appName;
	}

	protected function __route() {
		$ep = PATH_APP."/{$this->getRequestedApp()}/index.php";
		if(!file_exists($ep)) {
			require PATH_APP."/error/404.php";
		} else {
			require $ep;
		}
	}
	
	//TODO: Implements functions of IAppContext
	function getStaticResourceURL($resName) {
		
	}

	function getRelativeResourceURL($resName) {
		
	}

	public function createURL($query = null, $keep_get = array()) {
		
	}
}

?>

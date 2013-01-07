<?php
namespace services\jf\router;

/**
 * @author Terry
 */
class RouterService extends \lib\jf\core\BaseService implements \lib\jf\app\IAppContext {
	/**
	 * @var \lib\jf\Context
	 */
	private $context;
	
	private $keyapp;
	
	private $requestedApp;
	
	private $rewriteModEnabled;
	/**
	 * @var \lib\jf\rewrite_mod\IRewriteModProvider
	 */
	private $rewriteModProvider;
	
	protected function __init() {
		$this->context = \lib\jf\Context::getContext();
		$this->keyapp = $this->cfg->getValue('keyapp');
		$this->requestedApp = isset($_REQUEST[$this->keyapp]) ? $_REQUEST[$this->keyapp] : $this->context->getConfiguration()->getValue('routerSettings/app_default');
		$this->rewriteModEnabled = $this->context->getConfiguration()->getValue('routerSettings/rewrite_mod');
	
		$this->__route();
	}
	
	function getRequestedApp() {
		return $this->requestedApp;
	}
	
	function setRequestedApp($appName) {
		$this->requestedApp = $appName;
	}
	
	protected function getRealAppName($appName) {
		return $this->context->getConfiguration()->getValue('routerSettings/app_aliases/'.$appName);
	}
	
	protected function getRewriteModProvider() {
		if($this->rewriteModEnabled == 1 && $this->rewriteModProvider != null) {
			$this->rewriteModProvider = createInstance($this->context->getConfiguration()->getValue('routerSettings/rewrite_mod_provider'), 'lib\\jf\\rewrite_mod\\IRewriteModProvider');
		}
		
		return $this->rewriteModProvider;
	}

	protected function __route() {
		$isres = $this->getParameter('isres');
		if($isres == 1) {
			$ep = $this->getRelativeResourcesPath($this->requestedApp).urldecode($this->getParameter('resp'));
		} else $ep = JFX_PATH_APP."/{$this->getRealAppName($this->requestedApp)}/index.php";
		if(!file_exists($ep)) {
			require JFX_PATH_APP."/error/404.php";
		} else {
			if($isres == 1) {
				$this->getResourceContent($ep);
			} else {
				require $ep;
			}
		}
	}
	
	//TODO: Implements functions of IResourceManager
	function createStaticResourceURL($resName, $appName=null) {
		if($appName == null) $appName = $this->requestedApp;
		
		return $this->getStaticResourcesPath($appName)."$resName";
	}

	function createRelativeResourceURL($resName, $appName=null) {
		if($appName == null) $appName = $this->requestedApp;
		
		return $this->createURL(
			'', 
			array(
				$this->keyapp => $this->requestedApp,
				'isres' => 1,
				'resp'	=> $resName
			)
		);
	}
	
	private function getStaticResourcesPath($appName) {
		$path = $this->context->getConfiguration()->getValue('routerSettings/app_resource_path/static');
		
		return "$path/{$this->getRealAppName($appName)}";
	}
	
	private function getRelativeResourcesPath($appName) {
		$path = $this->context->getConfiguration()->getValue('routerSettings/app_resource_path/relative');
		
		return JFX_PATH_APP."/{$this->getRealAppName($appName)}$path";
	}
	
	private function getResourceContent($filepath) {
		header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
		header('Cache-Control: public'); // needed for i.e.
		header('Content-Type: '. mime_content_type($filepath));
		header('Content-Transfer-Encoding: Binary');
		header('Content-Length:'.filesize($filepath));
		//header('Content-Disposition: attachment; filename=file.zip');
		readfile($filepath);
		die();
	}

	/**
	 * @desc Generates URL for an application.
	 * @param string $path		uri path
	 * @param mixed $query		can be query string or array
	 * @param string $protocol	'http' | 'https' | 'ftp'
	 * @param string $host		host name
	 * @return string
	 */
	function createURL($path='', $query = null, $protocol=null, $host=null) {
		$full = false;
		
		if($protocol != null) {
			$full = true;
		} else $protocol = $_SERVER['SERVER_PROTOCOL'];
		
		if($host != null) {
			$full = true;
		} else $host = $_SERVER['HTTP_HOST'];
		
		if(is_array($query)) $query = http_build_query($query);
		
		$path .= ($query != null) ? "?$query" : "";
		
		//TODO: Before return, url could be retranslated. This might be helpful
		// for SEO.
		if($this->rewriteModProvider != null) {
			$path = $this->rewriteModProvider->rewrite($path);
		}
		return ($full === true) ? "$protocol://$host$path" : $path;
	}
	
	//TODO: Implements functions of IAppContext
	/**
	 * @desc Returns value of parameter that is in application context.
	 * @param string $name
	 * @param mixed $default	this value will be returned when name key is not found
	 * @return mixed
	 */
	function getParameter($name, $default=null) {
		return isset($_REQUEST[$name]) ? $_REQUEST[$name] : $default;
	}

	/**
	 * Gets public parameters.
	 * @return array
	 */
	function getParameters() {
		$params = array();

		foreach($_REQUEST as $key => $val) {
			$params[$key] = $val;
		}

		return $params;
	}

	/**
	 * @desc Sets value for a parameter. If value is NULL, removes parameter.
	 * @param string $name		parameter
	 * @param mixed $value		if value is NULL, removes parameter
	 */
	function setParameter($name, $value=null) {
		$_REQUEST[$name] = $value;
	}

	/**
	 * @desc Generates URL for an application.
	 * @param string $app		app name. If is NULL, returns URL of current requested application
	 * @param mixed $query		can be query string or array
	 * @param array $keep_get	parameters in request string to keep
	 * @return string
	 */
//	function createURL($app = null, $query = null, $keep_get=array()) {
//		if (is_null($app)) $app = $this->appName;
//		
//		$params = array();
//		
//		if($app != '') $params[$this->keyapp] = $app;
//		
//		if(!is_array($query)) parse_str($query, $params);
//		else $params = array_merge($params, $query);
//		
//		return $this->rm->createURL($params, $keep_get);
//	}

	/**
	 * Returns TRUE if this is ajax request.
	 * @return bool
	 */
	function isAjaxRequest() {
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) || isset($_REQUEST['force_ajax']);
	}
	
	/**
	 * @param string $name session data key name
	 * @param string $scope 'app' | NULL
	 */
	function getSession($name, $scope = 'app') {
		$key = ($scope == 'app') ? "app:{$this->getRealAppName($this->requestedApp)}:$name" : $name;
		return isset($_SESSION[$key]) ? $_SESSION[$key] : NULL;
	}

	function setSession($name, $value, $scope = 'app') {
		$key = ($scope == 'app') ? "app:{$this->getRealAppName($this->requestedApp)}:$name" : $name;
		$_SESSION[$key] = $value;
	}
}

?>

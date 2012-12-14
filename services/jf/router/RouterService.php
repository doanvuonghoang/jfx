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

		$exclude = array($this->keyApp, $this->keyPage);

		foreach($_REQUEST as $key => $val) {
			if(!in_array($key, $exclude)) {
				$params[$key] = $val;
			}
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
	function createURL($app = null, $query = null, $keep_get=array()) {
		if (is_null($app)) $app = $this->getRequestedApp();
		$q = ($app == '') ? '' : "{$this->keyapp}=$app";
		
		$params = array();
		if (!is_array($query)) {
			parse_str($query, $params);
		}else $params = $query;

		foreach($keep_get as $p) {
			$params[$p] = $_REQUEST[$p];
		}
		$params = http_build_query($params);

		$q .= ($params == '') ? '' : ( (($q == '') ? '' : '&').$params );
		$q = ($q == '') ? '/' : "?$q";
		return $q;
	}

	/**
	 * Returns TRUE if this is ajax request.
	 * @return bool
	 */
	function isAjaxRequest() {
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) || isset($_REQUEST['force_ajax']);
	}
	
	function getStaticResource($resName) {
		
	}

	function getRelativeResource($resName) {
		
	}
	
	/**
	 * @param string $name session data key name
	 * @param string $scope 'app' | NULL
	 */
	function getSession($name, $scope = 'app') {
		$key = ($scope == 'app') ? "app:{$this->getRequestedApp()}:$name" : $name;
		return isset($_SESSION[$key]) ? $_SESSION[$key] : NULL;
	}

	function setSession($name, $value, $scope = 'app') {
		$key = ($scope == 'app') ? "app:{$this->getRequestedApp()}:$name" : $name;
		$_SESSION[$key] = $value;
	}
	
}

?>

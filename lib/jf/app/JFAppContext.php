<?php
namespace lib\jf\app;

/**
 * @author Hoàng
 */
class JFAppContext implements IAppContext {
	
	private $rm;
	
	private $appName;
	
	function __construct($appName, IResourceManager $rm) {
		$this->rm = $rm;

		$this->appName = $appName;
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
	function createURL($app = null, $query = null, $keep_get=array()) {
		if (is_null($app)) $app = $this->appName;
		
		$params = array();
		
		if($app != '') $params[$this->keyapp] = $app;
		
		if(!is_array($query)) parse_str($query, $params);
		else $params = array_merge($params, $query);
		
		return $this->rm->createURL($params, $keep_get);
	}

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
		$key = ($scope == 'app') ? "app:{$this->appName}:$name" : $name;
		return isset($_SESSION[$key]) ? $_SESSION[$key] : NULL;
	}

	function setSession($name, $value, $scope = 'app') {
		$key = ($scope == 'app') ? "app:{$this->appName}:$name" : $name;
		$_SESSION[$key] = $value;
	}

	function getAppName() {
		return $this->appName;
	}

}

?>

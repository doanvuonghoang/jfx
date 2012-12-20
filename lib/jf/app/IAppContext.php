<?php
namespace lib\jf\app;

/**
 *
 * @author Hoàng
 */
interface IAppContext extends IResourceManager {
	/**
	 * @desc Returns value of parameter that is in application context.
	 * @param string $name
	 * @param mixed $default	this value will be returned when name key is not found
	 * @return mixed
	 */
	function getParameter($name, $default=null);

	/**
	 * Gets public parameters.
	 * @return array
	 */
	function getParameters();

	/**
	 * @desc Sets value for a parameter. If value is NULL, removes parameter.
	 * @param string $name		parameter
	 * @param mixed $value		if value is NULL, removes parameter
	 */
	function setParameter($name, $value=null);

	/**
	 * Returns TRUE if this is ajax request.
	 * @return bool
	 */
	function isAjaxRequest();
	
	/**
	 * @param string $name session data key name
	 * @param string $scope 'app' | NULL
	 */
	function getSession($name, $scope = 'app');

	function setSession($name, $value, $scope = 'app');
}

?>

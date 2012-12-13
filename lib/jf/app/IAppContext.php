<?php
namespace lib\jf\app;

/**
 *
 * @author Hoàng
 */
interface IAppContext {
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
	 * @desc Generates URL for an application.
	 * @param string $app		app name. If is NULL, returns URL of current requested application
	 * @param mixed $query		can be query string or array
	 * @param array $keep_get	parameters in request string to keep
	 * @return string
	 */
	function createURL($app = null, $query = null, $keep_get=array());

	/**
	 * Returns TRUE if this is ajax request.
	 * @return bool
	 */
	function isAjaxRequest();
}

?>

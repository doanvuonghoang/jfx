<?php
/**
 * @desc Requires source code in file.
 * If $file is not set, this does nothing. Otherwise, requires the $file.
 * @param string $className		class name
 */
function import($className) {
	if($className == NULL) return;
	require_once PATH_BASE.DIRECTORY_SEPARATOR.str_replace('\\', DIRECTORY_SEPARATOR, $className).'.php';
}

/**
 * @desc Creates a new instance of class by name and path.
 * @param string $className			class name
 * @param string $checkClass		class to check instance of
 * @return stdclass	if check class is not NULL and new object is not instance of this checl class, function will return FALSE.
 */
function createInstance($className, $checkClass=null) {
	import($className);

	$inst = new $className();
	if(!is_null($checkClass)) {
		if(!is_a($inst, $checkClass)) return FALSE;
	}

	return $inst;
}

/**
 * @desc Creates a new instance of class from an array.
 * @param array $array				array
 * @param string $checkClass		class name to check instance of
 * @return stdclass	if check class is not NULL and new object is not instance of this checl class, function will return FALSE.
 */
function createInstanceFromArray($array, $checkClass=null) {
	if(!isset($array['class_name'])) throw new Exception('invalid class name');
	return createInstance($array['class_name'], $checkClass);
}

function getNamespaceName($object) {
	$rc = new \ReflectionClass(get_class($object));
	
	return $rc->getNamespaceName();
}

/**
 * Adds an exception handler.
 * @param callable $callable		a callable object to handle system exception
 */
function addExceptionHanlder($callable) {
	if(!is_callable($callable)) return;

	$handlers = &getExceptionHandlers();
	if(!in_array($callable, $handlers)) $handlers[] = $callable;
}

/**
 * Gets array of exception handlers.
 *
 * @return array 	the handlers
 */
function &getExceptionHandlers() {
	static $handlers = array();

	return $handlers;
}

/**
 * @desc Handles every uncatch exceptions.
 * @param Exception $exc			an uncatch exception
 * @param bool $force				if true System will force handle this exception, else let exception handlers.
 */
function handleException($exc, $force=false) {
	$handlers = &getExceptionHandlers();

	if($force === true || empty($handlers)) {
		header('Status: 500', true, 500);
		echo "System has been halted by an uncaught exception!";
		if(defined('DISPLAY_ERROR') && DISPLAY_ERROR == 'ON') {
			echo '<pre>' . print_r($exc->getMessage(), true) . '</pre>';
		}
	} else {
		foreach($handlers as $eh) {
			call_user_func($eh, $exc);
		}
	}
}

/**
 * @desc Handles error in php.
 * @param int $errno				error number
 * @param string $errstr			error string
 * @param string $errfile			file raised error
 * @param int $errline				line raise error
 */
function handleError($errno, $errstr, $errfile, $errline) {
	throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}

date_default_timezone_set('Asia/Ho_Chi_Minh');
set_exception_handler('handleException');
set_error_handler('handleError', E_ALL);
spl_autoload_register('import', true, true);
?>
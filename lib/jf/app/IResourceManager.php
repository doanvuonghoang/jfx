<?php
namespace lib\jf\app;

/**
 * @author Hoàng
 */
interface IResourceManager {
	function getStaticResourceURL($resName);

	function getRelativeResourceURL($resName);
	
	/**
	 * @desc Generates URL for an application.
	 * @param mixed $query		can be query string or array
	 * @param array $keep_get	parameters in request string to keep
	 * @return string
	 */
	function createURL($query = null, $keep_get=array());
}

?>

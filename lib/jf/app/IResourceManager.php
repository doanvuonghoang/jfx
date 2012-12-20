<?php
namespace lib\jf\app;

/**
 * @author Hoàng
 */
interface IResourceManager {
	function createStaticResourceURL($resName, $appName=null);

	function createRelativeResourceURL($resName, $appName=null);
	
	/**
	 * @desc Generates URL for an application.
	 * @param string $path		uri path
	 * @param mixed $query		can be query string or array
	 * @param string $protocol	'http' | 'https' | 'ftp'
	 * @param string $host		host name
	 * @return string
	 */
	function createURL($path='', $query = null, $protocol=null, $host=null);
}

?>

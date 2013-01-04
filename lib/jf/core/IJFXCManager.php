<?php
namespace lib\jf\core;

/**
 * @author Hoàng
 */
interface IJFXCManager {
	function isExisted($cName);
	
	/**
	 * @param string $extName
	 * @return IConfiguration
	 */
	function getConfiguration($cName);
	
	function create($cName);
	
	function package($cName, $dest);

	function install($cPkgFile, $overwrite=false);

	function uninstall($cName, $deleteSource = false);
}

?>

<?php
namespace lib\jf\app;

/**
 * @author Hoàng
 */
class JFApp {
	private $cfgFile;
	
	protected $cfg;
	
	function __construct($cfgFile) {
		$this->cfgFile = $cfgFile;
		
		$this->cfg = \lib\jf\core\ArrayConfiguration::loadFromFile($this->cfgFile);
	}
	
	
}

?>

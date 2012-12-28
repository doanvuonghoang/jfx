<?php
namespace lib\jf\app;

/**
 * @author Hoàng
 */
class JFApp {
	private $cfgFile;

	/**
	 * @var \lib\jf\IConfiguration
	 */
	protected $cfg;
	
	/**
	 * @var IAppContext
	 */
	protected $ac;
	
	function __construct($cfgFile, IAppContext $ac) {
		$this->cfgFile = $cfgFile;
		$this->cfg = \lib\jf\core\ArrayConfiguration::loadFromFile($this->cfgFile);
		$this->ac = $ac;
	}
	
	function start() {
		echo 'Hello world!';
	}
}

?>

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
//		phpinfo();
//		echo "<img src='{$this->ac->createRelativeResourceURL('/images/404.jpg')}' />";
		$inst = \NumberFormatter::create('vi_VN', \NumberFormatter::SPELLOUT);
		
		echo $inst->format(11329, \NumberFormatter::TYPE_DEFAULT);
	}
}

?>

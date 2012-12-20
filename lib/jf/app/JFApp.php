<?php
namespace lib\jf\app;

/**
 * @author Hoàng
 */
class JFApp {
	const MODE_VIEW = 'VIEW';
	const MODE_EDIT = 'EDIT';
	
	private $cfgFile;

	/**
	 * @var \lib\jf\IConfiguration
	 */
	protected $cfg;
	
	/**
	 * @var IAppContext
	 */
	protected $ac;
	
	private $locale;
	
	private $theme;
	
	private $mode;
	
	private $responseType;
	
	private $bundles;
	
	function __construct($cfgFile, IAppContext $ac) {
		$this->cfgFile = $cfgFile;
		$this->cfg = \lib\jf\core\ArrayConfiguration::loadFromFile($this->cfgFile);
		$this->ac = $ac;
		
		$this->locale = $ac->getSession('locale');
		if(!$this->locale) $this->cfg->getValue ('locale');
		
		$this->theme = $ac->getSession('theme');
		if(!$this->theme) $this->cfg->getValue ('theme');
		
		$this->mode = $ac->getSession('mode');
		if(!$this->mode) $this->mode = self::MODE_VIEW;
		
		$this->responseType = $_SERVER['HTTP_ACCEPT'];
	}
	
	function start() {
		echo '<pre>' . print_r($this->responseType, true) . '</pre>';
		echo "<img src='{$this->ac->createStaticResourceURL('/imgs/404.jpg')}' />";
	}
}

?>

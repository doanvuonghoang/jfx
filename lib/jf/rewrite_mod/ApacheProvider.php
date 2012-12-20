<?php
namespace lib\jf\rewrite_mod;

/**
 * @author Hoàng
 */
class ApacheProvider implements IRewriteModProvider {
	/**
	 * @var IConfiguration
	 */
	private $urlCfg;
	private $urlCfgFileName;
	private $htAccessPath;
	private $changed;
	
	function __construct() {
		$this->htAccessPath = dirname($_SERVER['SCRIPT_FILENAME']);
		$this->urlCfgFileName = PATH_CONFIG.'/url.cnf.php';
		$this->urlCfg = \lib\jf\core\ArrayConfiguration::loadFromFile($this->urlCfgFileName);
		$this->changed = false;
	}
	
	public function rewrite($expr) {
		return $expr;
	}
	
	function applyRules() {
		
	}
	
	function addRule($rule) {
		$this->changed = true;
		
		
	}
	
	function removeRule($ruleKey) {
		$this->changed = true;
		
		unset($this->urlCfg[$ruleKey]);
	}
	
	function disableRule($ruleKey) {
		$this->changed = true;
		
		$this->urlCfg->setValue("$ruleKey/enabled", 0);
	}
	
	function enableRule($ruleKey) {
		$this->changed = true;
		
		$this->urlCfg->setValue("$ruleKey/enabled", 1);
	}
	
}

?>

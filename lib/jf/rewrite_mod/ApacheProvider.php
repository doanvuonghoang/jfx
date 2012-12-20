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
	
	function __construct() {
		$this->htAccessPath = dirname($_SERVER['SCRIPT_FILENAME']);
		$this->urlCfgFileName = PATH_CONFIG.'/url.cnf.php';
		$this->urlCfg = \lib\jf\core\ArrayConfiguration::loadFromFile($this->urlCfgFileName);
	}
	
	public function rewrite($expr) {
		return $expr;
	}
	
	function applyRules() {
		
	}
	
	function addRule($ruleKey, $rule) {
		
	}
	
	function removeRule($ruleKey) {
		
	}
	
	function disableRule($ruleKey) {
		
	}
	
	function enableRule($ruleKey) {
		
	}
	
}

?>

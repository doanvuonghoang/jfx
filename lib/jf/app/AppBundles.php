<?php
namespace lib\jf\app;

/**
 * @author Hoàng
 */
class AppBundles implements IAppBundles {
	private $cfg;
	
	private function __construct($resfile) {
		$this->cfg = \lib\jf\core\ArrayConfiguration::loadFromFile($resfile);
	}
	
	/**
	 * @param IPortlet $p
	 * @param string $locale
	 * @return PortletBundles
	 */
	static function getBundles(IPortlet $p, $locale) {
		$rc = new ReflectionClass($p);
		$resfile = dirname($rc->getFileName())."/res.$locale.php";
		
		return new AppBundles($resfile);
	}
	
	function getValue($name) {
		return $this->cfg->getValue($name);
	}
}

?>

<?php
namespace services\jf\test;

/**
 *
 * @author Terry
 */
class TestService extends \lib\jf\core\BaseService {
	
	protected function __init() {
		$c = \lib\jf\Context::getContext();
		
		if(isset($_SESSION['TEST_VALUE'])) echo $_SESSION['TEST_VALUE'];
		else $_SESSION['TEST_VALUE'] = 'HELL!';
		
		//$c->packageExtension('jf/session', JFX_PATH_BASE.'/tmp');
//		$extPkgFile = JFX_PATH_BASE.'/tmp/jf.session.zip';
//		$c->installExtension($extPkgFile, true);
//		$c->uninstallExtension('jf/session', true);
	}


}

?>

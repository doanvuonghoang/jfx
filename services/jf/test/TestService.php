<?php
namespace services\jf\test;

/**
 *
 * @author Terry
 */
class TestService extends \lib\jf\core\BaseService {
	
	protected function __init() {
		$c = \lib\jf\Context::getContext();
		
		//$c->packageExtension('jf/session', PATH_BASE.'/tmp');
//		$extPkgFile = PATH_BASE.'/tmp/jf.session.zip';
//		$c->installExtension($extPkgFile, true);
//		$c->uninstallExtension('jf/session', true);
	}


}

?>

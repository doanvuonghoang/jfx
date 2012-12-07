<?php
namespace services\jf\session;

use lib\jf\Context;

/**
 *
 * @author Terry
 */
class SessionService extends \lib\jf\core\BaseService {
	
	/**
	 *
	 * @var services\jf\session\ISessionDP
	 */
	private $sdp;

	protected function __init() {
		addExceptionHanlder(array($this, 'commitOnException'));

		session_set_save_handler(
		array($this, 'sessionOpen'),
		array($this, 'sessionClose'),
		array($this, 'sessionRead'),
		array($this, 'sessionWrite'),
		array($this, 'sessionDestroy'),
		array($this, 'sessionGc'));

		session_name($this->cfg->getValue('session_name', 'JF_SID'));
		session_save_path($this->cfg['session_data_provider']);
		
		$syscfg = Context::getContext()->getConfiguration();
		session_set_cookie_params(
				$syscfg->getValue('session/lifetime', 172800), // 2 days
				$syscfg->getValue('session/path', '/'),
				$syscfg->getValue('session/domain', '*'),
				$syscfg->getValue('session/secure', false),
				$syscfg->getValue('session/httponly', false));
		
		$this->sdp = $this->getSessionDP();

		session_start();
	}

	function sessionOpen($save_path, $session_name) {
		return true;
	}

	function sessionClose() {
		return true;
	}

	function sessionRead($sid) {
		$session = $this->sdp->getOne($sid);

		if (!$session) {
			$this->sdp->insert($sid, $_SERVER['REQUEST_URI']);

			return null;
		} else {
			$this->sdp->refreshActivedTime($sid, $_SERVER['REQUEST_URI']);
		}

		return $session['data'];
	}

	function sessionWrite($sid, $data) {
		$this->sdp->update($sid, $data);

		return true;
	}

	function sessionDestroy($sid) {
		$this->sdp->delete($sid);

		return true;
	}

	function sessionGc($maxlifetime) {
		$this->sdp->deleteByTime(time() - $maxlifetime);

		return true;
	}

	function commitOnException(\Exception $e) {
		session_write_close();
	}
	
	/**
	 * @return services\jf\session\ISessionDP
	 */
	private function getSessionDP() {
		return createInstance(session_save_path());
	}

}

?>

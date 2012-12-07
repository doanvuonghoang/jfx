<?php
namespace lib\jf\core;

use lib\jf\Context;
use lib\jf\IService;
use lib\jf\IConfiguration;


/**
 * Description of GenericService
 *
 * @author Terry
 */
abstract class BaseService implements IService {

	/**
	 *
	 * @var lib\jf\IConfiguration
	 */
	protected $cfg;

	/**
	 * Initializes service.
	 * @param lib\jf\IConfiguration $params
	 */
	function init(IConfiguration $params = null) {
		$this->cfg = $params;

		$this->__init();
		$this->__bindEvents();
	}

	/**
	 * Called after init(). Can be overwrited by subclasses to customize their initialization.
	 */
	protected abstract function __init();

	protected function __bindEvents() {
//		$es = $this->getEventService();
//
//		if($es != null) {
//			foreach($this->cfg->getValue('events', array()) as $key => $value) {
//				$es->registerHandler($value, array($this, $key));
//			}
//		}
	}

	/**
	 * @return EventService
	 */
	protected function getEventService() {
		return Context::getContext()->getService('event');
	}

	/**
	 * Fires an event.
	 * @param Event $e
	 */
	protected function fireEvent(Event $e) {
		$es = $this->getEventService();

		if($es != null) {
			$es->notify($e);
		}
	}
}

?>

<?php
namespace services\jf\event;

use lib\jf\core\BaseService;
use lib\jf\event\IEventDispatcher;
use lib\jf\event\Event;

class EventService extends BaseService implements IEventDispatcher {
	private $handlers;

	protected function __init() {
		$this->handlers = array();
	}


	/**
	 * Registers a handler for an event.
	 * @param string $eventClassName event name
	 * @param callback $callback callable handler
	 */
	function registerHandler($eventClassName, $callback) {
		$this->handlers[$eventClassName][] = $callback;
	}

	/**
	 * Unregisters a handler.
	 * @param $eventClassName
	 * @param $callback
	 */
	function unregisterHandler($eventClassName, $callback) {
		if(!isset($this->handlers[$eventClassName])) return;

		foreach($this->handlers[$eventClassName] as $key => $handler) {
			if($callback == $handler) {
				unset($this->handlers[$eventClassName][$key]);
				return;
			}
		}
	}

	/**
	 * Checks if can handle an event.
	 * @param Event $e event name
	 * @return string|FALSE
	 */
	function canHandle(Event $e) {
		$eventClassName = get_class($e);

		return isset($this->handlers[$eventClassName]) ? $eventClassName : FALSE;
	}

	/**
	 * Notifies event to handlers.
	 * @param Event $e event
	 * @return mixed the result of handle processes or FALSE if no handler for this event.
	 */
	function notify(Event $e) {
		if(($eventClassName = $this->canHandle($e)) !== FALSE) {
			foreach($this->handlers[$eventClassName] as $handler) {
				call_user_func($handler, $e);
			}
		} else return FALSE;
	}

}
<?php
namespace lib\jf\event;

interface IEventDispatcher {
	/**
	 * Registers a handler for an event.
	 * @param $eventClassName event name
	 * @param $callback callable handler
	 */
	function registerHandler($eventClassName, $callback);

	/**
	 * Unregisters a handler.
	 * @param $eventClassName
	 * @param $callback
	 */
	function unregisterHandler($eventClassName, $callback);

	/**
	 * Checks if can handle an event.
	 * @param Event $e event
	 * @return bool
	 */
	function canHandle(Event $e);

	/**
	 * Notifies event to handlers.
	 * @param Event $e event
	 * @return mixed the result of handle processes.
	 */
	function notify(Event $e);
}
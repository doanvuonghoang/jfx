<?php

namespace lib\jf\portlet;

use lib\jf\event\Event;

class PortletEvent extends Event {

	private $eventName;

	/**
	 * @param object $source
	 * @param string $eventName
	 */
	function __construct($source, $eventName) {
		parent::__construct($source);

		$this->eventName = $eventName;
	}

	function getEventName() {
		return $this->eventName;
	}

}
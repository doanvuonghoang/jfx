<?php
namespace lib\jf\event;

/**
 * Event
 *
 * @author Terry
 */
class Event {
	/**
	 * The object that raised this event.
	 * @var stdclass
	 */
	protected $source;

	/**
	 * The returned value.
	 * @var mixed
	 */
	protected $returnedValue;

	private $canceled = false;

	function __construct($source) {
		$this->source = $source;
	}

	/**
	 * Gets source.
	 * @return stdclass The object that raised this event.
	 */
	function getSource() {
		return $this->source;
	}

	/**
	 * Sets returned value of event.
	 * @param mixed $rv
	 */
	function setReturnedValue($rv) {
		$this->returnedValue = $rv;
	}

	/**
	 * Get returned value of event.
	 * @return mixed the returned value
	 */
	function getReturnedValue() {
		return $this->returnedValue;
	}

	/**
	 * Cancels this event.
	 */
	function cancelEvent() {
		$this->canceled = true;
	}

	/**
	 * @return Returns TRUE if this event was canceled, else returns FALSE
	 */
	function isCanceled() {
		return $this->canceled === true;
	}
}
?>

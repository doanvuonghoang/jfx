<?php
namespace services\jf\ipfilter;

class IPFilterException extends \Exception {
	protected $remoteIP;

	function __construct($remoteIP) {
		parent::__construct("Remote IP '$remoteIP' was not accessible to application.");

		$this->remoteIP = $remoteIP;
	}

	function getRemoteIP() {
		return $this->remoteIP;
	}
}
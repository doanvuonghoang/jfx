<?php
namespace services\jf\ipfilter;

class IPFilterException extends \Exception {
	protected $remoteIP;

	function __construct($remoteIP) {
		parent::__construct("Remote IP '$remoteIP' was blocked by IPFilter service.");

		$this->remoteIP = $remoteIP;
	}

	function getRemoteIP() {
		return $this->remoteIP;
	}
}
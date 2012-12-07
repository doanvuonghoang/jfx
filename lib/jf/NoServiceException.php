<?php
namespace lib\jf;

class NoServiceException extends \Exception {
	protected $service;
	protected $svcf;

	function __construct($service, $svcf) {
		parent::__construct("Service not found: '$service' => '$svcf'");

		$this->service = $service;
		$this->svcf = $svcf;
	}

	function getServiceName() {
		return $this->service;
	}
	
	function getServiceConfigPath() {
		return $this->svcf;
	}
}
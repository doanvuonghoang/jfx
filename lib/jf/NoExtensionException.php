<?php
namespace lib\jf;

class NoExtensionException extends \Exception {
	protected $extName;
	protected $extf;

	function __construct($extName, $extf) {
		parent::__construct("Service not found: '$extName' => '$extf'");

		$this->extName = $extName;
		$this->extf = $extf;
	}

	function getExtensionName() {
		return $this->extName;
	}
	
	function getExtensionConfigPath() {
		return $this->extf;
	}
}
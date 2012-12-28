<?php
namespace lib\jf;

class NoExtensionException extends core\BaseException {
	protected $extName;
	protected $extf;

	function __construct($extName, $extf) {
		parent::__construct("Extension not found: '$extName' => '$extf'", 'EC_NOEXTENSION');

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
<?php
namespace lib\jf\core;

class ObjectNotFoundException extends \Exception {

	protected $obj;
	
	function __construct($obj, $message='') {
		parent::__construct($message);
		
		$this->obj = $obj;
	}

	function getObject() {
		return $this->obj;
	}
}
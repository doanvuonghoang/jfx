<?php
namespace lib\jf\core;

class ObjectNotFoundException extends BaseException {

	protected $obj;
	
	function __construct($obj, $message='') {
		parent::__construct($message, 'EC_OBJECT_NOT_FOUND');
		
		$this->obj = $obj;
	}

	function getObject() {
		return $this->obj;
	}
}
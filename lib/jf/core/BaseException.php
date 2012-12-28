<?php
namespace lib\jf\core;

class BaseException extends \Exception {
	function __construct($message='', $code=0, $previous=null) {
		parent::__construct($message, intval($code), $previous);
		
		$this->code = $code;
	}
}
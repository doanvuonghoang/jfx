<?php
namespace services\jf\database;

use lib\jf\core\ObjectNotFoundException;

class DBKeyNotFoundException extends ObjectNotFoundException {
	function __construct($dbkey) {
		parent::__construct($dbkey, "DBKey '$dbkey' not found.");
	}
}
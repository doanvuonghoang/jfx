<?php
namespace lib\jf;

use lib\jf\IConfiguration;

interface IService {
	/**
	 * Initializes service.
	 * @param IConfiguration $params parameters
	 */
	function init(IConfiguration $params=null);
}
?>

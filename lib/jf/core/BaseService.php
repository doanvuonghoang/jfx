<?php
namespace lib\jf\core;

use lib\jf\IService;
use lib\jf\IConfiguration;


/**
 * Description of GenericService
 *
 * @author Terry
 */
abstract class BaseService implements IService {

	/**
	 *
	 * @var lib\jf\IConfiguration
	 */
	protected $cfg;

	/**
	 * Initializes service.
	 * @param lib\jf\IConfiguration $params
	 */
	function init(IConfiguration $params = null) {
		$this->cfg = $params;

		$this->__init();
	}

	/**
	 * Called after init(). Can be overwrited by subclasses to customize their initialization.
	 */
	protected abstract function __init();
	
	

}

?>

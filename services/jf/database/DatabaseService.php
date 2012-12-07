<?php
namespace services\jf\database;

use lib\jf\Context;
use lib\jf\core\BaseService;
use lib\jf\core\Toolkit;
use services\jf\database\DBKeyNotFoundException;

/**
 *
 * @author Terry
 */
class DatabaseService extends BaseService {

	private $connections;

	protected function __init() {
	}

	/**
	 * Gets connection associated with a dbkey.
	 * @param string $key a dbkey
	 * @return \PDO
	 */
	function getConnection($key) {
		if (isset($this->connections[$key]))
		return $this->connections[$key];

		$cfg = $this->getDBConfig($key);
		$this->connections[$key] = new \PDO($cfg['dsn'], $cfg['username'], $cfg['password'], Toolkit::array_read($cfg, 'options', null));

		return $this->connections[$key];
	}

	/**
	 * Gets database config of a dbkey.
	 * @param string $key dbkey string value
	 * @return array the config of dbkey
	 */
	function getDBConfig($key) {
		$cfg = Context::getContext()->getConfiguration()->getValue('dbconnections/' . $key);
		if (!$cfg) throw new DBKeyNotFoundException($key);

		return $cfg;
	}
	
	/**
	 * Execute sql script in a file.
	 * @param string $key		connection key
	 * @param string $sqlFile	sql file
	 */
	function executeSQLFile($key, $sqlFile) {
		$pdo = $this->getConnection($key);
		
		$script = file_get_contents($sqlFile);
		
		switch ($pdo->getAttribute(\PDO::ATTR_DRIVER_NAME)) {
			case 'mysql':
			case 'sqlite':
				$pdo->exec($script);
				break;
			default:
				throw new \Exception("unsupported driver {$pdo->getAttribute(\PDO::ATTR_DRIVER_NAME)}");
		}
	}

}

?>

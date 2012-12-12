<?php
namespace exts\jf\session;

/**
 *
 * @author Hoàng
 */
class MySQLSessionDP implements \services\jf\session\ISessionDP {
	/**
	 *
	 * @var \PDO
	 */
	private $pdo;
	
	private $table;

	function __construct() {
		$dbs = \lib\jf\Context::getContext()->getService('jf/database');
		if (is_a($dbs, 'services\jf\database\DatabaseService')) {
			$this->pdo = $dbs->getConnection('session');
		}
		else
			// @TODO: fix this
			throw new \Exception();
		
		$cfg = \lib\jf\Context::getContext()->getExtensionConfiguration('jf/session');
		
		$this->table = $cfg->getValue('params/TABLE_NAME');
	}

	function insert($sid, $activedRequest) {
		$q = $this->pdo->prepare("INSERT INTO {$this->table} (sid, last_actived_time, last_actived_request) VALUES (?, NOW(), ?)");
		$q->execute(array($sid, $activedRequest));
	}
	
	function update($sid, $data) {
		$q = $this->pdo->prepare("UPDATE {$this->table} SET data = ? WHERE sid = ?");
		$q->execute(array($data,$sid));
	}
	
	function delete($sid) {
		$q = $this->pdo->prepare("DELETE FROM {$this->table} WHERE sid = ?");
		$q->execute(array($sid));
	}
	
	function deleteByTime($period) {
		$q = $this->pdo->prepare("DELETE FROM {$this->table} WHERE last_actived_time < ?");
		$q->execute(array($period));
	}
	
	function getOne($sid) {
		$q = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE sid = ?");
		$q->execute(array($sid));
		
		return $q->fetch(\PDO::FETCH_ASSOC);
	}
	
	function refreshActivedTime($sid, $activedRequest) {
		$q = $this->pdo->prepare("
			UPDATE {$this->table} 
			SET 
				last_actived_time = NOW(),
				last_actived_request = ? 
			WHERE 
				sid = ?");
		$q->execute(array($activedRequest, $sid));
	}
}

?>

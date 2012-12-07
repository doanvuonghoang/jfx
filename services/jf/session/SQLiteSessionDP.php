<?php
namespace services\jf\session;

/**
 *
 * @author Hoàng
 */
class SQLiteSessionDP implements \services\jf\session\ISessionDP {
	/**
	 *
	 * @var \PDO
	 */
	private $pdo;

	function __construct() {
		$dbs = \lib\jf\Context::getContext()->getService('jf/database');
		if (is_a($dbs, 'services\jf\database\DatabaseService')) {
			$this->pdo = $dbs->getConnection('session');
		}
		else
			$this->pdo = new \PDO('sqlite:' . __DIR__ . '/session.sql3', NULL, NULL, array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION));
	}

	function insert($sid, $activedRequest) {
		$q = $this->pdo->prepare("INSERT INTO session (sid, last_actived_time, last_actived_request) VALUES (?, datetime('now', 'localtime'), ?)");
		$q->execute(array($sid, $activedRequest));
	}
	
	function update($sid, $data) {
		$q = $this->pdo->prepare("UPDATE session SET data = ? WHERE sid = ?");
		$q->execute(array($data,$sid));
	}
	
	function delete($sid) {
		$q = $this->pdo->prepare("DELETE FROM session WHERE sid = ?");
		$q->execute(array($sid));
	}
	
	function deleteByTime($period) {
		$q = $this->pdo->prepare("DELETE FROM session WHERE last_actived_time < ?");
		$q->execute(array($period));
	}
	
	function getOne($sid) {
		$q = $this->pdo->prepare("SELECT * FROM session WHERE sid = ?");
		$q->execute(array($sid));
		
		return $q->fetch(\PDO::FETCH_ASSOC);
	}
	
	function refreshActivedTime($sid, $activedRequest) {
		$q = $this->pdo->prepare("
			UPDATE session 
			SET 
				last_actived_time = datetime('now', 'localtime'),
				last_actived_request = ? 
			WHERE 
				sid = ?");
		$q->execute(array($activedRequest, $sid));
	}
	
}

?>

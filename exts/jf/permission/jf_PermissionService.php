<?php
import('platform.permission.exceptions.jf_AccessException');
import('platform.permission.exceptions.jf_EditException');

class jf_PermissionService extends jf_DBStorageService {

	/**
	 * @return jf_UserService
	 */
	protected function getUserService() {
		return jf_Context::getContext()->getService('platform/user');
	}

	private function getPermTable() {
		return $this->cfg->getValue('perm_table');
	}

	/**
	 * Checks current user can access.
	 * @param int $itemId
	 */
	function canAccess($itemId) {
		$uid = $this->getUserService()->getRemoteUserId();

		$q = $this->dbh->prepare("
		SELECT
			p.*
		FROM
		{$this->getPermTable()} p
		WHERE
			(p.role_id IN (SELECT ur.role_id FROM jf_user_in_role ur WHERE ur.user_id = ?) OR p.role_id IS NULL) AND
			(p.item_id = ? OR p.item_id IS NULL) AND
			(p.action_id = 'ACCESS');
		");
		$q->execute(array($uid, $itemId));

		if(!$q->rowCount()) {
			// TODO: auto login.
			// jf_Context::getContext()->getService('platform/user')->login('admin', 'admin');

			return FALSE;
		} else return TRUE;
	}

	/**
	 * Checks current user can edit.
	 * @param int $itemId
	 */
	function canEdit($itemId) {
		$uid = $this->getUserService()->getRemoteUserId();

		$q = $this->dbh->prepare("
		SELECT
			p.*
		FROM
		{$this->getPermTable()} p
		WHERE
			(p.role_id IN (SELECT ur.role_id FROM jf_user_in_role ur WHERE ur.user_id = ?) OR p.role_id IS NULL) AND
			(p.item_id = ? OR p.item_id IS NULL) AND
			(p.action_id = 'EDIT');
		");
		$q->execute(array($uid, $itemId));

		if(!$q->rowCount()) return FALSE;
		else return TRUE;
	}

	function getItems($action='EDIT', $refTable) {
		$uid = $this->getUserService()->getRemoteUserId();

		$q = $this->dbh->prepare("
		SELECT
			i.*
		FROM
		{$refTable} i INNER JOIN {$this->getPermTable()} p
				ON i.id = p.item_id OR p.item_id IS NULL
		WHERE
			(p.role_id IN (SELECT ur.role_id FROM jf_user_in_role ur WHERE ur.user_id = ?) OR p.role_id IS NULL) AND
			(p.action_id = ? OR p.action_id IS NULL)
		GROUP BY i.id;
		");
		$q->execute(array($uid, $action));

		return $q->fetchAll(PDO::FETCH_ASSOC);
	}

	function getRoles($itemId, $action='ACCESS') {
		$q = $this->dbh->prepare("
		SELECT
			r.*,
			p.item_id
		FROM
		{$this->getPermTable()} p LEFT JOIN jf_role r
				ON p.role_id = r.id
		WHERE
			(p.item_id <=> ? AND p.action_id = ?) OR
			(p.item_id IS NULL AND p.action_id = ?)
		ORDER BY r.id ASC
		");
		$q->execute(array($itemId, $action, $action));

		return $q->fetchAll(PDO::FETCH_ASSOC);
	}

	function deleteRole($itemId, $roleId, $action='ACCESS') {
		if($roleId == '') $roleId = null;

		$this->dbh->prepare("
		DELETE p.* FROM {$this->getPermTable()} p
		WHERE
			(item_id <=> ?) AND
			(role_id <=> ?) AND
			(action_id = ?)
		")->execute(array($itemId, $roleId, $action));
	}

	function deleteItem($itemId, $refTable) {
		$uid = $this->getUserService()->getRemoteUserId();

		$q = $this->dbh->prepare("
		DELETE
			i.*
		FROM
		{$this->getPermTable()} p LEFT JOIN jf_user_in_role ur
				ON p.role_id = ur.role_id OR p.role_id IS NULL
			INNER JOIN {$refTable} i
				ON p.item_id = i.id OR p.item_id IS NULL
		WHERE
			ur.user_id = ? AND
			(p.action_id = 'EDIT' OR p.action_id IS NULL) AND
			i.id = ?
		")->execute(array($uid, $itemId));
	}

	function addPermission($roleId, $itemId, $action='ACCESS') {
		$this->dbh->prepare("
		INSERT INTO {$this->getPermTable()}
			(role_id, item_id, action_id)
		VALUE
			(?, ?, ?)
		ON DUPLICATE KEY UPDATE action_id = ?
		")->execute(array($roleId, $itemId, $action, $action));
	}
}
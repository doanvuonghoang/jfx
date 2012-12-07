<?php
namespace services\jf\session;

/**
 * Description of BaseSessionDP
 *
 * @author Hoàng
 */
interface ISessionDP {
	function insert($sid, $activedRequest);
	
	function update($sid, $data);
	
	function delete($sid);
	
	function deleteByTime($lastActivedTime);
	
	function getOne($sid);
	
	function refreshActivedTime($sid, $activedRequest);
}

?>

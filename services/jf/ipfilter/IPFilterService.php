<?php
namespace services\jf\ipfilter;

use services\jf\ipfilter\IPFilterException;
use lib\jf\core\BaseService;

/**
 * IPFilterService
 *
 * @author Terry
 */
class IPFilterService extends BaseService {

	protected function __init() {
		$this->filterIP();
	}

	private function filterIP() {
		$list = $this->cfg->getValue('ipFilters');
		$remoteIP = $_SERVER['REMOTE_ADDR'];
		
		if(isset($list['block']) && 
			($remoteIP == $list['block'] ||
			(is_array($list['block']) && in_array($remoteIP, $list['block'])))) throw new FilterIPException($remoteIP);

		if(!isset($list['allow']) ||
			$list['allow'] == NULL ||
			$remoteIP == $list['allow'] ||
			(is_array($list['allow']) && in_array($remoteIP, $list['allow']))) return;

		throw new IPFilterException($remoteIP);
	}
}

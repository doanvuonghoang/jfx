<?php

$config = array(
	'class_name' => 'services\\jf\\ipfilter\\IPFilterService',
	'params' => array(
		// ipFilters is an array that contains allowed and blocked remote 
		// IP addresses accessible to JF. If ipFilters is NULL, 
		// system will allow all remote connection. ipFilters contains 2 subvalues
		// 'allow' and 'block'.
		'ipFilters' => array(
			'allow' => array('127.0.0.1', '10.2.25.1'),
			'block' => array(),
		)
	),
);
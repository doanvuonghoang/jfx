<?php

$config = array(
	'srcs'		=> array(
		'MySQLSessionDP.php',
	),
	'config'	=> array(
		'TABLE_NAME' => array(
			'default'	=> 'session',
			'type'		=> 'string',
			'desc'		=> 'name of table'
		),
	),
	'install_scripts'	=> array(
		'install.sql'
	),
	'uninstall_scripts'	=> array(
		'uninstall.sql'
	)
);
?>

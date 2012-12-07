<?php

$config = array(
	// Start up services.
	'startUpServices' => array(
		'jf/exception', 'jf/session', 'jf/ipfilter', 'jf/database', 'jf/test'
	),
	// session configs
	'session' => array(
		'domain' => '.jfx.dev'
	),
	// database connection strings
	'dbconnections' => array(
		'default' => array(
			'dsn' => 'mysql:host=localhost;port=3306;dbname=jfdb',
			'username' => 'root',
			'password' => 'db01',
			'options'  => array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION)
		),
//		'session' => array(
//			'dsn' => 'sqlite:'.PATH_CONFIG.'/session.sql3',
//			'username' => NULL,
//			'password' => NULL,
//			'options'  => array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION)
//		),
		'session' => array(
			'dsn' => 'mysql:host=localhost;port=3306;dbname=jfdb',
			'username' => 'root',
			'password' => 'db01',
			'options'  => array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION)
		),
	),
);
?>

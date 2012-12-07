<?php

$config = array(
	'class_name' => 'services\\jf\\session\\SessionService',
	'params' => array(
		'session_name'			=> 'JFSID',
		//'session_data_provider'	=> 'services\\jf\\session\\SQLiteSessionDP',
		'session_data_provider'	=> 'exts\\jf\\session\\MySQLSessionDP',
	),
);
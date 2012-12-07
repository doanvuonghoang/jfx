<?php
$type = $request->getParameter('type');
$server = $request->getParameter('server');
$port = $request->getParameter('port');
$username = $request->getParameter('username');
$password = $request->getParameter('password');
$database = $request->getParameter('database');

$rs = mysql_connect("$server:$port", $username, $password);

if(!$rs) throw new Exception(mysql_error());

if(!mysql_query("CREATE DATABASE IF NOT EXISTS $database")) throw new Exception(mysql_error());

$svc = $context->getService('database');
$svc->addDBKey('default', "$type:host=$server;port=$port;dbname=$database", $username, $password);

header("Location: {$request->createURL(null, "__p=dump_script")}");
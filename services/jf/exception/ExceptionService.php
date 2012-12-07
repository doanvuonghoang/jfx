<?php
namespace services\jf\exception;

use lib\jf\core\BaseService;

/**
 * ExceptionService
 *
 * @author Terry
 */
class ExceptionService extends BaseService {

	protected function __init() {
		addExceptionHanlder(array($this, 'printException'));
	}

	function printException(\Exception $exc) {
		header('Status: 500', true, 500);
		echo $this->formatException($exc);
	}

	function formatException(\Exception $exc) {
		$className = get_class($exc);
		$trace = nl2br($exc->getTraceAsString());
		return "
		<html>
		<head>
			<title>{$className} - Tinker</title>
		</head>
		<body>
		<table cellpadding=5 border=0 width=100%>
			<tr>
				<td colspan='2'><b>{$className}</b></td>
			</tr>
			<tr>
				<td width=10%><label>Message:</label>
				<td>{$exc->getMessage()}
			<tr>
				<td><label>Code:</label>
				<td>{$exc->getCode()}
			<tr>
				<td><label>File:</label>
				<td>{$exc->getFile()}
			<tr>
				<td><label>Line:</label>
				<td>{$exc->getLine()}
			<tr>
				<td colspan='2'><label style='font-weight:bold'>Stack trace:</label></td>
			<tr>
				<td colspan='2'><code>{$trace}</code></td>
		</table>
		<body>
		</html>
		";
	}
}

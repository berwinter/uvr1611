<?php
/**
 * Error Handling for the Application
 *
 * @copyright  Copyright (c) Bertram Winter bertram.winter@gmail.com
 * @license    GPLv3 License
 */
include_once("lib/config.inc.php");

function sendAjaxError($e){
	header_remove();
	header("HTTP/1.1 500 Internal Server Error");
	header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Content-type: application/json; charset=utf-8');
	$config = Config::getInstance();
	$error = array();
	if($config->app->debug) {
		$error["stacktrace"] = $e->getTraceAsString();
	}
	$error["message"] = $e->getMessage();
	echo json_encode($error);
	exit();
}

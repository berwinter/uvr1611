<?php 
/**
 * Basic functions for app configuration
 *
 * @copyright  Copyright (c) Bertram Winter bertram.winter@gmail.com
 * @license    GPLv3 License
 */

include_once("lib/config.inc.php");
include_once("lib/error.inc.php");

try {
	header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Content-type: application/json; charset=utf-8');

	$config = Config::getInstance();
	$result = array();

	$result["email"] = $config->app->email;
	$result["name"] = $config->app->name;
	$result["address"] = $config->uvr1611->address;
	$result["http_port"] = $config->uvr1611->http_port;
	$result["blnet-login"] = $config->uvr1611->blnet_login ? "true" : "false";
	$result["version"] = file_get_contents("VERSION");
	echo json_encode($result);
}
catch(Exception $e) {
	sendAjaxError($e);
}


<?php
/**
 * Basic functions for user
 *
 * @copyright  Copyright (c) Bertram Winter bertram.winter@gmail.com
 * @license    GPLv3 License
 */
include_once("lib/login/session.inc.php");
include_once("lib/error.inc.php");

try {
	// set json header
	header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Content-type: application/json; charset=utf-8');
	logout();
	echo "{\"status\": \"successful\"}";
}
catch(Exception $e) {
	sendAjaxError($e);
}

<?php
include_once("lib/backend/database.inc.php");
include_once("lib/login/session.inc.php");
include_once("lib/error.inc.php");

try {
	header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Content-type: application/json; charset=utf-8');
	
	$database = Database::getInstance();
	
	if(isset($_POST["chartid"], $_POST["names"]) && login_check()) {
		$database->editChart($_POST["chartid"], $_POST["names"]);
		echo "{\"status\": \"successful\"}";
	}
	else {
		echo "{\"status\": \"error\"}";
	}
}
catch(Exception $e) {
	sendAjaxError($e);
}

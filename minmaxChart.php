<?php
include_once 'lib/commonChart.inc.php';
include_once("lib/error.inc.php");

try {
	// get frame
	$frame = "frame1";
	if(isset($_GET["frame"])) {
		$frame = $_GET["frame"];
	}
	// get type
	$type = "analog1";
	if(isset($_GET["type"])) {
		$type = $_GET["type"];
	}
	include_once("lib/login/session.inc.php");
	if($database->isProtected($chartId) == false || login_check()) {
	    echo preg_replace('/"(-?\d+\.?\d*)"/', '$1', json_encode($database->queryMinmax($date,$frame,$type)));
	}
	else {
		echo "{status: \"access denied\"}";
	}
}
catch(Exception $e) {
	sendAjaxError($e);
}

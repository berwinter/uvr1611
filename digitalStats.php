<?php
include_once 'lib/commonChart.inc.php';
include_once("lib/login/session.inc.php");
include_once("lib/error.inc.php");

try {
	if($database->isProtected($chartId) == false || login_check()) {
	    echo json_encode($database->queryDigitalStats($date,$chartId,$period));
	}
	else {
		echo "{status: \"access denied\"}";
	}
}
catch(Exception $e) {
	sendAjaxError($e);
}

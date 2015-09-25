<?php
include_once 'lib/commonChart.inc.php';
include_once("lib/login/session.inc.php");
include_once("lib/error.inc.php");

try {
	if($database->isProtected($chartId) == false || login_check()) {
	    echo preg_replace('/"(-?\d+\.?\d*)"/', '$1', json_encode($database->queryAnalog($date,$chartId,$period)));
	}
	else {
		echo "{status: \"access denied\"}";
	}
}
catch(Exception $e) {
	sendAjaxError($e);
}

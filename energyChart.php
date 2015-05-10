<?php
include_once 'lib/commonChart.inc.php';include_once("lib/login/session.inc.php");
if($database->isProtected($chartId) == false || login_check()) {
    echo preg_replace('/"(-?\d+\.?\d*)"/', '$1', json_encode($database->queryEnergy($date,$chartId,$grouping)));
}
else {
	echo "{status: \"access denied\"}";
}

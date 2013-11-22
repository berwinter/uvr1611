<?php
include_once ("/var/www/myUvr1611DataLogger/lib/commonChart.inc.php");

echo preg_replace('/"(-?\d+\.?\d*)"/', '$1', json_encode($database->queryAnalog($date,$chartId,$period)));



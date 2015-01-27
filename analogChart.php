<?php
include_once ("lib/commonChart.inc.php");
//include_once ("lib/commonChart.inc.php");

echo preg_replace('/"(-?\d+\.?\d*)"/', '$1', json_encode($database->queryAnalog($date,$chartId,$period)));



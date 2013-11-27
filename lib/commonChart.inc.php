<?php
/**
 * Basic access for the charts
 *
 * @copyright  Copyright (c) Bertram Winter bertram.winter@gmail.com
 * @license    GPLv3 License
 */

include_once("/var/www/myUvr1611DataLogger/lib/backend/logfile.php");

$debug = 1;//0 =off, 1=less, 2=full debug
//get instance off logger
$logfile = LogFile::getInstance();
if ($debug > 1) $logfile->writeLog("commonChart.inc.php - start!\n");

 
include_once("/var/www/myUvr1611DataLogger/lib/backend/uvr1611-connection.inc.php");
include_once("/var/www/myUvr1611DataLogger/lib/backend/database.inc.php");


date_default_timezone_set("Europe/Berlin");


// set json header
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json; charset=utf-8');

// get date for chart
$date = date("Y-m-d");
if(isset($_GET["date"])) {
	$date = date("Y-m-d", strtotime($_GET["date"]));
}

// get chart id
$chartId = 1;
if(isset($_GET["id"])) {
	$chartId = $_GET["id"];
}

// get period
$period = 0;
if(isset($_GET["period"]) && $_GET["period"] == "week") {
	$period = 6;
}

// get grouping
$grouping = "days";
if(isset($_GET["grouping"])) {
	$grouping = $_GET["grouping"];
}

// connect to database
$database = Database::getInstance();



if ($debug > 1) $logfile->writeLog("commonChart.inc.php - check date!\n");
// check if required date is today and last update is older than 10 minutes
// -> so we need to fetch new values
if($date == date("Y-m-d") && ($database->lastDataset() + Config::getInstance()->app->chartcache) < time()) {
	try {
		if ($debug > 1) $logfile->writeLog("commonChart.inc.php - date okay!\n");	
		$uvr = Uvr1611::getInstance();
		$data = Array();
		$count = $uvr->getCount();
		if ($debug > 1) $logfile->writeLog("commonChart.inc.php - date okay - 2\n");			
		$lastDatabaseValue = $database->lastDataset();
		if ($debug > 1) $logfile->writeLog("commonChart.inc.php - date okay - 3\n");					
		for($i=0; $i < $count; $i++) {
			if ($debug > 1) $logfile->writeLog("commonChart.inc.php - try fetchdata\n");					
			// fetch a set of dataframes and insert them into the database
			$value = $uvr->fetchData();
			if ($debug > 1) $logfile->writeLog("commonChart.inc.php - data fetched\n");						
			if(strtotime($value["frame1"]->date) < $lastDatabaseValue) {
				break;
			}
			$data[] = $value;
			if(count($data) == 64) {
				$database->insertData($data);
				$data = Array();
			}
		}
		$uvr->endRead();
		// insert all data into database
		$database->insertData($data);
		$database->updateTables();
		if ($debug > 0) $logfile->writeLog("commonChart.inc.php - insert ".$count." sets in Database should be done\n");
	}
	catch (Exception $e) {
		$logfile->writeLog("commonChart.inc.php - exception: ".$e->getMessage()."\n");			
		if($e->getMessage() != "Another process is accessing the bl-net!") {
			return "{'error':'".$e->getMessage()."'}";
		}
	}
} 
else {
	$logfile->writeLog("commonChart.inc.php - no entry in Database --> timegap too small\n");
}

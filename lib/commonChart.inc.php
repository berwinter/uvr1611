<?php
/**
 * Basic access for the charts
 *
 * @copyright  Copyright (c) Bertram Winter bertram.winter@gmail.com
 * @license    GPLv3 License
 */
include_once("lib/backend/logfile.php");
include_once("lib/config.inc.php");
$logfile = LogFile::getInstance();
$logfile->writeLogInfo("commonChart.inc.php - start!\n");
//get instance off logger

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

$logfile->writeLogInfo("commonChart.inc.php - check date!\n");
// check if required date is today and last update is older than 10 minutes
// -> so we need to fetch new values
if($date == date("Y-m-d") && ($database->lastDataset() + Config::getInstance()->app->chartcache) < time()) {
	try {
		$logfile->writeLogInfo("commonChart.inc.php - date okay!\n");	
		$uvr = Uvr1611::getInstance();
		$data = Array();
		$count = $uvr->getCount();
		$myCount = 0;
		$count = $uvr->getCount();
if ($count > 0) {
		$logfile->writeLogInfo("commonChart.inc.php - date okay - 2\n");			
		$lastDatabaseValue = $database->lastDataset();
		for($i=0; $i < $count; $i++) {
		$logfile->writeLogInfo("commonChart.inc.php - date okay - 3\n");					
		for($i=0; $i < $count; $i++) {
			$logfile->writeLogInfo("commonChart.inc.php - try fetchdata\n");					
			// fetch a set of dataframes and insert them into the database
			$value = $uvr->fetchData();
			$logfile->writeLogInfo("commonChart.inc.php - data fetched\n");						
			if(strtotime($value["frame1"]->date) < $lastDatabaseValue) {
				break;
			}
			$data[] = $value;
		if(count($data) == 64) {
				$database->insertData($data);
				$data = Array();
				$myCount++;
			}
		}
		$uvr->endRead();
		// insert all data into database
		$database->insertData($data);
		$database->updateTables();
		$logfile->writeLogState("commonChart.inc.php - insert ".$count." sets in Database should be done\n");
		if ($count == 4095) {
		//additional debug info
			$logfile->writeLogState("commonChart.inc.php - myCount:= ".$myCount." value of i:= ".$i."\n");
		}
	} else {
		$logfile->writeLogError("commonChart.inc.php - getCount: $count \n");

	}
	}
	catch (Exception $e) {
		echo "{'error':'".$e->getMessage()."'}";
		$logfile->writeLogError("commonChart.inc.php - exception: ".$e->getMessage()."\n");
		echo "{'error':'".$e->getMessage()."'}";
	}

} 
else {
	$logfile->writeLogState("commonChart.inc.php - no entry in Database --> timegap too small\n");
}

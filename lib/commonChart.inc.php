<?php
/**
 * Basic access for the charts
 *
 * @copyright  Copyright (c) Bertram Winter bertram.winter@gmail.com
 * @license    GPLv3 License
 */
include_once("lib/backend/uvr1611.inc.php");
include_once("lib/backend/database.inc.php");
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
else if(isset($_GET["period"]) && $_GET["period"] == "year") {
	$period = 364;
}

// get grouping
$grouping = "days";
if(isset($_GET["grouping"])) {
	$grouping = $_GET["grouping"];
}

// connect to database
$database = Database::getInstance();

// check if required date is today and last update is older than 10 minutes
// -> so we need to fetch new values
foreach (Config::getInstance()->getLoggers() as $logger) {
	if($date == date("Y-m-d") && ($database->lastDataset($logger) + Config::getInstance()->app->chartcache) < time()) {
		$uvr = Uvr1611::getInstance($logger);
		$data = Array();
		$lastDatabaseValue = $database->lastDataset($logger);
		try {
			$count = $uvr->startRead();
			for($i=0; $i < $count; $i++) {
				// fetch a set of dataframes and insert them into the database
				$value = $uvr->fetchData();
				if($value !== false) {
			    	if(strtotime($value["frame1"]["date"]) < $lastDatabaseValue) {
			    		break;
			    	}
			    	$data[] = $value;
			    	if(count($data) == 64) {
					    $database->insertData($data, $logger);
					    $data = Array();
				    }
			    }
			}
			$uvr->endRead();
		}
		catch(Exception $e) {
			$uvr->endRead(false);
			throw $e;
		}
		// insert all data into database
		$database->insertData($data, $logger);
		$database->updateTables();
	}
}

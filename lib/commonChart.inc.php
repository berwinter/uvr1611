<?php
/**
 * Basic access for the charts
 *
 * @copyright  Copyright (c) Bertram Winter bertram.winter@gmail.com
 * @license    GPLv3 License
 */
include_once("lib/backend/uvr1611-connection.inc.php");
include_once("lib/backend/database.inc.php");

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

// connect to database
$database = Database::getInstance();

// check if required date is today and last update is older than 10 minutes
// -> so we need to fetch new values
if($date == date("Y-m-d") && ($database->lastDataset() + 600) < time()) {
	$uvr = Uvr1611::getInstance();
	while($uvr->getCount()) {
		// fetch a set of dataframes and insert them into the database
		$data = $uvr->fetchData();
		while ($frame = current($data)) {
			$database->insterDataset($frame, key($data));
			next($data);
		}
	}
}
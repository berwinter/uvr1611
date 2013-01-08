<?php
include_once("lib/backend/uvr1611-connection.inc.php");
include_once("lib/backend/database.inc.php");

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json; charset=utf-8');

$date = date("Y-m-d");
$chartId = 2;

if(isset($_GET["date"]))
{
	$date = date("Y-m-d", strtotime($_GET["date"]));
}

if(isset($_GET["id"]))
{
	$chartId = $_GET["id"];
}


$database = Database::getInstance();

if($date == date("Y-m-d"))
{
	if(($database->lastDataset() + 600) < time())
	{
		$uvr = Uvr1611::getInstance();
		while($uvr->getCount())
		{
			$data = $uvr->fetchData();
			$database->insterDataset($data, "frame1");
		}
	}
}
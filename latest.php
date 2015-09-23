<?php
include_once("lib/backend/uvr1611.inc.php");

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json; charset=utf-8');



if(isset($_GET["date"]) && $_GET["date"] < time()) {
	$date = $_GET["date"];
	// connect to database
	$database = Database::getInstance();
	echo preg_replace('/"(-?\d+\.?\d*)"/', '$1', json_encode($database->queryLatest($date)));
}
else 
{
	$data = load_cache("uvr1611_latest", Config::getInstance()->app->latestcache);

	if(!$data)
	{
		$uvr = Uvr1611::getInstance();
		$data = json_encode($uvr->getLatest());
		save_cache($data,"uvr1611_latest");
	}

	echo $data;
}

function save_cache($data, $key) {
	$key = md5($key);
	$data = serialize($data);
	file_put_contents('/tmp/'.$key, $data);
}

function load_cache($key, $expire) {
	$key = md5($key);
	$path = '/tmp/'.$key;
	if(file_exists($path))
	{
		if(time() < (filemtime($path) + $expire)) {
			return unserialize(file_get_contents($path));
		}
		unlink($path);
	}
	return false;
}

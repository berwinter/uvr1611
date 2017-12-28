<?php
include_once("lib/backend/uvr1611.inc.php");
include_once("lib/error.inc.php");
include_once("lib/config.inc.php");

try {
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
			$loggers = Config::getInstance()->getLoggers();
			$latest = array(
				"info" => array(
					"cached" => false,
					"loggers" => $loggers
				)
			);

			foreach ($loggers as $logger) {
				$uvr = Uvr1611::getInstance($logger);
				$latest[$logger] = $uvr->getLatest();
			}

			$data = json_encode($latest);
			save_cache($latest,"uvr1611_latest");
		}

		echo $data;
	}
}
catch(Exception $e) {
	sendAjaxError($e);
}

function save_cache($data, $key) {
	$temp = sys_get_temp_dir();
	$key = md5($key);
	$data["info"]["cached"] = true;
	$data = serialize(json_encode($data));
	file_put_contents("$temp/$key", $data);
}

function load_cache($key, $expire) {
	$temp = sys_get_temp_dir();
	$key = md5($key);
	$path = "$temp/$key";
	if(file_exists($path))
	{
		if(time() < (filemtime($path) + $expire)) {
			return unserialize(file_get_contents($path));
		}
		unlink($path);
	}
	return false;
}

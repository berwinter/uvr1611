<?php
/**
 * Uvr1611 Connection
 *
 * Provides access to the datalogger for stored datasets and latest values
 *
 * @copyright  Copyright (c) Bertram Winter bertram.winter@gmail.com
 * @license    GPLv3 License
 */

include_once("lib/error.inc.php");
include_once("lib/config.inc.php");
include_once("lib/backend/cmi-connection.inc.php");
include_once("lib/backend/web-connection.inc.php");
include_once("lib/backend/blnet-connection.inc.php");
include_once("lib/backend/database.inc.php");

class Uvr1611
{
	public static $instances;
	public static function getInstance($logger="uvr1611")
	{
		if (!isset(self::$instances[$logger])) {
			$config = Config::getInstance()->$logger;

			switch($config->logger) {
				case "cmi":
					self::$instances[$logger] = new CmiConnection($config, $logger);
					break;
				case "portal":
					self::$instances[$logger] = new WebConnection($config, $logger);
					break;
				default:
					self::$instances[$logger] = new BlnetConnection($config, $logger);
			}
		}
		return self::$instances[$logger];
	}
}

/**
 * Create a PID file
 * @throws Exception
 */
function create_pid()
{
	$temp = sys_get_temp_dir();
	$path = "$temp/uvr1611-logger.pid";
	if(file_exists($path)) {
		// if PID is older than an hour remove it
		if(time() > (filemtime($path) + 300)) {
			$pid = file_get_contents($path);
			exec("kill $pid");
		}
		else {
			throw new Exception("Another process is accessing the bl-net.");
		}

	}
	file_put_contents($path, getmypid());
}

/**
 * Remove the PID file
 */
function close_pid()
{
	$temp = sys_get_temp_dir();
	$path = "$temp/uvr1611-logger.pid";
	if(file_exists($path)) {
		unlink($path);
	}
}

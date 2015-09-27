<?php
/**
 * Uvr1611 Connection
 *
 * Provides access to the datalogger for stored datasets and latest values
 *
 * @copyright  Copyright (c) Bertram Winter bertram.winter@gmail.com
 * @license    GPLv3 License
 */
include_once("lib/config.inc.php");
include_once("lib/backend/cmi-connection.inc.php");
include_once("lib/backend/blnet-connection.inc.php");
include_once("lib/backend/database.inc.php");
error_reporting(0);

class Uvr1611
{
	public static $instance;
	public static function getInstance()
	{
		if (null == self::$instance) {
			$config = Config::getInstance()->uvr1611;
			
			switch($config->logger) {
				case "cmi": 
					self::$instance = new CmiConnection();
					break;
				default:
					self::$instance = new BlnetConnection();
			}
		}
		return self::$instance;
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
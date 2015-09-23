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
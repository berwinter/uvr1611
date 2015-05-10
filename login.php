<?php
/**
 * Basic functions for user
 *
 * @copyright  Copyright (c) Bertram Winter bertram.winter@gmail.com
 * @license    GPLv3 License
 */
include_once("lib/login/session.inc.php");

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json; charset=utf-8');

if(isset($_POST["user"], $_POST["password"]) && login($_POST["user"], $_POST["password"])) {
	echo "{\"status\": \"successful\"}";
}
else
{
	echo "{\"status\": \"password incorrect\"}";
}
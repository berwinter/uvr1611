<?php
/**
 * Web Connection
 *
 * Provides access to the datasets and latest values stored on https://cmi.ta.co.at
 *
 * @copyright  Copyright (c) Bertram Winter bertram.winter@gmail.com
 * @license    GPLv3 License
 */
include_once("config.inc.php");
include_once("uvr1611.inc.php");
include_once("cmi-parser.inc.php");
include_once("cmi-connection.inc.php");
error_reporting(0);

class WebConnection extends CmiConnection
{	
	function getData($url) {		
		$process = curl_init();
		$user = $this->config->user;
		$password = $this->config->password;
		$cmi = $this->config->cmi;
		$temp = sys_get_temp_dir();	
		curl_setopt($process, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
		curl_setopt($process, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($process, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($process, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($process, CURLOPT_CAINFO, "GeoTrustGlobalCA.crt");
		curl_setopt($process, CURLOPT_COOKIEJAR, "$temp/cmi-cookie.txt");
    	curl_setopt($process, CURLOPT_COOKIEFILE, "$temp/cmi-cookie.txt");
		$this->login($process, $user, $password);
		curl_setopt($process, CURLOPT_POST, 0);
		curl_setopt($process, CURLOPT_URL, "https://cmi.ta.co.at/webi/$cmi$url");
		$this->data = curl_exec($process);
		$this->pos = 0;
		$this->logout($process);
		curl_close($process);
	}
	
	function login($process,$user,$password) {
		$post = [
		    'username' => $user,
		    'passwort' => $password,
		    'login'   => "Einloggen",
		];
		curl_setopt($process, CURLOPT_URL, "https://cmi.ta.co.at/portal/checkLogin.inc.php?mode=ta");
		curl_setopt($process, CURLOPT_POST, 1);
		curl_setopt($process, CURLOPT_POSTFIELDS, $post);
		curl_exec($process);
	}
	
	function logout($process) {
		curl_setopt($process, CURLOPT_URL, "https://cmi.ta.co.at/portal/ta/logout/");
		curl_exec($process);
	}
}

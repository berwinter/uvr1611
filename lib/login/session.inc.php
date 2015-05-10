<?php
/**
 * Basic functions for user
 *
 * @copyright  Copyright (c) Bertram Winter bertram.winter@gmail.com
 * @license    GPLv3 License
 */
 
include_once("lib/backend/database.inc.php");

function sec_session_start() {
    $session_name = 'sec_session_id';
	
	ini_set('session.use_only_cookies', 1);
		
		
    // Gets current cookies params.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], false, true);

    session_name($session_name);

    session_start();            // Start the PHP session 
    session_regenerate_id();    // regenerated the session, delete the old one. 
}

function login($username, $password) {
	$database = Database::getInstance();
	$user = $database->getUser($username);
	if($user !== null) {
        $password = hash('sha512', $password . $user["salt"]);
        if($user["password"] == $password) {
        	$user_browser = $_SERVER['HTTP_USER_AGENT'];
            // XSS protection as we might print this value
            $user_id = preg_replace("/[^0-9]+/", "", $user["user_id"]);
            $_SESSION['user_id'] = $user_id;
			// XSS protection as we might print this value
            $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username);
		    $_SESSION['username'] = $username;
            $_SESSION['login_string'] = hash('sha512', $password . $user_browser);

            // Login successful. 
            return true;
         }
	 }
	 return false;
}

function login_check() {
    // Check if all session variables are set 
    if (isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_string'])) {
        $user_id = $_SESSION['user_id'];
        $login_string = $_SESSION['login_string'];
        $username = $_SESSION['username'];

        // Get the user-agent string of the user.
        $user_browser = $_SERVER['HTTP_USER_AGENT'];
		$database = Database::getInstance();
		$password = $database->getPassword($user_id);
		if($password !== null) {
            $login_check = hash('sha512', $password . $user_browser);
            if ($login_check == $login_string) {
            	// Logged In!!!! 
                return true;
			}
		}
	}
    return false;
}

function logout() {
	// Unset all session values 
	$_SESSION = array();

	// get session parameters 
	$params = session_get_cookie_params();

	// Delete the actual cookie. 
	setcookie(session_name(),'', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);

	// Destroy session 
	session_destroy();
}

sec_session_start();
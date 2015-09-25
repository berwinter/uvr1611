<?php
include_once("lib/backend/database.inc.php");
include_once("lib/login/session.inc.php");
include_once("lib/error.inc.php");

try {
	$database = Database::getInstance();
	if(isset($_POST["password1"], $_POST["password2"], $_POST["password3"]) && $_POST["password2"] == $_POST["password3"] && login("admin", $_POST["password1"])) {
		$database->changePassword("admin", $_POST["password2"]);
?>
	<html>
	<body>
		Passwort geaendert!
	</body>
	</html>
<?php
	}
	else
	{
?>
<html>
<body>
	<form method="post" action="changePassword.php">
		<label for="password1">Altes Passwort:</label>
		<input name="password1" type="password"/>
		<label for="password2">Neues Passwort:</label>
		<input name="password2" type="password"/>
		<label for="password3">Neues Passwort bestaetigen:</label>
		<input name="password3" type="password"/>
		<input name="submit" type="submit" value="aendern"/>
	</form>
</body>
</html>
<?php
	}
}
catch(Exception $e) {
	sendAjaxError($e);
}
?>

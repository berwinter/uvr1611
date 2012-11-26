<?php
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json; charset=utf-8');

exec('uvr1611-logger -r 10.0.0.100');
mysql_connect("localhost","uvr1611","uvr1611") or die ("Keine Verbindung moeglich");
mysql_set_charset('utf8'); 

mysql_select_db("uvr1611") or die("Konnte die Datenbank nicht waehlen.");

?>

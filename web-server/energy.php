<?php
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json; charset=utf-8');

$type = "days";

if(isset($_GET["type"]))
{
	$date = $_GET["type"];	
}

mysql_connect("localhost","uvr1611","uvr1611") or die ("Keine Verbindung moeglich");
mysql_set_charset('utf8'); 

mysql_select_db("uvr1611") or die("Konnte die Datenbank nicht waehlen.");


$data = mysql_query("SELECT DATE_FORMAT(tmp.timestamp, '%d.%m') AS time, energy1, energy2 FROM (SELECT * FROM energy ORDER BY timestamp DESC LIMIT 15) tmp ORDER BY tmp.timestamp ASC;") or die("Anfrage nicht erfolgreich");

$old = mysql_fetch_assoc($data);

print '{"cols": [{"id":"","label":"Time","pattern":"","type":"string"},';
print '{"id":"","label":"Ertrag Flachkollektor","pattern":"","type":"number"},';
print '{"id":"","label":"Ertrag Röhrenkollektor","pattern":"","type":"number"}],"rows": [';

$first = true;
while($r = mysql_fetch_assoc($data)) {
	if($first)
	{
		print '{"c":[{"v":"';
		$first = false;	
	}
	else
	{
		print ',{"c":[{"v":"';	
	}

	print $r["time"];
	print '","f":null}';
	print ',{"v":';
	print $r["energy1"] - $old["energy1"];
	print ',"f":null}';
	print ',{"v":';
	print $r["energy2"] - $old["energy2"];
	print ',"f":null}';
	print ']}';
	$old = $r;
}
	
print ']}'
?>

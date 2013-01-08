<?php
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json; charset=utf-8');

$date = date("Y-m-d");
$id = 2;

if(isset($_GET["date"]))
{
	$date = date("Y-m-d", strtotime($_GET["date"]));	
}

if(isset($_GET["id"]))
{
	$id = $_GET["id"];	
}

mysql_connect("localhost","uvr1611","uvr1611") or die ("Keine Verbindung moeglich");
mysql_set_charset('utf8'); 

mysql_select_db("uvr1611") or die("Konnte die Datenbank nicht waehlen.");



if($date == date("Y-m-d"))
{
	$query = mysql_query("SELECT MAX(timestamp) FROM data;") or die("Anfrage nicht erfolgreich");
	$last = mysql_fetch_row($query);
	if((strtotime($last[0]) + 600) < time())
	{
		exec('uvr1611-logger -r 10.0.0.100');	
	}
}


$query = mysql_query("SELECT columnId FROM chartColumns WHERE chartId=".$id.";") or die("Anfrage nicht erfolgreich");


while($r = mysql_fetch_assoc($query)) {
	$columns[] = $r["columnId"];
}

$sql = "SELECT DATE_FORMAT(timestamp, '%Y, %m, %d, %H, %i, %s') AS time, ";
$sql .= implode(", ", $columns);
$sql .= " FROM data WHERE timestamp > '".$date."' AND timestamp < DATE_ADD('".$date."',INTERVAL 1 DAY) LIMIT 1500;";

$data = mysql_query($sql) or die("Anfrage nicht erfolgreich");

$query = mysql_query("SELECT columnNames.columnId, columnName FROM columnNames INNER JOIN chartColumns ON columnNames.columnId = chartColumns.columnId WHERE chartId=
".$id.";") or die("Anfrage nicht erfolgreich");

while($r = mysql_fetch_assoc($query))
{
	$names[$r["columnId"]] = $r["columnName"];
}

print '{"cols": [{"id":"","label":"Time","pattern":"","type":"datetime"}';

foreach($columns as $c)
{
	print ',{"id":"","label":"';
	print $names[$c];
	print '","pattern":"","type":"number"}';
}
print '],"rows": [';
$first = true;
while($r = mysql_fetch_assoc($data)) {
	if($first)
	{
		print '{"c":[{"v":"Date(';
		$first = false;	
	}
	else
	{
		print ',{"c":[{"v":"Date(';	
	}

	print $r["time"];
	print ')","f":null}';
	foreach($columns as $c)
	{
		print ',{"v":';
		print $r[$c];
		print ',"f":null}';
	}
	print ']}';
	}
	
print ']}'
?>

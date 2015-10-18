<?php
error_reporting(0);
include_once("lib/config.inc.php");
$name = "Installation";
$check = array();
try {
	$config = Config::getInstance();
	$email = $config->app->email;
	$servername = $config->mysql->server;
	$username = $config->mysql->user;
	$password = $config->mysql->password;
	$database = $config->mysql->database;
	$check["config"] = true;
}
catch(Exception $e) {
	$email = "bertram.winter@gmail.com";
	$servername = "localhost";
	$username = "uvr1611";
	$password = "";
	$database = "uvr1611";
}

if(isset($_POST["action"])) {
	$servername = $_POST["servername"];
	$username = $_POST["username"];
	$password = $_POST["password"];
	$database = $_POST["database"];
}

$conn = @new mysqli($servername, $username, $password);
if (!$conn->connect_error) {
	$check["mysql"] = true;
}
$conn->set_charset("utf8");

if ($conn->select_db($database)) {
	$check["database"] = true;
}

try {
	if($check["mysql"] && isset($_POST["action"])) {
		switch($_POST["action"]) {
			case "reset":
				$message = resetPassword($conn);
				break;
			case "install":
				$message = createDatabase($conn, $database);
				$check["database"] = true;
				break;
			case "demo":
				$message = loadDemo($conn, $database);
				break;
			case "update":
				$message = updateDatabase($conn, $database);
				break;
			case "finish":
				$message = finishSetup();
				break;
		}
	}
}
catch (Exception $e) {
	$error = $e->getMessage();
}

if (!needDatabaseUpdate($conn, $database)) {
	$check["update"] = true;
}
$conn->close();

function finishSetup() {
	@unlink("install.php");
	if(file_exists("install.php")) {
		throw new Exception("Konnte 'install.php' nicht löschen. Bitte lösche 'install.php' manuell, um die Installation abzuschließen.");
	}
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: index.php");
	exit();
}

function loadDemo($conn, $database) {
	$sql = file_get_contents("sql/example-data/t_names_of_charts.sql");
	$sql .= file_get_contents("sql/example-data/t_menu.sql");
	$sql .= file_get_contents("sql/example-data/t_names.sql");
	$sql .= file_get_contents("sql/example-data/t_schema.sql");
	$result = $conn->multi_query($sql);
	if (!$result) {
	    throw new Exception("Konnte Demo Daten nicht laden: " . $conn->error);
	}
	while($conn->next_result());
	return "Demo Daten erfolgreich geladen.";
}

function createDatabase($conn, $database) {
	$database = $conn->real_escape_string($database);
	$result = $conn->query("CREATE DATABASE IF NOT EXISTS `$database`;");
	if(!$result) {
	    throw new Exception("Konnte Datenbank nicht anlegen: " . $conn->error);
	}
	$conn->select_db($database);
	$sql = file_get_contents("sql/structure.sql");
	$result = $conn->multi_query($sql);
	if (!$result) {
	    throw new Exception("Konnte Tabellen nicht erstellen: " . $conn->error);
	}
	while($conn->next_result());
	return "Datenbank '$database' erfolgreich angelegt.";
}

function needDatabaseUpdate($conn, $database) {
	$database = $conn->real_escape_string($database);
	$result = $conn->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME = 'view' AND TABLE_NAME = 't_menu' AND TABLE_SCHEMA = '$database';");	
	if(!$result || $result->num_rows == 0) {
		return true;
	}
	$result = $conn->query("SELECT * FROM INFORMATION_SCHEMA.STATISTICS WHERE INDEX_NAME = 'UNIQUE' AND TABLE_NAME = 't_schema' AND TABLE_SCHEMA = '$database';");
	if(!$result || $result->num_rows != 0) {
		return true;
	}
	$result = $conn->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME = 'type' AND TABLE_NAME = 't_menu' AND TABLE_SCHEMA = '$database' AND NOT DATA_TYPE = 'varchar';");
	if(!$result || $result->num_rows != 0) {
		return true;
	}
	$result = $conn->query("SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME IN ('t_chartoptions','t_users','t_data','t_menu','t_schema','t_names','t_names_of_charts','t_energies','t_max','t_min') AND TABLE_SCHEMA = '$database';");
	if(!$result || $result->num_rows != 10) {
		return true;
	}
	return false;
}

function updateDatabase($conn, $database) {
	$database = $conn->real_escape_string($database);
	$sql = file_get_contents("sql/structure.sql");
	$result = $conn->multi_query($sql);
	if (!$result) {
	    throw new Exception("Konnte Tabellen nicht erstellen: " . $conn->error);
	}
	while($conn->next_result());
	$result = $conn->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME = 'view' AND TABLE_NAME = 't_menu' AND TABLE_SCHEMA = '$database';");
	if(!$result || $result->num_rows == 0) {
		$result = $conn->query("ALTER TABLE `t_menu` ADD COLUMN `view` VARCHAR(50) NULL AFTER `schema` ;");
		if($result) {
			throw new Exception("Konnte Tabelle 't_menu' nicht aktualisieren: " . $conn->error);
		}
	}
	$result = $conn->query("SELECT * FROM INFORMATION_SCHEMA.STATISTICS WHERE INDEX_NAME = 'UNIQUE' AND TABLE_NAME = 't_schema' AND TABLE_SCHEMA = '$database';");
	if(!$result || $result->num_rows != 0) {
		$result = $conn->query("ALTER TABLE `t_schema` DROP INDEX `UNIQUE`;");
		if($result) {
			throw new Exception("Konnte Tabelle 't_schema' nicht aktualisieren: " . $conn->error);
		}
	}
	$result = $conn->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME = 'type' AND TABLE_NAME = 't_menu' AND TABLE_SCHEMA = '$database' AND NOT DATA_TYPE = 'varchar';");
	if(!$result || $result->num_rows != 0) {
		$result = $conn->query("ALTER TABLE `t_menu` CHANGE COLUMN `type` `type` VARCHAR(20) NOT NULL ;");
		if($result) {
			throw new Exception("Konnte Tabelle 't_menu' nicht aktualisieren: " . $conn->error);
		}
	}
	return "Alle Tabellen wurden aktualisiert.";
}

function resetPassword($conn) {
	$result = $conn->query("UPDATE `t_users` SET `password` = '40e2776165475d893e923da0fc9039569bad50e7f88e0ff07e11ad8bffd51019c7c0ab2709395c3599f4bebd6a6bd927e9c9470a638e1eef8e8cb971061d7412', `salt` = '80125411a211653c6b76a9c5b9b12b6406a4a53ab61543d31abf626cda4a58ba5c8d2411a5011ad2b61de2cecd07e02b6ec9ad7a2513299d977e34b5c3f76df0' WHERE `username` = 'admin';");
	if (!$result) {
	    throw new Exception("Konnte Passwort nicht zurücksetzen: " . $conn->error);
	} 
	return "Passwort wurde auf '1234' zurückgesetzt.";
}
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="de">
  <head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <title>UVR1611 Data Logger Pro - Setup</title>
	<link rel="stylesheet" type="text/css" href="css/format.css">
	<link rel="stylesheet" type="text/css" href="css/smoothness/jquery-ui-1.9.2.custom.min.css">
    <style>
      #logo { margin-top:-300px; }
	  #menu { height: 440px; color: #555;}
	  #output {margin-top: 10px; color: #C22;}
	  #success {margin-top: 10px;}
	  #form { width: 100%; table-layout: fixed;	border-collapse:collapse;}
	  #form tr {height: 25px;}
	  #form td.label {width:150px;}
	  #form td.field > input {width:100%}
	  #form td.buttons {text-align: center; padding-top: 15px;}
	  #form td.buttons > button {font-size:0.9em; max-width:85px; padding:4px;}
	  #status {padding:0;list-style:none;}
	  #status > li {margin-bottom:10px;}
	  .icon-error {background-image: url(css/smoothness/images/ui-icons_cd0a0a_256x240.png); }
	  .ui-icon {float:left; padding-right:4px;}
    </style>
  </head>
  <body>
	<div id="contact"><?php include("VERSION"); ?> <a href="https://github.com/berwinter/uvr1611" target="_blank">GitHub</a> <a href="mailto:<?php echo $email; ?>">Kontakt</a></div>
	</div>
		<svg id="logo" xmlns="http://www.w3.org/2000/svg"  width="280" height="60">
		  <g transform="translate(0,-992.36218)">
		    <text style="font-size:40px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#666666;fill-opacity:1;stroke:none" x="0.49456322" y="1004.2011">
		    	<tspan x="0.49456322" y="1004.2011" style="font-size:10px;font-style:italic;font-variant:normal;font-weight:bold;font-stretch:normal;fill:#666666;font-family:Arial">
		    		UVR<tspan style="font-size:12px;font-weight:normal;fill:#666666;">1611</tspan>
		    	</tspan>
		    </text>
		    <text style="font-size:28px;font-style:normal;font-weight:normal;line-height:125%;letter-spacing:0px;word-spacing:0px;fill:#666666;fill-opacity:1;stroke:none;" x="-1.1482943" y="1033.807">
		    	<tspan x="-1.1482943" y="1033.807" style="font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;fill:#666666;font-family:Arial">
		    		<tspan style="font-size:32px;fill:#666666">Data Logger</tspan>
		    		<tspan style="font-size:28px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;fill:#666666;font-family:Arial"> Pro</tspan>
		    	</tspan>
		    </text>
		    <text x="-0.13813959" y="1049.5406">
		    	<tspan x="-0.13813959" y="1049.5406" style="font-size:12px;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;fill:#666666;font-family:Arial"><?php echo $name;?></tspan>
		    </text>
		  </g>
		</svg>
	<div id="menu">
		<p><strong>Achtung!</strong> Die Verwendung dieses Skripts kann im Fehlerfall zu Datenverlust führen. Wichtige Daten sollten zuvor gesichert werden. Nach Abschluss der Installation sollte dieses Skript unbedingt gelöscht werden, um den Datenlogger vor unberechtigem Zugriff zu schützen.</p>
		<h3>Status</h3>
		<ul id="status">
			<?php 
			if($check["config"]) {
				echo '<li><span class="ui-icon ui-icon-check"></span> config/config.ini gefunden</li>';
			}
			else {
				echo '<li><span class="ui-icon icon-error ui-icon-close"></span> config/config.ini fehlerhaft</li>';
			}
			if($check["mysql"]) {
				echo '<li><span class="ui-icon ui-icon-check"></span> Datenbankverbindung erfolgreich</li>';
				if($check["database"]) {
					echo '<li><span class="ui-icon ui-icon-check"></span> Datenbank-Schema vorhanden</li>';
					if($check["update"]) {
						echo '<li><span class="ui-icon ui-icon-check"></span> Datenbank-Schema aktuell</li>';
					}
					else {
						echo '<li><span class="ui-icon icon-error ui-icon-close"></span> Datenbank-Schema muss aktualisiert werden</li>';
					}
				}
				else {
					echo '<li><span class="ui-icon icon-error ui-icon-close"></span> Datenbank-Schema nicht vorhanden</li>';
				}
			}
			else {
				echo '<li><span class="ui-icon icon-error ui-icon-close"></span> Datenbankverbindung nicht möglich</li>';
			}
			?>
		</ul>
		<h3>Datenbank Setup</h3>
		<form action="install.php" method="post">
			<table id="form">
			<tr>
				<td class="label">Server:</td><td class="field"><input class="ui-widget-content ui-corner-all" type="text" name="servername" value="<?php echo $servername;?>"/></td>
			</tr>
			<tr>
				<td class="label">Datenbank:</td><td class="field"><input class="ui-widget-content ui-corner-all" type="text" name="database" value="<?php echo $database;?>"/></td>
			</tr>
			<tr>
				<td class="label">Username:</td><td class="field"><input class="ui-widget-content ui-corner-all" type="text" name="username" value="<?php echo $username;?>"/></td>
			</tr>
			<tr>
				<td class="label">Passwort:</td><td class="field"><input class="ui-widget-content ui-corner-all" type="password" name="password" value="<?php echo $password;?>"/></td>
			</tr>
			<tr>
				<td class="buttons" colspan="2">
				<button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" type="submit" name="action" value="reset">Passwort zurücksetzen</button>
				<?php 
				if(!$check["mysql"]) {
					echo '<button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" type="submit" name="action" value="test">Verbindung testen</button>';		
				}			
				else if(!$check["database"]) {
					echo '<button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" type="submit" name="action" value="install">Datenbank anlegen</button>';
				}
				else if(!$check["update"]) {
					echo '<button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" type="submit" name="action" value="update">Datenbank updaten</button>';
				}
				else {
					echo '<button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" type="submit" name="action" value="demo">Lade Demo Daten</button>';
				}
				?>
				<button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" type="submit" name="action" value="finish">Installation abschließen</button>
				</td>
			</tr>
			</table>
		</form>
		<div id="output">
			<?php if(isset($error)) { echo $error; }?>
		</div>
		<div id="success">
			<?php if(isset($message)) { echo $message; }?>
		</div>
	</div>
	</body>
</html>
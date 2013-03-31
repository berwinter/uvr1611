UVR1611 Datalogger Pro
======

Der *UVR1611 Datalogger Pro* ist ein webbasierender Datenlogger für die Universalregelung UVR1611 von TA mithilfe des BL-NET über den CAN-Bus und DL.

Die Features sind:
* Datenlogging über CAN-Bus und DL in eine MySQL Datenbank
* Web-Interface
* Onlinegrafik
* Temperaturkurven (Tages und Wochenansicht)
* Leistungskurven (Tages und Wochenansicht)
* Ertragsdiagramme (Monats und Tagesgruppierung)
* Zoomen in den Diagrammen
* Übersichtstabellen zu den Diagrammen
* Monatliche Trendwerte

Ein Beispiel der Anwendung befindet sich hier: [Demo](http://berwinter.dyndns.org/uvr1611/)

Installation
------

Für die Anwendung wird ein Webserver mit PHP und ein MySQL Datenbank-Server benötigt. Die benötigten Pakete können unter Debian/Ubuntu mit folgendem Befehl installiert werden:

	sudo apt-get install lighttpd php5-cgi mysql-server mysql-client php5-mysql
	sudo lighttpd-enable-mod fastcgi-php
	sudo service lighttpd force-reload

Danach kann die Anwendung in den Ordner `/var/www/` kopiert werden. Zum erstellen der Datenbankstruktur kann das Skript `sql/structure.sql` importiert werden. 

Die Anwendung benütigt zur Laufzeit Schreibrechte für den Ordner `/tmp/`.


Konfiguration
------

Die Konfiguration des Datenloggers befindet sich in der Datei `config/config.ini`:
 
	[mysql]
	server = localhost
	user = uvr1611
	password = uvr1611
	database = uvr1611
	
	[uvr1611]
	address = 10.0.0.100
	port = 40000
	reset = false
	
	[app]
	chartcache = 600
	latestcache = 60
	reduction = 2

In der Sektion `mysql` befinden sich die Parameter für den Zugang zur Datenbank. Der angebene Datenbank-Benutzer benötigt die Rechte `DELETE`, `EXECUTE`, `INSERT`, `SELECT`, `SHOW VIEW` und `UPDATE` für die Datebank. In der Sektion `uvr1611` befinden sich die IP-Adresse für den BL-NET. Mit dem Schalter `reset` kann das Löschen der Daten vom BL-NET nach dem Logging aktiviert werden. Der Bereich `app` legt Einstellungen zur Anwendung fest. `chartcache` und `latestcache` legen den Zeitraum in Sekunden fest, in dem keine neuen Daten vom Bootloader geholt werden.  `reduction` reduziert die Daten in den Diagrammen um den angegebenen Faktor.

Automatisches abrufen der Daten
------
Damit die Daten automatisch vom BL-Net geholt werden, kann ein cron-Job mit folgendem Inhalt angelegt werden (z.B.: als `/etc/cron.hourly/uvr1611-logger`): 

	#!/bin/bash

	wget 'http://localhost/uvr1611/analogChart.php' -O /dev/null

Anpassen der Anwendung
------

Die Anpassung der Anwendung erfolgt vollständig über die Datenbank:
(Im Ordner `sql/example-data` befinden sich Beispieldaten der einzelnen Tabellen zum Importieren.)

#### t_menu ####

![Hauptmenü](./doc/t_menu.png)

#### t_names ####

![Senornamen](./doc/t_names.png)

#### t_names_of_charts ####

![Verknüpfung zwischen Sensoren und Diagrammen](./doc/t_names_of_charts.png)

#### t_schema ####

Die angezeigten Werte im Schema können in der Tabelle `t_schema` angepasst werden. Dazu musst der Pfad des `t_span` Elements in der SVG Grafik angebenen werden, welches den aktuellen Wert enthalten soll. Als Formatierung kann die Anzahl der Kommastellen angegenem werden (zB.: #.## für 2 Kommastellen). Für die digitalen Ausgänge kann die Funktion `DIGITAL()` verwendet werden um EIN bzw. AUS im Schema anzuzeigen. Für die Darstellung der Erträge stehen die Funktionen `MWH()` und `KWH()` zur Verfügung.

![Anzeige der aktuellen Werte im Schema](./doc/t_schema.png)

Screenshots
------

![Hauptmenü](./doc/main.png)

![Schema](./doc/schema.png)

![Temperaturdiagramme](./doc/linien.png)

![Temperaturdiagramme (Tabelle)](./doc/linien2.png)

![Ertäge](./doc/balken.png)

![Ertäge (Monate)](./doc/balken2.png)

Kontakt
------
Bertram Winter
bertram.winter@gmail.com

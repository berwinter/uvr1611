UVR1611 Datalogger Pro
======

Der *UVR1611 Datalogger Pro* ist ein webbasierender Datenlogger für die Universalregelung UVR1611 von TA mithilfe des BL-NET über den CAN-Bus und DL.

Die Features sind:
* Datenlogging über CAN-Bus und DL in eine MySQL Datenbank
* Web-Interface
* Onlinegrafik
* Temperaturkurven
* Leistungskurven
* Ertragsdiagramme

<!--Ein Beispiel der Anwendung befindet sich hier: [Demo](http://berwinter.dyndns.org/uvr1611/)-->

Installation
------

Für die Anwendung wird ein Webserver mit PHP und ein MySQL Datenbank-Server benötigt. Die benötigten Pakete können unter Ubuntu mit folgendem Befehl installiert werden:

	sudo apt-get install lighttpd php5-cgi mysql-server mysql-client

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

In der Sektion `mysql` befinden sich die Parameter für den Zugang zur Datenbank und in `uvr1611` die IP-Adresse für den BL-NET. Mit dem Schalter `reset` kann das Löschen der Daten vom BL-NET nach dem Logging aktiviert werden.

Anpassen der Anwendung
------

Die Anpassung der Anwendung erfolgt vollständig über die Datenbank:

#### t_menu ####

![Hauptmenü](./doc/t_menu.png)

#### t_names ####

![Senornamen](./doc/t_names.png)

#### t_names_of_charts ####

![Verknüpfung zwischen Sensoren und Diagrammen](./doc/t_names_of_charts.png)

#### t_schema ####

Die angezeigten Werte im Schema können in der Tabelle `t_schema` angepasst werden. Dazu musst der Pfad des `t_span` Elements in der SVG Grafik angebenen werden, welches den aktuellen Wert enthalten soll. Als Formatierung kann die Anzahl der Kommastellen angegenem werden (zB.: #.## für 2 Kommastellen). Für die digitalen Ausgänge kann die Funktion `DIGITAL()` verwendet werden um EIN bzw. AUS im Schema anzuzeigen.

![Anzeige der aktuellen Werte im Schema](./doc/t_schema.png)

Screenshots
------

![Hauptmenü](./doc/main.png)

![Schema](./doc/schema.png)

![Temperaturdiagramme](./doc/linien.png)

![Ertäge](./doc/balken.png)

Kontakt
------
Bertram Winter
bertram.winter@gmail.com

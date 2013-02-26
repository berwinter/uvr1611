<?php
/**
 * Database Connection (Singleton)
 *
 * Provides access to the database which stores the datasets and chart settings
 *
 * @copyright  Copyright (c) Bertram Winter bertram.winter@gmail.com
 * @license    GPLv3 License
 */
include_once("lib/config.inc.php");

class Database
{
	/**
	 *  Singleton Interface
	 */
	public static $instance;
	public static function getInstance()
	{
		if (null == self::$instance) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	/**
	 * Privates
	 */
	private $config;
	private $mysqli;
	
	/**
	 * Constructor
	 * Uses Config class to set up a MySQL connection
	 */
	private function __construct()
	{
		$this->config = Config::getInstance()->mysql;
		$this->mysqli = new mysqli($this->config->server,
								   $this->config->user,
								   $this->config->password,
								   $this->config->database);
		$this->mysqli->set_charset("utf8");
	}
	
	/**
	 * Inserts a Dataset from the Parser into the database
	 * @param Parser $data
	 * @param string $frame
	 */
	public function insterDataset($data, $frame)
	{
		// create dataset with date
		$id = $this->insertDate($data->date);
		
		// analog values
		for($i=1; $i<=16; $i++) {
			$name = "analog".$i;
			$this->insertAnalog($data->$name, $id, $name, $frame);
		}
		
		// digital values and speeds
		// digital1 -> speed1
		// digital2 -> speed2
		// digital6 -> speed3
		// digital7 -> speed4
		for($i=1; $i<=16; $i++) {
			$name = "digital".$i;
			$speed = NULL;
			switch($i) {
				case 1:
					$speed = $data->speed1;
					break;
				case 2:
					$speed = $data->speed2;
					break;
				case 6:
					$speed = $data->speed3;
					break;
				case 7:
					$speed = $data->speed4;
					break;
			}
			
			$this->insertDigital($data->$name, $id, $name, $frame, $speed);
		}
		
		// energy values
		for($i=1; $i<=2; $i++) {
			$name = "energy".$i;
			$this->insertEnergy($data->$name, $id, $name, $frame);
		}
		
		// power values
		for($i=1; $i<=2; $i++) {
			$name = "power".$i;
			$this->insertPower($data->$name, $id, $name, $frame);
		}
	}
	
	/**
	 * Inserts a new dataset with given date in the database and
	 * returns the id of the new dataset
	 * @param Date $date
	 * @return id
	 */
	private function insertDate($date)
	{
		// insert dataset
		$statement = $this->mysqli->prepare("INSERT IGNORE INTO t_datasets (date) ".
										    "VALUES (?);");
		$statement->bind_param('s', $date);
		$statement->execute();
		$statement->close();
		
		// get id back
		$statement = $this->mysqli->prepare("SELECT id FROM t_datasets ".
										    "WHERE date = ?;");
		$statement->bind_param('s', $date);
		$statement->bind_result($id);
		$statement->execute();
		$statement->fetch();
		return $id;
	}
	
	/**
	 * Inserts an analog value to a dataset id
	 * @param float $value
	 * @param int $id
	 * @param string $name
	 * @param string $frame
	 */
	private function insertAnalog($value, $id, $name, $frame)
	{
		$statement = $this->mysqli->prepare("REPLACE INTO t_analogs ".
											"(dataset, value, frame, type)".
										    "VALUES (?,?,?,?);");
		$statement->bind_param('idss', $id, $value, $frame, $name);
	
		$statement->execute();
		$statement->close();
	}
	
	/**
	 * Inserts a digital value to a dataset id and 
	 * if exists add a speed value to digital
	 * @param int $value
	 * @param int $id
	 * @param string $name
	 * @param string $frame
	 * @param int $speed
	 */
	private function insertDigital($value, $id, $name, $frame, $speed=NULL)
	{
		$statement = $this->mysqli->prepare("REPLACE INTO t_digitals ".
											"(dataset, value, frame, type)".
											"VALUES (?,?,?,?);");
		$statement->bind_param('iiss', $id, $value, $frame, $name);
		$statement->execute();
		
		if(isset($speed)) {
			$this->insertSpeed($statement->insert_id, $speed);
		}
		$statement->close();
	}
	
	/**
	 * Inserts a speed value to a digital 
	 * @param int $value
	 * @param int $id
	 */
	private function insertSpeed($value, $id)
	{
		$statement = $this->mysqli->prepare("REPLACE INTO t_speeds ".
											"(digital, value) ".
											"VALUES (?,?);");
		$statement->bind_param('ii', $value, $id);
		$statement->execute();
		$statement->close();
	}
	
	/**
	 * Inserts an energy value to a dataset id
	 * @param float $value
	 * @param int $id
	 * @param string $name
	 * @param string $frame
	 */
	private function insertEnergy($value, $id, $name, $frame)
	{
		$statement = $this->mysqli->prepare("REPLACE INTO t_energies ".
				                            "(dataset, value, frame, type) ".
											"VALUES (?,?,?,?);");
		$statement->bind_param('idss', $id, $value, $frame, $name);
		$statement->execute();
		$statement->close();
	}
	
	/**
	 * Inserts a power value to a dataset id
	 * @param float $value
	 * @param int $id
	 * @param string $name
	 * @param string $frame
	 */
	private function insertPower($value, $id, $name, $frame)
	{
		$statement = $this->mysqli->prepare("REPLACE INTO t_powers ".
											"(dataset, value, frame, type) ".
											"VALUES (?,?,?,?);");
		$statement->bind_param('idss', $id, $value, $frame, $name);
		$statement->execute();
		$statement->close();
	}
	
	/**
	 * Query the analog chart with given id and date
	 * @param Date $date
	 * @param int $chartId
	 * @param int $period
	 * @return Array
	 */
	public function queryAnalog($date, $chartId, $period)
	{
		// get the columns of a chart
		$statement = $this->mysqli->prepare("SELECT frame, type FROM t_names ".
											"INNER JOIN t_names_of_charts ".
											"ON (t_names.id = t_names_of_charts.name_id) ".
											"WHERE t_names_of_charts.chart_id=?;");
		$statement->bind_param('i', $chartId);
		
		$statement->execute();
		$statement->bind_result($frame, $name);
		
		$columns = array();
		$joins = array();
		$columnNames = array();
		$i = 1;
		// build chart query
		while($statement->fetch()) {
			$columnNames[] = sprintf("c%d",$i);
			$columns[] = sprintf("FORMAT(d%02d.value,1) AS c%d",$i,$i);
			$joins[] = sprintf("INNER JOIN t_analogs AS d%02d ".
							   "ON (t_datasets.id = d%02d.dataset ".
							   "AND d%02d.frame = \"%s\" AND d%02d.type = \"%s\")",
							    $i, $i, $i, $frame, $i, $name);
			$i++;
		}

		$sql = "SELECT date, ";
		$sql .= join(", ",$columnNames);
		$sql .= " FROM (SELECT @row := @row+1 AS rownum, UNIX_TIMESTAMP(t_datasets.date) AS date, ";
		$sql .= join(", ", $columns);
		$sql .= " FROM (SELECT @row :=0) r, t_datasets ";
		$sql .= join(" ", $joins);
		$sql .= sprintf(" WHERE t_datasets.date > DATE_SUB(\"%s\", INTERVAL %d DAY) ".
						"AND t_datasets.date < DATE_ADD(\"%s\", INTERVAL 1 DAY))".
						"ranked WHERE rownum %%%d =1;",
						$date, $period, $date, ($period+1)*2);
		
		$statement->close();
		
		// fetch chart data
		$result = $this->mysqli->query($sql);
		$rows = array();
		while($r = $result->fetch_array(MYSQLI_NUM)) {
			$rows[] = $r;
		}
		return $rows;
	}
	
	/**
	 * Query the power chart with given id and date
	 * @param Date $date
	 * @param int $chartId
	 * @param int $period
	 * @return Array
	 */
	public function queryPower($date, $chartId, $period)
	{
		// get the columns of a chart
		$statement = $this->mysqli->prepare("SELECT frame, type FROM t_names ".
											"INNER JOIN t_names_of_charts ".
											"ON (t_names.id = t_names_of_charts.name_id) ".
											"WHERE t_names_of_charts.chart_id=?;");
		$statement->bind_param('i', $chartId);
		$statement->execute();
		$statement->bind_result($frame, $name);
	
		$columns = array();
		$joins = array();
		$columnNames = array();
		$i = 1;
		// build chart query
		while($statement->fetch()) {
			$columnNames[] = sprintf("c%d",$i);
			$columns[] = sprintf("FORMAT(d%02d.value,3) AS c%d",$i,$i);
			$joins[] = sprintf("INNER JOIN t_powers AS d%02d ".
							   "ON (t_datasets.id = d%02d.dataset ".
							   "AND d%02d.frame = \"%s\" AND d%02d.type = \"%s\")",
							   $i, $i, $i, $frame, $i, $name);
			$i++;
		}
		
		$sql = "SELECT date, ";
		$sql .= join(", ",$columnNames);
		$sql .= " FROM (SELECT @row := @row+1 AS rownum, UNIX_TIMESTAMP(t_datasets.date) AS date, ";
		$sql .= join(", ", $columns);
		$sql .= " FROM (SELECT @row :=0) r, t_datasets ";
		$sql .= join(" ", $joins);
		$sql .= sprintf(" WHERE t_datasets.date > DATE_SUB(\"%s\", INTERVAL %d DAY) ".
						"AND t_datasets.date < DATE_ADD(\"%s\", INTERVAL 1 DAY))".
						"ranked WHERE rownum %%%d =1;",
						$date, $period, $date, ($period+1)*2);
		$statement->close();
		
		// fetch chart data
		$result = $this->mysqli->query($sql);
		$rows = array();
		while($r = $result->fetch_array(MYSQLI_NUM)) {
			$rows[] = $r;
		}
		return $rows;
	}
	
	/**
	 * Query the energy chart with given id and date
	 * @param Date $date
	 * @param int $chartId
	 * @param string $grouping
	 * @return Array
	 */
	public function queryEnergy($date, $chartId, $grouping)
	{
		// get the columns of a chart
		$statement = $this->mysqli->prepare("SELECT frame, type FROM t_names ".
											"INNER JOIN t_names_of_charts ".
											"ON (t_names.id = t_names_of_charts.name_id) ".
											"WHERE t_names_of_charts.chart_id=?;");
		$statement->bind_param('i', $chartId);
		$statement->execute();
		$statement->bind_result($frame, $name);
	
		$columns = array();
		$joins = array();
		$i = 1;
		// build chart query
		while($statement->fetch()) {
			$columns[] = sprintf("FORMAT(d%02dmax.value-d%02dmin.value,1) AS c%d",
								 $i, $i, $i);
			$joins[] = sprintf("INNER JOIN t_energies AS d%02dmin ".
							   "ON (tmp.minId = d%02dmin.dataset AND d%02dmin.frame = \"%s\" ".
							   "AND d%02dmin.type = \"%s\")", $i, $i, $i, $frame, $i, $name);
			$joins[] = sprintf("INNER JOIN t_energies AS d%02dmax ".
							   "ON (tmp.maxId = d%02dmax.dataset AND d%02dmax.frame = \"%s\" ".
							   "AND d%02dmax.type = \"%s\")", $i, $i, $i, $frame, $i, $name);
			$i++;
		}
		
		if($grouping=="months") {
			$format = "%b 20%y";
			$interval = "1 YEAR";
			$groupby = "MONTH(date),YEAR(date)";
		}
		else {
			$format = "%d. %b";
			$interval = "10 DAY";
			$groupby = "DATE(date)";
		}
		$sql = "SELECT DATE_FORMAT(tmp.date, '".$format."') AS date, ";
		$sql .= join(", ", $columns);
		$sql .= sprintf(" FROM (SELECT t_datasets.date AS date,".
						"MIN(t_datasets.id) AS minId, MAX(t_datasets.id) AS maxId ".  
    			    	"FROM t_datasets ".
    					"WHERE t_datasets.date < DATE_ADD(\"%s\",INTERVAL 1 DAY) ".
						"AND t_datasets.date > DATE_SUB(\"%s\", INTERVAL %s) ".
						"GROUP BY %s) AS tmp ", $date, $date,
						$interval, $groupby);
		$sql .= join(" ", $joins);

		$statement->close();
		
		// fetch chart data
		$result = $this->mysqli->query($sql);
		$rows = array();
		while($r = $result->fetch_array(MYSQLI_NUM)) {
			$rows[] = $r;
		}
		return $rows;
	}
	
	/**
	 * Query the date of the last dataset in the database
	 * @return number
	 */
	public function lastDataset()
	{
		$result = $this->mysqli->query("SELECT MAX(date) FROM t_datasets;");
		$last = $result->fetch_array();
		return strtotime($last[0]);
	}
	
	/**
	 * Get the chart and menu configuration
	 * @return Array
	 */
	public function getAppConfig()
	{
		// get menu items
		$statement = $this->mysqli->prepare("SELECT id, name, type, t_menu.schema, unit ".
											"FROM t_menu ORDER BY t_menu.order;");
		$statement->execute();
		$statement->bind_result($id, $name, $type, $schema, $unit);
		
		$rows = array("menu" => array(),
				      "values" => array());
		
		// build menu array
		while($statement->fetch()) {
			if($type == "schema" && $schema!=NULL) {
				$rows["menu"][] = array("id" => $id,
						                "name" => $name,
										"type" => $type,
										"schema" => $schema);
			}
			else {
				$rows["menu"][] = array("id" => $id,
										"name" => $name,
										"type" => $type,
										"unit" => $unit);
			}
		}
		$statement->close();
		
		// get chart configuration 
		for($i=0; $i < count($rows["menu"]); $i++) {
			if($rows["menu"][$i]["type"] != "schema") {
				$statement = $this->mysqli->prepare("SELECT name FROM t_names ".
													"INNER JOIN t_names_of_charts ".
													"ON (t_names.id = t_names_of_charts.name_id) ".
													"WHERE t_names_of_charts.chart_id=?;");
				
				$statement->bind_param('i', $rows["menu"][$i]["id"]);
				
				$statement->execute();
				$statement->bind_result($name);
				
				$columns = array();
				
				while($statement->fetch()) {
					$columns[] = $name;
				}
				$statement->close();
				$rows["menu"][$i]["columns"] = $columns;
			}
		}
		
		// get schema configuration
		$statement = $this->mysqli->prepare("SELECT path, frame, type, format ".
											"FROM t_schema;");
		$statement->execute();
		$statement->bind_result($path, $frame, $type, $format);
		
		while($statement->fetch()) {
			$rows["values"][] = array("path" => $path,
									  "frame" => $frame,
									  "type" => $type,
									  "format" => $format);
		}
		$statement->close();
		return $rows;
	}
}
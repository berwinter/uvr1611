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
		$this->config = Config::getInstance();
		$this->mysqli = new mysqli($this->config->mysql->server,
								   $this->config->mysql->user,
								   $this->config->mysql->password,
								   $this->config->mysql->database);
		$this->mysqli->set_charset("utf8");
	}
	
	/**
	 * Inserts all dataset into the database once
	 * @param Array $data
	 */
	public function insertData($data)
	{
		$insert = "INSERT IGNORE INTO t_data (date, frame,"
		         ."analog1, analog2, analog3, analog4, analog5, analog6, analog7, analog8,"
		         ."analog9, analog10, analog11, analog12, analog13, analog14, analog15, analog16,"
		         ."digital1, digital2, digital3, digital4, digital5, digital6, digital7, digital8,"
		         ."digital9, digital10, digital11, digital12, digital13, digital14, digital15, digital16,"
		         ."speed1, speed2, speed3, speed4,"
                 ."power1, power2, energy1, energy2)  VALUES ";
		
		$values = Array();
		foreach ($data as $dataset) {
			while ($frame = current($dataset)) {
				$values[] = $this->getValuesFormDataset($frame, key($dataset));
				next($dataset);
			}
		}
		
		$this->mysqli->query($insert.join(',',$values));
	}
	
	/**
	 * returns a string containing the values from a dataset
	 * @param Parser $data
	 * @param string $frame
	 */
	private function getValuesFormDataset($data, $frame)
	{
		return "('$data->date', '$frame',"
		      ." $data->analog1, $data->analog2, $data->analog3, $data->analog4,"
		      ." $data->analog5, $data->analog6, $data->analog7, $data->analog8,"
		      ." $data->analog9, $data->analog10, $data->analog11, $data->analog12,"
		      ." $data->analog13, $data->analog14, $data->analog15, $data->analog16,"
		      ." $data->digital1, $data->digital2, $data->digital3, $data->digital4,"
		      ." $data->digital5, $data->digital6, $data->digital7, $data->digital8,"
		      ." $data->digital9, $data->digital10, $data->digital11, $data->digital12,"
		      ." $data->digital13, $data->digital14, $data->digital15, $data->digital16,"
		      ." $data->speed1, $data->speed2, $data->speed3, $data->speed4,"
		      ." $data->power1, $data->power2, $data->energy1, $data->energy2)";
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
		$statement = $this->mysqli->prepare("SELECT frame, type FROM t_names_of_charts ".
											"WHERE chart_id=? ORDER BY t_names_of_charts.order ASC;");
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
			$columns[] = sprintf("FORMAT(%s,1) AS c%d",$name,$i);
			$i++;
		}

		$sql = "SELECT date, ";
		$sql .= join(", ",$columnNames);
		$sql .= " FROM (SELECT @row := @row+1 AS rownum, UNIX_TIMESTAMP(t_data.date) AS date, ";
		$sql .= join(", ", $columns);
		$sql .= " FROM (SELECT @row :=0) r, t_data ";
		$sql .= sprintf(" WHERE t_data.frame = \"%s\" AND t_data.date > DATE_SUB(\"%s\", INTERVAL %d DAY) ".
						"AND t_data.date < DATE_ADD(\"%s\", INTERVAL 1 DAY))".
						"ranked WHERE rownum %%%d =1;",
						$frame, $date, $period, $date, ($period+1)*$this->config->app->reduction);
		$statement->close();
		
		// fetch chart data
		$rows = array();
		if(	$result = $this->mysqli->query($sql)) {
			while($r = $result->fetch_array(MYSQLI_NUM)) {
				$rows[] = $r;
			}
			$result->close();
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
		$statement = $this->mysqli->prepare("SELECT frame, type FROM t_names_of_charts ".
											"WHERE chart_id=? ORDER BY t_names_of_charts.order ASC;");
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
			$columns[] = sprintf("FORMAT(%s,1) AS c%d",$name,$i);
			$i++;
		}

		$sql = "SELECT date, ";
		$sql .= join(", ",$columnNames);
		$sql .= " FROM (SELECT @row := @row+1 AS rownum, UNIX_TIMESTAMP(t_data.date) AS date, ";
		$sql .= join(", ", $columns);
		$sql .= " FROM (SELECT @row :=0) r, t_data ";
		$sql .= sprintf(" WHERE t_data.frame = \"%s\" AND t_data.date > DATE_SUB(\"%s\", INTERVAL %d DAY) ".
						"AND t_data.date < DATE_ADD(\"%s\", INTERVAL 1 DAY))".
						"ranked WHERE rownum %%%d =1;",
						$frame, $date, $period, $date, ($period+1)*$this->config->app->reduction);
		$statement->close();
		
		// fetch chart data
		$rows = array();
		if($result = $this->mysqli->query($sql)) {
			while($r = $result->fetch_array(MYSQLI_NUM)) {
				$rows[] = $r;
			}
			$result->close();
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
		$statement = $this->mysqli->prepare("SELECT frame, type FROM t_names_of_charts ".
											"WHERE chart_id=? ORDER BY t_names_of_charts.order ASC;");
		$statement->bind_param('i', $chartId);
		$statement->execute();
		$statement->bind_result($frame, $name);
	
		$columns = array();
		$joins = array();
		$i = 1;
		// build chart query
		while($statement->fetch()) {
			$columns[] = sprintf("FORMAT(d%02dmax.%s-d%02dmin.%s,1) AS c%d",
								 $i, $name, $i, $name, $i);
			$joins[] = sprintf("INNER JOIN t_data AS d%02dmin ".
							   "ON (tmp.min = d%02dmin.date AND d%02dmin.frame = \"%s\")",
					           $i, $i, $i, $frame);
			$joins[] = sprintf("INNER JOIN t_data AS d%02dmax ".
							   "ON (tmp.max = d%02dmax.date AND d%02dmax.frame = \"%s\")",
							   $i, $i, $i, $frame, $i, $name);
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
		$sql .= sprintf(" FROM (SELECT date,".
						"MIN(date) AS min, MAX(date) AS max ".  
    			    	"FROM t_data ".
    					"WHERE t_data.date < DATE_ADD(\"%s\",INTERVAL 1 DAY) ".
						"AND t_data.date > DATE_SUB(\"%s\", INTERVAL %s) ".
						"GROUP BY %s  ORDER BY date ASC) AS tmp ", $date, $date,
						$interval, $groupby);
		$sql .= join(" ", $joins);

		$statement->close();
		// fetch chart data
		$rows = array();
		if($result = $this->mysqli->query($sql)) {
			while($r = $result->fetch_array(MYSQLI_NUM)) {
				$rows[] = $r;
			}
			$result->close();
		}
		return $rows;
	}
	
	/**
	 * Query the date of the last dataset in the database
	 * @return number
	 */
	public function lastDataset()
	{
		$result = $this->mysqli->query("SELECT MAX(date) FROM t_data;");
		$last = $result->fetch_array();
		$result->close();
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
				$statement = $this->mysqli->prepare("SELECT t_names.name FROM t_names ".
													"INNER JOIN t_names_of_charts ".
													"ON (t_names.type = t_names_of_charts.type ".
		   			                                "AND t_names.frame = t_names_of_charts.frame) ".
													"WHERE t_names_of_charts.chart_id=? ORDER BY t_names_of_charts.order ASC;");
				
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
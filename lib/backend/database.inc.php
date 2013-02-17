<?php
include_once("lib/config.inc.php");

class Database
{

	public static $instance;

	private $config;
	private $mysqli;

	public static function getInstance()
	{
		if (null == self::$instance) {
			self::$instance = new self;
		}
		
		return self::$instance;
	}
	
	private function __construct(array $options=array())
	{
		$this->config = Config::getInstance()->mysql;
		$this->mysqli = new mysqli($this->config->server,$this->config->user,$this->config->password,$this->config->database);
		$this->mysqli->set_charset("utf8");
	}
	
	public function insterDataset($data, $frame)
	{
		$id = $this->insertDate($data->date);
		
		for($i=1; $i<=16; $i++)
		{
			$name = "analog".$i;
			$this->insertAnalog($data->$name, $id, $name, $frame);
		}
		
		for($i=1; $i<=16; $i++)
		{
			$name = "digital".$i;
			$speed = NULL;
			switch($i)
			{
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
		
		for($i=1; $i<=2; $i++)
		{
			$name = "energy".$i;
			$this->insertEnergy($data->$name, $id, $name, $frame);
		}
		
		for($i=1; $i<=2; $i++)
		{
			$name = "power".$i;
			$this->insertPower($data->$name, $id, $name, $frame);
		}
	}
	
	private function insertDate($date)
	{
		// insert
		$statement = $this->mysqli->prepare("INSERT IGNORE INTO t_datasets (date) VALUES (?)");
		$statement->bind_param('s', $date);
		$statement->execute();
		$statement->close();
		
		// get id
		$statement = $this->mysqli->prepare("SELECT id FROM t_datasets WHERE date = ?;");
		$statement->bind_param('s', $date);
		$statement->bind_result($id);
		$statement->execute();
		$statement->fetch();
		
		return $id;
	}
	
	private function insertAnalog($value, $id, $name, $frame)
	{
		$statement = $this->mysqli->prepare("REPLACE INTO t_analogs (dataset, value, frame, type) VALUES (?,?,?,?)");
		$statement->bind_param('idss', $id, $value, $frame, $name);
	
		$statement->execute();
		$statement->close();
	}
	
	private function insertDigital($value, $id, $name, $frame, $speed=NULL)
	{
		$statement = $this->mysqli->prepare("REPLACE INTO t_digitals (dataset, value, frame, type) VALUES (?,?,?,?)");
		$statement->bind_param('iiss', $id, $value, $frame, $name);
		$statement->execute();
		
		if(isset($speed))
		{
			$this->insertSpeed($statement->insert_id, $speed);
		}
		$statement->close();
	}
	
	private function insertSpeed($value, $id)
	{
		$statement = $this->mysqli->prepare("REPLACE INTO t_speeds (digital, value) VALUES (?,?)");
		$statement->bind_param('ii', $value, $id);
		$statement->execute();
		$statement->close();
	}
	
	private function insertEnergy($value, $id, $name, $frame)
	{
		$statement = $this->mysqli->prepare("REPLACE INTO t_energies (dataset, value, frame, type) VALUES (?,?,?,?)");
		$statement->bind_param('idss', $id, $value, $frame, $name);
	
		$statement->execute();
		$statement->close();
	}
	
	private function insertPower($value, $id, $name, $frame)
	{
		$statement = $this->mysqli->prepare("REPLACE INTO t_powers (dataset, value, frame, type) VALUES (?,?,?,?)");
		$statement->bind_param('idss', $id, $value, $frame, $name);
	
		$statement->execute();
		$statement->close();
	}
	
	public function testSql()
	{
		if($result = $this->mysqli->query("SELECT * FROM t_datasets;"))
		{
			while($row = $result->fetch_object())
			{
				print_r($row);
			}
		}
	}
	
	public function queryAnalog($date, $chartId)
	{
		$statement = $this->mysqli->prepare("SELECT frame, type FROM t_names INNER JOIN t_names_of_charts ON (t_names.id = t_names_of_charts.name_id) WHERE t_names_of_charts.chart_id=?;");
		$statement->bind_param('i', $chartId);
		
		$statement->execute();
		$statement->bind_result($frame, $name);
		
		$columns = array();
		$joins = array();

		$i = 1;
		while($statement->fetch())
		{
			$columns[] = sprintf("FORMAT(d%02d.value,1) AS c%d",$i,$i);
			$joins[] = sprintf("INNER JOIN t_analogs AS d%02d ON (t_datasets.id = d%02d.dataset AND d%02d.frame = \"%s\" AND d%02d.type = \"%s\")",$i,$i,$i,$frame,$i,$name);
			$i++;
		}

		$sql = "SELECT UNIX_TIMESTAMP(t_datasets.date) AS date, ";
		$sql .= join(", ", $columns);
		$sql .= " FROM t_datasets ";
		$sql .= join(" ", $joins);
		$sql .= sprintf(" WHERE t_datasets.date > \"%s\" AND t_datasets.date < DATE_ADD(\"%s\", INTERVAL 1 DAY);",$date,$date);
		
		$statement->close();
		
		$result = $this->mysqli->query($sql);

		
		$rows = array();
		while($r = $result->fetch_array(MYSQLI_NUM)) {
			$rows[] = $r;
		}
		return $rows;
	}
	
	public function queryPower($date, $chartId)
	{
		$statement = $this->mysqli->prepare("SELECT frame, type FROM t_names INNER JOIN t_names_of_charts ON (t_names.id = t_names_of_charts.name_id) WHERE t_names_of_charts.chart_id=?;");
		$statement->bind_param('i', $chartId);
	
		$statement->execute();
		$statement->bind_result($frame, $name);
	
		
		$columns = array();
		$joins = array();
		
		$i = 1;
		while($statement->fetch())
		{
			$columns[] = sprintf("FORMAT(d%02d.value,3) AS c%d",$i,$i);
			$joins[] = sprintf("INNER JOIN t_powers AS d%02d ON (t_datasets.id = d%02d.dataset AND d%02d.frame = \"%s\" AND d%02d.type = \"%s\")",$i,$i,$i,$frame,$i,$name);
			$i++;
		}
		
		$sql = "SELECT UNIX_TIMESTAMP(t_datasets.date) AS date, ";
		$sql .= join(", ", $columns);
		$sql .= " FROM t_datasets ";
		$sql .= join(" ", $joins);
		$sql .= sprintf(" WHERE t_datasets.date > \"%s\" AND t_datasets.date < DATE_ADD(\"%s\", INTERVAL 1 DAY);",$date,$date);
	
		$statement->close();
	
	
		$result = $this->mysqli->query($sql);
	
	
		$rows = array();
		while($r = $result->fetch_array(MYSQLI_NUM)) {
			$rows[] = $r;
		}
		return $rows;
	}
	
	public function queryEnergy($date, $chartId)
	{
		$statement = $this->mysqli->prepare("SELECT frame, type FROM t_names INNER JOIN t_names_of_charts ON (t_names.id = t_names_of_charts.name_id) WHERE t_names_of_charts.chart_id=?;");
		$statement->bind_param('i', $chartId);
	
		$statement->execute();
		$statement->bind_result($frame, $name);
	
		$columns = array();
		$joins = array();
		
		$i = 1;
		while($statement->fetch())
		{
			$columns[] = sprintf("FORMAT(d%02dmax.value-d%02dmin.value,1) AS c%d",$i,$i,$i);
			$joins[] = sprintf("INNER JOIN t_energies AS d%02dmin ON (tmp.minId = d%02dmin.dataset AND d%02dmin.frame = \"%s\" AND d%02dmin.type = \"%s\")",$i,$i,$i,$frame,$i,$name);
			$joins[] = sprintf("INNER JOIN t_energies AS d%02dmax ON (tmp.maxId = d%02dmax.dataset AND d%02dmax.frame = \"%s\" AND d%02dmax.type = \"%s\")",$i,$i,$i,$frame,$i,$name);
			$i++;
		}
		
		$sql = "SELECT DATE_FORMAT(tmp.date, '%d-%m') AS date, ";
		$sql .= join(", ", $columns);
		$sql .= sprintf(" FROM (
    			  	SELECT t_datasets.date AS date, MIN(t_datasets.id) AS minId, MAX(t_datasets.id) AS maxId    
    			    FROM t_datasets
    				WHERE t_datasets.date < DATE_ADD(\"%s\",INTERVAL 1 DAY) AND t_datasets.date > DATE_SUB(\"%s\", INTERVAL 10 DAY) GROUP BY DATE(date)
				 ) AS tmp ",$date,$date);
		$sql .= join(" ", $joins);

		$statement->close();
	
		$result = $this->mysqli->query($sql);
			
		$rows = array();
		while($r = $result->fetch_array(MYSQLI_NUM)) {
			$rows[] = $r;
		}
		return $rows;
	}
	
	public function lastDataset()
	{
		$result = $this->mysqli->query("SELECT MAX(date) FROM t_datasets;");
		$last = $result->fetch_array();
		return strtotime($last[0]);
	}
	
	public function getAppConfig()
	{
		$statement = $this->mysqli->prepare("SELECT id, name, type, t_menu.schema, unit FROM t_menu ORDER BY t_menu.order;");
		$statement->execute();
		$statement->bind_result($id, $name, $type, $schema, $unit);
		
		$rows = array("menu" => array(), "values" => array());
		
		while($statement->fetch()) {
			if($type == "schema" && $schema!=NULL)
			{
				$rows["menu"][] = array("id" => $id, "name" => $name, "type" => $type, "schema" => $schema);
			}
			else
			{
				$rows["menu"][] = array("id" => $id, "name" => $name, "type" => $type, "unit" => $unit);
			}
		}
		$statement->close();
		
		for($i=0; $i < count($rows["menu"]); $i++)
		{
			if($rows["menu"][$i]["type"] != "schema")
			{
				$statement = $this->mysqli->prepare("SELECT name FROM t_names INNER JOIN t_names_of_charts ON (t_names.id = t_names_of_charts.name_id) WHERE t_names_of_charts.chart_id=?;");
				echo $this->mysqli->error;
				
				$statement->bind_param('i', $rows["menu"][$i]["id"]);
				
				$statement->execute();
				$statement->bind_result($name);
				$columns = array();
				while($statement->fetch())
				{
					$columns[] = $name;
				}
				$statement->close();
				$rows["menu"][$i]["columns"] = $columns;
			}
		}
		
		
		$statement = $this->mysqli->prepare("SELECT path, frame, type, format FROM t_schema;");
		$statement->execute();
		$statement->bind_result($path, $frame, $type, $format);
		
		
		while($statement->fetch())
		{
			$rows["values"][] = array("path" => $path, "frame" => $frame, "type" => $type, "format" => $format);
		}
		$statement->close();
		
		return $rows;
		
	}
}
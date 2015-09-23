<?php
/**
 * CMI Connection
 *
 * Provides access to the CMI for stored datasets and latest values
 *
 * @copyright  Copyright (c) Bertram Winter bertram.winter@gmail.com
 * @license    GPLv3 License
 */
include_once("lib/config.inc.php");
include_once("lib/backend/cmi-parser.inc.php");
include_once("lib/backend/database.inc.php");


class CmiConnection
{

	const NEWLINE = "\x0D\x0A";
	const DATASET_SIZE = 80;
	
	private $config;
	private $parser;
	private $count = 0;
	private $days = array();

	public function __construct()
	{
		$this->config = Config::getInstance()->uvr1611;
	}

	public function getLatest()
	{
		$database = Database::getInstance();
		return $database->queryLatest(time());
	}
	
	public function endRead()
	{
		// reset data if configured
		if($this->config->reset) {
			$this->getData("/LOG/clear.log");
		}
		$this->count = 0;
		$this->pos = 0;
		$this->days = array();
	}
	
	public function fetchData()
	{
		$this->getCount();
		foreach($this->days as $day) {
			$this->getData($day);
			$data = $this->data;
			$pos = strpos($data, self::NEWLINE, 0)+2;
			$temp = unpack("v2count", substr($data, $pos, 4));
			$count = $temp["count1"] + $temp["count2"];
			$start = $pos+8+self::DATASET_SIZE*$count;
			$size = $this->parser->getSize();
			$end = strlen($data)-1*$size;
			while($start<=$end) {
				yield $this->parser->parse(substr($data, $end, $size));
				$end -= $size;
			}
		}
	}
	
	public function getCount()
	{
		if($this->count == 0) {
			$this->getData("/LOG/infoh.log");
			$header = $this->readInfoHeader();
			$this->parser = new CmiParser();
			foreach($this->getDatasets($header["count"]) as $dataset) {
				$this->parser->addDataset($dataset);
			}
			$years = explode(self::NEWLINE, trim(substr($this->data, $this->pos)));
			foreach($years as $year) {
				$this->getData("/LOG/info$year.log");
				$header = $this->readInfoHeader();
				$days = explode(self::NEWLINE, trim(substr($this->data, $this->pos+self::DATASET_SIZE*$header["count"])));
				foreach ($days as $day) {
					$temp = explode(" ", $day);
					$this->days[] = $temp[0];
					$this->count += intval($temp[0])/$this->parser->getSize();
				}
			}
			rsort($this->days);		
		}
		return $this->count;		
	}
	
	private function getDatasets($count) {
		for($i=0;$i<$count;$i++) {
			yield substr($this->data, $this->pos, self::DATASET_SIZE);
			$this->pos += self::DATASET_SIZE;
		}
	}
	
	private function readInfoHeader() {
		$this->pos = strpos($this->data, self::NEWLINE, $this->pos)+2;
		$this->pos = strpos($this->data, self::NEWLINE, $this->pos)+2;
		$this->pos = strpos($this->data, self::NEWLINE, $this->pos)+2;
		$data = unpack("v2count", substr($this->data, $this->pos, 4));
		$this->pos += 4;
		$header = array();
		$header["count"] = $data["count1"] + $data["count2"];
		return $header;
	}
	
	function getData($url) {
		$process = curl_init();
		curl_setopt($process, CURLOPT_URL, "http://$this->config->address:$this->config->port$url");
		curl_setopt($process, CURLOPT_USERPWD,  "winsol:data");
		curl_setopt($process, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
		curl_setopt($process, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($process, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		$this->data = curl_exec($process);
		$this->pos = 0;
		curl_close($process);
	}
}
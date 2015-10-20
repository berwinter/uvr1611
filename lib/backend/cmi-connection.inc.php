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
include_once("lib/backend/uvr1611.inc.php");
include_once("lib/backend/cmi-parser.inc.php");
include_once("lib/backend/database.inc.php");
error_reporting(0);

class CmiConnection
{

	const NEWLINE = "\x0D\x0A";
	const DATASET_SIZE = 80;
	
	private $config;
	private $parser;
	private $count = 0;
	private $days = array();
	private $day = 0;
	private $start = 0;
	private $end = 0;
	private $size = 0;

	public function __construct()
	{
		$this->config = Config::getInstance()->uvr1611;
		if(!function_exists('curl_version')) {
			throw new Exception("Requires PHP-CURL for CMI communication.");
		}
	}

	public function getLatest()
	{
		create_pid();
		$this->getCount();
		$database = Database::getInstance();
		$frames = $this->fetchData();
		foreach($frames as $key => $frame) {
			$current_energy = $database->getCurrentEnergy($key);
			$frames[$key]["current_energy1"] = $current_energy[0];
			$frames[$key]["current_energy2"] = $current_energy[1];
		}
		close_pid();
		return $frames;
	}
	
	public function startRead()
	{
		create_pid();
		return $this->getCount();
	}
	
	public function endRead($success = true)
	{
		close_pid();
		// reset data if configured
		if($success && $this->config->reset) {
			$this->getData("/LOG/clear.log");
		}
		$this->count = 0;
		$this->pos = 0;
		$this->days = array();
	}
	
	public function fetchData()
	{
		if($this->start>=$this->end) {
			// get next day
			$day = $this->days[$this->day];
			if($day == null) {
				return false;
			}
			$this->getData($day);
			$this->day++;
			$this->pos = strpos($this->data, self::NEWLINE, 0)+2;
			$temp = unpack("v2count", substr($this->data, $this->pos, 4));
			$count = $temp["count1"] + $temp["count2"];
			$this->start = $this->pos+8+self::DATASET_SIZE*$count;
			$this->size = $this->parser->getSize();
			$this->end = strlen($this->data);
		}
		$this->end -= $this->size;
		return $this->parser->parse(substr($this->data, $this->end, $this->size));
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
					18+self::DATASET_SIZE*$header["count"];
					$this->days[] = $temp[0];
					$count = (intval($temp[1])-(18+self::DATASET_SIZE*$header["count"]))/$this->parser->getSize();
					$this->count += $count;
				}
			}
			$this->day = 0;
			rsort($this->days);		
		}
		return $this->count;		
	}
	
	private function getDatasets($count) {
		$datasets = array();
		for($i=0;$i<$count;$i++) {
			$datasets[] = substr($this->data, $this->pos, self::DATASET_SIZE);
			$this->pos += self::DATASET_SIZE;
		}
		return $datasets;
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
		$ip = $this->config->address;
		$port = $this->config->port;
		curl_setopt($process, CURLOPT_URL, "http://$ip:$port$url");
		curl_setopt($process, CURLOPT_USERPWD,  "winsol:data");
		curl_setopt($process, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
		curl_setopt($process, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($process, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		$this->data = curl_exec($process);
		$this->pos = 0;
		curl_close($process);
	}
}

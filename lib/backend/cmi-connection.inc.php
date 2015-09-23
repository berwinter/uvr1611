<?php
/**
 * Uvr1611 Connection (Singleton)
 *
 * Provides access to the bootloader for stored datasets and actuell values
 *
 * @copyright  Copyright (c) Bertram Winter bertram.winter@gmail.com
 * @license    GPLv3 License
 */
//include_once("lib/config.inc.php");
//include_once("lib/backend/cmi-parser.inc.php");
//include_once("lib/backend/database.inc.php");
include_once("cmi-parser.inc.php");


class CmiConnection
{

	const NEWLINE = "\x0D\x0A";
	const DATASET_SIZE = 80;
	
	private $config;
	private $parser;
	private $count = 0;
	private $days = array();

	private function __construct()
	{
		//$this->config = Config::getInstance()->uvr1611;
	}

	public function getLatest()
	{
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
		foreach($this->days as $day) {
			$data = $this->getData($day);
			$pos = strpos($data, self::NEWLINE, 0)+2;
			$temp = unpack("v2count", substr($data, $pos+2, 4));
			$count = $temp["count1"] + $temp["count2"];
			$start = $pos+10+self::DATASET_SIZE*$count;
			$size = $parser->getSize();
			$end = strlen($data);
			while($start<$end) {
				yield $parser->parse(substr($data, $end, -$size));
				$end -= $size;
			}
		}
	}
	
	public function getCount()
	{
		if($this->count == 0) {
			$this->getData("/LOG/infoh.log");
			$header = $this->readInfoHeader();
			$this->parser = new Parser();
			foreach($this->getDatasets($header["count"]) as $dataset) {
				$parser->addDataset($dataset);
			}
			$years = explode(self::NEWLINE, trim(substr($this->data, $this->pos)));
			foreach($years as $year) {
				$this->getData("/LOG/info$year.log");
				$header = $this->readInfoHeader();
				$days = explode(self::NEWLINE, trim(substr($this->data, $this->pos+self::DATASET_SIZE*$header["count"])));
				foreach ($days as $day) {
					$temp = explode(" ", $day);
					$this->days[] = $temp[0];
					$this->count += int(intval($temp[0])/$parser->getSize());
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
		$data = unpack("v2count", substr($this->data, $this->pos+2, 4));
		$this->pos += 4;
		$header = array();
		$header["count"] = $data["count1"] + $data["count2"];
		return $header;
	}
	
	
	private function getData($url)
	{
		$file = fopen(".$url", "r") or die("Unable to open file!");
		$this->data = fread($file, filesize(".$url"));
		fclose($file);
	}
}
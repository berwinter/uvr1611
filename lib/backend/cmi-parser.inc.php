<?php
/**
 * Parses a binary string containing a dataset
 * 
 * Provides access to the values of a dataset as object properties
 *
 * @copyright  Copyright (c) Bertram Winter bertram.winter@gmail.com
 * @license    GPLv3 License
 */

class CmiDataset
{
	private $mapping = array(0 => "analog1", 1 => "analog2", 2 => "analog3",
						  3 => "analog4", 4 => "analog5", 5 => "analog6",
                          6 => "analog7", 7 => "analog8", 8 => "analog9",
				  		  9 => "analog10", 10 => "analog11", 11 => "analog12",
						 12 => "analog13", 13 => "analog14", 14 => "analog15",
				         15 => "analog16", 16 => "digital1", 17 => "digital2",
					     18 => "digital3", 19 => "digital4", 20 => "digital5",
						 21 => "digital6", 22 => "digital7", 23 => "digital8",
					     24 => "digital9", 25 => "digital10", 26 => "digital11",
						 27 => "digital12", 28 => "digital13", 29 => "unknown",
						 30 => "speed1", 31 => "speed2", 32 => "speed3",
						 33 => "speed4", 34 => "power1", 35 => "kWh1",
						 36 => "MWh1", 37 => "power2", 38 => "kWh2", 39 => "MWh2");
						 
	const DIGITAL = 0x00ff;
	const SPEED = 0x0000;
	const POWER = 0x020a;
	const KWH = 0x000b;
	const MWH = 0x000c;
	const PT1000 = 0x0a3c;
	const TEMP = 0x0101;
	const VOL = 0x0003;
	const RAD = 0x0002;
	const HOURS = 0x000f;
	const COUNT = 0x0011;
		
	
	public function __construct($string) {
		$this->data = unpack("C/Cframe/C2/Cid/C3/vtype/Csize", substr($string, 0, 11));
		$this->desc = trim(substr($string, 11));
	}
	
	public function getSize() {
		return $this->data["size"];
	}
	
	public function getFormat() {
		switch($this->data["size"]) {
			case 1: return "C".$this->getFrame().$this->getName();
			case 2: return "v".$this->getFrame().$this->getName();
			case 4: return "V".$this->getFrame().$this->getName();
		}
	}
	
	public function getValue($value) {
		switch ($this->data["type"]) {
			case self::PT1000:
			case self::TEMP: 
			case self::KWH:
				return $value/10;
			case self::VOL:
				return $value*4;
			case self::POWER:
				return $value/2560;
			case self::DIGITAL:
			case self::SPEED:
			case self::RAD:
			case self::COUNT:
			case self::HOURS:
			case self::MWH:
			default:
				return $value;
		}
	}
	
	public function getName() {
		return $this->mapping[$this->data["id"]];
	}
	
	public function getFrame() {
		return "frame".$this->data["frame"];
	}
}

class CmiParser
{	
	private $datasets = array();
	private $format = "Cdays/Cmonths/Cyears/Cseconds/Cminutes/Chours/C2none/";
	private $size = 14;
	
	public function addDataset($string) {
		$dataset = new CmiDataset($string);
		$this->format .= $dataset->getFormat()."/";
		$this->size += $dataset->getSize();
		$this->datasets[] = $dataset;
	}
	
	public function getFormatString()
	{
		return $this->format;
	}
	
	public function getSize()
	{
		return $this->size;
	}
	
	public function parse($string)
	{
		$result = array();
		$data = unpack($this->getFormatString(), $string);	
		foreach($this->datasets as $dataset) {
			$frame = $dataset->getFrame();
			$name = $dataset->getName();
			$value = $dataset->getValue($data[$frame.$name]);
			$result[$frame][$name] = $value; 
		}
		
		foreach($result as $key => $frame) {
			$result[$key]["date"] = sprintf("20%02d-%02d-%02d %02d:%02d:%02d",
				              $data["years"],   $data["months"],
							  $data["days"],    $data["hours"],
							  $data["minutes"], $data["seconds"]);
			if(array_key_exists("kWh1", $result[$key])) {
				$result[$key]["energy1"] = $result[$key]["kWh1"]+$result[$key]["MWh1"]*1000;
				unset($result[$key]["kWh1"]);
				unset($result[$key]["MWh1"]);
			}
			if(array_key_exists("kWh2", $result[$key])) {
				$result[$key]["energy2"] = $result[$key]["kWh2"]+$result[$key]["MWh2"]*1000;
				unset($result[$key]["kWh2"]);
				unset($result[$key]["MWh2"]);
			}
			foreach(array("analog1", "analog2","analog3","analog4","analog5","analog6","analog7","analog8","analog9", "analog10", "analog11", "analog12", "analog13", "analog14", "analog15", "analog16", "digital1", "digital2","digital3","digital4","digital5","digital6","digital7","digital8","digital9", "digital10", "digital11", "digital12", "digital13", "digital14", "digital15", "digital16","speed1","speed2","speed3","speed4","power1","power2","energy1","energy2") as $k) {
				if(!array_key_exists($k, $result[$key])) {
					$result[$key][$k] = "NULL";
				}
			}
		}

		return $result;
	}
}

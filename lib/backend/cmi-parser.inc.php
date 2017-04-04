<?php
/**
 * Parses a binary string containing a dataset
 *
 * Provides access to the values of a dataset as object properties
 *
 * @copyright  Copyright (c) Bertram Winter bertram.winter@gmail.com
 * @license    GPLv3 License
 */
error_reporting(0);

class CmiDataset
{
	private $mapping = array(
		"esr21" =>  array(0 => "analog1",    1 => "analog2",    2 => "analog3",
						  9 => "digital1",  11 => "speed1"),
		"uvr" =>    array(0 => "analog1",    1 => "analog2",    2 => "analog3",
						  3 => "analog4",    4 => "analog5",    5 => "analog6",
		                  6 => "analog7",    7 => "analog8",    8 => "analog9",
				  		  9 => "analog10",  10 => "analog11",  11 => "analog12",
						 12 => "analog13",  13 => "analog14",  14 => "analog15",
				         15 => "analog16",  16 => "digital1",  17 => "digital2",
					     18 => "digital3",  19 => "digital4",  20 => "digital5",
						 21 => "digital6",  22 => "digital7",  23 => "digital8",
					     24 => "digital9",  25 => "digital10", 26 => "digital11",
						 27 => "digital12", 28 => "digital13", 29 => "unknown",
						 30 => "speed1",    31 => "speed2",    32 => "speed3",
						 33 => "speed4",    34 => "power1",    35 => "kWh1",
						 36 => "MWh1",      37 => "power2",    38 => "kWh2",
						 39 => "MWh2"),
		"uvrx2" =>  array(0 =>
						array(0 => "analog1",   1 => "analog2",   2 => "analog3",
						  	  3 => "analog4",   4 => "analog5",   5 => "analog6",
		                      6 => "analog7",   7 => "analog8",   8 => "analog9",
				  		      9 => "analog10", 10 => "analog11", 11 => "analog12",
						     12 => "analog13", 13 => "analog14", 14 => "analog15",
				             15 => "analog16"),
						  1 =>
						array(0 => "digital1",   1 => "digital2",   2 => "digital3",
  						  	  3 => "digital4",   4 => "digital5",   5 => "digital6",
  		                      6 => "digital7",   7 => "digital8",   8 => "digital9",
  				  		      9 => "digital10", 10 => "digital11", 11 => "digital12",
  						     12 => "digital13", 13 => "digital14", 14 => "digital15",
  				             15 => "digital16")
						),
		"canbc" =>  array(0 => "analog1",    1 => "analog2",    2 => "analog3",
						  3 => "power1",     4 => "kWh1",       5 => "MWh1",
						  6 => "analog4",    7 => "analog5",    8 => "analog6",
						  9 => "power2",    10 => "kWh2",      11 => "MWh2",
						 12 => "analog1",   13 => "analog2",   14 => "analog3",
						 15 => "power1",    16 => "kWh1",      17 => "MWh1"),
		"canez" =>  array(0 => "analog1",    1 => "analog2",    2 => "analog3",
						  3 => "analog4",    4 => "analog5",    5 => "analog6",
                          6 => "analog7",    7 => "analog8",    8 => "analog9",
				  		  9 => "analog10",  10 => "analog11",  11 => "analog12",
						 12 => "analog13",  13 => "analog14",  14 => "analog15",
				         15 => "analog16",  34 => "power1",    35 => "kWh1",
						 36 => "MWh1",      37 => "power2",    38 => "kWh2",
						 39 => "MWh2",      40 => "power2",    41 => "kWh2",
					     42 => "MWh2"));


	private $units = array(
		"uvr" =>    array(1 => "°C",    2 => "W/m2",    3 => "l/h",    4 => "Sek",
	                      5 => "Min",   6 => "l/Pulse", 7 => "Kelvin", 8 => "%",
					     10 => "kW",   11 => "kWh",     12 => "MWh",  13 => "V",
					     14 => "mA",   15 => "Stunden", 16 => "Tage", 17 => "Pulse",
					     19 => "km/h", 20 => "Hz",      21=> "l/min", 22 => "bar"),
		"canbc" =>  array(1 => "°C",    3 => "l/h",      4 => "kW",    5 => "kWh",
		                  6 => "MWh"));

	const LONG_SIGN = 0x80000000;
	const SHORT_SIGN = 0x8000;
	const LONG_MASK = 0xFFFFFFFF;
	const SHORT_MASK = 0xFFFF;
	const POWER = 0x0a;
	const ENERGY = 0x0b;
	const UVR = 0x80;
	const UVRX2 = 0x87;
	const ESR21 = 0x70;
	const CAN_BC = 0x84;
	const CAN_EZ = 0x85;


	public function __construct($string) {
		$this->data = unpack("Csource/Cframe/Ccanid/Cdevice/C3id/C/Cunit/Cformat/Csize/C7", substr($string, 0, 18));
		$this->data["desc"] = trim(substr($string, 18));
	}

	public function getSize() {
		return $this->data["size"];
	}

	public function getFormat() {
		switch($this->data["size"]) {
			case 1: return "C".$this->getFrameId().$this->getName();
			case 2: return "v".$this->getFrameId().$this->getName();
			case 4: return "V".$this->getFrameId().$this->getName();
		}
	}

	public function getValue($value) {
		if ($this->data["size"]==1) {
			return $value;
		}
		if ($this->data["size"]==2 && $value&self::SHORT_SIGN) {
			$value = -(($value^self::SHORT_MASK)+1);
		}
		if ($this->data["size"]==4 && $value&self::LONG_SIGN) {
			$value = -(($value^self::LONG_MASK)+1);
		}

		if ($this->data["device"] == self::UVR || $this->data["device"] == self::CAN_EZ) {
			switch ($this->data["unit"]) {
				case self::ENERGY:
					return $value/10;
				case self::POWER:
					return $value/100;
			}
		}

		switch ($this->data["format"]) {
			case 1:
				return $value/10;
			case 2:
				return $value/100;
			default:
				return $value;
		}
	}

	public function getName() {
		switch ($this->data["device"]) {
			case self::UVR:
				return $this->mapping["uvr"][$this->data["id1"]];
			case self::UVRX2:
				return $this->mapping["uvrx2"][$this->data["id2"]][$this->data["id1"]%16];
			case self::ESR21:
				return $this->mapping["esr21"][$this->data["id1"]];
			case self::CAN_EZ:
				return $this->mapping["canez"][$this->data["id1"]];
			case self::CAN_BC:
				return $this->mapping["canbc"][$this->data["id1"]];
			default:
				throw new Exception(sprintf("Device with code 0x%02x and CAN id %d not supported.", $this->data["device"], $this->data["canid"]));
		}
	}

	public function getFrameId() {
		switch ($this->data["device"]) {
			case self::UVR:
			case self::ESR21:
				return "f".$this->data["frame"].":".$this->data["canid"];
			case self::UVRX2:
				return "f".(floor($this->data["id1"]/16)).":".$this->data["canid"];
			case self::CAN_BC:
				if($this->data["id1"] < 13) {
					return "f".$this->data["frame"].":".$this->data["canid"].":1";
				}
				else {
					return "f".$this->data["frame"].":".$this->data["canid"].":2";
				}
			case self::CAN_EZ:
				if($this->data["id1"] > 39) {
					return "f".($this->data["frame"]+1).":".$this->data["canid"];
				}
				else {
					return "f".$this->data["frame"].":".$this->data["canid"];
				}
			default:
					throw new Exception(sprintf("Device with code 0x%02x and CAN id %d not supported.", $this->data["device"], $this->data["canid"]));
			}
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
		$temp = array();
		$data = unpack($this->getFormatString(), $string);
		foreach($this->datasets as $dataset) {
			$frameId = $dataset->getFrameId();
			$name = $dataset->getName();
			$value = $dataset->getValue($data[$frameId.$name]);
			$temp[$frameId][$name] = $value;
		}

		$result = array();
		$i = 1;
		foreach($temp as $frame) {
			$result["frame$i"]["date"] = sprintf("20%02d-%02d-%02d %02d:%02d:%02d",
				              $data["years"],   $data["months"],
							  $data["days"],    $data["hours"],
							  $data["minutes"], $data["seconds"]);
			if(array_key_exists("kWh1", $frame)) {
				$result["frame$i"]["energy1"] = $frame["kWh1"]+$frame["MWh1"]*1000;
			}
			else {
				$result["frame$i"]["energy1"] = "NULL";
			}
			if(array_key_exists("kWh2", $frame)) {
				$result["frame$i"]["energy2"] = $frame["kWh2"]+$frame["MWh2"]*1000;
			}
			else {
				$result["frame$i"]["energy2"] = "NULL";
			}
			foreach(array("analog1", "analog2","analog3","analog4","analog5","analog6","analog7","analog8","analog9", "analog10", "analog11", "analog12", "analog13", "analog14", "analog15", "analog16", "digital1", "digital2","digital3","digital4","digital5","digital6","digital7","digital8","digital9", "digital10", "digital11", "digital12", "digital13", "digital14", "digital15", "digital16","speed1","speed2","speed3","speed4","power1","power2", "kWh1", "MWh1", "kWh2", "MWh2") as $key) {
				if(array_key_exists($key, $frame)) {
					$result["frame$i"][$key] = $frame[$key];
				} else {
					$result["frame$i"][$key] = "NULL";
				}
			}
			$i++;
		}

		return $result;
	}
}

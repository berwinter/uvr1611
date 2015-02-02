<?php
/**
 * Parses a binary string containing a dataset
 * 
 * Provides access to the values of a dataset as object properties
 *
 * @copyright  Copyright (c) Bertram Winter bertram.winter@gmail.com
 * @license    GPLv3 License
 */
 
include_once("lib/config.inc.php");
/* when it is too slow, Config must be a value from the constructor 
* --> changes also in uvr1611-connection.inc.php necessary
*/
 
class Parser
{
	/**
	 * Parser constant
	 */
	const SIGN_BIT = 0x8000;
	const POSITIVE_VALUE_MASK = 0x00000FFF;
	const NEGATIVE_VALUE_MASK = 0x0000AFFF;
	const DIGITAL_ON = 1;
	const DIGITAL_OFF = 0;
	const SPEED_ACTIVE = 0x80;
	const SPEED_MASK = 0x1F;
	const TYPE_MASK = 0x7000;
	const TYPE_NONE = 0x0000;
	const TYPE_DIGITAL = 0x1000;
	const TYPE_TEMP = 0x2000;
	const TYPE_VOLUME = 0x3000;
	const TYPE_RADIATION = 0x4000;
	const TYPE_RAS = 0x7000;
	const RAS_POSITIVE_MASK = 0x000001FF;
	const INT32_MASK = 0xFFFFFFFF;
	const INT32_SIGN = 0x80000000;
	const RAS_NEGATIVE_MASK = 0xFFFFFE00;
	const RAS_NORMAL   = 0x200;
	const RAS_LOWERING = 0x400;
	const RAS_STANDBY  = 0x600;

	const DEBUG = 0;
	
	/**
	 * Constructor
	 * Parses thourgh the dataset and add values as properties
	 * @param string $data binary string containing the dataset
	 */
	public function __construct($data)
	{
		$configRASMode = Config::getInstance()->RASMode;
		if (self::DEBUG ==1) {
			echo "configRASMode: ";		
			echo "normal= ".$configRASMode->normal.", ";
			echo "standby= ".$configRASMode->standby.", ";
			echo "lowering= ".$configRASMode->lowering.", ";
			echo "timer= ".$configRASMode->timer.", ";
			echo "notUsed= ".$configRASMode->notUsed."\n";
		}
		// check if dataset contains time information
		// (fetched from bootloader storage)
		if(strlen($data) == 61) {
			$package = unpack("C55/Cseconds/Cminutes/"
					         ."Chours/Cdays/Cmonths/Cyears",$data);
			$this->date = sprintf("20%02d-%02d-%02d %02d:%02d:%02d",
					              $package["years"],   $package["months"],
								  $package["days"],    $package["hours"],
								  $package["minutes"], $package["seconds"]);

		}
		else {
//	echo "datum should be added\n";
			$this->date = date("Y-m-d H:i:s");
//	echo $this->date."\n";

		}

		// unpack binary string
		//$package = unpack("v16analog/vdigital/C4speed/Cactive".
		//				  "/Vpower1/vkWh1/vMWh1/Vpower2/vkWh2/vMWh2",$data);
		$package = unpack("v16analog/Sdigital/C4speed/Cactive".
						  "/Vpower1/vkWh1/vMWh1/Vpower2/vkWh2/vMWh2",$data);
		// 16 Analog channels
		for($i=1; $i<=16; $i++) {
			$key = ("analog".$i);
			$this->$key = self::convertAnalog($package["analog".$i]);
		}
		
		// 16 Digital channels (only 13 in use)
		for($i=1; $i<=16; $i++) {
			$key = ("digital".$i);
			$this->$key = self::convertDigital($package["digital"],$i);
		}
		
		// 4 speeds
		for($i=1; $i<=4; $i++) {
			$key = ("speed".$i);
			$this->$key = self::convertSpeed($package["speed".$i]);
		}
		
		// 2 energy values
		for($i=1; $i<=2; $i++) {
			$key = ("energy".$i);
			$this->$key = self::convertEnergy($package["MWh".$i],
											  $package["kWh".$i],
											  $package["active"], $i);
		}
		
		// 2 power values
		for($i=1; $i<=2; $i++) {
			$key = ("power".$i);
			$this->$key = self::convertPower($package["power".$i],
					                         $package["active"], $i);
		}
	
	// 16 Analog channels check RAS-Mode
		for($i=1; $i<=16; $i++) {
			$key = ("RASMode".$i);
			$this->$key = self::convertRASMode($package["analog".$i], $configRASMode);
		}	
	
	}
	
	/**
	 * Provides access to the configuration properties
	 * @param string $name Property name
	 * @throws Exception Property not found
	 */
	public function __get($name)
	{
		throw new Exception('call to undefined property: '.$name);
	}
	
	/**
	 * Convert the int value to a float
	 * @param int $value
	 * @return number
	 */
	private static function convertAnalog($value)
	{
		
	if (self::DEBUG > 1){
		echo "convertAnalog:" .$value;
		echo "\n";
		}
		
		// calculate result value
		//$result = $value & self::POSITIVE_VALUE_MASK;
		if($value & self::SIGN_BIT) {
			$result = -(($result ^ self::NEGATIVE_VALUE_MASK)+1);
			if (self::DEBUG >1) echo "negative value $result\n";
		}
		
		else {
			$result = $value & self::POSITIVE_VALUE_MASK;
			if (self::DEBUG >1) echo "positive value $result\n";			
		}
		// choose type
		switch($value & self::TYPE_MASK)
		{
			case self::TYPE_TEMP:
				if (self::DEBUG >1) echo "TYPE_TEMP\n";
				return $result/10;
			case self::TYPE_VOLUME:
				if (self::DEBUG >1) echo "TYPE_VOLUME\n";			
				return $result*4;
			case self::TYPE_DIGITAL:
				if (self::DEBUG >1) echo "TYPE_DIGITAL\n";			
				if($value & self::SIGN_BIT) {
					return 1;
				}
				else {
					return 0;
				}
			case self::TYPE_RAS:
				//$result = $value & self::RAS_POSITIVE_MASK;
				if (self::DEBUG >1) echo "TYPE_RAS\n";	
				if($value & self::SIGN_BIT) {
					$convValue = ($value | self::RAS_NEGATIVE_MASK)/10;
					if (self::DEBUG ==1) echo "RAS_NEGATIVE_MASK: ".$convValue."\n";
				//	return $convValue;
					return (-(($result ^ self::RAS_POSITIVE_MASK)+1)/10);
				}

				else {
					$convValue = ($value & self::RAS_POSITIVE_MASK)/10;					
					if (self::DEBUG ==1) echo "RAS_POSITIVE_MASK - value: ".$value."\n";					
					if (self::DEBUG ==1) echo "RAS_POSITIVE_MASK: ".$convValue."\n";
//					return $convValue;					
					return (($value & self::RAS_POSITIVE_MASK)/10);
				}
			case self::TYPE_RADIATION:
			case self::TYPE_NONE:
			default:
				return $result;
		}
	}
	
	/**
	 * Check if bit is set on a given position
	 * @param int $value
	 * @param int $position
	 * @return number
	 */
	private static function convertDigital($value, $position)
	{
		if($value & (0x1<<($position-1))) {
			return self::DIGITAL_ON;
		}
		else {
			return self::DIGITAL_OFF;
		}
	}
	
	/**
	 * Check if speed is activated and returns its actual value
	 * @param int  $value
	 * @return NULL|boolean
	 */
	private static function convertSpeed($value)
	{
		if($value & self::SPEED_ACTIVE) {
			return "NULL";
		}
		else {
			return ($value & self::SPEED_MASK);
		}
	}
	
	/**
	 * Checks if heat meter is activated on a given position
	 * and returns its energy
	 * @param int $value
	 * @param int $active
	 * @param int $position
	 * @return number|NULL
	 */
	private static function convertEnergy($MWh, $kWh, $active, $position)
	{
		if($active & $position) {
			return ($MWh*1000 + $kWh/10);
		}
		else{
			return "NULL";
		}
	}
	
	/**
	 * Checks if heat meter is activated on a given position
	 * and returns its power
	 * @param int $value
	 * @param int $active
	 * @param int $position
	 * @return number|NULL
	 */
	private static function convertPower($value, $active, $position)
	{
		if($active & $position) {
		//	if($value & self::INT32_SIGN) {
		//		return -(($value ^ self::INT32_MASK)+1) / 2560;
		//	}
		//	else {
		//		return ($value/2560);
		//	}
		return ($value/2560);
		}
		else {
			return "NULL";
		}
	}
	
	/**
	 * check if the input is a RAS and get the textual mode off the RAS
	 * @param int $value, class $config
	 * @return string
	 	const RAS_NORMAL   = 0x200;
	const RAS_LOWERING = 0x400;
	const RAS_STANDBY  = 0x600;
	 */
	private static function convertRASMode($value, &$config)
	{
		if (self::DEBUG ==2) {
			echo "configRASMode: ";		
			echo "normal= ".$config->normal.", ";
			echo "standby= ".$config->standby.", ";
			echo "lowering= ".$config->lowering.", ";
			echo "timer= ".$config->timer.", ";
			echo "notUsed= ".$config->notUsed."\n";
	
			echo "RASMODE value = ".$value." , ";	
		}
		// choose type
		switch($value & self::TYPE_MASK)
		{
			case self::TYPE_RAS:
				if (($value & self::RAS_STANDBY) == self::RAS_STANDBY) {
					if (self::DEBUG ==1) echo "standby = ".$config->standby."\n";			
					return $config->standby;
				} else if (($value & self::RAS_LOWERING) == self::RAS_LOWERING) {
					if (self::DEBUG ==1) echo "lowering = ".$config->lowering."\n";			
					return $config->lowering;
				} else if(($value & self::RAS_NORMAL) == self::RAS_NORMAL) {
					if (self::DEBUG ==1) echo "Normal = ".$config->normal."\n";			
					return $config->normal;	
				} else {
					if (self::DEBUG ==1) echo "timer = ".$config->timer."\n";			
					return $config->timer;
				}
			default:
				return $config->notUsed;											
		}
	}
	
}

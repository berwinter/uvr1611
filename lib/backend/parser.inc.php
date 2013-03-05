<?php
/**
 * Parses a binary string containing a dataset
 * 
 * Provides access to the values of a dataset as object properties
 *
 * @copyright  Copyright (c) Bertram Winter bertram.winter@gmail.com
 * @license    GPLv3 License
 */
class Parser
{
	/**
	 * Parser constant
	 */
	const SIGN_BIT = 0x8000;
	const POSITIVE_VALUE_MASK = 0x00000FFF;
	const NEGATIVE_VALUE_MASK = 0xFFFFF000;
	const DIGITAL_ON = 1;
	const DIGITAL_OFF = 0;
	const SPEED_ACTIVE = 0x80;
	const SPEED_MASK = 0x1F;
	
	/**
	 * Constructor
	 * Parses thourgh the dataset and add values as properties
	 * @param string $data binary string containing the dataset
	 */
	public function __construct($data)
	{
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

		// unpack binary string
		$package = unpack("v16analog/Sdigital/C4speed/Cactive".
						  "/Vpower1/Venergy1/Vpower2/Venergy2",$data);
		
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
			$this->$key = self::convertEnergy($package["energy".$i],
											  $package["active"], $i);
		}
		
		// 2 power values
		for($i=1; $i<=2; $i++) {
			$key = ("power".$i);
			$this->$key = self::convertPower($package["power".$i],
					                         $package["active"], $i);
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
		if($value & self::SIGN_BIT) {
			return (($value | self::NEGATIVE_VALUE_MASK)/10);
		}
		else {
			return (($value & self::POSITIVE_VALUE_MASK)/10);
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
	private static function convertEnergy($value, $active, $position)
	{
		if($active & $position) {
			return ($value/10);
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
			return ($value/2560);
		}
		else {
			return "NULL";
		}
	}
}
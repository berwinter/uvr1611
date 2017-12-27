<?php
/**
 * Parses a binary string containing a dataset
 * 
 * Provides access to the values of a dataset as object properties
 *
 * @copyright  Copyright (c) Bertram Winter bertram.winter@gmail.com
 * @license    GPLv3 License
 */

class BlnetParser
{
	/**
	 * Parser constant
	 */
	// 1 bit
	const DIGITAL_ON = 1;
	const DIGITAL_OFF = 0;
	// 8 bit
	const SPEED_ACTIVE = 0x80;
	const SPEED_MASK = 0x1F;
	// 16 bit
	const INT16_POSITIVE_MASK = 0xFFFF;
	const SIGN_BIT = 0x8000;
	const POSITIVE_VALUE_MASK = 0x0FFF;
	const TYPE_MASK = 0x7000;
	const TYPE_NONE = 0x0000;
	const TYPE_DIGITAL = 0x1000;
	const TYPE_TEMP = 0x2000;
	const TYPE_VOLUME = 0x3000;
	const TYPE_RADIATION = 0x4000;
	const TYPE_RAS = 0x7000;
	const RAS_POSITIVE_MASK = 0x01FF;
	// 32 bit
	const INT32_MASK = 0xFFFFFFFF;
	const INT32_SIGN = 0x80000000;
	
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
		$package = unpack("v16analog/vdigital/C4speed/Cactive".
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
		// choose type
		switch($value & self::TYPE_MASK)
		{
			case self::TYPE_TEMP:
				return self::calculateValue($value, 0.1);
			case self::TYPE_VOLUME:
				return self::calculateValue($value, 4);
			case self::TYPE_DIGITAL:
				if($value & self::SIGN_BIT) {
					return 1;
				}
				else {
					return 0;
				}
			case self::TYPE_RAS:
				return self::calculateValue($value, 0.1, self::RAS_POSITIVE_MASK);
			case self::TYPE_RADIATION:
			case self::TYPE_NONE:
			default:
				return self::calculateValue($value);
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
			$kWh = self::calculateValue($kWh, 0.1, self::INT16_POSITIVE_MASK);
			return ($MWh*1000 + $kWh);
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
			return self::calculateValue($value, 1 / 2560, self::INT32_MASK, self::INT32_SIGN);
		}
		else {
			return "NULL";
		}
	}

	/**
	 * Calculate value with multiplier and positive mask
	 * @param int $value
	 * @param float $multiplier
	 * @param int $positivemask
	 * @return value
	 */
	private static function calculateValue($value, $multiplier = 1, $positivemask = self::POSITIVE_VALUE_MASK, $signbit = self::SIGN_BIT)
	{
		$result = $value & $positivemask;
		if($value & $signbit) {
			$result = -(($result ^ $positivemask) + 1);
		}
		return $result * $multiplier;
	}
}

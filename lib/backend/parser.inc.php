<?php

class Parser
{
	public function __construct($data)
	{
		if(strlen($data) == 65)
		{
			$package = unpack("C3timestamp/v16analog/Sdigital/C4speed/Cactive/Vpower1/Venergy1/Vpower2/Venergy2/Cseconds/Cminutes/Chours/Cdays/Cmonths/Cyears",$data);
			$this->timestamp = $package["timestamp1"] + ($package["timestamp2"]<<8) + ($package["timestamp3"]<<16);
			$this->date = sprintf("20%02d-%02d-%02d %02d:%02d:%02d", $package["years"], $package["months"], $package["days"],$package["hours"], $package["minutes"],$package["seconds"]);
		}
		else if(strlen($data) == 57)
		{
			$package = unpack("C/v16analog/Sdigital/C4speed/Cactive/Vpower1/Venergy1/Vpower2/Venergy2",$data);
		}
		else
		{
			throw new Exception("Unknown data format!");
		}
		
		for($i=1; $i<=16; $i++)
		{
			$key = ("analog".$i);
			$this->$key = self::convertAnalog($package["analog".$i]);
		}
		
		for($i=1; $i<=16; $i++)
		{
			$key = ("digital".$i);
			$this->$key = self::convertDigital($package["digital"],$i);
		}
		
		for($i=1; $i<=4; $i++)
		{
			$key = ("speed".$i);
			$this->$key = self::convertSpeed($package["speed".$i]);
		}
		
		for($i=1; $i<=2; $i++)
		{
			$key = ("energy".$i);
			$this->$key = self::convertEnergy($package["energy".$i],$package["active"],$i);
		}
		
		for($i=1; $i<=2; $i++)
		{
			$key = ("power".$i);
			$this->$key = self::convertPower($package["power".$i],$package["active"],$i);
		}
	
	}
	
	public function __get($name)
	{
		throw new Exception('call to undefined property: ' . $name);
	}
	
	private static function convertAnalog($value)
	{
		if($value & 0x8000)
		{
			return (($value | 0xFFFFF000)/10);
		}
		else
		{
			return (($value & 0xFFF)/10);
		}
	}
	
	private static function convertDigital($value, $i)
	{
		if($value & (0x1<<($i-1)))
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}
	
	private static function convertSpeed($value)
	{
		if($value & 0x80)
		{
			return NULL;
		}
		else
		{
			return ($value & 0x1F);
		}
	}
	
	private static function convertEnergy($value, $active, $i)
	{
		if($active & $i)
		{
			return ($value/10);
		}
		else
		{
			return NULL;
		}
	}
	
	private static function convertPower($value, $active, $i)
	{
		if($active & $i)
		{
			return ($value/2560);
		}
		else
		{
			return NULL;
		}
	}
}
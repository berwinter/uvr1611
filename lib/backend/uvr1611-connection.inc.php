<?php
include_once("lib/config.inc.php");
include_once("lib/backend/parser.inc.php");
include_once("lib/backend/constants.inc.php");

class Uvr1611
{
	
	public static $instance;
	
	private $config;
	private $sock;
	private $count=0;
	private $address=0;

	public static function getInstance()
	{
		if (null == self::$instance) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	private function __construct(array $options=array())
	{
		$this->config = Config::getInstance()->uvr1611;
		
	}

	public function getLatest()
	{
		create_pid();
		$this->checkMode();
		
		$cmd = GET_LATEST."\x01";
		
		for($i=0; $i<MAX_RETRYS; $i++)
		{
			$data = $this->query($cmd, 57);
			
			if($this->checksum($data))
			{
				$binary = unpack("C*",$data);
				if($binary[1] == WAIT_TIME)
				{
					sleep($binary[2]);
				}
				else
				{	
					close_pid();
					return new Parser($data);
				}
			}
		}
		close_pid();
		throw new Exception("Could not get latest data!");
	}
	
	public function resetData()
	{
		create_pid();
		$this->checkMode();
		$this->count = 0;
		$this->address = 0;
		close_pid();
	}
	
	
	public function fetchData()
	{
		if($this->count > 0)
		{
			create_pid();
			$this->checkMode();
			
			$address1 = $this->address & 0xFF;
			$address2 = ($this->address & 0xFF00)>>7;
			$address3 = ($this->address & 0xFF0000)>>15;
			
			$cmd = pack("C6", READ_DATA, $address1, $address2, $address3, 1,
							  READ_DATA + 1 + $address1 + $address2 + $address3);
			
			//print_r(unpack("C*", $cmd));

			$data = $this->query($cmd, 65);
			
			//print_r(unpack("C*", $data));
			
			if($this->checksum($data))
			{
				$this->address += 64;
				$this->count--;
				close_pid();
				return new Parser($data);
			}
			close_pid();
			throw new Exception("Could not get data!");
		}
	}
	
	public function getCount()
	{
		if($this->count == 0)
		{
			create_pid();
			$this->checkMode();
			
			$data = $this->query(GET_HEADER, 14);
			
			if($this->checksum($data))
			{
				$binary = unpack("C5/CnumberOfFrames/C*",$data);
				$binary = unpack("Ctype/Cversion/C3timestamp/CnumberOfFrames/C".$binary["numberOfFrames"]."/C3startaddress/C3endaddress/Cchecksum",$data);
				
				if($binary["startaddress3"] != 0xFF ||
				   $binary["startaddress2"] != 0xFF ||
				   $binary["startaddress1"] != 0xFF ||
				   $binary["endaddress3"] != 0xFF ||
				   $binary["endaddress2"] != 0xFF ||
				   $binary["endaddress1"] != 0xFF)
				{
					$startaddress = ($binary["startaddress3"] << 15) + ($binary["startaddress2"] << 7) + $binary["startaddress1"];
					$endaddress = ($binary["endaddress3"] << 15) + ($binary["endaddress2"] << 7) + $binary["endaddress1"];
					$this->count = (($endaddress - $startaddress) / 64) + 1;
					$this->address = $startaddress;
				}
			}
			close_pid();
		}

		return $this->count;
	}
	
	private function checkMode()
	{

		$mode = $this->query(GET_MODE, 1);
		if($mode != CAN_MODE)
		{
			throw new Exception('BL-Net is not in CAN mode!');
		}
	}
	
	private function connect()
	{
		$this->sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		socket_connect($this->sock, $this->config->address, $this->config->port);
	}
	
	private function disconnect()
	{
		socket_close($this->sock);
	}
	
	private function checksum($data)
	{
		$binary = unpack("C*",$data);

		$sum = 0;
		$checksum = array_pop($binary);
		
		foreach($binary as $byte)
		{
			$sum += $byte;	
		}
		
		
		if(($sum % 256) == $checksum)
		{
			return true;
		}
		return false;
	}
	
	private function query($cmd, $length)
	{
		$this->connect();
		
		if(strlen($cmd) == socket_write($this->sock, $cmd, strlen($cmd)))
		{
			$data = socket_read($this->sock, $length, PHP_BINARY_READ);
			
			$this->disconnect();
			return $data;
		}

		$this->disconnect();
		throw new Exception('Error while querying command!\nCommand: '.bin2hex($cmd));

	}
}


function create_pid() {
	$path = '/tmp/uvr1611-logger.pid';
	if(file_exists($path))
	{
		if(time() > (filemtime($path) + 3600)) {
			$pid = file_get_contents($path);
			exec("kill $pid");
		}
		else
		{
			throw new Exception("Another process is excessing the bl-net!");
		}

	}
	file_put_contents($path, getmypid());
}

function close_pid() {
	unlink('/tmp/uvr1611-logger.pid');
}
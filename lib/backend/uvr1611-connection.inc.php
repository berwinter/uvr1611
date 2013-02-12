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
	private $mode;
	private $addressInc = 64;
	private $actualSize = 57;
	private $fetchSize = 65;
	private $canFrames = 1;

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
		$this->checkMode();
		
	}

	public function getLatest()
	{
		$this->getCount();
		create_pid();
		
		$cmd = pack("C2",GET_LATEST,1);
		
		for($i=0; $i<MAX_RETRYS; $i++)
		{
			$data = $this->query($cmd, $this->actualSize);
			
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
					return $this->splitLatest($data);
				}
			}
		}
		close_pid();
		throw new Exception("Could not get latest data!");
	}
	
	public function resetData()
	{
		create_pid();
		$this->count = 0;
		$this->address = 0;
		close_pid();
	}
	
	
	public function fetchData()
	{
		if($this->count > 0)
		{
			create_pid();
			
			$address1 = $this->address & 0xFF;
			$address2 = ($this->address & 0x7F00)>>7;
			$address3 = ($this->address & 0xFF8000)>>15;
			
			$cmd = pack("C6", READ_DATA, $address1, $address2, $address3, 1,
							  READ_DATA + 1 + $address1 + $address2 + $address3);
			
			$data = $this->query($cmd, $this->fetchSize);
			
			if($this->checksum($data))
			{

				$this->address += $this->addressInc;
				$this->count--;
				close_pid();
				return $this->splitDatasets($data);
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
			$data = $this->query(GET_HEADER, 21);
			
			if($this->checksum($data))
			{
				switch($this->mode)
				{
					case CAN_MODE:
						$binary = unpack("C5/CnumberOfFrames/C*",$data);
						$binary = unpack("Ctype/Cversion/C3timestamp/CnumberOfFrames/C".$binary["numberOfFrames"]."/C3startaddress/C3endaddress/Cchecksum",$data);
						$this->addressInc = 64 * $binary["numberOfFrames"];
						$this->canFrames = $binary["numberOfFrames"];
						$this->actualSize = 57;
						$this->fetchSize = 4+61*$this->canFrames;
						break;
					case DL_MODE:
						$binary = unpack("C5/Cdevice1/C3startaddress/C3endaddress/Cchecksum",$data);
						$this->addressInc = 64;
						$this->actualSize = 57;
						$this->fetchSize = 65;
						break;
					case DL2_MODE:
						$binary = unpack("C5/Cdevice1/Cdevice2/C3startaddress/C3endaddress/Cchecksum",$data);
						$this->addressInc = 128;
						$this->actualSize = 113;
						$this->fetchSize = 126;
						break;
					
				}
				
				if($binary["startaddress3"] != 0xFF ||
				   $binary["startaddress2"] != 0xFF ||
				   $binary["startaddress1"] != 0xFF ||
				   $binary["endaddress3"] != 0xFF ||
				   $binary["endaddress2"] != 0xFF ||
				   $binary["endaddress1"] != 0xFF)
				{
					$startaddress = ($binary["startaddress3"] << 15) + ($binary["startaddress2"] << 7) + $binary["startaddress1"];
					$endaddress = ($binary["endaddress3"] << 15) + ($binary["endaddress2"] << 7) + $binary["endaddress1"];
					$this->count = (($endaddress - $startaddress) / $this->addressInc) + 1;
					$this->address = $startaddress;
				}
			}
			close_pid();
		}

		return $this->count;
	}
	
	private function checkMode()
	{

		$this->mode = $this->query(GET_MODE, 1);
		switch($this->mode)
		{
			case CAN_MODE:
			case DL_MODE:
			case DL2_MODE:
				return;
		}
		throw new Exception('BL-Net mode is not supported!');
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
			$data = "";
			do {
				$return = socket_read($this->sock, $length, PHP_BINARY_READ);
				$data .= $return;
			}
			while(strlen($return)>32 && strlen($data) < $length);
			
			$this->disconnect();
			return $data;
		}

		$this->disconnect();
		throw new Exception('Error while querying command!\nCommand: '.bin2hex($cmd));

	}
	
	private function splitDatasets($data)
	{
		$frames = array();
		switch($this->mode)
		{
			case CAN_MODE:
				for($i=0;$i<$this->canFrames;$i++)
				{
					$frames[] = new Parser(substr($data, 3+61*$i, 61));
				}
				break;
			case DL_MODE:
				$frames[] = new Parser(substr($data, 0, 61));
				break;
			case DL2_MODE:
				$frames[] = new Parser(substr($data, 0, 61));
				$frames[] = new Parser(substr($data, 3+61, 61));
				break;
		}

		return $frames;
	}
	
	private function splitLatest($data)
	{
		$frames = array();
	
		switch($this->mode)
		{
			case CAN_MODE:
				for($i=0;$i<$this->canFrames;$i++)
				{
					$frames[] = new Parser(substr($data, 1+56*$i, 56));
				}
				break;
			case DL_MODE:
				$frames[] = new Parser(substr($data, 1, 56));
				break;
			case DL2_MODE:
				$frames[] = new Parser(substr($data, 1, 56));
				$frames[] = new Parser(substr($data,1+56, 56));
				break;
		}
	
		return $frames;
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
			throw new Exception("Another process is accessing the bl-net!");
		}

	}
	file_put_contents($path, getmypid());
}

function close_pid() {
	unlink('/tmp/uvr1611-logger.pid');
}
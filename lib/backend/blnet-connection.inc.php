<?php
/**
 * BL-net Connection
 *
 * Provides access to the bootloader for stored datasets and latest values
 *
 * @copyright  Copyright (c) Bertram Winter bertram.winter@gmail.com
 * @license    GPLv3 License
 */
include_once("lib/config.inc.php");
include_once("lib/backend/uvr1611.inc.php");
include_once("lib/backend/blnet-parser.inc.php");
include_once("lib/backend/database.inc.php");
error_reporting(0);

class BlnetConnection
{
	/**
     * Constants for the UVR Communication
	 */
	const CAN_MODE = "\xDC";
	const DL_MODE = "\xA8";
	const DL2_MODE = "\xD1";
	const GET_MODE = "\x81";
	const GET_HEADER = "\xAA";
	const GET_LATEST = 0xAB;
	const READ_DATA = 0xAC;
	const END_READ = "\xAD";
	const RESET_DATA = "\xAF";
	const WAIT_TIME = 0xBA;
	const MAX_RETRYS = 10;
	const DATASET_SIZE = 61;
	const LATEST_SIZE = 56;
	
	
	/**
	 * Privates
	 */
	private $config;
	private $debug;
	private $sock = null;
	private $count=-1;
	private $address=0;
	private $mode;
	private $addressInc = 64;
	private $addressEnd;
	private $actualSize = 57;
	private $fetchSize = 65;
	private $canFrames = 1;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$config = Config::getInstance();
		$this->config = $config->uvr1611;
		$this->debug = $config->app->debug;
		$this->checkMode();
	}

	/**
	 * Get the actuell values from the bootloader
	 * @throws Exception
	 * @return Parser
	 */
	public function getLatest()
	{
		$this->connect();
		$this->getCount();
		create_pid();
		$frames = array();
		$info = array();
		// for all can frames
		for($j=1; $j<=$this->canFrames; $j++) {
			// build command
			$cmd = pack("C2",self::GET_LATEST,$j);
			// try 4 times to get values
			for($i=0; $i<self::MAX_RETRYS; $i++) {
				$data = $this->query($cmd, $this->actualSize);
				
				if($this->checksum($data)) {
					$binary = unpack("C*",$data);
					if($binary[1] == self::WAIT_TIME) {
						$info["sleep"]["frame$j"][] = $binary[2];
						$this->disconnect();
						// wait some seconds for data
						sleep($binary[2]);
						$this->connect();
					}
					else {	
						$info["got"]["frame$j"] = $i;
						$frames = array_merge($frames, $this->splitLatest($data, "frame$j"));
						break;
					}
				}
				if($i==self::MAX_RETRYS-1) {
					$frames["frame$k"] = "timeout";
				}
			}
		}
		close_pid();
		$this->disconnect();
		if(count($frames)>0) {
			$frames["time"] = date("H:i:s");
			if($this->debug) {
				$frames["info"] = $info;
			}
			return $frames;
		}
		throw new Exception("Could not get latest data.");
	}
	
	/**
	 * Start read on the bootloader
	 */
	public function startRead()
	{
		create_pid();
		return $this->getCount();
	}
	
	/**
	 * End read and reset memory on the bootloader
	 */
	public function endRead($success = true)
	{
		$this->connect();
		// send end read command
		if($this->query(self::END_READ, 1) != self::END_READ) {
			throw new Exception("End read command failed.");
		}
		// reset data if configured
		if($success && $this->config->reset) {
			if($this->query(self::RESET_DATA, 1) != self::RESET_DATA) {
				throw new Exception("Could not reset memory.");
			}
		}
		$this->count = -1;
		$this->address = 0;
		close_pid();
		$this->disconnect();
	}
	
	
	/**
	 * Fetch datasets from bootloader memory
	 * @throws Exception Checksum error
	 * @return Parser
	 */
	public function fetchData()
	{
		if($this->count > 0) {
			$this->connect();
			
			// build address for bootloader
			$address1 = $this->address & 0xFF;
			$address2 = ($this->address & 0x7F00)>>7;
			$address3 = ($this->address & 0xFF8000)>>15;
			
			// build command
			$cmd = pack("C6", self::READ_DATA, $address1, $address2, $address3, 1,
							  self::READ_DATA + 1 + $address1 + $address2 + $address3);
			
			$data = $this->query($cmd, $this->fetchSize);
			
			if($this->checksum($data)) {
				// increment address
				$this->address -= $this->addressInc;
				if($this->address < 0)
					$this->address = $this->addressEnd;
				$this->count--;
				return $this->splitDatasets($data);
			}
			throw new Exception("Could not get data.");
		}
	}
	
	/**
	 * Get the number of datasets in the bootloader memory
	 * @return number
	 */
	public function getCount()
	{
		if($this->count == -1) {
			$this->connect();
			$data = $this->query(self::GET_HEADER, 21);
			
			if($this->checksum($data)) {
				switch($this->mode) {
					case self::CAN_MODE:
						$binary = unpack("C5/CnumberOfFrames/C*",$data);
						$binary = unpack("Ctype/Cversion/C3timestamp/CnumberOfFrames/C".
										 $binary["numberOfFrames"].
										 "/C3startaddress/C3endaddress/Cchecksum", $data);
						$this->addressInc = 64 * $binary["numberOfFrames"];
						$this->canFrames = $binary["numberOfFrames"];
						$this->actualSize = 57;
						$this->fetchSize = 4+61*$this->canFrames;
						break;
					case self::DL_MODE:
						$binary = unpack("C5/Cdevice1/C3startaddress/C3endaddress/Cchecksum",
										 $data);
						$this->addressInc = 64;
						$this->canFrames = 1;
						$this->actualSize = 57;
						$this->fetchSize = 65;
						break;
					case self::DL2_MODE:
						$binary = unpack("C5/Cdevice1/Cdevice2/C3startaddress/C3endaddress/Cchecksum",
										 $data);
						$this->addressInc = 128;
						$this->canFrames = 1;
						$this->actualSize = 113;
						$this->fetchSize = 126;
						break;
				}
				
				$this->addressEnd = floor(0x07FFFF/$this->addressInc)*$this->addressInc;
				
				
				// check if address is valid (!= 0xFFFFFF)
				if($binary["startaddress3"] != 0xFF ||
				   $binary["startaddress2"] != 0xFF ||
				   $binary["startaddress1"] != 0xFF ||
				   $binary["endaddress3"] != 0xFF ||
				   $binary["endaddress2"] != 0xFF ||
				   $binary["endaddress1"] != 0xFF)
				{
					// fix addresses
					$startaddress = ($binary["startaddress3"] << 15)
								  + ($binary["startaddress2"] << 7)
								  + $binary["startaddress1"];
					$endaddress = ($binary["endaddress3"] << 15)
							    + ($binary["endaddress2"] << 7)
							    + $binary["endaddress1"];
					
					if($endaddress > $startaddress) {
						// calculate count
						$this->count = (($endaddress - $startaddress)
						             / $this->addressInc) + 1;
					}
					else {
						// calculate count
						$this->count = (($this->addressEnd + $startaddress - $endaddress)
									 / $this->addressInc);
					}

					$this->address = $endaddress;
				}
			}
		}
		return $this->count;
	}
	
	/**
	 * Check if Bootloader Mode is supported
	 * @throws Exception Mode not supported
	 */
	private function checkMode()
	{
		$this->connect();
		$this->mode = $this->query(self::GET_MODE, 1);
		$this->disconnect();

		switch($this->mode) {
			case self::CAN_MODE:
			case self::DL_MODE:
			case self::DL2_MODE:
				return;
		}
		throw new Exception('BL-Net mode is not supported.');
	}
	
	/**
	 * Connect via TCP to the bootloader
	 */
	public function connect()
	{
		if($this->sock == null) {
			$this->sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
			socket_connect($this->sock,
						   $this->config->address,
						   $this->config->port);
		}
	}
	
	/**
	 * Disconnect from the bootloader
	 */
	public function disconnect()
	{
		socket_close($this->sock);
		$this->sock = null;
	}
	
	/**
	 * Verify the checksum
	 * @param string $data Binary string to check
	 * @return boolean
	 */
	private function checksum($data)
	{
		$binary = unpack("C*",$data);
		$sum = 0;
		// pop checksum from string
		$checksum = array_pop($binary);
		// sum up all bytes
		foreach($binary as $byte) {
			$sum += $byte;	
		}
		// verify the checksum
		if(($sum % 256) == $checksum) {
			return true;
		}
		return false;
	}
	
	/**
	 * Send a command to the bootloader and wait for the response
	 * if response is less then 32 bytes long return immediately
	 * @param string $cmd
	 * @param int $length
	 * @throws Exception
	 * @return string Binary
	 */
	private function query($cmd, $length)
	{
		// send command
		if(strlen($cmd) == socket_write($this->sock, $cmd, strlen($cmd))) {
			$data = "";
			// get response until length or less 32 bytes
			do {
				$return = socket_read($this->sock, $length, PHP_BINARY_READ);
				$data .= $return;
			}
			while(strlen($return)>32 && strlen($data) < $length);
			
			return $data;
		}

		$this->disconnect();
		throw new Exception('Error while querying command.\nCommand: '.bin2hex($cmd));
	}
	
	/**
	 * Split a binary string in datasets and parse it (Datasets values)
	 * @param string $data
	 * @return Parser
	 */
	private function splitDatasets($data)
	{
		$frames = array();
		switch($this->mode) {
			case self::CAN_MODE:
				for($i=0;$i<$this->canFrames;$i++) {
					$frames["frame".($i+1)] = (array)(new BlnetParser(substr($data, 3+self::DATASET_SIZE*$i, self::DATASET_SIZE)));
				}
				break;
			case self::DL_MODE:
				$frames["frame1"] = (array)(new BlnetParser(substr($data, 0, self::DATASET_SIZE)));
				break;
			case self::DL2_MODE:
				$frames["frame1"] = (array)(new BlnetParser(substr($data, 0, self::DATASET_SIZE)));
				$frames["frame2"] = (array)(new BlnetParser(substr($data, 3+self::DATASET_SIZE, self::DATASET_SIZE)));
				break;
		}

		$start = 0;
		if($this->mode==self::CAN_MODE) {
			$start = 3;
		}
		if(substr($data, $start, self::DATASET_SIZE-6) == str_repeat("\x00",self::DATASET_SIZE-6)) {
			if($this->debug) {
				$this->logDataset($data, $frames);
			}
			return false;
		}
		else {
			return $frames;	
		}
	}
	
	private function logDataset($data, $frames)
	{
		$text ="";
		$text .= "Address: ".dechex($this->address)."\n";
		$text .= "Count: ".$this->count."\n";
		$text .= "Data: ".bin2hex($data)."\n";
		$text .= "Frame: ".print_r($frames,true)."\n\n";
		$temp = sys_get_temp_dir();
		file_put_contents("$temp/uvr1611-logger.log", $text, FILE_APPEND);
	}
	
	/**
	 * Split a binary string in datasets and parse it (Actuell values)
	 * @param string $data
	 * @return Parser
	 */
	private function splitLatest($data, $frame)
	{
		// connect to database
		$database = Database::getInstance();
		$frames = array();
		switch($this->mode) {
			case self::CAN_MODE:
				$frames[$frame] = (array)(new BlnetParser(substr($data, 1, self::LATEST_SIZE)));
				$current_energy = $database->getCurrentEnergy($frame);
				$frames[$frame]["current_energy1"] = $current_energy[0];
				$frames[$frame]["current_energy2"] = $current_energy[1];
				break;
			case self::DL_MODE:
				$frames["frame1"] = (array)(new BlnetParser(substr($data, 1, self::LATEST_SIZE)));
				$current_energy = $database->getCurrentEnergy("frame1");
				$frames["frame1"]["current_energy1"] = $current_energy[0];
				$frames["frame1"]["current_energy2"] = $current_energy[1];
				break;
			case self::DL2_MODE:
				$frames["frame1"] = (array)(new BlnetParser(substr($data, 1, self::LATEST_SIZE)));
				$current_energy = $database->getCurrentEnergy("frame1");
				$frames["frame1"]["current_energy1"] = $current_energy[0];
				$frames["frame1"]["current_energy2"] = $current_energy[1];
				$frames["frame2"] = (array)(new BlnetParser(substr($data,1+self::LATEST_SIZE, self::LATEST_SIZE)));
				$current_energy = $database->getCurrentEnergy("frame2");
				$frames["frame2"]["current_energy1"] = $current_energy[0];
				$frames["frame2"]["current_energy2"] = $current_energy[1];
				break;
		}
		return $frames;
	}
}

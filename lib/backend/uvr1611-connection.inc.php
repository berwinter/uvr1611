<?php
/**
 * Uvr1611 Connection (Singleton)
 *
 * Provides access to the bootloader for stored datasets and actuell values
 *
 * @copyright  Copyright (c) Bertram Winter bertram.winter@gmail.com
 * @license    GPLv3 License
 */
include_once("lib/config.inc.php");
include_once("lib/backend/parser.inc.php");
include_once("lib/backend/database.inc.php");
include_once("lib/backend/logfile.php");

class Uvr1611
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
	const MAX_RETRYS = 4;
	const DATASET_SIZE = 61;
	const LATEST_SIZE = 56;
	
	
	/**
	 *  Singleton Interface
	 */
	public static $instance;
	public static function getInstance()
	{
		if (null == self::$instance) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	/**
	 * Privates
	 */
	private $config;
	private $sock = null;
	private $count=-1;
	private $address=0;
	private $mode;
	private $addressInc = 64;
	private $addressEnd;
	private $actualSize = 57;
	private $fetchSize = 65;
	private $canFrames = 1;

	private $myAData = array();
	private $logfile;

	/**
	 * Constructor
	 */
	private function __construct()
	{
		//$this->config = Config::getInstance()->uvr1611;
		//$this->checkMode();
		//get instance off logger
		$this->logfile = LogFile::getInstance();
		$this->logfile->writeLogInfo("uvr1611-connection.inc - construct \n");
		$this->allConfig = Config::getInstance();
		$this->logfile->writeLogInfo("uvr1611-connection.inc - allConfig \n");
		$this->config = $this->allConfig->uvr1611;
		$this->logfile->writeLogInfo("uvr1611-connection.inc - config \n");
		$this->checkMode();
		$this->logfile->writeLogInfo("uvr1611-connection.inc - checkMode \n");
	}

	/**
	 * Get the actuell values from the bootloader
	 * @throws Exception
	 * @return Parser
	 */
	public function getLatest()
	{
		$this->logfile->writeLogInfo("uvr1611-connection.inc - getLatest - 1\n");
		$this->connect();-
		$this->getCount();
		create_pid();
		$this->logfile->writeLogInfo("uvr1611-connection.inc - pid created - 2\n");

		$latest = "";
		try{
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
							$this->disconnect();
							// wait some seconds for data
							sleep($binary[2]);
							$this->connect();
						}
						else {	
							$latest .= $data;
							break;
						}
					}
				}
			}
			$this->logfile->writeLogInfo("uvr1611-connection.inc - close pid  - 3\n");

			close_pid();
			$this->disconnect();
			if(strlen($latest)>0) {
				$gdata = $this->splitLatest($latest);
				$this->logfile->writeLogInfo("uvr1611-connection.inc - splitLatest - 4\n");
			} else {
				$this->logfile->writeLogError("uvr1611-connection.inc-getLatest - Could not get latest data from uvr1611\n");				
			}
			return $gdata;	
		}
		catch (Exception $e) {
			close_pid();		
			$this->logfile->writeLogError("uvr1611-connection.inc-getLatest - ".$e->getMessage()."\n");			
//			throw new Exception("Could not get latest data!");
		}
	}
	
	/**
	 * End read and reset memory on the bootloader
	 */
	public function endRead()
	{
		$this->connect();
		create_pid();
		// send end read command
		if($this->query(self::END_READ, 1) != self::END_READ) {
			$this->logfile->writeLogError("uvr1611-connection.inc-endRead - End read command failed.\n");			
			throw new Exception("End read command failed.");
		}
		// reset data if configured
		if($this->config->reset) {
			if($this->query(self::RESET_DATA, 1) != self::RESET_DATA) {
				$this->logfile->writeLogError("uvr1611-connection.inc-endRead - Could not reset memory.\n");						
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
	try{
			if($this->count > 0) {
				$this->logfile->writeLogInfo("uvr1611-connection.inc - fetchData - 1\n");
				$this->connect();
				create_pid();
				$this->logfile->writeLogInfo("uvr1611-connection.inc - fetchData - 2\n");

				// build address for bootloader
				$address1 = $this->address & 0xFF;
				$address2 = ($this->address & 0x7F00)>>7;
				$address3 = ($this->address & 0xFF8000)>>15;
				$this->logfile->writeLogInfo("uvr1611-connection.inc - fetchData - 2-address\n");

				// build command
				$cmd = pack("C6", self::READ_DATA, $address1, $address2, $address3, 1,
								  self::READ_DATA + 1 + $address1 + $address2 + $address3);
				$this->logfile->writeLogInfo("uvr1611-connection.inc - fetchData - 2-pack - cmd: ". $this->address." \n");
				$this->logfile->writeLogInfo("uvr1611-connection.inc - fetchData - 2-pack -fetchsize: ". $this->fetchSize." \n");

				$data = $this->query($cmd, $this->fetchSize);
				$this->logfile->writeLogInfo("uvr1611-connection.inc - fetchData - 3\n");

				if($this->checksum($data)) {
					// increment address
					$this->address -= $this->addressInc;
					if($this->address < 0)
						$this->address = $this->addressEnd;
					$this->count--;
					close_pid();
					return $this->splitDatasets($data);
				}
				$this->logfile->writeLogInfo("uvr1611-connection.inc - fetchData - 4\n");
				close_pid();
				$this->logfile->writeLogError("uvr1611-connection.inc-fetchData 1- ".$e->getMessage()."\n");
				throw new Exception("Could not get data!");
			}

	}
	catch (Exception $e) {
		$this->logfile->writeLogError("uvr1611-connection.inc-fetchData - ".$e->getMessage()."\n");
		close_pid();
		throw new Exception("da hot's was!");
	}
}
	
	/**
	 * Get the number of datasets in the bootloader memory
	 * @return number
	 */
	public function getCount()
	{
		try {
			if($this->count == -1) {
				$this->logfile->writeLogInfo("uvr1611-connection.inc - getCount - 1 \n");
				$this->connect();
				$this->logfile->writeLogInfo("uvr1611-connection.inc - getCount - connect \n");
				create_pid();
				$this->logfile->writeLogInfo("uvr1611-connection.inc - getCount - create_pid \n");
				$data = $this->query(self::GET_HEADER, 21);
				$this->logfile->writeLogInfo("uvr1611-connection.inc - getCount - query \n");

				if($this->checksum($data)) {
					switch($this->mode) {
						case self::CAN_MODE:
							$this->logfile->writeLogInfo("uvr1611-connection.inc - getCount - CAN_MODE \n");
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
							$this->logfile->writeLogInfo("uvr1611-connection.inc - getCount - DL_MODE \n");
							$binary = unpack("C5/Cdevice1/C3startaddress/C3endaddress/Cchecksum",
											 $data);
							$this->addressInc = 64;
							$this->canFrames = 1;
							$this->actualSize = 57;
							$this->fetchSize = 65;
							break;
						case self::DL2_MODE:
							$this->logfile->writeLogInfo("uvr1611-connection.inc - getCount - DL2_MODE \n");
							$binary = unpack("C5/Cdevice1/Cdevice2/C3startaddress/C3endaddress/Cchecksum",
											 $data);
							$this->addressInc = 128;
							$this->canFrames = 1;
							$this->actualSize = 113;
							$this->fetchSize = 126;
//							$this->logfile->writeLogInfo("uvr1611-connection.inc - getCount - binary: ".var_dump($binary));
							$this->logfile->writeLogInfo("uvr1611-connection.inc - getCount - hex: ".bin2hex($data)."\n");
							$this->logfile->writeLogInfo("uvr1611-connection.inc - getCount - dez: ".implode(", ",$binary)."\n");
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
						$this->logfile->writeLogInfo("uvr1611-connection.inc - getCount - valid address\n");

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
							$this->logfile->writeLogInfo("uvr1611-connection.inc - getCount - $endaddress > $startaddress\n");
						}
						else {
							// calculate count
							$this->count = (($this->addressEnd + $startaddress - $endaddress)
										 / $this->addressInc);
							$this->logfile->writeLogInfo("uvr1611-connection.inc - getCount - $endaddress <= $startaddress, $this->addressEnd\n");
						}

						$this->address = $endaddress;
					} 
					else {
						// calculate count
						//$this->count = (($this->addressEnd + $startaddress - $endaddress)
						//			 / $this->addressInc);
											$this->logfile->writeLogError("uvr1611-connection.inc - getCount - NO VALID address \n");
						//exception removed , otherwise the piko data wouldn't be insert in database
						//throw new Exception("uvr1611-connection.inc - getCount - NO VALID address");
					}

					//$this->address = $endaddress;
				}
				close_pid();
			}
			/**
			* -1 is binary 1111111 -> dec 4095
			* avoid to show "insert 4095 sets in Database should be done"
			*/
			if($this->count == -1 || $this->count == 4095) {
				$this->count = 0;
			}
			$this->logfile->writeLogInfo("uvr1611-connection.inc-getCount - count: ".$this->count."\n");
			return $this->count;
		}catch (Exception $e) {
			close_pid();
		//}
		//return $this->count;
					$this->logfile->writeLogError("uvr1611-connection.inc-getCount - ".$e->getMessage()."\n");		
			throw new Exception("getCount, da hot's was!- exception: ".$e->getMessage()."\n");
		}	
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
		$this->logfile->writeLogError("uvr1611-connection.inc-checkMode - BL-Net mode is not supported!\n");		
		throw new Exception('BL-Net mode is not supported!');
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
		$receives = 0;
		// send command
		if(strlen($cmd) == socket_write($this->sock, $cmd, strlen($cmd))) {
			$this->logfile->writeLogInfo("uvr1611-connection.inc - query - Command: ".bin2hex($cmd)."\n");
			$data = "";
			// get response until length or less 32 bytes
			do {
				$return = socket_read($this->sock, $length, PHP_BINARY_READ);
				$data .= $return;
				$receives++;
			}
			while(strlen($return)>32 && strlen($data) < $length);
			$this->logfile->writeLogInfo("uvr1611-connection.inc - query - bytes received: ".$receives."\n");
			return $data;
		}

		$this->disconnect();
		$this->logfile->writeLogError("uvr1611-connection.inc-query - Error while querying command!Command: ".bin2hex($cmd)."\n");
		throw new Exception('Error while querying command! Command: '.bin2hex($cmd));
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
					$frames["frame".($i+1)] = new Parser(substr($data, 3+self::DATASET_SIZE*$i, self::DATASET_SIZE));
				}
				break;
			case self::DL_MODE:
				$frames["frame1"] = new Parser(substr($data, 0, self::DATASET_SIZE));
				break;
			case self::DL2_MODE:
				$frames["frame1"] = new Parser(substr($data, 0, self::DATASET_SIZE));
				$frames["frame2"] = new Parser(substr($data, 3+self::DATASET_SIZE, self::DATASET_SIZE));
				break;
		}
		return $frames;
	}
	
	/**
	 * Split a binary string in datasets and parse it (Actuell values)
	 * @param string $data
	 * @return Parser
	 */
	private function splitLatest($data)
	{
		// connect to database
		//$database = Database::getInstance();
		$frames = array();
		switch($this->mode) {
			case self::CAN_MODE:
				for($i=0;$i<$this->canFrames;$i++) {
					//$frames["frame".($i+1)] = (array)(new Parser(substr($data, 1+(1+self::LATEST_SIZE)*$i, self::LATEST_SIZE)));
					//$current_energy = $database->getCurrentEnergy("frame".($i+1));
					//$frames["frame".($i+1)]["current_energy1"] = $current_energy[0];
					//$frames["frame".($i+1)]["current_energy2"] = $current_energy[1];
						$frames["frame".($i+1)] = new Parser(substr($data, 1+(1+self::LATEST_SIZE)*$i, self::LATEST_SIZE));
				}
				break;
			case self::DL_MODE:
				//$frames["frame1"] = (array)(new Parser(substr($data, 1, self::LATEST_SIZE)));
				//$current_energy = $database->getCurrentEnergy("frame1");
				//$frames["frame1"]["current_energy1"] = $current_energy[0];
				//$frames["frame1"]["current_energy2"] = $current_energy[1];
				$frames["frame1"] = new Parser(substr($data, 1, self::LATEST_SIZE));
				break;
			case self::DL2_MODE:
				//$frames["frame1"] = (array)(new Parser(substr($data, 1, self::LATEST_SIZE)));
				//$current_energy = $database->getCurrentEnergy("frame1");
				//$frames["frame1"]["current_energy1"] = $current_energy[0];
				//$frames["frame1"]["current_energy2"] = $current_energy[1];
				//$frames["frame2"] = (array)(new Parser(substr($data,1+self::LATEST_SIZE, self::LATEST_SIZE)));
				//$current_energy = $database->getCurrentEnergy("frame2");
				//$frames["frame2"]["current_energy1"] = $current_energy[0];
				//$frames["frame2"]["current_energy2"] = $current_energy[1];
				//break;
				$frames["frame1"] = new Parser(substr($data, 1, self::LATEST_SIZE));
				$frames["frame2"] = new Parser(substr($data,1+self::LATEST_SIZE, self::LATEST_SIZE));
				break;
		}
		//$frames["time"] = date("H:i:s");
		return $frames;
	}
}

/**
 * Create a PID file
 * @throws Exception
 */
function create_pid()
{
	$logfile = LogFile::getInstance();

	$path = '/tmp/uvr1611-logger.pid';
	if(file_exists($path)) {
		$pid = file_get_contents($path);
		// if PID is older than an hour remove it
		//if(time() > (filemtime($path) + 3600)) {
		//	$pid = file_get_contents($path);
		//	exec("kill $pid");
		if(time() > (filemtime($path) + 400)) {
			exec("kill $pid");
			close_pid();
			$logfile->writeLogError("uvr1611-connection.inc-create_pid - kill ".$pid."\n");
		//	throw new Exception("uvr1611-connection - kill ".$pid);
		}
		else {
			$logfile->writeLogError("uvr1611-connection.inc-create_pid - Another process is accessing the bl-net! pid= ".$pid." \n");
			throw new Exception("Another process is accessing the bl-net!");
		}

	}
	file_put_contents($path, getmypid());
}

/**
 * Remove the PID file
 */
function close_pid()
{
$filename='/tmp/uvr1611-logger.pid';
$path   = '/tmp/uvr1611-logger.pid';

	if (file_exists($filename)) {
	       	$erg=unlink( $filename);
//		unlink("/tmp/uvr1611-logger.pid");
		if (!$erg){
			$logfile = LogFile::getInstance();
			$logfile->writeLogError("uvr1611-connection.inc-close_pid - NOT sucessfully ".$pid."\n");
		}
	}
}

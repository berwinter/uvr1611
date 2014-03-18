<?php

/**
 * @author frama <frama1038@gmail.com>
 * @copyright copyright @ by Frama
 * @package 
 * @version 0.1
 */
include_once("lib/config.inc.php");

class LogFile
{
	/**
	* The open file handle.
	*
	* @access private
	*/
	var $m_handle;

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
	* Constructor
	*
	* @access public
	* @param string $fileName The name of the log file.
	*/
	public static function logfile($fileName)
	{
		if (file_exists($fileName))
		{
			$this->m_handle = @fopen($fileName, 'a') ;
		}
		else
		{
			$this->m_handle = @fopen($fileName, 'w') ;
		}

		if ($this->m_handle === FALSE)
		{
			trigger_error('Failed to open log file: ' . $fileName . '\n' .
			'Current working directory: ' . getcwd(), E_USER_WARNING) ;
		}
    }

	/**
	 * Constructor
	 * Uses Config class to get the infos
	 */
	private function __construct()
	{	
		$this->m_debug = Config::getInstance()->Logging->debug;
		$this->m_fileName = Config::getInstance()->Logging->logfile;		
		if (file_exists($this->m_fileName))
		{
			$this->m_handle = @fopen($this->m_fileName, 'a') ;
			$this->writeLogInfo("file still exits ".$this->m_handle." \n");
		}
		else
		{
			$this->m_handle = @fopen($this->m_fileName, 'w') ;
			$this->writeLogInfo("file new created\n");
		}

		if ($this->m_handle === FALSE)
		{
			trigger_error('Failed to open log file: ' . $this->m_fileName . '\n' .
			'Current working directory: ' . getcwd(), E_USER_WARNING) ;
		}	 
	}	
	
	/**
	* Close the log file.
	*
	* @access public
	*/
	public function close()
    {
		if ($this->m_handle !== FALSE)
		{
			fclose($this->m_handle) ;
		}
    }

	/**
	* Write to a log file.
	*
	* @access public
	* @param mixed Data to be logged.
	* @return integer number of bytes written to the log.
	*/
	public function writeLog($theData) 
    {
		// outputs the username that owns the running php/httpd process
		// (on a system with the "whoami" executable in the path)
		$whoami = exec('whoami');

		$stext = "";	
		$aktDate=date("Y.m.d - H:i:s");

		$stext = $aktDate." (".$whoami.") - ".$theData;		
		
		if ($this->m_handle !== FALSE)
		{
			fwrite($this->m_handle, $stext) ;
		}
		else
		{
			trigger_error('Failed to write in log file: ' . $this->m_fileName,E_USER_WARNING);
		}
    }

		/**
	* Write "state" to a log file.
	*
	* @access public
	* @param mixed Data to be logged.
	*/
	public function writeLogState($theData)
    {
		if ($this->m_debug > 0 )
		{	
			$stext = "State  - ".$theData;					
			$this->writeLogFile($stext);	
		}	
    }
	/**
	* Write "info" to a log file.
	*
	* @access public
	* @param mixed Data to be logged.
	*/
	public function writeLogInfo($theData)
    {
		if ($this->m_debug > 2 )
		{	
			$stext = "Info  - ".$theData;					
			$this->writeLogFile($stext);	
		}	
    }		
	/**
	* Write "warn" to a log file.
	*
	* @access public
	* @param mixed Data to be logged.
	*/
	public function writeLogWarn($theData)
    {
		if ($this->m_debug > 1 )
		{	
			$stext = "Warn  - ".$theData;					
			$this->writeLogFile($stext);	
		}	
    }	
	/**
	* Write "errors" to a log file.
	* @access public
	* @param mixed Data to be logged.
	*/
	public function writeLogError($theData)
    {
		if ($this->m_debug > 0 )
		{	
			$stext = "ERROR - ".$theData;		
			$this->writeLogFile($stext); 
		}			
    }	

	/**
	* Write to a log file.
	*
	* @access private
	* @param mixed Data to be logged.
	*/	
	private function writeLogFile($theData) 
	{
		// outputs the username that owns the running php/httpd process
		// (on a system with the "whoami" executable in the path)
		$whoami = exec('whoami');
		$aktDate=date("Y.m.d - H:i:s");
		$stext = $aktDate." (".$whoami."): ".$theData;				
		
		if ($this->m_handle !== FALSE)
		{
			fwrite($this->m_handle, $stext) ;
		}
		else
		{
			trigger_error('Failed to write in log file: ' . $this->m_fileName,E_USER_WARNING);
		}
    }	
}//class logfile
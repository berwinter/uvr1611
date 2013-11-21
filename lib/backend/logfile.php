<?php

/**
 * @author frama <frama1038@gmail.com>
 * @copyright copyright @ by Frama
 * @package 
 * @version 0.1
 */


class LogFile
{
	/**
	* The open file handle.
	*
	* @access private
	*/
	var $m_handle;
	var $m_fileName = "/var/log/myDataLogger.log";

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
	 * Uses Config class to set up a MySQL connection
	 */
	private function __construct()
	{
		if (file_exists($this->m_fileName))
		{
			$this->m_handle = @fopen($this->m_fileName, 'a') ;
		}
		else
		{
			$this->m_handle = @fopen($this->m_fileName, 'w') ;
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
		$stext = "";	
		$aktDate=date("Y.m.d - H:i:s");
		$stext = $aktDate." - ".$theData;		
		
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

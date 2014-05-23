<?php
/**
 * Class to log errors in PHP
 */
class Error_Log
{
	/**
	 * @var string
	 */
	private $logDirectory;

	/**
	 * @var resource
	 */
	private $logFile;

	/**
	 * @var array
	 */
	private $logTypes = array('warning', 'notice', 'error', 'fatal');

	/**
	 * @var string
	 */
	private $logFileName = '';

	/**
	 * If you want to log to a specific place pass in the log file, if not the class will work out where to log the files
	 *
	 * @param null $logFileName
	 *
	 * @throws Exception
	 */
	public function __construct( $logFileName = NULL )
	{
		$this->set_default_log_directory();

		$this->logFileName = $logFileName;
	}

	/**
	 * Close the log file on class destruct
	 */
	public function __destruct()
	{
		$this->close_log_file();
	}

	/**
	 * Log the error message to a log file
	 *
	 * @param $message
	 * @param string $type
	 *
	 * @return bool
	 * @throws Exception
	 */
	public function log( $message, $type = 'error' )
	{
		if(!is_string($message))
		{
			throw new Exception('Message must be a string.');
		}

		if(empty($message))
		{
			throw new Exception('Message can not be empty.');
		}

		$this->open_log_file( );

		if(!$this->logFile)
		{
			throw new Exception('Log file can not be found.');
		}

		if(!in_array(strtolower($type), $this->logTypes))
		{
			throw new Exception('Log type of ' . $type . ' is incorrect please provide ' . implode(', ', $this->logTypes));
		}

		// Set the log type message
		switch(strtolower($type))
		{
			case 'notice':
				$typeMessage = ucwords($type);
				break;

			case 'warning':
				$typeMessage = '! Warning !';
				break;

			case 'error':
				$typeMessage = '!!! Error !!!';
				break;

			case 'fatal':
				$typeMessage = '!***! Fatal !***!';
				break;
		}

		// build the log message
		$logMessage = sprintf('%s [%s] - %s', $typeMessage, $this->get_time(), $message);

		flock($this->logFile,LOCK_EX);
		fwrite($this->logFile,$logMessage."\n");
		flock($this->logFile,LOCK_UN);

		return true;
	}

	/**
	 * @return bool|string
	 */
	private function get_time()
	{
		return date("d.m.Y - H:i:s");
	}

	/**
	 * Open a new log file
	 *
	 * @throws Exception
	 */
	private function open_log_file(  )
	{
		$this->close_log_file();

		if($this->logFileName === NULL)
		{
			$this->logFileName = $this->logDirectory . '/' . date('Ymd') . '-log.txt';
		}

		$this->logFile = @fopen($this->logFileName,"a");

		if(!$this->logFile)
		{
			throw new Exception('Log file could not be opened.');
		}
	}

	/**
	 * If set close the log file
	 */
	private function close_log_file()
	{
		if($this->logFile)
		{
			fclose($this->logFile);
			$this->logFile = FALSE;
		}
	}

	/**
	 * Create the log directory if it doesn't already exist
	 */
	private function set_default_log_directory()
	{
		$this->logDirectory = dirname(__FILE__) . '/logs';

		if(!is_dir($this->logDirectory))
		{
			mkdir($this->logDirectory, 766);
		}
	}
}
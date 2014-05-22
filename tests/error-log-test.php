<?php
/**
 * Created by PhpStorm.
 * User: paul.underwood
 * Date: 22/05/2014
 * Time: 10:17
 */
require_once dirname(dirname(__FILE__)) . '/error-log.php';
class Error_LogTest extends PHPUnit_Framework_TestCase
{
	public function testlogwarning()
	{
		$errorlog = new Error_Log();
		$log = $errorlog->log('Warning Message');
		$this->assertTrue($log);
	}

	public function testlognotice()
	{
		$errorlog = new Error_Log();
		$log = $errorlog->log('Notice Message', 'notice');
		$this->assertTrue($log);
	}

	public function testlogerror()
	{
		$errorlog = new Error_Log();
		$log = $errorlog->log('Error Message', 'error');
		$this->assertTrue($log);
	}

	public function testlogfatal()
	{
		$errorlog = new Error_Log();
		$log = $errorlog->log('Fatal Message', 'fatal');
		$this->assertTrue($log);
	}

	/**
	 * @expectedException Exception
	 */
	public function testmessagenotastring()
	{
		$errorlog = new Error_Log();
		$log = $errorlog->log(new stdClass(), 'fatal');
	}

	/**
	 * @expectedException Exception
	 */
	public function testwrongtype()
	{
		$errorlog = new Error_Log();
		$log = $errorlog->log('Error', 'randomcrap');
	}

	public function testnewloglocation()
	{
		$errorlog = new Error_Log(dirname(__FILE__).'/store-log-file-here.txt');
		$log = $errorlog->log('Error', 'error');
		$this->assertTrue($log);
	}

	/*
	public function testcreatelogdirectory()
	{
		$errorlog = new Error_Log();
		$log = $errorlog->log('Fatal Message', 'fatal');
		$this->assertTrue($log);
	}*/
}
 
<?php
require_once 'phpinject.php';

interface IMessagePrinter
{
	function write($string);
}

class HtmlMessagePrinter implements IMessagePrinter
{
	public function __construct(IDatabaseProvider $db)
	{
		$db->connect();
	}
	
	public function write($string)
	{
		print '<em>"' . $string . '" was printed using HTML.</em>';
	}
}

class PlainTextPrinter implements IMessagePrinter
{
	public function write($string)
	{
		print '"' . $string . '" was printed using plain text.';
	}
}

interface IDatabaseProvider
{
	function connect();
}

class MysqlProvider implements IDatabaseProvider
{
	public function connect()
	{
		echo 'connecting to mysql';
	}
}

class SqlLiteProvider implements IDatabaseProvider
{
	public function connect()
	{
		echo 'connecting to sqllite';
	}
}

class hello_world
{
	public function __construct(IMessagePrinter $printer, IDatabaseProvider $db)
	{
		$this->printer = $printer;
		$this->db = $db;
		
		$this->db->connect();
	}
	
	public function say($message)
	{
		$this->printer->write($message);
	}
}




$ioc = new phpinject;
$ioc->bind('IDatabaseProvider')->to('MysqlProvider')
	->bind('IMessagePrinter')->to('HtmlMessagePrinter');


$helloWorldPrinter = $ioc->instantiate('hello_world');
$helloWorldPrinter->say('Hej!');
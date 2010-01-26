<?php
require_once 'phpinject.php';

interface IMessagePrinter
{
	function write($string);
}

class HtmlMessagePrinter implements IMessagePrinter
{
	private $created;
	
	public function __construct()
	{
		$this->created = date('Y-m-d H:i:s');
	}
	
	public function write($string)
	{
		print 'I was created ' . $this->created . '. This message was printed using Html.<br />';
	}
}

class hello_world
{
	public function __construct(IMessagePrinter $printer)
	{
		$this->printer = $printer;
	}
	
	public function say($message)
	{
		$this->printer->write($message);
	}
}




$ioc = new phpinject;
$ioc->bind('IMessagePrinter')
	->to('HtmlMessagePrinter')
	->using(new BindingType_Normal);


$p1 = $ioc->instantiate('hello_world');
$p1->say('Hej!');

sleep(2);

$p2 = $ioc->instantiate('hello_world');
$p2->say('Hejsan!');
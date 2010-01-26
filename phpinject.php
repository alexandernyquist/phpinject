<?php
class phpinject
{
	private $types = array();
	private $currentBinding; // Used for method chaning
	
	public function bind($type)
	{
		$this->currentBinding = $type;
		return $this;
	}
	
	public function to($concrete)
	{
		if($this->currentBinding == null) throw new Exception('No interface or base class specified.');
		
		$this->types[$this->currentBinding] = array('concrete' => $concrete, 'bindingType' => new BindingType_Normal()); // Default binding type should be normal

		return $this;
	}
	
	public function using(BindingType $type)
	{
		$this->types[$this->currentBinding]['bindingType'] = $type;
	}
	
	public function instantiate($className)
	{
		$class = new ReflectionClass($className);
		$method = $class->getMethod('__construct'); // we can only instantiate by constructor atm
		
		$paramDependencies = array();
		
		foreach($method->getParameters() as $parameter)
		{
			$concrete = $parameter->getClass()->getName();
			
			if($this->types[$concrete] == null) throw new Exception('Type of ' . $concrete . ' could not be found');
			
			$binder = new $this->types[$concrete]['bindingType'];

			$paramDependencies[] = $binder->activate($this->types[$concrete]['concrete']);
		}
		
		return $class->newInstanceArgs($paramDependencies);
	}
}

abstract class BindingType
{
	abstract function activate($type);
}

class BindingType_Normal extends BindingType
{
	public function activate($type)
	{
		return new $type;
	}
}

class BindingType_Singleton extends BindingType
{
	public function __construct()
	{
		// If a session is not started, start it
		if(session_id() == '') @session_start();
	}
	
	public function activate($type)
	{
		if(!isset($_SESSION['phpinject_singletons'][$type]))
			$_SESSION['phpinject_singletons'][$type] = new $type;
		
		return $_SESSION['phpinject_singletons'][$type];
	}
}
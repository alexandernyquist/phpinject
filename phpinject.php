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
		
		$this->types[$this->currentBinding] = $concrete;
		
		$this->currentBinding = null;
		return $this;
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
			
			$paramDependencies[] = new $this->types[$concrete];
		}
		
		return $class->newInstanceArgs($paramDependencies);
	}
}
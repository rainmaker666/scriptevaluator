<?php

namespace ScriptEvaluator;

class ScriptEvaluator
{
	private $evalStart;
	
	private $callback;
	
	public function __construct()
	{
		$this->callback = null;
		$this->evalStart = false;
		
		register_shutdown_function(array($this, "catchError"));
	}
	
	public function setCallback($function)
	{
		if(is_callable($function))
			$this->callback = $function;
	}
	
	public function execute($script)
	{
		$this->evalStart = true;
		eval($script);
		$this->evalStart = false;
		
	}
	
	public function catchError()
	{
		$error = error_get_last();
		if($this->evalStart && !is_null($error))
		{
			if(!is_null($this->callback))
			{
				$call = $this->callback;
				$call($error);
			}
			else
			{
				//TODO add better error reporting
				print "<pre>";
				var_dump($error);
				print "</pre>";
			}
		}
	}
}
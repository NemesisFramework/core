<?php
/***********************************************************************
************************************************************************
	Hooks
	manage hooks/triggers
************************************************************************
***********************************************************************/

class Hook
{
	private $functionHook;
	private static $hooks = array();
		
	public function __construct ($function=null)
	{
		$this->functionHook = $function;
	}
	
	public function call()
	{
		if (!is_null($this->functionHook))
			return call_user_func_array($this->functionHook, (func_num_args() > 0) ? func_get_args() : array());
		
		if (func_num_args() > 0)
		{
			$args1 = func_get_args(0);
			return $args1[0];
		}
		else
			return null;
	}
	
	public function apply()
	{
		if (!is_null($this->functionHook))
			call_user_func_array($this->functionHook, (func_num_args() > 0) ? func_get_args() : array());
	}
	
	public static function set($class, $name, $function)
	{
		self::$hooks[$class][$name] = new Hook($function);
	}
	
	public static function get($class, $name)
	{
			
		if (!isset(self::$hooks[$class][$name]))
			self::$hooks[$class][$name] = new Hook ();
			
		return self::$hooks[$class][$name];
	}
	
	public static function rm($class, $name)
	{
		if (isset(self::$hooks[$class][$name]))
			unset(self::$hooks[$class][$name]);
	}
}

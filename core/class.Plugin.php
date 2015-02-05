<?php
/*
	Class Plugin
	Description : Manage Plugins
	Dependencies : Loader
*/

class Plugin
{
	private static $instances;

	public function __construct($name='init', $attributes=array())
	{
		$this->name = $name;
		$this->path = NEMESIS_PATH.'plugins/'.$this->name.'/';
		$this->setup($attributes);
		
		if ($name == 'init')
		{
			$path = NEMESIS_PATH.'plugins';
			if (!file_exists($path))
				error_log('Core.Plugin : plugins directory does not exist');
		}
			
	}
	
	public function setup($attributes=array()) 
	{
	}
	
	public static function getInstance($name, $attributes=array())
	{
		if (file_exists($file=NEMESIS_PATH.'plugins/'.$name'.php'))
		{
			require_once($file);
		}
		else 
		{

			if (!isset(self::$instances[$name]))
			{
			
				if (file_exists($config=NEMESIS_PATH.'plugins/'.$name.'/config.php'))
					require_once($config);
			
				if (file_exists($functions=NEMESIS_PATH.'plugins/'.$name.'/functions.php'))
					require_once($functions);
		
				if (!file_exists($file=NEMESIS_PATH.'plugins/'.$name.'/plugin.php'))
				{	
					echo $file.' is missing. Cannot run plugin called '.$name.'';
					die;
				}
			
				require_once($file);

				$className = $name.'Plugin';
				self::$instances[$name] = new $className($name, $attributes);
			}
			
			return self::$instances[$name];
		}
	}

}

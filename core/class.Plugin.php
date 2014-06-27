<?php
/***********************************************************************
************************************************************************
	Class Plugin
	Manage Plugins
************************************************************************
***********************************************************************/

class Plugin extends MVC
{
	private static $instances;

	public function __construct($name, $attributes=array())
	{
		$this->name = $name;
		$this->path = PLUGINS.$this->name.'/';
		$this->resources_url = NEMESIS_URL.'plugins/'.$this->name.'/resources/';
		$this->setup($attributes);
	}
	
	public function setup($attributes=array()) 
	{
	}
	
	public static function getInstance($name, $attributes=array())
	{
		if (!isset(self::$instances[$name]))
			
			if (file_exists($config=PLUGINS.$name.'/config.php'))
				require_once($config);
			
			if (file_exists($functions=PLUGINS.$name.'/functions.php'))
				require_once($functions);
		
			if (!file_exists($file=PLUGINS.$name.'/plugin.php'))
			{
				echo $file.' is missing. Cannot run plugin called '.$name.'';
				die;
			}
			
			require_once($file);

			$className = $name.'Plugin';
			self::$instances[$name] = new $className($name, $attributes);
		return self::$instances[$name];
	}
	

}

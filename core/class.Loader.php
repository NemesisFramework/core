<?php

class Loader
{

	private static $instance = null;
	
	public static $overrideFromPlugins = array();

	public static function getInstance ()
    {
		if (is_null(self::$instance))
		{
			self::$instance = new Loader ();
		}

		return self::$instance;
	}

	public function __construct()
	{
		// Override main functions from plugins
		foreach(self::$overrideFromPlugins as $plugin)
		{
			if (file_exists($file=PLUGINS.$plugin.'/functions.php'))
				require_once($file);
		}
		
		// Load main core functions
		require_once(CORE.'functions.php');
	
		// Autoload class core
		spl_autoload_register(
			function ($className)
			{
				if (file_exists($classFile=CORE.'class.'. $className . '.php'))
				{
					require_once($classFile);
				}
			}
		);
		
		// Split request from current URL
		
		$exploded = explode('?', $_SERVER['REQUEST_URI']);
		if (sizeof($exploded) > 0)
			$request = $exploded[0];
		else
			$request = $_SERVER['REQUEST_URI'];
		
		if (NEMESIS_ROOT && NEMESIS_ROOT != '/')
			$request = str_replace (NEMESIS_ROOT, '', $request);

		// if (!empty($request) && (substr($request, -1) != '/') && !extension($request))
			// URL::redirect($request, 1);
		
		URL::splitRequest($request);
		URL::$request['QUERY_STRING'] = $_SERVER['QUERY_STRING'];
		
		// perms
		if (($perm=getperms(CACHE)) != '0777') 
		{
			error_log('CACHE : you must set 0777 perms to '.CACHE.' ('.$perm.')');
		}
	}

	/* PLUGIN LOADER */
	public function plugin($plugin, $attributes=array())
	{
		return Plugin::getInstance($plugin, $attributes);
	}

	/* APP LOADER */
	public function app($app)
	{
		return App::getInstance($app);
	}

}

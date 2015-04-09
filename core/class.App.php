<?php
/*
	Class App
	Description : Manage MVC Applications
	Dependencies : Loader, MVC, Hook, URL
*/

class App extends MVC
{
	private static $instances;
	public $NEMESIS = null;
    public $url = ''; // deprecated
	public $forbiddenMethods = array('setup', 'run', 'setAsDefault', 'startTime', 'endTime');

	public function __construct($name, $version)
	{
        $this->name = $name;
        $this->version = $version;
		$this->NEMESIS = Loader::getInstance();
    }
    
    /* DEPRECATED
    public function setAsDefault()
	{
		$this->url = '';
		URL::$prefix = '';
	}
    */
  
	public function startTime()
	{
		$time = microtime();
		$time = explode(' ', $time);
		$time = $time[1] + $time[0];
		$this->startTime = $time;
	}

	public function endTime()
	{
		$time = microtime();
		$time = explode(' ', $time);
		$time = $time[1] + $time[0];
		$finish = $time;
		$total_time = round(($finish - $this->startTime), 4);
		echo '<br />Page generated in '.$total_time.' seconds.';
	}

	public function setup()
	{
	}

	public function run()
	{
		$this->setup();

		// detect if the current URL calls the app

		Hook::get('App', 'FILENAME')->apply($this);

		if (is_file($this->path.'resources/'.URL::$request['SOURCE']) && file_exists($this->path.'resources/'.URL::$request['SOURCE']))
		{
			header('Content-type: '.mime_content_type($this->path.'resources/'.URL::$request['SOURCE']));
			echo file_get_contents($this->path.'resources/'.URL::$request['SOURCE']);
			exit();
		}

		$request = URL::$request['SOURCE'];

		if (empty($request) && !empty($this->url))
			return false;

		if (!empty($request) && !empty($this->url))
		{

			if (strpos($request, $this->url) !== 1)
				return false;

			$request = str_replace('/'.$this->url, '', $request);
		}

		URL::$request['HASH'] = explode('/', trim($request, '/'));

		// load the page method
		if (empty($request))
		{
			Hook::get('App', 'URL')->apply($this, 'index');
			$this->index();
		}
		else
		{
			$method = array_shift(URL::$request['HASH']);
			Hook::get('App', 'URL')->apply($this, $method);

			if (method_exists($this->name, $method) && !in_array(strtolower($method), $this->forbiddenMethods))
			{
				$this->$method(URL::$request['HASH']);
			}
			else if ($file=$this->getController($method))
			{
				ob_start();
				$HASH = &URL::$request['HASH'];
				$MVC = &$this;
				include_once($file);
				$this->injectCol('html', 'controller', ob_get_clean());
				$this->addToBuffer($this->getView('html'));
			}
			else
			{
				$this->error404(URL::$request['HASH']);
			}

		}

	}
	
	public function index()
	{
		$this->addTobuffer('HOME PAGE');
	}

	public function error404($arguments=array())
	{
		if ($file=$this->getController('error404'))
		{
			ob_start();
			$HASH = &URL::$request['HASH'];
			$MVC = &$this;
			include_once($file);
			$this->injectCol('html', 'controller', ob_get_clean());
			$this->addToBuffer($this->getView('html'));
			return false;
		}
		else
			$this->addTobuffer('ERROR 404 : PAGE NOT FOUND');
	}
	
	public static function getNav($items)
    {

		foreach ($items as $item)
		{
			$item = Hook::get('App', 'getNav')->call($item);

			if (is_array($item))
				$new_menu_items[] = array('name' => $item[0], 'url' => $item[1], 'target' => '_blank');
			else
				$new_menu_items[] = array('name' => $item, 'url' => new URL($item));
		}

		return $new_menu_items;
	}
	
	public static function getInstance($name, $version='1')
	{
		if (!isset(self::$instances[$name]))
		{
			if (file_exists($config=NEMESIS_PROCESS_PATH.'config.php'))
				require_once($config);

			/* DEPRECATED
            if (file_exists($functions=$this->path.'/functions.php'))
				require_once($functions);

			if (!file_exists($file=$this->path.'app.php'))
			{
				echo $file.' is missing. Cannot run application called '.$name.'';
				die;
			}
			
			require_once($file);
			*/
			
			self::$instances[$name] = new $name($name, $version);
		}
		return self::$instances[$name];
	}
}

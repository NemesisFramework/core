<?php
/***********************************************************************
************************************************************************
	Class MVC
	Manage MVC items
************************************************************************
***********************************************************************/

class MVC
{	
	protected $name, $version, $resources_url;
	protected $buffer = '';
	protected $variables = array();
	protected $collections = array();
	protected $CSS = array();
	protected $constants = array();
	protected $JS = array();
	protected $messages = array();
	
	public $path;
	
	public function getController ($controller)
	{
		if (!file_exists($file=$this->path.'controllers/'.$controller.'.php'))
		{
			return false;
		}
		
		return $file;
	}
	
	public function getModel ($model)
	{
		if (!file_exists($file=$this->path.'models/'.$model.'.php'))
		{
			error_log('Core.MVC : Model '.$file.' does not exist');
			return false;
		}
		
		return $file;
	}
	
	public function getResource ($resource)
	{
		if ($resource && preg_match('#http\:\/\/#', $resource))
			return $resource;
			
		if (!file_exists($file=$this->path.'resources/'.$resource))
		{
			error_log('Core.MVC : Resource '.$file.' does not exist');
			return false;
		}
		
		return $this->resources_url.$resource;
	}
	
	public function getView ($view)
	{
		
		if (!file_exists($file=$this->path.'views/'.$view.'.php'))
		{
			error_log('Core.MVC : View '.$file.' does not exist');
			return false;
		}
		
		// TOOLS FOR THE VIEW 
		$MVC = &$this;
		if (isset($this->collections[$view]))
			$COLLECTIONS = &$this->collections[$view];

		Hook::get('MVC', 'beforeView_'.$view)->apply();

		ob_start();
		
		Hook::get('MVC', 'topView_'.$view)->apply();

		require ($file);
		
		Hook::get('MVC', 'bottomView_'.$view)->apply();

		if ($view == 'head' && !URL::isHttpRequest())
		{
			$this->printCSS('head');
		}
		else if ($view == 'scripts')
		{
			if (URL::isHttpRequest())
				$this->printCSS();
				
			$this->printConstants();
			$this->printJS();
		}
		
		$out = ob_get_clean();
		
		$this->parseVariables($view, $out);		
			
		return $out."\n";
	}
	
	protected function parseVariables($view, &$out)
	{
		if (isset($this->variables[$view]) && !empty($this->variables[$view]))
		{
			foreach ($this->variables[$view] as $k => $v) {
				$out = str_replace('{$'.$k.'}', $v, $out);
			}
		}	
	}
	
	public function addToBuffer($html)
	{
		Hook::get('MVC', 'addToBuffer')->apply($html);
		$this->buffer .= $html;
	}
	
	public function render($view, $collections = array())
	{
		if (!empty($collections))
		{
			foreach ($collections as $k => $v)
			{
				$this->injectCol($view, $k, $v);
			}
		}
		
		if ($out=$this->getView($view))
			echo $out;
	}
	
	public function __toString()
	{
		Hook::get('MVC', 'toString')->apply($this->buffer);
		return $this->buffer;
	}
	
	public function injectVar($view, $list_var=array())
	{
	
		Hook::get('MVC', 'injectVar')->apply($view, $list_var);
		
		$this->variables[$view] = (isset($this->variables[$view]))? array_merge($this->variables[$view], $list_var):$list_var;
	}
	
	public function injectCol($view, $name, $value=array())
	{
	
		Hook::get('MVC', 'injectCol')->apply($view, $name, $value);
		
		if (!isset($this->collections[$view]))
			$this->collections[$view] = new stdClass();
		
		$this->collections[$view]->{$name} = $value;
	}

	public function loadCSS ($resources=array())
	{
		if (!is_array($resources))
			$this->CSS[] = $resources;

		else
		{
			if (!isset($this->CSS))
				$this->CSS = array();
			$this->CSS = array_merge($this->CSS, $resources);
		}

	}
	
	public function defineConstants ($c=array())
	{
		if (!is_array($c))
			$this->constants[] = $c;

		else
		{
			if (!isset($this->constants))
				$this->constants = array();
			$this->constants = array_merge($this->constants, $c);
		}
	}

	public function loadJS ($resources=array())
	{
		if (!is_array($resources))
			$this->JS[] = $resources;

		else
		{
			if (!isset($this->JS))
				$this->JS = array();
			$this->JS = array_merge($this->JS, $resources);
		}

	}

	private function printCSS ($view='')
	{
	
		$this->CSS = Hook::get('MVC', 'CSS')->call($this->CSS, $this->path.'resources/');

		if (!empty($this->CSS))
		{
			if ($view == 'head')
			{
				foreach($this->CSS as $lib)
				{
					$href = $this->getResource($lib).'?'.$this->version;
					echo '<link rel="stylesheet" type="text/css" media="screen" href="'.$href.'" />'."\n";
				}
			}
			else
			{
				echo '<style type="text/css">'."\n";
				foreach($this->CSS as $lib) 
				{
					$href = $this->getResource($lib).'?'.$this->version;
					echo '@import url("'.$href.'");'."\n";
				}
				echo '</style>'."\n";
			}
	
			unset($this->CSS);
		}

	}
	
	private function printConstants()
	{
	
		if (!empty($this->constants))
		{
			echo '<script type="text/javascript">'."\n";
			echo ' if( typeof window.NEMESIS == \'undefined\' ) window.NEMESIS = {};'."\n";
			echo ' NEMESIS = { '."\n";
			foreach ($this->constants as $k => $v)
			{
				if (!preg_match('#__#', $k))
					echo '  '.$k.' : \''.$v.'\','."\n";
			}
			echo ' };'."\n";
			foreach ($this->constants as $k => $v)
			{
				if (preg_match('#__#', $k))
					echo $k.' = \''.$v.'\';'."\n";
			}
			echo '</script>'."\n";
			unset($this->constants);
		}
	}

	private function printJS ()
	{
		
		$this->JS = Hook::get('MVC', 'JS')->call($this->JS, $this->path.'resources/');
		
		if (!empty($this->JS))
		{

			foreach($this->JS as $lib)
			{
			
				echo '<script type="text/javascript" src="'.$this->getResource($lib).'?'.$this->version.'"></script>'."\n";

			}
			unset($this->JS);
		}

	}
	
	protected function addMessage($type, $message)
	{
		$this->messages[$type][] = $message;
	}
	
	protected function displayMessages()
	{
		if (URL::isHttpRequest() && !empty($this->messages))
		{
			if(!headers_sent())
				header('Content-Type: application/json; charset=utf-8', true,200);
				echo json_encode($this->messages);
			die;
		}
	}
	
}	

<?php
/*
    HTMLhelpers
	helpers for HTML forms
	Dependencies : null
*/

class HTMLhelpers
{
	
	public function input($type, $attributes=array())
	{
		return '<input type="'.$type.'"'.self::implode_attributes($attributes).' />'."\n";
	}
	
	public function text($attributes=array())
	{
		return $this->input('text', $attributes);
	}

	public function submit($attributes=array())
	{
		return $this->input('submit', $attributes);	
	}
	
	public function hidden($attributes=array())
	{
		return $this->input('hidden', $attributes);
	}
	
	public function checkbox($attributes=array())
	{
		return $this->input('checkbox', $attributes);
	}
	
	public function radio($attributes=array())
	{
		return $this->input('radio', $attributes);
	}
	
	public function button($attributes=array())
	{
		return $this->input('button', $attributes);
	}
	
	public function password($attributes=array())
	{
		return $this->input('password', $attributes);
	}
	
	public function file($attributes=array())
	{
		return $this->input('file', $attributes);
	}
	
	// HTML5 inputs
	
	public function email($attributes=array())
	{
		return $this->input('email', $attributes);
	}
	
	public function url($attributes=array())
	{
		return $this->input('url', $attributes);
	}

	public function textarea($attributes=array())
	{
		$value = '';
		
		if (isset($attributes['value']))
		{
			$value = $attributes['value'];
			unset($attributes['value']);
		}
		
		return '<textarea'.self::implode_attributes($attributes).'>'.$value.'</textarea>'."\n";
	}
	
	public function label($attributes=array())
	{
		$value = '';
		
		if (isset($attributes['value']))
		{
			$value = $attributes['value'];
			unset($attributes['value']);
		}
		
		return '<label'.self::implode_attributes($attributes).'>'.$value.'</label>'."\n";
	}

	public function select($content='', $current=0, $attributes=array())
	{
		$out = '<select'.self::implode_attributes($attributes).'>'."\n";
		if (is_string($content))
			$out .= $content."\n";
		else if (self::is_assoc($content))
		{
			foreach ($content as $k => $v)
				$out .= '<option value="'.$k.'"'.(($k==$current)? ' selected="true"':'').'>'.$v.'</option>'."\n";
		}
		else if (is_array($content))
		{
			foreach ($content as $c)
				$out .= '<option value="'.$c.'"'.(($c==$current)? ' selected="true"':'').'>'.$c.'</option>'."\n";
		}
		else if (is_object($content))
		{
			while ($content && $data=$content->next('row'))
				$out .= '<option value="'.$data[0].'"'.(($data[0]==$current)? ' selected="true"':'').'>'.$data[1].'</option>'."\n";
		}
		$out .= '</select>'."\n";
		return $out;
	}

	public function form($html='', $hiddens=array(), $attributes=array())
	{
		extract($attributes);
		$out = '<form'.(isset($action)? ' action="'.$action.'"':'').(isset($name)? ' name="'.$name.'"':'').(isset($method)? ' method="'.$method.'"':' method="post"').(isset($enctype)? ' enctype="'.($enctype!='text/plain'? 'multipart/form-data':'text/plain').'"':'').'>'."\n";

		foreach ($hiddens as $key => $value)
		{
			$out .= $this->hidden(array('name' => $key,  'value' => $value))."\n";
		}
		
		$out .= $html;
		
		$out .= '</form>'."\n";
		return $out;
	}
	
	public static function is_assoc ($arr) 
	{
        return (is_array($arr) && (!count($arr) || count(array_filter(array_keys($arr),'is_string')) == count($arr)));
    }
	
	public static function implode_attributes($attributes=array())
	{
		$out = '';

		foreach($attributes as $k => $v) 
		{
			$out .= ' '.$k.'="'.$v.'"';
		}
		
		return $out;
	}
}

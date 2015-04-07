<?php
/*
    CSSmin
	minify CSS
	Dependencies : Loader, Hook, MVC
*/


class CSSmin
{
	
	public function __construct() 
	{
		Hook::set('MVC', 'CSS', function($files, $pathResources, $version) {
			
			$fileNameCache = str_replace('/', '', implode('_', $files)).$version'.css';
			
			if (!file_exists($pathResources.$fileNameCache))
			{
				$buffer = '';
				foreach($files as $file)
				{
					
					$fileName = $pathResources.$file;
					$bufferFile = @file_get_contents($fileName);
					
					/* correct url imgs */
					$newUrl = '/'.str_replace(basename($file), '', $file);
					$bufferFile = preg_replace('#url\(["\']?([^"\']+)["\']?\)#', 'url('.$newUrl.'$1)', $bufferFile);
					$buffer .= $bufferFile;
				}
				
				//compress
				/* remove comments */
				$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
				/* remove tabs, spaces, newlines, etc. */
				$buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
				
				@file_put_contents($pathResources.$fileNameCache, $buffer);
			}
			
			return array($fileNameCache);
		});
	}
}

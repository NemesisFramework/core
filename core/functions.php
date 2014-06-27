<?php

/*

Developer Name: Nicolas Castelli
Developer Website: http://ncastelli.carbonmade.com 
File name: Functions.php
Creation date: 12/2/2013
Liences: GPL2

Description:

Main core functions

*/



//	MANAGE STRINGS

if (!function_exists('strip_accents')) {

	function strip_accents ($str) {
		
		return strtr($str,'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ','aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
	
	}

}


if (!function_exists('strip_specialchars')) {
	
	function strip_specialchars ($str, $replacement='') {
	
		$str = htmlentities(strip_tags($str));
		return preg_replace('/&.+;/', $replacement, $str);
		
	}
}


if (!function_exists('beautify')) {
	
	function beautify ($str) {
	
		$str = strip_specialchars($str, '-');
		$str = trim($str);
		$str = str_replace(' ', '-', $str);
		
		do {
			$str = str_replace('--', '-', $str, $r);
		}
		while ($r != 0);

		return strip_accents($str);
	
	}
	
}


if (!function_exists('minimize')) {
	
	function minimize ($str) {
		
		$str = beautify($str);
		$str = str_replace(array('/', '\\', '-', $str));
		
		do {
			$str = str_replace('--', '-', $str, $r);
		}
		while ($r != 0);

		return $str;
	
	}
	
}


if (!function_exists('excerpt')) {

	function excerpt ($str, $size = 200) {
		
		$str = trim($str);

		if (!empty($str)) {
			$str = strip_tags($str);
			$str = substr($str, 0, $size);
		}

		return $str;
	
	}

}
	

if (!function_exists('is_email')) {

	function is_email ($str) {

		return preg_match('`([[:alnum:]]([-_.]?[[:alnum:]])*@[[:alnum:]]([-_.]?[[:alnum:]])*\.([a-z]{2,4}))`', $str);

	}

}


if (!function_exists('is_phone_fr')) {

	function is_phone_fr ($str) {

		return preg_match('`0[1-9][0-9]{8}`', $str);
		
	}

}



//	MANAGE FILES && SHORTCUTS

if (!function_exists('getperms')) {

	function getperms ($path) {
		return substr(sprintf('%o', fileperms($path)), -4);
	}

}


if (!function_exists('filename')) {

	function filename ($filePath) {
		
		return pathinfo($filePath, PATHINFO_FILENAME); // PHP 5.2
		
	}
	
}


if (!function_exists('extension')) {

	// does not work with tar.gz
	function extension ($filePath) {
		
		return pathinfo($filePath, PATHINFO_EXTENSION);
		
	}
	
}



if (!function_exists('upload')) {

	// return an array of upload result for each file or an integer of the targetPath's permissions if not 0777
	function upload ($name, $targetPath, $extensions=array()) {
	
		if (($perms=getperms($targetPath)) != '0777')
			return $perms;
	
		$result = array();
	
		if (!is_array($_FILES[$name]['name'])) {
		
			if (!empty($extensions) && !in_array(extension( $_FILES[$name]['name'] ), $extensions))
				return false;
		
			$targetPathCurrent = $targetPath . basename( $_FILES[$name]['name'] );
			
			$i = 1;
			while (file_exists($targetPathCurrent))
			{
				$targetPathCurrent = $targetPath . filename( $_FILES[$name]['name'] ) . '_'.$i.'.'.extension( $_FILES[$name]['name'] );
				$i++;
			}
			
			if (move_uploaded_file($_FILES[$name]['tmp_name'], $targetPathCurrent) && file_exists($targetPathCurrent))
				return array($targetPathCurrent);
			else
				return $result;
			
		}
		
		$i = 0;
		
		foreach ($_FILES[$name]['name'] as $fileName) {
			
			if (!empty($extensions) && !in_array(extension( $fileName ), $extensions))
				return false;
			
			$targetPathCurrent = $targetPath . basename( $fileName );
			
			$j = 1;
			while (file_exists($targetPathCurrent))
			{
				$targetPathCurrent = $targetPath . filename( $fileName ) . '_'.$j.'.'.extension( $fileName );
				$j++;
			}
			
			if (move_uploaded_file($_FILES[$name]['tmp_name'][$i], $targetPathCurrent) && file_exists($targetPathCurrent))
			{
				$result[$i] = $targetPathCurrent;
				$i++;
			}
			
		}
		
		return $result;
	}

}


if (!function_exists('download')) {

	function download ($filePath, $fileName='') {
	
		if (!file_exists($filePath))
			return $filePath;
		
		$fileInfos = pathinfo($filePath);
		
		header('Content-disposition: attachment; filename='. (($fileName)? $fileName:$fileInfos['basename'].'.'.$fileInfos['extension']));
		header('Content-Type: application/force-download');
		header('Content-Transfer-Encoding: '. mime_content_type($fileInfos['extension']) .'\n');
		header('Content-Length: '.filesize($filePath));
		header('Pragma: no-cache');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0, public');
		header('Expires: 0');
		readfile($filePath);
		exit();
		
	}
	
}


// MANAGE DATES && SHORTCUTS


if (!function_exists('is_date')) {

	// Return DATE TYPE (DATE or TIMESTAMP) or FALSE
	function is_date($str) {
	
		if (empty($str))
			return false;

		if (is_numeric($stamp=$str) && $str > 1000000000)
			$type = 'TIMESTAMP';
		else
		{
			if (!is_numeric($stamp=strtotime($str)))
				return false;
			$type = 'DATE';
		}

		$checkdate = getdate(time());

		if (!checkdate($checkdate['mon'], $checkdate['mday'], $checkdate['year']))
			return false;

		return $type;
	}
	
}


if (!function_exists('datetime')) {

	function datetime($time) {
	
		return date('Y-m-d H:i:s', $time);
		
	}
	
}


// ALTERNATIVE TO FILE GET CONTENTS


if (!function_exists('url_get_contents')) {

	function url_get_contents ($Url) {
		if (!function_exists('curl_init')){
			error_log('curl is not installed');
			return false;
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $Url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}

}


// OUTPUT HTML


if (!function_exists('sanitize_output')) {

	function sanitize_output($buffer)
	{
		$search = array(
			'/\>[^\S ]+/s', //strip whitespaces after tags, except space
			'/[^\S ]+\</s', //strip whitespaces before tags, except space
			'/(\s)+/s'  // shorten multiple whitespace sequences
			);
		$replace = array(
			'>',
			'<',
			'\\1'
			);
		$buffer = preg_replace($search, $replace, $buffer);

		return $buffer;
	}

}

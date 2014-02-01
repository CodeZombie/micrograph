<?php 
class File {
	public static function copyFile($old, $new) {
		$location = isset($GLOBALS["directory"]) ? $GLOBALS["directory"] : "";
		return copy($old, $location . $new);
	}
	
	public static function getFileExtension($filename) {
		 $i = strrpos($filename,".");
		 if (!$i) {
			return "";
		}
		 $l = strlen($filename) - $i;
		 $ext = substr($filename,$i+1,$l);
		 return $ext;
	}
	
	public static function createFileIfNotExist($filename) {
		$location = isset($GLOBALS["directory"]) ? $GLOBALS["directory"] : "";
		$filename = $location . $filename;
		if(!file_exists($filename)) {
			$handle = fopen($filename,"w");
			fclose($handle);
		}
	}
	public static function saveFile($filename, $content) {
		$location = isset($GLOBALS["directory"]) ? $GLOBALS["directory"] : "";
		$filename = $location . $filename;
		if(!file_exists($filename)) {
			$handle = fopen($filename,"w+");
			fclose($handle);
		}
		return file_put_contents($filename,$content);
	}
	
	public static function fileExists($filename) {
		$location = isset($GLOBALS["directory"]) ? $GLOBALS["directory"] : "";
		if(file_exists($location . $filename)) {
			return true;
		}
		return false;
	}
	
	public static function readFile($filename) {
		$location = isset($GLOBALS["directory"]) ? $GLOBALS["directory"] : "";
		$filename = $location . $filename;
		if(!file_exists($filename)) {
			//error!
			return false;
		}
		return file_get_contents($filename);
	}
	
	public static function deleteFile($filename) {
		$location = isset($GLOBALS["directory"]) ? $GLOBALS["directory"] : "";
		if(file_exists($location . $filename)) {
			unlink($location . $filename);
			return true;
		}
		return false;
	}
	
	public static function getFilesInDirectory($directory, $order = "asc") {
		$location = isset($GLOBALS["directory"]) ? $GLOBALS["directory"] : "";
		$results = array();
		$results = scandir($location . $directory);
		foreach($results as $key => $value) {
			if($value == "." || $value == "..") {
				unset($results[$key]);
			}
		}
		$results= array_values($results);
		if($order == "asc") {
			return $results;
		}
		else {
			return array_reverse($results);
		}
	}
	
	public static function getFileNameByIndex($directory, $n, $order = "asc") {
		$location = isset($GLOBALS["directory"]) ? $GLOBALS["directory"] : "";
		$directory = $location . $directory;
		return self::getFilesInDirectory($directory, $order)[$n-1];
	}
	
	public static function getLastFileInDirectory($directory) {
		$arr = self::getFilesInDirectory($directory);
		return end($arr);
	}
	
	public static function getNumberOfFilesInDirectory($directory) {
		$location = isset($GLOBALS["directory"]) ? $GLOBALS["directory"] : "";
		$directory = $location . $directory;
		return count(self::getFilesInDirectory($directory));
	}
}
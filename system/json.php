<?php
class Json {
	public static function readJsonFile($filename, $hidden = false) {
		if($hidden) {
			$data = json_decode(substr(File::readFile($filename),8),true);
		}
		else {
			$data = json_decode(File::readFile($filename),true);
		}
		return $data;
	}
	public static function saveJsonFile($content,$filename,$hidden = false) {
		
		if($hidden) {
			return File::saveFile($filename,"<?php //".json_encode($content));
		}
		else {
			return File::saveFile($filename,json_encode($content));
		}
	}
}
?>
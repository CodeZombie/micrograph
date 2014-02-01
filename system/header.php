<?php

class Header {
	
	static function getHeaderPost() {
		$arr = array();
		foreach(func_get_args() as $request) {
			if(!isset($_POST[$request])) {
				return false;
			}
			else {
				$arr[] = $_POST[$request];
			}
		}
		if(count($arr)>1) {
			return $arr;
		}
		else {
			return $arr[0];
		}
	}
	
	static function getHeaderGet() {
		$arr = array();
		foreach(func_get_args() as $request) {
			if(!isset($_GET[$request])) {
				return false;
			}
			else {
				$arr[] = $_GET[$request];
			}
		}
		if(count($arr)>1) {
			return $arr;
		}
		else {
			return $arr[0];
		}
	}
	
	static function formatHeaderGetList() {
		$output = "";
		$list = func_get_args();
		foreach ($list as &$value) {
			$key = array_search($value,$list);
			if($key!==false) {
				if(self::getHeaderGet($list[$key])!=false) {
					$output = $output."&".$list[$key]."=".self::getHeaderGet($value);
				}
			}
		}
		return $output;
	}
}
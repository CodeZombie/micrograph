<?php
class post {
	function __construct($id) {
		$dataArray = Json::readJsonFile("../content/posts/" . $id . ".json");
		if(!$dataArray) {
			return false;
		}
		
	}
}
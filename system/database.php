<?php
class Database {
	public static function postExistsById($id) {
		$filename = "content/postdata/" . str_pad($id, 5, "0", STR_PAD_LEFT) . ".json";
		if(File::fileExists($filename)) {
			return true;
		}
		return false;
	}
	public static function readPostById($id) {
	
		$filename = "content/postdata/" . str_pad($id, 5, "0", STR_PAD_LEFT) . ".json";
		$outputdata = array();
		$postdata = Json::readJsonFile($filename);
		
		$outputdata["id"] = $postdata["id"];
		$outputdata["title"] = $postdata["title"];
		$outputdata["status"] = $postdata["status"];
		$outputdata["tags"] = $postdata["tags"];
		
		if($postdata["css"] !== false) {
			//load CSS file
			//put css code into $outputdata["css"];
			
		}
		else {
			$outputdata["css"] = false;
		}
		
		$outputdata["content"] = File::readFile("content/markdown/" . $id . ".md");
		
		if($outputdata["content"]===false) {
			
			return false;
		}
		
		return $outputdata;
	}

	public static function readPostByIndex($id, $order = "asc", $tagfilter = false) { //returns array of data
		if($tagfilter !== false) {
			$tagarray = Json::readJsonFile("content/tags.json");
			if(array_key_exists($tagfilter,$tagarray)) {
				$tagarray = $tagarray[$tagfilter];
				if($order === "asc") {
					return self::readPostById($tagarray[$id-1]);
				}
				else {
					return self::readPostById(array_reverse($tagarray)[$id-1]);
				}
			}
			else {
				return false;
			}
		}
		$filename = "content/postdata/" . File::getFileNameByIndex("content/postdata/", $id, $order);
		return self::readPostById(Json::readJsonFile($filename)["id"]);
	}
	
	public static function getNumberOfPosts($tagfilter = false) {
		if($tagfilter === false) {
			return File::getNumberOfFilesInDirectory("content/postdata/");
		}
		else {
			$file = Json::readJsonFile("content/tags.json");
			if(array_key_exists($tagfilter,$file)) {
				return count($file[$tagfilter]);
			}
			else {
				return false;
			}
		}
	}
	
	public static function savePost($title, $content, $tags, $status = "published" , $custom_css = false, $id = false) {
		if($id !== false) {
			$id = intval($id);
		}
		
		if($title == "") {
			$GLOBALS["ERROR"] = "Please enter a title";
			return false;
		}
		
		if($content == "") {
			$GLOBALS["ERROR"] = "Please write some content";
			return false;
		}
		
		$postdata = array();
		//get the last file in the directory (which will have the largest filenumber), strip the ".json", convert it to an integer, and add 1
		if($id === false) {
			$postcount = intval(substr(File::getLastFileInDirectory("content/postdata/"),0,5)) + 1;
		}
		else { 
			$postcount = $id;
		}
		$tagdata = Json::readJsonFile("content/tags.json");
		
		$postdatafilename = "content/postdata/" . str_pad($postcount,5,"0",STR_PAD_LEFT) . ".json";
		$postdata["id"] = $postcount;
		//link image data as well
		$postdata["title"] = $title;
		$postdata["status"] = $status;
		$postdata["css"] = $custom_css;
		$postdata["tags"] = array();
		$exploded_tags = explode(",",$tags);
		
		foreach($exploded_tags as $tag) {
		
			if(trim($tag) !== "") {
			
				array_push($postdata["tags"],strtolower(trim($tag)));
				
				self::saveTag(strtolower(trim($tag)),$postcount);
			}
		}
		
		Json::saveJsonFile($postdata,$postdatafilename);//save /postdata file
		File::saveFile("content/markdown/" . $postcount . ".md", $content);
		//save files (custom css)
		return true;
	}
	
	public static function deletePost($id) {
		$filename = "content/postdata/" . str_pad($id, 5, "0", STR_PAD_LEFT) . ".json";
		if(!File::fileExists($filename)) {
			$GLOBALS["ERROR"] = "Post doesn't exist";
			return false;
		}
		File::deleteFile("content/markdown/" . $id . ".md");
		File::deleteFile($filename);
		//delete/update tag record
		$tags = Json::readJsonFile("content/tags.json");
		$newtags = array();
		//cycle through each tag, deleting any record of $id
		foreach($tags as $key=>$value) {
			if(array_values(array_diff($tags[$key], array(intval($id)))) !== array()) {
				$newtags[$key] = array_values(array_diff($tags[$key], array(intval($id))));
			}
		}
		Json::saveJsonFile($newtags,"content/tags.json");
		return true;
	}
	public static function deleteBackup() {
		File::deleteFile("content/markdown/backup.md");
		return File::deleteFile("content/backup.json");
	}
	public static function saveBackup($id = false, $title, $content, $tags, $status = "draft", $images = false, $custom_css = false) {
		$postdata["id"] = $id;
		$postdata["title"] = $title;
		$postdata["status"] = $status;
		$postdata["css"] = $custom_css;
		$postdata["tags"] = $tags;
		
		Json::saveJsonFile($postdata,"content/backup.json");
		File::saveFile("content/markdown/backup.md", $content);
	}
	
	public static function saveTag($tag, $id) {
		
		$oldtagdata = Json::readJsonFile("content/tags.json");
		
		if($oldtagdata != null) {
			if(array_key_exists($tag, $oldtagdata)!==false) {
				
				if(in_array($id, $oldtagdata[$tag])) {
					//check if value is already in array
					return false;
				}
				array_push($oldtagdata[$tag], $id);
			}
			else {
				$oldtagdata[$tag] = array($id);
			}
		Json::saveJsonFile($oldtagdata,"content/tags.json");
		}
		else {
			$data = array();
			$data[$tag] = array($id);
			Json::saveJsonFile($data,"content/tags.json");
		}
	}
	
	//public static function editPost($id, ;
	
}
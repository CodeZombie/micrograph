<?php
class Database {

	public static function postExistsById($id) {
		$filename = "content/postdata/" . str_pad($id, 5, "0", STR_PAD_LEFT) . ".json";
		if(File::fileExists($filename)) {
			return true;
		}
		return false;
	}	
	
	public static function draftExistsById($id) {
		$filename = "content/draftdata/" . str_pad($id, 5, "0", STR_PAD_LEFT) . ".json";
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
			$tagfilter = strtolower($tagfilter);
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
		if($id === false) {
			//get the last file in the directory (which will have the largest numeric filename), strip the ".json", convert it to an integer, and add 1
			$postcount = intval(substr(File::getLastFileInDirectory("content/postdata/"),0,5)) + 1;
		}
		else { 
			$postcount = $id;
		}
		
		//delete all tags associated with the post we're going to update
		if($id !== false) {
			$tagdata = Json::readJsonFile("content/tags.json");
			$newtags = array();
			foreach($tagdata as $key=>$value) {
				if(array_values(array_diff($tagdata[$key], array(intval($id)))) !== array()) {
					$newtags[$key] = array_values(array_diff($tagdata[$key], array(intval($id))));
				}
			}
			Json::saveJsonFile($newtags,"content/tags.json");
		}
		
		$tagdata = Json::readJsonFile("content/tags.json");
		$postdatafilename = "content/postdata/" . str_pad($postcount,5,"0",STR_PAD_LEFT) . ".json";
		$postdata["id"] = $postcount;
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
		
		Json::saveJsonFile($postdata,$postdatafilename);
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
	
	public static function deleteDraft($id) {
		$filename = "content/draftdata/" . str_pad($id, 5, "0", STR_PAD_LEFT) . ".json";
		if(!File::fileExists($filename)) {
			$GLOBALS["ERROR"] = "Draft doesn't exist";
			return false;
		}
		File::deleteFile("content/draftmarkdown/" . $id . ".md");
		File::deleteFile($filename);
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
	
	public static function saveDraft($title, $content, $tags, $id = null) {
		if($title == "") {
			$GLOBALS["ERROR"] = "Please enter a title";
			return false;
		}
		
		if($content == "") {
			$GLOBALS["ERROR"] = "Please write some content";
			return false;
		}
		
		$postdata = array();
		//get the last file in the directory (which will have the largest numeric filename), strip the ".json", convert it to an integer, and add 1
		$postcount = intval(substr(File::getLastFileInDirectory("content/draftdata/"),0,5)) + 1;
		if($id !== null) {
			//overwrite old post
			$postcount = $id;
		}
		$postdatafilename = "content/draftdata/" . str_pad($postcount,5,"0",STR_PAD_LEFT) . ".json";
		$postdata["id"] = $postcount;
		$postdata["title"] = $title;
		$postdata["tags"] = $tags;

		Json::saveJsonFile($postdata,$postdatafilename);
		File::saveFile("content/draftmarkdown/" . $postcount . ".md", $content);
		//save files (custom css)
		return true;
	}
	
	public static function readDraftByIndex($index,$order = "asc") {
		$filename = "content/draftdata/" . File::getFileNameByIndex("content/draftdata/", $index, $order);
		return self::readDraftById(Json::readJsonFile($filename)["id"]);
	}
	
	public static function readDraftById($id) {
		$filename = "content/draftdata/" . str_pad($id, 5, "0", STR_PAD_LEFT) . ".json";
		$outputdata = array();
		$postdata = Json::readJsonFile($filename);
		
		$outputdata["id"] = $postdata["id"];
		$outputdata["title"] = $postdata["title"];
		$outputdata["tags"] = $postdata["tags"];
		
		$outputdata["content"] = File::readFile("content/draftmarkdown/" . $id . ".md");
		
		if($outputdata["content"]===false) {
			
			return false;
		}
		
		return $outputdata;
	}
	
	public static function getNumberOfDrafts() {
		return File::getNumberOfFilesInDirectory("content/draftdata/");
	}
	
	public static function saveTag($tag, $id) {
		$tag = strtolower($tag);
		$oldtagdata = Json::readJsonFile("content/tags.json");
		
		if($oldtagdata != null) {
			if(array_key_exists($tag, $oldtagdata)!==false) {
				//check if value is already in array
				if(in_array($id, $oldtagdata[$tag])) {
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
	
}
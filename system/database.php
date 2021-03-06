<?php
class Database {

	public static function postExistsById($draft, $id) {
		if($draft) {
			$path = "content/draftdata/";
		}
		else {
			$path = "content/postdata/";
		}
		
		$filename = $path . str_pad($id, 5, "0", STR_PAD_LEFT) . ".json";
		if(File::fileExists($filename)) {
			return true;
		}
		return false;
	}	

	public static function readPostById($draft, $id, $parse = false) {
		if($draft) {
			$datapath = "content/draftdata/";
			$mdpath = "content/draftmarkdown/";
		}
		else {
			$datapath = "content/postdata/";
			$mdpath = "content/markdown/";
		}
		
		$filename = $datapath . str_pad($id, 5, "0", STR_PAD_LEFT) . ".json";
		$outputdata = array();
		$postdata = Json::readJsonFile($filename);
		
		$outputdata["id"] = $postdata["id"];
		$outputdata["title"] = $postdata["title"];
		$outputdata["tags"] = $postdata["tags"];
		$outputdata["post_time"] = $postdata["post_time"];
		if(isset($postdata["last_update_time"])) {
			$outputdata["last_update_time"] = $postdata["last_update_time"];
		}

		$outputdata["content"] = File::readFile($mdpath . $id . ".md");
		if($parse) {
			$outputdata["content"] = nl2br(Parsedown::instance()->parse($outputdata["content"]));
		}
		if($outputdata["content"]===false) {
			
			return false;
		}
		
		return $outputdata;
	}
	
	public static function readPostByIndex($draft, $index, $order = "asc", $tagfilter = false) {
		if($draft) {
			$path = "content/draftdata/";
		}
		else {
			$path = "content/postdata/";
		}
		
		if(!$draft) {
			if($tagfilter !== false) {
				$tagarray = Json::readJsonFile("content/tags.json");
				if(array_key_exists($tagfilter,$tagarray)) {
					$tagarray = $tagarray[$tagfilter];
					if($order === "asc") {
						return self::readPostById(false, $tagarray[$index-1]);
					}
					else {
						return self::readPostById(array_reverse($tagarray)[$index-1]);
					}
				}
				else {
					return false;
				}
			}
		}
		$filename = $path . File::getFileNameByIndex($path, $index, $order);
		return self::readPostById($draft, Json::readJsonFile($filename)["id"]);
	}
	
	public static function getNumberOfPosts($draft, $tagfilter = false) {
		if(!$draft) {
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
		else {
			return File::getNumberOfFilesInDirectory("content/draftdata/");
		}
	}
	
	public static function savePost($draft, $title, $content, $tags, $id = false) {
		if($draft) {
			$datapath = "content/draftdata/";
			$mdpath = "content/draftmarkdown/";
		}
		else {
			$datapath = "content/postdata/";
			$mdpath = "content/markdown/";
		}
		
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
			$postcount = intval(substr(File::getLastFileInDirectory($datapath),0,5)) + 1;
		}
		else { 
			$postcount = $id;
		}
		
		if($id !== false) {
			$postdata["last_update_time"] = date('D, M j Y \a\t g:ia');
			$postdata["post_time"] = self::readPostById($draft,$id)['post_time'];
		}
		else {
			$postdata["post_time"] = date('D, M j Y \a\t g:ia');
		}
		
		if(!$draft) {
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
		}
		
		
		$postdatafilename = $datapath . str_pad($postcount,5,"0",STR_PAD_LEFT) . ".json";
		$postdata["id"] = $postcount;
		$postdata["title"] = $title;
		
		
		if(!$draft) {
			$postdata["tags"] = array();
			$exploded_tags = explode(",",$tags);
			
			foreach($exploded_tags as $tag) {
				if(trim($tag) !== "") {
					array_push($postdata["tags"],strtolower(trim($tag)));
					self::saveTag(strtolower(trim($tag)),$postcount);
				}
			}
		}
		else {
			$postdata["tags"] = $tags;
		}
		
		Json::saveJsonFile($postdata,$postdatafilename);
		File::saveFile($mdpath . $postcount . ".md", $content);
		//save files (custom css)
		return true;
	}
	
	public static function deletePost($draft, $id) {
		if($draft) {
			$datapath = "content/draftdata/";
			$mdpath = "content/draftmarkdown/";
		}
		else {
			$datapath = "content/postdata/";
			$mdpath = "content/markdown/";
		}
		
		$filename = $datapath . str_pad($id, 5, "0", STR_PAD_LEFT) . ".json";
		if(!File::fileExists($filename)) {
			$GLOBALS["ERROR"] = "Post doesn't exist";
			return false;
		}
		
		File::deleteFile($mdpath . $id . ".md");
		File::deleteFile($filename);
		if(!$draft) {
			$tags = Json::readJsonFile("content/tags.json");
			$newtags = array();
			//cycle through each tag, deleting any record of $id
			foreach($tags as $key=>$value) {
				if(array_values(array_diff($tags[$key], array(intval($id)))) !== array()) {
					$newtags[$key] = array_values(array_diff($tags[$key], array(intval($id))));
				}
			}
			Json::saveJsonFile($newtags,"content/tags.json");
		}
		return true;
	}
	
	public static function deleteBackup() {
		File::deleteFile("content/markdown/backup.md");
		return File::deleteFile("content/backup.json");
	}
	
	public static function saveBackup($id = false, $title, $content, $tags, $status = "draft") {
		$postdata["id"] = $id;
		$postdata["title"] = $title;
		$postdata["status"] = $status;
		$postdata["tags"] = $tags;
		
		Json::saveJsonFile($postdata,"content/backup.json");
		File::saveFile("content/markdown/backup.md", $content);
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
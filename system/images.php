<?php
class Images {

	public static function uploadImage($fileHandle) { // accepts $_FILES['images']
		if($fileHandle === null) {
			$GLOBALS["ERROR"] = "Upload failed";
			return false;
		}
		$image=$fileHandle['name'];
		if($image) {
			$filename = stripslashes($fileHandle['name']);
			$extension = strtolower(File::getFileExtension($filename));
			if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
				$GLOBALS["ERROR"] = "File must be .jpg .jpeg .png or .gif";
				return false;
			}
			else {
				$size=filesize($fileHandle['tmp_name']);
				if ($size > 1000*1024) {
					$GLOBALS["ERROR"] = "File too large";
					return false;
				}
				$time = time();
				$image_name = $time . '-' . substr($filename, 0, strrpos($filename, ".")) . '.' . $extension;
				$newname = "content/images/" . $image_name;
				
				$offset = 1;
				while(File::fileExists($newname)) {
					$image_name = $time . '-' . substr($filename, 0, strrpos($filename, ".")) . '-' . $offset . '.' . $extension;
					$newname = "content/images/" . $image_name;
					$offset++;
				}
				
				$copied = File::copyFile($fileHandle['tmp_name'], $newname);
				if (!$copied) {
					$GLOBALS["ERROR"] = "Image could not be saved.";
					return false;
				}
				//no errors
				$GLOBALS["MESSAGE"] = "Image saved successfully";
				return true;
			}
		}
	}
	
	public static function getImageList($offset, $amount) {
		$images = File::getFilesInDirectory("content/images/");
		$images = array_reverse($images);
		$output = array();
		for($i=$offset;$i<$offset+$amount;$i++) {
			if(isset($images[$i])) {
				$output[] = $images[$i];
			}
		}
		return $output;
	}
}
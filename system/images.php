<?php
class Images {

	public static function uploadImage($fileHandle) { // accepts $_FILES['images']
	$GLOBALS["directory"] = isset($GLOBALS["directory"]) ? $GLOBALS["directory"] : "";
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
				//create thumbnail for JPG JPEG or PNG
				if($extension == "jpg" || $extension == "jpeg" || $extension == "png") {
					if($extension == "jpg" || $extension == "jpeg") {
						$raw_image = imagecreatefromjpeg($GLOBALS["directory"] . $newname);
					}
					if($extension == "png") {
						$raw_image = imagecreatefrompng($GLOBALS["directory"]. $newname);
					}
					$thumb_filename = "content/images/thumbs/" . "thumb." . $image_name;
					$raw_image_meta = getimagesize($GLOBALS["directory"] . $newname);
					$raw_image_width = $raw_image_meta[0];
					$raw_image_height = $raw_image_meta[1];
					$thumb_image_width = $raw_image_width;
					$thumb_image_height = $raw_image_height;
					
					if($thumb_image_width > 600) {
						$percent_to_scale_by = ($thumb_image_width - 600) / $thumb_image_width;
						$thumb_image_width = 600;
						$thumb_image_height =  $thumb_image_height - ($thumb_image_height * $percent_to_scale_by);
					}
					
					if($thumb_image_height > 600) {
						$percent_to_scale_by = ($thumb_image_height - 600) / $thumb_image_height;
						$thumb_image_height = 600;
						$thumb_image_width = $thumb_image_width - ($thumb_image_width * $percent_to_scale_by);
					}
					
					$thumb_image = imagecreatetruecolor($thumb_image_width, $thumb_image_height);
					imagecopyresampled($thumb_image, $raw_image, 0, 0, 0, 0, $thumb_image_width, $thumb_image_height, $raw_image_width, $raw_image_height);
					if($extension == "jpg" || $extension == "jpeg") {
						imagejpeg($thumb_image, $GLOBALS["directory"] . $thumb_filename, 60);
					}
					if($extension == "png") {
						imagepng($thumb_image, $GLOBALS["directory"] . $thumb_filename, 4);
					}
					imagedestroy($thumb_image);
					imagedestroy($raw_image);
				}
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
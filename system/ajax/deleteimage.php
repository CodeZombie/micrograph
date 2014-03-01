<?php
session_start();
if(isset($_SESSION['userid']) && $_SESSION['userid'] === "admin") {
	$GLOBALS["directory"] = "../../";
	include("../header.php");
	include("../file.php");
	$img = "content/images/" . Header::getHeaderPost("image");
	if(File::fileExists($img)) {
		echo (string)File::deleteFile($img);
	}
	else {
		echo "0";
	}
} else {
	echo "403 forbidden";
}
 ?>
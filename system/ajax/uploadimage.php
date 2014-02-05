<?php
session_start();
if(isset($_SESSION['userid']) && $_SESSION['userid'] === "admin") {
	$GLOBALS["directory"] = "../../";
	include("../images.php");
	include("../file.php");
	
	return Images::uploadImage($_FILES['image']);
	
} else {
	echo "403 forbidden";
}
 ?>
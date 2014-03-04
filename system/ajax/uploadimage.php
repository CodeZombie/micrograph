<?php
session_start();
if(isset($_SESSION['userid']) && $_SESSION['userid'] === "admin") {
	//$GLOBALS["directory"] = "../../";
	include("../images.php");
	include("../file.php");
	$dat = Images::uploadImage($_FILES['image']);
	echo (string)$dat;
	//return more descriptive errors than just "1" or "null"
} else {
	echo "403 forbidden";
}
 ?>
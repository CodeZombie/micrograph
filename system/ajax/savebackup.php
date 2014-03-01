<?php
session_start();
if(isset($_SESSION['userid']) && $_SESSION['userid'] === "admin") {
	$GLOBALS["directory"] = "../../";
	include ("../database.php");
	include ("../header.php");
	include ("../file.php");
	include("../json.php");
	
	if(Header::getHeaderPost("content") == "") {
		die();
	}
	
	Database::saveBackup(Header::getHeaderPost("id"), Header::getHeaderPost("title"), Header::getHeaderPost("content"), Header::getHeaderPost("tags"));
} else {
	echo "403 forbidden";
}
?>
<?php
session_start();
if(isset($_SESSION['userid']) && $_SESSION['userid'] === "admin") {
	include ("../header.php");
	include ("../database.php");
	include ("../json.php");
	include ("../file.php");
	$error = 0;
	$error_message = "";
	$post_title = Header::getHeaderPost("post_title");
	$post_content = Header::getHeaderPost("post_content");
	$post_tags = Header::getHeaderPost("post_tags");
	
	if($post_title === "") {
		$error = 1;
		$error_message = "Title cannot be empty";
	}
	
	if($post_content === "") {
		$error = 1;
		$error_message = "Post cannot be empty";
	}
	
	if(Database::createNewPost($post_title, $post_content, $post_tags)) {
		echo "success";
	}
	
}
?>
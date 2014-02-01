<?php
session_start();
if(isset($_SESSION['userid']) && $_SESSION['userid'] === "admin") {
	include ("../header.php");
	include ("../file.php");
	include("../json.php");
	$meta = array();

	//every 30 seconds(tweakable) after a change is written, run this function to save a backup.
	//when the user manually saves the post, whether as a draft, or a publication, delete the backup files.
	//if the user then types more, a new backup file will be created, and goto 10
	//if the user closes the window before commiting a save, but after a backup is created, the system will notify them next login
	//the user will then have the option to recover said work, or discard the backup file.
	//the notification will only appear once after login. the user will have to re-login to experience the message again.
	//if the backup is of a saved draft/publication, the backup will contain the /postdata id of the adjacent post
	//if a postid is present, and the user clicks recover, they will be transported to the editpost screen.
	//if a postid is not present, the user is transported to the newpost screen, with the backup data already filled in.
	$content = Header::getHeaderPost("content");
	$meta["title"] = Header::getHeaderPost("title");
	//save more info, like tags, thumbnail image, postID (if possible), current time, 
	
	Json::saveJsonFile($meta, "../../content/backup.json");
	File::saveFile("../../content/markdown/backup.md", $content);
}
?>
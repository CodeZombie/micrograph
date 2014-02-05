<?php
//Do not include() this file. It is only to be used for on-the-fly ajax requests.
session_start();
if(isset($_SESSION['userid']) && $_SESSION['userid'] === "admin") {

	include ("../header.php");

	$markdown = Header::getHeaderPost("markdown");
	if($markdown !== "") {
		include("../parsedown.php");
		echo Parsedown::instance()->parse($markdown);
	}
} else {
echo "403 forbidden";
}
?>

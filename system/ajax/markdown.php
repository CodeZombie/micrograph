<?php
//Do not include() this file. It is only to be used for on-the-fly ajax requests.
include ("../header.php");

$markdown = Header::getHeaderPost("markdown");
if($markdown !== "") {
	include("../parsedown/parsedown.php");
	echo Parsedown::instance()->parse($markdown);
}
?>

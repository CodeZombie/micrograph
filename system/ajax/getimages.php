<?php
session_start();
if(isset($_SESSION['userid']) && $_SESSION['userid'] === "admin") {
	$GLOBALS["directory"] = "../../";
	include('../file.php');
	include('../images.php');
	include('../header.php');
	$offset = Header::getHeaderGet('offset');
	$amount = Header::getHeaderGet('amount');
	if(!$offset || $offset < 0) {
		$offset = 0;
	}
	if(!$amount || $amount <= 0) {
		$amount = 1;
	}
	
	$result = json_encode(Images::getImageList($offset,$amount));
	echo $result;
}

<?php
$GLOBALS["directory"] = substr(dirname(__FILE__), strlen(dirname($_SERVER['SCRIPT_FILENAME']))+1, strlen(dirname(__FILE__))).'/';
$GLOBALS["mg_constant_in_production"] = true;
include($GLOBALS["directory"] . '/system/json.php');
include($GLOBALS["directory"] . '/system/paginate.php');
include($GLOBALS["directory"]. '/system/posts.php');
include($GLOBALS["directory"] . '/system/database.php');
include($GLOBALS["directory"] . '/system/file.php');
include($GLOBALS["directory"] . '/system/postlist.php');
include($GLOBALS["directory"] . '/system/parsedown.php');

function mg_getPosts($amountperpage, $currentpage, $order = "asc", $tagfilter = false) {
	return new PostList($amountperpage, $currentpage, $order, $tagfilter, true);
}

function mg_getPostById($id) {
	return Database::readPostById(false,$id, true);
}

?>

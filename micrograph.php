<?php
$GLOBALS["directory"] = dirname(__FILE__).'/';
include($GLOBALS["directory"] . '/system/json.php');
include($GLOBALS["directory"] . '/system/paginate.php');
include($GLOBALS["directory"]. '/system/posts.php');
include($GLOBALS["directory"] . '/system/database.php');
include($GLOBALS["directory"] . '/system/file.php');
include($GLOBALS["directory"] . '/system/postlist.php');

function mg_getPosts($amountperpage, $currentpage, $order = "asc", $tagfilter = false) {
	return new PostList($amountperpage, $currentpage, $order, $tagfilter);
}

function mg_getPostById($id) {
	return Database::readPostById(false,$id);
}

?>

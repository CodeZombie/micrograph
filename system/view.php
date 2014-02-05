<?php
class View {

	public static function showError() {
		$_DISPLAY = true;
		if($GLOBALS["ERROR"] != "") {
			include("/../views/error.php");
		}
	}
	public static function showMessage() {
		$_DISPLAY = true;
		if($GLOBALS["MESSAGE"] != "") {
			include("/../views/message.php");
		}
	}
	public static function showRegister() {
		$_DISPLAY = true;
		include("/../views/header.php");
		include("/../views/register.php");
		include("/../views/footer.php");
	}
	
	public static function showLogin() {
		$_DISPLAY = true;
		include("/../views/header.php");
		include("/../views/login.php");
		include("/../views/footer.php");
	}
	public static function showImagePage() {
		$_DISPLAY = true;
		$active = 2;
		include("/../views/header.php");
		include("/../views/navbar.php");
		self::showError();
		self::showMessage();
		include("/../views/imagepages.php");
		include("/../views/footer.php");
	}
	
	public static function showPosts($currentPage = null) {
		$_DISPLAY = true;
		$tagFilter = Header::getHeaderGet("tag");
		
		if($tagFilter == "") {
			$tagFilter = false;
		}
		
		$resultsPerPage = Header::getHeaderGet("perpage");

		if($currentPage===null) {
			$currentPage = Header::getHeaderGet("page");
		}
		
		if($resultsPerPage!=2 && $resultsPerPage!=5 && $resultsPerPage!=10 && $resultsPerPage!=15) {
			$resultsPerPage=5;
		}
		
		$order = Header::getHeaderGet("order");
		$tgplr = new PostList($resultsPerPage, $currentPage, $order, $tagFilter);

		$active = 0;
		
		include("/../views/header.php");
		include("/../views/navbar.php");
		self::showError();
		self::showMessage();
		include("/../views/postlist.php");
		include("/../views/footer.php");
	}
	
	public static function showNewPost($backup = false) {
		$_DISPLAY = true;
		if($backup == true) {
			$backup = Json::readJsonFile("content/backup.json");
			$post_content_value = File::readFile("content/markdown/backup.md");
			$post_title_value = $backup["title"];
			$post_tags_value = $backup["tags"];
		}
		
		$active = 1;
		include("/../views/header.php");
		include("/../views/navbar.php");
		self::showError();
		self::showMessage();
		include("/../views/newpost.php");
		include("/../views/footer.php");
	}

	public static function showPostEditor($id) {
		$_DISPLAY = true;
		if($id === "backup") {
			$post = Json::readJsonFile("content/backup.json");
			$post_content_value = File::readFile("content/markdown/backup.md");
			$id = $post["id"];
			$post_tags_value = $post["tags"];
		}
		else {
			$post = Database::readPostById($id);
			$post_content_value = $post["content"];
			$post_tags_value = "";
			foreach($post["tags"] as $key => $tag) {
				$post_tags_value = $post_tags_value . $tag;
				if($key !== count($post["tags"])-1) {
					$post_tags_value = $post_tags_value . ", "; 
				}
			}
		}
		
		$post_title_value = $post["title"];

		
		$active = -1;
		include("/../views/header.php");
		include("/../views/navbar.php");
		self::showError();
		self::showMessage();
		include("/../views/posteditor.php");
		include("/../views/footer.php");
	}
	
	public static function showSettings() {
		$_DISPLAY = true;
		$active = 3;
		include("/../views/header.php");
		include("/../views/navbar.php");
		self::showError();
		self::showMessage();
		include("/../views/settings.php");
		include("/../views/footer.php");
	}
}
<?php
/*
TODO:
	Make tag searching case insensitive
	Create a Draftdata folder. and handle drafts the same way as posts, but totally isolate them from posts (create a "drafts" tab in the nav)
	
*/
DEFINE("SESSION_TIMEOUT_TIME",14400);

include('system/bcrypt.php');
include('system/header.php');
include('system/json.php');
include('system/paginate.php');
include('system/user.php');
include('system/view.php');
include('system/posts.php');
include('system/database.php');
include('system/file.php');
include('system/images.php');
include('system/exposed/tgpostlistrequest.php');

$GLOBALS["ERROR"] = "";
$GLOBALS["MESSAGE"] = "";

session_start();

File::createFileIfNotExist("content/tags.json");

if(User::isLoggedIn()) {
	switch (Header::getHeaderGet('action')) {
		case "logout":
			User::logout();
			View::showLogin();
			break;
		case "posts":
			if(Database::getNumberOfPosts() === 0 ) {
				$GLOBALS["ERROR"] = "No posts to show";
				View::showNewPost();
				break;
			}
			View::showPosts();
			break;
		case "newpost":
			View::showNewPost();
			break;
		case "images":
			View::showImagePage();
			break;
		case "editpost":
			if(!Database::postExistsById(Header::getHeaderGet('id'))) {
				$GLOBALS["ERROR"] = "Post does not exist";
				View::showPosts();
			}
			else {
				View::showPostEditor(Header::getHeaderGet('id'));
			}
			break;
		case "savepost":
			//save backup (in case savePost returns false)
			Database::saveBackup(false, Header::getHeaderPost('post_title'), Header::getHeaderPost('post_content'), Header::getHeaderPost('post_tags'));
			if(Database::savePost(Header::getHeaderPost('post_title'), Header::getHeaderPost('post_content'), Header::getHeaderPost('post_tags'))) {
				Database::deleteBackup();
				View::showPosts("last");
			}
			else {
				//view NewPost screen, but show backed up data in the fields
				View::showNewPost(true);
			}
			break;
		case "savepostedit":
			Database::saveBackup(Header::getHeaderGet('id'), Header::getHeaderPost('post_title'), Header::getHeaderPost('post_content'), Header::getHeaderPost('post_tags'));
			if(Database::savePost(Header::getHeaderPost('post_title'), Header::getHeaderPost('post_content'), Header::getHeaderPost('post_tags'),"published",false,Header::getHeaderGet('id'))) {
				Database::deleteBackup();
				View::showPosts();
			}
			else {
				View::showPostEditor("backup");
			}
			break;
		case "deletepost":
			if(!Database::postExistsById(Header::getHeaderGet('id'))) {
				$GLOBALS["ERROR"] = "Post does not exist";
			}
			else {
				Database::deletePost(Header::getHeaderGet('id'));
			}
			View::showPosts();
			break;
		case "uploadimage":
			Images::uploadImage($_FILES['image']);
			View::showImagePage();
			break;
		case "recoverbackup":
			if(File::fileExists("content/backup.json")) {
				if(Json::readJsonFile("content/backup.json")["id"]===false) {
					View::showNewPost(true);//show new post screen with the backup data loaded
				}
				else {
					View::showPostEditor("backup");//show new post screen with the backup data loaded
				}
			} else {
				$GLOBALS["ERROR"] = "No backup file to recover";
				View::showPosts();
			}
			break;
		case "deletebackup":
			if(!Database::deleteBackup()) {
				$GLOBALS["ERROR"] = "Could not delete backup file";
			}
			View::showPosts();
			break;
		default:
			View::showPosts();
			break;
	}
	if($_SESSION['timeout'] + SESSION_TIMEOUT_TIME < time()) {
		User::logout();
	}
}
else {
	if(file_exists("config/login.conf.php")) {
		if(Header::getHeaderGet('action') == "trylogin" && User::tryLogin(Header::getHeaderPost('username'),Header::getHeaderPost('password'))) {
			$_SESSION['timeout'] = time();
			View::showPosts();
		}
		else {
			View::showLogin();
		}
	}
	else {
		if(Header::getHeaderGet('action') == "tryregister" && User::tryRegister(Header::getHeaderPost('username'),Header::getHeaderPost('password_one'),Header::getHeaderPost('password_two'))) {
				View::showLogin();
		}
		else {
			View::showRegister();
		}
	}
}
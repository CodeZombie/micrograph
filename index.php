<?php
/*
TODO:
	Image page pagination
	Organize the code in a more obvious way
	Refactor redundant code (especially in the views (why are there 3 similar post editor views) and in the post/draft database functionality)
		+ consolidate Post and Draft functionality into a single base
	Automatically save backups of posts & drafts while editing
		+ When you type a character in the content box, a timer starts counting down.
		+ If it counts down to 0 from 15 seconds, save a backup.
	Settings page
		+ Option to change username/password
		+ Button to download entire blog archive as a zip
	API calls
	Fix in-editor image uploading
	
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
include('system/postlist.php');
include('system/draftlist.php');

$GLOBALS["ERROR"] = "";
$GLOBALS["MESSAGE"] = "";

session_start();

File::createFileIfNotExist("content/tags.json");

$requiredFolder = array("content/draftdata/","content/draftmarkdown/","content/images/","content/markdown/","content/postdata/");
foreach($requiredFolder as $folder) {
	if(!File::fileExists($folder)) {
		die("<b>Fatal Error:</b> Directory <em>" . $folder . "</em> does not exist. Please create it with proper permissions.");
	}
}
function goHome($priority = "post") {

	if($priority == "draft") {
		if(Database::getNumberOfDrafts() === 0 ) {
			$GLOBALS["ERROR"] = "No drafts to show";
			if(Database::getNumberOfPosts() === 0 ) {
				View::showNewPost();
				return true;
			}
			else {
				View::showPosts();
				return true;
			}
		}
		else {
			View::showDrafts();
			return true;
		}
	}
	if($priority == "post") {
		if(Database::getNumberOfPosts() === 0 ) {
			$GLOBALS["ERROR"] = "No posts to show";
			if(Database::getNumberOfDrafts() === 0 ) {
				View::showNewPost();
				return true;
			}
			else {
				View::showDrafts();
				return true;
			}
		}
		else {
			View::showPosts();
			return true;
		}
	}
	
}

if(User::isLoggedIn()) {
	switch (Header::getHeaderGet('action')) {
		case "logout":
			User::logout();
			View::showLogin();
			break;
		case "posts":
			goHome();
			break;
		case "drafts":
			goHome("draft");
		break;
		case "newpost":
			View::showNewPost();
			break;
		case "images":
			View::showImagePage();
			break;
		case "editdraft":
			if(!Database::draftExistsById(Header::getHeaderGet('id'))) {
				$GLOBALS["ERROR"] = "Draft does not exist";
				View::showDrafts();
			}
			else {
				View::showDraftEditor(Header::getHeaderGet('id'));
			}
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
			Database::saveBackup(false, Header::getHeaderPost('post_title'), Header::getHeaderPost('post_content'), Header::getHeaderPost('post_tags'));
			if(Header::getHeaderPost('publishbutton')!==false) {
				if(Database::savePost(Header::getHeaderPost('post_title'), Header::getHeaderPost('post_content'), Header::getHeaderPost('post_tags'))) {
					Database::deleteBackup();
					View::showPosts("last");
				}
				else {
					//view NewPost screen, but show backed up data in the fields
					View::showNewPost(true);
				}
			}
			elseif(Header::getHeaderPost('draftbutton')!==false) {
				if(Database::saveDraft(Header::getHeaderPost('post_title'),Header::getHeaderPost('post_content'),Header::getHeaderPost('post_tags'))) {
					Database::deleteBackup();
					View::showDrafts("last");
				} 
				else {
					View::showNewPost(true);
				}
			}
			else {
				View::showNewPost(true);
			}
			break;
		case "savedraftedit":
			Database::saveBackup(Header::getHeaderGet('id'), Header::getHeaderPost('post_title'), Header::getHeaderPost('post_content'), Header::getHeaderPost('post_tags'));
			if(Header::getHeaderPost('publishbutton')!==false) {
				if(Database::savePost(Header::getHeaderPost('post_title'), Header::getHeaderPost('post_content'), Header::getHeaderPost('post_tags'))) {
					Database::deleteBackup();
					Database::deleteDraft(Header::getHeaderGet('id'));
					View::showPosts("last");
				}
				else {
					//view NewPost screen, but show backed up data in the fields
					View::showNewPost(true);
				}
			}
			elseif(Header::getHeaderPost('draftbutton')!==false) {
				if(Database::saveDraft(Header::getHeaderPost('post_title'),Header::getHeaderPost('post_content'),Header::getHeaderPost('post_tags'), Header::getHeaderGet('id'))) {
					Database::deleteBackup();
					View::showDrafts("last");
				} 
				else {
					View::showNewPost(true);
				}
			}
			else {
				View::showNewPost(true);
			}
			break;
		case "savepostedit":
			Database::saveBackup(Header::getHeaderGet('id'), Header::getHeaderPost('post_title'), Header::getHeaderPost('post_content'), Header::getHeaderPost('post_tags'));
			if(Header::getHeaderPost('publishbutton')!==false) {
				if(Database::savePost(Header::getHeaderPost('post_title'), Header::getHeaderPost('post_content'), Header::getHeaderPost('post_tags'),"published",false,Header::getHeaderGet('id'))) {
					Database::deleteBackup();
					View::showPosts();
				}
				else {
					View::showPostEditor("backup");
				}
			}
			elseif(Header::getHeaderPost('draftbutton')!==false) {
				if(Database::saveDraft(Header::getHeaderPost('post_title'),Header::getHeaderPost('post_content'),Header::getHeaderPost('post_tags'))) {
					Database::deletePost(Header::getHeaderGet('id'));
					Database::deleteBackup();
					View::showDrafts("last");
				} 
				else {
					View::showNewPost(true);
				}
			}
			else {
				View::showPostEditor("backup");
			}
			break;
		case "deletedraft":
			if(!Database::draftExistsById(Header::getHeaderGet('id'))) {
				$GLOBALS["ERROR"] = "Draft does not exist";
			}
			else {
				Database::deleteDraft(Header::getHeaderGet('id'));
			}
			goHome("draft");
			break;
		case "deletepost":
			if(!Database::postExistsById(Header::getHeaderGet('id'))) {
				$GLOBALS["ERROR"] = "Post does not exist";
			}
			else {
				Database::deletePost(Header::getHeaderGet('id'));
			}
			goHome();
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
			goHome();
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
			goHome();
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
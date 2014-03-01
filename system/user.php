<?php 
class User {
	
	public static function isLoggedIn() {
		if(isset($_SESSION['userid']) && $_SESSION['userid'] == "admin") {
				return true;
			}
		return false;
	}
	public static function tryRegister($username, $password_one, $password_two) {
		if($username == "") {
			$GLOBALS["ERROR"] = "Please enter a username";
			return false;
		}
		
		if((preg_match("/[^a-zA-Z0-9-_]+/i",$username)!=0)) {
			$GLOBALS["ERROR"] = "Username contained illegal characters";
			return false;
		}
		
		if(strlen($password_one) <= 3)
		{
			$GLOBALS["ERROR"] = "Password too short";
			return false;
		}
		
		if($password_one != $password_two) {
			$GLOBALS["ERROR"] = "Passwords do not match";
			return false;
		}
		
		$arr = array();
		$arr['username'] = $username;
		$arr['pwhash'] = bcrypt_hash($password_one);
		
		if(!Json::saveJsonFile($arr, "config/login.conf.php", true)) {
			$GLOBALS["ERROR"] = "Error saving <i>login.conf.php</i> file. Check permissions.";
			return false;
		}
		return true;
	}	
	public static function tryLogin($usernametotry, $passwordtotry) {
		$credentials = Json::readJsonFile("config/login.conf.php", true);
		$realUsername =  $credentials['username'];
		$realPwhash =  $credentials['pwhash'];
		unset($credentials);
		
		if($usernametotry == "") {
			$GLOBALS["ERROR"] = "Please enter your username";
			return false;
		}

		if($passwordtotry == "") {
			$GLOBALS["ERROR"] = "Please enter your password";
			return false;
		}
		sleep(1.25);
		
		if($usernametotry==$realUsername && bcrypt_check($passwordtotry, $realPwhash)) {
			$_SESSION['userid'] = "admin";
			return true;
		}
		else {
			$GLOBALS["ERROR"] = "Username or Password was incorrect";
			return false;
		}
	}
	
	public static function logout() {
		//unsets session vars
		$_SESSION = array();
		//deletes session cookies
		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000,
				$params["path"], $params["domain"],
				$params["secure"], $params["httponly"]
			);
		}
		//destroys session
		session_destroy();
		return true;
	}

}
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
		$log = Json::readJsonFile("config/log.conf.php",true);
		$ip = $_SERVER['REMOTE_ADDR'];
		
		foreach($log as $key => $val) {
			if($val['time'] + 1800 < time()) {
				$log = array_splice($log,array_search($ip,$log) + 1,1);
			}
		}
		
		if(isset($log[$ip])) {
			if($log[$ip]['attempts'] >= 10) {
				die("Too many login attempts. please wait before trying again. " . (string)(round(($log[$ip]['time'] + 1800 - time())/60,1)) . " minutes remaining");
			}
		}
		
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
		
		if($usernametotry==$realUsername && bcrypt_check($passwordtotry, $realPwhash)) {
			$_SESSION['userid'] = "admin";
			if(isset($log[$ip])) {
				$newdata = array_splice($log,(1+array_search($ip,$log)),1);
			}
			else {
				$newdata = $log;
			}
			Json::saveJsonFile($newdata, "config/log.conf.php", true);
			return true;
		}
		else {
			$data = array();
			if(isset($log[$ip]['attempts'])) {
				$data[$ip]['attempts'] = $log[$ip]['attempts'] + 1;
			}
			else {
				$data[$ip]['attempts'] = 1;
			}
			$data[$ip]['time'] = time();
			if($log != "") {
				$newdata = array_merge($log,$data);
			}
			else{
				$newdata = $data;
			}
			Json::saveJsonFile($newdata, "config/log.conf.php", true);
			$attempts_left = 9;
			if(isset($log[$ip])) {
				$attempts_left = 10 - ($log[$ip]['attempts']+1);
			}
			$GLOBALS["ERROR"] = "Username or Password was incorrect. " . (string)($attempts_left) . " attempts remaining";
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
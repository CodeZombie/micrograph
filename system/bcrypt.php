<?php
/*
  By Marco Arment <me@marco.org>.
  https://gist.github.com/marcoarment/1053158
  
  With legacy modification by jportoles 
  https://gist.github.com/jportoles
  
  This code is released in the public domain.
  ABSOLUTELY NO WARRANTY OR GUARANTEE IS IMPLIED OR PROVIDED.
*/

function bcrypt_hash($password, $work_factor = 8) {

    if (version_compare(PHP_VERSION, '5.3') < 0) {
		throw new Exception('Bcrypt requires PHP 5.3 or above');
	}
	
    if ($work_factor < 4 || $work_factor > 31){
		$work_factor = 8;
	}
	
    $salt = '$2a$' . str_pad($work_factor, 2, '0', STR_PAD_LEFT) . '$' . substr(strtr(base64_encode(pseudoRandomKey(16)), '+', '.'), 0, 22);
    return crypt($password, $salt);
}
function bcrypt_check($password, $stored_hash, $legacy_handler = NULL) {

    if (version_compare(PHP_VERSION, '5.3') < 0) {
		throw new Exception('Bcrypt requires PHP 5.3 or above');
	}
	
    if (bcrypt_is_legacy_hash($stored_hash)) {
		if ($legacy_handler) return call_user_func($legacy_handler, $password, $stored_hash);
		else throw new Exception('Unsupported hash format');
    }
	
    return crypt($password, $stored_hash) == $stored_hash;
}
function pseudoRandomKey($size) {

	if (function_exists('openssl_random_pseudo_bytes')) {
		$rnd = openssl_random_pseudo_bytes($size, $strong);
		if($strong === TRUE) return $rnd;
	}
	
	$sha=''; $rnd='';
	
	for ($i=0;$i<$size;$i++) {
		$sha = hash('sha256',$sha.mt_rand());
		$char = mt_rand(0,62);
		$rnd .= chr(hexdec($sha[$char].$sha[$char+1]));
	}
	
	return $rnd;
}
function bcrypt_is_legacy_hash($hash) {
	return substr($hash, 0, 4) != '$2a$'; 
}
?>

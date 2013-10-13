<?php
date_default_timezone_set('Asia/Kolkata');

if (!defined('__ROOT__')) { define('__ROOT__', dirname(dirname(__FILE__))); }

/* http://www.php.net/manual/en/function.is-array.php#102652 */
function is_assoc ($arr) {
	return (is_array($arr) && count(array_filter(array_keys($arr),'is_string')) == count($arr));
}

function uses_arg($argName, $default = null, $globalName = '') {
	if (isset($_REQUEST[$argName])) {
		$val = $_REQUEST[$argName];
	} else {
		$val = $default;
	}
	if ($globalName) {///what is $globalName
		$GLOBALS[$globalName] = $val;
	} else {
		$GLOBALS[$argName] = $val;
	}
}

function requires_arg($argName, $globalName = '') {
	if (!isset($_REQUEST[$argName])) {///$_REQUEST contains $_POST,$_GET,$_COOKIE
		throw new Exception("Argument $argName not found.");
	}
	uses_arg($argName, null, $globalName);
}

function get_post($argName, $default=null) {
	if (isset($_POST[$argName])) {
		return $_POST[$argName];
	} else {
		return $default;
	}
}

function uses_post($argName, $default=null, $globalName = '') {
	$val = get_post($argName, $default);
	
	if ($globalName) {
		$GLOBALS[$globalName] = $val;
	} else {
		$GLOBALS[$argName] = $val;
	}
}

function is_submission($check) {
	return isset($_POST[$check]);
}

function uses_models($models) {
	foreach($models as $model) {
		require_once __ROOT__."/models/$model.php";
	}
}

function redirect($page) {
	$path = pathinfo($_SERVER['PHP_SELF'], PATHINFO_DIRNAME);
	header(sprintf('Location: http://%s%s%s', $_SERVER['HTTP_HOST'], $path, $page));
	exit;
}

function no_cache() {
	header("Cache-Control: no-cache, no-store, must-revalidate");
	header('Pragma: no-cache');
	header("Expires: 0");
}

function notEmpty($var) {
	return !empty($var);
}

function sendMail($to,$from,$subject,$body){
	try {
		ini_set('SMTP', 'localhost');
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= "From: <$from@commvault.com>\r\n";
		mail($to, $subject, $body, $headers);
	}
	catch(Exception $e) {
		die(sprintf('{"success":false,"error":%s}', json_encode($e->getMessage())));
	}
}

?>
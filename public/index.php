<?php session_start();
define("BASE_DIR", dirname(dirname(__FILE__))."/");
define("BASE_URL", "http://127.0.0.1:8001/");

define("SETTINGS_FILE" , BASE_DIR."settings.php");
require_once(BASE_DIR."vendor/autoload.php");

use ResBooker\Lib\Registry;
use ResBooker\Lib\Database;

$reg = new Registry();
$db = new Database();
if($db->error == null){
	$reg->db = $db;
}else{
	die("System error - failed to connect to the database server");
}

$request_uri = $_SERVER['REQUEST_URI'];

if($request_uri == "/"){
	$url_segments = [];
}else{
    $url_segments = explode("/", $request_uri);
}

$total_segemets = count($url_segments);
$controller = "Reservations";
$action = "index";



if($total_segemets == 2){
	$controller = $url_segments[1];
	$action = "index";
}elseif($total_segemets >= 3){
	$controller = $url_segments[1];
	$action = $url_segments[2];
}

$allowed_routes = require_once(BASE_DIR."routes.php");

if(in_array($controller. "#" . $action, $allowed_routes)){
	$controller_class_name = "ResBooker\\Controllers\\".$controller;
	$reg->params = $url_segments;
	$controller_class =  new $controller_class_name($reg);
	$controller_class->$action();
}else{

	print "404";
}



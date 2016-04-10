<?php session_start();

// define useful constants and load composers autoloader 
define("BASE_DIR", dirname(dirname(__FILE__))."/");

define("SETTINGS_FILE" , BASE_DIR."settings.php");
require_once(BASE_DIR."vendor/autoload.php");

use ResBooker\Lib\Registry;
use ResBooker\Lib\Database;
use ResBooker\Config\Settings;

// For access in templates/views
define("BASE_URL", Settings::$website_url);
/** 
Registry implements a simple Singleton pattern enabling the application,
to share data, objects etc.. at runtime between.
**/

$reg = new Registry();
$db = new Database();
if($db->error == null){
	/** Add the database adpater to our registry so that we maitain one database connection
	   at all times.
	**/
	$reg->db = $db;
}else{
	die("System error - failed to connect to the database server");
}

// Get the current URL in the address bar
$request_uri = $_SERVER['REQUEST_URI'];

/**
We split the request_uri by / to create an array of URL
segments, by doing so when than can access the controller , action
and any other get parameters.
**/
if($request_uri == "/"){
	$url_segments = [];
}else{
    $url_segments = explode("/", $request_uri);
}

$total_segemets = count($url_segments);
// setup a default route
$controller = "Reservations";
$action = "index";


/**
if the total segements derived from request_uri is only 2 that means 
we only have a controller name and should default the action to index.

Should we have 3 segements than we also have the action method as well.
**/
if($total_segemets == 2){
	$controller = $url_segments[1];
	$action = "index";
}elseif($total_segemets >= 3){
	$controller = $url_segments[1];
	$action = $url_segments[2];
}

// routes.php is a simple array that returns a list of allowed routes
$allowed_routes = require_once(BASE_DIR."routes.php");

// check if the current controller and action is found in our allowed routes
if(in_array($controller. "#" . $action, $allowed_routes)){
	/**
	since this route is allowed , we instantiate the controller
	which takes a single argument i.e. the registry object.
	We also store the url_segements array in the registry for late use
	when we need to access get variable information.
	**/
	$controller_class_name = "ResBooker\\Controllers\\".$controller;
	$reg->params = $url_segments;
	$controller_class =  new $controller_class_name($reg);

	//finally execute the controller action
	$controller_class->$action();
}else{
	/**
	 TO-DO - design a better 404 page , for the purposes of this execise i didn't spend to much
	 time on design. 
    **/
	print "404";
}



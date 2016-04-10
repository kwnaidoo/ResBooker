<?php
namespace ResBooker\Config;
// Simple class to keep track of all application settings
// example usage Settings::setting_name , Settings::$database

class Settings{
	public static $website_url = "http://127.0.0.1:8001/";
	public static $database = [
	    "DSN" => "mysql:host=localhost;port=3307;dbname=resbooker",
	    "USERNAME" => "",
	    "PASSWORD" => ""
	];
}

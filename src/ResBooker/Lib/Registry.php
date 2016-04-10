<?php
namespace Resbooker\Lib;

/**
A Singleton class to maintain application level state i.e. allow
sharing of data , objects, arrays etc... between controllers, models
and other framework classes.

This ensures that only one instance of those bits of data exists in the 
current application lifecircle.

**/
class Registry{
	private $data = [];

	public function __set($key, $value){
		$this->data[$key] = $value;
	}

	public function __get($key){
		if(array_key_exists($key, $this->data)){
			return $this->data[$key];
		}else{
			return null;
		}
	}
}
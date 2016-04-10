<?php
namespace Resbooker\Lib;

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
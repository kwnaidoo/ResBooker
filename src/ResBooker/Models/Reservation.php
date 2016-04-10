<?php
namespace ResBooker\Models;
use ResBooker\Models\Model;

class Reservation extends Model{

	public function validate_client_first_name($sender){
		$valid = True;
		$this->validation_errors = [];
		if(strlen($this->client_first_name) < 3){
			$this->validation_errors[] = "First name is invalid";
			$valid = False;
		}
		return $valid;
	}

	public function validate_client_surname($sender){
		$valid = True;
		$this->validation_errors = [];
		if(strlen($this->client_surname) < 3){
			$this->validation_errors[] = "Surname is invalid";
			$valid = False;
		}
		return $valid;
	}

	public function validate_client_email_address($sender){
		$valid = True;
		$this->validation_errors = [];
		if(!filter_var($this->client_email_address, FILTER_VALIDATE_EMAIL)){
			$this->validation_errors[] = "Email is invalid.";
			$valid = False;
		}
		return $valid;
	}


	
}
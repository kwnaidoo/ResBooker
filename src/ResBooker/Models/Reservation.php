<?php
namespace ResBooker\Models;
use ResBooker\Models\Model;

// Maps to the reservations table and stores a sumary of the reservation
class Reservation extends Model{

	/**
	   a bunch of validators checking string lengths and 
	   if the email is valid. A lot more validation can be
	   added to improve the quality of data but for now these
	   are the three most important fields. 

	**/
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
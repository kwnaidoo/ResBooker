<?php
namespace ResBooker\Controllers;
use ResBooker\Controllers\Controller;
use ResBooker\Models\Reservation;
use ResBooker\Models\Room;
use ResBooker\Models\ReservedRoom;
/**
  Reservations is currently the applications main controller 
  and handles all the processing related to making a reservation
  and displaying the websites fronted.
**/
class Reservations extends Controller{

	public function index(){

		/** 
		The homepage has a form to make a reservation, should
		this form be already filled in , just take the user
		straight to the search rooms section since we already
		have all their contact information.

		There is a cancel button should they wish to refresh their
		reservation.

		**/

		if(isset($_SESSION['reservation_id'])){
			$this->redirect("Reservations/search_rooms#reservation");
		}else{
			$this->render("reservations/form");
		}
	    
	}

	/**
	 When the homepage form is submitted , the data is sent to this method,
	 the method will than extract all the form data and create a record
	 in our reservations table.

	 Should the above process be successful , a reservation ID is returned and stored
	 in the session and as well any other information we need for later usage.

	 This method is fully ajax driven and therefore will return a JSON response.

	**/

	function capture_details(){
		// instantiatea reservation object
		$res = new Reservation($this->reg);

		//populate the object will the form data
		$res->client_first_name  = $_POST['client_first_name'];
		$res->client_surname  = $_POST['client_surname'];
		$res->client_email_address = $_POST['client_email_address'];
		$res->check_in_datetime = $_POST['check_in_datetime'];
		$res->check_out_datetime = $_POST['check_out_datetime'];
		$res->client_telephone = $_POST['client_telephone']; 

		//store the inserted ID
		$saved_id = $res->save();

		if($saved_id == -1){
			// if something went wrong - return the relevant error message
			print json_encode(['msg' => 'failed' , 'errors' => $res->validation_errors]);
		}else{
			// since everything went well , we set up the session variables
			$_SESSION['rooms_to_book'] = [];
			$_SESSION['total_cost'] = 0;
			$_SESSION['reservation_id'] = $saved_id;
			$_SESSION['check_in_datetime'] = $_POST['check_in_datetime'];
			$_SESSION['check_out_datetime'] = $_POST['check_out_datetime'];

			// send the browser the URL to the room search page.
			print json_encode(['msg' => 'success' , 'url' => BASE_URL."Reservations/search_rooms#reservation"]);
		}

	}

	/**

		This method is responsible for returning a paginated list
		of rooms for the user to browse through and add to their 
		reservation.

		TO DO -- implement a better "No results" when no records are found.
	**/
	function search_rooms(){

		// Instatiate a room object and setup variables
		$room = new Room($this->reg);
		$rooms = [];
		$page = 1;
		$per_page = 5;
		$next = -1;
		$prev = -1;

		// Query the db to count all available rooms for this time period.
		$total = $room->total_rooms($_SESSION['check_in_datetime'], 
			$_SESSION['check_out_datetime']);

		// calculate total pages by diving the total records from our db by the limit per page.
		if($total > 0){
			$total_pages = ceil($total / $per_page);

			// if params[3] exists than we got a page number which we need to store in $page
			if(isset($this->reg->params[3])){
				$page = (int)$this->reg->params[3];
			}

			if($page < 1){ $page = 1;}
			if($page > $total_pages) { $page = $total_pages;}
			$offset = ($page-1) * $per_page;

			// finally get an array of rooms by the time period and limit based on calculations above.

			$rooms = $room->get_rooms($_SESSION['check_in_datetime'], 
				$_SESSION['check_out_datetime'], $offset, $per_page);

			if($page > 1){
				$prev = $page - 1;
			}
			if($page < $total_pages){
				$next = $page + 1;
			}

		}

		//render the search view and pass through all the data needed to display.
		$this->render("reservations/search", [
			'rooms' => $rooms, "prev" => $prev, "next" => $next, 
			'total_cost' => $_SESSION["total_cost"], 
			'total_rooms' => count($_SESSION['rooms_to_book']),
			'rooms_to_book' => $_SESSION['rooms_to_book']
		]);

	}

	/**
		This method either adds or removes a room from
		the current reservation based on whether the room
		exists in our reservation or not , if it does - the room is
		removed and the relevant counters updated. If it doesn't
		the room will be added and the relevant counters updated.

		The HTML button will change it self to "SELECT ROOM" or "DESELECT ROOM"
		depending on the response from this method.

		The method is AJAX driven and therefore returns JSON as a response.
	**/
	function toggle_room(){
		// get the room ID posted to us via the AJAX call
		$room_id = (int)$_POST['room_id'];

		// setup our response to the browser variable and other variables
		$response = [];
		$room_obj = new Room($this->reg);
		$room = $room_obj->get_by("id", $room_id);
		$response["msg"] = "failed";

		// if a room was found matching this room id proceed
		if($room){

			// if we have this room already remove it otherwise add it.
			// update the total cost as well.
			// action_type will tell the JavaScript which button to show.

			if(in_array($room_id, $_SESSION['rooms_to_book'])){
				$key = array_search($room_id, $_SESSION['rooms_to_book']);
				unset($_SESSION['rooms_to_book'][$key]);
				$response["action_type"] = "removed";
				$_SESSION["total_cost"] -= $room->price_normal;
			}else{
				$response["action_type"] = "added";
				$_SESSION['rooms_to_book'][] = $room_id;
				$_SESSION["total_cost"] += $room->price_normal;
			}
			$response["msg"] = "success";
		
        }
    	$response['rooms'] = $_SESSION['rooms_to_book'];
	    $response['total_cost'] = $_SESSION["total_cost"];
	    $response['total_rooms'] = count($_SESSION['rooms_to_book']);
		print json_encode($response);
   }

   /**
	  This is the final step in our reservation process , once
	  the client is happy they click a confirm button on the view side
	  which triggers this method.
   **/

   function confirm(){
   	// setup variables
   	$res = new Reservation($this->reg);
   	$room = new Room($this->reg);
   	$response = [];

   	//update the reservation table with the total cost and set the date of the booking.
    $res->total_cost = $_SESSION['total_cost'];
   	$res->no_rooms_booked = count($_SESSION['rooms_to_book']);
   	$res->date_of_booking = date("Y-m-d");
   	$updated = $res->update("id", $_SESSION['reservation_id']);

   	/**
   	  if the reservation table updated fine than loop through all the
   	  the rooms in our reservation and add them to our reserved_rooms table
   	**/
   	if($updated){

   	    foreach($_SESSION['rooms_to_book'] as $room_id){
   	    	$rroom = new ReservedRoom($this->reg);
   	    	$rroom->room_id = $room_id;
   	    	$rroom->reservation_id =  $_SESSION['reservation_id'];
   	    	$rroom->check_in_datetime = $_SESSION['check_in_datetime'];
   	    	$rroom->check_out_datetime = $_SESSION['check_out_datetime'];
   	    	$rroom->save();
   	    }
   	    // clear our session
   	    $_SESSION['rooms_to_book'] = [];
   	    $_SESSION['total_cost'] = 0;
   	    $_SESSION['reservation_id'] = null;
   	    $_SESSION['check_in_datetime'] = null;
   	    $_SESSION['check_out_datetime'] = null;
   	    $_SESSION['total_cost'] = 0;

   	    // message the user that the operation was successful
   	    $response['heading'] = "Successfully placed reservation";
   	    $response['message'] = "Thank you! - we have successfully received
   	    your reservation and will be touch shortly.";
   	}else{

   		// tell the user that their attempt to make a reservation failed
   		$response['heading'] = "Failed to place your reservation";
   		$response['message'] = "Unfortunately an unknow error prevented the 
   		submission of your reservation. Please click the below link to try again:<br />
   		<a href=\"BASE!Reservations/search_rooms#reservation\">Go back to reservation</a>";
   		$response['message'] = str_replace("BASE!", BASE_URL, $response['message']);
   	}
   	return $this->render("reservations/message", $response);
 }

// simple method to delete the current reservation and destory session if user cancels.
 function cancel(){
 	$res = new Reservation($this->reg);
 	if(isset($_SESSION['reservation_id'])){
 	    $res->delete_by_id($_SESSION['reservation']);
 	    $_SESSION = array();
 	    
 	}
 	return $this->redirect("Reservations/index#reservation");
 }

   

}
<?php
namespace ResBooker\Controllers;
use ResBooker\Controllers\Controller;
use ResBooker\Models\Reservation;
use ResBooker\Models\Room;
use ResBooker\Models\ReservedRoom;
class Reservations extends Controller{

	public function index(){


		if(isset($_SESSION['reservation_id'])){
			$this->redirect("Reservations/search_rooms#reservation");
		}else{
			$this->render("reservations/form");
		}
	    
	}

	function capture_details(){
		$res = new Reservation($this->reg);
		$res->client_first_name  = $_POST['client_first_name'];
		$res->client_surname  = $_POST['client_surname'];
		$res->client_email_address = $_POST['client_email_address'];
		$res->check_in_datetime = $_POST['check_in_datetime'];
		$res->check_out_datetime = $_POST['check_out_datetime'];
		$res->client_telephone = $_POST['client_telephone']; 
		$saved_id = $res->save();

		if($saved_id == -1){
			print json_encode(['msg' => 'failed' , 'errors' => $res->validation_errors]);
		}else{
			$_SESSION['rooms_to_book'] = [];
			$_SESSION['total_cost'] = 0;
			$_SESSION['reservation_id'] = $saved_id;
			$_SESSION['check_in_datetime'] = $_POST['check_in_datetime'];
			$_SESSION['check_out_datetime'] = $_POST['check_out_datetime'];
			print json_encode(['msg' => 'success' , 'url' => BASE_URL."Reservations/search_rooms#reservation"]);
		}

	}

	function search_rooms(){
		$room = new Room($this->reg);
		$rooms = [];
		$page = 1;
		$per_page = 5;
		$next = -1;
		$prev = -1;

		$total = $room->total_rooms($_SESSION['check_in_datetime'], 
			$_SESSION['check_out_datetime']);
		if($total > 0){
			$total_pages = ceil($total / $per_page);

			if(isset($this->reg->params[3])){
				$page = (int)$this->reg->params[3];
			}

			if($page < 1){ $page = 1;}
			if($page > $total_pages) { $page = $total_pages;}
			$offset = ($page-1) * $per_page;
			$rooms = $room->get_rooms($_SESSION['check_in_datetime'], 
				$_SESSION['check_out_datetime'], $offset, $per_page);

			if($page > 1){
				$prev = $page - 1;
			}
			if($page < $total_pages){
				$next = $page + 1;
			}

		}

		$this->render("reservations/search", [
			'rooms' => $rooms, "prev" => $prev, "next" => $next, 
			'total_cost' => $_SESSION["total_cost"], 
			'total_rooms' => count($_SESSION['rooms_to_book']),
			'rooms_to_book' => $_SESSION['rooms_to_book']
		]);

	}

	function add_rooms(){
		$room_id = (int)$_POST['room_id'];
		$response = [];
		$room_obj = new Room($this->reg);
		$room = $room_obj->get_by("id", $room_id);
		$response["msg"] = "failed";

		if($room){
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

   function confirm(){
   	$res = new Reservation($this->reg);
   	$room = new Room($this->reg);
   	$response = [];

    $res->total_cost = $_SESSION['total_cost'];
   	$res->no_rooms_booked = count($_SESSION['rooms_to_book']);
   	$res->date_of_booking = date("Y-m-d");
   	$updated = $res->update("id", $_SESSION['reservation_id']);

   	if($updated){

   	    foreach($_SESSION['rooms_to_book'] as $room_id){
   	    	$rroom = new ReservedRoom($this->reg);
   	    	$rroom->room_id = $room_id;
   	    	$rroom->reservation_id =  $_SESSION['reservation_id'];
   	    	$rroom->check_in_datetime = $_SESSION['check_in_datetime'];
   	    	$rroom->check_out_datetime = $_SESSION['check_out_datetime'];
   	    	$rroom->save();
   	    }
   	    $_SESSION['rooms_to_book'] = [];
   	    $_SESSION['total_cost'] = 0;
   	    $_SESSION['reservation_id'] = null;
   	    $_SESSION['check_in_datetime'] = null;
   	    $_SESSION['check_out_datetime'] = null;
   	    $_SESSION['total_cost'] = 0;
   	    $response['heading'] = "Successfully placed reservation";
   	    $response['message'] = "Thank you! - we have successfully received
   	    your reservation and will be touch shortly.";
   	}else{
   		$response['heading'] = "Failed to place your reservation";
   		$response['message'] = "Unfortunately an unknow error prevented the 
   		submission of your reservation. Please click the below link to try again:<br />
   		<a href=\"BASE!Reservations/search_rooms#reservation\">Go back to reservation</a>";
   		$response['message'] = str_replace("BASE!", BASE_URL, $response['message']);
   	}
   	return $this->render("reservations/message", $response);
 }

 function cancel(){
 	$res = new Reservation($this->reg);
 	if(isset($_SESSION['reservation_id'])){
 	    $res->delete_by_id($_SESSION['reservation']);
 	    $_SESSION = array();
 	    return $this->redirect("Reservations/index#reservation");
 	}
 }

   

}
<?php
namespace ResBooker\Models;
use ResBooker\Models\Model;

class Room extends Model{

	function total_rooms($check_in_datetime, $check_out_datetime){
		$sql = "select count(id) as count FROM rooms WHERE";
		$sql .= " id not in(select room_id from reserved_rooms";
		$sql .= " WHERE check_in_datetime >= ? and check_out_datetime <= ?)";
		$this->db->execute($sql, [$check_in_datetime, $check_out_datetime]);
		$obj = $this->db->getRow();
		if($obj){
			return $obj->count;
		}else{
			return -1;
		}

        

	}

	function get_rooms($check_in_datetime, $check_out_datetime, $offset, $limit){
		$sql = "select id, name, description, price_normal FROM rooms WHERE";
		$sql .= " id not in(select room_id from reserved_rooms";
		$sql .= " WHERE check_in_datetime >= ? and check_out_datetime <= ? )";
        $sql .= " LIMIT ? , ?";

		$this->db->execute($sql, [$check_in_datetime, $check_out_datetime, $offset, $limit]);
		$rows = $this->db->getRows();
		if($rows){
			return $rows;
		}else{
			return [];
		}

	       

	}
	
}
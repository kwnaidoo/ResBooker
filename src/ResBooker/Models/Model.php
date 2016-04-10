<?php 
namespace ResBooker\Models;

class Model{

	protected $reg = null;
	protected $db = null;
	protected $data = [];
	protected $table = "";
	public $validation_errors = [];

	public function __construct($reg){
		$this->reg = $reg;
		$this->db = $this->reg->db;
		$class_name = str_replace("ResBooker\\Models\\", '', get_class($this)) . "s";
		$this->table = strtolower(preg_replace('/(.)([A-Z])/', '$1_$2', $class_name));

	}


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

	public function validate($field, $sender){
		$passed = True;
		$validation_method = "validate_{$field}";
		if(method_exists($this, $validation_method)){
			$passed = $this->$validation_method($sender);
		}

		return $passed;
	}

	public function save(){
		$this->validation_errors = [];
		$fields = "";
		$value_placeholders = "";
		$value_list = [];
		$sql = "INSERT INTO {$this->table} (!fields) VALUES(!values)";

		foreach($this->data as $field=>$value){
			if($this->validate($field, "save")){

				if($fields == ""){
					$fields .= $field;
				}else{
					$fields .= "," . $field;
				}

				if($value_placeholders == ""){
					$value_placeholders .= " ?";
				}else{
					$value_placeholders .= ", ?";
				}
				$value_list[] = $value;


			}else{
				return -1;
			}
		}
		$fields = trim($fields, ",");
		$value_placeholders = trim($value_placeholders, ",");
		$sql = str_replace("!fields", $fields, $sql);
		$sql = str_replace("!values", $value_placeholders, $sql);

		$this->db->execute($sql, $value_list);

		if($this->db->error == null){

			if($this->db->countAffected() > 0){
				return $this->db->conn->lastInsertId();
			}
		}
		
	    return -1;
   }


   	public function update($field, $val){
   		$this->validation_errors = [];
   		$fields = "";
   		$value_list = [];
   		$sql = "UPDATE {$this->table} set !fields WHERE {$field}= ?";

   		foreach($this->data as $f=>$value){
   			if($f && $value){
	   			if($this->validate($f, "update")){
	   				if($fields == ""){
	   					$fields .= "{$f}= ? ";
	   				}else{
	   					$fields .= ",{$f}= ? ";
	   				}

	   				$value_list[] = $value;


	   			}else{
	   				return False;
	   			}
   		   }
   		}
   		$fields = trim($fields, ",");
   		$sql = str_replace("!fields", $fields, $sql);

   		$value_list[] = $val;

   		$this->db->execute($sql, $value_list);
   		if($this->db->error == null){
   			if($this->db->countAffected() > 0){
   				return True;
   			}
   		}
   		
   	    return False;
      }


   public function get_by($field, $value, $fields_to_select="*"){
   	    $sql = "select {$fields_to_select} FROM {$this->table} WHERE {$field}= ?";

   	    $this->db->execute($sql, array($value));
   	    return $this->db->getRow();
   }

   public function find($fields="*",$limit=0, $offset=0, $order="id ASC", $where=null,$bind_values=[]){
   	    $sql = "select {$fields} FROM {$this->table}";
   	    if($where){
   	    	$sql.=" WHERE {$where}";
   	    }
   	    if($limit > 0 and $offset > 0){
   	    	$sql.=" LIMIT {$offset},{$limit}";
   	    }else if($limit > 0){
   	    	$sql.=" LIMIT {$limit}";
   	    }
   	    $sql.=" ORDER BY {$order}";
   	    if(count($bind_values) > 0 && $where){
   	    	$this->db->execute($sql, $bind_values);
   	    }else{
   	    	$this->db->execute($sql);
   	    }
   	    
   	    return $this->db->getRows();
   }

   public function count_by($field, $value){
   	    $sql = "select count(*) as count FROM {$this->table} WHERE {$field}= ?";
   	    $this->db->execute($sql, [$value]);
   	    $obj = $this->db->getRow();
   	    if($obj){
   	    	return $obj->count;
   	    }

   	    return -1;
   }

   public function delete_by_id($id){
   	    $id = (int) $id;
   	    $sql = "DELETE FROM {$this->table} WHERE id={$id}";

   	    $this->db->execute($sql);
   	    if($this->db->error == null){
   			if($this->db->countAffected() > 0){
   				return True;
   			}
   		}
   		
   	    return False;
   }

}
<?php 
namespace ResBooker\Models;
/**
  This is the base model that all models should extend,
  it provides various database interaction methods to simplify
  and abstract database interactions when performing CRUD tasks.

  For maximum security we utilize prepare statements throughout to 
  prevent SQL injection attacks.
**/
class Model{

	// setup required variables for all models
	protected $reg = null;
	protected $db = null;
	protected $data = [];
	protected $table = "";
	public $validation_errors = [];

	// as with all other framework classes , we must accept a registry object.
	public function __construct($reg){
		$this->reg = $reg;
		// assign the database adpater to $this->db
		$this->db = $this->reg->db;
		/**
		  The convention is to name the Model class the same as that of the table,
		  except the model name should be signular and camel cased.
		  
		  The following two lines serves as a very basic inflection algorith to
		  dertime the database table name from the class name, for the purposes
		  of this application i did not spend too much time on this as the following
		  is sufficient for the database table names i chose.
		**/
		$class_name = str_replace("ResBooker\\Models\\", '', get_class($this)) . "s";
		$this->table = strtolower(preg_replace('/(.)([A-Z])/', '$1_$2', $class_name));

	}

	// magic getters and setters to dynamically set/get database field values.
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

    /**
		A basic validation "hook" mechanism , that allows
		for attaching validation methods to model fields.

		e.g. if you have a field in the table called "email",
		the model will automatically look for a method called
		validate_email and run that method , the method should
		return True or False.

		If False is returned the calling operation such as a Save or Update
		will be stopped immediatly.

		Note : All validation methods should accept a sender argument,
		this enables the method to know what the calling method is , e.g. 
		you may want to apply different rules based on whether the calling
		method is a save or update.
   **/
	public function validate($field, $sender){
		$passed = True;
		$validation_method = "validate_{$field}";
		if(method_exists($this, $validation_method)){
			$passed = $this->$validation_method($sender);
		}

		return $passed;
	}

	/**
	 Based on the fields you set using the magic set method, this
	 method will dynamically build a SQL query , run validators
	 and run the query on the database server.

	 Return => -1 for failures or the record ID on success.

	**/
	public function save(){
		// basic variable setup
		$this->validation_errors = [];
		$fields = "";

		/**
		For security we utilize prepare statements therefore we pass in the values
		 as an array to bind  later down.
		**/
		$value_placeholders = "";
		$value_list = [];

		// INSERT template - !fields and !values will be substituted later down.
		$sql = "INSERT INTO {$this->table} (!fields) VALUES(!values)";

		// loop through all fields in this model obj
		foreach($this->data as $field=>$value){
			// trigger validators with sender "save"
			if($this->validate($field, "save")){

				if($fields == ""){
					$fields .= $field;
				}else{
					$fields .= "," . $field;
				}

				// drop in ?'s where the values should appear
				if($value_placeholders == ""){
					$value_placeholders .= " ?";
				}else{
					$value_placeholders .= ", ?";
				}

				// store the current fields value in our value_list
				$value_list[] = $value;


			}else{
				return -1;
			}
		}
		// clean of any extra commas
		$fields = trim($fields, ",");
		$value_placeholders = trim($value_placeholders, ",");

		// subsitute the fields and value placeholders in our SQL template string
		$sql = str_replace("!fields", $fields, $sql);
		$sql = str_replace("!values", $value_placeholders, $sql);

		// run the query
		$this->db->execute($sql, $value_list);

		if($this->db->error == null){

			// should we have succeeded , we return the record's ID
			if($this->db->countAffected() > 0){
				return $this->db->conn->lastInsertId();
			}
		}
		
	    return -1;
   }

   // very similar to save , except this updates an exsiting record,return: True or False.

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


   /**
       get_by allows for fetching a single record based on any valid
       table field, the return is a PDO table row object.
   **/
   public function get_by($field, $value, $fields_to_select="*"){
   	    $sql = "select {$fields_to_select} FROM {$this->table} WHERE {$field}= ?";

   	    $this->db->execute($sql, array($value));
   	    return $this->db->getRow();
   }

   /**
		find is a flexible filter method allowing the model
		to query the database table by utilzing a custom where clause,
		paging , and ordering data.

		The method will return an array of PDO objects.
   **/
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


   /**
    Allows for counting the number of rows matching the search
    criteria, you can search by any valid table column.

    returns an integer
   **/
   public function count_by($field, $value){
   	    $sql = "select count(*) as count FROM {$this->table} WHERE {$field}= ?";
   	    $this->db->execute($sql, [$value]);
   	    $obj = $this->db->getRow();
   	    if($obj){
   	    	return $obj->count;
   	    }

   	    return -1;
   }

   // simple delete by ID , returns true or false.
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
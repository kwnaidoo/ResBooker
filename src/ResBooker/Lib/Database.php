<?php 
namespace Resbooker\Lib;
use ResBooker\Config\Settings;
/**

A PDO abstration layer that serves as an adapter between
the frameworks models and the database backends.

**/
class Database{
    var $conn = False;
    var $error = null;
    var $query = null;

    function __construct(){
    	try{
	    	$this->conn = new \PDO(Settings::$database["DSN"], Settings::$database["USERNAME"], Settings::$database["PASSWORD"]);
			
            // Turn on exceptions because we want to be able to catch fatal erros.
            $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			$this->conn->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
		}catch(\PDOException $e){
			$this->error = $e;
		}
    }

    /**
    Execute will run our SQL , prepare the SQL statement and bind any passed in values.

    Includes built in exception handling , errors will be stored in a $this->error.

    Method will return Ture or False depending on operation success or failure.
    **/

    public function execute($sql, $params=array()){
    	$this->query = null;
        $this->error = null;
    	if($this->conn){
    		try{
                // prepare SQL statement
    			$this->query = $this->conn->prepare($sql);

                // bind params/values if they've been passed to the method
    			if(count($params) > 0){

    				$this->query->execute($params);
    			}else{
    				$this->query->execute();
    			}
                return $this->query;

    		}catch(\PDOException $e){
    			$this->error = $e->getMessage();
                return False;
    		}
    	}else{
    		return False;
    	}
    }

    // method to determine how many rows where effected by a non select query.
    function countAffected(){
        try{
            return $this->query->rowCount();
        }catch(\PDOException $e){
            $this->error = $e->getMessage();
        }
    }

    // returns a single PDO object
    function getRow(){
        $this->error = null;
        if($this->query){
            try{
                return $this->query->fetch(\PDO::FETCH_OBJ);
            }catch(\PDOException $e){
                $this->error = $e->getMessage();
            }
        }
        return null;
    }

    // returns an array of PDO objects
    function getRows(){
        $this->error = null;
    	if($this->query){
    		try{
    			return $this->query->fetchAll(\PDO::FETCH_OBJ);
    		}catch(\PDOException $e){
    			$this->error = $e->getMessage();
    		}
    	}
    	return [];
    }

    /** 
    basic wrappers for PDO transactions,
    Exceptions are purposely not caught here
    so that the calling method can determine if
    it needs to rollback the transaction.
    **/

    function begin_transaction(){
            $this->conn->beginTransaction();
    }
    function execute_transaction($sql){
        $this->conn->exec($sql);
    }
    function commit_transaction(){
        $this->conn->commit();
    }
    function rollback_transaction(){
        try{
            return $this->conn->rollBack();
        }catch(\PDOException $e){
            $this->error = $e->getMessage();
            return False;
        }
    }

}
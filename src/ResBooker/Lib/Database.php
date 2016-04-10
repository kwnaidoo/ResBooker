<?php 
namespace Resbooker\Lib;
use ResBooker\Config\Settings;

class Database{
    var $conn = False;
    var $error = null;
    var $query = null;
    function __construct(){
    	try{
	    	$this->conn = new \PDO(Settings::$database["DSN"], Settings::$database["USERNAME"], Settings::$database["PASSWORD"]);
			$this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			$this->conn->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
		}catch(\PDOException $e){
			$this->error = $e;
		}
    }

    public function execute($sql, $params=array()){
    	$this->query = null;
        $this->error = null;
    	if($this->conn){
    		try{

    			$this->query = $this->conn->prepare($sql);
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


    function countAffected(){
        try{
            return $this->query->rowCount();
        }catch(\PDOException $e){
            $this->error = $e->getMessage();
        }
    }


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
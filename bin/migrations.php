<?php require_once("bootstrap.php");
use ResBooker\Lib\Database;
Class Migrator{

	public function run($type){
		$migration_files = scandir(BASE_DIR."migrations");
		if($type == "down"){
            rsort($migration_files);
		}else{
		    sort($migration_files);
	    }

		foreach($migration_files as $mfile){
			if(stripos($mfile, ".php")!== False){
				$migration_name = str_replace(".php", "... \n", $mfile);
				$sql_cmds = require_once(BASE_DIR."migrations/".$mfile);
				if(isset($sql_cmds[$type])){
						print "Running {$type} migration: " . $migration_name;
						$db = new Database();
						if($db->error != null){
							print "========================================================================\n";
							print "Migrations Failed - error connecting to the database server: \n";
							print "========================================================================\n";
							print $db->error;
							print "========================================================================\n";
							die;
						}
						try{
							$db->begin_transaction();
							foreach($sql_cmds[$type] as $sql){
								$db->execute_transaction($sql);
							}

							$db->commit_transaction();
						}catch(PDOException $e){
							print "========================================================================\n";
							print "Migration Failed - error while processing SQL , Rolling back changes: \n";
							print "========================================================================\n";
							print "Migration : {$migration_name} \n";
							print $e->getMessage(). "\n";
							print "========================================================================\n";

						}

				}else{
					print "Failed! to run migration(No {$type} migrations): " . $migration_name;
				}
			}
		}
	}
	public function main($options){
		$migration_type = "up";

		if(isset($options[0])){
			if($options[0] == "down"){
			    $migration_type = "down";	
			}
		}
		$this->run($migration_type);
	}
}


$c = new Migrator();
unset($argv[0]);
$c->main(array_values($argv));
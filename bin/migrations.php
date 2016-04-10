<?php require_once("bootstrap.php");
use ResBooker\Lib\Database;
/**
 This is a basic migrations class and no way as comprehensive as something like phinx,
 it's purely been develop to make setting up the initial database structure easier and
 allows me to rapidly create and destory the database structure for easier testing.

**/
Class Migrator{

	/**
	  This is the workhorse method - it basically looks in the migrations folder at the
	  top level of this framework and will run each migration file sequencially based on
	  the type of migration needed.

	  Two types are support i.e. Forward which is the default and Backwards which can be
	  triggered by passing in a command line argument namely "down".

	  Single file migrations are currently not supported - the migrator will simply either
	  forward migrate or backward migrate all migration files found in the migrations folder.

	**/
	public function run($type){
		// get a full file list of the migrations folder
		$migration_files = scandir(BASE_DIR."migrations");

		/**
		Instantiate a new Db object , this is standalone script and therefore does
		not utilize the registry. 
		**/
		$db = new Database();

		// make sure we have a valid db connection, if not kill the script.
		if($db->error != null){
			print "========================================================================\n";
			print "Migrations Failed - error connecting to the database server: \n";
			print "========================================================================\n";
			print $db->error;
			print "========================================================================\n";
			die;
		}

		/** 
		Sort files sequencially based on the migration file name.
		All migration file names should begin with a sequence of numbers e.g. 001, 002, 003.

		**/

		if($type == "down"){
            rsort($migration_files);
		}else{
		    sort($migration_files);
	    }
	    // loop through each migration file
		foreach($migration_files as $mfile){

			// skip the file if its not got a .php extension
			if(stripos($mfile, ".php")!== False){
				// remove the file extension from the migration file name 
				// to make it more human readable.

				$migration_name = str_replace(".php", "... \n", $mfile);
				//require the file - this will load an array of arrays into $sql_cmds
				$sql_cmds = require_once(BASE_DIR."migrations/".$mfile);

				// work with only the "down" or "up" SQL commands found in the migration file
				if(isset($sql_cmds[$type])){
						print "Running {$type} migration: " . $migration_name;
						/**
						we start a PDO transaction and run through the SQL commands,
						executing one at a time. We are utilizing transactions because
						if even one migration fails - we should rollback the entire
						operation to prevent database corruption.
						**/
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
	// By convention all the shell scripts should include a main method.
	public function main($options){
		$migration_type = "up";

		// if a migration type was specified - we send that type to the run method
		if(isset($options[0])){
			if($options[0] == "down"){
			    $migration_type = "down";	
			}
		}
		$this->run($migration_type);
	}
}

/**
instantiate the migrator and send the std php $argv array to the class, 
this allows for the main method to handle any arguments passed to the script.

Since the first $argv item is the file name , we just delete that for convenience.
**/
$c = new Migrator();
unset($argv[0]);
$c->main(array_values($argv));
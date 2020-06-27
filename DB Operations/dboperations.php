<?php

/************************************************************************************
	Author		:	ANJANI KUMAR PIRATLA											*
	File Name 	:	dbOperations.php												*
	Licence		:	Freeware 														*
																					*
************************************************************************************/
/************************************************************************************
																					*
	FUNCTIONS LIST:																	*
	function connect()	// connect to mysql server and select db					*
	function delete()	// delete records from a table without using clause			*
	function deleteClause() // delete records from a table using clause				*
	function update()	// update records of a table								*
	function dropTable()	// drop a table from a database							*
	function createTable()	// create a table 										*
	function insertValuesIntoTable()	// insert values into a table				*
	function showTables() // show all tables in a database							*
	function showDatabases()	// show all databases								*
	function GetAllRecordsFromTable() // fetch/display all records from a table		*
	function close() // close database connection									*
																					*	
************************************************************************************/

// main class dbOperations
class DBOperations {
var $connection;
var $db;
var $del;
var $update;
var $sql;
var $row;
var $drop;
var $insert;
var $create;
var $close;
var $query;
var $count;
var $fields;		
var $results;
var $values;
var $getrecords;
var $numrows;
var $records;

	// connect to server
	function connect(){
		include("database.php");
		$this->connection = @mysql_connect($server,$user,$pass);
		$this->db = @mysql_select_db($database);

	if(!$this->connection)
		echo "Failed to connect to MySQL server. Check whehter MySQL server is running or not.",br;
		
	if(!$this->db)
		echo "Failed to select db. Check whether DB $database exists or not.",br;
	}	

	// delete records without using clause
	function delete($table){
		$this->del = @mysql_query("DELETE FROM $table");		
		
		if(!$this->del)
			echo "Delete failed without using clause. Delete table name: ",$table,br;
	}

	// delete records using clause	
	function deleteClause($table,$clause){
		$this->del = @mysql_query("DELETE FROM $table WHERE $clause");
		
		if(!$this->del)
			echo "Delete failed using clause. Delete table name: ",$table,br;
	}
	
	// update records
	function update($table,$clause){
		$this->update = @mysql_query("UPDATE $table $clause");
		
		if(!$this->update)
			echo "Update failed. Update table name: ",$table,br;
	}
	
	// drop table
	function dropTable($table){
		$this->drop = @mysql_query("DROP TABLE $table");
		
		if(!$this->drop)
			echo "Failed to drop the table. Check whether table $table exists or not.",br;
	}

	// create table
	function createTable($table,$clause){
		$this->create = @mysql_query("CREATE TABLE $table $clause");
		
		if(!$this->create)
			echo "Failed to create the table. Create table name: ",$table,br;
	}
	
	// insert values into table
	function insertValuesIntoTable($table,$fields,$values){
		$this->insert = @mysql_query("INSERT INTO $table ($fields) VALUES($values)");
		
		if(!$this->insert)
			echo "Failed to insert values into the table: $table",br;
	}
	
	// show tables in a database
	function showTables($db_name){
		$this->query = @mysql_query("SHOW TABLES FROM $db_name");
		$this->count = @mysql_num_rows($this->query);
		for($i=0;$i<$this->count;$i++){
			while($this->row=@mysql_fetch_array($this->query)){
				echo $this->row[$i],br;
			}
		}
		
		if(!$this->query)
			echo "Failed to show tables from $db_name",br;
	}

	// show databases
	function showDatabases(){
		$this->query = @mysql_query("SHOW DATABASES");
		$this->count = @mysql_num_rows($this->query);
		for($i=0;$i<$this->count;$i++){
			while($this->row=@mysql_fetch_array($this->query)){
				echo $this->row[$i],br;
			}
		}

		if(!$this->query)
			echo "Failed to show databases",br;
	}

	// show all records from a table
	function GetAllRecordsFromTable($db_name,$table){
		$this->query = @mysql_list_fields($db_name,$table);
		$this->fields = @mysql_num_fields($this->query);		
		$this->results = array();
		$this->values = array();

		for($i=0; $i<$this->fields;$i++){
			$this->row = @mysql_field_name($this->query,$i);
			$this->results[$i] = $this->row;
		}
		$this->count = count($this->results);

		$this->getrecords = @mysql_query("SELECT * FROM $table");
		$this->numrows = @mysql_num_rows($this->getrecords);
	
		for($j=0;$j<$this->numrows;$j++){
			$this->records = @mysql_fetch_array($this->getrecords);
			$this->values[$j] = $this->records;
		}

		foreach($this->values as $this->v){
			for($k=0;$k<$this->count;$k++){
				echo $this->v[$k]."&nbsp;";
			}
			echo br;
		}

		if(!$this->query)
			echo "Failed to get field names",br;
			
		if(!$this->getrecords)

			echo "Failed to show records",br;
	}

	//close connection
	function close(){
		$this->close = @mysql_close($this->connection);
	}
}
?>
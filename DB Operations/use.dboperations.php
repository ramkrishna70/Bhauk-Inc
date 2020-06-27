<?php

/************************************************************************************
	Author		:	ANJANI KUMAR PIRATLA											*
	File Name 	:	use.dbOperations.php											*
	Licence		:	Freeware 														*
																					*
************************************************************************************/

include("dboperations.php");

// connect to server and database
DBOperations::connect();	

// specify the name of the table to delete records :: use this for deleting all the records
// replace $table with the name of the table from which you want to delete records
DBOperations::delete($table);	

// use this for deleting records with where clause
// replace $table with table name and $clause respectively
// for example name of the table=>"test" id='12'
DBOperations::deleteClause($table,$clause);	

// use this for updating records
// replace $table with table name and $clause i.e $table name=>"test", $clause=>"set username='anjani' WHERE id='21'"
DBOperations::update($table,$clause); 

// use this for creating a table
// replace $table with table name and $clause i.e. 
//$table name=>"test", $clause=>" (`id` INT UNSIGNED AUTO_INCREMENT, `username` VARCHAR (30), `password` VARCHAR (30), PRIMARY KEY(`id`))"
DBOperations::createTable($table,$clause);

// use this for dropping a table
// replace $table with the name of the table name i.e. $table name=>"test"
DBOperations::dropTable($table);

// use this for inserting values into a table
// replace the $table with the name of the table name i.e. $table name=>"test"
// replace the $clause with cleasue i.e. "username,password","'anjani','kumar'"
DBOperations::insertValuesIntoTable($table,$clause);

// use this for displaying all the tables in a database
// replace the $db_name with respective database name 
DBOperations::showTables($db_name);

// use this for displaying all the databases
// replace the $db_name with respective database name 
DBOperations::showDatabases($db_name);

// use this for fetching/displaying all records from a table
// replace $db_name and $table with respective database and table names i.e. $db_name=>"mysql" $table=>"test"
DBOperations::GetAllRecordsFromTable($db_name,$table);

// close database connection
DBOperations::close();
?>
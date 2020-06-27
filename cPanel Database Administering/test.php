<?php
error_reporting(E_ALL);
include('cpanel_mysql.class.php');

$db = new cpanel_db('domain','user','pass');

//create db
if($db->createDb('cpdb')){
	echo 'Db Created';
} else {
	echo 'Db not created';
}


//create user 
if($db->createUser('cpuser','3837djd')){
	echo 'User Created';
} else {
	echo 'User not created';
}

//grant access
if($db->grantPriv('cpdb','cpuser')){
	echo 'Priv granted';
} else {
	echo 'Priv not granted';
}


//we can run the above three by this single line:
//$result = $db->runBatch('batch','batch','elkdrlfd');
	//we can now check the result
	print_r($result);

//del database
$db->delDb(array('db1','db2','db3'));

//del users
$db->delUser(array('user1','user1','user3'));


//we can also check whether a db/user exists
if($db->isUserExists('cpuser')) echo 'User exists';

if($db->isDbExists('cpdb')) echo 'Db exists';
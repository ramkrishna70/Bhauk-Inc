<?php

/**
 * Name: cPanel Database Class
 * Version: 1.0
 * Author: 	The HungryCoder
 * Contact:	thehungrycoder@gmail.com
 * Homepage: www.hungrycoder.xenexbd.com
 * 
 * This class will create mysql database and users in cPanel. This is my first PHP work in my new job. Thanks for my collegues who inspired me while working. 
 */

class cpanel_db {
	protected $cpdomain;
	protected $cpuser;
	protected $cppass;
	protected $cptheme;
	private $error;
	public $callresult;

	
	function __construct($cpdomain,$cpuser,$cppass,$cptheme='x3'){
		$this->cpdomain = $cpdomain;
		$this->cpuser = $cpuser;
		$this->cppass = $cppass;
		$this->cptheme = $cptheme;
		
		
	}
	
	private function callUrl($urlsuffix){
		if(empty($urlsuffix)) return $this->error('URL is empty');
				
		$url = "http://".$this->cpdomain.":2082/frontend/".$this->cptheme.$urlsuffix;
		$this->callresult=''; //reset
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_USERPWD, "$this->cpuser:$this->cppass");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	//	curl_setopt($ch, CURLOPT_VERBOSE , 1 );
		$this->callresult = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
		return $info;
	}
	
	/**
	 * Create Database
	 *
	 * @param string $dbname
	 * @return boolean
	 */
	public function createDb($dbname){
		$suffix = "/sql/addb.html?db=$dbname";
		
		//return $this->callresult;
		$this->callUrl($suffix);
		
		return $this->isSuccess('db');
	}	
	
	public function createUser($username,$pass){
		$suffix = "/sql/adduser.html?user=$username&pass=$pass&pass2=$pass";
		
		//return $this->callresult;
		$this->callUrl($suffix);
		
		return $this->isSuccess('user');
	}
		
	public function grantPriv($db,$user){
		//prepare the params
		$params = "db=$db&user=$user&update=&ALL=ALL&SELECT=SELECT&CREATE=CREATE&INSERT=INSERT&ALTER=ALTER&UPDATE=UPDATE&DROP=DROP&DELETE=DELETE&LOCKTABLES=LOCK&INDEX=INDEX&REFERENCES=REFERENCES&CREATETEMPORARYTABLES=TEMPORARY&CREATEROUTINE=CREATEROUTINE";
		$callurl = "/sql/addusertodb.html?$params";
		
		$this->callUrl($callurl);
		
		return $this->isSuccess('grant');
	}
	
	private function isSuccess($type='db'){
		switch ($type){
			case 'db':
				if(eregi('Added the database',$this->callresult)){
					return true;
				} else {
					return false;
				}
				break;
				
			case 'user':
				if(eregi('Added user',$this->callresult)){
					return true;
				} else {
					return false;
				}
				break;
				
			case 'grant':
				if(eregi('was added to the database',$this->callresult)){
					return true;
				} else {
					return false;
				}
				break;				
			case 'deldb':
				if(eregi('deleted the database',$this->callresult)){
					return true;
				} else {
					return false;
				}
				break;				
			case 'deluser':
				if(eregi('Deleted the user',$this->callresult)){
					return true;
				} else {
					return false;
				}
				break;	
		}
	}

	public function error($msg){
		$this->error = $msg;
		return false;
	}
	
	public function runBatch($dbname,$dbuser,$dbpass){
		if(empty($dbname) OR empty($dbuser) OR empty($dbpass)) return false;
		$result = array();
		//create the database
		$result['db'] = $this->createDb($dbname);
		
		//create the user
		$result['user'] = $this->createUser($dbuser,$dbpass);
		
		//grant the access with real db name and username
		$result['grant'] = $this->grantPriv($this->cpuser.'_'.$dbname,$this->cpuser.'_'.$dbuser);
		
		return $result; 
	}
	
	/**
	 * This method deletes a number of databases from cpanel. 
	 *
	 * @param array $dbs
	 * @return array
	 */
	public function delDb($dbs){
		//this method will delete a number of dbs. 
		if(is_array($dbs)){
			foreach ($dbs as $db){
				$db_full_name = $this->cpuser .'_'.$db;
				$suffix = "/sql/deldb.html?db=$db_full_name";
				$this->callUrl($suffix);
				$result[$db] = $this->isSuccess('deldb');
			}
			return $result;
		} else {
			$this->error('Not an array');
		}
		return false;
	}	
	/**
	 * This method deletes a number of users from cpanel. 
	 *
	 * @param array $dbs
	 * @return array
	 */
	public function delUser($users){
		//this method will delete a number of dbs. 
		if(is_array($users)){
			foreach ($users as $user){
				$user_full_name = $this->cpuser .'_'.$user;
				$suffix = "/sql/deluser.html?user=$user_full_name";
				$this->callUrl($suffix);
				$result[$user] = $this->isSuccess('deluser');
			}
			return $result;
		} else {
			$this->error('Not an array');
		}
		return false;
	}
	
	/**
	 * Find whether a database is exists in cPanel or not! 
	 *
	 * @param string $dbname Do not include the database name prefix (anything before _). 
	 * @return boolean
	 */
	public function isDbExists($dbname,$limit=100){
		$suffix = "/sql/index.html?itemsperpage=$limit";
		$this->callUrl($suffix);
		$html = $this->callresult;
		$html = substr($html,stripos($html,'<select name=db>')-1);
		$html = substr($html,1,stripos($html,'</select>'));
		$html = str_ireplace('</option>','BR',$html);
		$html = strip_tags($html);
		$all_db = explode('BR',$html); //array of all databases. 
		
		//trim the whitespaces surrounding the dbname
		$all_db = array_map('trim',$all_db);
		//print_r($all_db);
		
		if(in_array($this->cpuser.'_'.$dbname,$all_db)){
			return true;
		} else {
			return false;
		}
	}	
	
	public function isUserExists($username,$limit=100){
		$suffix = "/sql/index.html?itemsperpage=$limit";
		$this->callUrl($suffix);
		$html = $this->callresult;
		
		$html = substr($html,stripos($html,'<select name=user>')-1);
		
		$html = substr($html,1,stripos($html,'</select>'));

		$html = str_ireplace('</option>','BR',$html);
		$html = strip_tags($html);
		$all_user = explode('BR',$html); //array of all databases. 
		
		//trim the whitespaces surrounding the dbname
		$all_user = array_map('trim',$all_user);
		//print_r($all_db);
		
		if(in_array($this->cpuser.'_'.$username,$all_user)){
			return true;
		} else {
			return false;
		}
	}
}

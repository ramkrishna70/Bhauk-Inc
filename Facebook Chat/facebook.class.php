<?php
error_reporting(E_ALL);

class FacebookChat {
	public $ua 			= "Firefox/3.5.3 (lzyc)";
	public $cookiefile		= "cookie.txt";
	public $logged		= 0;
	
	private $email;
	private $password;
	private $attempt 	= 0;
	private $html;
	
	private $uid;
	private $channel;
	private $post_form_id;
	
		function __construct($email = NULL, $password = NULL){
			
			if(!extension_loaded('curl'))	die("Install curl\r\n");
			if(!extension_loaded('json'))	die("Install json\r\n");
			if(version_compare(PHP_VERSION, '5.3', '<')) die("Update php to 5.3\r\n");
			
			$this->email	= $email;
			$this->password = $password;
			
			$this->resetcookie();
		}


		public function login(){
			
			
			$data = array
						(
						"charset_test" 	=> "%E2%82%AC%2C%C2%B4%2C%E2%82%AC%2C%C2%B4%2C%E6%B0%B4%2C%D0%94%2C%D0%84",
						"locale" 		=> "en_US",
						"non_com_login"	=> "",
						"persistent"		=> "1",
						"email" 		=> urlencode($this->email),
						"pass" 		=> urlencode($this->password),
						"charset_test"	=> "%E2%82%AC%2C%C2%B4%2C%E2%82%AC%2C%C2%B4%2C%E6%B0%B4%2C%D0%94%2C%D0%84",
						"lsd"			=> "str7a"
						);
						
			$this->html = $this->postreq("http://www.facebook.com/login.php?login_attempt=1", $data, 1);
						
				
				preg_match("/<title>(.+?)<\/title>/", $this->html, $out);

				if(preg_match("/Home/", $out[1])){
								preg_match("/c_user=(.+?);/", $this->html, $out);
								$this->uid = $out[1];
						
								#preg_match("/channel(\d+)/", $this->html, $out);
								#$this->channel = $out[1];
			
								preg_match("/name=\"post_form_id\" value=\"(.+?)\"/", $this->html, $out);
								$this->post_form_id = $out[1];
			
							goto a;
					
				} else {

					$this->attempt++;
						if($this->attempt > 3){
							goto b;
						}
					$this->login();
				}
				
				a: {
					$this->logged = 1;
					return true;
				}
				
				b: return false;
		}
		
		public function buddylist()
		{
			
			if(!$this->logged) die("Are you logged?");
						
			$buddies = $this->postreq("http://www.facebook.com/ajax/chat/buddy_list.php" , array(
						"user"					=> $this->uid,
						"popped_out"			=> "false",
						"force_render"			=> "true",
						"buddy_list"			=> "1",
						"notifications"			=> "0",
						"post_form_id"			=> $this->post_form_id,
						"fb_dtsg"				=> "90rZa",
						"post_form_id_source"	=> "AsyncRequest",
						"__a"					=> "1"
					));

			
				$buddies = $this->json($buddies);
				
				if(!$buddies['payload']['buddy_list']['userInfos']) die("Error\r\n");
								
				function delenableVC(&$val)
				{
					unset($val['enableVC']);
				}
				
				array_walk($buddies['payload']['buddy_list']['userInfos'], 'delenableVC');
				
				return $buddies['payload']['buddy_list']['userInfos'];

		}
		
		public function sendmsg($msg, $friend){
						
			$data = array(
						"msg_text"		=> $msg,
						"msg_id"		=> rand(99999, 999999),
						"to"			=> $friend,
						"client_time"	=> time(),
						"post_form_id"	=> $this->post_form_id
					);
			
			print $this->postreq("http://www.facebook.com/ajax/chat/send.php", $data);
			
		}
		
		
		private function resetcookie(){
				$f = fopen($this->cookiefile, "wb");
				fclose($f);	
				
			return $f;
		}
		
		
		private function postreq($url, $postdata, $header = 0){
				$ch = curl_init($url);
				
					$opt = 	curl_setopt_array ($ch, array (
								CURLOPT_REFERER			=> "http://www.facebook.com",
								CURLOPT_HEADER			=> $header,
								CURLOPT_FOLLOWLOCATION	=> 1,
								CURLOPT_RETURNTRANSFER	=> 1,
								CURLOPT_POST			=> 1,
								CURLOPT_POSTFIELDS		=> $this->postdata($postdata),
								CURLOPT_USERAGENT		=> $this->ua,
								CURLOPT_COOKIEFILE		=> $this->cookiefile,
								CURLOPT_COOKIEJAR		=> $this->cookiefile,
								CURLOPT_SSL_VERIFYPEER	=> 0,
								CURLOPT_SSL_VERIFYHOST	=> 0
							));

				$html = curl_exec($ch);
				curl_close($ch);
				
			return $html;
		}
		
		private function json($json)
		{
			return json_decode(substr($json, 9, strlen($json)), true);
		}
		
		private function postdata($array)
		{
			$final = NULL;
			foreach($array as $key => $val) $final .= $key."=".$val."&";
			return rtrim($final, "&");
		}
}


?>
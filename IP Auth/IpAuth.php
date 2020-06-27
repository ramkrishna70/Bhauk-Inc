<?php
/**
 * IP Adreess Authenticating System for PHP
 * 
 * @author Rolando Santamaria Maso | rsantamaria@hab.uci.cu
 * @license GPL
 * @package IpAuth
 */
class IpAuth{
	/**
	  * Return a boolean result if IP address is into an IP addresses List
	  *
	  * @param string $range
	  * @param string $ip
	  * @return boolean
	  */
	 static function is_sub_IP($range, $ip){
	 	
	     	$array_range 	= explode('.', $range);
	     	$array_ip 		= explode('.', $ip);
	     	
	     	for ($i = 0; $i<count($array_range); $i++){
	     		
		     	if (strstr($array_range[$i], '/'))
		     		$parts[] = explode('/', $array_range[$i]);
		     	else 
		     		$parts[] = $array_range[$i];
		     		
		     	if (is_array($parts[$i])){
		     		if ((int)$parts[$i][0] <= (int)$array_ip[$i] && (int)$parts[$i][1] >= (int)$array_ip[$i])
		     			continue;
		     		else 
		     			return FALSE;
		     	}elseif (is_string($parts[$i])) {
		     		if ($parts[$i] == $array_ip[$i])
		     			continue;
		     		else 
		     			return FALSE;
		     	}
		     	
	     	}
     	
     	      return TRUE;
     	
        }
     
     
	 /**
	  * Check permissions for an IP address in the IP addresses List
	  * 
	  * @exception Exception
	  * @return boolean
	  */
	 static function check_permissions_for_IP($ip, $ipListName = "ip_default_list.ini"){
	 	
	 	/* PARSING FILE .INI */ 
	 	
	 	$file = explode("/", __FILE__);
	 	$file[count($file)-1] = "ip_lists/".$ipListName;
	 	$file = implode("/",$file);
	 	
		if (!file_exists($file))
			throw new Exception("File: ".$ipListName." not found!"); 
	 		
		$ip_list = parse_ini_file($file);
	 	if (!$ip_list)
	 		return FALSE;
	 		
		foreach($ip_list as $key => $value){
			if (self::is_sub_IP($key, $ip)){
	 			if (FALSE == $value)
	 			 	return FALSE;
	 			return TRUE;
	 		}
	 	}
	 		
	 	return FALSE;
	 		
	 }
	 
}	 
	 
?>

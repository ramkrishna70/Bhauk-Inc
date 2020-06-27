<?php
/**
 ***************************************************************************
						DOMAIN AND EMAIL VALIDATOR
 ***************************************************************************
 * Author: Dileep Awasthi
 * This Class can be used for domain validation of any email address and find
 * IP Address of domain.
 
 * This class get the MX record with the highest priority then do an A record lookup
 * any suggestion are welcome. Please contact me at dileep.awasthi@gmail.com
 * You can reach me at http://logicsart.com also.
**/
class EmailValdation
{
	var $ip;
	var $email;
	function ipAddress($domain)
	{
		ini_set('display_errors', "0");
		$this->ip = false;
		//Check with MX Records. DNS_MX is type of dns_get_record function. 
		try {
		$records = dns_get_record($domain, DNS_MX);
		} catch(Exception $e) {
			return $e->getMessage();
		}
		if(!empty($records)) {
		$priority = null;
		foreach($records as $record) {
			if($priority == null || $record['pri'] < $priority) {
				$myip = gethostbyname($record['target']);
				if($myip != $record['target']) {
					$this->ip = $myip;
					$priority = $record['pri'];
				}
			}
		}
		}
		//If no MX records are found
		if(!$this->ip) {
		$ip = gethostbyname($domain);
			if($this->ip == $domain) {
				$this->ip = false;
			}
		}

		return $this->ip;
	}
	
	function checkEmailDomain() //This method checks domain existance
	{
		list($emailPart, $domainPart) = explode('@', $this->email); //Breaking email into domain and id
		if(filter_var($this->email, FILTER_VALIDATE_EMAIL) && $this->ipAddress($domainPart)) {
			return 1; //Invalid domain
		}
		else {
			return 0; //Valid Domain
		}
	}
	
}
?>
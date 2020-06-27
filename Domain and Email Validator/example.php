<?php
/*Here is example that how can you use the class*/
include "validmail.php";
$test = new EmailValdation;
$test->email = 'dileep.awasthi@gmail.com';
if($test->checkEmailDomain() == 0) { //Check DNS of Email Address
	echo 'Invalid Domain <br />';
} else {
	echo 'Valid Domain <br />';
}
$mydomain = 'logicsart.com';
echo 'IP Address of <b>'.$mydomain.'</b> is: '.$test->ipAddress($mydomain); //Check IP address

?>
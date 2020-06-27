<?php
require_once "../regexpBuilder.php";
/*
SQL datetime format checking. Format: 2009-11-03 11:55:29
LOGIC:
- 4 numbers
- hyphen
- 2 numbers
- hyphen
- 2 numbers
- space
- 2 numbers
- :
- 2 numbers
- :
- 2 numbers
*/

$regexp=new regexpBuilder(CASE_INSENSITIVE);
$regexp->matchLineStart()	//Perform the check starting from the begin of the string
->match(DIGIT_CHAR)->frequency(4)	//4 numbers
->match("-")	//hyphen
->match(DIGIT_CHAR)->frequency(2)	//2 numbers
->match("-")	//hyphen
->match(DIGIT_CHAR)->frequency(2)	//2 numbers
->match(SPACE_CHAR)	//space char
->match(DIGIT_CHAR)->frequency(2)	//2 numbers
->match(":")	//:
->match(DIGIT_CHAR)->frequency(2)	//2 numbers
->match(":")	//:
->match(DIGIT_CHAR)->frequency(2)	//2 numbers
->matchLineEnd();	//Match the end of the string

echo "2009-11-03 11:55:29: ".($regexp->testOn("2009-11-03 11:55:29") ? "true" : "false"); //True
echo "<br>2009/11/03 11:55:29: ".($regexp->testOn("2009/11/03 11:55:29") ? "true" : "false"); //False
echo "<br>2009-11-3 11:55:29: ".($regexp->testOn("2009-11-3 11:55:29") ? "true" : "false"); //False
?>
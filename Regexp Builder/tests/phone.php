<?php
require_once "../regexpBuilder.php";
/*
Phone number checking. Format: (999) 999-9999
LOGIC:
- (
- 3 numbers
- )
- zero ore more space characters
- 3 numbers
- hyphen
- 4 numbers
*/

$regexp=new regexpBuilder(CASE_INSENSITIVE);
$regexp->matchLineStart()	//Perform the check starting from the begin of the string
->match("(")	//(
->match(DIGIT_CHAR)->frequency(3)	//3 numbers
->match(")")	//)
->match(GENERAL_SPACE_CHAR)->frequency(ZERO_OR_MORE)	//Zero ore more space characters
->match(DIGIT_CHAR)->frequency(3)	//3 numbers
->match("-")	//hyphen
->match(DIGIT_CHAR)->frequency(4)	//4 numbers
->matchLineEnd();	//Match the end of the string

echo "(999) 999-9999: ".($regexp->testOn("(999) 999-9999") ? "true" : "false"); //True
echo "<br>(999)999-9999: ".($regexp->testOn("(999)999-9999")? "true" : "false"); //True
echo "<br>(999) 999.9999: ".($regexp->testOn("(999) 999.9999")? "true" : "false"); //False
echo "<br>( 999 ) 999-9999: ".($regexp->testOn("( 999 ) 999-9999")? "true" : "false"); //False
?>
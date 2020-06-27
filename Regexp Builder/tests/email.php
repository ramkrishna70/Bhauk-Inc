<?php
require_once "../regexpBuilder.php";
/*
Email checking.
LOGIC:
- one or more letter, number or dot characters
- @
- one or more letter, number or dot characters
- dot
- Letters repeated between 2 and 4 times
*/

$regexp=new regexpBuilder(CASE_INSENSITIVE);
$regexp->matchLineStart()	//Perform the check starting from the begin of the string
->matchOneOfTheseChars(LETTER_CHAR.DIGIT_CHAR.".")->frequency(ONE_OR_MORE)	//Letter, number or dot repeated on ore more times
->match("@")	//@ sign
->matchOneOfTheseChars(LETTER_CHAR.DIGIT_CHAR.".")->frequency(ONE_OR_MORE)	//Letter, number or dot repeated on ore more times
->match(".")	//dot
->matchOneOfTheseChars(LETTER_CHAR)->frequency(2,4)	//Letters repeated between 2 and 4 times
->matchLineEnd();	//Match the end of the string

echo "example@email.com: ".($regexp->testOn("example@email.com") ? "true" : "false"); //True
echo "<br>example@email.comwrong: ".($regexp->testOn("example@email.comwrong") ? "true" : "false"); //False
?>
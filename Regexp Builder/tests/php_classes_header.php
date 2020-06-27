<?php
require_once "../regexpBuilder.php";
/*
PHP class header. Capture the name of the class and the optional extension class.
Format: class name extends extensionName
LOGIC:
- class
- one or more spaces
- start capturing
- one or more letters, digit or underscore characters
- stop capturing
- start a new group
- one or more spaces
- extends
- one or more spaces
- start capturing
- one or more letters, digit or underscore characters
- stop capturing
- close group and match only if it's present
*/

$regexp=new regexpBuilder(CASE_INSENSITIVE);
$regexp->match("class")	//Class
->match(SPACE_CHAR)	//One or more spaces
->capture()	//start capturing
->matchOneOfTheseChars(LETTER_CHAR.DIGIT_CHAR."_")->frequency(ONE_OR_MORE)	//one or more letters, digit or underscore characters
->closeCapture()	//stop capturing
->openGroup()		//start a new group
->match(SPACE_CHAR)	//One or more spaces
->match("extends")	//extends
->match(SPACE_CHAR)	//One or more spaces
->capture()	//start capturing
->matchOneOfTheseChars(LETTER_CHAR.DIGIT_CHAR."_")->frequency(ONE_OR_MORE)	//one or more letters, digit or underscore characters
->closeCapture()//stop capturing
->closeGroup()->frequency(ZERO_OR_ONE);	//close group and match only if it's present

$match=$regexp->execOn("class test extends extensionName");
echo "class test extends extensionName<br>Match 1: ".$match[1][0]."<br>"."Match 2: ".$match[2][0]; //Match 1: test, Match 2: extensionName
$match=$regexp->execOn("class test");
echo "<br><br>class test<br>Match 1: ".$match[1][0]."<br>"."Match 2: ".(isset($match[2][0]) ? $match[2][0] : ""); //Match 1: test, Match 2:
?>
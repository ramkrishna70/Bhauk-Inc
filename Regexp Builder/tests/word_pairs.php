<?php
require_once "../regexpBuilder.php";
/*
Word pair test. Find the first word in a sequence of equal words.
Format: test test test FOO FOO
LOGIC:
- start capture the first word
- letter characters repeated one or more times
- end capture
- open a group
- space character
- match the first word captured
- close group and match only if it is repeated one ore more times
*/

$regexp=new regexpBuilder();
$regexp->capture("first word")	//Start a capture and assign it a name
->match(LETTER_CHAR)->frequency(ONE_OR_MORE)	//letter characters repeated one or more times
->closeCapture()	//end capture
->openGroup()	//open a group
->match(SPACE_CHAR)	//space character
->matchCapture("first word")	//match the first word captured
->closeGroup()->frequency(ONE_OR_MORE);	//close group and repeat it one ore more times

$match=$regexp->execOn("test test test FOO FOO",PREG_SET_ORDER);
echo "test test test FOO FOO<br>Match 1: ".$match[0][1]."<br>"."Match 2: ".$match[1][1]; //Match 1: test, Match 2:FOO
?>
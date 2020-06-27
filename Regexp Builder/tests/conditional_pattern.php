<?php
require_once "../regexpBuilder.php";
/*
Find any word that starts with "abc". If it's followed by a "d" then match the rest of the word otherwise match the first character after "abc"
String: "abcdefg abc2323 abcdd abc342"
LOGIC:
- start capture
- match "abc"
- start condition
- if followed by "d"
- then match every letter repeated one or more times
- otherwise match the first character
- close the condition
- end capture
*/

$regexp=new regexpBuilder();
$regexp->capture()	//start capture
->match("abc")	//match "abc"
->ifItIs(FOLLOWED_BY)	//start condition
->match("d")	//if followed by "d"
->then()->match(LETTER_CHAR)->frequency(ONE_OR_MORE)	//then match every letter repeated one or more times
->otherwise()->matchEverything()	//otherwise match the first character
->closeIf()	//close the condition
->closeCapture();	//end capture

$match=$regexp->execOn("abcdefg abc2323 abcdd abc342");
foreach($match[1] as $result)
	echo $result."<br>";	//abcdefg, abc2, abcdd, abc3
?>
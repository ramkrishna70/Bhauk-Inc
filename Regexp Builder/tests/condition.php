<?php
require_once "../regexpBuilder.php";
/*
Find any word that starts with "abc" followed by "de" or "yz". String: "abc abcde abcyz abced test"
LOGIC:
- start capture
- match "abc"
- start condition
- if followed by "de" or "yz"
- close the condition
- match the rest of the word by matching every char except the space repeated one ore more times
- end capture
*/

$regexp=new regexpBuilder();
$regexp->capture()	//start capture
->match("abc")	//match "abc"
->ifItIs(FOLLOWED_BY)	//start condition
->matchOneOfTheseWords("de","yz")	//if followed by "de" or "yz"
->closeIf()	//close the condition
->matchEveryCharExcept(SPACE_CHAR)->frequency(ONE_OR_MORE)	//match the rest of the word by matching every char except the space repeated one ore more times
->closeCapture();	//end capture

$match=$regexp->execOn("abc abcde abcyz abced test");
foreach($match[1] as $result)
	echo $result."<br>";	//abcde, abcyz
?>
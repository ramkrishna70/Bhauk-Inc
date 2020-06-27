<?php
require_once "../regexpBuilder.php";
/*
Match every "cat" then if it is preceeded by "dog" than match "run" otherwise match "eat"
LOGIC:
- open capture
- match "cat"
- start condition
- if it's preceeded by "dog"
- then match "run"
- otherwise match "eat"
- close condition
- close capture
*/

$regexp=new regexpBuilder();
$regexp->capture() //open capture
->match("cat") //match "cat"
->ifItIs(PRECEEDED_BY) //start condition
->match("dog".SPACE_CHAR) //if it's preceeded by "dog"
->then()->match(SPACE_CHAR."run") //then match "run"
->otherwise()->match(SPACE_CHAR."eat") //otherwise match "eat"
->closeIf()	//close condition
->closeCapture();	//close capture

$matches=$regexp->execOn("dog cat run, mouse cat eat");
print_r($matches[1]); 
//[0] => cat run 
//[1] => cat eat
?>
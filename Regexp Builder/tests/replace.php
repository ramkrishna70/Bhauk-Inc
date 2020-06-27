<?php
require_once "../regexpBuilder.php";
/*
Replace function test. Replace every space with the space character in html format (&nbsp;).
Format: This is a test
LOGIC:
- find space charachter repeated one or more times
- replace with &nbsp;
*/

$regexp=new regexpBuilder();
$regexp->match(SPACE_CHAR)->oneOrMoreTimes();	//find space charachter repeated one or more times

$result=$regexp->replaceWith("&nbsp;","This is a test");
echo "This is a test<br>Result: ".$result; //Result: This&nbsp;is&nbsp;a&nbsp;test
?>
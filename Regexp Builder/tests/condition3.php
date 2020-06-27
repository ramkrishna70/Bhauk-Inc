<?php
require_once "../regexpBuilder.php";
/*
Match every "abc" followed by "def" and preceeded by "xyz"
LOGIC:
- match "abc"
- start condition
- if it's followed by "def"
- close condition
- start condition
- if it's preceeded by "xyz"
- close condition
*/

$regexp=new regexpBuilder();
$regexp->match("abc") //match "abc"
->ifItIs(FOLLOWED_BY) //start condition
->match("def") //if it's followed by "def"
->closeIf()	//close condition
->ifItIs(PRECEEDED_BY) //start condition
->match("xyz") //if it's preceeded by "xyz"
->closeIf();	//close condition

$matches=$regexp->execOn("xyzabcdef");
print_r($matches[0]); 
//[0] => abc

//Revert conditions
$regexp2=new regexpBuilder();
$regexp2->match("abc") //match "abc"
->ifItIs(PRECEEDED_BY) //start condition
->match("xyz") //if it's preceeded by "xyz"
->closeIf()//close condition
->ifItIs(FOLLOWED_BY) //start condition
->match("def") //if it's followed by "def"
->closeIf();	//close condition	

$matches=$regexp2->execOn("xyzabcdef");
print_r($matches[0]);
//[0] => abc
?>
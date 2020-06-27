<?php
require_once "../regexpBuilder.php";
/*
Replace with function test. Increase every number in the string.
Format: Today is 09/11/2009

LOGIC:
- find every number repeated one ore more times
- increase and return it with a function
*/
$regexp=new regexpBuilder();
$regexp->match(DIGIT_CHAR)->oneOrMoreTimes();	//find every number repeated one ore more times

$callback=create_function('$match','return (int)$match[0]+1;');
$result=$regexp->replaceWithCallback($callback,"Today is 09/11/2009");
echo "Today is 09/11/2009<br>Result: ".$result; //Result: Today is 10/12/2010
?>
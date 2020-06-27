<?php
require_once "../regexpBuilder.php";
/*
Get every string that contains only letters:
Format: array("abc"," ",".","def","");

LOGIC:
- match line start
- match letters repeated one or more times
- match line end
- grep
*/

$regexp=new regexpBuilder();
$regexp->matchLineStart() //match line start
->matchOneOfTheseChars(LETTER_CHAR)->oneOrMoreTimes() //match letters repeated one or more times
->matchLineEnd();  //match line end

$input=array("abc"," ",".","def","");
$result=$regexp->grep($input);

echo "Test: array(\"abc\",\" \",\".\",\"def\",\"\");<br>";
echo "Result: ".print_r($result,true)."<br>"; //Array ( [0] => abc [3] => def ) 
?>
<?php
require_once "../regexpBuilder.php";
/*
Split the function with every single space:
Format: Sky    is  the limit

LOGIC:
- match every space
- split the string
*/

$regexp=new regexpBuilder();
$regexp->matchOneOfTheseChars(SPACE_CHAR);  //match every space

$result=$regexp->split("Sky    is  the limit");
//Remove empty matches
$result2=$regexp->split("Sky    is  the limit",-1,PREG_SPLIT_NO_EMPTY);

echo "Test: Sky    is  the limit<br>";
echo "Result 1: ".print_r($result,true)."<br>"; //Array ([0]=>Sky[1]=>[2]=>[3]=>[4]=>is[5]=>[6]=>the[7]=>limit) 
echo "Result 2: ".print_r($result2,true)."<br>" //Array ([0]=>Sky[1]=>is[2]=>the[3]=>limit)
?>
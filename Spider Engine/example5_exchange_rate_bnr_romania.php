<?php
/**
* About author:
* Radu T.
* email: eagle[not]bv[not]ro[[not][isat][not]]yahoo[[not][isdot][not]]com
*
* About class:
* SpiderEngine v.1.1.0 class for spidering any html page
*   -fetchData() - for reading the content of a html page
*   -processData() - for doing whatever you want to the results
*
*     -url - url to read from eg. http://www.home.com/page_no_<range[0]>.html
*     -range - array for range of action on url eg. array(0=>array("start"=>1,"end"=>10,"step"=>1)) - that means: for(i=1;i<=10;i+=1)
*     -pattern - the html text containing the pattern_definition and text             
*     -pattern_definition - array definition names eg. array("dummy","cat","subcat")
*     -start - from where the spider reads the content of the page
*     -end - array of "to_process" and "not_to_process" content, if a text from array "to_process" was found in content page then the data is spidered and is called processData(), if a text from array "not_to_process" was found in content page then just show a message
*     
*     -pattern definition example: {p[abc]}, {p[1]},{p[#]},{p[no.1]} etc.
*   -pattern can be found in the same page multiple times
*/

include_once("spiderengine.class.php");

//spider all exchange rate for BNR Romania, from www.cursvalutar.ro

$obj->url="http://www.cursvalutar.ro/";
$obj->range=array();
$obj->pattern_definition=array("dummy","name","short_name","value"); //dummy is used for content that changes between pages and we are not interested in it
$obj->start='<body';
$obj->end=array("to_process"=>array('</body>'),"not_to_process"=>array());
$obj->pattern='<tr><td width="10%"><img src="{p[dummy]}" width="{p[dummy]}" height="{p[dummy]}" border="{p[dummy]}" alt="{p[dummy]}"></td><td width="40%">{p[name]}</td><td width="20%">{p[short_name]}</td><td width="20%" align="right">{p[value]}</td><td width="10%" align="center"><img src="{p[dummy]}" alt="{p[dummy]}" width="{p[dummy]}" height="{p[dummy]}"></td></tr>';
$obj->fetchData();

?>
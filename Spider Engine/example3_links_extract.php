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

//spider links from http://www.susdescargas.com

$obj=new SpiderEngine();
$obj->url="http://www.susdescargas.com/windows/";
$obj->range=array();
$obj->pattern_definition=array("dummy","link","desc"); //dummy is used for content that changes between pages and we are not interested in it
$obj->start='<table cellpadding="0" cellspacing="0" width="100%" class="navi" summary="" align="center">';
$obj->end=array("to_process"=>array('</table>
</div><table cellpadding="0" cellspacing="0" width="100%" class="navicat" summary="" align="center">'),"not_to_process"=>array());
$obj->pattern='<td{p[dummy]}><a{p[dummy]}href="{p[link]}"{p[dummy]}>{p[desc]}</a></td>';

$obj->fetchData();

?> 
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

class MySpider extends SpiderEngine {
	
	function processData($pattern_matches) //you can do whatever you want here with the pattern matches, insert in a database etc.
	{
		foreach ($pattern_matches as $k=>$v)
		{
			if($v['dummy'])
			{
				unset($pattern_matches[$k]['dummy']);
			}
		}
		print_r($pattern_matches);
	}
}

//spider all infos from http://www.google.com
//modify in url "?q="+keywords and your needed range in $obj->range

$obj=new MySpider();
$obj->url="http://www.google.com/search?q=Spider+Engine&start={range[0]}";
$obj->range=array(0=>array("start"=>0,"end"=>100,"step"=>10));
$obj->pattern_definition=array("dummy","link","title","content","cache","similar"); //dummy is used for content that changes between pages and we are not interested in it
$obj->start='<div>';
$obj->end=array("to_process"=>array('</body>'),"not_to_process"=>array());
$obj->pattern='<div class=g{p[dummy]}><h2 class=r><a{p[dummy]}href="{p[link]}"{p[dummy]}class=l{p[dummy]}>{p[title]}</a></h2><table border=0 cellpadding=0 cellspacing=0><tr><td class=j><font size=-1>{p[content]}<br><span{p[dummy]}>{p[dummy]}</span><nobr><a class=fl href="{p[cache]}">{p[dummy]}</a> - <a class=fl href="{p[similar]}">{p[dummy]}</a></nobr></font></td></tr></table></div>';
$obj->fetchData();

?> 
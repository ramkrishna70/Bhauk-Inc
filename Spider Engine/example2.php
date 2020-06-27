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
* 	-url - url to read from eg. http://www.home.com/page_no_<range[0]>.html
* 	-range - array for range of action on url eg. array(0=>array("start"=>1,"end"=>10,"step"=>1)) - that means: for(i=1;i<=10;i+=1)
* 	-pattern - the html text containing the pattern_definition and text		     
* 	-pattern_definition - array definition names eg. array("dummy","cat","subcat")
* 	-start - from where the spider reads the content of the page
* 	-end - array of "to_process" and "not_to_process" content, if a text from array "to_process" was found in content page then the data is spidered and is called processData(), if a text from array "not_to_process" was found in content page then just show a message
* 	
* 	-pattern definition example: {p[abc]}, {p[1]},{p[#]},{p[no.1]} etc.
*   -pattern can be found in the same page multiple times
*/

include_once("spiderengine.class.php");

//spider news data from http://www.susdescargas.com

$obj=new SpiderEngine();
$obj->url="http://www.susdescargas.com/news/Stiri-IT/";
$obj->range=array();
$obj->pattern_definition=array("dummy","id","title","link","description","author","date"); //dummy is used for content that changes between pages and we are not interested in it
$obj->start='<table cellpadding="0" cellspacing="0" width="98%" class="" summary="" align="center" style="margin-top:2px; margin-bottom:0px;padding-bottom:2px;"><tr><td align="left" width="50%" class="blue">Showing';
$obj->end=array("to_process"=>array('<table cellpadding="0" cellspacing="0" width="98%" class="" summary="" align="center" style="margin-top:2px; margin-bottom:2px;border-top:1px dotted #005fa2;"><tr><td align="left" width="50%" class="blue">Showing'),"not_to_process"=>array());
$obj->pattern='<tr style=""><td width="100%" align="left" style="border-bottom:1px dotted #005fa2;border-right:1px dotted #005fa2;padding-left:2px;padding-top:3px;padding-bottom:3px;"><table cellpadding="0" cellspacing="2" width="100%" class="" summary="" align="center" style="margin-top:0px; margin-bottom:0px;cursor:pointer;" title="{p[dummy]}" onclick="go(\'{p[link]}\');">
			<tr>
				<td style="padding:2px;border:1px solid #ccdfec; background-color:#f4faff;" width="5%" valign="top" align="center"><span class="blueminibold">{p[id]}</span></td>
				<td style="padding-left:5px;padding-top:2px;padding-bottom:2px;border:1px solid #ccdfec; background-color:#f4faff;" width="50%" valign="top" align="left"><span class="orangeminibold">{p[title]}</span>&nbsp;<span class="blueminibold">|</span>&nbsp;<span class="greygmini">{p[description]}</span></td>
				<td style="padding:2px;border:1px solid #ccdfec; background-color:#f4faff;" width="25%" valign="top" align="center"><span class="orangeminibold">by {p[author]}</span></td>
				<td style="padding:2px;border:1px solid #ccdfec; background-color:#f4faff;" width="20%" valign="top" align="center"><span class="orangeminibold">Data: {p[date]}</span></td>
			</tr>
			</table></td></tr>';

$obj->fetchData();

?>
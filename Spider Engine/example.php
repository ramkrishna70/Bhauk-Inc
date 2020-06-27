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

//spider some article in multiple pages from space.com

$obj=new SpiderEngine();
$obj->url="http://www.space.com/scienceastronomy/101_earth_facts_030722-{range[0]}.html";
$obj->range=array(0=>array("start"=>1,"end"=>10,"step"=>1));
$obj->pattern_definition=array("dummy","title","author","date","content"); //dummy is used for content that changes between pages and we are not interested in it
$obj->start='<!--end bigad--> ';
$obj->end=array("to_process"=>array('<a name="endstory"></a>'),"not_to_process"=>array());
$obj->pattern='<table border="0">  
 	<tr>
 		<td colspan="2" style="padding-bottom: 10px;"><a href="{p[dummy]}" target="_blank"><img src="{p[dummy]}" border="0"/></a>
 		</td> 
 	</tr> 
 	<tr>
 		<td width="125" align="left" valign="top"><img src=\'{p[dummy]}\' border=\'{p[dummy]}\'>
 		</td>
 		<td width="355" align="left" valign="top">
 			<font face="Verdana, Helvetica, sans-serif" size="3" color="#1B4872"><b>{p[title]}</b><br><font face="Verdana, Helvetica, sans-serif" size="1" color="#333333"><b>By <A HREF="/php/contactus/feedback.php?r=rb">{p[author]}</A></b><br>Senior Science Writer<br></font></font><font size="1" face="arial,helvetica" color="#330066">posted: 06:20 am ET<br>{p[date]}<br></font><br>
 		</td>
 	</tr>
 </table>


 <a name="beginstory"></a>
{p[content]}{p[dummy]}</A></p>';

$obj->fetchData();
?>
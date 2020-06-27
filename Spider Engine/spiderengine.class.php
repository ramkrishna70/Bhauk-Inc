<?php
/**
* About author:
* Radu T.
* email: eaglebvro[@]yahoo[dot]com
*
* If you want to spider something, just notify me on email and I'll help you.
*
* About class:
* SpiderEngine v.2 class for spidering any html page
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

class SpiderEngine {

    var $url="";
    var $range=array();
    var $pattern="";
    var $pattern_matches=array();
    var $pattern_definition=array();
    var $start="";
    var $end=array();
    var $openType;
    var $pathToLogFile="";
    var $recursiveClassName="";
    var $utf8_encode="";
    var $utf8_decode="";
                	
// Constructor
function SpiderEngine ($openType="fgc",$pathToLogFile='./logfile.log',$utf8_encode=false,$utf8_decode=false) {
	
	error_reporting( E_ALL );
	ini_set( 'display_errors' , true );
	//set_time_limit( 3600 * 24 );
	
	print('<html><meta http-equiv="content-type" content="text/html; charset=iso-8859-1"><head><title>SPIDER ENGINE @eaglebvro</title></head><body>');
	
	$this->openType=$openType;
	$this->pathToLogFile=$pathToLogFile;
	$this->utf8_encode=$utf8_encode;
	$this->utf8_decode=$utf8_decode;
	
}

function scrollDown()
{
	print( '<script> window.scrollBy(0,1000000); </script>' );
}
          
function open_external_url($url, $method = "fgc")
{
   //sleep(1);
	$data = '';
   if(strtolower($method) == "curl")
   {
       $curl = curl_init();

	  // Setup headers - I used the same headers from Firefox version 2.0.0.6
	  // below was split up because php.net said the line was too long. :/
	  $header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
	  $header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
	  $header[] = "Cache-Control: max-age=0";
	  $header[] = "Connection: keep-alive";
	  $header[] = "Keep-Alive: 300";
	  $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
	  $header[] = "Accept-Language: en-us,en;q=0.7,ro;q=0.3";
	  $header[] = "Pragma: "; // browsers keep this blank.
	
	  curl_setopt($curl, CURLOPT_URL, $url);
	  curl_setopt($curl, CURLOPT_USERAGENT, 'Googlebot/2.1 (+http://www.google.com/bot.html)');
	  curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
	  curl_setopt($curl, CURLOPT_REFERER, 'http://www.google.com');
	  curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');
	  curl_setopt($curl, CURLOPT_AUTOREFERER, true);
	  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	  curl_setopt($curl, CURLOPT_TIMEOUT, 10);
	
	  $html = curl_exec($curl); // execute the curl command
	  curl_close($curl); // close the connection
	
	  return $html; // and finally, return $html
   }
   else if(strtolower($method) == "fopen")
   {
       $file = fopen($url, "r");
       if ($file)
       {
	       while(!feof($file)) {
	           $data = $data . fgets($file, 4096);
	       }
	       fclose ($file);
       }
       else return false;       
   }
   else if(strtolower($method) == "fgc")
   {
   		$data=file_get_contents($url);
       
       	if(!$data) return false;
   }
   return $data;
} 

function imageNameFromUrl($source)
{
	$img=explode('/',$source);
	return rawurlencode($img[count($img)-1]);
}

function fetchImage($source,$destination)
{
	$source=substr_replace($source,rawurlencode(basename($source)),strpos($source,basename($source)));
	$data = file_get_contents($source);
	$file = fopen($destination, "w+");
	$r=fputs($file, $data);
	fclose($file);
	
	return $r;
}

function strip_selected_tags($str, $tags = "", $stripContent = false)
{
    preg_match_all("/<([^>]+)>/i",$tags,$allTags,PREG_PATTERN_ORDER);
    foreach ($allTags[1] as $tag){
        if ($stripContent) {
            $str = preg_replace("/<".$tag."[^>]*>.*<\/".$tag.">/iU","",$str);
        }
        $str = preg_replace("/<\/?".$tag."[^>]*>/iU","",$str);
    }
    return $str;
}

function fetchData($process=true)
{

//make an array of text and pattern def	
$arr=explode(']}',$this->pattern);
//print_r($arr);
$array=array();
foreach ($arr as $ak=>$av)
{
	$array[$ak]['text']=$av;
	foreach($this->pattern_definition as $pd)
	{
		$array[$ak]['text']=str_replace('{p['.$pd,'',$array[$ak]['text']);
	}
	
	$array[$ak]['pd']=explode('{p[',$arr[$ak]);
	$array[$ak]['pd']=isset($array[$ak]['pd'][1])?$array[$ak]['pd'][1]:"dummy";
}
//print_r($array);	
//end_make an array of text and pattern def	

if(count($this->range)>0)
{
	
foreach ($this->range as $k=>$v)
{

if(substr_count($this->url,'{range['.$k.']}')==1)
{	
	
for ($i=$this->range[$k]['start'];$i<=$this->range[$k]['end'];$i+=$this->range[$k]['step'])
{
	
//$i is the page
$t=new Timer();	// starts the timer foreach page

$url=str_replace('{range['.$k.']}',$i,$this->url);
	
print('<span style="color:#005fa2;">Url: '.$url.' processing!</span><br>');
$this->scrollDown();
$this->appendToLog('Url: '.$url.' processing!');
	
$content_temp= $this->open_external_url($url,$this->openType);
   
  //print $content_temp;

if($content_temp){   
   
	foreach ($this->end['not_to_process'] as $ntp)
	{
		
		if($ntp!='')
		{
			if(substr_count($content_temp,$ntp)>0)
			{
				print('<span style="color:#ff6600;">Url: '.$url.' wasn\'t processed because "'.$ntp.'" was found in content !</span><br>');
				$this->scrollDown();
				$this->appendToLog('Url: '.$url.' wasn\'t processed because "'.$ntp.'" was found in content !');
				$next=false;
			}
			elseif(substr_count($content_temp,$ntp)==0) {
				$next=true;
			}
		}
		else {
			$next=true;
		}
	}
	
	//echo $next;
	
	if($next)
	{
		foreach ($this->end['to_process'] as $ta)
		{   
			
			if(substr_count($content_temp,$ta)==1)
			{
				$content_temp=$this->textBetween($this->start,$ta,$content_temp); //take the content between start and end, end must be an array
				
								
				if($this->utf8_encode)
				{
					$content_temp=utf8_encode($content_temp);
				}
				
				if($this->utf8_decode)
				{
					$content_temp=utf8_decode($content_temp);
				}
				
				//$content_temp=chars_encode($content_temp,true);
		
				//print ($content_temp);
				//$this->scrollDown();
				
				//print_r($array);
				//$this->scrollDown();
				
				$rest='';
								
				do
				{
						
					for($j=0;$j<=(count($array)-2);$j++)
					{
						$this->pattern_matches[$url][$array[$j]['pd']]=$this->textBetween($array[$j]['text'],$array[$j+1]['text'],substr($content_temp,strlen($rest)));
						$rest.=$array[$j]['text'].$this->pattern_matches[$url][$array[$j]['pd']];
					}
					
					//$kpm++;
				}
				while (substr_count(substr($content_temp,strlen($rest)),$array[0]['text'])>0);
				
				if($process)
				{			
					$this->processData();
					unset($this->pattern_matches);
				}
				
				$this_time=$t->getTTMS();
				print('<span style="color:#ff6600;">Url: '.$url.' has been processed in '.$this_time.' !</span><br>');					$this->scrollDown();		
				$this->appendToLog('Url: '.$url.' has been processed in '.$this_time.' !');
					
				
			}
		}
	}
}
else {
	print('<span style="color:#ff6600;">Url: '.$url.' couldn\'t be opened!</span><br>');
	$this->appendToLog('Url: '.$url.' couldn\'t be opened!');
	sleep(20);
	print('<span style="color:#ff6600;">The spider will be restarted from url: '.$url.' !</span><br>');
	$this->appendToLog('The spider will be restarted from url: '.$url.' !');
	
	$this->setSpiderConfigRangeStart($i);
	
	if(eval("return (\$obj_new=new ".$this->recursiveClassName."(\"".$this->openType."\",\"".$this->pathToLogFile."\"));"))
	{
		$obj_new->url=$this->url;
		$obj_new->recursiveClassName=$this->recursiveClassName;
		$obj_new->start=$this->start;
		$obj_new->end=$this->end;
		$obj_new->pattern=$this->pattern;
		$obj_new->pattern_definition=$this->pattern_definition;
		$obj_new->range=$this->range;
		$obj_new->range[$k]['start']=$i;
		$obj_new->fetchData();
	}
	
	unset($this);
	
}

}
}
}
}
else{
	
$t=new Timer();	// starts the timer foreach page
$url=$this->url;	
	
print('<span style="color:#005fa2;">Url: '.$url.' processing!</span><br>');
$this->scrollDown();
$this->appendToLog('Url: '.$url.' processing!');
	
$content_temp= $this->open_external_url($url,$this->openType);

//print $content_temp;

if($content_temp)
{
   
	foreach ($this->end['not_to_process'] as $ntp)
	{
		if($ntp!='')
		{
			if(substr_count($content_temp,$ntp)>0)
			{
				print('<span style="color:#ff6600;">Url: '.$url.' wasn\'t processed because "'.$ntp.'" was found in content !</span><br>');
				$this->scrollDown();
				$this->appendToLog('Url: '.$url.' wasn\'t processed because "'.$ntp.'" was found in content !');
				$next=false;
			}
			elseif(substr_count($content_temp,$ntp)==0) {
				$next=true;
			}
		}
		else {
			$next=true;
		}
	}
	
	if($next)
	{
		foreach ($this->end['to_process'] as $ta)
		{   
			if(substr_count($content_temp,$ta)==1)
			{
				$content_temp=$this->textBetween($this->start,$ta,$content_temp); //take the content between start and end, end must be an array
				
								
				if($this->utf8_encode)
				{
					$content_temp=utf8_encode($content_temp);
				}
				
				if($this->utf8_decode)
				{
					$content_temp=utf8_decode($content_temp);
				}
				
				//$content_temp=chars_encode($content_temp,true);
		
				//print ($content_temp);
				//$this->scrollDown();
				
				//print_r($array);
				//$this->scrollDown();
				
				$rest='';
				$kpm=0;
								
				do
				{
						
					for($j=0;$j<=(count($array)-2);$j++)
					{
						$this->pattern_matches[$kpm][$array[$j]['pd']]=$this->textBetween($array[$j]['text'],$array[$j+1]['text'],substr($content_temp,strlen($rest)));
						$rest.=$array[$j]['text'].$this->pattern_matches[$kpm][$array[$j]['pd']];
					}
					
					$kpm++;
				}
				while (substr_count(substr($content_temp,strlen($rest)),$array[0]['text'])>0);
				
				if($process)
				{			
					$this->processData();
				}
				
				$this_time=$t->getTTMS();
				print('<span style="color:#ff6600;">Url: '.$url.' has been processed in '.$this_time.' !</span><br>');							$this->scrollDown();		
				$this->appendToLog('Url: '.$url.' has been processed in '.$this_time.' !');
					
				
			}
		}
	}
}
else {
	print('<span style="color:#ff6600;">Url: '.$url.' couldn\'t be opened!</span><br>');
	$this->appendToLog('Url: '.$url.' couldn\'t be opened!');
	sleep(5);
}

}
print('</body></html>');

}

function fetchDataFromContent($content_temp)
{

//make an array of text and pattern def	
$arr=explode(']}',$this->pattern);
//print_r($arr);
$array=array();
foreach ($arr as $ak=>$av)
{
	$array[$ak]['text']=$av;
	foreach($this->pattern_definition as $pd)
	{
		$array[$ak]['text']=str_replace('{p['.$pd,'',$array[$ak]['text']);
	}
	
	$array[$ak]['pd']=explode('{p[',$arr[$ak]);
	$array[$ak]['pd']=isset($array[$ak]['pd'][1])?$array[$ak]['pd'][1]:"dummy";
}
//print_r($array);	
//end_make an array of text and pattern def	

if($this->utf8_encode)
{
	$content_temp=utf8_encode($content_temp);
}

if($content_temp){   
   
$kpm=0;   
		
$rest='';
				
do
{
		
	for($j=0;$j<=(count($array)-2);$j++)
	{
		$this->pattern_matches[$kpm][$array[$j]['pd']]=$this->textBetween($array[$j]['text'],$array[$j+1]['text'],substr($content_temp,strlen($rest)));
		$rest.=$array[$j]['text'].$this->pattern_matches[$kpm][$array[$j]['pd']];
	}
	
	$kpm++;
}
while (substr_count(substr($content_temp,strlen($rest)),$array[0]['text'])>0);

//print_r($this->pattern_matches);	

return $this->pattern_matches;
}

}

function arrayToString($array)
{
	$text='';$x=0;
    $text.="array(";
    $count=count($array);

    foreach ($array as $key=>$value)
    {
        $x++;

        if (is_array($value))
        {
            if(substr($text,-1,1)==')')    $text .= ',';
            $text.='"'.$key.'"'."=>".arraytostring($value);
            continue;
        }

        $text.="\"$key\"=>\"$value\"";

        if ($count!=$x) $text.=",";
    }

    $text.=")";

    if(substr($text, -4, 4)=='),),')$text.='))';

    return $text;
}

function processData() //anything you want to process matches
{

	print_r($this->pattern_matches);

}

function textBetween($s1,$s2,$s){
  $s1 = strtolower($s1);
  //echo $s1;
  $s2 = strtolower($s2);
  //echo $s2;
  $L1 = strlen($s1);
  //echo $L1;
  $scheck = strtolower($s);
  //$scheck = $s;
  //echo $scheck;
  if($L1>0){$pos1 = strpos($scheck,$s1);} else {$pos1=0;}
  if($pos1 !== false){
   if($s2 == '') return substr($s,$pos1+$L1);
   $pos2 = strpos(substr($scheck,$pos1+$L1),$s2);
   if($pos2!==false) return substr($s,$pos1+$L1,$pos2);
  }
  return '';
}

function appendToLog($logstr)
{
	    $timestamp = date("M d H:i:s");
        
        $log_append_str = "$timestamp " .$logstr;
        
        if(file_exists($this->pathToLogFile) && is_writeable($this->pathToLogFile))
        {
                $fp = fopen($this->pathToLogFile, 'a+');
                fputs($fp, "$log_append_str\r\n");
                fclose($fp);
        }
        else if(!file_exists($this->pathToLogFile) && is_writeable($this->pathToLogFile))
        {
                touch($this->pathToLogFile);
                chmod($this->pathToLogFile, 0777);
                $fp = fopen($this->pathToLogFile, 'a+');
                fputs($fp, "$log_append_str\r\n");
                fclose($fp);
        }
        else
        {
                die("Unable to write to ".$this->pathToLogFile." ...");
        }       
}

}//end class
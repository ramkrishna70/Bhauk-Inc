<?php
/******************************************************
A123Z Class
Generates Quick links for navigiation pages depending on number of results
Author : Shadi Ali
Email  : write2shadi@gmail.com

visit  : http://oxygen.dnsprotect.com/~pharoaho/

********************************************************/

/**********************************************************


Simple Usage: 
-------------

<?php 

$a123z 			= new a123z();   
$a123z->total 	= 500;			// the number of records
$output         = $a123z->output;

echo $output;

?>


Advanced Usage:
---------------

<?php 

$a123z 			 = new a123z();  
 
$a123z->total 	 = 500;			  // the number of records.
$a123z->max      = 20;			  // max results to be displayed per page.
$a123z->url		 = "index.php?foo=foo";  // URL to be the target of the links.
$a123z->page     = $_GET['page']; // variable represents the current page.

$a123z->template = array(  // menu template
						  'First'  		=> '<a href=[URL]&page=1>&lt;&lt; First</a>&nbsp;&nbsp;', 		            // First page link
						  'Previous'	=> '<a href=[URL]&page=[PAGE_P]>&lt; Previous</a>&nbsp;&nbsp;&nbsp;',	    // Previous page link
						  'Page'		=> '<a href=[URL]&page=[PAGE]>[PAGE]</a>&nbsp;',				            // Pages numbers link
						  'CurPage'		=> '<strong>[CurPAGE]</strong>&nbsp;',						             	// Current Page Link
						  'Next'		=> '&nbsp;&nbsp;&nbsp;<a href=[URL]&page=[PAGE_N]>Next &gt;</a>',			// Next page link
						  'Last'		=> '&nbsp;&nbsp;<a href=[URL]&page=[PAGE_L]>Last &gt;&gt;</a>',		        // Last page link
						  );
						 
$output          = $a123z->output;

echo $output;

?>

****************************************************************/


class a123z
{
var $url;   	// Navigation URL (i.e  : index.php?).
var $max;   	// Max items to be displayed per page.
var $total;		// Total Items count (i.e mysql table records count).
var $page; 		// Current Page number.

var $template	= array();  // Template Variables

	function a123z()
	{
	$this->url   		= $_SERVER['PHP_SELF']."?";  									 // Navigation URL (i.e  : index.php?).
	$this->max   		= 30;        												 // Max items to be displayed per page.
	$this->total 		= 0;			 											 // Total Items count (i.e mysql table records count).
	$this->page  		= ($_GET['page'] ? $_GET['page'] : 1);			 			 // Current Page number.
	
	$this->template	= array(
						  'First'  		=> '<a href=[URL]&page=1>&lt;&lt; First</a>&nbsp;&nbsp;', 		                // First page link
						  'Previous'	=> '<a href=[URL]&page=[PAGE_P]>&lt; Previous</a>&nbsp;&nbsp;&nbsp;',	// Previous page link
						  'Page'		=> '<a href=[URL]&page=[PAGE]>[PAGE]</a>&nbsp;',				            // Pages numbers link
						  'CurPage'		=> '<strong>[CurPAGE]</strong>&nbsp;',						             	// Current Page Link
						  'Next'		=> '&nbsp;&nbsp;&nbsp;<a href=[URL]&page=[PAGE_N]>Next &gt;</a>',			// Next page link
						  'Last'		=> '&nbsp;&nbsp;<a href=[URL]&page=[PAGE_L]>Last &gt;&gt;</a>',		        // Last page link
						  );
	}
	
	function output()
	{
	$output = '';
	$pages  = ceil($this->total/$this->max);
	$PrvValue = $this->page - 1;
	if($this->page > 1)
	{ 	
		$output  = $this->template['First'];
		$output .= $this->template['Previous'];
		$output  = str_replace('[PAGE_P]',$PrvValue,$output);
	}
	
	for($i=1;$i <= $pages;$i++)
	{
	
		if($i != $this->page)
		{
			$output .= $this->template['Page'];
			$output  = str_replace('[PAGE]',$i,$output);
			$next    = 1;
		}else{
			$output .= $this->template['CurPage'];
			$output  = str_replace('[CurPAGE]',$i,$output);
			$next    = 0;
		}
	}
	
	$NxtValue = $this->page + 1;
	if($next == 1)
	{
	 	$output .= $this->template['Next'];
		$output .= $this->template['Last'];
		$output  = str_replace('[PAGE_N]',$NxtValue,$output);
		$output  = str_replace('[PAGE_L]',$i-1,$output);
	}
	$output  = str_replace('[URL]',$this->url,$output);
	return $output;
	}
}

?>
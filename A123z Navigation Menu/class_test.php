<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>A123Z</title>
</head>

<body style="font-family:Geneva, Arial, Helvetica, sans-serif">
<p>Simple Usage: <br />
  -------------  </p>
<p>
  <textarea style="font-family:Geneva, Arial, Helvetica, sans-serif; color:#666666; font-size:10px" name="textarea" cols="80" rows="10" wrap="off">&lt;?php 
require_once("a123z.class.php");
$a123z&nbsp;               =&nbsp;new&nbsp;a123z();&nbsp;&nbsp;&nbsp;
$a123z->total&nbsp;     =&nbsp;100;               //&nbsp;the&nbsp;number&nbsp;of&nbsp;records
$output&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;=&nbsp;$a123z->output();

echo&nbsp;$output;

?&gt;
  </textarea>
  </p>
<p><br />
  <?php 
require_once("a123z.class.php");
$a123z 			= new a123z();   
$a123z->total 	= 100;			// the number of records
$output         = $a123z->output();

echo $output;

?>
  <br />
  <br />
  Advanced Usage:<br />
---------------</p>
<p>
  <textarea style="font-family:Geneva, Arial, Helvetica, sans-serif; color:#666666; font-size:10px"  name="textarea" cols="200" rows="10" wrap="off">&lt;?php 
require_once("a123z.class.php");
$a123z 			 = new a123z();  
 
$a123z->total 	 = 100;			  // the number of records.
$a123z->max      = 10;			  // max results to be displayed per page.
$a123z->url		 = "class_test.php?foo&foo2";  // URL to be the target of the links.
$a123z->page     = $_GET['page']; // variable represents the current page.

$a123z->template = array(  // menu template
						  'First'  		=> '<a href=[URL]&page=1>&lt;&lt; First</a>&nbsp;&nbsp;', 		            // First page link
						  'Previous'	=> '<a href=[URL]&page=[PAGE_P]>&lt; Previous</a>&nbsp;&nbsp;&nbsp;',	    // Previous page link
						  'Page'		=> '<a href=[URL]&page=[PAGE]>[PAGE]</a>&nbsp;',				            // Pages numbers link
						  'CurPage'		=> '<strong>[CurPAGE]</strong>&nbsp;',						             	// Current Page Link
						  'Next'		=> '&nbsp;&nbsp;&nbsp;<a href=[URL]&page=[PAGE_N]>Next &gt;</a>',			// Next page link
						  'Last'		=> '&nbsp;&nbsp;<a href=[URL]&page=[PAGE_L]>Last &gt;&gt;</a>',		        // Last page link
						  );
						 
$output          = $a123z->output();

echo $output;

?&gt;
  </textarea>
</p>
<p>
  <?php 
require_once("a123z.class.php");
$a123z 			 = new a123z();  
 
$a123z->total 	 = 100;			  // the number of records.
$a123z->max      = 10;			  // max results to be displayed per page.
$a123z->url		 = "class_test.php?foo&foo2";  // URL to be the target of the links.
$a123z->page     = $_GET['page']; // variable represents the current page.

$a123z->template = array(  // menu template
						  'First'  		=> '<a href=[URL]&page=1>&lt;&lt; First</a>&nbsp;&nbsp;', 		            // First page link
						  'Previous'	=> '<a href=[URL]&page=[PAGE_P]>&lt; Previous</a>&nbsp;&nbsp;&nbsp;',	    // Previous page link
						  'Page'		=> '<a href=[URL]&page=[PAGE]>[PAGE]</a>&nbsp;',				            // Pages numbers link
						  'CurPage'		=> '<strong>[CurPAGE]</strong>&nbsp;',						             	// Current Page Link
						  'Next'		=> '&nbsp;&nbsp;&nbsp;<a href=[URL]&page=[PAGE_N]>Next &gt;</a>',			// Next page link
						  'Last'		=> '&nbsp;&nbsp;<a href=[URL]&page=[PAGE_L]>Last &gt;&gt;</a>',		        // Last page link
						  );
						 
$output          = $a123z->output();

echo $output;

?>
</p>
</body>
</html>

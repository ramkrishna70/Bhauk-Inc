<?
require_once ('htaccess.class.php');

global $_GET;

$vdir = ''; // current addres on your host (can be $_SERVER['REQUEST_URI'])

$htaccess = new HTAccess ($_GET);

$htaccess->setLine ('RewriteBase /');
$htaccess->setLine ('RewriteRule ^(test1)[/]?$                    ?action=$1                               [L]');
$htaccess->setLine ('RewriteRule ^(test1/2)[/]?$                  ?action=$1&page=2                        [L]');


//with url == http://HOST/
$htaccess->execute ($vdir);
xmp ($_GET, $vdir);

$vdir = 'test1';	//with url == http://HOST/test1/
$htaccess->execute ($vdir);
xmp ($_GET, $vdir);

$vdir = 'test1/2';	//with url == http://HOST/test1/2/
$htaccess->execute ($vdir);
xmp ($_GET, $vdir);


function xmp (&$a, $label = null)
{
	$nl = "\r\n";
	if ($label) print "{$nl}<hr /><b>{$label}:</b>{$nl}";
	print "{$nl}<xmp>{$nl}";
	print_r ($a);
	print "{$nl}</xmp>{$nl}";
}

?>
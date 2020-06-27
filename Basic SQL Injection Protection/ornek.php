<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?

// Copyright 2011-~ Muammer TURKMEN
include_once("sqlkoruma.php");
$deneme1=new sqlinj;
$deneme1->ekleme("into");    // add custom words to list.

echo $deneme1->basla("get","veri")."<br><br>"; // only $_REQUEST["veri"] protect
echo stripslashes($deneme1->basla("get","veri")."<br><br>"); // stripslashes cleaning \ chars

echo $deneme1->basla("'''' select * from urunler where, insert into --update urunler")."<br><br>";  // clear custom text.
echo stripslashes($deneme1->basla("'''' \" \" select * from urunler where, insert into --update urunler"));
// AIO
$deneme1->basla("aio","all"); // aio -> $_REQUEST,$_GET,$_POST protect all types --- all -> all variables
echo "<br><br>".$_GET["veri"]."===".stripslashes($_GET["veri"])."<br><br>";
echo "<br><br>".$_GET["veri2"]."===".stripslashes($_GET["veri2"])."<br><br>";
print_r($_GET)."<br>";
print_r($_REQUEST);

?>
</body>
</html>
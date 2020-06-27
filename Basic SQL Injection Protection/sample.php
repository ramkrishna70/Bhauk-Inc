<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?

// Copyright 2020 Ram Krishna
include_once("sqlprotection.php");
$letithappen1=new sqlinj;
$letithappen1->adding("into");    // add custom words to list.

echo $letithappen1->Start("get","data")."<br><br>"; // only $_REQUEST["data"] protect
echo stripslashes($letithappen1->Start("get","data")."<br><br>"); // stripslashes cleaning \ chars

echo $letithappen1->Start("'''' select * from products where, insert into --update products")."<br><br>";  // clear custom text.
echo stripslashes($letithappen1->Start("'''' \" \" select * from products where, insert into --update products"));
// AIO
$letithappen1->Start("aio","all"); // aio -> $_REQUEST,$_GET,$_POST protect all types --- all -> all variables
echo "<br><br>".$_GET["data"]."===".stripslashes($_GET["data"])."<br><br>";
echo "<br><br>".$_GET["data2"]."===".stripslashes($_GET["data2"])."<br><br>";
print_r($_GET)."<br>";
print_r($_REQUEST);

?>
</body>
</html>
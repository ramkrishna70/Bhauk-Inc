<?php
  /* if not logged in then back to login page */
  session_start();
  if(!isset($_SESSION['is_successful_login']) || $_SESSION['is_successful_login'] == false){ 
    header ('location: index.php'); exit;
  }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ajax Login Module version 1.0 - Welcome</title>
</head>

<body>
 <div style="width:500px; margin:auto; text-align:center"> Welcome -  Success Login</div>
</body>
</html>

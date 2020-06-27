<html>
<head><title>Welcome to PHP Chatting</title></head>
<body bgcolor='#002266'>
<br>
<?php
     require "./chat.php";
     $_SESSION['chat'] = new chat;
     $_SESSION['chat']->setup();
?>
<html>

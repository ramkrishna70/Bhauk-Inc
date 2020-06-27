<?php
include("facebook.class.php");

$obj = new FacebookChat("lurukee@gmail.com", "mypassword");
$obj->login();
print_r($obj->buddylist());
$obj->sendmsg("Hey jhonny, how are u?", "my_friend_id");
?>
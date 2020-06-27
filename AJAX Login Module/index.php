<?php
/**
 * Ajax Login Module v1.0
 *
 * Ajax Login Module is a simple AJAX login page that is very easy to 
 * plug into your existing php application with no need for further configuration and coding.
 *
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2009, Christopher M. Natan
 * @link          http://phpstring.co.cc/phpclasses/modules/ajax-login-module/
 * @version       $Revision$
 * @modifiedby    $LastChangedBy$
 * @lastmodified  $Date$
 *
 */
   include('ajaxLoginModule.class.php');
   $ajaxLoginModule = new ajaxLoginModule;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ajax Login Module v1.0</title>
<link href="files/login.css" rel="stylesheet" type="text/css" />
<?php echo $ajaxLoginModule->initJquery();?>
</head>
<body>
<div id="container" style="display:none;">
  <div class="title">Ajax Login Module v1.0</div>
  <form action="" method="post" class="ajax_form">
    <ul>
      <li class="label"> Username</li>
      <li class="field">
        <input name="username" type="text" class="text" />
      </li>
      <li class="label"> Password</li>
      <li class="field">
        <input name="password" type="password" class="text"/>
      </li>
      <li class="label"> </li>
      <li class="field"> <img src="files/isubmit.jpg" class="submit" onclick="$('.<?php echo AJAX_FORM_ELEMENT?>').submit();"/>
        <input name="submit" type="submit" style="display:none" />
      </li>
      <li class="invalid_message">
        <div class="ajax_notify" style="display:none; clear:both"> 
          Error : Invalid username or password. Please try again.
          <!--don't delete this div class="ajax_notify"-->
        </div>
      </li>
      <li class="label status"> 
        <span class="ajax_wait">
        <!--don't delete this span class="ajax_wait"-->
        </span> </li>
    </ul>
    <div class="ajax_target">
      <!--don't delete this div class="ajax_target" -->
    </div>
  </form>
  <?php 
   echo $ajaxLoginModule->initLogin();
 ?>
  <p class="default"> Default username: admin / password: admin</p>
</div>
</body>
</html>

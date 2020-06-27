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
 


include('files/db.php');
class ajaxLoginModule  {
  private $timeout         = null;
  private $target_element  = null;
  private $wait_text       = null;
  private $form_element    = null;
  private $wait_element    = null;
  private $notify_element  = null;
   
  function __construct() {
     include ('config.php'); 
	 $msql  = new Db;
	 $msql->connect();
	 $this->is_login();
  } 
  function get_config() {
	 $this->set_ajax_config();
  } 
  function set_ajax_config() {
     $this->timeout         = AJAX_TIMEOUT;
     $this->target_element  = AJAX_TARGET_ELEMENT;
     $this->wait_text       = AJAX_WAIT_TEXT;
	 $this->wait_element    = AJAX_WAIT_ELEMENT;
     $this->notify_element  = AJAX_NOTIFY_ELEMENT;
     $this->form_element    = AJAX_FORM_ELEMENT;
  }
  function initLogin($arg = array()) {
	 $this->get_config();
	 $this->login_script();   	 
  }
  function initJquery() { 
	 return "<script type='text/javascript' src='files/jquery-1.3.2.min.js'></script>";
  }
  function login_script() { 
	 include ('files/login_script.php');
  }
  function is_login() {
      if(isset($_POST['username']))  {
	     $username   = $_POST['username'];
		 $password   = $_POST['password'];
		 $strSQL = "SELECT * FROM ".USERS_TABLE_NAME."
				    WHERE username ='$username' AND password = '$password'
				   ";
        $result  = mysql_query ($strSQL); 
		$row     = mysql_fetch_row($result);
		$exist   = count($row);
		if($exist >=2) { $this->jscript_location();  } 
		else { $this->notify_show();}
		exit;		
	  }   
  }
  function notify_show() {
    echo "<script>$('.".AJAX_NOTIFY_ELEMENT."').fadeIn();</script>";
  }
  function jscript_location() {
    $this->set_session();
    echo "<script> $('#container').fadeOut();window.location.href='".SUCCESS_LOGIN_GOTO."'</script>";
  }
  function set_session() {
      session_start();
	  $_SESSION['is_successful_login'] = true;
  }  	 
	  
}  
?>  
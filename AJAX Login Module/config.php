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
 
 /* 
  * Main Configuration 
  * Im sure you will edit this section.
 */
  error_reporting(0);
  define('MYSQL_HOSTNAME',  'localhost');  /* hostname */
  define('MYSQL_USERNAME',  'root');       /* username */
  define('MYSQL_PASSWORD',  'cmnworks');   /* password */
  define('MYSQL_DATABASE',  'phpclasses'); /* database */
  /* Login successful the redirect to */
  define('SUCCESS_LOGIN_GOTO'   ,'welcome.php');
  
/* if the USERS_TABLE_NAME doesn't exist in the DB then this module  will automatically create this TABLE  */
  define('USERS_TABLE_NAME','alm_users');
  
  
  
  
  
  
  
  
  
  
  
  /* Advance Configuration - no need to edit this section */
  define('AJAX_TIMEOUT',        '10000000');
  define('AJAX_TARGET_ELEMENT', 'ajax_target');
  define('AJAX_WAIT_TEXT',      'Please wait...');
  define('AJAX_FORM_ELEMENT',   'ajax_form');
  define('AJAX_WAIT_ELEMENT',   'ajax_wait');
  define('AJAX_NOTIFY_ELEMENT', 'ajax_notify');
  
  
  
  
           

?>
<?php

/*
     This class uses the following functions.

# chat() : Constructor
# roomsList() : This functions lists all the availabe rooms names
# changeRoom() : This function places the users from one room to another room
# createRoom() : This function lets the users creates their own room
# login() : This function lets the user to log into the chat
# logout() : This function lets the user to log out of the chat
# table_begin() : This begins the html table
# table_end() : This ends the html table
# showLogin() : This functions is for displaying the login page
# showUsers() : This function is for showing all the chatters in the room
# function chatMsg() : This function displays the text input for sending the messages
# chatDisplay() : This function displayes all the messages posted by the users
# setup() : This function calls the necessary functions automatically when an action is performed

*/

     define("ROOMS_DB", "test.rooms");
     define("CHAT_DB", "test.chat");
     class chat
     {
             var $dbLink;
             var $user;
             var $room_name;
             var $msg;
             function chat()
             {
                    $this->dbLink = mysql_connect() or die("Try again latter");
             }
             function roomsList($loginFlag=TRUE, $msg=NULL)
             {
                  $room_name = array();
                  $this->dbLink = mysql_connect() or die("Try again latter");
                  $quer = sprintf("select * from %s where status=0", ROOMS_DB);
                  $res = mysql_query($quer, $this->dbLink);
                  if(mysql_num_rows($res)) {
                      while($row = mysql_fetch_array($res)) {
                          $rooms[] = $row['room_name'];
                      }
                  }
                  if($loginFlag!=TRUE) {
                      if(isset($_POST['logout'])) {
                            $this->setup();
                      } else if(isset($_POST['change_room'])) {
                            $this->changeRoom($this->room_name, $_POST['change_room_name'], 
                                            $this->user, session_id());
                      } else if(isset($_POST['create_room'])) {
                            $this->createRoom($_POST['new_room'], $this->user, $_SERVER['REMOTE_ADDR'], 0);
                      }
                      echo"<form name='login' action=\"".$_SERVER['PHP_SELF']."\" method='POST' target='_top'>";
                      echo"<input type='hidden' name='".session_name()."' value='".urlencode(session_id())."'>";
                      echo"<table>"; 
                      echo"<tr>";
                      echo"<td><font color='#ffffff'>Change To</font>&nbsp;";
                      echo"<select name='change_room_name'>";
                           for($i=0; $i<count($rooms); $i++)
                                echo"<option value='".$rooms[$i]."'>".$rooms[$i]."</option>";
                      echo"</select>&nbsp;";
                      echo"<input type='submit' name='change_room' 
                               value='Change Room'></td>";
                      echo"<td><font color='#ffffff'>Create Room&nbsp;</font>";
                      echo"<input type='text' name='new_room'>&nbsp;";
                      echo"<input type='submit' name='create_room' value='Create Room'></td>";
                      echo"<td><input type='submit' name='logout' value='Logout'></td>";
                      echo"</tr>";
                      echo"</table>";
                      echo"</form>";
                  } else
                        return $rooms;
 
             }
             function changeRoom($old_room, $new_room, $user, $sesId)
             { 
                  $quer = sprintf("update %s set room_name='%s' where sesId='%s' and 
                          user ='%s'", CHAT_DB, $new_room, session_id(), $user);
                  $res = mysql_query($quer, $this->dbLink);
                  $quer = sprintf("update %s set no_of_users=no_of_users-1 where 
                          room_name='%s'", ROOMS_DB, $old_room);
                  $res = mysql_query($quer, $this->dbLink);
                  $quer = sprintf("update %s set no_of_users=no_of_users+1 where 
                          room_name='%s'", ROOMS_DB, $new_room);
                  $res = mysql_query($quer, $this->dbLink);
                  $this->room_name = $new_room;
                  header("Location:chatMain.php", replace);
                  exit(0);
             }
             function createRoom($room_name, $user, $ip, $status=0)
             {
                  $quer = sprintf("select * from %s where room_name='%s'", ROOMS_DB, $room_name);
                  $res = mysql_query($quer, $this->dbLink);
                  if(!mysql_num_rows($res)) {  
                        $cols="(__id, room_name, creator, no_of_users, ip, status)";
                        $quer = sprintf("insert into %s %s values('', '%s', '%s', 0, '%s', '%s') ", 
                                 ROOMS_DB, $cols, $room_name, $user, $ip, $status);
                        $res = mysql_query($quer, $this->dbLink);
                        $quer = sprintf("update %s set no_of_users=no_of_users+1 where 
                                    room_name='%s'", ROOMS_DB, $room_name);
                        $res = mysql_query($quer, $this->dbLink);
                        $this->room_name = $room_name;
                        $quer = sprintf("update %s set room_name='%s' where sesId='%s' and 
                                  user ='%s'", CHAT_DB, $room_name, session_id(), $user);
                        $res = mysql_query($quer, $this->dbLink);
                        if(!file_exists("/tmp/".$this->room_name)) {
                             exec("touch /tmp/".$this->room_name);
                             exec("chmod 666 /tmp/".$this->room_name);
                             $fp=fopen("/tmp/".$this->room_name, "w");
                             fputs($fp, "<h4><font color='green'>You are in ".$this->room_name." room</font></h4>");
                             fclose($fp);
                        }
                  } else {
                         $_SESSION['err'] = " This room already exists! ";
                  }
                  header("Location:chatMain.php", replace);
                  exit(0);
             }
             function login($user, $room_name, $ip, $sesId)
             {
                  $chquer = sprintf("select * from %s where user='%s' ", CHAT_DB, $user);
                  $res = mysql_query($chquer, $this->dbLink);
                  $alowLogin=FALSE;
                  if(mysql_num_rows($res)) {
                       $row = mysql_fetch_array($res);
                       $quer = sprintf("select (unix_timestamp()-unix_timestamp('%s'))/3600 as Diff", $row['__login']);
                       $res = mysql_query($quer, $this->dbLink);
                       $difRow = mysql_fetch_array($res);
                       if($difRow['dayDiff'] >= 8) {
                            $quer = sprintf("delete from %s where user='%s'", CHAT_DB, $user);
                            $res = mysql_query($quer, $this->dbLink);
                            $alowLogin=TRUE;
                       }
                  }
                  if(!mysql_num_rows($res) || $alowLogin==TRUE) {
                       $cols = "(__id, sesId, user, room_name, __login, ip)";
                       $quer = sprintf("insert into %s %s values('', '%s', '%s', '%s', now(), '%s') ", 
                               CHAT_DB, $cols, $sesId, $user, $room_name, $ip);
                       $res = mysql_query($quer, $this->dbLink);
                       $quer = sprintf("update %s set no_of_users=no_of_user+1 where room_name='%s'",                                         ROOMS_DB, $room_name);
                       $res = mysql_query($quer, $this->dbLink);
                       $this->room_name = $room_name;
                       $this->user = $user;
                       return true;
                  } else {
                       return false;
                  }
             }
             function logout()
             { 
                  $quer = sprintf("delete from %s where user='%s' and room_name='%s'", 
                          CHAT_DB, $this->user, $this->room_name);
                  $res = mysql_query($quer, $this->dbLink);
                  $quer = sprintf("update %s set no_of_users=no_of_user-1 where room_name='%s'",                                         ROOMS_DB, $this->room_name);
                  $res = mysql_query($quer, $this->dbLink);
             }
             function table_begin($head=NULL, $colspan=2, $width="60%")
             {
                  echo"<table cellpadding=4 cellspacing=0 border=0 align='center' width='".$width."'>";
                  echo"<tr><td colspan='".$colspan."' align='center'>";
                  echo"<table cellspacing=2 cellpadding=2 border=0>"; 
                  echo"<tr><td colspan='2' align='center' bgcolor='#003366'><font color='#ffffff'><b>".$head."</b></font></td></tr>";
                  echo"<tr><td colspan='2' align='center'>&nbsp;</td></tr>";
             }
             function table_end()
             { 
                  echo"</table>";
                  echo"</td></tr>";
                  echo"</table>";
             }  
             function showLogin($msg=NULL)
             {
                  echo"<form name='login' action=\"".$_SERVER['PHP_SELF']."\" method='POST'>";
                  echo"<input type='hidden' name='".session_name()."' value='".urlencode(session_id())."'>";
                  $this->table_begin("Welcome To PHP Chat", 2);
                  if(!empty($msg)) {
                       echo"<tr><td colspan=2><font color='red'>".$msg."</font></td></tr>";
                  }
                  echo"<tr>";
                  echo"<td><font color='#ffffff'>Enter user name</font></td>";
                  echo"<td><input type='text' name='user'>";
                  echo"</tr>";
                  echo"<tr>";
                  echo"<td><font color='#ffffff'>Select a room</font></td>";
                  echo"<td>";
                  $rooms = $this->roomsList(TRUE);
                  echo"<select name='room_name'>";
                       for($i=0; $i<count($rooms); $i++)
                            echo"<option value='".$rooms[$i]."'>".$rooms[$i]."</option>";
                  echo"</select>";
                  echo"</td>";
                  echo"</tr>";
                  echo"<tr>";
                  echo"<td colspan=2 align='center'><input type='submit' name='login' 
                           value='Login'></td>";
                  echo"</tr>";
                  $this->table_end();
                  echo"</form>";
             }
             function showUsers($room_name=NULL)
             {
                  $this->dbLink = mysql_connect() or die("Try again latter");
                  $quer= sprintf("select * from %s where room_name='%s'", CHAT_DB, $this->room_name);
                  $res = mysql_query($quer, $this->dbLink);
                  if(mysql_num_rows($res)) {
                      echo"<table cellpadding=1 cellspacing=1 border=0 width='100%'>";
                      echo"<tr><td>";
                      echo"<table cellpadding=1 cellspacing=1 border=1 width='100%'>";
                      while($row = mysql_fetch_array($res)) 
                          echo"<tr><td><font color='#ffffff'><b>".$row['user']."</b></font></td></tr>";
                      echo"</table>"; 
                      echo"</td></tr></table>";
                  }
             }
             function chatMsg($msg=NULL, $room_name=NULL)
             {  
                  if(!empty($_POST['msg'])) {
                       $fp = fopen("/tmp/".$this->room_name, "a");
                       $msg = $this->user." > ".$_POST['msg']."\n";
                       fputs($fp, $msg);
                  }
                  echo"<form name='msgFrm' action=\"".$_SERVER['PHP_SELF']."\" method='POST' onSubmit=\"if(this.msg.value=='') return false; else this.msg.focus()\">";
                  echo"<table>";
                  echo"<tr><td><input type='text' name='msg' size=90></td>";
                  echo"<td><input type='submit' name='sendmsg' value='Send'></td>";
                  echo"</table>";
                  echo"</form>";
             }
             function chatDisplay()
             {
                  $lines = file("/tmp/".$this->room_name);
                  $start = array_search(" <h4><font color='green'>Welcome To New User ".$_SESSION['user']->user_name."</font></h4>\n", $lines);
                  if(!$start)
                      $start=0;
                  echo"<table>";
                  if(!empty($_SESSION['err'])) {
                           echo"<tr><td colspan=4><font color='red'>".$_SESSION['err']."</font></td></tr>";
                           $_SESSION['err']=NULL;
                  }
                  for($i=$start; $i<count($lines); $i++) {
                      if(!empty($lines[$i])) {
                          echo"<tr><td><font color='#ffffff'>";
                          echo $lines[$i];
                          echo"</font></td></tr>";
                      }
                  }
                  echo"</table>";
             }
             function setup()
             {
                  
                  if(isset($_POST['logout'])) {
                        $_SESSION['chat']->logout(); 
                        unset($_SESSION['chat']);
                        session_unregister(session_id());
                        $_SESSION['chat']=NULL;  
                        header("Location:index.php", replace);
                        exit(0);
                  } else if(isset($_POST['login'])) {
                       $this = $_SESSION['chat'];
                       if($_SESSION['chat']->login($_POST['user'], $_POST['room_name'], 
                                   $_SERVER['REMOTE_ADDR'], session_id())) {
                            $_SESSION['chat']->room_name = $_POST['room_name'];
                            $_SESSION['user']->user_name = $_POST['user'];
                            $fp = fopen("/tmp/".$_SESSION['chat']->room_name, "a");
                            $msg = " <h4><font color='green'>Welcome To New User ".$_SESSION['user']->user_name."</font></h4>\n";
                            fputs($fp, $msg);
                            header("Location:chatMain.php", replace);
                            exit(0);
                       } else {
                            $msg = "Another user already using this handle. Choose another handle";
                            $this->showLogin($msg);
                       }
                  } else { 
                       if($room_name="MainRoom" && !file_exists("/tmp/".$this->room_name)) {
                            exec("touch /tmp/".$this->room_name);
                            exec("chmod 666 /tmp/".$this->room_name);
                            $fp=fopen("/tmp/".$this->room_name, "w");
                            fputs($fp, "<h4><font color='green'>You are in ".$this->room_name." room</font></h4>");
                            fclose($fp);
                       }
                       $_SESSION['chat']->showLogin();
                  }
             }
     }
     session_start();
?>

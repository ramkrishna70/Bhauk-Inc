<?PHP
// Ending Session info is important to clean up current active operations
// Also unfinished abandoned sessions will be removed from the system
	
  $rs = new mysql_recordset("conn","SELECT * FROM tbl_active_sessions") ;
  if ($rs->query())
    {
    while (!$rs->movenext())
      {
    // This is just one way to do it, normally performance will not be compromised as
    // long concurrent sessions don't grow too much... in this case a 2 pass process
    // deleting directly based on tbl_active_sessions start time (first from tbl_carrito and then from
    // tbl_active_sessions will attune for improved performance.  Used this approach as it is far
    // more clear to understand than the 'advanced' method
			
      $timeactual = time(); //current time to check session expiry
      if ($rs->value("start") < $timeactual-10800) //expiration = 3 hours
        {
        // DELETE FROM active_sessions and from shopping basket tables
       $comm = new mysql_command("conn","DELETE FROM tbl_basket WHERE sessionid = '" . $rs->value("sessionid") . "'") ;
       $comm->execute() ;
       $comm->sqlstring = "DELETE FROM tbl_active_sessions WHERE sessionid = '" . $rs->value("sessionid") . "'" ;
       $comm->execute() ;
       }	
     }
   $rs->clear_recordset() ;
   }
?>

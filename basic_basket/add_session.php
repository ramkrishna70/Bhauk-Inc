<?PHP
  $sql = "SELECT * FROM tbl_active_sessions WHERE sessionID = '" . $sessionID . "'" ;
  $rs = new mysql_recordset("conn",$sql) ;
  if ($rs->query()==0)
    {
    // No Session with same ID was found so we add a new session
    $segs = time() ; 
    $sql = "INSERT INTO tbl_active_sessions (sessionid,start) VALUES ('" . $sessionID . "'," . $segs . ")" ;
    $comm = new mysql_command("conn",$sql) ;
    $comm->execute() ;
    } 
  $rs->clear_recordset() ;
?>
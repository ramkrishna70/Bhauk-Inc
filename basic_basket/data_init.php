<?PHP // Database initialization...
  $conn = new mysql_conn("localhost","user","pass","database",true) ;
  $conn->logfile = $dirapp . "logs/logfile" ;
  $conn->init() ;
?>

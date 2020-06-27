<? require ("cookie.php") ; 			?>
<? require ("mysql_conn.php") ;			?>
<? require ("mysql_recordset.php") ;	        ?>
<? require ("mysql_command.php") ;		?>
<? require ("data_init.php") ; 			?>
<? require ("del_session.php") ;		?>
<? require ("add_session.php") ;		?>
<? require ("basket.php") ;			?>
<? $carrito = new basket("conn","productview.php3") ;  ?>
<? $carrito->change_qty($id,$qty) ; ?>
<? $conn->destroy() ;               ?>
<HTML>
<HEAD></HEAD>
<BODY onLoad = "javascript: location.replace('show.php')">
</BODY>
</HTML>
<? require ("cookie.php") ; 			?>
<? require ("mysql_conn.php") ;			?>
<? require ("mysql_recordset.php") ;            ?>
<? require ("mysql_command.php") ;	        ?>
<? require ("data_init.php") ; 			?>
<? require ("del_session.php") ;		?>
<? require ("add_session.php") ;		?>
<? require ("basket.php") ;			?>
<? $carrito = new basket("conn","showproduct.php3") ; ?>
<html><head></head>
<body>
<? $carrito->screen_dump() ;			?>
<? $conn->destroy() ; 				?>
</body>
</html>
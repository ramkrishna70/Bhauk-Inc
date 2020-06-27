<?PHP
// Base SHOPPING KART class

class basket {

  // Initialization required values
  VAR $sessionID ;  // autoinitialized from GLOBAL variable from a cookie
  VAR $view ;	    // script that generates product info pages
	
  // Private DATA vars
  VAR $total ;
  VAR $itemcount ;	
	
  // Protected DATA ACCES members
  VAR $connOBJ ;
  VAR $rsOBJ ;
  VAR $commOBJ ;
				
  // Private METHODS
	
  function init()
    {
    $this->sessionID = $GLOBALS["sessionID"] ;
    $this->update() ;
    }

  function calculate()
    {
    $this->total = 0 ;
    $this->itemcount = 0 ;
    while (!$this->rsOBJ->movenext())
      {
      $this->total+=($this->rsOBJ->value("price")*$this->rsOBJ->value("qty")) ;
      $this->itemcount += $this->rsOBJ->value("qty") ;
      }
    }
		
  // Public interface
	
  function basket($conn,$view)
    {
    $this->connOBJ = $conn ;
    $this->view = $view ;
    $this->init() ;
    }
	
  function insert($prodid,$qty)
    {
    $this->rsOBJ = new mysql_recordset($this->connOBJ,"SELECT * from tbl_basket WHERE sessionid='" . $this->sessionID . "' and prodid=" . $prodid );
    if ($this->rsOBJ->query())
      {
      // Product already on the basket
      $this->rsOBJ->movenext() ;
      $oldqty = $this->rsOBJ->value("qty") ;
      $qty+=$oldqty ;
      $this->commOBJ = new mysql_command($this->connOBJ,"update tbl_basket set qty=". $qty ." where sessionid='" . $this->sessionID . "' AND prodid=" . $prodid) ;
      $this->commOBJ->execute() ;			
      $this->rsOBJ->clear_recordset() ;
    } else {
      // new product, must INSERT
      $this->rsOBJ->sqlstring = "SELECT * from tbl_products WHERE id=" . $prodid ;
      if ($this->rsOBJ->query())
        {
	$this->rsOBJ->movenext() ;
	$sql = "INSERT INTO tbl_basket (sessionid,prodid,product,price,qty) VALUES ('".$this->sessionID."',".$prodid.",'".$this->rsOBJ->value("product")."',".$this->rsOBJ->value("price").",1)" ;
	$this->commOBJ = new mysql_command($this->connOBJ,$sql) ;
	$this->commOBJ->execute() ;
	$this->rsOBJ->clear_recordset() ;
	} 
      }
    $this->update() ;
    }
	
  function delete($prodid)
    {
    $this->commOBJ = new mysql_command($this->connOBJ,"DELETE from tbl_basket WHERE prodid=" . $prodid . " and sessionid='" . $this->sessionID . "'") ;
    $this->commOBJ->execute() ;
    $this->update() ;
    }
		
  function empty_basket()
    {
    $this->commOBJ = new mysql_command($this->connOBJ,"DELETE from tbl_basket WHERE sessionid='" . $this->sessionID . "'") ;
    $this->commOBJ->execute() ;
    $this->update() ;
    }
		
  function update()
    {
    $this->rsOBJ = new mysql_recordset($this->connOBJ,"SELECT * from tbl_basket WHERE sessionid='" . $this->sessionID . "'") ;
    if ($this->rsOBJ->query())
      {			
      $this->calculate() ;
      $this->rsOBJ->clear_recordset() ;
    } else {
      $this->total = 0 ;
      $this->itemcount = 0 ;
      }
    }
		
  function change_qty($prodid,$qty)
    {
    $this->rsOBJ = new mysql_recordset($this->connOBJ,"SELECT * from tbl_basket WHERE sessionid='" . $this->sessionID . "' and prodid=" . $prodid );
    if ($this->rsOBJ->query())
      {
      $this->rsOBJ->movenext() ;
      if ($qty==0)
        {
	$this->delete($prodid) ;
	$this->rsOBJ->clear_recordset() ;
      } else {
	$mod = $qty - $this->rsOBJ->value("qty") ;
	$this->rsOBJ->clear_recordset() ;
	$this->insert($prodid,$mod) ;
	}
      }
    }

  function screen_dump()
    {
    $this->rsOBJ = new mysql_recordset($this->connOBJ,"SELECT * FROM tbl_basket WHERE sessionid='" . $this->sessionID . "'") ;
    $this->rsOBJ->query() ;
    $count = 0 ;
    echo "<table border=1><tr><th>PRODUCT</th><th>ITEMS</th><th>DELETE?</th><th>PRICE/UNIT</th><th>TOTAL PRICE</th>" ;
    while (!$this->rsOBJ->movenext()) 
      {
      $count++ ;?>
      <tr>		  
      <td align="left" valign="top"><a href="<?echo $this->view;?>?id=<? $this->rsOBJ->field("prodid");?>"><?$this->rsOBJ->field("product");?></a></td>
      <td align="center" valign="top"><form name="mod<?echo $count;?>" method="POST" action="modify.php">
      <input type="hidden" name="id" value="<?$this->rsOBJ->field("prodid");?>">
      <input type="text" name="qty" value="<?$this->rsOBJ->field("qty");?>" size="5" onBlur="this.form.submit()">
      </form></td>
      <td align="center" width="30" valign="top"><form method="POST" name="del<?echo $count;?>" action="delete.php">
      <input type="hidden" name="id" value="<?$this->rsOBJ->field("prodid");?>">
      <input type="checkbox" onClick="this.form.submit()"></form></td>
      <td align="center" valign="top"><?$this->rsOBJ->field("price");?></td>		
      <td  valign="top" align="right"><?echo $this->rsOBJ->value("qty")*$this->rsOBJ->value("price");?></td></tr>
      <?}?>
      <tr><th colspan="4">BASKET TOTAL</th><td align="right"><?echo $this->total;?></td></tr></table>
      <?}
      }?>
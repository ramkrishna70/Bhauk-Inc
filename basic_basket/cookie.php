<?PHP
// Generation of SESSION ID (cookie based)
// Cookie will last for 3 hours, enough for any simple purchase
// This is just an example of how it can be done

  if (!$sessionID)
    {
    $sessionID = uniqid("") ;
    $expires = time() + 3*3600 ;
    setcookie("sessionID",$sessionID,$expires) ;
    }
?>
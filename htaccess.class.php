<?

/**
* Class for handling htaccess of Apache with better htaccess editing
* @author    Sven Wagener <sven.wagener@intertribe.de>
* @contributor Jan Matousek <seiffs@centrum.cz>
* @copyright Intertribe - Internetservices Germany
* @include      Funktion:_include_
*/ 

class htmodel{
  var $fHtaccess = ""; // path and filename for htaccess file
  var $fHtgroup  = ""; // path and filename for htgroup file
  var $fPasswd   = ""; // path and filename for passwd file
  var $fDelimiter = "##AUTOMATED - DO NOT EDIT##";
  
  var $authType="Basic"; // Default authentification type
  var $authName="Secured area"; // Default authentification name

    /**
    * constructor
    */
    function __construct(){
    }

    /**
    * Initialising class htaccess
    */
    function htaccess(){
    }

    /**
    * Sets the filename and path of .htaccess to work with
    * @param string    $filename    the name of htaccess file
    */
    function setFHtaccess($filename){
        $this->fHtaccess=$filename;
    }
    
    /**
    * Sets the filename and path of the htgroup file for the htaccess file
    * @param string    $filename    the name of htgroup file
    */
    function setFHtgroup($filename){
        $this->fHtgroup=$filename;
    }
    
    /**
    * Sets the filename and path of the password file for the htaccess file
    * @param string    $filename    the name of htgroup file
    */
    function setFHtpasswd($filename){
        $this->fPasswd=$filename;
    }

    /**
    * Adds a user to the password file
    * @param string $username     Username
    * @param string $password     Password for Username
    * @param string $group        Groupname for User (optional)
    * @return boolean $created         Returns true if user have been created otherwise false
    */
    function addUser($username,$password,$group=""){
        // checking if user already exists
        $file=@fopen($this->fPasswd,"r");
        $isAlready=false;
        while($line=@fgets($file,200)){
            $lineArr=explode(":",$line);
            if($username==$lineArr[0]){
                $isAlready=true;
             }
        }
        
        if($isAlready==false){
            $file=fopen($this->fPasswd,"a");            
	    $password=crypt($password);            
            $newLine=$username.":".$password."\n";

            fputs($file,$newLine);
            fclose($file);
            return true;
        }else{
            return false;
        }
    }

    /**
    * Adds a group to the htgroup file
    * @param string $groupname     Groupname
    */
    function addGroup($groupname){
        $file=fopen($this->fHtgroup,"a");
        fclose($file);
    }

    /**
    * Deletes a user in the password file
    * @param string $username     Username to delete
    * @return boolean $deleted    Returns true if user have been deleted otherwise false
    */
    function delUser($username){
        // Reading names from file
        $file=fopen($path.$this->fPasswd,"r");
        $i=0;
        while($line=fgets($file,200)){
            $lineArr=explode(":",$line);
            if($username!=$lineArr[0]){
                $newUserlist[$i][0]=$lineArr[0];
                $newUserlist[$i][1]=$lineArr[1];
                $i++;
            }else{
                $deleted=true;
            }
        }
        fclose($file);

        // Writing names back to file (without the user to delete)
        $file=fopen($path.$this->fPasswd,"w");
        for($i=0;$i<count($newUserlist);$i++){
            fputs($file,$newUserlist[$i][0].":".$newUserlist[$i][0]."\n");
        }
        fclose($file);
        
        if($deleted==true){
            return true;
        }else{
            return false;
        }
    }
    
    /**
    * Returns an array of all users in a password file
    * @return array $users All usernames of a password file in an array
    */
    function getUsers() {
    $file=fopen($this->fPasswd,"r");
    for($i=0;$line=fgets($file,200);$i++) {
        $lineArr=explode(":",$line);
        if($lineArr[0]!="") {
        $userlist[$i]['jmeno']=$lineArr[0];
        $userlist[$i]['id']=$lineArr[0];
        $userlist[$i]['heslo']=$lineArr[1];
        }
    }
    fclose($file);

    // sort users
    if(!empty($userlist)){
      array_multisort($userlist);      
      return $userlist;
    }
    }
    
    /**
    * Sets a password to the given username
    * @param string $username     The name of the User for changing password
    * @param string $password     New Password for the User
    * @return boolean $isSet      Returns true if password have been set
    */    
    function setPasswd($username,$new_password){
        // Reading names from file
        $newUserlist="";
        
        $file=fopen($this->fPasswd,"r");
        $x=0;
        for($i=0;$line=fgets($file,200);$i++){
            $lineArr=explode(":",$line);
            if($username!=$lineArr[0] && $lineArr[0]!="" && $lineArr[1]!=""){
                $newUserlist[$i][0]=$lineArr[0];
                $newUserlist[$i][1]=$lineArr[1];
                $x++;
            }else if($lineArr[0]!="" && $lineArr[1]!=""){
                $newUserlist[$i][0]=$lineArr[0];
                $newUserlist[$i][1]=crypt($new_password)."\n";
                $isSet=true;
                $x++;
            }
        }
        fclose($file);

        unlink($this->fPasswd);

        /// Writing names back to file (with new password)
        $file=fopen($this->fPasswd,"w");
        for($i=0;$i<count($newUserlist);$i++){
            $content=$newUserlist[$i][0].":".$newUserlist[$i][1];
            fputs($file,$content);
        }
        fclose($file);

        if($isSet==true){
            return true;
        }else{
            return false;
        }
    }

    /**
    * Sets the Authentification type for Login
    * @param string $authtype     Authentification type as string
    */
    function setAuthType($authtype){
        $this->authType=$authtype;
    }

    /**
    * Sets the Authentification Name (Name of the login area)
    * @param string $authname     Name of the login area
      */
    function setAuthName($authname){
        $this->authName=$authname;
    }

    /**
    * adds the automated lines only to the .htaccess file
    * @see setFhtaccess()
      */
    function enableLogin(){
      $file=fopen($this->fHtaccess,"r");
      $string = fread($file,filesize($this->fHtaccess));
      fclose($file);

      $file=fopen($this->fHtaccess,"w");
      if($file){
	 fputs($file,$string);
	 fputs($file,"\n".$this->fDelimiter."\n");
	 fputs($file,"AuthType        ".$this->authType."\n");
	 fputs($file,"AuthUserFile    ".$this->fPasswd."\n");
	 fputs($file,"AuthName        \"".$this->authName."\"\n");
	 fputs($file,"Require         valid-user\n");
	 fclose($file);
       }
    }

    /**
    * removes the automated lines only from the .htaccess file
    */
    function disableLogin(){
      $file=fopen($this->fHtaccess,"r");
      $string = fread($file,filesize($this->fHtaccess));
      fclose($file);
     
      $strarray = explode("\n",$string);
      foreach($strarray as $key => $line)
	if($line == $this->fDelimiter)
	  $breaker = $key;
      array_splice($strarray,$breaker);
      $string = '';
      foreach($strarray as $key => $line)
	$string .= $line."\n";
      $file1=fopen($this->fHtaccess,"w");
      fputs($file1,$string);
      fclose($file1);
    }

    /**
    * checks if login is enabled in the htaccess file
    * @return boolean $isSet      Returns true if login is enabled
    */
    function isLogin(){
      $file=fopen($this->fHtaccess,"r");
      $string = fread($file,filesize($this->fHtaccess));
      fclose($file);

      $strarray = explode("\n",$string);
      foreach($strarray as $key => $line)
	if($line == $this->fDelimiter)
	  $breaker = $key;
      if(isset($breaker))
	return true;
      return false;
    }
}
?>
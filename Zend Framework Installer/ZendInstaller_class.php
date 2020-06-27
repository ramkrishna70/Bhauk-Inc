<?php
/**
 * ZendInstaller. A class to setup and Start Up your Zend Framework applications
 *
 * @author Ruben Crespo Alvarez [rumailster@gmail.com] http://peachep.wordpress.com
 * except function copydirr by Anton Makarenko [makarenkoa@ukrpost.net]
 */
 
class ZendInstaller {

var $application_name;
var $library_directory;
var $adapter;
var $host;
var $username;
var $password;
var $dbname;


function ZendInstaller ($name, $library_dir, $adapt, $ho, $usern, $pass, $dbn)
    {

        $adapter = $adapt;
        $host = $ho;
        $username = $usern;
        $password = $pass;
        $dbname = $dbn;


    if ($library_dir == '')
    {
        echo 'ERROR: You must define the path to the in your Downloaded Zend Framework distribution.<br /> You can download it at <a href="http://framework.zend.com/download">framework.zend.com/download</a>.';

    } else {
        
        $library_directory = $library_dir;

            if ($name == '')
            {
            $application_name = 'Zend_Project';
                }else{
            $application_name = $name;
            }


    if (mkdir ($application_name, 0777)) {
    $this->Directory_Tree($application_name, $library_directory, $adapter, $host, $username, $password, $dbname);
    }else{
    echo 'ERROR: A project named '.$application_name.' already exists in this directory.';
    }
            
    }



}
    
    


    private function Directory_Tree ($application_path, $library, $adapter, $host, $username, $password, $dbname)
    {
    echo "INSTALLING <br />";
    //application directory
    mkdir ($application_path."/application", 0777);
    mkdir ($application_path."/application/controllers", 0777);
    mkdir ($application_path."/application/models", 0777);
    mkdir ($application_path."/application/views", 0777);
    mkdir ($application_path."/application/views/filters", 0777);
    mkdir ($application_path."/application/views/helpers", 0777);
    mkdir ($application_path."/application/views/scripts", 0777);

    //public directory
    mkdir ($application_path."/public", 0777);
    mkdir ($application_path."/public/images", 0777);
    mkdir ($application_path."/public/scripts", 0777);
    mkdir ($application_path."/public/styles", 0777);


    //copy libray directory to our application tree
    mkdir ($application_path."/library", 0777);

    $folder = $application_path."/library";

    // Copiamos todos los archivos a la nueva carpeta usando el archivo copydirr.inc.php
    $this->copydirr($library,$folder,0777,true);


/*Creamos los .htaccess en
application_folder*/

$text = "RewriteEngine on
RewriteRule .* index.php

php_flag magic_quotes_gpc off
php_flag register_globals off";

    $this->writeInFile($application_path."/.htaccess", $text);

    //application_folder/public/
    $text = "RewriteEngine off";
    $this->writeInFile($application_path."/public/.htaccess", $text);


    //application_folder/application/
    $text = "deny from all";
    $this->writeInFile($application_path."/application/.htaccess", $text);

    //application_folder/library/
    $this->writeInFile($application_path."/library/.htaccess", $text);


//Incluimos el indexBootStrap
$this->index_BootStrap($application_path);


//config db conexion
$this->config($application_path, $adapter, $host, $username, $password, $dbname);

$this->SettingUp_Index_Controller ($application_path);

$this->footer ($application_path);

$this->SettingUp_Styles ($application_path);

    echo "CONGRATULATIONS - Installation Complete !!<br />
    You can see a index controller example <a href='".$application_path."/'>here</a>";
    }


    private function index_BootStrap ($application_path){

/*Creamos el index bootStrap en
application_folder*/

$text = '<?php
//Mostramos todos los errores que presente la pagina
error_reporting (E_ALL|E_STRICT);
//Definimos la Zona Horaria
//date_default_timezone_set (\'Europe/London\');

//Inclumos los archivos de library y los modelos en el include path
set_include_path (\'.\' . PATH_SEPARATOR . \'./library\'
    . PATH_SEPARATOR . \'./application/models/\'
    . PATH_SEPARATOR . get_include_path());

//Inclumos loader.php para acceder a la clase Zend_loader
include \'Zend/Loader.php\';

Zend_Loader::loadClass(\'Zend_Controller_Front\');
Zend_Loader::loadClass(\'Zend_Config_Ini\');
Zend_Loader::loadClass(\'Zend_Registry\');
Zend_Loader::loadClass (\'Zend_Db\');
Zend_Loader::loadClass (\'Zend_Db_Table\');


//load configuration
$config = new Zend_Config_Ini (\'./application/config.ini\', \'general\');
$registry = Zend_Registry::getInstance();
$registry -> set (\'config\', $config);

//Setup database
$db = Zend_Db::factory($config->db->adapter,
$config->db->config->toArray());
Zend_Db_Table::setDefaultAdapter($db);


//setup controller
$frontController = Zend_Controller_Front::getInstance();
$frontController->throwExceptions(true);
$frontController->setControllerDirectory(\'./application/controllers\');

//run!!
$frontController->dispatch();';


$this->writeInFile($application_path."/index.php", $text);


    }



    private function config ($application_path, $adapter, $host, $username, $password, $dbname){
//application_folder/application/config.ini
    $text = "[general]
db.adapter = ".$adapter."
db.config.host = ".$host."
db.config.username = ".$username."
db.config.password = ".$password."
db.config.dbname = ".$dbname;

    $this->writeInFile($application_path."/application/config.ini", $text);
    }


    private function copydirr($fromDir,$toDir,$chmod=0777,$verbose=false){

// Check for some errors
$errors=array();
$messages=array();
if (!is_writable($toDir))
    $errors[]='target '.$toDir.' is not writable';
if (!is_dir($toDir))
    $errors[]='target '.$toDir.' is not a directory';
if (!is_dir($fromDir))
    $errors[]='source '.$fromDir.' is not a directory';
if (!empty($errors))
    {
    if ($verbose)
        foreach($errors as $err)
            echo '<strong>Error</strong>: '.$err.'<br />';
    return false;
    }

$exceptions=array('.','..');
//* Processing
$handle=opendir($fromDir);
while (false!==($item=readdir($handle)))
    if (!in_array($item,$exceptions))
        {
        //cleanup for trailing slashes in directories destinations
        $from=str_replace('//','/',$fromDir.'/'.$item);
        $to=str_replace('//','/',$toDir.'/'.$item);
        
        if (is_file($from))
            {
            if (@copy($from,$to))
                {
                chmod($to,$chmod);
                touch($to,filemtime($from)); // to track last modified time
                $messages[]='File copied from '.$from.' to '.$to;
                }
            else
                $errors[]='cannot copy file from '.$from.' to '.$to;
            }
        if (is_dir($from))
            {
            if (@mkdir($to))
                {
                chmod($to,$chmod);
                $messages[]='Directory created: '.$to;
                }
            else
                $errors[]='cannot create directory '.$to;
                $this->copydirr($from,$to,$chmod,$verbose);
            }
        }
closedir($handle);

//Output
if ($verbose)
    {
    foreach($errors as $err)
    {
        echo '<strong>Error</strong>: '.$err.'<br />';
        }
    
    
    /*
    foreach($messages as $msg)
    {
    echo $msg.'<br />';
     }
     */
     
     
    }

return true;
}





private function SettingUp_Index_Controller ($application_path) {

/*Index Controller*/

$controller_text = '<?php
class IndexController extends Zend_Controller_Action
{

function init()
{
$this->view->baseUrl = $this->_request->getBaseUrl();
}

	function indexAction ()
	{
	$this->view->title = "CONGRATULATIONS - Installation Complete";
	$this->view->introtext = "The Installation of your Zend application is Complete.";
	$this->view->text = "This is the view ('.$application_path.'/application/views/scripts/index/index.phtml) of Index Controller in '.$application_path.'/application/controllers/IndexController.php.";
	}
}
?>';

$this->writeInFile($application_path."/application/controllers/IndexController.php", $controller_text);


/*Setting Up the View*/

mkdir ($application_path."/application/views/scripts/index", 0777);

$view_text = '<html>
<head>
<title><?php echo $this->escape($this->title); ?></title>
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $this->baseUrl;?>/public/styles/site.css" />
</head>
<body>
<h1><?php echo $this->escape($this->title); ?></h1>
<p><?php echo $this->escape($this->introtext); ?></p>
<p><?php echo $this->escape($this->text); ?></p>

<?php echo $this->render("footer.phtml"); ?>

';

$this->writeInFile($application_path."/application/views/scripts/index/index.phtml", $view_text);

}


private function footer ($application_path)
{
$footer_text = "
<p>Zend Installer by Rub&eacute;n Crespo &Aacute;lvarez <a href='http://peachep.wordpress.com'>[peachep.wordpress.com]</a>.<br />For more information or if you find anything that is wrong, please let email me at <a href='mailto:rumailster@gmail.com'>rumailster@gmail.com</a>.</p>
</body>
</html>";
$this->writeInFile($application_path."/application/views/scripts/footer.phtml", $footer_text);
}


private function SettingUp_Styles ($application_path)
{
$css_text = "
body, html {
font-size:100%;
margin:0;
font-family:verdana, arial, helvetica, sans-serif;
color:#000;
background-color:#DFEEF0;
}

h1 {
font-size:1.4em;
color:#637789;
}

/*and ...more styles*/
";

$this->writeInFile($application_path."/public/styles/site.css", $css_text);
}

private function writeInFile ($file, $text)
{

    if ($fp = fopen ($file, "a")){  
        fwrite ($fp, $text);
    }
    
    
    chmod($file, 0777);

    fclose ($file);
}

}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
                      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<head>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
<?php 
require 'DefaultBody.php';
require 'enviroment/enviroment.php';

//custom body elements classes
require 'nose.php';

//custom enviroment elements classes
require 'enviroment/smell.php';

$enviroment = new Enviroment();

$human = new DefaultBody($enviroment);

echo "<br /><br /><i>Before head move: </i> ";
print_r ($human->Head->getPosition());

echo "<br /><br /><i>move head up: </i>";
$human->Head->moveVertical(1);
print_r ($human->Head->getPosition());

echo "<br /><br /><i>rotate head right: </i>";
$human->Head->moveHorizontal(1);
print_r ($human->Head->getPosition());


//emit sound
$envSound = new Sound($enviroment);
echo "<br /><br /><i>Emit sound: echoooo</i>";
$envSound->emitSound("echoooo", 5);
echo "<br /><br /><i>Emit sound: WRRRRR </i>";
$envSound->emitSound("WRRRRR", 7);

//emit Light
$envLight = new Light($enviroment);
echo "<br /><br /><i>Emit light: 10 </i>";
$envLight->emitLight(10);

echo "<br /><br /><i>Emit light (on closed eyes): 10 </i>";
$human->Eye->close();
$envLight->emitLight(10);

//say something
echo "<br /><br /><i>MOUTH: say Hello world</i>";
$human->Mouth->say('Hello world', 7);


echo "<br /><br /><i>Add custom body element NOSE and costom enviroment element SMELL and then emit smell</i>";
//add eye to default body
$nose = new Nose();
$human->addBodyElement($nose);

//create custom enviroment element and emit rose smell
$envSmell = new Smell($enviroment);
$envSmell->emitSmell('Rose', 3);

?>

</body>
</html>

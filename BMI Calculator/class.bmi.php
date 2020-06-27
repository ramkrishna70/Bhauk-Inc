<?
/*********************************
ThumbnailCreator
Programmed by : Daniel Thul
E-mail        : Daniel.Thul@gmx.de
*********************************/
/*SoMe InFoS:
I would like, if you write me an e-mail if you use this class, so that I can see how it uses.
If you copy this script, please take over the notes, so that everybody can see the author of this class (Daniel Thul).
Thank you. :-)*/
class bmi{

var $height;
var $schwer;

function calculate_bmi(){

if (isset($this->height) && isset($this->weight) && is_numeric($this->height) && is_numeric($this->weight)){
$height = $this->height;
$weight = $this->weight;
$height = str_replace(",", ".", $height);
$weight = str_replace(",", ".", $weight);
$nenner = $height*$height;
$bmi = $weight/$nenner;
$bmi = round($bmi, "0");
echo '<center>Ihr BMI-Wert beträgt: * Your BMI: '.$bmi.'<br><br>';
echo '<table align="center"><tr><td>';
if ($bmi < 18){
echo '<font color="red">';
$ende = '</font>';
}
echo 'weniger als 18 * less than 18'.$ende.'</td><td>';
if ($bmi < 18){
echo '<font color="red">';
$ende = '</font>';
}
echo 'Untergewicht * Underweight'.$ende.'</td></tr><tr><td>';
if ($bmi < 26 && $bmi > 17){
echo '<font color="red">';
$ende = '</font>';
}
echo '18-25'.$ende.'</td><td>';
if ($bmi < 26 && $bmi > 17){
echo '<font color="red">';
$ende = '</font>';
}
echo 'Normalgewicht * Normalweight'.$ende.'</td></tr><tr><td>';
if ($bmi < 31 && $bmi > 25){
echo '<font color="red">';
$ende = '</font>';
}
echo '26-30'.$ende.'</td><td>';
if ($bmi < 31 && $bmi > 25){
echo '<font color="red">';
$ende = '</font>';
}
echo 'Übergewicht * Overweight'.$ende.'</td></tr><tr><td>';
if ($bmi > 30){
echo '<font color="red">';
$ende = '</font>';
}
echo 'over 30'.$ende.'</td><td>';
if ($bmi > 30){
echo '<font color="red">';
$ende = ' !</font>';
}
echo 'Fettleibigkeit; Gefahr für die Gesundheit * Heavy Overweight; Danger for your health'.$ende.'</td></tr></table>';
}
else{
exit();
}
}// End of function
}//End of class
?>
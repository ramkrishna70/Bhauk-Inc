<html>
<head>
<script typ="text/javascript" language="JavaScript">
function bittewarten(s){
        s.value = "Please wait ...";
        s.disabled = true;
        return true;
}
</script>
<title></title>
<meta name="author" content="Daniel Thul">
<meta name="generator" content="Ulli Meybohms HTML EDITOR">
</head>
<body text="#000000" bgcolor="#FFFFFF" link="#FF0000" alink="#FF0000" vlink="#FF0000">
<?
if (!isset($_POST['height']) || !isset($_POST['weight'])){
//Show a form, if nothing has been typed yet * Es wird ein Formular angezeigt, wenn noch nichts eingegeben wurde
?>
<center>
<form name="BMI" action="bmi.php" method="POST" onSubmit="return bittewarten(this.post)">
Schreiben sie bitte hier ihre Körpergröße (in meter, z.B. 1,83):<br>
Type your size here (in meters, e.g. 1,83):<br>
<input type="text" name="height" value="<?=$_POST['height']?>" maxlength="6"><br><br>
Und hier ihr Gewicht (in kg, z.B 63):<br>
And here your weight (in kg, e.g. 63):<br>
<input type="text" name="weight" value="<?=$_POST['weight']?>" maxlength="6"><br><br>
<input type="submit" value="BMI errechnen * calculate BMI" name="post">
</form>
<center>
<?
}
//First check if the variables are numbers * erst wird geprüft, ob die Variablen Nummern sind
else if (is_numeric(str_replace(",", ".", $_POST['height'])) && is_numeric(str_replace(",", ".", $_POST['weight']))){
$height = str_replace(",", ".", $_POST['height']);
$weight = str_replace(",", ".", $_POST['weight']);
require('class.bmi.php');
//If it is so, then a new object is being created... * Wenn ja, dann wird ein neues Objekt erstellt...
$bmi = new bmi;
//...the variables for the height and the weight are being set... * ...die Variablen für Größe und Gewicht gesetzt...
$bmi->height = $height;
$bmi->weight = $weight;
//...and the BMI is calculated and shown * ...und der BMI errechnet und ausgegeben
$bmi->calculate_bmi();
}
else{
echo 'Sie haben keine, oder nicht nur Nummern eingegeben! * You've typed no or not only numbers!';
}
?>
</body>
</html>
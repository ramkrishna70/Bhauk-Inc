<?php
	header("Content-type:text/javascript");
	include("PhpAjaxSubmitForms.php");
	try {
		$obj = new PhpAjaxSubmitForms("demo.php", "GET");
		/*if you dont put anything the parametars are: ("default.php", "GET", "sid=Math.Random()")*/
		$responseID = "response"; //id of element that you want to display response in
		$inputfields = array("username*", "password*", "title"); //the asterisk at the end of the string is for required fields
		$obj->SubmitFormFunction($inputfields, $responseID); //create javascript function to submit the form
	} catch (Exception $e) {
		echo $e->getMessage();
	}
?>
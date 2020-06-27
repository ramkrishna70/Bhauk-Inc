<?php 
//simple output to AJAX call
if(isset($_GET['username'])) {
	foreach ($_GET as $key => $value) {
		echo $key ." = ". $value. "<br />";
	}
	exit();
}
?>
<html>
<head>
	<title>Php ajax test</title>
	<script type="text/javascript" src="example.php"></script>
</head>

<body>
<div id="loader" style="display:none;">
<img src="loader.gif" />
</div>
	<form method="GET" name="SomeForm" onsubmit="return false"><!-- Very important to put onsubmit="return false"-->
		Username:<input type="text" name="username" id="username" />
		Title:<input type="text" name="title" id="title" />
		Password:<input type="password" name="password" id="password" />
		<input type="submit" name="SubmitBtn" id="SubmitBtn" value="Submit" onclick="SubmitForm();" /><!-- Very important to put onclick="SubmitForm();"-->
	</form>
	<div id="response" style="width:500px; height:250px;"></div>
</body>
</html>
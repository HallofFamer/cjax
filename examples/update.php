<?php

require_once "ajax.php";

$ajax->click("button1",$ajax->call("ajax.php?update/updateBox"));
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Ajax Update Containers,divs,spans, and others</title>
<?php echo $ajax->init();?>
<style>
.box {
	position:relative;
	overflow: auto;
	width: 200px;
	height: 200px;
	margin-right:1px;
	background-color: #CEFFCE;
}
</style>
</head>
<body>
<input type='button' id='button1' name='button1' value='Update box below with content'>

<div class='box' id='content1'></div>
<br />
Code used:
<?php 
echo $ajax->code("
\$ajax->update('content1', \$text);
//OR
\$ajax->content1 = \$text;
");?>
</body>
</html>
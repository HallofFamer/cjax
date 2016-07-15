<?php

require_once "ajax.php";

$ajax->click("addBtn",$ajax->call("ajax.php?changeclass/addClass"));
$ajax->click("removeBtn",$ajax->call("ajax.php?changeclass/removeClass"));
$ajax->click("toggleBtn",$ajax->call("ajax.php?changeclass/toggleClass"));

$ajax->click("addBtn2",$ajax->call("ajax.php?changeclass/addClasses"));
$ajax->click("removeBtn2",$ajax->call("ajax.php?changeclass/removeClasses"));
$ajax->click("toggleBtn2",$ajax->call("ajax.php?changeclass/toggleClasses"));
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Ajax Change Class for elements</title>
<?php echo $ajax->init();?>
<style>
#box {
    width: 280px;
    text-align:center;
    border-style: solid;
}
#box2 {
    width: 320px;
    text-align:center;
    border-style: solid;
}
.redbox {
    border-color: red;
}
.blueback {
    color: white;
    background-color: blue;
}
</style>
</head>
<body>
<p><b><i>Since CJAX 6.0 it is possible to change the class of a given element with ease with addClass, removeClass and toggleClass functions:</i></b></p>
<p>Click the below buttons to play with this functionality of adding/removing class(.redbox).</p>
<div id='box'>Fun with CJAX Framework version 6.0!</div>
<br />
<input type='button' id='addBtn' value='Add Class'>
<input type='button' id='removeBtn' value='Remove Class'>
<input type='button' id='toggleBtn' value='Toggle Class'>
<br /><br /><br />
<p>It is also possible to add/remove multiple classes for a given element(.redbox and .blueback).</p>
<div id='box2'>More Fun with CJAX Framework version 6.0!</div>
<br />
<input type='button' id='addBtn2' value='Add Classes'>
<input type='button' id='removeBtn2' value='Remove Classes'>
<input type='button' id='toggleBtn2' value='Toggle Classes'>
<br /><br />
Code used:
<?php 
echo $ajax->code("
\$ajax->addClass('#box', '.redbox');
\$ajax->removeClass('#box', '.redbox');
\$ajax->toggleClass('#box', '.redbox');


\$ajax->addClass('#box2', ['.redbox', '.blueback']);
\$ajax->removeClass('#box2', ['.redbox', '.blueback']);
\$ajax->toggleClass('#box2', ['.redbox', '.blueback']);
");?>
<p>Note: The dot(.) sign in front of class name is optional, but this notation is recommended to distinguish it as class name, rather than an element id.</p>
</body>
</html>
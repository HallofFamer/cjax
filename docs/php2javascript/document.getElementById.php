<?php
require '../ajax.php';

use CJAX\Core\CJAX;
$ajax = CJAX::getInstance();

?>
<html>
<head>
<?php  echo $ajax->init();?>
</head> 
<body>
<h2>getElementByid in PHP</h2>
(5.0-RC2+)
<br />
Syntax:  $ajax->document([mixed property/function], [elementId], [callback])
<br />
Parameter:
<br />
<ul>
	<li>
	"getElementById" string
	</li>
	<li>
	elementId string<br />
	The id of an element in the HTML document.
	</li>
	<li>
	callback string [JavaScript Function]<br />
	a call back JavaScript function in a string. First parameter in this callback will be the element obtained.
	</li>	
	
</ul>
<h4>Example</h4>
<br />


<?php 

echo $ajax->code("
\$ajax->document(\"getElementById\",\"elementId\",\"function(element) {
		//your element is...
		alert(element);
	}
\");"
);
?>
</body>
</html>
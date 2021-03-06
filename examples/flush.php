<?php

require_once 'ajax.php';

$ajax->exec('#link1', $ajax->call('ajax.php?flush/flushElement'));

?>
<html>
<head>
<?php echo $ajax->init();?>
<title>Flush</title>
</head>
<body>
<H2>Flush</H2>
Remove events from an element. Lets say you used $ajax->click() to add one or more requests to a link, and maybe other APis.
Flush, will clear  all events set to that element. This allows you to re-use stuff.
<?php 
echo $ajax->code("
//add an ajax request to #link1
\$ajax->exec('#link1', \$ajax->call('ajax.php?flush/flushElement'));

//in the controller we flush it..
//so if you click the link again, it is clean and won't do anything.

//controller
namespace Examples\\Controllers;
use CJAX\\Core\\AJAXController;

class Flush extends AJAXController{

	public function flushElement(){
		//Flus link1 HTML
		\$this->ajax->flush('#link1');
	}
}
");
?>
<a id='link1' href='#'>link1</a> 
</body>
</html>
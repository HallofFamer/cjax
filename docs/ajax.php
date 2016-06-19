<?php

use CJAX\Core\CJAX;
use CJAX\Core\CJAXException;

if(!file_exists($f = '../cjax/ajax.php')) {
	throw new CJAXException("Ajax File was not found.");
}

require_once $f;
$ajax = CJAX::getInstance();
if(!$ajax->isAjaxRequest()) {
	
	$ajax->document('body.innerHTML', ['prepend'=> "
	<div>
	<a href='http://cjax.sourceforge.net'>
	<img src='http://cjax.sourceforge.net/media/logo.png' border=0/>
	</a>
	</div>"]);
	$ajax->import('resources/css/table.css');
}
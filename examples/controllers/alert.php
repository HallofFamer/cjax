<?php

use CJAX\Core\CJAX;

class controller_alert {
	
	function fire_alert($message)
	{
		
		$ajax = CJAX::getInstance();
		
		$ajax->alert($message);
	}
}
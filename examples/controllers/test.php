<?php

namespace Examples\Controllers;
use CJAX\Core\AJAXController;

class Test extends AJAXController{
	
	public function test2(){
		echo "This is response of test/test2, generic test controller.";
	}
	
	public function remote($key){
		$ajaxEvent = json_decode($_COOKIE['cjaxevent'], TRUE);
        $ajaxEventMessage = "<br>";
        foreach($ajaxEvent as $key => $value){
            if($key == "timeStamp") $value = date("Y-m-d H:i:s", $value/1000);
            $ajaxEventMessage .= "{$key}: {$value};<br>";
        }
		$this->ajax->success("You pressed key: {$key}. It is associated with event: {$ajaxEventMessage}");
    }
	
	public function formHandler(){
		$this->debug($_POST);
	}
}
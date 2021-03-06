<?php

namespace Examples\Controllers;
use CJAX\Core\AJAXController;

class ChangeValue extends AJAXController{
	
	public function text($elementId, $current_value){
		$this->ajax->text1 = "Random number..".rand(100,1000);
	}
	
	
	public function check($elementId, $current_value){
		if($current_value){
			$this->ajax->check1 = false;
		} 
        else{
			$this->ajax->check1 = true;
		}
	}
	
	public function div($num = 0){
		$text = [];
		
	//Some random strings .......
		$text[] = "Lorem Ipsum is simply dummy text of the printing and typesetting industry. ";
		$text[] = "Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown ";
		$text[] = "printer took a galley of type and scrambled it to make a type";
		$text[] = "specimen book. It has survived not only five centuries, but also the leap into electronic";
		$text[] = "typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of ";
		$text[] = "Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.";
		$text[] = "it is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout."; 
		$text[] = "The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using";
		$text[] = "'Content here, content here', making it look like readable English. Many desktop publishing packages and ";
		$text[] = "web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many";
		$text[] = "web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).";
		
		$this->ajax->DIV_1 = $text[rand(0, count($text)-1)];
	}
}
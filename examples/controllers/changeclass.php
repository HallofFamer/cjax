<?php

namespace Examples\Controllers;
use CJAX\Core\AJAXController;

class ChangeClass extends AJAXController{

	public function addClass(){		
		$this->ajax->addClass("#box", ".redbox");
	}	

    public function removeClass(){
		$this->ajax->removeClass("#box", ".redbox");        
    }

    public function toggleClass(){
        $this->ajax->toggleClass("#box", ".redbox");
    }

    public function addClasses(){
		$this->ajax->addClass("#box2", [".redbox", ".blueback"]);
    }

    public function removeClasses(){
		$this->ajax->removeClass("#box2", [".redbox", ".blueback"]);
    }

    public function toggleClasses(){
		$this->ajax->toggleClass("#box2", [".redbox", ".blueback"]);
   }
}
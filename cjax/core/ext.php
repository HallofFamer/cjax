<?php

/** ################################################################################################**   
* Copyright (c)  2008  CJ.   
* Permission is granted to copy, distribute and/or modify this document   
* under the terms of the GNU Free Documentation License, Version 1.2   
* or any later version published by the Free Software Foundation;   
* Provided 'as is' with no warranties, nor shall the author be responsible for any mis-use of the same.     
* A copy of the license is included in the section entitled 'GNU Free Documentation License'.   
*   
*   CJAX  6.0               $     
*   ajax made easy with cjax                    
*   -- DO NOT REMOVE THIS --                    
*   -- AUTHOR COPYRIGHT MUST REMAIN INTACT -   
*   Written by: CJ Galindo                  
*   Website: http://cjax.sourceforge.net                     $      
*   Email: cjxxi@msn.com    
*   Date: 2/12/2007                           $     
*   File Last Changed:  04/05/2016           $     
**####################################################################################################    */ 

namespace CJAX\Core;

class Ext{
	
	function __construct($array = []){
		if($array && is_array($array) || is_object($array)){
			foreach($array as $k => $v){
				$this->{$k} = $v;
			}
		}
	}
	
	function __set($setting, $value){
		$this->$setting = $value;
	}
	
	function __get($setting){
		if(isset($this->$setting)){
			return $this->$setting;
		}
	}
}
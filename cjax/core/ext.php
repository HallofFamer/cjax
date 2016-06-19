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
*   File Last Changed:  04/18/2016           $     
**####################################################################################################    */ 

namespace CJAX\Core;

/**
 * The Ext class, defines an extensible class whose properties can be dynamically added/removed.
 * Ext serves as base class for many CJAX objects to allow convenient Xml and settings/options manipulation.
 * @category CJAX
 * @package Core
 * @author CJ Galindo <cjxxi@msn.com>
 * @copyright (c) 2008, CJ Galindo
 * @link https://github.com/ajaxboy/cjax
 * @version 6.0
 * @since 1.0
 */

class Ext{
	
	/**
     * The constructor for Ext class, dynamically creates an object with its properties from an array parameter.
     * @param array  $array
     * @access public
     * @return Ext
     */	    
	public function __construct($array = []){
		if($array && is_array($array) || is_object($array)){
			foreach($array as $k => $v){
				$this->{$k} = $v;
			}
		}
	}
	
	/**
     * The magic method __get, obtains a dynamically created property for Ext class if it exists.
	 * @param string  $setting
     * @access public
     * @return Mixed
     */	 	
	public function __get($setting){
		if(isset($this->$setting)){
			return $this->$setting;
		}
	}    
    
	/**
     * The magic method __set, dynamically creates properties for Ext class.
	 * @param string  $setting
     * @param mixed  $value
     * @access public
     * @return void
     */	    
	public function __set($setting, $value){
		$this->$setting = $value;
	}
}
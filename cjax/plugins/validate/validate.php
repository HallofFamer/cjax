<?php

/** ################################################################################################**   
* Copyright (c)  2008  CJ.   
* Permission is granted to copy, distribute and/or modify this document   
* under the terms of the GNU Free Documentation License, Version 1.2   
* or any later version published by the Free Software Foundation;   
* Provided 'as is' with no warranties, nor shall the author be responsible for any misuse of the same.     
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

namespace CJAX\Plugins\Validate; 
use CJAX\Core\CJAX;
use CJAX\Core\Plugin;
 
/**
 * The Validate class, it provides public API for plugin Jquery Validate.
 * @category CJAX
 * @package Plugins
 * @subpackage Validate
 * @author CJ Calindo <cjxxi@msn.com>
 * @copyright (c) 2008, CJ Galindo
 * @link https://github.com/ajaxboy/cjax
 * @version 6.0
 * @since 6.0
 * @api
 */

class Validate extends Plugin{
	
	/**
	 * The rules property, stores an arrays of validation rules. 
     * @access private
	 * @var array
	 */      
	private $rules;
	

  	/**
     * The rightHandler method, it is called to assign callback handlers for validation.
     * @param string  $api
     * @param array  $args
     * @param object  $xmlObj
     * @access public
     * @return void
     */         
	public function rightHandler($api, $args, $xmlObj){
		switch($api){
			case '_overLay':				
				$xmlObj->callback = $this;
				break;
			case '_overLayContent':
				$xmlObj->callback = $this;
				break;
			default:
				$xmlObj->callback = $this;
		}
		
	}
    
  	/**
     * The onLoad method, it is called when the plugin is loaded for the first time.
     * @param string  $buttonId
     * @param string  $postUrl
     * @param array  $rules
     * @param bool  $importJs
     * @access public
     * @return void
     */       	
	public function onLoad($buttonId, $postUrl, $rules = [], $importJs = false){
		$ajax = CJAX::getInstance();
		$this->callback($ajax->click($buttonId,$ajax->form($postUrl)));	
		
		if($importJs){
			$this->import('jquery.validate.min.js');
		}		
		$this->rules = $rules;
	}
	
  	/**
     * The onAjaxLoad method, it is called when the plugin is loaded by an AJAX request.
     * For this plugin, it simply calls onLoad method for AJAX requests, only no need to import jquery library file.
     * @param string  $buttonId
     * @param string  $postUrl
     * @param array  $rules
     * @access public
     * @return void
     */      
	public function onAjaxLoad($buttonId, $postUrl, $rules = []){
		return $this->onLoad($buttonId, $postUrl, $rules);
	}
	
  	/**
     * The rule method, adds additional rules for validation.
     * @param array  $name
     * @param array  $rule
     * @access public
     * @return void
     */        
	public function rule($name, $rule){
		if(!$rule){
			return;
		}
		$rules = $this->rules['rules'];
		$messages  = $this->rules['messages'];
		
		foreach($rule as $k => $v){
			if(is_array($v)){
				if(isset($v[0])){
					$rules[$name][$k] = $v[0];
				}
				if(isset($v[1])){
					$messages[$name][$k] = $v[1];
				}
			} 
            else{
				$rules[$name][$k] = $v;
			}
		}
		$this->rules['rules'] = $rules;
		$this->rules['messages'] = $messages;
		
		$this->set('c', $this->rules);
	}
}
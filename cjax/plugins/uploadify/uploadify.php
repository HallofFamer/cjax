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
 
namespace CJAX\Plugins\Uploadify; 
use CJAX\Core\CJAX;
use CJAX\Core\Plugin;
 
/**
 * The Uploadify class, it provides public API for plugin Jquery Uploadify.
 * @category CJAX
 * @package Plugins
 * @subpackage Uploadify
 * @author CJ Calindo <cjxxi@msn.com>
 * @copyright (c) 2008, CJ Galindo
 * @link https://github.com/ajaxboy/cjax
 * @version 6.0
 * @since 6.0
 * @api
 */

class uploadify extends Plugin{

	/**
	 * The options property, stores a list of options for uploadify. 
     * @access private 
	 * @var array
	 */      
	private $options = []; 
	
	/**
	 * The options property, defines allowed extensions for uploading.
     * @access private
	 * @var array
	 */    
	public $exts = ['jpg','jpeg','gif','png'];

    
  	/**
     * The onLoad method, it is called when the plugin is loaded for the first time.
     * This method attempts to assign option variables and load javascript library files.
     * @param string  $uploadId
     * @param array  $options
     * @access public
     * @return void
     */      
	public function onLoad($uploadId = null, $options = []){	
		$this->options = $options;	
		$this->import('uploadify-3.2/jquery.uploadify.js',0,true);
		$this->import('uploadify-3.2/uploadify.css');
		$this->set('c', session_id());
	}
	
	/**
     * The magic method __set, dynamically assigns option variables for uploadify.
     * Below is an example of how to use this magic method:
     * <code>
     * <?php
     * $this->buttonText = "Button";
     * $this->fileTypeDesc = "Images";
     * ?>
     * </code>
	 * @param string  $setting
     * @param mixed  $value
     * @access public
     * @return void
     */		    
	public function __set($setting, $value){
		if($setting == 'target'){
			if($dir = CJAX::getInstance()->config->uploadify_dir){
				$value = $dir;
			}
			if(!$value){
				$value = './';
			}
			if(!is_writable($value)){
				
				$ajax = CJAX::getInstance();
				$ajax->error("Uploadify: Target is not writable. Check directory exists and has proper permission, then try again.");
				
				//remove any pending uploadify tasks
				$this->abort();
				return;
			}
			$this->save('target', $value);
			return;
		}
		
		if($setting == 'fileTypeExts'){
			$exts = preg_replace(["/^\*\./","/\*|\;/"],'',$value);
			$exts = explode('.',$exts);
			$this->exts = $exts;
			$this->save('exts', $exts);
		}
		$this->options[$setting] = $value;
		$this->set('b', $this->options, $this->id); //parameter, variable
	}
}
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
*   File Last Changed:  06/16/2016           $     
**####################################################################################################    */  

namespace CJAX\Plugins\Uploader;
use CJAX\Core\CJAX;
use CJAX\Core\Plugin; 
 
/**
 * The Uploader class, it provides public API for plugin Uploader.
 * @category CJAX
 * @package Plugins
 * @subpackage Uploader
 * @author CJ Calindo <cjxxi@msn.com>
 * @copyright (c) 2008, CJ Galindo
 * @link https://github.com/ajaxboy/cjax
 * @version 6.0
 * @since 5.0
 * @api
 */

class Uploader extends Plugin{
    
	/**
	 * The options property, stores a list of options for uploader. 
     * @access private 
	 * @var array
	 */   	
	private $options = [];
	
    
  	/**
     * The rightHandler method, handles right API assignments.
     * Below is an example of how to use this handler method:
     * <code>
     * <?php
	 * $ajax->validate()->uploader();
	 * $ajax->call()->uploader();
     * ?>
     * </code>
     * @param string  $api
     * @param array  $args
     * @param object  $xmlObj
     * @access public
     * @return void
     */        
	public function rightHandler($api, $args, $xmlObj){
		$xmlObj->postCallback = $this;
	}
		
  	/**
     * The callbackHandler method, it is called to assign callback handlers for uploader.
     * @param string  $sender
     * @param string  $receiver
     * @param string  $setting
     * @access public
     * @return void
     */          
	public function callbackHandler($sender, $receiver, $setting){
		switch($setting){
			case 'postCallback':
                $cache = $this->coreEvents->getCache();
				$event = $cache->get($receiver->id);			
				$callback = $cache->get($sender->id);				
				$event['postCallback'][$sender->id] = $callback;
				$sender->delete();
				
				$callbacks = $this->coreEvents->processScache($event['postCallback']);
				$callbacks = $this->coreEvents->mkArray($callbacks,'json', true);
				$event['postCallback'] =  "<cjax>{$callbacks}</cjax>";	
                $cache->set($receiver->id, $event);
				$this->coreEvents->simpleCommit();
			    break;
		}
	}
	
  	/**
     * The preview method, used to specifies preview options for uploader.
     * @param string  $previewUrl
     * @param array  $data
     * @access public
     * @return void
     */       
	public function preview($previewUrl, $data = []){
		$ajax = CJAX::getInstance();
		if($ajax->config->previewUrl){
			$previewUrl = $ajax->config->previewUrl;
		}
		$this->options['preview'] = $data;
		$this->options['preview_url'] = $previewUrl;
		$ajax->save('upload_options', $this->options);
	}

  	/**
     * The onLoad method, it is called when the plugin is loaded for the first time.
     * @param string  $btnId
     * @param string  $targetDirectory
     * @param array  $options
     * @access public
     * @return void
     */          
	public function onLoad($btnId =  null, $targetDirectory = null, $options = []){
		if(is_array($btnId) && !$options){
			$options = $btnId;
			$btnId = null;
			
			if(isset($options['dir'])){
				$targetDirectory = $options['dir'];
			}
		}
		$ajax = CJAX::getInstance();
		foreach($options as $k =>$v){
			$this->{$k} = $v;
		}
		if(isset($options['before'])){
			$this->set('a', $options);
		}
		
		if($ajax->config->uploaderDir){
			$targetDirectory = $ajax->config->uploaderDir;
		}
		if(!$targetDirectory){
			$targetDirectory = './';
		}

		if(!is_writable($targetDirectory) && !is_writable("../{$targetDirectory}")){
			return $ajax->warning("Cjax Upload: Directory '{$targetDirectory}' is not writable, , aborting..",5);
		}
		if(!isset($options['text'])){
			$options['text'] = 'Uploading File(s)...';
		}
		if(!isset($options['ext'])){
			$options['ext'] = ['jpg','jpeg','gif','png'];
		}
		if(!isset($options['files_require'])){
			$options['files_require'] = true;
		}
		if(!isset($options['form_id'])){
			$options['form_id'] = null;
		}
		$ajax->text = $options['text'];
		$target = rtrim($targetDirectory,'/') . '/';
		
		if(!isset($options['url'])){
			$options['url'] = null;
		}
		
		if(!isset($options['target'])){
            $root = (defined('CJAX_ROOT'))?CJAX_ROOT."/":"";         
			$options['target'] = $root.$target;
		}
		$ajax->save('uploadOptions', $options);
			
		if(!$btnId || is_array($btnId)){
			$xml = $ajax->form($options['url'], $options['form_id']);
		} 
        else{
			$xml = $ajax->exec($btnId, $ajax->form($options['url'], $options['form_id']));
		}
		$this->options = $options;
		$this->callback($xml);
	}
	
  	/**
     * The onAjaxLoad method, it is called when the plugin is loaded by an AJAX request.
     * For this plugin, it simply calls onLoad method for AJAX requests, only no need to import jquery library file.
     * @param string  $btnId
     * @param string  $targetDirectory
     * @param array  $options
     * @access public
     * @return void
     */      
	public function onAjaxLoad($btnId, $targetDirectory, $options = []){
		return $this->onLoad($btnId, $targetDirectory, $options);
	}
}
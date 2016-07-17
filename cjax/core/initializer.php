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
*   File Last Changed:  06/18/2016           $     
**####################################################################################################    */  

namespace CJAX\Core;

/**
 * The Initializer class, the utility class used to create the ajax object.
 * This class is useful as creation of CJAX singleton object is complex.
 * @category CJAX
 * @package Core
 * @author Ordland Euroboros <halloffamer@mysidiarpg.com>
 * @copyright (c) 2008, CJ Galindo
 * @link https://github.com/ajaxboy/cjax
 * @version 6.0
 * @since 6.0
 * @final
 */

final class Initializer{
    
    /**
     * The ajax property, stores a reference to the singleton ajax object.
     * @access private 
     * @var CJAX
     */	      
    private $ajax;
    
    
	/**
     * The constructor for Initializer class, initializes basic property for CJAX given its empty instance.
	 * @param CJAX  $ajax
     * @access public
     * @return Initializer
     */	      
    public function __construct(CJAX $ajax){
        $this->ajax = $ajax;
        $this->ajax->config = (file_exists(CJAX_HOME."/config.php"))? new Config: new Ext;
        $this->ajax->cache = new Cache($this->ajax->config->caching);
        $this->ajax->coreEvents = new CoreEvents($this->ajax->config, $this->ajax->cache);
        $this->ajax->domEvents = new DOMEvents($this->ajax->coreEvents);
        $this->ajax->pluginManager = $this->ajax->coreEvents->getPluginManager();
        $this->ajax->request = new Request($this->ajax->coreEvents);
    }
    
	/**
	 * The initiate method, initiates further attributes for CJAX and returns its instance.
     * @access public
     * @return CJAX
	 */      
    public function initiateAjax(){
        $this->initiateFormat();
        $this->initiateDebugging();
        $this->initiateJsPath();  
        return $this->ajax;
    }
    
	/**
	 * The initiateFormat method, initiates format object and associated properties.
     * @access private
     * @return void
	 */     
    private function initiateFormat(){
		if(!isset($this->ajax->format) || !$this->ajax->format){
			$this->ajax->format = new Format;		
			$this->initiateSession();
			if(!$this->ajax->isAjaxRequest() && defined('AJAX_CD')){
				@setcookie('AJAX_CD', AJAX_CD, null, '/');
			} 
            else{
				if(isset($_COOKIE['AJAX_CD']) && !defined('AJAX_CD')){
					define('AJAX_CD', $_COOKIE['AJAX_CD']);
				}
				if(!function_exists('cleanInput')){
					function cleanInput($input){
						return $input;
					}
				}
			}
		}    
    }
    
	/**
	 * The initiateSession method, initiates a session for CJAX.
     * @access private
     * @return void
	 */        
    private function initiateSession(){
		if(isset($_REQUEST['session_id'])){
			session_id($_REQUEST['session_id']);
			@session_start();
		} 
        elseif(!$this->ajax->config->fallback && !isset($_SESSION)){
		    @session_start();
		}        
    }
    
	/**
	 * The initiateDebugging method, initiates debugging options for CJAX.
     * @access private
     * @return void
	 */      
    private function initiateDebugging(){
		if($this->ajax->config->ipDebug){
			if(is_array($this->ajax->config->ipDebug) && in_array(@$_SERVER['REMOTE_ADDR'], $this->ajax->config->ipDebug)){
				$this->ajax->config->ipDebug = false;
			} 
            elseif(@$_SERVER['REMOTE_ADDR'] != $this->ajax->config->ipDebug){
				$this->ajax->config->ipDebug = false;
			}
		}
		
		if($this->ajax->config->caching && isset($_SERVER['REQUEST_URI'])){
			$this->ajax->coreEvents->crc32 = crc32($_SERVER['REQUEST_URI']);
			$cache = $this->ajax->cache->read('cjax-'.$this->ajax->coreEvents->crc32);
			$this->ajax->coreEvents->caching = $cache;
		}
		if($this->ajax->config->debug){
			@ini_set('display_errors', 1);
			@ini_set('log_errors', 1);
		}        
    }
    
	/**
	 * The initiateJsPath method, initiates javascript path options for CJAX.
     * @access private
     * @return void
	 */      
    private function initiateJsPath(){
		if(!$jsDir = $this->ajax->config->jsPath){
			if(@is_dir('cjax/')){
				$jsDir  = "cjax/assets/js/";
			} 
            elseif(@is_dir('assets/js/')){
				$jsDir  = "assets/js/";
			} 
            elseif(@is_dir('../cjax')){
				$jsDir  = "../cjax/assets/js/";
			} 
            elseif(@is_dir('../../cjax')){
				$jsDir  = "../../cjax/assets/js/";
			} 
            elseif(@is_dir('../../../cjax')){
				$jsDir  = "../../../cjax/assets/js/";
			} 
            else{
                throw new CJAXException("Cannot find the correct path to Js directory.");
			}
			
			$error = error_get_last();			
			if($error && preg_match('%.*(open_basedir).*File\(([^\)]+)%', $error['message'], $match)){
                throw new CJAXException(sprintf("Restriction <b>open_basedir</b> is turned on. File or directory %s will not be accessible while this setting is on due to security directory range.", $match[2]));
			}
		}
		$this->ajax->js($jsDir);	        
    }
}
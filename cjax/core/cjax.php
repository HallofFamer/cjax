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

namespace CJAX\Core;

/**
 * The CJAX class that is the core public API used to create AJAX requests/events.
 * By default CJAX follows singleton pattern, which will be changed to DI in future.
 * @category CJAX
 * @package Core
 * @author CJ Galindo <cjxxi@msn.com>
 * @copyright (c) 2008, CJ Galindo
 * @link https://github.com/ajaxboy/cjax
 * @version 6.0
 * @since 1.0
 */

class CJAX extends Framework{

	/**
	 * The CJAX property, stores the singleton instance of CJAX. 
     * @access private
	 * @var CJAX
     * @static
	 */      
	private static $CJAX;
		
        
	/**
     * The static method getInstance, acquires the singleton instance for CJAX class.
     * This method will be deprecated in future and removed in CJAX 7.0.
     * @access public
     * @return CJAX
     * @static
     * @api
     */
	public static function getInstance(){
		if(self::$CJAX){
			return self::$CJAX;
		}		
		CoreEvents::errorHandlingConfig();
		$ajax = new self;
		if(!defined('JSON_FORCE_OBJECT')){
			define('JSON_FORCE_OBJECT', 16);
		}
		
		if(!isset($ajax->format) || !$ajax->format){
			$ajax->format = new Format;		
			$config = new Ext;
			if(file_exists($f = CJAX_HOME."/"."config.php")){
				include($f);
				if(isset($config)){
					$config = new Ext($config);
				}
			}
			$ajax->config = $config;
			
			$ajax->initiate($ajax);
			if(!$ajax->isAjaxRequest() && defined('AJAX_CD')){
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
        
		if($ajax->config->ipDebug){
			if(is_array($ajax->config->ipDebug) && in_array(@$_SERVER['REMOTE_ADDR'], $ajax->config->ipDebug)){
				$ajax->config->ipDebug = false;
			} 
            elseif(@$_SERVER['REMOTE_ADDR']!=$ajax->config->ipDebug){
				$ajax->config->ipDebug = false;
			}
		}
		
		if($ajax->config->caching && isset($_SERVER['REQUEST_URI'])){
			$ajax->crc32 = crc32($_SERVER['REQUEST_URI']);
			$cache = $ajax->readCache('cjax-'.$ajax->crc32);
			$ajax->caching = $cache;
		}
		if($ajax->config->debug){
			@ini_set('display_errors', 1);
			@ini_set('log_errors', 1);
		}
		
		if(!$jsDir = $ajax->config->jsPath){
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
		$ajax->js($jsDir);		
		return self::$CJAX = $ajax;
	}

	/**
     * The initiateRequest method, initiates an AJAX request.
     * @access public
     * @return void
     */	      
    public function initiateRequest(){
        if(!$this->handleModRewrite()) return;
        $this->handleFriendlyURLs();        
    }

 	/**
     * The handleModRewrite method, handles mod rewrite with server variables.
     * @access private
     * @return void
     */	      
    private function handleModRewrite(){
        if(isset($_SERVER['REDIRECT_QUERY_STRING']) && $_SERVER['REDIRECT_QUERY_STRING']){
        	$_SERVER['QUERY_STRING'] = $_SERVER['REDIRECT_QUERY_STRING'];
        } 
        elseif(isset($_SERVER['PATH_INFO']) && $_SERVER['PATH_INFO'] && !$_SERVER['QUERY_STRING']){
	        $_SERVER['QUERY_STRING'] = ltrim($_SERVER['PATH_INFO'],'/');
        }

        $file = 'ajax.php';
        if(isset($_SERVER['SCRIPT_NAME'])){
	        $file = preg_replace("/.+\//",'', ltrim($_SERVER['SCRIPT_NAME'],'/'));
        }
        if(defined('AJAX_FILE') && AJAX_FILE != $file){
	        return false;
        }
        return true;
    }

 	/**
     * The handleFriendlyURLs method, handles friendly urls with server variables.
     * @access private
     * @return void
     */	          
    private function handleFriendlyURLs(){
        if(isset($_SERVER['QUERY_STRING']) && $query = $_SERVER['QUERY_STRING']){
        	$packet = explode('/' ,rtrim($query,'/'));
        	if(count($packet) == 1) {
                $this->handlePlugin($packet[0]);
	        }
        	if($this->isAjaxRequest() || defined('AJAX_VIEW') ){
                $this->handleController($packet);
	        }
        }

        if(!$this->isAjaxRequest() && count(array_keys(debug_backtrace(false))) == 1 && !defined('AJAX_VIEW')){
	        throw new CJAXException("Security Error. You cannot access this file directly.");
        }
    }

 	/**
     * The handlePlugin method, handles additional actions for plugins.
     * @param mixed  $isPlugin
     * @access private
     * @return void
     */	      
    private function handlePlugin($isPlugin = null){
	    if($plugin = $this->isPlugin($isPlugin) && !defined('AJAX_VIEW')){
		    define('AJAX_VIEW', true);
	    }        
    }

 	/**
     * The handlePController method, handles additional actions for controllers.
     * @param array  $packet
     * @access private
     * @return void
     */	       
    private function handleController($packet = null){
	    if($packet && count(array_keys($packet)) >= 2 && $packet[0] && $packet[1]){
		    $_REQUEST['controller'] = $packet[0];
		    $_REQUEST['function'] = $packet[1];
		    $_REQUEST['cjax'] = time();
		    if(count(array_keys($packet)) > 2){
			    unset($packet[0]);
				unset($packet[1]);
				if($packet){
				    $params = range('a','z');
					foreach($packet as $k  => $v){
					    $_REQUEST[current($params)] = $v;
						next($params);
				    }
				}
		    }
		} 
        elseif(!$this->input('controller') && count($packet) == 1){
			$url = explode('&',$_SERVER['QUERY_STRING']);
			if(count($url) == 1){
				$_REQUEST['controller'] = $packet[0];
			}
	    }        
    }
}
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
 * The Request class, it iss used to handle and check CJAX requests.
 * @category CJAX
 * @package Core
 * @author Ordland Euroboros <halloffamer@mysidiarpg.com>
 * @copyright (c) 2008, CJ Galindo
 * @link https://github.com/ajaxboy/cjax
 * @version 6.0
 * @since 6.0
 * @final
 */

final class Request{
    
    /**
     * The coreEvents property, stores a reference to the CoreEvents object.
     * @access private 
     * @var CoreEvents
     */	     
    private $coreEvents;
    
    
	/**
     * The constructor for Request class, creates a CJAX Request object.
	 * @param CoreEvents  $coreEvents
     * @access public
     * @return Request
     */	    
    public function __construct(CoreEvents $coreEvents){
        $this->coreEvents = $coreEvents;
    }
    
	/**
	 * The checkRequest method, tells whether or not the a ajax request has been placed.
	 * @param mixed  $callback
     * @param array  $param
     * @access public
	 * @return boolean
	 */    
    public function checkRequest($callback = null, &$params = null){
	 	$r = $this->coreEvents->isAjaxRequest();
	 	if($r && $callback){
	 		if(is_array($callback)){
	 			if(substr($callback[0],0,4) == 'self'){
	 				$arr = debug_backtrace(false);
		 			$trace = $arr[1];
		 			$class = $trace['class'];
	 				$callback[0] = $class;
	 			}
	 			if(!$params) $params = [];
	 			$r = call_user_func_array($callback, $params);
	 		} 
            else{
	 			$r = call_user_func($callback);
	 		}
	 		exit;
	 	}
        return ($this->coreEvents->isAjaxRequest())? true: false;        
    }
    
 	/**
	 * The setRequest method, sets CJAX request parameter.
	 * @param bool  $request
     * @access public
	 * @return void
	 */       
	public function setRequest($request = true){
        $_GET['cjax'] = ($request)? time(): '';
        $_REQUEST['cjax'] = ($request)? time(): '';
	}    
    
	/**
     * The handleRequest method, handles an AJAX request for CJAX.
     * @access public
     * @return void
     */	          
    public function handleRequest(){
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
        	if($this->coreEvents->isAjaxRequest() || defined('AJAX_VIEW') ){
                $this->handleController($packet);
	        }
        }

        if(!$this->coreEvents->isAjaxRequest() && count(array_keys(debug_backtrace(false))) == 1 && !defined('AJAX_VIEW')){
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
	    if($plugin = $this->coreEvents->isPlugin($isPlugin) && !defined('AJAX_VIEW')){
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
        elseif(!$this->coreEvents->input('controller') && count($packet) == 1){
			$url = explode('&',$_SERVER['QUERY_STRING']);
			if(count($url) == 1){
				$_REQUEST['controller'] = $packet[0];
			}
	    }        
    }    
}
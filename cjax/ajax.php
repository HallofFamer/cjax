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
*   File Last Changed:  06/18/2016            $     
**####################################################################################################    */   

require_once __DIR__."/autoloader.php";
use CJAX\Auth;
use CJAX\Core\CJAX; 
use CJAX\Core\CJAXException;
use CJAX\Core\Ext;

/**
 * The AJAX class that initializes and handles AJAX requests with CJAX.
 * @category CJAX
 * @author CJ Galindo <cjxxi@msn.com>
 * @copyright (c) 2008, CJ Galindo
 * @link https://github.com/ajaxboy/cjax
 * @version 6.0
 * @since 1.0
 * @final
 */

final class AJAX{
    
	/**
	 * The ajax property, stores an instance of CJAX for operations.
     * @access private
	 * @var CJAX
	 */    	
    private $ajax;
    
    
	/**
     * The constructor for AJAX class, initializes an AJAX request for CJAX.
	 * @param string  $controller
     * @access public
     * @return AJAX
     */	    
	public function __construct($controller){
        $this->configErrorReporting();
		$this->ajax = CJAX::getInstance();
		$controller = $rawClass = preg_replace('/:.*/', '', $controller);
		$function = isset($_REQUEST['function'])? $_REQUEST['function']: null;		
		if($this->ajax->config->camelize){
			$rawClass = $this->ajax->camelize($rawClass, $this->ajax->config->camelizeUcfirst);
		}
		$requestObject = null;
		$altController = null;
		
        $this->validateInput($controller, $function);      	
		$args = $this->filterParams();
		if($controller == '_crossdomain'){
            $method = new ReflectionMethod($this->ajax, 'crossdomain');
			return $this->getResponse($method->invokeArgs($this->ajax, $this->fetchEvent($method, $args)));
		}	
        if($plugin = $this->ajax->plugin($controller, true)){ 
            if(method_exists($plugin, $function)){
                $method = new ReflectionMethod($plugin, $function);
                return $this->getResponse($method->invokeArgs($plugin, $this->fetchEvent($method, $args)));
            } 
            else{
                $altController = $plugin->controllerFile;
                if(!file_exists($altController)){
                    $this->abort("{$controller} Plugin: Controller File Not Found.");
                }
            }
        }

		$isFile = $this->loadFiles($controller, $altController);	
        $requestObject = ($isFile)? $this->createController($controller, $function): $requestObject;	
        if($this->authenticate($controller, $function, $args, $requestObject)){
            return;
        }
    
        $function = ($function)? $function: $rawClass;
        $this->checkController($isFile, $rawClass, $requestObject, $controller, $function);        
        $method = new ReflectionMethod($requestObject, $function);
        $this->getResponse($method->invokeArgs($requestObject, $this->fetchEvent($method, $args)));
	}
    
	/**
     * The abort method, ends AJAX request with an error message.
     * @param string  $err
     * @access public
     * @return void
     */	    
	public function abort($err){
		$this->ajax->error($err);
		exit($err);
	}
    
 	/**
     * The configErrorReporting method, configures error reporting for CJAX.
     * @access private
     * @return int
     */	    
    private function configErrorReporting(){
		@ini_set('display_errors', 1);
		@ini_set('log_errors', 1);
		$level = ini_get('error_reporting');
		if($level > 30719 || $level == 2048){
			@ini_set('error_reporting', $level-E_STRICT);
			$level = ini_get('error_reporting');
		}
		return $level;        
    }
    
 	/**
     * The validateInput method, checks if controller and function input parameters are valid.
     * @param string  $controller
     * @param string  $function
     * @access private
     * @return void
     */	     
    private function validateInput($controller, $function){
		if(preg_match("/[^a-zA-Z0-9_]/", $controller)){
			$this->abort("Invalid Controller: {$controller}");
		}
		if($function && preg_match("/[^a-zA-Z0-9_]/", $function)){
			$this->abort("Invalid Function: {$function}");
		} 
		if(file_exists($f = CJAX_HOME.'/'.'includes.php')){
			if(!defined('AJAX_INCLUDES')){
				$this->ajax->includes = true;
				include_once $f;
			}
		}          
    }
    
 	/**
     * The checkController method, checks if controller and its function are available.
     * @param bool  $isFile
     * @param string  $rawClass
     * @param object  $requestObject
     * @param string  $controller
     * @param string  $function
     * @access private
     * @return void
     */	     
    private function checkController($isFile, $rawClass, $requestObject, $controller, $function){
		if(!$isFile){
			header("Content-disposition:inline; filename='{$controller}.php'");
			header("HTTP/1.0 404 Not Found");
			header("Status: 404 Not Found");
			$this->abort("Controller File: {$controller}.php not found");
		}
		if(!$controller){
			$this->abort("Controller Class \"{$rawClass}\" could not be found.");
		}
		if(!method_exists($requestObject, $function)){
			$this->abort("Controller Method/Function: {$rawClass}::{$function}() was not found");
		}        
    }
    
 	/**
     * The authenticate method, attempts to authenticate AJAX requests if necessary.
     * @param string  $controller
     * @param string  $function
     * @param array  $args
     * @param object  $requestObject
     * @access private
     * @return bool
     */	      
    private function authenticate($controller, $function, $args, $requestObject){
		if(file_exists($f = CJAX_HOME.'/auth.php')){
			require_once $f;
			if(class_exists('AjaxAuth')){
				$auth = new Auth;
				if(!$auth->validate($controller, $function, $args, $requestObject)) {
					$auth->authError();
                    return true;
				}
			} 
            else{
				$this->abort("Class AjaxAuth was not found.");
			}
			if(method_exists($auth, 'intercept') && $response = $auth->intercept($controller, $function , $args, $requestObject)){
				if(is_array($response) || is_object($response)){
					$this->getResponse($response);
				}
				return true;
			}
		}
        return false;        
    }
    
 	/**
     * The fetchEvent method, adds event arg to AJAX request parameter if necessary.
     * @param ReflectionMethod  $method
     * @param array  $args
     * @access private
     * @return array
     */	    
    private function fetchEvent($method, $args){
        $parameters = $method->getParameters();
        if($parameters){
            $parameterClass = $parameters[0]->getClass();
            if($parameterClass && $parameterClass->getNamespaceName() == "CJAX\\Event"){
                array_unshift($args, $parameterClass->newInstance(json_decode($_COOKIE['cjaxevent'])));
            }
        } 
        return $args;        
    }
    
 	/**
     * The getResponse method, prints an AJAX response to the screen.
     * @param array|object  $response
     * @access private
     * @return void
     */	        
	private function getResponse($response){
		if($response && (is_array($response) || is_object($response))){
			header('Content-type: application/json; charset=utf-8');
			print $this->ajax->jsonEncode($response);
		}		
	}
	
 	/**
     * The loadFiles method, loads files given controller name.
     * @param string  $controller
     * @param string  $altController
     * @access private
     * @return void
     */	          
	private function loadFiles($controller, $altController = null){
		if($altController){
			$files[] = $altController;
		}
		if(defined('AJAX_CD')){
			$ajaxCd = AJAX_CD;
		} 
        elseif(isset($_COOKIE['AJAX_CD']) && $_COOKIE['AJAX_CD']){
			$ajaxCd = $_COOKIE['AJAX_CD'];
		}
		
		$files[] = "{$ajaxCd}/{$controller}.php";
		$files[] = "{$this->ajax->coreEvents->controllerDir}/{$ajaxCd}/{$controller}.php";
		$files[] = CJAX_ROOT."/{$ajaxCd}/{$controller}.php";
		
		do{
			if(file_exists($f = $files[key($files)])){
				require_once $f;
				return $f;
			}
		}while(next($files));
	}

 	/**
     * The filterParams method, acquires parameters from AJAX request.
     * @access private
     * @return array
     */	        
	private function filterParams(){
		$args = [];
		$argCount = count(array_keys($_REQUEST)) - 3;
		foreach(range('a','z') as $k => $v){
			if(isset($_REQUEST[$v])){
				if(is_array($_REQUEST[$v])){
					foreach($_REQUEST[$v] as $k2 => $v2){
                        $args[$v][$k2] = (is_array($_REQUEST[$v][$k2]))
                                         ? array_map('urldecode', $_REQUEST[$v][$k2])
                                         : urldecode($_REQUEST[$v][$k2]);
					}
				} 
               else{
					$args[$v] = urldecode($_REQUEST[$v]);
				}
			}
            else{
				$args[$v] = null;
			}
			if($k >= $argCount){
				break;
			}
		}
		if(function_exists('cleanInput')){
			$args = cleanInput($args);
			$_REQUEST = cleanInput($_REQUEST,'_REQUEST');
			$_POST = cleanInput($_POST,'_POST');
			$_GET = cleanInput($_GET,'_GET');
		}
		
		foreach($args as $k => $v){
            $this->ajax->{$k} = (is_array($v))? new Ext($v): $v;
		}
		return $args;
	}
	
 	/**
     * The createController method, instantiates an AJAX controller object.
     * @param string  $class
     * @param string  $function
     * @access private
     * @return AJAXController
     */	     	
	private function createController($class, $function){
		if(!$class){
			return false;
		}

        if($this->ajax->plugin($class, true)){
            $class = "CJAX\\Plugins\\{$class}\\Controllers\\{$class}";
        }
        else{
            $className = ucwords(AJAX_CD."/".$class, "/");
            $class = str_replace("/", "\\", $className);
        }
	    return new $class($this->ajax);
	}
    
 	/**
     * Static method main, it serves as a centralized entry point for AJAX requests by CJAX.
     * @access public
     * @return void
     */	     
    public static function main(){
        try{
            $base = realpath(__DIR__.'/..');
            defined('CJAX_ROOT') or define('CJAX_ROOT', $base);
            defined('AJAX_BASE') or define('AJAX_BASE', "{$base}/cjax/");
            defined('CJAX_HOME') or define('CJAX_HOME', "{$base}/cjax");
            defined('CJAX_CORE') or define('CJAX_CORE', "{$base}/cjax/core");   
            define('AJAX_CONTROLLER',1);
            if(!defined('AJAX_CD')){
                define('AJAX_CD', 'controllers');
            }  

            $ajax = CJAX::getInstance();
            $ajax->request->handleRequest();
            $ajax->pluginManager->initiate();
            $controller = $ajax->input('controller');
            if($controller){
                new self($controller);
            }   
        }
        catch(CJAXException $cje){
            $exitMessage = $cje->getMessage();
            $ajax = CJAX::getInstance();
            if($ajax->config->debug){
                $exitMessage = "Exception: {$exitMessage} @ ";
                $trace = $cje->getTrace();
                if(!empty($trace[0]['class'])){
                    $exitMessage .= "{$trace[0]['class']}->";
                }
                $exitMessage .= "{$trace[0]['function']}();";
            }
            exit($exitMessage);
        }
    }
}

AJAX::main();
$ajax = CJAX::getInstance();
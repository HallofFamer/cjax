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
*   File Last Changed:  04/22/2016           $     
**####################################################################################################    */ 

namespace CJAX\Core;

/**
 * The PluginManager class that stores and manages a collection of plugins and handles their operations.
 * @category CJAX
 * @package Core
 * @author Ordland Euroboros <halloffamer@mysidiarpg.com>
 * @copyright (c) 2008, CJ Galindo
 * @link https://github.com/ajaxboy/cjax
 * @version 6.0
 * @since 6.0
 * @final
 */

final class PluginManager{

    /**
     * The coreEvents property, stores an instance of injected CoreEvents object.
     * @access private 
     * @var CoreEvents
     */	     
    private $coreEvents;
    
    /**
     * The instances property, stores an array of instantiated plugin objects.
     * @access private 
     * @var array
     */	  
	private $instances = [];
	
    /**
     * The classes property, stores an array of plugin class names.
     * @access private 
     * @var array
     */	
	private $classes = [];
	
    /**
     * The aborted property, specifies an array of aborted plugins.
     * @access private 
     * @var array
     */	
	private $aborted = [];
    
    /**
     * The meta property, defines an array of plugin metadata.
     * @access private 
     * @var array
     */	    
	private $meta = [];
	
    /**
     * The dirs property, defines an array of plugin directories.
     * @access private 
     * @var array
     */	    
    private $dirs = [];
	
    /**
     * The readDir property, specifies recently read/processed plugin directories.
     * @access private 
     * @var array
     */    
    private $readDir = [];


	/**
     * The constructor for PluginManager class, creates an instance of PluginManager object.
	 * @param CoreEvents  $coreEvents
     * @access public
     * @return PluginManager
     */	    
    public function __construct(CoreEvents $coreEvents){
        $this->coreEvents = $coreEvents;
    }         
    
	/**
     * The isPlugin method, check if a plugin exists given its name.
	 * @param string  $pluginName
     * @access public
     * @return bool
     */	      
	public function isPlugin($pluginName){
		$plugins = $this->readDir(CJAX_HOME."/plugins/");
		if(isset($plugins[$pluginName])){
			return isset($plugins[$pluginName]);
		}
	}    
    
	/**
     * The getPlugin method, check if a plugin exists given its name.
	 * @param string  $pluginName
     * @param array  $params
     * @param string  $instanceId
     * @param bool  $loadController
     * @access public
     * @return Plugin
     */	     
	public function getPlugin($pluginName = null, $params = [], $instanceId = null, $loadController = false){
        if(isset($this->instances[$pluginName]) && is_object($this->instances[$pluginName])){
			return $this->instances[$pluginName];
		}
		if(!isset($this->classes[$pluginName])){
			$pluginClass = ucfirst($pluginName);
            $pluginClass = "\\CJAX\\Plugins\\{$pluginClass}\\{$pluginClass}";
			$this->classes[$pluginName] = $pluginClass;
		} 
        else{
			$pluginClass .= $this->classes[$pluginName]."\\".$this->classes[$pluginName];
		}
		if(!isset($this->instances[$pluginName]) || !is_object($this->instances[$pluginName])){
			if(!isset($params[1])){
				$plugin = $this->instances[$pluginName] = new $pluginClass($this->coreEvents);
				$plugin->params = [];
				if(!is_null($instanceId)){
					$plugin->id = $instanceId;
                    $plugin->entryIds[$instanceId] = $instanceId;
				}
				
				$plugin->dir = $this->dir($pluginName);
				$plugin->name = $pluginName;		
			} 
            else{
				$args = [];
				$params = $params[1];
				$paramRanges = range('a','f');
				foreach($paramRanges as $k => $v){
					$args[$v] = current($params);
					if($k >= count($params)){
						$args[$v] = null;
					} 
                    else{
						next($params);
					}
				}
				extract($args);
				$plugin = $this->instances[$pluginName] = new $pluginClass($this->coreEvents, ["a" => $a, "b" => $b, "c" => $c, "d" => $d, "e" => $e, "f" => $f]);
				$plugin->params  = $params;
				if(!is_null($instanceId)){
					$plugin->id = $instanceId;
					$plugin->entryIds[$instanceId] = $instanceId;
				}
				$plugin->dir = $this->dir($pluginName);
				$plugin->name = $pluginName;
			}
		} 
        else{
			$plugin = $this->instances[$pluginName];
		}
		$dir = $this->dir($pluginName).$plugin->controllersDir;
		$plugin->xml = $this->coreEvents->xmlObject($instanceId);
		$plugin->controllersDir = $dir;
		$plugin->controllerFile = $dir."/{$pluginName}.php";
		$plugin->name = $pluginName;
		return $plugin;
	}    
    
	/**
     * The instanceTriggers method, triggers a method call for a plugin object.
	 * @param Plugin  $plugin
     * @param array  $params
     * @access public
     * @return Plugin
     */	    
	public function instanceTriggers($plugin, $params){
		if(!$this->coreEvents->isAjaxRequest() && method_exists($plugin, 'onLoad') && $params){
			call_user_func_array([$plugin, 'onLoad'], $params);	
		} 
        elseif(method_exists($plugin, 'onAjaxLoad') && $params){
			call_user_func_array([$plugin, 'onAjaxLoad'],  $params);				
		}
	}    
    
	/**
     * The initiate method, initializes plugin manager's actions/operations.
     * @access public
     * @return void
     */	     
	public function initiate(){
		if($this->meta){
			return $this->meta;
		}
		$base = CJAX_HOME;    
		$plugins = $base."/plugins/";		
		$this->meta = $this->readDir($plugins);
	}

	/**
     * The isAborted method, checks if a plugin has been aborted.
     * @param string  $pluginName
     * @access public
     * @return bool
     */       
	public function isAborted($pluginName = null){
		if(isset($this->aborted[$pluginName])){
			return true;
		}
        if(isset($this->instances[$pluginName]) && $this->instances[$pluginName]->isAborted()){
            $this->aborted[$pluginName] = $this->instances[$pluginName];
            return true;
        }
        return false;
	}    
    
	/**
     * The trigger method, triggers plugin event handling.
     * @param string  $event
     * @param array  $params
     * @access public
     * @return bool
     */     
	public function trigger($event, $params = []){	
		if($this->classes){
			foreach($this->classes as $k => $v){
                $plugin = $this->coreEvents->plugin($v);			
				if(!$plugin || !$this->hasClass($k)){
					continue;
				}
				
				if($this->isAborted($k) || $plugin->exclude){
					continue;
				}
				if(method_exists($plugin, $event)){
					call_user_func_array([$plugin, $event], $params);
					if($plugin instanceof XmlItem){
						die('plugin:delete');
						$plugin->delete();
					}
				}
			}
		}
	}    
    
	/**
     * The file method, gets full path of a given plugin.
     * @param string  $pluginName
     * @access public
     * @return string
     */	    
	public function file($pluginName){
		return $this->meta[$pluginName]->file;		
	}
	
	/**
     * The method method, gets the method name of a given plugin.
     * @param string  $pluginName
     * @access public
     * @return string
     */    
	public function method($pluginName){
		return $this->meta[$pluginName]->method;
	}
	
	/**
     * The dir method, gets the path of plugin's directory.
     * @param string  $pluginName
     * @access public
     * @return string
     */    
	public function dir($pluginName = null){
		if(!$pluginName){
			$pluginName = $this->name;
		}
		return $this->dirs[$pluginName];
	}    
    
	/**
     * The readDir method, reads directory information for plugins.
     * @param string  $resource
     * @access public
     * @return array
     */        
	public function readDir($resource){
		if($this->readDir){
			return $this->readDir;
		}
		$dirs = scandir(str_replace("\\","/", $resource));
		unset($dirs[0], $dirs[1]);	
		$new = [];
		
		foreach($dirs as $k => $v){
			$name = preg_replace("/\..+$/", '', $v);		
			if(isset($new[$name])) {
				continue;
			}
			$obj = new Ext($v);
			$obj->file = "{$v}.js";
			$obj->method = $v;
			if(is_dir($resource.$v)){
				$this->dirs[$name] = $resource.$v.'/';
				$dir = $this->dirs[$name];
				
				if(file_exists($f = "{$dir}{$v}.php")){
					require_once $f;
					$class = $v;
					$parent = get_parent_class($class);
					if(!class_exists($class) || $parent != 'plugin'){
						$class = 'plugin_'.$v;
					} 					
					if(class_exists($class)){
                        $this->loadClass($obj, $class);
					}
				}
				$obj->file = "{$v}/{$obj->file}";
				$new[$name] = $obj;
			} 
            else{
				$this->dirs[$name] = $resource;
				$new[$name] = $obj;
				$dir = $resource;
			}
		}
		return $this->readDir = $new;
	}
    
	/**
     * The hasClass method, checks if a plugin has a custom class defined.
     * @param string  $pluginName
     * @access public
     * @return bool
     */         
	public function hasClass($pluginName){
		if(isset($this->classes[$pluginName])){
			return true;
		}
	}    
    
	/**
     * The loadClass method, loads plugin class if exists.
     * @param object  $obj
     * @param string  $class
     * @access public
     * @return void
     */     
    private function loadClass($obj, $class){
        $vars = get_class_vars($class);
        if(isset($vars['file'])){
            $obj->file = $vars['file'];
            $obj->method = preg_replace(["/\..+$/","/\.js$/"], '', $obj->file);
        }
        $this->classes[$v] = $class;

        if(method_exists($class, 'autoload')){
            call_user_func([$class,'autoload']);
        }
        if(!$this->coreEvents->isAjaxRequest()){
            if(method_exists($class, 'PageAutoload')){
                call_user_func([$class,'PageAutoload']);
            }
        } 
        else{
            if(method_exists($class, 'AjaxAutoload')){
                call_user_func([$class,'AjaxAutoload']);
            }
        }        
    }    
}
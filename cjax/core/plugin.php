<?php

/** ################################################################################################**   
* Copyright (c)  2008  CJ.   
* Permission is granted to copy, distribute and/or modify this document   
* under the terms of the GNU Free Documentation License, Version 1.2   
* or any later version published by the Free Software Foundation;   
* Provided 'as is' with no warranties, nor shall the author be responsible for any mis-use of the same.     
* A copy of the license is included in the section entitled 'GNU Free Documentation License'.   
*   
*   CJAX  6.0                $     
*   ajax made easy with cjax                    
*   -- DO NOT REMOVE THIS --                    
*   -- AUTHOR COPYRIGHT MUST REMAIN INTACT -   
*   Written by: CJ Galindo                  
*   Website: http://cjax.sourceforge.net                     $      
*   Email: cjxxi@msn.com    
*   Date: 2/12/2007                           $     
*   File Last Changed:  04/05/2016            $     
**####################################################################################################    */   

namespace CJAX\Core;

class Plugin extends Ext{

	/**
	 * 
	 * instancedd  to the plugin class
	 * @var unknown_type
	 */
	private static $instance;
    
    private $coreEvents;
    
	//xmlItem Object
	public $xml;
	
	/**
	 * Instances to plugins
	 */
	private static $_instances = [];
	
    /**
	 * 
	 * entries Ids from plugns
	 * @var unknown_type
	 */
	private static $_instancesIds = [];
	
	/**
	 * 
	 * Plugin has a class
	 * @var unknown_type
	 */
	private static $_instancesExist = [];
    
	/**
	* Plugins parameters
	 */
	private static $_instancesParams = [];
    
	/**
	* Default controllers directory to each plugin
	 */
	public $controllersDir = 'controllers';
	
	public $controllerFile = null;
	
	/**
	 * A executable string before the plugin is ran.
	 * @var unknown_type
	 */
	public $init = "function(){}";
	
	/**
	 * 
	 * Plugins that are aborted
	 * @var unknown_type
	 */
	private static $_aborted = [];
	
	/**
	 * 
	 * When needing  $loading in the contructor 
	 * @var unknown_type
	 */
	private static $_loadingPrefix = null;
    
	public $dir;
    
	public static $initiatePlugins = [];
    
	public $loading;
	/**
	 * 
	 * Plugins settings
	 * 
	 * @var unknown_type
	 */
	public $ajaxFile = false; //if true the it  will replace any string that start with ajax.php to a full url.

	/**
	 * 
	 * javascript file name,
	 * by default is the plugin's name but can be different.
	 * @var unknown_type
	 */
	public $file = null;
	
	/**
	 * 
	 * Plugin arguments
	 * @var unknown_type
	 */
	public $params;
	
	/**
	 * 
	 * class pertaining to an addon
	 * @var unknown_type
	 */
	public $class;
	
	/**
	 * 
	 * If using Exec event, store the element_id.
	 * @var unknown_type
	 */
	public $element_id;
	
	/**
	 * 
	 * If a  plugin is used more than once on the page, assigns an id
	 * in wished to do modifications in later execution
	 * @var integer
	 */
	public $_id;
    
	public $id;
	
    private static $_dirs = [];
	
    private static $_initiated;
	
    private static $readDir = [];
	
	/**
	 * 
	 * For session variables use cookie?
	 * if false it will use sessions.
	 * 
	 * @var boolean
	 */
	private $cookie = false;

    public function __construct(CoreEvents $coreEvents, $array = []){
        parent::__construct($array);
        $this->coreEvents = $coreEvents;
    }
    
	/**
	 * 
	 * Delete plugin entries
	 */
	public function abort($pluginName = null){
		if(!$pluginName){
			$pluginName = $this->loading;
		}
		if(self::$_instancesIds){
			if(isset(self::$_instancesIds[$pluginName])){
				$entries = self::$_instancesIds[$pluginName];
				foreach($entries as $v){
					$this->deleteEntry($v);
				}
			}
		}
		self::$_aborted[$pluginName] = true;
 	}
 	
 	/*
 	 * can preload the plugin file if plugin is not being fired.
 	 */
	public function preload(){
		$file = preg_replace('/.+\/+/', '', $this->file($this->loading));
		$this->import($file);
	}
	
	public function xmlObject(){
        return $this->coreEvents->xmlObjects($this->_id);
	}
	
	/**
	 * 
	 * mirrors xmlItem::xml()
	 */
	public function xml(){
		return $this->coreEvents->xmlObjects($this->_id)->xml();
	}
	
	/**
	 * 
	 * mirrors xmlItem::output()
	 */
	public function output(){
		return $this->coreEvents->xmlObjects($this->_id)->output();
	}	
	
	/**
	 * 
	 * mirros xmlItem::delete()
	 */
	public function delete(){
		return $this->coreEvents->xmlObjects($this->_id)->delete();
	}
	
	public function trigger($event, $params = []){	
		if(self::$_instancesExist){
			foreach(self::$_instancesExist as $k => $v){
                $plugin = $this->coreEvents->plugin($v);			
				if(!$plugin || !self::hasClass($k)){
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
	
	public function isAborted($pluginName = null){
		$plugin = $this->getInstance();
		if(!$pluginName){
			$pluginName = $plugin->loading;
		}
		if(isset(self::$_aborted[$pluginName])){
			return true;
		}
	}
	
	/**
	 * 
	 * @deprecated
	 * @param unknown_type $apiObj
	 */
	public function prevent($apiObj){
		$this->xml->callback = $apiObj;
	}
	
	/**
	 * 
	 * pass apis, and they will be accesible in javascripot through this.callback;
	 * 
	 * @param unknown_type $apiObj
	 */
	public function callback($apiObj){
		$this->xml->callback = $apiObj;
		CoreEvents::$cache = $this->coreEvents->callbacks(CoreEvents::$cache);
	}
	
	public function imports($files = [], &$data = []){
		$data['plugin_dir'] = $this->loading;
		$ajax = CJAX::getInstance();		
		return $ajax->imports($files, $data);
	}
	
	/**
	 * 
	 * Impor javascript and css files
	 * @param mixed $file
	 * @param integer $loadTime - in milliseconds
	 */
	public function import($file , $loadTime = 0, $onInit = false){
		$ajax = CJAX::getInstance();	
		if(!is_array($file) && preg_match("/^https?/", $file)){
			$data['file'] = $file;
		} 
        else{
			$data['plugin_dir'] = $this->loading;
			$data['file'] = $file;
		}
		
		$data['time'] = (int)$loadTime;
			
			
		if($onInit){
			$ajax->initExtra[] = $data;
		} 
        else{
			$this->coreEvents->first();//forces this command to be executed before any other
			$ajax->import($data);
		}
	}
	
	public function waitFor($file){
		$this->coreEvents->xmlObjects($this->_id)->waitfor = $file;
		$this->coreEvents->simpleCommit();
	}
	
	public function isPlugin($pluginName){
		$plugins = self::readDir(CJAX_HOME."/plugins/");
		if(isset($plugins[$pluginName])) {
			return isset($plugins[$pluginName]);
		}
	}
	
	public function setVars($setting, $value){
		if(empty(self::$_instancesIds) || !isset(self::$_instancesIds[$this->loading])){
			return;
		} 
        else{
			$instances  = self::$_instancesIds[$this->loading];
		}
		
		foreach($instances as $v){
			$this->setVar($setting, $value, $v);
		}
	}
	
	/**
	 * 
	 * Set variables that can be accessed as this.var
	 */
	private function setVar($setting, $value, $instanceId){
		if(!isset(CoreEvents::$cache[$instanceId])){
			return;
		}
		$item = CoreEvents::$cache[$instanceId];
		
		if(is_array($value)){
			$value  = $this->coreEvents->mkArray($value);
		}
		$item['extra'][$setting] = $value;		
		$this->coreEvents->updateCache($instanceId, $item);
	}
	
	/**
	 * 
	 * Updates parameters using plugin class
	 */
	public function set($setting, $value, $instanceId = null){
		if($this->isAborted($this->loading)){
			return;
		}
		$params = range('a','z');	
		if(!in_array($setting, $params)){
			return $this->setVars($setting,$value);
		}
		
		if(!is_null($instanceId)){
			$item = CoreEvents::$cache[$instanceId];
			$item['data'][$setting] = $value;			
			CoreEvents::UpdateCache($instanceId, $item);
		} 
        else{			
			if(!isset(self::$_instancesIds[$this->loading])){
				return;
			}			
			$instances  = self::$_instancesIds[$this->loading];		
			if(!$instances){
				return false;
			}
			if(count($instances)==1){
				return $this->set($setting ,$value, implode($instances));
			}
			foreach ($instances as  $v){
				$this->set($setting ,$value, $v);
			}
		}
	}
	
	public static function getPluginInstance(CoreEvents $coreEvents, $plugin = null, $params = [], $instanceId = null, $loadController = false){
        if(isset(self::$_instances[$plugin]) && is_object(self::$_instances[$plugin])){
			return self::$_instances[$plugin];
		}
        $pluginObject = new self($coreEvents);
		if(!isset(self::$_instancesExist[$plugin])){
			$pluginClass = ucfirst($plugin);
            $pluginClass = "\\CJAX\\Plugins\\{$pluginClass}\\{$pluginClass}";
			self::$_instancesExist[$plugin] = $pluginClass;
		} 
        else{
			$pluginClass .= self::$_instancesExist[$plugin]."\\".self::$_instancesExist[$plugin];
		}
		
		if(!isset(self::$_instances[$plugin]) || !is_object(self::$_instances[$plugin])){
			if(!isset($params[1])){
				self::$_loadingPrefix = $plugin;
				$_plugin = self::$_instances[$plugin] = new $pluginClass($coreEvents);
				$_plugin->params = [];
				if(!is_null($instanceId)){
					$_plugin->_id = $instanceId;
					$_plugin->id = $instanceId;
					self::$_instancesIds[$plugin][$instanceId] = $instanceId;
				}
				
				$_plugin->dir = $pluginObject->dir($plugin);
				$_plugin->loading = $plugin;				
				self::$_loadingPrefix = null;
			} 
            else{
				$args = [];
				$params = $params[1];
				$_params = range('a','f');
				foreach($_params as $k => $v){
					$args[$v] = current($params);
					if($k >= count($params)){
						$args[$v] = null;
					} 
                    else{
						next($params);
					}
				}
				extract($args);
				self::$_loadingPrefix = $plugin;
				$_plugin = self::$_instances[$plugin] = new $pluginClass($coreEvents, $a, $b, $c, $d, $e, $f);
				$_plugin->params  = $params;
				if(!is_null($instanceId)){
					$_plugin->_id = $instanceId;
					$_plugin->id = $instanceId;
					self::$_instancesIds[$plugin][$instanceId] = $instanceId;
				}
				$_plugin->dir = $pluginObject->dir($plugin);
				$_plugin->loading = $plugin;
				self::$_loadingPrefix = null;
			}
		} 
        else{
			$_plugin = self::$_instances[$plugin];
		}
		$dir = $pluginObject->dir($plugin).$_plugin->controllersDir;
		$_plugin->xml = $coreEvents->xmlObject($instanceId);
		$_plugin->controllersDir = $dir;
		$_plugin->controllerFile = $dir."/{$plugin}.php";
		$_plugin->loading = $plugin;
		return $_plugin;
	}
	
	public function instanceTriggers($plugin , $params){
		if(!$this->coreEvents->isAjaxRequest() && method_exists($plugin, 'onLoad') && $params){
			call_user_func_array([$plugin, 'onLoad'], $params);	
		} 
        elseif(method_exists($plugin, 'onAjaxLoad') && $params){
			call_user_func_array([$plugin, 'onAjaxLoad'],  $params);				
		}
	}
	
	public function deleteEntry($entryId){
		if(isset(CoreEvents::$cache[$entryId])){
			unset(CoreEvents::$cache[$entryId]);
		}
		self::$_instancesIds[$this->loading] = [];
	}
	
	public function hasClass($plugin){
		if(isset(self::$_instancesExist[$plugin])){
			return true;
		}
	}
	
	public function getInstance($plugin = null, $params = [], $instanceId  = null){
		if(is_object(self::$instance)){
			return self::$instance;
		}
		if(!$plugin){
			$plugin = new Plugin($this->coreEvents);
			return self::$instance = $plugin;
		}		
		if($plugin = self::getPluginInstance($this->coreEvents, $plugin, $params, $instanceId)){
			return $plugin;
		}		
	}
	
	public static function initiatePlugins(){
		if(self::$initiatePlugins){
			return self::$initiatePlugins;
		}
		$base = CJAX_HOME;    
		$plugins = $base."/plugins/";		
		self::$initiatePlugins = self::readDir($plugins);
	}
	
	/**
	 * 
	 * Saves values in a cookie or session
	 * @param unknown_type $setting
	 * @param unknown_type $value
	 */
	public function save($setting, $value, $prefix = null){
		if(!$prefix){
			$prefix = $this->loading;
		}
		if(!$prefix && self::$_loadingPrefix){
			$prefix = self::$_loadingPrefix;
		}
		if($prefix){
			$setting = $prefix.'_'.$setting;
		}	
		return $this->coreEvents->save($setting, $value, $this->cookie);
	}
	
	/**
	 * 
	 * get settings saved in cookies
	 */
	public function get($setting, $prefix = null){
		if(!$prefix){
			$prefix = $this->loading;
		}
		if(!$prefix && self::$_loadingPrefix){
			$prefix = self::$_loadingPrefix;
		}
		if($prefix){
			$setting = $prefix.'_'.$setting;
		}
		return $this->coreEvents->getSetting($setting, $prefix);
	}
		
	/**
	 * get the full path of a plugin
	 */
	public function file($name){
		return self::$initiatePlugins[$name]->file;		
	}
	
	public function init(){
		return $this->init;
	}
	
	public function method($method){
		return self::$initiatePlugins[$method]->method;
	}
	
	public function dir($plugName = null){
		if(!$plugName){
			$plugName = $this->loading;
		}
		return self::$_dirs[$plugName];
	}
	
	public static function readDir($resource){
		if(self::$readDir){
			return self::$readDir;
		}
		$dirs = scandir(str_replace("\\","/",$resource));
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
				self::$_dirs[$name] = $resource.$v.'/';
				$dir = self::$_dirs[$name];
				
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
				self::$_dirs[$name] = $resource;
				$new[$name] = $obj;
				$dir = $resource;
			}
		}
		return self::$readDir = $new;
	}
    
    private static function loadClass($obj, $class){
        $ajax = CJAX::getInstance();
        $vars = get_class_vars($class);
        if(isset($vars['file'])){
            $obj->file = $vars['file'];
            $obj->method = preg_replace(["/\..+$/","/\.js$/"], '', $obj->file);
        }
        self::$_instancesExist[$v] = $class;

        if(method_exists($class, 'autoload')){
            call_user_func([$class,'autoload']);
        }
        if(!$ajax->isAjaxRequest()){
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
    
	public function  __get($setting){
		return $this->get($setting);		
	}    
    
	/**
	 * 
	 * Set variables
	 */
	public function __set($setting, $value){
		$this->setVars($setting, $value);
	}       
    
   	/**
	 * 
	 * Handle right handlers chain apis
	 * 
	 * @param unknown_type $api
	 * @param unknown_type $args
	 */
	public function __call($api, $args){
		return call_user_func_array([$this->xml, $api], $args);
	}      
}
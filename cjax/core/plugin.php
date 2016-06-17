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
*   File Last Changed:  04/22/2016            $     
**####################################################################################################    */   

namespace CJAX\Core;

/**
 * The Plugin class that represents a basic unit of CJAX's plugin system.
 * Since CJAX 6.0, Plugin class is lightweight and only contains information of one specific plugin object.
 * @category CJAX
 * @package Core
 * @author Ordland Euroboros <halloffamer@mysidiainc.com>
 * @copyright (c) 2008, CJ Galindo
 * @link https://github.com/ajaxboy/cjax
 * @version 6.0
 * @since 1.0
 * @api
 */

class Plugin{
    
    /**
     * The coreEvents property, stores an instance of injected CoreEvents object.
     * @access protected 
     * @var CoreEvents
     */	    
    protected $coreEvents;     
    
    /**
     * The cookie property, it will use cookie instead of session if true.
     * @access protected
     * @var bool
     */       
	protected $cookie = false;  
    
    /**
     * The aborted property, specifes if the plugin is aborted.
     * @access protected
     * @var bool
     */     
    protected $aborted = false;
    
    /**
     * The ajaxFile property, it will replace any string starting with ajax.php to full url if true.
     * @access public 
     * @var bool
     */      
	public $ajaxFile = false;      
    
    /**
     * The id property, assigns an id if the plugin is used for more than once on a page.
     * This id will be useful to do modifications in later execution.
     * @access public 
     * @var int
     */   
	public $id;    
    
    /**
     * The name property, defines the plugin name and the identity key for a plugin.
     * @access public 
     * @var string
     */      
	public $name;   
    
    /**
     * The dir property, specifies the main directory for a given plugin.
     * @access public 
     * @var string
     */      
	public $dir; 
    
    /**
     * The file property, defines the name of javascript file name for plugin.
     * By default it is the plugin's name, but can be different.
     * @access public 
     * @var string
     */      
	public $file = null;    
    
    /**
     * The controllersDir property, specifies the controllers directory for this plugin.
     * By default it is 'controllers', but can be different.
     * @access public 
     * @var string
     */          
	public $controllersDir = 'controllers';
	
    /**
     * The controllersFile property, defines the controllers file name for this plugin.
     * @access public 
     * @var string
     */      
	public $controllerFile = null;

    /**
     * The init property, which is an executable string runs before plugin is created.
     * @access public 
     * @var string
     */        
	public $init = "function(){}";    
    
    /**
     * The xml property, stores an instance of XmlItem object for this plugin.
     * @access public 
     * @var XmlItem
     */      
	public $xml;   
	
    /**
     * The params property, specifies the plugin arguments.
     * @access public 
     * @var array
     */        
	public $params;
	
    /**
     * The class property, defines a class pertaining to an addon.
     * @access public 
     * @var string
     */        
	public $class;
	
    /**
     * The elementId property, stores the element Id associated with plugin event.
     * @access public 
     * @var string
     */      
	public $elementId;     
	  
    /**
     * The entryIds property, stores an array of entry ids for this plugin.
     * @access public 
     * @var array
     */      
    public $entryIds = [];
    

	/**
     * The constructor for Plugin class, creates an instance of Plugin object.
	 * @param CoreEvents  $coreEvents
     * @param array  $array
     * @access public
     * @return Plugin
     */	     
    public function __construct(CoreEvents $coreEvents, $array = []){
        $this->coreEvents = $coreEvents;
		if($array && (is_array($array) || is_object($array))){
			foreach($array as $k => $v){
				$this->$k = $v;
			}
		}
    }
    
	/**
     * The init method, fetches the executable string for $init.
     * @access public
     * @return string
     */	       
	public function init(){
		return $this->init;
	}
	    
	/**
     * The isAborted method, checks if this plugin has been aborted.
     * @access public
     * @return bool
     */       
	public function isAborted(){
        return $this->aborted;
	}    

	/**
     * The abort method, terminates plugin executation and deletes plugin entry ids.
     * @access public
     * @return void
     */     
	public function abort(){
		if($this->entryIds){
			foreach($this->entryIds as $entryId){
				$this->deleteEntry($entryId);
            }
            $this->entryIds = [];
		}
        $this->aborted = true;
 	}
	
	/**
     * The xmlObject method, gets the XmlItem object associated with this plugin.
     * @access public
     * @return XmlItem
     */      
	public function xmlObject(){
        return $this->coreEvents->xmlObjects($this->id);
	}

	/**
     * The xml method, mirrors XmlItem's xml() method.
     * @access public
     * @return object
     */     
	public function xml(){
		return $this->coreEvents->xmlObjects($this->id)->xml();
	}
	
	/**
     * The output method, mirrors XmlItem's output() method.
     * @access public
     * @return object
     */      
	public function output(){
		return $this->coreEvents->xmlObjects($this->id)->output();
	}	

	/**
     * The delete method, mirrors XmlItem's delete() method.
     * @access public
     * @return object
     */    
	public function delete(){
		return $this->coreEvents->xmlObjects($this->id)->delete();
	}
    
	/**
     * The import method, imports and caches a JavaScript or CSS file.
     * @param string  $file
     * @param int  $loadTime
     * @param bool  $onInit
     * @access public
     * @return void
     */        
	public function import($file, $loadTime = 0, $onInit = false){
		if(!is_array($file) && preg_match("/^https?/", $file)){
			$data['file'] = $file;
		} 
        else{
			$data['plugin_dir'] = $this->name;
			$data['file'] = $file;
		}
		
		$data['time'] = (int)$loadTime;						
		if($onInit){
            $this->coreEvents->initExtra[] = $data;
		} 
        else{
			$this->coreEvents->first();//forces this command to be executed before any other
            $this->coreEvents->import($data);
		}
	}    
    
	/**
     * The imports method, imports and caches JavaScript or CSS files.
     * @param array  $files
     * @param array  $data
     * @access public
     * @return void
     */    	    
	public function imports($files = [], &$data = []){
		$data['plugin_dir'] = $this->name;
        $this->coreEvents->imports($files, $data);
	}	
	
	/**
     * The waitFor method, wait for javascript file to be loaded before firing plugin.
     * @param string  $file
     * @access public
     * @return void
     */    	
	public function waitFor($file){
		$this->coreEvents->xmlObjects($this->id)->waitfor = $file;
		$this->coreEvents->simpleCommit();
	}       

	/**
     * The get method, gets a setting that has been saved with save() function. 
     * @param string  $setting
     * @param string  $prefix
     * @access public
     * @return mixed
     */       
	public function get($setting, $prefix = null){
		if(!$prefix){
			$prefix = $this->name;
		}
		if($prefix){
			$setting = $prefix.'_'.$setting;
		}
		return $this->coreEvents->getSetting($setting, $prefix);
	}    

	/**
     * The set method, updates parameters using plugin class. 
     * @param string  $setting
     * @param mixed  $value
     * @param int  $instanceId
     * @access public
     * @return void
     */        
	public function set($setting, $value, $instanceId = null){
		if($this->aborted){
			return;
		}
		$params = range('a','z');	
		if(!in_array($setting, $params)){
			return $this->setVars($setting, $value);
		}

        $cache = $this->coreEvents->getCache();
		if(!is_null($instanceId)){
            $item = $cache->get($instanceId);
			$item['data'][$setting] = $value;
            $this->coreEvents->updateCache($instanceId, $item);
		} 
        else{			
			if(!$this->entryIds){
				return;
			}	
			if(count($this->entryIds) == 1){
				return $this->set($setting, $value, implode($this->entryIds));
			}
			foreach($this->entryIds as $entryId){
				$this->set($setting, $value, $entryId);
			}
		}
	}    
    
	/**
     * The setVars method, set variables that can be accessed as this.var in javascript for all instance ids.
     * @param string  $setting
     * @param mixed  $value
     * @access public
     * @return void
     */        
	public function setVars($setting, $value){
		if(!$this->entryIds){
			return;
		} 		
		foreach($this->entryIds as $entryId){
			$this->setVar($setting, $value, $entryId);
		}
	}

	/**
     * The setVar method, set variables that can be accessed as this.var in javascript.
     * @param string  $setting
     * @param mixed  $value
     * @param int  $instanceId
     * @access private
     * @return void
     */     
	private function setVar($setting, $value, $instanceId){
		$item = $this->coreEvents->getCache()->get($instanceId);
		if(!$item){
            return;
        }
        
		if(is_array($value)){
			$value  = $this->coreEvents->mkArray($value);
		}
		$item['extra'][$setting] = $value;		
		$this->coreEvents->updateCache($instanceId, $item);
	}

	/**
     * The save method, saves values in a cookie or session for future use.
     * @param string  $setting
     * @param mixed  $value
     * @param string  $prefix
     * @access public
     * @return void
     */         
	public function save($setting, $value, $prefix = null){
		if(!$prefix){
			$prefix = $this->name;
		}
		if($prefix){
			$setting = $prefix.'_'.$setting;
		}	
		return $this->coreEvents->save($setting, $value, $this->cookie);
	}
	
	/**
     * The deleteEntry method, deletes an entry id for plugin.
     * @param int  $entryId
     * @access public
     * @return void
     */       
	public function deleteEntry($entryId){
        $this->coreEvents->getCache()->removeCache($entryId);
	}     

	/**
     * The callback method, assigns an API as javascript callback function.
     * @param object  $apiObj
     * @access public
     * @return void
     */       
	public function callback($apiObj){
		$this->xml->callback = $apiObj;
        $cache = $this->coreEvents->getCache();
        $cache->setCache($this->coreEvents->callbacks($cache->getCache()));
	}     
    
	/**
     * The prevent method, intercepts an API and prevent it from being fired.
     * @param object  $apiObj
     * @access public
     * @return void
     */     
	public function prevent($apiObj){
		$this->xml->callback = $apiObj;
	}    
 
	/**
     * The magic method __get, dynamically gets a setting/parameter for plugin.
	 * @param string  $setting
     * @access public
     * @return mixed
     */	    
	public function __get($setting){
		return $this->get($setting);		
	}    

	/**
     * The magic method __set, dynamically sets a parameter/setting for plugin.
	 * @param string  $setting
     * @param mixed  $value
     * @access public
     * @return void
     */	    
	public function __set($setting, $value){
		$this->setVars($setting, $value);
	}       

	/**
     * The magic method __call, carries out right handlers chain APIs.
	 * @param string  $api
     * @param array  $args
     * @access public
     * @return void
     */	     
	public function __call($api, $args){
        $argCount = count($args);
        switch($argCount){
            case 0: 
                return $this->xml->$api();
            case 1:
                return $this->xml->$api($args[0]);
            case 2:
                return $this->xml->$api($args[0], $args[1]);
            case 3:
                return $this->xml->$api($args[0], $args[1], $args[2]);
            case 4:
                return $this->xml->$api($args[0], $args[1], $args[2], $args[3]);
            default:
                return call_user_func_array([$this->xml, $api], $args);
        }		
	}     
}
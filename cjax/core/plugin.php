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

class Plugin extends Ext{
    
	/**
	 * 
	 * If a plugin is used more than once on the page, assigns an id
	 * in wished to do modifications in later execution
	 * @var integer
	 */
	public $id;    
    
	public $name;   
    
	public $dir; 
    
	/**
	 * 
	 * javascript file name,
	 * by default is the plugin's name but can be different.
	 * @var unknown_type
	 */
	public $file = null;    
    
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
    
	//xmlItem Object
	public $xml;   
	
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
	 * Plugins settings
	 * 
	 * @var unknown_type
	 */
	public $ajaxFile = false; //if true the it  will replace any string that start with ajax.php to a full url. 
    
    protected $coreEvents;     
    
	/**
	 * 
	 * For session variables use cookie?
	 * if false it will use sessions.
	 * 
	 * @var boolean
	 */
	protected $cookie = false;  
    
    protected $aborted = false;
	
    /**
	 * 
	 * entries Ids for this plugin.
	 * @var array
	 */    
    public $entryIds = [];
    

    public function __construct(CoreEvents $coreEvents, $array = []){
        parent::__construct($array);
        $this->coreEvents = $coreEvents;
    }
    
	public function init(){
		return $this->init;
	}
	    
    
	public function isAborted(){
        return $this->aborted;
	}    
    
	/**
	 * 
	 * Delete plugin entries
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
	
	public function xmlObject(){
        return $this->coreEvents->xmlObjects($this->id);
	}
	
	/**
	 * 
	 * mirrors xmlItem::xml()
	 */
	public function xml(){
		return $this->coreEvents->xmlObjects($this->id)->xml();
	}
	
	/**
	 * 
	 * mirrors xmlItem::output()
	 */
	public function output(){
		return $this->coreEvents->xmlObjects($this->id)->output();
	}	
	
	/**
	 * 
	 * mirros xmlItem::delete()
	 */
	public function delete(){
		return $this->coreEvents->xmlObjects($this->id)->delete();
	}
	
	/**
	 * 
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
		$data['plugin_dir'] = $this->name;
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
			$data['plugin_dir'] = $this->name;
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
		$this->coreEvents->xmlObjects($this->id)->waitfor = $file;
		$this->coreEvents->simpleCommit();
	}
	
	/**
	 * 
	 * get settings saved in cookies
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
	 * 
	 * Updates parameters using plugin class
	 */
	public function set($setting, $value, $instanceId = null){
		if($this->aborted){
			return;
		}
		$params = range('a','z');	
		if(!in_array($setting, $params)){
			return $this->setVars($setting, $value);
		}

		if(!is_null($instanceId)){
			$item = CoreEvents::$cache[$instanceId];
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
    
	public function setVars($setting, $value){
		if(!$this->entryIds){
			return;
		} 		
		foreach($this->entryIds as $entryId){
			$this->setVar($setting, $value, $entryId);
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
	 * Saves values in a cookie or session
	 * @param unknown_type $setting
	 * @param unknown_type $value
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
	
	public function deleteEntry($entryId){
		if(isset(CoreEvents::$cache[$entryId])){
			unset(CoreEvents::$cache[$entryId]);
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
	 * @param string $api
	 * @param array $args
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
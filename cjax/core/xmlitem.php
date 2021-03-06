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
*   File Last Changed:  06/18/2016            $     
**####################################################################################################    */  


namespace CJAX\Core;

/**
 * The XmlItem class that handles fetching/saving XML data for AJAX request.
 * @category CJAX
 * @package Core
 * @author CJ Galindo <cjxxi@msn.com>
 * @copyright (c) 2008, CJ Galindo
 * @link https://github.com/ajaxboy/cjax
 * @version 6.0
 * @since 1.0
 */

class XmlItem{

    /**
     * The coreEvents property, stores an instance of injected CoreEvents object.
     * @access private
     * @var CoreEvents
     */	    
	private $coreEvents;
    
    /**
     * The selector property, defines the name of selector method.
     * @access public 
     * @var string
     */	
	public $selector;
    
    /**
     * The id property, specifies the element ID associated with XmlItem.
     * @access public 
     * @var string
     */	    
	public $id = null;
    
    /**
     * The name property, specifies the element name associated with XmlItem.
     * @access public 
     * @var string
     */	     
	public $name = null;
	
    /**
     * The type property, defines the element type associated with XmlItem.
     * @access public 
     * @var string
     */	      
    public $type = null;
	
	/**
	 * The buffer property, stores any extra data that can be added through the xml item to other Exec events.
     * @access public
	 * @var mixed
	 */
	public $buffer = [];
    
	/**
	 * The api property, defines a list of available selector methods for XmlItem.
     * @access private
	 * @var array
	 */    
	private $api = ['overlay', 'overlayContent', 'call', 'form', 'import', 'AddEventTo', 'Exec', 
                    'click', 'change', 'update', 'property', 'keyup', 'keydown', 'keypress', 'mouseover', 
                    'mouseout', 'mouseenter', 'mouseleave', 'mousedown', 'mouseup', 'mousemove',
                    'drag', 'dragend', 'dragenter', 'dragleave', 'dragover', 'dragstart', 'drop',
		            'blur', 'success', 'warning', 'process', '_message', 'error', 'location'];
	
	/**
	 * The cache property, stores cache used for AJAX requests.
     * @access public
	 * @var array
	 */       
	public $cache = [];
	
    
	/**
     * The constructor for XmlItem class, creates an instance of XmlItem object.
	 * @param CoreEvents  $coreEvents
     * @param string  $xmlId
     * @param string  $name
     * @param string  $type
     * @access public
     * @return XmlItem
     */		    
	public function __construct(CoreEvents $coreEvents, $xmlId, $name = null, $type = null){
        $this->coreEvents = $coreEvents;
		$this->name = $name;
		$this->id = (int)$xmlId;
		$this->type = $type;
	}	
 	
	/**
     * The attach method, adds a list of callback methods to this XmlItem.
	 * @param array  $callbacks
     * @access public
     * @return void
     */	    
	public function attach($callbacks){
		$xml = $this->xml();
		$cb = $this->coreEvents->processCache($callbacks);
		$xml['stack'] = $this->coreEvents->mkArray($cb);
        $cache = $this->coreEvents->getCache();
        $cache->set($this->id, $xml);
        
		foreach($callbacks as $k2 => $v2){
            $cache->delete($k2);
		}
	}   
    
	/**
     * The callback method, adds a callback to XmlItem.
	 * @param object  $xmlObj
     * @param mixed  $fn
     * @access public
     * @return void
     */	     
	public function callback($xmlObj, $fn = null){
		$this->callback = $xmlObj;
	}
	
	/**
     * The delete method, removes this XmlItem from CJAX cache so it won't be available for AJAX request.
     * @access public
     * @return void
     */	        
	public function delete(){
		if(!is_null($this->id)){
			$this->coreEvents->removeCache($this->id);
		}
	}
	
	/**
     * The next method, fetches the next available Xml object.
     * @param object  $xmlObj
     * @access public
     * @return void
     */	     
	public function next($xmlObj){
		$xmlObjects = $this->coreEvents->xmlObjects();
		$found = false;
		foreach($xmlObjects as $v){
			if($v->id == $xmlObj->id){
				$found = true;
				continue;
			}
			if($found){
				return $v;
			}
		}
	}
	
	/**
     * The xml method, retrieves an Xml object from CoreEvents' cache list.
     * @param string  $id
     * @access public
     * @return object
     */	      
	public function xml($id = null){
		$id or $id = $this->id;
		if(!is_null($id)){
            return $this->coreEvents->getCache()->get($id);
		}
	}
    	
	/**
     * The magic method __set, dynamically creates properties for XmlItem class.
	 * @param string  $setting
     * @param mixed  $value
     * @access public
     * @return void
     */		    
	public function __set($setting, $value){
        $cache = $this->coreEvents->getCache();
		if($value instanceof Plugin){
			if(method_exists($value, 'callbackHandler') && $value->callbackHandler($value->xml, $this, $setting)){
			    return $value;
		    }
            
			switch($setting){
				case 'waitFor':
					$value = $value->id;
				    break;
				case 'callback':					
					if(isset($this->coreEvents->callbacks[$value->id])){					
						$cb = $this->coreEvents->callbacks[$value->id];
						$cb = $this->coreEvents->processCache($cb);
                        $cache->cache[$value->id]['callback'] = $this->coreEvents->mkArray($cb, 'json', true);						
						$this->coreEvents->callbacks[$this->id][$value->id] = $cache->cache[$value->id];
						$value->delete();
					} 
                    else{
						$this->coreEvents->callbacks[$this->id][$value->id][] = $cache->cache[$value->id];
						$value->delete();
					}					
					return;
				default:
					return;
			}
			
		} 
		
		if(in_array($this->coreEvents->lastCmd, $this->api)){
			if(is_object($value)){
				$this->coreEvents->callbacks[$this->id][$value->id] = $cache->cache[$value->id];
			} 
            else{
				$this->coreEvents->callbacks[$this->id][$value] = $cache->cache[$value];
			}
		} 
        else{
			$event = $cache->cache[$this->id];
			$event[$setting] = $value;
			$cache->cache[$this->id] = $event;
			if($setting == 'waitFor'){
				$cache->cache[$value]['onwait'][$this->id] = $this->xml();
				$this->delete();
			}
			$this->coreEvents->simpleCommit();
		}
		
	}
	
	/**
     * The magic method __call, allows CJAX to call custom javascript function from PHP.
	 * @param string  $fn
     * @param array  $args
     * @access public
     * @return void
     */	    
	public function __call($fn, $args){
		if(isset($args['do'])){
			return true;
		}
        
		$ajax = CJAX::getInstance();		
		if($this->coreEvents->isPlugin($fn)){
			$lastCmd = $this->coreEvents->lastCmd;
			$plugin = call_user_func_array([$ajax, $fn], $args);
			if(method_exists($plugin, 'rightHandler')){
				$plugin->rightHandler($lastCmd, $args, $this);
			}
			return $plugin;
		}
		if(in_array($fn,$this->api)){
			$this->callback = call_user_func_array([$ajax, $fn], $args);
			return $this;
		}
		
		if($this->selector){
			$arguments[] = $this->selector;
			$arguments = array_merge($arguments, $args);
			$args = $arguments;
		}
		$params = range('a','z');
		$pParams = [];
		if($args){
			do{
				$pParams[current($params)] = $args[key($args)];				
			}while(next($args) && next($params));
		}		
		$data = ['do' => '_fn', 'fn' => $fn, 'fn_data' => $pParams];
		return $this->coreEvents->xmlItem($this->coreEvents->xml($data),'xmlItem_fn');
	}    
}
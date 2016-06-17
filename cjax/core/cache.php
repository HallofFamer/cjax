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
*   File Last Changed:  06/16/2016           $     
**####################################################################################################    */ 

namespace CJAX\Core;

/**
 * The Cache class, stores a cache for AJAX request.
 * @category CJAX
 * @package Core
 * @author CJ Galindo <cjxxi@msn.com>
 * @copyright (c) 2008, CJ Galindo
 * @link https://github.com/ajaxboy/cjax
 * @version 6.0
 * @since 1.0
 */

class Cache{
    
    /**
     * The cache property, stores a list of the actual cache content.
     * @access public 
     * @var array
     */	    
	public $cache = [];
    
    /**
     * The actions property, stores a list of the actions for caching.
     * @access public 
     * @var array
     */	    
    public $actions = [];
    
    /**
     * The lastCache property, holds cache set for to execute last. Use flag $ajax->flag('last'); to store commands.
	 * This makes it so that commands even if called first can be executed last.
     * @access public 
     * @var array
     */	     
    public $lastCache = [];
    
    /**
     * The caching property, determines if caching is enabled.
     * @access public 
     * @var bool
     */	     
    private $caching;    
	

    public function __construct($caching){
        $this->caching = $caching;
    }
    
    public function getCache(){
        return $this->cache;
    }  
    
    public function setCache($cache = []){
        $this->cache = $cache;
    }

    public function getActions(){
        return $this->actions;
    }    
    
    public function setActions($actions = []){
        $this->actions = $actions;
    }
    
    public function getLastCache(){
        return $this->lastCache;
    }
    
    public function get($cacheId){
        return $this->cache[$cacheId];
    }
    
    public function getCacheId(){
		$count = 0;
		if($this->cache){
			end($this->cache);
			$count = key($this->cache);
			reset($this->cache);
		}		
		return $count;        
    }
    
    public function set($cacheId, $value){
        $this->cache[$cacheId] = $value;
    }
    
    public function setCacheId($value = null, $cacheId = null){
		if($cacheId){
			if($cacheId == 'actions'){
				$this->actions[] = $value;
			} 
            else{
				$this->cache[$cacheId] = $value;
			}
		} 
        else{
			$this->cache[] = $value;
		}      
    }
    
	public function setLastCache($add = null, $cacheId = null){
		if($cacheId){
			$this->lastCache[$cacheId] = $add;
		} 
        else{
			array_push($this->lastCache, $add);
		}
	}
    
    public function merge(){
        $this->cache = array_merge($this->cache, $this->lastCache);
    }
    
    public function hasContents(){
        return ($this->cache || $this->actions);
    }        
    
    public function getContents(){
		$cache = $this->cache;
		if(!$this->cache){
			$cache = $this->actions;
			if($this->lastCache){
				$cache = array_merge($cache, $this->lastCache);
			}
		} 
        else{
			if($this->actions){
				$cache = array_merge($cache, $this->actions);
			}
			if($this->lastCache){
				$cache = array_merge($cache, $this->lastCache);
			}
		}
        return $cache;
    }
    
	public function read($crc32 = null){
        $filename = ($crc32)? $crc32: 'cjax.txt';
        $dir = ($this->caching)? sys_get_temp_dir(): CJAX_HOME.'/assets/cache/';
 		$dir = rtrim($dir, '/').'/';
 		$file = $dir.$filename;
 		if(is_file($file)){
 			if(getlastmod($file) > time() + 3600){
 				return; //1 hour to regenerate
 			}
	 		$content = file_get_contents($file);
	 		if($content){
	 			$content = unserialize($content);
	 		}
	 		return $content;
 		}
	}
    
	public function tap($crc32){
		$cache = $this->read('cjax-'.$crc32);
		return ($cache)? $cache[$crc32]: null;
	}      
    
    public function commit(){
		if(!$this->cache && !$this->actions){
			return;
		}
		if(!$this->cache){
			$this->cache = $this->actions;
			if($this->lastCache){
				$this->cache = array_merge($this->cache, $this->lastCache);
			}
		} 
        else{
			if($this->actions){
				$this->cache = array_merge($this->cache, $this->actions);
			}
			if($this->lastCache){
				$this->cache = array_merge($this->lastCache, $this->cache);
			}
		}        
    }
    
    public function clear(){
 		if(!isset($_SESSION)){
			@session_start();
		}
		unset($_SESSION['cjax_x_cache']);			
		if(!headers_sent()){
			@setcookie('cjax_x_cache', '');
		}       
    }
 
    public function remove($cacheId){
		if(is_array($cacheId)){
			foreach($cacheId as $k){
				unset($this->cache[$k]);
			}
		} 
        elseif(isset($this->cache[$cacheId])){
			unset($this->cache[$cacheId]);
		}        
    }
    
    public function removeCache($cacheId = null){
        if(isset($this->cache[$cacheId])){
            unset($this->cache[$cacheId]);            
        }
    }
    
    public function removeLast($count){
		do{
			$count--;
			end($this->cache);
			unset($this->cache[key($this->cache)]);			
		}while($count);        
    }
    
    public function flush(){
        $this->cache = [];
    }
    
    public function flushAll(){
        $this->cache = [];
        $this->actions = [];
        $this->lastCache = [];
    }
}
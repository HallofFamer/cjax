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
*   File Last Changed:  06/18/2016           $     
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
	

	/**
     * The constructor for Cache class, creates an instance of Cache object.
	 * @param bool  $caching
     * @access public
     * @return Cache
     */	     
    public function __construct($caching){
        $this->caching = $caching;
    }
    
	/**
     * The getCache method, fetches the internal cache data array.
     * @access public
     * @return array
     */         
    public function getCache(){
        return $this->cache;
    }  
    
	/**
     * The setCache method, overwrites the internal cache data array.
     * @param array  $cache
     * @access public
     * @return array
     */           
    public function setCache($cache = []){
        $this->cache = $cache;
    }
    
	/**
     * The get method, obtains a cached data given its id.
     * @param int  $cacheId
     * @access public
     * @return mixed
     */      
    public function get($cacheId){
        return $this->cache[$cacheId];
    }
    
 	/**
     * The getId method, acquires the current cache Id for internal cache iterator.
     * @param int  $cacheId
     * @access public
     * @return int
     */     
    public function getId(){
		$count = 0;
		if($this->cache){
			end($this->cache);
			$count = key($this->cache);
			reset($this->cache);
		}		
		return $count;        
    }
    
	/**
     * The set method, adds a given data to the cache given its id.
     * @param int  $cacheId
     * @param mixed  $value
     * @access public
     * @return void
     */         
    public function set($cacheId, $value){
        $this->cache[$cacheId] = $value;
    } 
    
	/**
     * The append method, appends a given cache to the end of cache.
     * If an optional value for cacheId is provided, it behaves the same as set method.
     * @param mixed  $value
     * @param int|string  $cacheId
     * @access public
     * @return void
     */           
    public function append($value = null, $cacheId = null){
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
    
	/**
     * The appendLast method, adds a given data to the last cache given its id.
     * @param mixed  $add
     * @param int  $cacheId
     * @access public
     * @return void
     */      
	public function appendLast($add = null, $cacheId = null){
		if($cacheId){
			$this->lastCache[$cacheId] = $add;
		} 
        else{
			array_push($this->lastCache, $add);
		}
	}       
    
	/**
     * The merge method, merges the last cache into cache array.
     * @access public
     * @return void
     */      
    public function merge(){
        $this->cache = array_merge($this->cache, $this->lastCache);
    }
    
  	/**
     * The hasContents method, checks if there are contents stored in cache.
     * This method returns true if either cache or actions array contains data.
     * @access public
     * @return bool
     */   
    public function hasContents(){
        return ($this->cache || $this->actions);
    }        
    
  	/**
     * The getContents method, acquires all contents stored in cache.
     * @access public
     * @return array
     */     
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
    
  	/**
     * The read method, read cached data stored previously in local disk.
     * @param string  $crc32
     * @access public
     * @return mixed
     */       
	public function read($crc32 = null){
        $filename = ($crc32)? $crc32: 'cjax.txt';
        $dir = ($this->caching)? sys_get_temp_dir(): CJAX_HOME.'/assets/cache/';
 		$dir = rtrim($dir, '/').'/';
 		$file = $dir.$filename;
 		if(is_file($file)){
 			if(getlastmod($file) > time() + 3600){
 				return; 
 			}
	 		$contents = file_get_contents($file);
	 		if($contents){
	 			$contents = unserialize($contents);
	 		}
	 		return $contents;
 		}
	}
    
  	/**
     * The tap method, fetches cache stored in memory or on local disk.
     * @param string  $crc32
     * @access public
     * @return mixed
     */       
	public function tap($crc32){
		$cache = $this->read('cjax-'.$crc32);
		return ($cache)? $cache[$crc32]: null;
	}      
 
  	/**
     * The remove method, removes a given cache from storage given its id.
     * If the cached data is an array, this method will reduce it to an empty array.
     * @param int  $cacheId
     * @access public
     * @return void
     */       
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
    
  	/**
     * The delete method, deletes a given cache from storage given its id.
     * Different from remove method, it completely get rids of specified data.
     * @param int  $cacheId
     * @access public
     * @return void
     */     
    public function delete($cacheId = null){
        if(isset($this->cache[$cacheId])){
            unset($this->cache[$cacheId]);            
        }
    }
    
  	/**
     * The deleteLast method, deletes the last cache from storage.
     * @param int  $count
     * @access public
     * @return void
     */       
    public function deleteLast($count){
		do{
			$count--;
			end($this->cache);
			unset($this->cache[key($this->cache)]);			
		}while($count);        
    }
    
  	/**
     * The flush method, flushes and clears the internal cached data.
     * @access public
     * @return void
     */       
    public function flush(){
        $this->cache = [];
    }
    
  	/**
     * The flushAll method, flushes and empties all stored data.
     * @access public
     * @return void
     */       
    public function flushAll(){
        $this->cache = [];
        $this->actions = [];
        $this->lastCache = [];
    }
}
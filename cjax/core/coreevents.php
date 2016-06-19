<?php

/** ################################################################################################**   
* Copyright (c)  2008  CJ.   
* Permission is granted to copy, distribute and/or modify this document   
* under the terms of the GNU Free Documentation License, Version 1.2   
* or any later version published by the Free Software Foundation;   
* Provided 'as is' with no warranties, nor shall the author be responsible for any misuse of the same.     
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
*   File Last Changed:  06/18/2016           $     
**####################################################################################################    */   

namespace CJAX\Core;
 
/**
 * The CoreEvents class that handles CJAX internal processes and functionality.
 * @category CJAX
 * @package Core
 * @author CJ Galindo <cjxxi@msn.com>
 * @copyright (c) 2008, CJ Galindo
 * @link https://github.com/ajaxboy/cjax
 * @version 6.0
 * @since 1.0
 */

class CoreEvents{

    /**
     * The a-j property, stores convenient ajax data for CJAX to use.
     * @access public
     * @var mixed
     */	        
	public $a, $b, $c, $d, $e, $f, $g, $h, $i, $j;
	
	/**
	 * The config property, specifies the configuration variables for CJAX.
     * @access public
	 * @var Config|Ext
	 */      
	public $config;
    
	/**
	 * The cache property, stores a refernece to the Cache object.
	 * @access public
	 * @var Cache 
	 */    
    private $cache; 
    
	/**
	 * The PluginManager property, stores a reference of PluginManager object.
     * @access private
	 * @var PluginManager
	 */    
    private $pluginManager;    

	/**
	 * The caching property, checks if disk level caching is enabled.
     * @access public
	 * @var string
	 */    
	public $caching = false;    
    
	/**
	 * The callbacks property, stores a list of cached callbacks for CJAX to use.
     * @access public
	 * @var array
	 */       
	public $callbacks = [];
    
	/**
	 * The controllerDir property, defines where controllers are located in a sub directory.
	 * @access public
	 * @var string
	 */
	public $controllerDir = '';         

	/**
	 * The crc32 property, defines information for CJAX's crc32 string.
     * @access public
	 * @var string
	 */    	
	public $crc32;

	/**
	 * The event property, specifies the default event for CJAX if not specified.
     * @access public
	 * @var string
	 */     
	public $event = "onClick";
    
	/**
	 * The file property, specifies the full name of cjax.js file.
     * @access public
	 * @var string
	 */ 
	public $file; 
    
	/**
	 * The flag property, holds the last flag set by CJAX internal processes.
	 * @access public
	 * @var string
	 */        
	public $flag = null;     
    
	/**
	 * The fullPath property, defines fullPath of CJAX library.
     * @access public
	 * @var string
	 */     
	public $fullPath;    
    
	/**
	 * The initExtra property, stores a list of extra parameters upon CJAX initialization.
     * @access public
	 * @var array
	 */       
	public $initExtra = [];    
    
	/**
	 * The jsDir property, defines where javascript files are located in a sub directory.
	 * @access public
	 * @var string
	 */    
	public $jsDir = null;    
    
	/**
	 * The lastCmd property, defines the last command/action executed for CJAX.
     * @access public
	 * @var string
	 */        
	public $lastCmd; 
    
	/**
	 * The log property, specifies whether to show internal debug info.
     * @access public
	 * @var bool
	 */    	
	public $log = false;  
    
	/**
	 * The method property, specifies the default way of making AJAX calls, either get or post.
     * @access public
	 * @var string
	 */      
	public $method;    
    
	/**
	 * The path property, defines the path where Javascript library is located.
     * @access public
	 * @var string
	 */       
	public $path;    
    
	/**
	 * The port property, defines the port number when connecting to web pages.
     * @access public
	 * @var 80
	 */        
	public $port = 80;    
    
 	/**
	 * The shutDown property, checks if system shutdown is executed.
     * @access private
	 * @var bool
	 */       
	private $shutDown = false;    
    
	/**
	 * The strict property, defines whether CJAX strict mode is enabled.
     * @access public
	 * @var bool
	 */     
	public $strict = false;
	
	/**
	 * The version property, defines the version number for CJAX.
	 * @access public
	 * @var string
	 */     
	public $version = '6.0';      
    
	/**
	 * The xmlObjects property, stores an object that holds referneces to XmlItem Ids.
     * @access public
	 * @var Ext
	 */    
	private $xmlObjects;
	
	/**
	 * The wrapper property, defines wrapper text for CJAX cache.
     * @access private
	 * @var string
	 */     
	private $wrapper;
	 

	/**
     * The constructor for CoreEvents class, creates an instance of CoreEvents object.
	 * @param Config|Ext  $config
     * @param Cache  $cache
     * @access public
     * @return CoreEvents
     */	        
    public function __construct($config, Cache $cache){
        $this->config = $config;
        $this->cache = $cache;
        $this->pluginManager = new PluginManager($this);        
    }   
    
	/**
	 * The getCache method, obtains the cache instance stored in CoreEvents.
     * @access public
     * @return Cache
	 */      
    public function getCache(){
        return $this->cache;
    }    
    
	/**
	 * The getPluginManager method, obtains the plugin manager instance stored in CoreEvents.
     * @access public
     * @return PluginManager
	 */    
    public function getPluginManager(){
        return $this->pluginManager;
    }
    
	/**
	 * The xmlItem method, creates an instance of XmlItemobject given its id and name.
     * @param int  $xml
     * @param string  $name
     * @access public
     * @return XmlItem
	 */       
	public function xmlItem($xml, $name){
		if(!is_integer($xml)){
            throw new CJAXException("XML:{$name} ".print_r($xml,1)." is not an integer.");
		}
		$xmlItem = new XmlItem($this, $xml, $name);
        $this->xmlObjects = ($this->xmlObjects)? $this->xmlObjects: new Ext;
		$this->xmlObjects->{$xmlItem->id} = $xmlItem;	
		return $xmlItem;
	}
	
	/**
	 * The xmlObject method, fetches an XmlItem object stored in CoreEvents.
     * @param string  $id
     * @access public
     * @return mixed
	 */     
	public function xmlObject($id = null){
        return (is_null($id))? null: $this->xmlObjects->$id;
	}
	
	/**
	 * The xmlObjects method, fetches one or all of XmlItems stored in CoreEvents.
     * @param string  $id
     * @access public
     * @return mixed
	 */      
	public function xmlObjects($id = null){
        return (is_null($id))? $this->xmlObjects: $this->xmlObjects->$id;
	}
	
	/**
	 * The callbacks method, assigns a cache to callback array in CoreEvents.
     * @param array  $cache
     * @param bool  $test
     * @access public
     * @return array
	 */    
	public function callbacks($cache, $test = false){
		if($this->callbacks){
			foreach($this->callbacks as $k => $v){
                $cb = $this->processCache($v);
				if(!isset($cache[$k])){
					$v[$k]['callback'] = $this->mkArray($cb,'json', true);
				} 
                else{
                    $cache[$k]['callback'] = ($test)? $cb: $this->mkArray($cb, 'json', true);
				}
			}
		}
		return $cache;
	}
	
	/**
	 * The out method, writes the output of cache stored in CoreEvents. 
     * @access public
     * @return string
	 */        
	public function out(){
		if(!$this->cache->hasContents()){
			return;
		}
		$cache = $this->callbacks($this->cache->getContents());		
		$preload = null;        
		foreach($cache as $k => $v){
			if($v['do'] == '_import' || $v['do'] == '_imports' || isset($v['is_plugin'])){
				$preload[$k] = $v;
				if(!isset($v['is_plugin'])){
					unset($cache[$k]);
				}
			}
		}
        
		if($preload){
			$preload = $this->mkArray($this->processCache($preload));
		}		
		$processedCache = $this->mkArray($this->processCache($cache));				
		$out  = "<xml class='cjax'>{$processedCache}</xml><xml class='cjax'><preload>{$preload}</preload></xml>";
		if($this->wrapper){
			$out = str_replace('(!xml!)', $out, $this->wrapper);
		}
		return $out;
	}
	
	/**
	 * The commit method, commits changes made in CoreEvents cache and creates xml/json response.
     * @access public
     * @return string
	 */        
	public function commit(){
		if(!$this->cache->hasContents()){
			return;
		}        	
        $contents = $this->cache->getContents();
        $this->cache->setCache($this->callbacks($contents));
		$cache = $this->cache->getCache();
		$preload = [];
		foreach($cache as $k => $v){
			if($v['do'] == '_import' || $v['do'] == '_imports' || isset($v['is_plugin'])) {
				$preload[$k] = $v;
				if(!isset($v['is_plugin'])){
                    $this->cache->remove($k);
				}
			}
		}
        
        $preload = ($preload)? $this->mkArray($this->processCache($preload)): null;   
		$cache = $this->mkArray($this->processCache($this->cache->getCache()));		
		$debug = ($this->config->debug)? 1 : 0;		
		$out = 'CJAX.process_all("'.$cache.'","'.$preload.'", '.$debug.', true);';		
		return $out;
	}
	
	/**
	 * The simpleCommit method, creates xml/json response from cache.
     * simpleCommit does not update internal cache, only uses cache contents to create responses.
     * @param bool  $return
     * @access public
     * @return mixed
	 */       
	public function simpleCommit($return = false){
		if($this->config->fallback || $this->caching){
			return true;
		}
        $cache = $this->callbacks($this->cache->getContents());	
		$preload = [];
		foreach($cache as $k => $v){
			if(isset($v['do']) && ($v['do'] == '_import' || $v['do'] == '_imports' || isset($v['is_plugin']))) {
				$preload[$k] = $v;
				if(!isset($v['is_plugin'])){
					unset($cache[$k]);
				}
			}
		}   
		if($preload){
			$preload = $this->mkArray($this->processCache($preload));
		}		
		$processedCache = $this->mkArray($this->processCache($cache));		
		$debug = ($this->config->debug)? 1 : 0;	
		if($preload){
			$this->save('cjax_preload', $preload);
		}
		$this->save('cjax_x_cache', $processedCache);
		if($debug){
			$this->save('cjax_debug', $debug);
		}
		return $processedCache;
	}
	
	/**
	 * The cacheWrapper method, creates cache wrapper string for AJAX responses.
     * @param array  $wrapper
     * @access public
     * @return void
	 */       
	public function cacheWrapper($wrapper = []){
		if(is_array($wrapper)){
			$this->wrapper = implode('(!xml!)', $wrapper);
		}		
	}        
    
	/**
	 * The saveCache method, saves and prints out cached response data.
     * This method is registered as shutdown function to call before script exits.
     * @access public
     * @return void
	 */        
	public function saveCache(){
		if($this->log && $this->cache->getCache()){
			throw new CJAXException("Debug Info:<pre>".print_r($this->cache->getCache(), 1)."</pre>");
		}
		
		if($this->isAjaxRequest()){			
			print $this->out();
			return;
		}  
        else{			
			$out = $this->commit();			
			if($this->config->caching){
				if(is_array($this->caching) && crc32('caching=1;'.$out)!= key($this->caching)){
					$this->write([$this->crc32 => 'caching=1;'.$out], 'cjax-'.$this->crc32);
				} 
                elseif(!$this->caching){
					$this->write([$this->crc32 => 'caching=1;'.$out], 'cjax-'.$this->crc32);
				}
			} 
            else{
				if($this->config->fallback){					
					$data = $this->fallbackPrint($out);			
                    print "\n<script>{$data}\n</script>";
				}
			}
		}
	}
	
	/**
	 * The processCache method, process cached data for ajax request.
     * @param array  $cache
     * @access public
     * @return array
	 */        
	public function processCache($cache){
		foreach($cache as $k => $v){
			$v['uniqid'] = $k;
			if(isset($v['do']) && $v['do'] == 'AddEventTo'){
				$v = $this->processEventCache($v);
			}
			
			if(isset($v['is_plugin'])){
				$v = $this->processPluginCache($v);
			}
			
			foreach($v as $k2 => $v2){
				if(is_array($v2)){
					$v2 = $this->mkArray($v2);
					$v[$k2] = "<$k2>$v2</$k2>";
					
				} 
                else{
					$v[$k2] = "<$k2>$v2</$k2>";
				}
			}
			$cache[$k] = "<cjax>".implode($v)."</cjax>";
		}
		return $cache;
	}    
    
	/**
	 * The processPluginCache method, process cached data for CJAX plugins.
     * @param array  $v
     * @param string  $caller
     * @access public
     * @return array
	 */          
	public function processPluginCache($v, $caller = null){
		if($v['data'] && is_array($v['data'])){
			$v['data'] = $this->mkArray($v['data']);
		}
		if(isset($v['extra'])){
			$v['extra'] = $this->mkArray($v['extra']);
		}
		if(isset($v['onwait'])){
			$v['onwait'] = $this->processCache($v['onwait']);
		}
		if(isset($v['callback'])){
			$v['callback'] = $this->mkArray($v['callback']);
		}			
		return $v;
	}
	
 	/**
	 * The processEventCache method, process cached data for ajax events.
     * @param array  $event
     * @access public
     * @return array
	 */     
	public function processEventCache($event){
		$keep = ['event_elementId', 'xml', 'do', 'event', 'waitFor', 'uniqid'];
		foreach($event['events'] as $k => $v){
			$v['event_elementId'] = $event['elementId'];
			foreach($v as $k2 => $v2){
				if(is_array($v2)){
					foreach($v2 as $k3 => $v3){
						if(is_array($v3)){
							$v2[$k3] = $this->mkArray($v3);
						}
					}
					$v[$k2] = $this->mkArray($v2);
				}
			}
			$v['xml'] = $this->xmlIt($v);
			foreach($v as $_k => $_v){
				if(in_array($_k, $keep)){
					continue;
				}
				unset($v[$_k]);
			}
			$event['events'][$k] = $v;
		}
		
		$event['events'] = $this->mkArray($event['events']);
		return $event;
	}
	
 	/**
	 * The updateCache method, updates a given cached data given its id and new value.
     * @param int  $instanceId
     * @param mixed  $data
     * @access public
     * @return array
	 */       
	public function updateCache($instanceId, $data){
        $this->cache->set($instanceId, $data);
		$this->simpleCommit();
	}    
    
 	/**
	 * The lastEntryId method, finds the id number for last cache.
     * @access public
     * @return int
	 */      
	public function lastEntryId(){
		$count = 0;
        $cache = $this->cache->getCache();
		if($cache){
			end($cache);
			$count = key($cache);
			reset($cache);
		}		
		return $count;
	}
	
 	/**
	 * The isPlugin method, checks if a given plugin exists.
     * @param string  $pluginName
     * @access public
     * @return bool
	 */      
	public function isPlugin($pluginName){
        return $this->pluginManager->isPlugin($pluginName);
	}
    
	/**
	 * The import method, imports css or javascript files for CJAX to use.
     * @param string  $file
     * @param int  $loadTime
     * @access public
     * @return int
	 */    
	public function import($file, $loadTime = 0){
        $data = ['do' => '_import', 'time' => (int)$loadTime, 'is_import' => 1];
		if(!is_array($file)){
			$data['file'] = $file;
		} 
        else{
			$data = array_merge($data, $file);
		}		
		return $this->xml($data);
	}

	/**
	 * The imports method, imports more than one file for CJAX.
     * @param array  $files
     * @param array  $data
     * @access public
     * @return int
	 */        
	public function imports($files = [], &$data = []){
		$data['do'] = '_imports';
		$data['files'] = $this->xmlIt($files, 'file');
		$data['is_import'] = 1;
		
		$this->first();
		return $this->xml($data);
	}    
    
	/**
	 * The first method, sets 'first' flag for CJAX events, alias to preload method.
     * @access public
     * @return void
	 */         
	public function first(){
		$this->flag('first');
	}
	
 	/**
	 * The preload method, flags command execution in high priority preload mode.
     * @access public
     * @return void
	 */    
	public function preload(){
		$this->flag('first');
	}       
    
	/**
	 * The xml method, it is an outputer that allows the interaction with xml.
	 * @param xml  $xml
     * @param string  $apiName
     * @access public
	 * @return int
	 */
	public function xml($xml, $apiName  = null){
		if(isset($xml['do'])){
			$this->lastCmd = $xml['do'];
		}
		if($this->flag){			
			if(is_array($this->flag)){
				$xml['flag'] = $this->xmlIt($this->flag);
				$this->flag = null;
			} 
            elseif($this->flag == 'first'){
                $this->cache->appendLast($xml);
				$this->flag = null;
				return;
			}
		}
		$this->cache($xml);	
		if(!$this->isAjaxRequest()){
			$this->simpleCommit(); 
		}
		$count = $this->lastEntryId();	
		return $count;
	}
	
	/**
	 * The fallbackPrint method, it is used by CJAX to fallback on a small footprint on the page to be able to pass the pending data.
	 * @param string  $out
     * @access public
	 * @return string
	 */    
	public function fallbackPrint($out){
		return "init = function() {
	                if (arguments.callee.done) return;
	                arguments.callee.done = true;
	                _cjax = function() {
		                $out
	                }
	                window['DOMContentLoaded'] = true;
	                if(typeof CJAX != 'undefined') {
		                _cjax();
	                } else {
	                   	window['_CJAX_PROCESS'] = function() {
			                 _cjax();
		                }
	                }
                }
                if (document.addEventListener) {
	                document.addEventListener('DOMContentLoaded', init, false);
                } else {
	                /* for Internet Explorer */
	                /*@cc_on @*/
	                /*@if (@_win32)
	                document.write('<script defer src=\"{$this->fullPath}cjax.js.php?json=1\"><'+'/script>');
	                /*@end @*/
	                window.onload = init;
                }";
	}
    
 	/**
	 * The cache method, it is used for loading 'fly' events.
	 * @param mixed  $value
     * @param int  $cacheId
     * @access public
	 * @return string
	 */   
	public function cache($value = null, $cacheId = null){
		if(!$this->shutDown){
			register_shutdown_function([$this, "saveCache"]);
			$this->shutDown = true;	
		}
		$this->cache->append($value, $cacheId);
		if($value == null){
			return $this->cache->getCache();
		}
	}
	
 	/**
	 * The jsonEncode method, encodes data into json format.
	 * @param array  $array
     * @access public
	 * @return object
	 */      
	public function jsonEncode($array){
		return json_encode($array, JSON_FORCE_OBJECT);
	}
	
 	/**
	 * The mkArray method, creates response data array given raw array data and tag name.
	 * @param array  $array
     * @param string  $tag
     * @param bool  $double
     * @access public
	 * @return string
	 */       
	public function mkArray($array, $tag = 'json', $double = false){
		$json = $this->encode($this->jsonEncode($array));		
        $json = ($double)? $this->encode($json): $json;	
		return "<{$tag}>{$json}</{$tag}>";
	}

 	/**
	 * The headRef method, loads cjax javascript library and outputs html for script tags.
     * If min parameter is specified, i
	 * @param string  $jsPath
     * @param bool  $min
     * @access public
	 * @return string
	 */      
	public function headRef($jsPath = null, $min = false){
		$file = "cjax-6.0.js";
		if($min){
			$file = $this->file;
		}
		if(is_string($min) && !is_bool($min)){
            $jsPath = ($file)? rtrim($min,'/').'/cjax/assets/js/': rtrim($min,'/'); 
		} 
        else{
			if($this->config->initUrl && preg_match("/https?/", $this->config->initUrl)) {
				$jsPath = rtrim($this->config->initUrl,'/').'/cjax/assets/js/';
			}
		}
		if($this->crc32){
			$file .= "?crc32={$this->crc32}";
		}
		
		if($this->config->sizzle){
			$script[] = "<script type='text/javascript' src='{$jsPath}sizzle.min.js'></script>\n";
		}
		
		if($this->initExtra){
			$pluginPath = str_replace('/assets/js','/plugins',$jsPath);
			foreach($this->initExtra as $k => $v) {
                $script[] = (isset($v['plugin_dir']))? "\t<script type='text/javascript' src='".$pluginPath.$v['plugin_dir'].'/'.$v['file']."'></script>\n"
                                                     : "\t<script type='text/javascript' src='".$v['file']."'></script>\n";
			}
		}
		if($this->jsDir){
            $path = ($file)? $jsPath.$file: $jsPath;
			$this->fullPath = $path;
			$script[] = "<script defer='defer' id='cjax_lib' type='text/javascript' src='{$path}'></script>\n";
		} 
        elseif($this->path){
			if($this->path[strlen($this->path)-1] =='/') {
				$this->path = substr($this->path, 0, strlen($this->path) -1);
			}
			$this->fullPath = ($this->file)? $this->path: $this->path."/assets/js/";
			$script[] = "<script id='cjax_lib' type='text/javascript' src='".$this->fullPath.$file."'></script>\n";
		}
		return implode($script);
	}

	/**
	 * The init method, initiates the process of sending the javascript file to the application.
	 * @param bool  $min
     * @access public
	 * @return string
	 */
	public function init($min = true){
        $this->file = ($min && substr($min, 0, 2) == '..')
                       ? (strpos($min,'.js') !== false) ? null: "cjax-6.0.min.js"
                       : "cjax-6.0.min.js";
		return $this->headRef($this->jsDir, $min);
	}      
    
	/**
	 * The js method, it sets up the directory where the CJAX FRAMEWORK resides.
	 * @param string  $jsDir
     * @param bool  $force
     * @access public
     * @return bool
	 */
	public function js($jsDir, $force = false){
		if($force){
			$this->path = $jsDir;
			return $this->jsDir = false;
		}
		if(!$this->jsDir && $this->jsDir !== false){
			$this->path = $jsDir;
			$this->jsDir = $jsDir;
		}
	}         
    
	/**
	 * The curl method, opens a curl request with given url and post data.
	 * @param string  $url
     * @param array  $postData
     * @access public
     * @return mixed
	 */    
	public function curl($url, $postData = []){
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_URL, 'http://sourceforge.net');
		curl_setopt($ch, CURLOPT_HEADER,0);  // DO NOT RETURN HTTP HEADERS
		if($postData && is_array($postData)){
			curl_setopt($ch,CURLOPT_POST, count($postData));
			curl_setopt($ch,CURLOPT_POSTFIELDS, http_build_query($postData));
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);  // RETURN THE CONTENTS OF THE CALL
		curl_setopt($ch, CURLOPT_TIMEOUT, 3);
		$data = curl_exec($ch);
		$err = curl_errno($ch);
		curl_close($ch);
		return ($err)? $this->fsockopen($url): $data;
	}
	
	/**
	 * The remote method, creates a remote url/cross-domain request.
	 * @param string  $url
     * @access public
     * @return mixed
	 */        
	public function remote($url){
		$content = @file_get_contents($url);		
		if($content !== false){			
			return $content;
		}
        return (function_exists('curl_init'))? $this->curl($url): $this->fsockopen($url);
	}
	
	/**
	 * The fsockopen method, opens a socket for ajax request.
	 * @param string  $url
     * @param string  $errno
     * @param string  $errstr
     * @access public
     * @return mixed
	 */      
	public function fsockopen($url, $errno = null, $errstr = null){
		if(!function_exists('fsockopen')){
			throw new CJAXException('You  need cURL or fsockopen enabled to connect to a remote server.');
		}
		
		$info = parse_url($url);
		$host = $info['host'];
		$file = $info['path'];
		$fp = @fsockopen($host,80,$errno,$errstr);
		@stream_set_timeout($fp, 20);
		if(!$fp){
			throw new CJAXException('Could not connect to remote server: '. $errstr);
		}
		if($errstr){
			throw new CJAXException('Error:#'.$errno.' '.$errstr);
		}

		$base = "/";			
	    @fputs($fp, "GET {$base}{$file} HTTP/1.1\r\n");
	    @fputs($fp, "HOST: {$host}\r\n");
	    @fputs($fp, "Connection: close\r\n\r\n");
		$getInfo = false;
		$data= [];
        
		while(!feof($fp)){
			if($getInfo){
				$data[] = fread($fp, 1024);
			} 
            elseif(fgets($fp, 1024) == "\r\n"){
				$getInfo = true;
			}
		}
		fclose($fp);		
		return implode($data);
	}
	
	/**
	 * The readCache method, reads available cache for CJAX.
	 * @param string  $crc32
     * @access public
     * @return string
	 */    
	public function readCache($crc32 = null){
        $filename = ($crc32)? $crc32: 'cjax.txt';
        $dir = ($this->config->caching)? sys_get_temp_dir(): CJAX_HOME.'/assets/cache/';
 		$dir = rtrim($dir, '/').'/';
 		$file = $dir.$filename;
 		if(is_file($file)){
 			if(getlastmod($file) > time() + 3600){
 				return;//1 hour to regenerate
 			}
	 		$content = file_get_contents($file);
	 		if($content){
	 			$content = unserialize($content);
	 		}
	 		return $content;
 		}
	}
	
	/**
	 * The write method, writes to a file used as alternative for CJAX internal cache.
	 * @param string  $content
     * @param string  $filename
     * @access public
     * @return string
	 */        
 	public function write($content, $filename = null){
 		if(!$filename){
	 		$filename = 'cjax.txt';
 		}
 		if($this->config->caching && !is_writable($dir = sys_get_temp_dir())){
 			$dir = CJAX_HOME.'/assets/cache/';
 		}
 		if(is_array($content)){
 			$content = serialize($content);
 		}
 		$dir = rtrim($dir, '/').'/';
 		$file = $dir.$filename;
        
 		if(file_exists($file) && !is_writable($file) && !chmod($filename, 0666)){
 			throw new CJAXException("CJAX: Error! file ($file) is not writable, Not enough permission");
 		}
 		if(!$fp = @fopen($file, 'w')){
 			throw new CJAXException("CJAX: Error! file ($file) is not writable, Not enough permission");
 		}
 		if(fwrite($fp, $content) === FALSE){
 			throw new CJAXException("Cannot write to file ($file)");
 		}
 		if(!fclose($fp)){
 			throw new CJAXException("Cannot close file ($file)");
 		}
 	}
 	
	/**
	 * The wait method, it will execute a command in a specified amouth of time.
	 * @param int  seconds
	 * @param bool  $milliseconds
	 * @param bool  $expand
     * @access public
     * @return CoreEvents
	 */         
	public function wait($seconds, $milliseconds = false, $expand = true){
		$data['timeout'] = $seconds;
		if(is_float($seconds)){
			$milliseconds = true;
		}
		$data['ms'] = $milliseconds;
		if(!$seconds){
			$data['no_wait'] = 1;
		} 
        else{
			$data['expand'] = $expand;
		}
		$this->flag = $data;
		return $this;
	}     

	/**
	 * The flag method, helper method to generate flags quicker.
	 * @param int  $flagId
	 * @param int  $commandCount
	 * @param array  $settings
     * @access public
     * @return CoreEvents
	 */        
	public function flag($flagId, $commandCount = 1, $settings = []){
		switch($flagId){
			case 'wait':					
				$settings['command_count'] = $commandCount;		
				$this->flag = $settings;
				break;
			case 'first':
			case 'last':
			case 'no_wait':
				$this->flag = 'first';
				break;
			default:
				if($this->strict){
					throw new CJAXException("Invalid Flag Argument Prodivided");
				}
		}
	}

	/**
	 * The flag method, tells whether this is an ajax request or not.
     * @access public
     * @return bool
	 */            
	public function isAjaxRequest(){
		$request = $this->input('ajax');
		if($request){
			return true;
		}
		$request = $this->input('cjax_iframe');
		if($request){
			return true;
		}
		$headers = [];
		if(function_exists('apache_request_headers')){
			$headers = apache_request_headers();
			if(!isset($headers['X-Requested-With'])){
                $headers['X-Requested-With'] = (isset($_SERVER['HTTP_X_REQUESTED_WITH']))?
                                                $_SERVER['HTTP_X_REQUESTED_WITH'] : null;
			}
		} 
        elseif(isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
			$headers['X-Requested-With'] = $_SERVER['HTTP_X_REQUESTED_WITH'];
		}
		
		if(!empty($headers) && ($headers['X-Requested-With'] == 'CJAX FRAMEW0RK 6.0' || $headers['X-Requested-With'] == 'XMLHttpRequest')){
		    return true;
		}
		//allow access to flash
		if(isset($_SERVER['HTTP_USER_AGENT']) && $_SERVER['HTTP_USER_AGENT'] == 'Shockwave Flash'){
			return true;
		}
	}

	/**
	 * The addEventTo method, attaches a CJAX event to DOM element.
	 * @param string  $element
	 * @param string|array  $actions
	 * @param string  $event
     * @access public
     * @return XmlItem
	 */
	public function addEventTo($element, $actions, $event = 'onclick'){
        $data = ['do' => 'AddEventTo', 'elementId' => $element, 
                 'event' => $event, 'events' => $actions];	
		return $this->xmlItem($this->xml($data), 'AddEventTo', 'api');
	}     
    
	/**
     * The exec method, binds any events to given elements.
     * This method is the generic event binder that can handle any types of events.
     * @param string  $selector
     * @param array  $actions
     * @param string  $event
     * @access public
     * @return XmlItem
     */         
	public function exec($selector, $actions, $event = "click"){
		if(!$this->cache->getCache()){
			return false;
		}
		if(is_array($selector)){
			$selector = implode('|', $selector);
		}
		if($event){
			if(substr($event, 0,2)  != "on" && $event != 'click'){
				$event = "on{$event}";
			}
			$this->event = $event;
		}
		$execActions = [];

		if($actions && is_array($actions)){		
			$execActions = $this->execActions($actions, $selector);
			return $this->addEventTo($selector, $execActions, $event);
		}
			
		if($actions instanceof XmlItem || $actions instanceof Plugin){
			if($actions instanceof Plugin){
				$actions->elementId = $selector;
				$actions->xml->elementId = $selector;
				if(method_exists($actions, 'onEvent')){
					call_user_func('onEvent', $actions, $selector);
				}
			}
			
			$item = $actions->xml();
			$item['event'] = $event;
			if(isset($this->callbacks[$actions->id]) && $this->callbacks[$actions->id]){
				$item['callback'] = $this->processCache($this->callbacks[$actions->id]);
				$item['callback'] = $this->mkArray($item['callback'],'json', true);
			}
			$actions->delete();
			return $this->addEventTo($selector, [$actions->id => $item], $event);
		} 
        else{
			$execActions = $this->cache->get($actions);
			$execActions['event'] = $event;
            $this->cache->deleteLast(1);
			return $this->addEventTo($selector, [$actions => $execActions], $event);
		}
	}
	
	/**
	 * The execActions method, executes actions for cjax request and saves to cache.
     * @param array  $actions
     * @param string  $selector
     * @access private
     * @return array
	 */    
    private function execActions($actions, $selector){
        $execActions = [];
        foreach($actions as $k => $v){
            if(is_object($v) && ($v instanceof XmlItem || $v instanceof Plugin)){
                if($v instanceof Plugin){
                    $v->elementId = $selector;
                    $v->xml->elementId = $selector;
                    if(method_exists($v, 'onEvent')){
                        call_user_func('onEvent', $v, $selector);
                    }
                }

                if(isset($this->callbacks[$v->id]) && $this->callbacks[$v->id]){
                    $v->attach($this->callbacks[$v->id]);
                    foreach($this->callbacks[$v->id] as $k2 => $v2){
                        $this->cache->remove($k2);
                    }
                }
                $execActions[$v->id] = $v->xml();
                $v->delete();
            } 
            else{
                if(is_object($v)){
                    continue;
                }
                $execActions[$v] = $this->cache->get($v);
                $this->cache->remove($v);
            }
        }
        return $execActions;        
    }        
    
	/**
	 * The encode method, endodes data to be used in url.
     * @param string  $data
     * @access public
     * @return array
	 */        
	public function encode($data){
		return urlencode(str_replace('+', '[plus]', $data));		
	}

	/**
	 * The xmlIt method, converts an array into xml.
     * @param array  $input
     * @param string  $tag
     * @access public
     * @return string
	 */        
	public function xmlIt($input = [], $tag = null){
		$new = [];
		if(is_array($input) && $input){
            $new = $this->xmlInput($tag, $input);
			return implode($new);
		}
	}
    
	/**
	 * The xmlInput method, helper method to convert each input data into xml.
     * @param string  $tag
     * @param array  $input
     * @access public
     * @return array
	 */       
    private function xmlInput($tag, $input){
        $new = [];
        foreach($input as $k => $v){
            if($v){
                if($tag){
                    $k = $tag;
                }
                if(is_array($v)){
                    foreach($v as $k2 => $v2){
                        $new[] =  $this->xmlIt($v2);
                    }
                } 
                else{
                    $new[] =  "<$k>$v</$k>";
                }
            }
        }
        return $new;    
    }

	/**
	 * The save method, saves settings/data into PHP sessions and cookies.
     * @param string  $setting
     * @param mixed  $value
     * @param bool  $useCookie
     * @access public
     * @return void
	 */       
	public function save($setting, $value = null, $useCookie = false){
		if(!isset($_SESSION)){
			@session_start();
		}       
		if($this->config->fallback){
			if($value === null && isset($_SESSION[$setting])){
				unset($_SESSION[$setting]);
				$this->cookie($setting);
			} 
            else{
				$_SESSION[$setting] = $value;
				$this->cookie($setting, $value);
			}
		} 
        elseif(!$useCookie){
			if($value === null && isset($_SESSION[$setting])){
				unset($_SESSION[$setting]);
			} 
            else{
				$_SESSION[$setting] = $value;
			}
		} 
        else{
		    $this->cookie($setting, $value);
        }
	}
	
	/**
	 * The cookie method, sets a cookie given its name and value.
     * @param string  $setting
     * @param mixed  $value
     * @access public
     * @return void
	 */        
	public function cookie($setting, $value = null){
        ($value === null)? @setcookie($setting, $value, time()-(3600*1000), '/')
                         : @setcookie($setting, $value, null, '/');
	}

	/**
	 * The input method, fetches and filters input with given value.
     * @param mixed  $value
     * @access public
     * @return string
	 */        
 	public function input($value = 'cjax'){
		$v = isset($_REQUEST[$value])? $_REQUEST[$value] : (isset($_GET[$value])? $_GET[$value]: null);		
		if(!$v && isset($_COOKIE[$value]) && $_COOKIE[$value]){
			$v = $_COOKIE[$value];
		}
        
		if(is_array($v)){
			foreach($v as $k => $kv){
                $return[$k] = (is_array($kv))? $kv: addslashes($kv);
			}
			return $return;
		}
		return addslashes($v);		
	}

	/**
	 * The get method, acquires session or cookie value.
     * If the parameter $getAsObject is true, array values will be converted into objects.
     * @param string  $setting
     * @param bool  $getAsObject
     * @access public
     * @return mixed
	 */
	public function get($setting, $getAsObject = false){
		$value = null;
		if(isset($_SESSION[$setting])){
			$value = $_SESSION[$setting];
		} 
        elseif(isset($_COOKIE[$setting])){
			$value = $_COOKIE[$setting];
		}
        
		if(is_array($value) && $getAsObject){
			$value = new Ext($value);
		} 
        elseif($getAsObject){
			$value = new Ext;
		}
		return $value;
	}   
    
	/**
	 * The getSetting method, gets raw data stored in session or cookie.
     * @param string  $setting
     * @access public
     * @return mixed
	 */        
	public function getSetting($setting){
		return $this->get($setting);
	}

	/**
	 * The removeCache method, removes data from internal cache and commits this change.
     * @param int  $cacheId
     * @access public
     * @return void
	 */       
	public function removeCache($cacheId){
        $this->cache->remove($cacheId);
		$this->simpleCommit();
	}

	/**
	 * The path method, specifies the path/url for where CJAX is located in a child/sub-directory. 
     * @param string  $path
     * @access public
     * @return void
	 */         
	public function path($path){
		$this->path = $path;
	}

	/**
	 * The remotepPath method, obtains the remote path information for CJAX library. 
     * @access public
     * @return string
	 */      
	public function remotePath(){
		return 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER["SCRIPT_NAME"]).'/cjax';
	}

	/**
	 * The getFile method, finds and opens a given file given its name.
     * @param string  $file
     * @access public
     * @return string
	 */     
	public function getFile($file = null){
		return $this->connect($_SERVER['HTTP_HOST'],(isset($_SERVER['SERVER_PORT'])? $_SERVER['SERVER_PORT']:80), $file, true);
	}

 	/**
	 * The connect method, connects to a remote or local server and loads a given file.
     * @param string  $file
     * @param int  $port
     * @param bool  local
     * @access public
     * @return string
	 */    
	public function connect($file = null, $port = 80, $local = false){
		if(!$port){
			$port = $this->port;
			if(!$port){
				$port = 80;
			}
		}
		if(!function_exists('fsockopen')){
			throw new CJAXException('no fsockopen: be sure that php function fsockopen is enabled.');
		}
				
		$fp = @fsockopen($host, $port, $errno, $errstr);
		if(!$fp){
			return false;
		}
		if($errstr){
			throw new CJAXException('error:'.$errstr);
		}
        
		$base = "/";
        @fputs($fp, "GET {$base}{$file} HTTP/1.1\r\n");
        @fputs($fp, "HOST: {$host}\r\n");
		@fputs($fp, "Connection: close\r\n\r\n");
		$getInfo = false;
		$data = [];
		while(!feof($fp)){
			if($getInfo){
				$data[] = fread($fp, 1024);
			} 
            elseif(fgets($fp, 1024) == "\r\n"){
				$getInfo = true;
			} 
		}
		fclose($fp);
		return implode($data);
	}
}
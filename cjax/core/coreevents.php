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
*   File Last Changed:  04/24/2016           $     
**####################################################################################################    */   

namespace CJAX\Core;
use CJAX\Config;
use StdClass;
 
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

	public $a,$b,$c,$d,$e,$f,$g,$h,$i, $j;
	
	public $config;

	//acts more strict in the kind of information you provive
	public $strict = false;

	public $messageId;
 
	public $trace = 0;
	
	private $xmlObjects;
    
	/**
	 * 
	 * Stores a reference of PluginManager.
	 * @var PluginManager
	 */    
    protected $pluginManager;
	
	//helper to cache callbacks
	public $callbacks = [];
	
	private $simpleCommit;
	
	private $wrapper;
	
	public $lastCmd;
	
	//is using includes.php?
	public $includes = false;
	
	/**
	 * 
	 * For js functions
	 * @var string
	 */
	public $selector;
 	
	/**
	 *
	 * Force the system to adapt to a loading or not loading state.
	 * @var bool
	 */
	public $loading = false;
	
	/**
	 * 
	 * Some hosts have issues with sessions so lets fallback on cookies
	 * @var bool
	 */
	public $fallback = false;

	public $post = [];

	public $dir;

	public $attachEvent = true;
	
	public $log = false; //show internal debug info
	
	/**
	 * If a request variable is sent with 'session_id' name
	 * the framework will start session with that id.
	 * 
	 * In case ever need sessions
	 * @var string
	 */
	public $sessionId;

	/**
	 * default port when connecting to web pages
	 *
	 * @var unknown_type
	 */
	public $port = 80;

	/**
	 * if controllers are located in a sub directory
	 *
	 * @var string
	 */
	public $controllerDir = '';

	/*
	 * hold an object with some formattig helpers
	 * not meant to be added to the package but it was added at some point
	 * @return cjaxFormat
	 */
	public $format;

	/**
	 * Check whether or not to call the shutdown function
	 *
	 * @var boolean $shutDown
	 */
	private $shutDown = false;
	
	public $caching = false;
	
	public $crc32;
	
	/**
	 * store cache procedure
	 *
	 * @var string $cache
	 */
	public static $cache = [];    

	/**
	 * hold cache for actions
	 *
	 * @var array
	 */
	public static $actions = [];
	
	/**
	 * 
	 * Hold cache set for to execute last. Use flag $ajax->flag('last'); to store commands.
	 * This makes it so that commands even if called first can be executed last.
	 * @var unknown_type
	 */
	public static $lastCache = [];
    
	/**
	 * number of commands passed last in Exec
	 */
	private $lastExecCount = 0;

	/**
	 * specified whether to use the cache system or normal mode
	 *
	 * @var boolean $useCache
	 */
	public $useCache;

	//new alias to replace $JSevent.
	public $event = "onClick";

	/**
	 * Set the text to show when the page is loading
	 * this replaces the "loading.."
	 *
	 *
	 * @var mixed $text
	 */
	public $text = null;

	/*
	 * The the CJAX console on debug mode
	 */
	public $debug;

	/**
	 * Get the current version of CJAX FRAMEWORK you are using
	 *
	 * @var string
	 */
	public $version = '6.0';

	/**
	 * Tells whether CJAX output has initciated or not, to void duplications
	 *
	 * @var boolean $is_init
	 */
	public $isInit;
	
	public $initExtra = [];

	/**
	 * Sets the default way of making AJAX calls, it can be either get or post
	 */
	public $method;

	public $file; //full name of the cjax.js    
    
	/**
	 * Path where JavaScript Library is located
	 *
	 * @var string
	 */
	public $path;
	
	/**
	 * 
	 * @var unknown_type
	 */
	public $fullPath;

	/**
	 * Path where JavaScript Library is located
	 *
	 * @var string
	 */
	private $jsdir = null;
	
	public $caller;
	
	//holds he latest flag
	public $flag = null;

	public $flagCount = 0;
	
    
    public function __construct(){
        $this->pluginManager = new PluginManager($this);
        $this->config = (file_exists(CJAX_HOME."/config.php"))? new Config: new Ext;
    }
    
	public function xmlItem($xml, $name){
		if(!is_integer($xml)){
            throw new CJAXException("XML:{$name} ".print_r($xml,1)." is not an integer.");
		}
		$xmlItem = new XmlItem($this, $xml, $name);
        $this->xmlObjects = ($this->xmlObjects)? $this->xmlObjects: new StdClass;
		$this->xmlObjects->{$xmlItem->id} = $xmlItem;	
		return $xmlItem;
	}
	
	public function camelize($string, $ucfirst = true){
		$string = str_replace(['-', '_'], ' ', $string);
		$string = str_replace(' ', '', ucwords($string)); 
		return ($ucfirst)? ucfirst($string): lcfirst($string);
	}
	
	public function xmlObject($id = null){
        return (is_null($id))? null: $this->xmlObjects->$id;
	}
	
	public function xmlObjects($id = null){
        return (is_null($id))? $this->xmlObjects: $this->xmlObjects->$id;
	}
	
	public function flushCache($all = false){
		if(!isset($_SESSION)){
			@session_start();
		}
		if($all){
			$_SESSION['cjax_x_cache'] = '';
			@setcookie('cjax_x_cache','');
		}
		$_SESSION['cjax_preload'] = '';
		$_SESSION['cjax_debug'] = '';
		@setcookie('cjax_preload','');
		@setcookie('cjax_debug','');
	}
	
	public function flushRawCache(){
		self::$cache = [];
		self::$actions = [];
		self::$lastCache = [];
	}
	
	public function callbacks($cache, $test = false){
		if($this->callbacks){
			foreach($this->callbacks as $k => $v){
                $cb = $this->processScache($v);
				if(!isset($cache[$k])){
					$v[$k]['callback'] = $this->mkArray($cb,'json', true);
				} 
                else{
                    $cache[$k]['callback'] = ($test)? $cb: $this->mkArray($cb,'json', true);
				}
			}
		}
		return $cache;
	}
	
	public function out(){
		if(!self::$cache && !self::$actions){
			return;
		}
		$cache = self::$cache;
		if(!self::$cache){
			$cache = self::$actions;
			if(self::$lastCache){
				$cache = array_merge($cache,self::$lastCache);
			}
		} 
        else{
			if(self::$actions){
				$cache = array_merge($cache,self::$actions);
			}
			if(self::$lastCache){
				$cache = array_merge($cache,self::$lastCache);
			}
		}
		
		$cache = $this->callbacks($cache);		
		$preload = null;
		foreach($cache as $k => $v){
			if($v['do']=='_import' || $v['do']=='_imports' || isset($v['is_plugin'])) {
				$preload[$k] = $v;
				if(!isset($v['is_plugin'])){
					unset($cache[$k]);
				}
			}
		}
		if($preload){
			$preload = $this->mkArray($this->processScache($preload));
		}
		
		$processedCache = $this->mkArray($this->processScache($cache));
				
		$out  = "<xml class='cjax'>{$processedCache}</xml><xml class='cjax'><preload>{$preload}</preload></xml>";
		if($this->wrapper){
			$out = str_replace('(!xml!)', $out, $this->wrapper);
		}
		return $out;
	}
	
	public function commit(){
		if(!self::$cache && !self::$actions){
			return;
		}
		if(!self::$cache){
			self::$cache = self::$actions;
			if(self::$lastCache){
				self::$cache = array_merge(self::$cache, self::$lastCache);
			}
		} 
        else{
			if(self::$actions){
				self::$cache = array_merge(self::$cache, self::$actions);
			}
			if(self::$lastCache){
				self::$cache = array_merge(self::$lastCache, self::$cache);
			}
		}
        
		$ajax = CJAX::getInstance();		
		self::$cache = $this->callbacks(self::$cache);
		$preload = [];
		foreach(self::$cache as $k => $v){
			if($v['do'] == '_import' || $v['do'] == '_imports' || isset($v['is_plugin'])) {
				$preload[$k] = $v;
				if(!isset($v['is_plugin'])){
					unset(self::$cache[$k]);
				}
			}
		}
        $preload = ($preload)? $this->mkArray($this->processScache($preload)): null;   
		$cache = $this->mkArray($this->processScache(self::$cache));		
		if($ajax->config->debug){
			$this->debug = true;
		}
		$debug = ($this->debug)? 1 : 0;		
		$out = 'CJAX.process_all("'.$cache.'","'.$preload.'", '.$debug.', true);';		
		return $out;
	}
	
	public function simpleCommit($return = false){
		$ajax = CJAX::getInstance();
		if($this->fallback || $ajax->config->fallback || $this->caching){
			return true;
		}
		$cache = self::$cache;
		if(!$cache){
			$cache = self::$actions;
			if(self::$lastCache) {
				$cache = array_merge($cache, self::$lastCache);
			}
		} 
        else{
			if(self::$actions){
				$cache = array_merge(self::$cache, self::$actions);
			}
			if(self::$lastCache){
				$cache = array_merge(self::$lastCache, $cache);
			}
		}
		
		$cache = $this->callbacks($cache);
		
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
			$preload = $this->mkArray($this->processScache($preload));
		}		
		$processedCache = $this->mkArray($this->processScache($cache));		
		if($ajax->config->debug){
			$this->debug = true;
		}
		$debug = ($this->debug)? 1 : 0;
	
		if($preload){
			$this->save('cjax_preload', $preload);
		}
		$this->save('cjax_x_cache', $processedCache);
		if($debug){
			$this->save('cjax_debug', $debug);
		}
		$this->simpleCommit = $cache;
		return $processedCache;
	}
	
	/**
	 * Saves the cache
	 *
	 * @return string
	 */
	public static function saveSCache(){
		$ajax = CJAX::getInstance();
		if($ajax->log && self::$cache){
			throw new CJAXException("Debug Info:<pre>".print_r(self::$cache,1)."</pre>");
		}
		
		if($ajax->isAjaxRequest()){			
			print $ajax->out();
			return;
		}  
        else{			
			$out = $ajax->commit();
			
			if($ajax->config->caching){
				if(is_array($ajax->caching) && crc32('caching=1;'.$out)!= key($ajax->caching)){
					$ajax->write([$ajax->crc32 => 'caching=1;'.$out], 'cjax-'.$ajax->crc32);
				} 
                elseif(!$ajax->caching){
					$ajax->write([$ajax->crc32 => 'caching=1;'.$out], 'cjax-'.$ajax->crc32);
				}
			} 
            else{
				if($ajax->fallback || $ajax->config->fallback){					
					$data = $ajax->fallbackPrint($out);			
					print "\n<script>$data\n</script>";
				}
			}
		}
	}
	
	public function processScachePlugin($v, $caller = null){
		if($v['data'] && is_array($v['data'])){
			$v['data'] = $this->mkArray($v['data']);
		}
		if(isset($v['extra'])){
			$v['extra'] = $this->mkArray($v['extra']);
		}
		if(isset($v['onwait'])){
			$v['onwait'] = $this->processScache($v['onwait']);
		}
		if(isset($v['callback'])){
			$v['callback'] = $this->mkArray($v['callback']);
		}			
		return $v;
	}
	
	public function processScacheAddEventTo($event){
		$keep = ['event_elementId','xml','do','event','waitFor','uniqid'];
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
	
	public function processScache($cache){
		foreach($cache as $k => $v){
			$v['uniqid'] = $k;
			if(isset($v['do']) && $v['do']=='AddEventTo'){
				$v = $this->processScacheAddEventTo($v);
			}
			
			if(isset($v['is_plugin'])){
				$v = $this->processScachePlugin($v);
			}
			
			foreach($v  as $k2 => $v2){
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
	
	public function lastEntryId(){
		$count = 0;
		if(self::$cache){
			end(self::$cache);
			$count = key(self::$cache);
			reset(self::$cache);
		}		
		return $count;
	}
	
	public function lastId(){
		return $this->lastEntryId();
	}
	
	/**
	 * 
	 * Tells if plugin exists or not
	 * regardless of it having a class or not
	 * 
	 * @param unknown_type $pluginName
	 */
	public function isPlugin($pluginName){
        return $this->pluginManager->isPlugin($pluginName);
	}
	
	public function updateCache($instanceId, $data){
		self::$cache[$instanceId] = $data;
		$this->simpleCommit();
	}
	
	/**
	 * 
	 * gets plugin only if it has a class
	 */
	public function plugin($pluginName, $loadController = false){
		if($this->isPlugin($pluginName) && $plugin = $this->pluginManager->getPlugin($pluginName, null, null, $loadController)){
			return $plugin;
		}
	}
	
	public function initiatePlugins(){
		return $this->pluginManager->initiate();        
	}
	
	/**
	 * 
	 * import css and javascript files
	 * @param mixed_type $file
	 * @param unknown_type $loadTime
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
	 * 
	 * import more than one file, waiting for the previous to load.
	 * @param mixed_type $files
	 * @param unknown_type $data
	 */
	public function imports($files = [], &$data = []){
		$data['do'] = '_imports';
		$data['files'] = $this->xmlIt($files, 'file');
		$data['is_import'] = 1;
		
		$this->first();
		return $this->xml($data);
	}    
    
	/**
	 * 
	 * sets flag 
	 */
	public function first(){
		$this->flag('first');
	}
	
	/**
	 * xml outputer, allows the interaction with xml
	 *
	 * @param xml $xml
     * @param string $apiName
	 * @return string
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
				$this->setLastCache($xml);
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
	
	public function cacheWrapper($wrapper = []){
		if(is_array($wrapper)){
			$this->wrapper = implode('(!xml!)', $wrapper);
		}		
	}
	
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

	public function getCache(){
		return self::$cache;
	}

	/**
	 * Used for loading "fly" events
	 *
	 * @param string $add
	 */
	public function cache($add = null, $cacheId = null){
		if(!$this->shutDown) {
			register_shutdown_function(['CJAX\\Core\\CoreEvents','saveSCache']);
			$this->shutDown = true;
			$this->useCache = true;		
		}
		
		if($cacheId){
			if($cacheId=='actions'){
				self::$actions[] = $add;
			} 
            else{
				self::$cache[$cacheId] = $add;
			}
		} 
        else{
			self::$cache[] = $add;
		}
		if($add == null){
			return self::$cache;
		}
	}
	
	public function template($templateName){
		return file_get_contents(CJAX_HOME."/assets/templates/{$templateName}");		
	}
	
	public function jsonEncode($array){
		return json_encode($array, JSON_FORCE_OBJECT);
	}
	
	public function mkArray($array, $tag = 'json', $double = false){
		$json = $this->encode($this->jsonEncode($array));		
        $json = ($double)? $this->encode($json): $json;	
		return "<{$tag}>{$json}</{$tag}>";
	}

	/**
	 * Setting up the directory where the CJAX FRAMEWORK resides
	 *
	 * @param string $jsdir
	 */
	public function js($jsdir, $force = false){
		if($force){
			$this->path = $jsdir;
			return $this->jsdir = false;
		}
		if(!$this->jsdir && $this->jsdir !== false){
			$this->path = $jsdir;
			$this->jsdir = $jsdir;
		}
	}

	/**
	 * Outputs our FRAMEWORK to the browser
	 * @param unknown-type $jsPath
	 * @return unknown
	 */
	public function headRef($jsPath = null, $min = false){
		$ajax = CJAX::getInstance();
		$file = "cjax-6.0.js";
		if($min) {
			$file = $this->file;
		}
		if(is_string($min) && !is_bool($min)){
            $jsPath = ($file)? rtrim($min,'/').'/cjax/assets/js/': rtrim($min,'/'); 
		} 
        else{
			if($ajax->config->initUrl && preg_match("/https?/", $ajax->config->initUrl)) {
				$jsPath = rtrim($ajax->config->initUrl,'/').'/cjax/assets/js/';
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
		if($this->jsdir){
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
	 * initciates the process of sending the javascript file to the application
	 *
	 * @param optional boolean $min - get the minimized version of javascript
	 * @return string
	 */
	public function init($min = true){
        $this->file = ($min && substr($min, 0, 2)=='..')
                        ? (strpos($min,'.js') !== false) ? null: "cjax-6.0.min.js"
                        : "cjax-6.0.min.js";
		$this->isInit = $href = $this->headRef($this->jsdir, $min);
		return $href;
	}

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
	
	public function remote($url){
		$content = @file_get_contents($url);		
		if($content !== false){			
			return $content;
		}
        return (function_exists('curl_init'))? $this->curl($url): $this->fsockopen($url);
	}
	
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
	
	public function readCache($crc32 = null){
        $ajax = CJAX::getInstance();
        $filename = ($crc32)? $crc32: 'cjax.txt';
        $dir = ($ajax->config->caching)? sys_get_temp_dir(): CJAX_HOME.'/assets/cache/';
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
	
	public function tapCache($crc32){
		$cache = $this->readCache('cjax-'.$crc32);
		return ($cache)? $cache[$crc32]: null;
	}
	
	/**
	 * write to a file in file system, used as an alrernative to for cache
	 *
	 * @param string $content
	 * @param string $filename
	 */
 	public function write($content, $filename = null){
 		if(!$filename){
	 		$filename = 'cjax.txt';
 		}
 		$ajax = CJAX::getInstance();
 		if($ajax->config->caching && !is_writable($dir = sys_get_temp_dir())){
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
	 * 
	 * perform cross domain  requests
	 * @param unknown_type $url
	 */
	public function crossdomain($url){
		$response = $this->remote($url);
		if(!$response || strpos(strtolower($response),'not found') !== false){
			return;
		}
		print $response;
	}

	/**
	 * Helper to generate flags quicker.
	 * @param $flag_id
	 * @param $command_count
	 */
	public function flag($flagId, $commandCount = 1, $settings = []){
		switch($flagId){
			case 'wait':					
				$settings['command_count'] = $commandCount;		
				$this->flag = $settings;
				$this->_flagcount = $commandCount;
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
	 * 
	 * tell whether this is an ajax request or not.
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
	 *  Tell whether or not the a ajax request has been placed
	 *
	 * Sunday August 3 2008 added functionality:
	 *
	 * @return boolean
	 */
	public function request($callback = null, &$params = null){
	 	$r = $this->isAjaxRequest();
	 	if($r && $callback){
	 		if(is_array($callback)){
	 			if(substr($callback[0],0,4)=='self'){
	 				$arr = debug_backtrace(false);
		 			$trace = $arr[1];
		 			$class = $trace['class'];
	 				$callback[0] =$class;
	 			}
	 			if(!$params) $params = [];
	 			$r = call_user_func_array($callback, $params);
	 		} 
            else{
	 			$r = call_user_func($callback);
	 		}
	 		exit;
	 	}
        return ($this->isAjaxRequest())? true: false;
	 }

	public function setRequest($request = true){
        $_GET['cjax'] = ($request)? time(): '';
        $_REQUEST['cjax'] = ($request)? time(): '';
	}

	/**
	 * Encode special data to void conflicts with javascript
	 *
	 * @param string $data
	 * @return string
	 */
	public function encode($data){
		return urlencode(str_replace('+', '[plus]', $data));		
	}

	/**
	 * Converts an array into xml..
	 */
	public function xmlIt($input = [], $tag = null){
		$new = [];
		if(is_array($input) && $input){
            $new = $this->xmlInput($tag, $input);
			return $xml = implode($new);
		}
	}
    
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

	public function save($setting, $value = null, $useCookie = false){
		$ajax = CJAX::getInstance();
		if(!isset($_SESSION)){
			@session_start();
		}       
		if($this->fallback || $ajax->config->fallback){
			if($value===null && isset($_SESSION[$setting])){
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
	
	public function cookie($setting, $value = null){
        ($value === null)? @setcookie($setting, $value, time()-(3600*1000), '/')
                         : @setcookie($setting, $value, null, '/');
	}

	public function getSetting($setting){
		return $this->get($setting);
	}
	
	public function setLastCache($add = null, $cacheId = null){
		if($cacheId){
			self::$lastCache[$cacheId] = $add;
		} 
        else{
			array_push(self::$lastCache, $add);
		}
	}

	/**
	 * 
	 * remove cache
	 * @param mixed $cacheId
	 */
	public function removeExecCache($cacheId){
		if(is_array($cacheId)){
			foreach($cacheId as $k){
				unset(self::$cache[$k]);
			}
		} 
        elseif(isset(self::$cache[$cacheId])){
			unset(self::$cache[$cacheId]);
		}
		$this->simpleCommit();
	}
	
	/**
	 * 
	 * remove cache
	 * @param int $count
	 */
	public function removeLastCache($count){
		do{
			$count--;
			end(self::$cache);
			unset(self::$cache[key(self::$cache)]);			
		}while($count);
	}
	
	/**
	 * 
	 * remove cache
	 * @param mixed $cacheId
	 */
	public function removeCache($cacheId){
		unset(self::$cache[$cacheId]);
	}
	
	public function lastExecCount($count = 0){
		if($count){
			$this->lastExecCount = $count;
		}
		return $this->lastExecCount;
	}

	/**
	 * Yet to implement
	 *
	 * @param string $errno
	 * @param string $errstr
	 * @param string $errfile
	 * @param string $errline
	 * @return string
	 */
	public function CJAXErrorHandler($errno, $errstr, $errfile, $errline){
		switch($errno){
			case E_USER_ERROR:
				echo "<b>CJAX:</b> [$errno] $errstr<br />\n";
				echo "  Fatal error on line $errline in file $errfile";
				echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
				echo "Aborting...<br />\n";
				exit(1);
			    break;
			case E_USER_WARNING:
				echo "<b>Cjax WARNING</b> [$errno] $errstr<br />\n";
				break;
			case E_USER_NOTICE:
				echo "<b>Cjax NOTICE</b> [$errno] $errstr<br />\n";
			break;
			default:
				echo "Unknown error type: [$errno] $errstr<br />\n";
			break;
		}
		/* Don't execute PHP internal error handler */
		return true;
	}

	public function CJAXExceptionHandler(){

	}

	public function clearCache(){
		//$old_err = set_error_handler(['self', 'CJAXErrorHandler']);
		if(!isset($_SESSION)){
			@session_start();
		}
		unset($_SESSION['cjax_x_cache']);
			
		if(!headers_sent()){
			@setcookie('cjax_x_cache','');
		}
	}
	
	public function initiate($ajax){
		if(isset($_REQUEST['session_id'])){
			session_id($_REQUEST['session_id']);
			@session_start();
		} 
        elseif(!$ajax->config->fallback && !isset($_SESSION)){
		    @session_start();
		}
	}

	/**
	 * Optional text, replaces the "loading.." text when an ajax call is placed
	 *
	 * @param unknown_type $ms
	 */
	public function text($ms = ''){
		$this->text = $ms;
	}

	/**
	 * CJAX is bein called from within a child directory then you will need to specify
	 * the url where CJAX is located (eg. http://yoursite.com/cjax)
	 *
	 * @param string $path [CJAX URL]
	 */
	public function path($path){
		$this->path = $path;
	}

	public function remotePath(){
		return 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER["SCRIPT_NAME"]).'/cjax';
	}

	public function getFile($file = null){
		return $this->connect($_SERVER['HTTP_HOST'],(isset($_SERVER['SERVER_PORT'])? $_SERVER['SERVER_PORT']:80), $file, true);
	}

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

	/*
	 * Get session or cookie value
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
	
	public function code($data, $tags = true){	
		@ini_set('highlight.string', "#DD0000"); // Set each colour for each part of the syntax
		@ini_set('highlight.comment', "#FF8000"); // Suppression has to happen as some hosts deny access to ini_set and there is no way of detecting this
		@ini_set('highlight.keyword', "#007700");
		@ini_set('highlight.default', "#0000BB");
		@ini_set('highlight.html', "#0000BB");
			
		$data = str_replace("\n\n", "\n", $data);	
		$data = ($tags)? highlight_string("<?php \n" . $data . "\n?>", true)
                       : highlight_string($data, true); 		
		return '<div id="code_highlighted">'.$data."</div>";
	}
	
	public function jsCode($data, $tags = false, $output = null){ 		
		@ini_set('highlight.string', "#DD0000"); // Set each colour for each part of the syntax
		@ini_set('highlight.comment', "#FF8000"); // Suppression has to happen as some hosts deny access to ini_set and there is no way of detecting this
		@ini_set('highlight.keyword', "green");
		@ini_set('highlight.default', "#0000BB");
		@ini_set('highlight.html', "#0000BB");

		$data =  "<script>". highlight_string("\n" . $data ."\n")."</script>"; 		
        return ($tags)? str_replace(['?php', '?&gt;'], ['script type="text/javascript">', '&lt;/script&gt;'], $output)
                      : str_replace(['&lt;?php', '?&gt;'], ['', ''], $data);
	}
	
	public static function errorHandlingConfig(){
		/**Error Handling**/
		@ini_set('display_errors', 1);
		@ini_set('log_errors', 1);
		$level = ini_get('error_reporting');
		if($level > 30719 || $level == 2048){
			@ini_set('error_reporting', $level-E_STRICT);
			$level = ini_get('error_reporting');
		}
		return $level;
	}
}
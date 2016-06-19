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
*   File Last Changed:  06/18/2016           $     
**####################################################################################################    */   

namespace CJAX\Core;

/**
 * The CJAX class that is the core public API used to create AJAX requests/events.
 * By default CJAX follows singleton pattern, which will be changed to DI in future.
 * This class serves as the public API, it provides method used directly in client code.
 * @category CJAX
 * @package Core
 * @author CJ Galindo <cjxxi@msn.com>
 * @copyright (c) 2008, CJ Galindo
 * @link https://github.com/ajaxboy/cjax
 * @version 6.0
 * @since 1.0
 */

class CJAX{

	/**
	 * The cache property, stores a reference to the cache object.
     * @access public
	 * @var Cache
	 */       
    public $cache;
    
	/**
	 * The config property, specifies the configuration variables for CJAX.
     * @access public
	 * @var Config|Ext
	 */         
    public $config;
    
	/**
	 * The coreEvents property, holds a reference to the core events object.
     * @access public
	 * @var CoreEvents
	 */      
    public $coreEvents; 
    
	/**
	 * The domEvents property, stores a reference to the dom events object.
     * @access public
	 * @var DOMEvents
	 */      
    public $domEvents;        
    
	/*
	 * The format property, holds an object with some formatting helpers.
     * @access public
	 * @var Format
	 */
	public $format;    
    
	/**
	 * The pluginManager property, holds a reference to the plugin manager object.
     * @access public
	 * @var PluginManager
	 */      
    public $pluginManager;
    
	/**
	 * The request property, stores a reference to CJAX request object.
     * @access public
	 * @var Request
	 */     
    public $request;
    
	/**
	 * The includes property, specifies if there are external files/libraries to include.
	 * @access public
	 * @var string
	 */	
	public $includes = false;    
    
	/**
	 * The CJAX property, stores the singleton instance of CJAX. 
     * @access private
	 * @var CJAX
     * @static
	 */      
	private static $CJAX;
		
        
	/**
     * The static method getInstance, acquires the singleton instance for CJAX class.
     * This method will be deprecated in future and removed in CJAX 7.0.
     * @access public
     * @return CJAX
     * @static
     * @api
     */
	public static function getInstance(){
        if(!self::$CJAX){
            $initializer = new Initializer(new self);
            self::$CJAX = $initializer->initiateAjax();            
        }
        return self::$CJAX;
	}
    
    /**
     * The click method, assigns a click AJAX event to the given element.
     * @param string  $elementId
     * @param array  $actions
     * @access public
     * @return XmlItem
     * @api
     */	      
	public function click($elementId, $actions = []){
        return ($actions)? $this->exec($elementId, $actions): $this->__call('click', $elementId);
	}
    
	/**
     * The change method, assigns a change AJAX event to the given element.
     * @param string  $elementId
     * @param array  $actions
     * @access public
     * @return XmlItem
     * @api
     */	    
	public function change($elementId, $actions){
		return $this->exec($elementId, $actions, 'change');
	}

	/**
     * The blur method, assigns a blur AJAX event to the given element.
     * @param string  $elementId
     * @param array  $actions
     * @access public
     * @return XmlItem
     * @api
     */	     
	public function blur($elementId, $actions){
		return $this->exec($elementId, $actions, 'blur');
	} 
    
	/**
     * The keyup method, assigns a keyup AJAX event to the given element.
     * @param string  $elementId
     * @param array  $actions
     * @access public
     * @return XmlItem
     * @api
     */	    
	public function keyup($elementId, $actions){
		return $this->exec($elementId, $actions, 'keyup');
	}

	/**
     * The keydown method, assigns a keydown AJAX event to the given element.
     * @param string  $elementId
     * @param array  $actions
     * @access public
     * @return XmlItem
     * @api
     */	       
	public function keydown($elementId, $actions){
		return $this->exec($elementId, $actions, 'keydown');
	}

	/**
     * The keypress method, assigns a keypress AJAX event to the given element.
     * KeyPress is a relatively complex event, it is handled by DomEvents class internally.
     * @param string  $elementId
     * @param mixed  $actions
     * @param string  $key
     * @access public
     * @return XmlItem
     * @api
     */	     
	public function keypress($elementId, $actions, $key = null){
        return $this->domEvents->keypress($elementId, $actions, $key);
	}

	/**
     * The rightclick method, assigns a rightclick AJAX event to the given element.
     * @param string  $elementId
     * @param array  $actions
     * @access public
     * @return XmlItem
     * @api
     */	      
    public function rightclick($elementId, $actions){
		return $this->exec($elementId, $actions, 'contextmenu');        
    }
    
	/**
     * The doubleclick method, assigns a doubleclick AJAX event to the given element.
     * @param string  $elementId
     * @param array  $actions
     * @access public
     * @return XmlItem
     * @api
     */	      
    public function doubleclick($elementId, $actions){
        return $this->exec($elementId, $actions, 'dblclick');      
    }
    
	/**
     * The mouseover method, assigns a mouseover AJAX event to the given element.
     * @param string  $elementId
     * @param array  $actions
     * @access public
     * @return XmlItem
     * @api
     */	    
    public function mouseover($elementId, $actions){
		return $this->exec($elementId, $actions, 'mouseover');        
    }

	/**
     * The mouseout method, assigns a mouseout AJAX event to the given element.
     * @param string  $elementId
     * @param array  $actions
     * @access public
     * @return XmlItem
     * @api
     */	       
    public function mouseout($elementId, $actions){
		return $this->exec($elementId, $actions, 'mouseout');        
    }

	/**
     * The mouseenter method, assigns a mouseenter AJAX event to the given element.
     * @param string  $elementId
     * @param array  $actions
     * @access public
     * @return XmlItem
     * @api
     */	     
    public function mouseenter($elementId, $actions){
		return $this->exec($elementId, $actions, 'mouseenter');        
    }

	/**
     * The mouseleave method, assigns a mouseleave AJAX event to the given element.
     * @param string  $elementId
     * @param array  $actions
     * @access public
     * @return XmlItem
     * @api
     */	        
    public function mouseleave($elementId, $actions){
		return $this->exec($elementId, $actions, 'mouseleave');        
    }

	/**
     * The mousedown method, assigns a mousedown AJAX event to the given element.
     * @param string  $elementId
     * @param array  $actions
     * @access public
     * @return XmlItem
     * @api
     */	    
    public function mousedown($elementId, $actions){
		return $this->exec($elementId, $actions, 'mousedown');        
    }

	/**
     * The mouseup method, assigns a mouseup AJAX event to the given element.
     * @param string  $elementId
     * @param array  $actions
     * @access public
     * @return XmlItem
     * @api
     */	    
    public function mouseup($elementId, $actions){
		return $this->exec($elementId, $actions, 'mouseup');        
    }

	/**
     * The mousemove method, assigns a mousemove AJAX event to the given element.
     * @param string  $elementId
     * @param array  $actions
     * @access public
     * @return XmlItem
     * @api
     */	     
    public function mousemove($elementId, $actions){
		return $this->exec($elementId, $actions, 'mousemove');        
    }

	/**
     * The drag method, assigns a drag AJAX event to the given element.
     * @param string  $elementId
     * @param array  $actions
     * @access public
     * @return XmlItem
     * @api
     */	        
    public function drag($elementId, $actions, $dataTransfer = null){
        $actions = ($dataTransfer)? $this->domEvents->dataTransfer($actions, $dataTransfer): $actions;
        return $this->exec($elementId, $actions, 'drag');     
    }
    
	/**
     * The dragend method, assigns a dragend AJAX event to the given element.
     * @param string  $elementId
     * @param array  $actions
     * @access public
     * @return XmlItem
     * @api
     */	     
    public function dragend($elementId, $actions, $dataTransfer = null){
        $actions = ($dataTransfer)? $this->domEvents->dataTransfer($actions, $dataTransfer): $actions;
        return $this->exec($elementId, $actions, 'dragend');     
    }
    
	/**
     * The dragenter method, assigns a dragenter AJAX event to the given element.
     * @param string  $elementId
     * @param array  $actions
     * @access public
     * @return XmlItem
     * @api
     */    
    public function dragenter($elementId, $actions, $dataTransfer = null){
        $actions = ($dataTransfer)? $this->domEvents->dataTransfer($actions, $dataTransfer): $actions;
        return $this->exec($elementId, $actions, 'dragenter');     
    } 
    
	/**
     * The dragleave method, assigns a dragleave AJAX event to the given element.
     * @param string  $elementId
     * @param array  $actions
     * @access public
     * @return XmlItem
     * @api
     */      
    public function dragleave($elementId, $actions, $dataTransfer = null){
        $actions = ($dataTransfer)? $this->domEvents->dataTransfer($actions, $dataTransfer): $actions;
        return $this->exec($elementId, $actions, 'dragleave');     
    }    
    
	/**
     * The dragover method, assigns a dragover AJAX event to the given element.
     * @param string  $elementId
     * @param array  $actions
     * @access public
     * @return XmlItem
     * @api
     */     
    public function dragover($elementId, $actions, $dataTransfer = null){
        $actions = ($dataTransfer)? $this->domEvents->dataTransfer($actions, $dataTransfer): $actions;
        return $this->exec($elementId, $actions, 'dragover');     
    }      
    
	/**
     * The dragstart method, assigns a dragstart AJAX event to the given element.
     * @param string  $elementId
     * @param array  $actions
     * @access public
     * @return XmlItem
     * @api
     */        
    public function dragstart($elementId, $actions, $dataTransfer = null){
        $actions = ($dataTransfer)? $this->domEvents->dataTransfer($actions, $dataTransfer): $actions;
        return $this->exec($elementId, $actions, 'dragstart');     
    }     
    
	/**
     * The drop method, assigns a drop AJAX event to the given element.
     * @param string  $elementId
     * @param array  $actions
     * @access public
     * @return XmlItem
     * @api
     */     
    public function drop($elementId, $actions, $dataTransfer = null){
        $actions = ($dataTransfer)? $this->domEvents->dataTransfer($actions, $dataTransfer): $actions;
        return $this->exec($elementId, $actions, 'drop');     
    }          
    
	/**
     * The call method, it is used to pass extra variables to POST ajax.
     * This method is currently used in iframe uploads.
     * @param array  $vars
     * @access public
     * @return void
     * @api
     */       
	public function ajaxVars($vars){
		if(is_array($vars)){
			$vars = http_build_query($vars);
		}
        $data = ['do' => 'ajaxVars', 'vars' => $vars];
        $this->coreEvents->xml($data);
	}  
    
	/**
     * The cacheWrapper method, create a cache wrapper.
     * It provides a public API for singleton ajax object to call cacheWrapper method on CoreEvents object.
     * @param array  $wrapper
     * @access public
     * @return void
     * @api
     */     
	public function cacheWrapper($wrapper = []){
        $this->coreEvents->cacheWrapper($wrapper);
    }   
    
	/**
     * The call method, create Ajax calls with CJAX.
     * It provides a public API for singleton ajax object to call call method on DOMEvents object.
     * @param string  $url
     * @param string  $containerId
     * @param string  $confirm
     * @access public
     * @return XmlItem
     * @api
     */       
	public function call($url, $containerId = null, $confirm = null){
        return $this->domEvents->call($url, $containerId, $confirm);
    }     
    
	/**
     * The camelize method, create camelized string with given parameter.
     * @param string  $string
     * @param bool  $ucfirst
     * @access public
     * @return string
     * @api
     */      
	public function camelize($string, $ucfirst = true){
		$string = str_replace(['-', '_'], ' ', $string);
		$string = str_replace(' ', '', ucwords($string)); 
		return ($ucfirst)? ucfirst($string): lcfirst($string);
	}   
    
	/**
     * The clear method, clears all cjax cookie and session data.
     * @param bool  $all
     * @access public
     * @return void
     * @api
     */     
	public function clear($all = false){
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
    
	/**
     * The code method, shows and formats custom code.
     * It provides a public API for singleton ajax object to call code method on Format object.
     * @param string  $data
     * @param bool  $tags
     * @access public
     * @return string
     * @api
     */     
	public function code($data, $tags = true){	
        return $this->format->code($data, $tags);
    }    
    
	/**
	 * 
	 * The crossdomain method, performs cross domain requests.
	 * @param string $url
     * @access public
     * @return void
     * @api
	 */
	public function crossdomain($url){
		$response = $this->coreEvents->remote($url);
		if(!$response || strpos(strtolower($response), 'not found') !== false){
			return;
		}
		print $response;
	}    
    
	/**
     * The dialog method, triggers a CJAX dialog with specified information.
     * @param string  $content
     * @param string  $title
     * @param array  $options
     * @access public
     * @return XmlItem
     * @api
     */      
	public function dialog($content, $title = null, $options = []){
		$content = $this->format->output($content, $title);
		return $this->overlayContent($content, $options);
	}    
    
	/**
	 * The debug method, shows debug information for CJAX processes.
	 * @param array  $data
	 * @param string  $title
     * @param array  $extra
     * @access public
     * @return void
     * @api
	 */
	public function debug($data, $title = 'Debug Information', $extra = null){
		if($extra){
			$extra .= '<br />';
		}
		$this->dialog($extra.'<pre>'.print_r($data,1).'</pre>', $title, ['top'=> 100]);
	}
    
	/**
	 * The error method, display an error message in the middle of the screen.
     * If number of seconds is specified, the message will disappear after the time elapses.
	 * @param string  $msg
	 * @param int  $seconds
     * @return int
     * @api
	 */      
	public function error($msg = "Error!", $seconds = 15){
		return $this->message($this->format->message($msg, Format::CSS_ERROR), $seconds);
	}       
    
    
	/**
     * The exec method, binds any events to given elements.
     * It provides a public API for singleton ajax object to call exec method on CoreEvents object.
     * @param string  $selector
     * @param array  $actions
     * @param string  $event
     * @access public
     * @return XmlItem
     * @api
     */         
	public function exec($selector, $actions, $event = "click"){
        return $this->coreEvents->exec($selector, $actions, $event);
    }       
    
	/**
     * The form method, converts a form into AJAX form and submits this form.
     * It provides a public API for singleton ajax object to call form method on DOMEvents object.
     * @param string  $url
     * @param string  $formId
     * @param string  $containerId
     * @param string  $confirm
     * @access public
     * @return XmlItem
     * @api
     */     
    public function form($url, $formId = null, $containerId = null, $confirm = null){
        return $this->domEvents->form($url, $formId, $containerId, $confirm);
    }    
    
	/**
	 * The get method, acquires session or cookie value.
     * If the parameter $getAsObject is true, array values will be converted into objects.
     * It provides a public API for singleton ajax object to call get method on CoreEvents object.
     * @param string  $setting
     * @param bool  $getAsObject
     * @access public
     * @return mixed
     * @api
	 */
	public function get($setting, $getAsObject = false){
        return $this->coreEvents->get($setting, $getAsObject);
    }    
    
	/**
     * The import method, imports css and javascript files.
     * It provides a public API for singleton ajax object to call form method on CoreEvents object.
     * @param string  $file
     * @param int  $loadTime
     * @access public
     * @return int
     * @api
     */      
	public function import($file, $loadTime = 0){
        return $this->coreEvents->import($file, $loadTime);
    }    
    
	/**
	 * The info method, display a custom information message in the middle of the screen.
     * If number of seconds is specified, the message will disappear after the time elapses.
	 * @param string  $msg
	 * @param int  $seconds
     * @return int
     * @api
	 */       
	public function info($msg = null, $seconds = 3){
		return $this->message($this->format->message($msg, Format::CSS_INFO), $seconds);
	}     
    
	/**
	 * The init method, initiates the process of sending the javascript file to the application.
     * It provides a public API for singleton ajax object to call init method on CoreEvents object.
	 * @param bool  $min
     * @access public
	 * @return string
     * @api
	 */
	public function init($min = true){
        return $this->coreEvents->init($min);
	}         
    
	/**
	 * The input method, fetches input for CJAX request.
     * It provides a public API for singleton ajax object to call input method on CoreEvents object.
	 * @param string  $value
     * @access public
     * @return bool
     * @api
	 */       
	public function input($value = 'cjax'){
        return $this->coreEvents->input($value);
    }    
    
	/**
	 * The isAjaxRequest method, tells if the given request is ajax request.
     * It provides a public API for singleton ajax object to call isPlugin method on CoreEvents object.
     * @access public
     * @return bool
     * @api
	 */
	public function isAjaxRequest(){
        return $this->coreEvents->isAjaxRequest();
	}        
    
	/**
	 * The isPlugin method, tells if plugin exists or not regardless of it having a class or not.
     * It provides a public API for singleton ajax object to call isPlugin method on PluginManager object.
	 * @param string  $pluginName
     * @access public
     * @return bool
     * @api
	 */
	public function isPlugin($pluginName){
        return $this->pluginManager->isPlugin($pluginName);
	}       
    
	/**
	 * The js method, it sets up the directory where the CJAX FRAMEWORK resides.
     * It provides a public API for singleton ajax object to call js method on CoreEvents object.
	 * @param string  $jsdir
     * @param bool  $force
     * @access public
     * @return bool
     * @api
	 */
	public function js($jsdir, $force = false){
		return $this->coreEvents->js($jsdir, $force);
	}        
    
	/**
     * The jsCode method, displays and formats javascript code.
     * It provides a public API for singleton ajax object to call jsCode method on CoreEvents object.
     * @param string  $data
     * @param bool  $tags
     * @param string  $output
     * @access public
     * @return string
     * @api
     */       
	public function jsCode($data, $tags = false, $output = null){ 
        return $this->format->jsCode($data, $tags, $output);
    }    
    
	/**
     * The jsonEncode method, encode an array to json format.
     * It provides a public API for singleton ajax object to call jsonEncode method on CoreEvents object.
     * @param array  $array
     * @access public
     * @return string
     * @api
     */         
	public function jsonEncode($array){
		return $this->coreEvents->jsonEncode($array);
	}
    
	/**
	 * The loading method, display a custom loading message in the middle of the screen.
     * If number of seconds is specified, the message will disappear after the time elapses.
	 * @param string  $msg
	 * @param int  $seconds
     * @return int
     * @api
	 */        
	public function loading($msg = "Loading..."){
		return $this->message($this->format->message($msg, Format::CSS_SUCCESS));
	}        
    
	/**
	 * The location method, redirect the page.
	 * This is a recommended alternative to the built-in php function header().
	 * @param string  $url 
     * @access public
     * @return int
     * @api
	 */
	public function location($url = null){		
		return $this->coreEvents->xml(['do' => 'location', 'url' => $url]);
	}      
    
	/**
	 * The message method, display a message in the middle of the screen.
     * If number of seconds is specified, the message will disappear after the time elapses.
	 * @param string  $message
	 * @param int  $seconds
     * @param string  $containerId
     * @return int
     * @api
	 */
	public function message($message, $seconds = 3, $containerId = 'cjax_message'){
        $data = ['do' => '_message', 'message' => $message, 
                 'time' => $seconds, 'message_id' => $containerId];
		return $this->coreEvents->xml($data);
	}    
    
	/**
     * The overLay method, shows an overlay of resources from a given url.
     * It provides a public API for singleton ajax object to call overLay method on DOMEvents object.
     * @param string  $url
     * @param array  $options
     * @param bool  $useCache
     * @access public
     * @return XmlItem
     * @api
     */     
	public function overLay($url = null, $options = [], $useCache = false){
        return $this->domEvents->overLay($url, $options, $useCache);
    }  
    
	/**
     * The overLay method, shows an overlay with supplied html content.
     * It provides a public API for singleton ajax object to call overLayContent method on DOMEvents object.
     * @param string  $content
     * @param array  $options
     * @access public
     * @return XmlItem
     * @api
     */       
    public function overlayContent($content = null, $options = null){
        return $this->domEvents->overlayContent($content, $options);        
    }    
    
	/**
	 * The plugin method, loads a plugin given its name.
     * It provides a public API for singleton ajax object to call plugin method on PluginManager object.
	 * @param string  $pluginName
     * @param bool  $loadController
     * @access public
     * @return Plugin
     * @api
	 */    
	public function plugin($pluginName, $loadController = false){
		return $this->pluginManager->plugin($pluginName, $loadController);
	}    
    
	/**
     * The post method, it uses call() to post stuff.
     * It provides a public API for singleton ajax object to call post method on DOMEvents object.
     * @param string  $url
     * @param array  $vars
     * @access public
     * @return XmlItem
     * @api
     */      
    public function post($url, $vars = []){
        return $this->domEvents->post($url, $vars);
    }  
    
	/**
     * The post method, it passes data to PHP $_POST array.
     * It provides a public API for singleton ajax object to call postVars method on DOMEvents object.
     * @param array  $vars
     * @access public
     * @return void
     * @api
     */      
    public function postVars($vars = []){
        $this->domEvents->postVars($vars);
    }     
    
	/**
	 * 
	 * The prevent method, it prevents other APIS and saving them to stack that can be retrieved by plugins.
     * @param string  $pluginName
     * @param string  $id
	 * @param int  $count
	 * @access public
     * @return void
     * @api
	 */
	public function prevent($pluginName, $id, $count = 1){
        $data = ['do' => 'prevent', 'count' => $count, 
                 'uniqid' => $id, 'plugin_name' => $pluginName];
		$this->xml($data);
	}        
    
	/**
	 * The process method, display a processing message in the middle of the screen.
     * If number of seconds is specified, the message will disappear after the time elapses.
	 * @param string  $msg
	 * @param int  $seconds
     * @return int
     * @api
	 */        
	public function process($msg = "Processing...", $seconds = 3){
		return $this->message($this->format->message($msg, Format::CSS_PROCESS), $seconds);
	}    
    
	/**
	 * The property method, sets value to an element.
	 * @param string  $elementId
	 * @param string  $value
     * @access public
     * @return int
     * @api
	 */
	public function property($elementId, $value = '', $clearDefault = false, $selectText = false){
		$options = ['do' => 'property', 'elementId' => $elementId, 'clear_text' => $clearDefault,
                    'select_text' => $selectText, 'value' => $value];
		return $this->coreEvents->xml($options);
	}

	/**
	 * The readCache method, reads available cache for CJAX.
	 * @param string  $crc32
     * @access public
     * @return string
     * @api
	 */    
 	public function readCache($crc32 = null){
        return $this->coreEvents->readCache($crc32);
    }   
    
	/**
	 * The remove method, it will remove an specified element from the page
	 * @param string  $obj
     * @access public
     * @return void
     * @api
	 */
	public function remove($obj){
		$this->coreEvents->xml(['do' => 'remove', 'elementId' => $obj]);
	}        
    
	/**
	 * The save method, saves custom data for CJAX to use.
     * It provides a public API for singleton ajax object to call post method on CoreEvents object.
	 * @param string  $setting
     * @param mixed  $value
     * @param bool  $useCookie
     * @access public
	 * @return void
     * @api
	 */    
	public function save($setting, $value = null, $useCookie = false){
        $this->coreEvents->save($setting, $value, $useCookie);
    }    
    
	/**
	 * The select method, selects an item/option in an AJAX change event.
	 * @param string  $element
	 * @param array  $options
     * @param mixed  $selected
     * @param bool  $allowInput
     * @access public
     * @return int
     * @api
	 */    
 	public function select($element, $options = [], $selected = null, $allowInput = false){
        $select = ['do' => 'select', 'elementId' => $element, 'selected' => $selected,
                   'options' => $options, 'allow_input' => $allowInput];	
		return $this->coreEvents->xml($select);
	}
 
	/**
	 * The style method, assigns styles to an element.
	 * @param string  $elementId
	 * @param array $style
     * @access public
     * @return int
     * @api
	 */
	public function style($elementId, $style = []){
        $data = ['do' => 'style', 'element' => $elementId, 'style' => $this->coreEvents->mkArray($style)];
		return $this->coreEvents->xml($data);
	}
    
	/**
	 * The success method, display a success message in the middle of the screen.
     * If number of seconds is specified, the message will disappear after the time elapses.
	 * @param string  $msg
	 * @param int  $seconds
     * @return int
     * @api
	 */    
	public function success($msg = "Success!", $seconds = 3){
        return $this->message($this->format->message($msg, Format::CSS_SUCCESS));
	}    
    
	/**
	 * The tapCache method, acquires available cache for CJAX.
	 * @param string  $crc32
     * @access public
     * @return string
     * @api
	 */       
	public function tapCache($crc32){
		$cache = $this->readCache('cjax-'.$crc32);
		return ($cache)? $cache[$crc32]: null;
	}    
    
	/**
	 * The update method, updates any element on the page by specifying the element ID.
	 * Usage:  <code>$ajax->update('elementId', $content);</code>
	 * @param string  $elementId
	 * @param string  $data
     * @access public
     * @return int
     * @api
	 */
	public function update($elementId, $data = null){
		return $this->property($elementId, $data);
	} 
    
	/**
	 * The wait method, it will execute a command in a specified amouth of time.
     * It provides a public API for singleton ajax object to call wait method on CoreEvents object.
	 * @param int  seconds
	 * @param bool  $milliseconds
	 * @param bool  $expand
     * @access public
     * @return CoreEvents
     * @api
	 */    
	public function wait($seconds, $milliseconds = false, $expand = true){
        return $this->coreEvents->wait($seconds, $milliseconds, $expand);
    }    
    
	/**
	 * The waitFor method, it will execute after a file loads.
	 * @param string  $file
	 * @param bool  $waitForFile
     * @access public
     * @return void
     * @api
	 */     
	public function waitFor($file, $waitForFile){
		$xml = $this->coreEvents->import($file);		
		$xml->waitfor = $waitForFile;
	}       
    
	/**
	 * The waitReset method, it removes waiting times.
     * @access public
     * @return int
     * @api
	 */
	public function waitReset(){	
		return $this->coreEvents->xml(['do' => '_wait', 'time_reset' => 1]);		
	}    
    
	/**
	 * The warning method, display a warning message in the middle of the screen.
     * If number of seconds is specified, the message will disappear after the time elapses.
	 * @param string  $msg
	 * @param int  $seconds
     * @return int
     * @api
	 */       
	public function warning($msg = "Invalid Input", $seconds = 4){
		return $this->message($this->format->message($msg, Format::CSS_WARNING), $seconds);
	}       
    
	/**
     * The magic method __get, dynamically gets a setting/parameter for CJAX.
	 * @param string  $setting
     * @access public
     * @return mixed
     */	      
	public function __get($setting){
        if(property_exists($this->coreEvents, $setting)){
            return $this->coreEvents->$setting;
        }
        else{
            return null;
        }
	}
	
	/**
     * The magic method __set, dynamically sets a parameter/setting for CJAX.
	 * @param string  $setting
     * @param mixed  $value
     * @access public
     * @return void
     */	      
	public function __set($setting, $value){
		if($this->pluginManager->isPlugin($setting)){
			return;
		}
        if(property_exists($this->coreEvents, $setting)){
            $this->coreEvents->$setting = $value;
            return;
        }
		if(is_object($value)){
			switch($value->name){
				case 'call':
					$value->container_id = $setting;
				    break;
				case 'form':
					$this->coreEvents->exec($setting, $value->id);
				    break;
			}
			return $this->coreEvents->simpleCommit();
		} 
        else{
			$xml = $this->property($setting, $value);
			$this->coreEvents->simpleCommit();
			return $xml;
		}
	}       
    
	/**
     * The magic method __call, carries out magical operation for CJAX events.
	 * @param string  $method
     * @param array  $args
     * @access public
     * @return void
     */	       
	public function __call($method, $args){
		$params = range('a', 'z');
		$pParams = [];
		if($args){
			if(!is_array($args)){
				$args = [$args];
			}
			foreach($args as $v){
				$pParams[current($params)] =  $v;
				next($params);
			}
		}

		if($this->pluginManager->isPlugin($method)){
			$entryId = null;
			if($pParams){
				$params = func_get_args();
                $data = ['do' => $this->pluginManager->method($method), 'is_plugin' => $method,
                         'data' => $pParams, 'file' => $this->pluginManager->file($method)];
				$data['filename'] = preg_replace("/.*\//",'', $data['file']);				
				$entryId = $this->coreEvents->xmlItem($this->coreEvents->xml($data), $method)->id;
			}
            
			$plugin = $this->pluginManager->getPlugin($method, $params, $entryId);
			if($pParams){
				$this->pluginManager->instanceTriggers($plugin, $pParams);
			}
			return $plugin;
		} 
        else{
			$data = ['do' => '_fn', 'fn' => $method, 'fn_data' => $pParams];	
			$item = $this->coreEvents->xmlItem($this->coreEvents->xml($data), 'fn');
			$item->selector = $method;
			return $item;
		}
	}      
}

function ajax(){
    return CJAX::getInstance();
}
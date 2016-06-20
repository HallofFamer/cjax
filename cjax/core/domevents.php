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
 * The DOMEvents class that implements methods to handle specific AJAX events.
 * @category CJAX
 * @package Core
 * @author Ordland Euroboros <halloffamer@mysidiarpg.com>
 * @copyright (c) 2008, CJ Galindo
 * @link https://github.com/ajaxboy/cjax
 * @version 6.0
 * @since 6.0
 */

class DOMEvents{
    
    /**
     * The coreEvents property, stores an instance of injected CoreEvents object.
     * @access private
     * @var CoreEvents
     */	     
    private $coreEvents;     
    
    /**
     * The loading property, defines whether to force system to adapt to a loading or not loading state.
     * @access public
     * @var bool
     */	     
	public $loading = false;        
    
	/**
	 * The post property, stores an array of post data used for PHP.
     * @access public
	 * @var array
	 */    
	public $post = []; 
    
	/**
	 * The text property, specifies the text to show when the page is loading
	 * By default the text is "loading..", but it can be replaced.
     * @access public
	 * @var string
	 */
	public $text = null;        
    
    
	/**
     * The constructor for DOMEvents class, creates an instance of DOMEvents object.
	 * @param CoreEvents  $coreEvents
     * @access public
     * @return DOMEvents
     */	    
    public function __construct(CoreEvents $coreEvents){
        $this->coreEvents = $coreEvents;
    }        
    
    /**
	 * The call method, creates Ajax calls.
	 * @param string  $url
	 * @param string  $containerId
	 * @param string  $confirm
     * @access public
	 * @return string
	 */
	public function call($url, $containerId = null, $confirm = null){
		if(preg_match('/^https?/', $url)){
			$out['crossdomain'] = true;
		}
		$out['do'] = '_call';
		$out['url'] = $url;
		
		if($this->post){
			if(is_array($this->post)){
				$args = http_build_query($this->post);
				$out['args'] = $args;
				$out['post'] = true;
			} 
            else{
				$out['post'] = true;
			}
		}
		if($containerId){
            $out['container_id'] = $containerId;
        }
		if(is_bool($this->text) && $this->text === false){
			$out['text'] = "no_text";
		} 
        elseif($this->text){
			$out['text'] = $this->text;
		}
		
		if($confirm){
            $out['confirm'] = $confirm;
        }
		if($this->loading){
			$out['is_loading'] = true;
		}		
		return $this->coreEvents->xmlItem($this->coreEvents->xml($out), 'call', 'api');
	}             
    
	/**
	 * 
	 * The callc method, makes several requests without exec event.
	 * This method is an alternative to call() when the requests go so fast and need just a little timeout to work properly.
	 * @param string  $url
	 * @param string  $containerId
	 * @param string $confirm
     * @access public
     * @return XmlItem
	 */
	public function callc($url, $containerId = null, $confirm = null){
		$this->coreEvents->wait(200, true); // 200 milliseconds
		return $this->call($url, $containerId, $confirm);
	}    
    
	/**
     * The dataTransfer method, handles data transfer for drag and drop event.
     * @param string  $actions
     * @param array  $dataTransfer
     * @access public
     * @return XmlItem
     */         
    public function dataTransfer($actions, $dataTransfer = null){
		if(is_array($dataTransfer) && $actions instanceof XmlItem){
            if($dataTransfer['dragImage']){
                $dataTransfer['dragImage'] = (object)$dataTransfer['dragImage'];
            }
			$actions->dataTransfer = $dataTransfer;
			$actions->buffer = ['dataTransfer' => $dataTransfer];
		}
        return $actions;
    }
    
    /**
     * The form method, converts a form into AJAX form and submits this form.
     * @param string $url  
     * @param string $formId  
     * @param string $containerId
     * @param string $confirm  
     * @access public
     * @return XmlItem
     */
    public function form($url, $formId = null, $containerId = null, $confirm = null){
        $out = ['do' => '_form', 'url' => $url];        
        if($formId) $out['form_id'] = $formId;
        if(!is_null($containerId)){
        	$out['container'] = $containerId;
        }
        
    	if(!is_null($this->text)){
			$out['text'] = $this->text;
		} 
        elseif($this->text === false){
			$out['text'] = 'Loading...';
		}

        if($confirm){
            $out['confirm'] = $confirm;
        }

        if(is_array($this->post)){
        	$args = http_build_query($this->post);
        	$out['args'] = $args;
        	$out['post'] = true;
        }
        else{
        	$out['post'] = 1;
        }
        return $this->coreEvents->xmlItem($this->coreEvents->xml($out), 'form', 'api');       
    }      
    
    /**
     * The keypress method, converts a form into AJAX form and submits this form.
     * @param string $elementId
     * @param mixed  $actions
     * @param string|array  $key 
     * @access public
     * @return XmlItem
     */
 	public function keypress($elementId, $actions, $key = null){
		if($key && $actions instanceof XmlItem){
			if(is_array($key)){
				foreach($key as $k => $v){
					if($v == 'enter'){
                        $v = 13;
                    }
					$keys[$v] = $v;
				}
			} 
            else{
				$keys = [$key => $key];
			}
			$actions->key = $keys;
			$actions->buffer = ['key' => $keys];
		}
		return $this->coreEvents->exec($elementId, $actions, 'keypress');
	}   
    
	/**
	 *
	 * The overLay method, shows an overlay of resources from a given url.
     * If useCache parameter is true, the overlayed content will be cached until page refreshes.
	 * @param string  $url
     * @param array  $options
	 * @param bool  $useCache
	 * @access public
     * @return XmlItem
	 */
	public function overLay($url = null, $options = [], $useCache = false){
        $data = ['do' => '_overLay'];
		if(!isset($options['click_close'])){
			$options['click_close'] = true;
		}
		if($options && is_array($options)){
			foreach($options as $k => $v){
				if($v instanceof Plugin){
					$xml = $v->xmlObject();
					$xmlData = $xml->pack();
					$xml->delete();
				} 
                else{
					$xmlData = $v;
				}
				$data[$k] = $xmlData;
			}
			$data['options'] = $options;
		}
		$data['url'] = $url;
		$data['cache'] = $useCache;
		if($url){
			$data['template'] = $this->template('overlay.html');
		}		
		return $this->coreEvents->xmlItem($this->coreEvents->xml($data), 'overlay', 'api');
	}    
    
	/**
     * The overLay method, shows an overlay with supplied html content.
     * @param string  $content
     * @param array  $options
     * @access public
     * @return XmlItem
     * @api
     */  
	public function overlayContent($content = null, $options = null){
        $data = ['do' => '_overLayContent', 'content' => $content];
		if(!isset($options['click_close'])){
			$options['click_close'] = true;
		}
		
		if($options && is_array($options)){
			foreach($options as $k => $v ){
				$data[$k] = $v;
			}
			$data['options'] = $this->coreEvents->mkArray($options);
		}
		$data['template'] = $this->coreEvents->encode($this->template('overlay.html'));
		return $this->coreEvents->xmlItem($this->coreEvents->xml($data), 'overlayContent', 'api');
	} 
    
	/**
	 * The post method, makes a post request with data assigned to $_POST array.
	 * @param string  $url
	 * @param array  $vars
     * @access public
     * @return void
	 */    
	public function post($url, $vars = []){
		if(is_array($vars)){
			$this->post = $vars;
		} 
        elseif(!$vars){
			$vars = parse_url($url, PHP_URL_PATH);
		}
		$this->call($url);
	}    
    
	/**
     * The post method, it passes data to PHP $_POST array.
     * @param array  $vars
     * @access public
     * @return void
     * @api
     */   
    public function postVars($vars = []){
        $this->post = $vars;
    }         
    
	/**
	 * The template method, loads a template file for CJAX.
	 * @param string  $templateName
     * @access public
     * @return string
	 */       
	public function template($templateName){
		return file_get_contents(CJAX_HOME."/assets/templates/{$templateName}");		
	}    
    
	/**
	 * The text method, optionally replaces the "loading.." text when an ajax call is placed.
	 * @param string  $ms
     * @access public
     * @return void
	 */
	public function text($ms = ''){
		$this->text = $ms;
	}   
}
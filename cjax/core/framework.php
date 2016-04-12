<?php
/** ################################################################################################**   
* Copyright (c)  2008  CJ.   
* Permission is granted to copy, distribute and/or modify this document   
* under the terms of the GNU Free Documentation License, Version 1.2   
* or any later version published by the Free Software Foundation;   
* Provided 'as is' with no warranties, nor shall the autor be responsible for any mis-use of the same.     
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

/**
 * Load core events
 */

namespace CJAX\Core; 

class Framework Extends CoreEvents{
	
    private static $overLay = [];

    
	public function click($elementId, $actions = []){
		if(!$actions){
			return $this->__call('click', $elementId);
		}
		return $this->Exec($elementId, $actions);
	}
	
	public function change($elementId, $actions){
		return $this->Exec($elementId, $actions, 'change');
	}
	
	public function blur($elementId, $actions){
		return $this->Exec($elementId, $actions, 'blur');
	}
	
	public function keyup($elementId, $actions){
		return $this->Exec($elementId, $actions, 'keyup');
	}

	public function keydown($elementId, $actions){
		return $this->Exec($elementId, $actions, 'keydown');
	}
	
	public function keypress($elementId, $actions, $key = null){
		if($key && $actions instanceof XmlItem){
			if(is_array($key)){
				foreach($key as $k => $v){
					if($v == 'enter') $v = 13;
					$_keys[$v] = $v;
				}
			} 
            else{
				$_keys = [$key => $key];
			}
			$actions->key = $_keys;
			$actions->buffer = ['key' => $_keys];
		}
		return $this->Exec($elementId, $actions, 'keypress');
	}

    public function rightclick($elementId, $actions){
		return $this->Exec($elementId, $actions, 'contextmenu');        
    }
    
    public function doubleclick($elementId, $actions){
        return $this->Exec($elementId, $actions, 'dblclick');      
    }
    
    public function mouseover($elementId, $actions){
		return $this->Exec($elementId, $actions, 'mouseover');        
    }

    public function mouseout($elementId, $actions){
		return $this->Exec($elementId, $actions, 'mouseout');        
    }

    public function mouseenter($elementId, $actions){
		return $this->Exec($elementId, $actions, 'mouseenter');        
    }

    public function mouseleave($elementId, $actions){
		return $this->Exec($elementId, $actions, 'mouseleave');        
    }

    public function mousedown($elementId, $actions){
		return $this->Exec($elementId, $actions, 'mousedown');        
    }

    public function mouseup($elementId, $actions){
		return $this->Exec($elementId, $actions, 'mouseup');        
    }

    public function mousemove($elementId, $actions){
		return $this->Exec($elementId, $actions, 'mousemove');        
    }
    
    public function drag($elementId, $actions, $dataTransfer = null){
        if($dataTransfer) $actions = $this->dataTransfer($actions, $dataTransfer);
        return $this->Exec($elementId, $actions, 'drag');     
    }
    
    public function dragend($elementId, $actions, $dataTransfer = null){
        if($dataTransfer) $actions = $this->dataTransfer($actions, $dataTransfer);
        return $this->Exec($elementId, $actions, 'dragend');     
    }
    
    public function dragenter($elementId, $actions, $dataTransfer = null){
        if($dataTransfer) $actions = $this->dataTransfer($actions, $dataTransfer);
        return $this->Exec($elementId, $actions, 'dragenter');     
    } 
    
    public function dragleave($elementId, $actions, $dataTransfer = null){
        if($dataTransfer) $actions = $this->dataTransfer($actions, $dataTransfer);
        return $this->Exec($elementId, $actions, 'dragleave');     
    }    
    
    public function dragover($elementId, $actions, $dataTransfer = null){
        if($dataTransfer) $actions = $this->dataTransfer($actions, $dataTransfer);
        return $this->Exec($elementId, $actions, 'dragover');     
    }      
    
    public function dragstart($elementId, $actions, $dataTransfer = null){
        if($dataTransfer) $actions = $this->dataTransfer($actions, $dataTransfer);
        return $this->Exec($elementId, $actions, 'dragstart');     
    }     
    
    public function drop($elementId, $actions, $dataTransfer = null){
        if($dataTransfer) $actions = $this->dataTransfer($actions, $dataTransfer);
        return $this->Exec($elementId, $actions, 'drop');     
    }     
	
    private function dataTransfer($actions, $dataTransfer =nulll){
		if(is_array($dataTransfer) && $actions instanceof XmlItem){
            if($dataTransfer['dragImage']) $dataTransfer['dragImage'] = (object)$dataTransfer['dragImage'];
			$actions->dataTransfer = $dataTransfer;
			$actions->buffer = ['dataTransfer' => $dataTransfer];
		}
        return $actions;
    }

	public function __get($setting){
		/*$value = self::input($setting);
		if(!$value) {
			if($a = self::input('a')){
				if(isset($a[$setting])){
					$value = $a[$setting];
				}
			}
		}
		if($value && function_exists('cleanInput')){
			$value = cleanInput($value);
		} 
		return $value;*/
	}
	
	public function __call($method, $args){
		$list = [];
		$params = range('a','z');

		$pParams = [];
		if($args){
			if(!is_array($args)){
				$args = array($args);
			}
			foreach($args as $v){
				$pParams[current($params)] =  $v;
				next($params);
			}
		}

		if($this->isPlugin($method)){
			$entryId = null;
            $pluginClass = new Plugin;
			if($pParams) {
				$params = func_get_args();
				$data['do'] = $pluginClass->method($method);
				$data['is_plugin'] = $method;
				$data['data'] = $pParams;
				$data['file'] = $pluginClass->file($method);
				$data['filename'] = preg_replace("/.*\//",'', $data['file']);
				
				$entryId = $this->xmlItem($this->xml($data), $method)->id;
			}
			$plugin = Plugin::getPluginInstance($method, $params , $entryId);
			if($pParams) {
				$pluginClass->instanceTriggers($plugin, $pParams);
			}
			return $plugin;
		} 
        else{
			$data = [];
			$data['do'] = '_fn';
			$data['fn'] = $method;
			$data['fn_data'] = $pParams;
			
			$item = $this->xmlItem($this->xml($data),'fn');
			$item->selector = $method;
			return  $item;
		}
	}
	
	public function waitFor($file, $waitForFile){
		$xml = $this->import($file);		
		$xml->waitfor = $waitForFile;
	}
	
	/**
	 * 
	 * Prevent other APIS and saving them to stack that can be retrived by plugins.
	 * @param unknown_type $count
	 * @param unknown_type $call_id
	 */
	public function prevent($pluginName, $id, $count = 1){
		$data['do'] = 'prevent';
		$data['count'] = $count;
		$data['uniqid'] = $id;
		$data['plugin_name'] = $pluginName;
		$this->xml($data);
	}
	
	/**
	 * Bind events to elements
	 * 
	 * @param $selector
	 * @param $actions
	 * @param $event
	 */
	public function Exec($selector , $actions , $event = "click"){
		if(!$this->getCache()){
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
		$_actions = [];

		if($actions && is_array($actions)){			
			$cache = CoreEvents::$cache;
			
			foreach($actions as $k => $v){
				if(is_object($v) && ($v instanceof XmlItem || $v instanceof Plugin)){
					if($v instanceof Plugin){
						$v->element_id = $selector;
						$v->xml->element_id = $selector;
						if(method_exists($v, 'onEvent')){
							call_user_func('onEvent', $v, $selector);
						}
					}
					
					if(isset(CoreEvents::$callbacks[$v->id]) && CoreEvents::$callbacks[$v->id]){
						$v->attach(CoreEvents::$callbacks[$v->id]);
						foreach(CoreEvents::$callbacks[$v->id] as $k2 => $v2){
							unset(CoreEvents::$cache[$k2]);
						}
						
					}
					$_actions[$v->id] = $v->xml();
					$v->delete();
				} 
                else{
					if(is_object($v)){
						//some functions return the ajax object?
						continue;
					}
					$_actions[$v] = CoreEvents::$cache[$v];
					unset(CoreEvents::$cache[$v]);
				}
			}
			return $this->AddEventTo($selector, $_actions, $event);
		}
			
		if($actions instanceof XmlItem || $actions instanceof Plugin){
			if($actions instanceof Plugin){
				$actions->element_id = $selector;
				$actions->xml->element_id = $selector;
				if(method_exists($actions, 'onEvent')){
					call_user_func('onEvent', $actions, $selector);
				}
			}
			
			$item = $actions->xml();
			$item['event'] = $event;
			if(isset(CoreEvents::$callbacks[$actions->id]) && CoreEvents::$callbacks[$actions->id]){
				$item['callback'] = $this->processScache(CoreEvents::$callbacks[$actions->id]);
				$item['callback'] = $this->mkArray($item['callback'],'json', true);
			}
			$actions->delete();
			return $this->AddEventTo($selector, [$actions->id => $item],$event);
		} 
        else{
			$_actions = CoreEvents::$cache[$actions];
			$_actions['event'] = $event;
			$this->removeLastCache(1);
			return $this->AddEventTo($selector, [$actions => $_actions],$event);
		}
	}
	
	/**
	 * 
	 *  Uses call() to post stuff
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
	 * 
	 * Making several requests without Exec event.
	 * This is an alternative to call() when the requests go so fast and need just a little timeout to work properly.
	 * 
	 * @param unknown_type $url
	 * @param unknown_type $container_id
	 * @param unknown_type $confirm
	 */
	public function callc($url, $containerId = null, $confirm = null){
		$this->wait(200, true);// 200 milliseconds
		return $this->call($url, $containerId, $confirm);
	}
	
    /**
	 * Create Ajax calls
	 *
	 * @param required string $url
	 * @param optional string $containerId = null
	 * @param optional string $confirm = 'get'
	 * @return string
	 */
	public function call($url, $containerId = null, $confirm = null){
		$ajax = CJAX::getInstance();
		
		if(preg_match('/^https?/', $url)){
			$out['crossdomain'] = true;
		}
		$out['do'] = '_call';
		$out['url'] = $url;
		
		if($ajax->post){
			if(is_array($ajax->post)){
				$args = http_build_query($ajax->post);
				$out['args'] = $args;
				$out['post'] = true;
			} 
            else{
				$out['post'] = true;
			}
		}

		if($containerId) $out['container_id'] = $containerId;
		if(is_bool($ajax->text) && $ajax->text === false){
			$out['text'] = "no_text";
		} 
        elseif($ajax->text){
			$out['text'] = "{$ajax->text}";
		}
		
		if($confirm) $out['confirm'] = $confirm;

		if($ajax->loading){
			$out['is_loading'] = true;
		}
		
		return $this->xmlItem($this->xml($out),'call','api');
	}
	
	
	public function __set($setting, $value){
		if($this->isPlugin($setting)){
			//is a plugin..
			return;
		}
		if(is_object($value)){
			switch($value->name){
				case 'call':
					$value->container_id = $setting;
				break;
				case 'form':
					$this->Exec($setting, $value->id);
				break;
			}
			return $this->simpleCommit();
		} 
        else{
			$xml =  $this->property($setting,$value);
			$this->simpleCommit();
			return $xml;
		}
	}
	
	/**
	 * 
	 * is used to pass extra variables to POST ajax.
	 * currently used in iframe uploads
	 * 
	 * @param unknown_type $vars
	 */
	public function ajaxVars($vars){
		$data['do'] = 'ajaxVars';
		if(is_array($vars)){
			$vars = http_build_query($vars);
		}
		$data['vars'] = $vars;
		$this->xml($data);
	}
		
	public function dialog($content, $title = null, $options = []){
		$content = $this->format->_dialog($content, $title);
		return $this->overlayContent($content, $options);
	}
	
	/**
	 * 
	 * Show overlay dialog provided information
	 * @param unknown_type $data
	 * @param unknown_type $title
	 */
	public function debug($data, $title ='Debug Information', $extra = null){
		if($extra){
			$extra .= '<br />';
		}
		$this->dialog($extra.'<pre>'.print_r($data,1).'</pre>', $title, ['top'=> 100]);
	}
	
	/**
	 * *set value to an element
	 * @param string $elementId
	 * @param string $value
	 */
	public function property($elementId, $value = '', $clearDefault = false, $selectText = false){
		$options = [];
		$options['do']  = 'property';
		$options['element_id'] = $elementId;
		$options['clear_text'] = $clearDefault;
		$options['select_text'] = $selectText;
		$options['value'] = $value;
		//$options['options'] = $this->mkArray($options);		
		return $this->xml($options);
	}

	public function select($element, $options = array(), $selected = null, $allowInput = false){
		$select['do'] = 'select';
		$select['element_id'] = $element;
		$select['selected'] = $selected;
		$select['options'] = $options;
		$select['allow_input'] = $allowInput;		
		return $this->xml($select);
	}

    /**
     * Submit a form
     *
     * @param require string $url  url where the request will be sent to
     * @param require string $form_id  the form id
     * @param optional string $container_id = null  alternative element where to load the response
     * @param optional string $confirm  ask before sending the request
     * @return unknown
     */
    public function form($url, $formId = null, $containerId = null, $confirm = null){
        $ajax = CJAX::getInstance();       
        $out = [];        
        $out['do'] = '_form';
        $out['url'] = $url;
        if($formId) $out['form_id'] = $formId;
        if(!is_null($container_id)){
        	$out['container'] = $containerId;
        }

    	if(!is_null($ajax->text)){
			$out['text'] = $ajax->text;
		} 
        elseif($ajax->text===false){
			$out['text'] = 'Loading...';
		}

        if($confirm){
            $out['confirm'] = $confirm;
        }

        if(is_array($ajax->post)){
        	$args = http_build_query($ajax->post);
        	$out['args'] = $args;
        	$out['post'] = true;
        }
        else{
        	$out['post'] = 1;
        }
        $xml = $this->xmlItem($this->xml($out), 'form', 'api');       
        return $xml;
    }


	/**
	 * assign styles to an element
	 *
	 * @param unknown_type $style
	 */
	public function style($elementId, $style = []){
		$data['do'] = 'style';
		$data['element'] = $elementId;
		$data['style'] = $this->mkArray($style);
		return $this->xml($data);
	}

	/**
	 *
	 * overlay url
	 * @param $url
	 * @param $useCache
	 * @param $options
	 * Accepted  $options Example
	 *  $options['top'] = '50px';
		$options['left'] = '100px';
		$options['transparent'] = '60%'; // from 1 transparent to 100 solid, how transparent should it be? default is 80.
		$options['color'] = '#FF8040'
	 */
	public function overLay($url = null, $options = [], $useCache = false){
		$data['do'] = '_overLay';
		if(!isset($options['click_close'])){
			$options['click_close'] = true;
		}
		if($options && is_array($options)){
			foreach($options as $k => $v){
				if($v instanceof Plugin){
					$xml = $v->xmlObject();
					$_data = $xml->pack();
					$xml->delete();
				} 
                else{
					$_data = $v;
				}
				$data[$k] = $_data;
			}
			$data['options'] = $options;
		}
		$data['url'] = $url;
		$data['cache'] = $useCahe;
		if($url){
			$data['template'] = $this->template('overlay.html');
		}
		
		return $this->xmlItem($this->xml($data), 'overlay', 'api');
	}

	/**
	 *
	 * Display an overlay with supplied html.
	 *
	 * Options -
	 * Examples:
 	    $options['transparent']	=	10;
		$options['color']	=	'#425769';
		$options['top'] = 200;
		$options['left'] = "50%";
	 * @param $content
	 * @param $options
	 */
	public function overlayContent($content = null, $options = null){
		$_options = [];

		$data['do'] = '_overLayContent';
		$data['content'] = $content;
		if(!isset($options['click_close'])){
			$options['click_close'] = true;
		}
		
		if($options && is_array($options)){
			foreach($options as $k => $v ){
				$data[$k] = $v;
			}
			$data['options'] = $this->mkArray($options);
		}
		$data['template'] = $this->encode($this->template('overlay.html'));

		return $this->xmlItem($this->xml($data), 'overlayContent', 'api');
	}

	/**
	 * Display a message in the middle of the screen
	 *
	 * @param string $data
	 * @param integer $seconds if specified, this is the number of seconds the message will appear in the screen
	 * then it will dissapear.
	 */
	public function message($message, $seconds = 3, $containerId = 'cjax_message'){
		$data['do'] = '_message';
		$data['message'] = $message;
		$data['time'] =  $seconds;
		$data['message_id'] = $containerId;
		return $this->xml($data);
	}
	
	/**
	 * 
	 * import css and javascript files
	 * @param mixed_type $file
	 * @param unknown_type $max_time
	 */
	public function import($file , $loadTime = 0){
		$data['do'] = '_import';
		$data['time'] = (int) $loadTime;
		$data['is_import'] = 1;
		if(!is_array($file)){
			$data['file'] = $file;
		} 
        else{
			$data = array_merge($data, $file);
		}
		
		return $this->xml($data);
		//return $this->xmlItem($this->xml($data), 'import') ;
	}
	
	/**
	 * 
	 * import more than one file, waiting for the previous to load.
	 * @param mixed_type $file
	 * @param unknown_type $max_time
	 */
	public function imports($files = [], &$data = []){
		$data['do'] = '_imports';
		$data['files'] = $this->xmlIt($files, 'file');
		$data['is_import'] = 1;
		
		$this->first();
		return $this->xml($data);
	}

	/**
	 * Update any element on the page by specifying the element ID
	 * Usage:  $ajax->update('element_id',$content);
	 * @param string $obj
	 * @param string $data
	 */
	public function update($elementId, $data = null){
		return $this->property($elementId, $data);
	}
	
	/**
	 * Add event to elements
	 * --
	 * AddEventTo();
	 *
	 * @param string $element
	 * @param string $event
	 * @param string $method
	 */
	public function AddEventTo($element, $actions, $event = 'onclick'){
		$data['do'] = 'AddEventTo';
		$data['element_id'] = $element;
		$data['event'] = $event;
		$data['events'] = $actions;		
		return $this->xmlItem($this->xml($data), 'AddEventTo', 'api');
	}

	/**
	 * Will execute a command in a specified amouth of time
	 * e.g $ajax->wait(5);
	 * Will wait 5 seconds before executes the next CJAX command
	 * 
	 *
	 * @param integer $seconds
	 * @param boolean $expand  - make other commands wait for this timeout and if there is a timeout add to it.
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
		$this->_flag = $data;
		return $this;
	}
	
	/**
	 * 
	 * Removes waiting times
	 */
	public function waitReset(){
		$data['do'] = '_wait';	
		$data['time_reset'] = 1;	
		return $this->xml($data);		
	}
	
	/**
	 * Flag function
	 * 
	 * Set command execution in high  priority preload mode.
	 */
	public function preload(){
		$this->flag('first');
	}

	/**
	 * Will remove an specified element from the page
	 *
	 * @param string $obj
	 */
	public function remove($obj){
		$data['do'] = 'remove';
		$data['element_id'] = $obj;
		$this->xml($data);
	}

	/**
	 * Redirect the page.
	 * this is a recommended alternative to the built-in php function Header();
	 *
	 * @param string $where [URL]
	 */
	public function location($url = null){
		$data['do'] = 'location';
		$data['url'] = $url;		
		return $this->xml($data);
	}
}
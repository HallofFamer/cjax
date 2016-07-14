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
*   File Last Changed:  04/18/2016           $     
**####################################################################################################    */   
 
namespace CJAX\Plugins\Dracula; 
use CJAX\Core\Plugin;

/**
 * The Dracula class, it provides public API for plugin Dracula.
 * @category CJAX
 * @package Plugins
 * @subpackage Dracula
 * @author Ordland Euroboros <halloffamer@mysidiarpg.com>
 * @copyright (c) 2008, CJ Galindo
 * @link https://github.com/ajaxboy/cjax
 * @version 6.0
 * @since 6.0
 * @api
 */

class Dracula extends Plugin{
	
	/**
	 * The containers property, stores a group of containers list assigned for Dracula events. 
     * @access private
	 * @var array
	 */           
    private $containers = [];

	/**
	 * The options property, stores a list of options for each Dracula event. 
     * @access private
	 * @var array
	 */      
	private $options = []; 
    
	/**
	 * The events property, stores a list of custom callback events for Dracula. 
     * @access private
	 * @var array
	 */        
    private $events = [];
			

  	/**
     * The onLoad method, it is called when the plugin is loaded for the first time.
     * @param array|string  $containers
     * @param array  $options
     * @param array  $events
     * @access public
     * @return void
     */       
	public function onLoad($containers = null, $options = [], $events = []){	
		$this->import('dragula-3.7/dragula.min.js', 0, true);
        if($containers){
            $this->drake($containers, $options, $events);
        }
	}
    
  	/**
     * The drake method, used for assigning drag and drop events with dracula.
     * This method can be called repeatedly to assign more groups of containers for drag and drop event.
     * @param array|string  $containers
     * @param array  $options
     * @param array  $events
     * @access public
     * @return void
     */        
    public function drake($containers = null, $options = [], $events = []){
        $this->containers[] = $containers;
        $this->options[] = $options;
        $this->events[] = $events;
        $this->set('a', $this->containers);
        $this->set('b', $this->options);
        $this->set('c', $this->events);
    }
}
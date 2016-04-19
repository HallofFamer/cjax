<?php

/** ################################################################################################**   
* Copyright (c)  2008  CJ.   
* Permission is granted to copy, distribute and/or modify this document   
* under the terms of the GNU Free Documentation License, Version 1.2   
* or any later version published by the Free Software Foundation;   
* Provided 'as is' with no warranties, nor shall the autor be responsible for any mis-use of the same.     
* A copy of the license is included in the section entitled 'GNU Free Documentation License'.   
*   
*   CJAX  6.0               $     
*   ajax made easy with cjax                    
*   -- DO NOT REMOVE THIS --                    
*   -- AUTHOR COPYRIGHT MUST REMAIN INTACT -   
*   Written by: CJ Galindo                  
*   Website: http://cjax.sourceforge.net                     $      
*   Email: cjxxi@msn.com    
*   Date: 03/13/2016                          $     
*   File Last Changed:  04/18/2016           $     
**####################################################################################################    */ 

namespace CJAX\Event;
use StdClass;

/**
 * The abstract InputEvent class, parent class of all CJAX input event type classes.
 * An InputEvent represents any events associated with user input, such as keyboard and mouse events.
 * @category CJAX
 * @package Event
 * @author Ordland Euroboros <halloffamer@mysidiarpg.com>
 * @copyright (c) 2008, CJ Galindo
 * @link https://github.com/ajaxboy/cjax
 * @version 6.0
 * @since 6.0
 * @abstract
 * @api
 */

abstract class InputEvent extends Event{

	/**
	 * The altKey property, checks if the "ALT" key was pressed when input event is was triggered. 
     * @access protected
	 * @var bool
	 */       
    protected $altKey;

	/**
	 * The component property, stores the component id involved in this input event. 
     * @access protected
	 * @var string
	 */        
    protected $component;   
    
	/**
	 * The ctrlKey property, checks if the "CTRL" key was pressed when input event is was triggered. 
     * @access protected
	 * @var bool
	 */      
    protected $ctrlKey;
    
	/**
	 * The metaKey property, checks if the "META" key was pressed when input event is was triggered. 
     * @access protected
	 * @var bool
	 */          
    protected $metaKey;
    
	/**
	 * The shiftKey property, checks if the "SHIFT" key was pressed when input event is was triggered. 
     * @access protected
	 * @var bool
	 */           
    protected $shiftKey;

    
	/**
     * The constructor for InputEvent class, it initializes basic input event properties.
     * Child classes of InputEvent need to call this parent constructor to ensure complete initialization of event object.
	 * @param StdClass  $event
     * @access public
     * @return InputEvent
     */	      
    public function __construct(StdClass $event){
        parent::__construct($event);
        $this->altKey = (bool)$event->altKey;
        $this->component = $event->component;
        $this->ctrlKey = (bool)$event->ctrlKey;
        $this->metaKey = (bool)$event->metaKey;
        $this->shiftKey = (bool)$event->shiftKey;
    }
    
  	/**
     * The isAltDown method, returns whether or not the Alt modifier is down on this event.
     * @access public
     * @return bool
     */     
    public function isAltDown(){
        return $this->altKey;
    }   
    
	/**
	 * The getComponent method, obtains the component id associated with this input event. 
     * @access protected
	 * @var string
	 */           
    public function getComponent(){
        return $this->component;
    }
   
  	/**
     * The isControlDown method, returns whether or not the Control modifier is down on this event.
     * @access public
     * @return bool
     */    
    public function isControlDown(){
        return $this->ctrlKey;
    }
    
  	/**
     * The isMetaDown method, returns whether or not the Meta modifier is down on this event.
     * @access public
     * @return bool
     */      
    public function isMetaDown(){
        return $this->metaKey;
    }
    
  	/**
     * The isShiftDown method, returns whether or not the Shift modifier is down on this event.
     * @access public
     * @return bool
     */      
    public function isShiftDown(){
        return $this->shiftKey;
    }
    
   	/**
     * The abstract method getLocation, finds the location of the input device associated with this event.
     * This method will need to be overriden by child classes to provide different implementations.
     * @access public
     * @return mixed
     * @abstract
     */      
    abstract public function getLocation();
}
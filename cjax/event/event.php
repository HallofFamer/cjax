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
*   Date: 03/13/2016                           $     
*   File Last Changed:  04/18/2016           $     
**####################################################################################################    */ 

namespace CJAX\Event;
use StdClass;

/**
 * The abstract Event class, parent class of all CJAX event type classes.
 * The event class provides properties from javascript event object, which PHP can use in AJAX request.
 * To use event classes, pass it as first argument to controller classes. CJAX is smart enough to create event object automatically.
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

abstract class Event{
	
	/**
	 * The CAPTURING_PHASE constant, defines the integer value for capturing phase event status. 
	 * @var int
     * @constant
	 */     
    const CAPTURING_PHASE = 1;
    
	/**
	 * The AT_TARGET constant, defines the integer value for at-target phase event status. 
	 * @var int
     * @constant
	 */        
    const AT_TARGET = 2;
    
	/**
	 * The BUBBLING_PHASE constant, defines the integer value for bubbling phase event status. 
	 * @var int
     * @constant
	 */       
    const BUBBLING_PHASE = 3;
    
	/**
	 * The bubbles property, specifies if a specific event can bubble or not.
     * @access protected
	 * @var bool
	 */      
    protected $bubbles;
    
	/**
	 * The cancelable property, specifies if a if a specific event is cancelable.
     * @access protected
	 * @var bool
	 */     
    protected $cancelable;
    
	/**
	 * The defaultPrevented property, checks if default is prevented.
     * @access protected
	 * @var bool
	 */      
    protected $defaultPrevented;
    
	/**
	 * The phase property, defines the event phase.
     * @access protected
	 * @var int
	 */     
    protected $phase;
    
	/**
	 * The source property, stores the id of element that triggers the event.
     * @access protected
	 * @var string
	 */       
    protected $source;
    
	/**
	 * The target property, stores the id of element whose event listener triggers this event.
     * @access protected
	 * @var string
	 */        
    protected $target;
    
	/**
	 * The timeStamp property, specifies the number of milliseconds since midnight of January 1, 1970.
     * @access protected
	 * @var int
	 */     
    protected $timeStamp;
    
	/**
	 * The trusted property, checks if the event is trusted or not.
     * @access protected
	 * @var bool
	 */         
    protected $trusted;
    
	/**
	 * The target property, defines the type of event that was triggered.
     * @access protected
	 * @var string
	 */     
    protected $type;
    
    
	/**
     * The constructor for Event class, it initializes basic event properties for every child event classes.
	 * @param StdClass  $event
     * @access public
     * @return Event
     */	       
	public function __construct(StdClass $event){
        $this->bubbles = (bool)$event->bubbles;
        $this->cancelable = (bool)$event->cancelable;
        $this->defaultPrevented = (bool)$event->defaultPrevented;
        $this->phase = $event->eventPhase;
        $this->source = $event->target;
        $this->target = $event->currentTarget;
        $this->timeStamp = $event->timeStamp;
        $this->trusted = (bool)$event->isTrusted;
        $this->type = $event->type;
    }
    	
	/**
     * The canBubble method, find out if a specific event can bubble or not.
     * @access public
     * @return bool
     */	        
    public function canBubble(){
        return $this->bubbles;
    }
    
	/**
     * The isCancelable method, find out if a specific event is cancelable.
     * @access public
     * @return bool
     */	        
    public function isCancelable(){
        return $this->cancelable;
    }
    
	/**
     * The hasDefaultPrevented method, checks whether the preventDefault() method was called for the event.
     * @access public
     * @return bool
     */	      
    public function hasDefaultPrevented(){
        return $this->defaultPrevented;
    }
    
 	/**
     * The getPhase method, returns a number that indicates which phase of the event flow is currently being evaluated.
     * @access public
     * @return int
     */   
    public function getPhase(){
        return $this->phase;
    }
    
  	/**
     * The getSource method, returns the element that triggered the event.
     * @access public
     * @return string
     */           
    public function getSource(){
        return $this->source;
    }
    
 	/**
     * The getTarget method, returns the element whose event listeners triggered the event.
     * @access public
     * @return string
     */        
    public function getTarget(){
        return $this->target;
    }
    
  	/**
     * The getTimeStamp method, gets the number of milliseconds since midnight of January 1, 1970.
     * @access public
     * @return int
     */      
    public function getTimeStamp(){
        return $this->timeStamp;
    }
    
	/**
     * The isTrusted method, find out if a specific event is trusted.
     * @access public
     * @return bool
     */    
    public function isTrusted(){
        return $this->trusted;
    }
    
	/**
     * The isTrusted method, return the type of event that was triggered.
     * @access public
     * @return string
     */      
    public function getType(){
        return $this->type;
    }
    
	/**
     * Magic method __toString, acquires the event's information in string format.
     * @access public
     * @return string
     */      
    public function __toString(){
        return "Event:".get_class($this).".{$this->type}";
    }
}
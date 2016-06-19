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
 * The FocusEvent class, representing focus-related events like focus, blur, focusin, or focusout.
 * @category CJAX
 * @package Event
 * @author Ordland Euroboros <halloffamer@mysidiarpg.com>
 * @copyright (c) 2008, CJ Galindo
 * @link https://github.com/ajaxboy/cjax
 * @version 6.0
 * @since 6.0
 * @api
 */

class FocusEvent extends Event{

	/**
	 * The component property, stores the primary component id involved in focus change. 
     * @access protected
	 * @var string
	 */       
    protected $component;
    
	/**
	 * The oppositeComponent property, specifies the other Component involved in this focus change. 
     * @access protected
	 * @var string
	 */      
    protected $oppositeComponent;

    
	/**
     * The constructor for FocusEvent class, it initializes basic focus event properties.
	 * @param StdClass  $event
     * @access public
     * @return FocusEvent
     */	     
    public function __construct(StdClass $event){
        parent::__construct($event);
        $this->component = $event->component;        
        $this->oppositeComponent = $event->oppositeComponent;
    }
 
	/**
     * The getComponent method, returns the primary component in this focus change.
     * @access public
     * @return string
     */	     
    public function getComponent(){
        return $this->component;
    }
    
	/**
     * The getOppositeComponent method, returns the other component in this focus change.
     * @access public
     * @return string
     */    
    public function getOppositeComponent(){
        return $this->oppositeComponent;
    }   
}
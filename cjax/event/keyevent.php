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
 * The KeyEvent class, representing events that describes user interaction with the keyboard.
 * KeyEvent inherits from InputEvents, therefore it contains all properties for input event too.
 * @category CJAX
 * @package Event
 * @author Ordland Euroboros <halloffamer@mysidiarpg.com>
 * @copyright (c) 2008, CJ Galindo
 * @link https://github.com/ajaxboy/cjax
 * @version 6.0
 * @since 6.0
 * @api
 */


class KeyEvent extends InputEvent{

	/**
	 * The charCode property, stores the Unicode value of the pressed keyboard key. 
     * @access protected
	 * @var int
	 */     
    protected $charCode;
    
	/**
	 * The keyCode property, stores the Unicode value of the pressed keyboard key. 
     * @access protected
	 * @var int
	 */       
    protected $keyCode;
    
	/**
	 * The keyText property, defines the keyboard button that was pressed when a key event occured. 
     * @access protected
	 * @var string
	 */          
    protected $keyText;
    
	/**
	 * The location property, specifies the location of a key on the keyboard or device. 
     * @access protected
	 * @var int
	 */      
    protected $location;

    
	/**
     * The constructor for KeyEvent class, it initializes basic key event properties.
	 * @param StdClass  $event
     * @access public
     * @return KeyEvent
     */	    
    public function __construct(StdClass $event){
        parent::__construct($event);
        $this->charCode = $event->charCode;        
        $this->keyCode = $event->keyCode;
        $this->keyText = $event->key;
        $this->location = $event->location;
    }
    
  	/**
     * The getCharCode method, gets the Unicode value of the pressed keyboard key.
     * @access public
     * @return int
     */       
    public function getCharCode(){
        return $this->charCode;
    }
    
  	/**
     * The getKeyCode method, returns the integer keyCode associated with the key in this event.
     * @access public
     * @return int
     */        
    public function getKeyCode(){
        return $this->keyCode;
    }
    
  	/**
     * The getKeyText method, obtains the character associated with the key in this event.
     * @access public
     * @return string
     */       
    public function getKeyText(){
        return $this->keyText;
    }
    
   	/**
     * The getLocation method, obtains the location of the key that originated this key event.
     * @access public
     * @return int
     */    
    public function getLocation(){
        return $this->location;
    }   
}
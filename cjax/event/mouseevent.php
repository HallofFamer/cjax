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
 * The MouseEvent class, representing events that occur due to the user interacting with a mouse.
 * MouseEvent inherits from InputEvents, therefore it contains all properties for input event too.
 * @category CJAX
 * @package Event
 * @author Ordland Euroboros <halloffamer@mysidiarpg.com>
 * @copyright (c) 2008, CJ Galindo
 * @link https://github.com/ajaxboy/cjax
 * @version 6.0
 * @since 6.0
 * @api
 */

class MouseEvent extends InputEvent{

	/**
	 * The button property, stores a number that indicates which mouse button was pressed when a mouse event was triggered. 
     * @access protected
	 * @var int
	 */      
    protected $button;
    
	/**
	 * The clickCount property, stores a number that indicates how many times the mouse was clicked in the same area. 
     * @access protected
	 * @var int
	 */      
    protected $clickCount;
    
	/**
	 * The x property, specifies the horizontal coordinate of mouse pointer relative to the source component. 
     * @access protected
	 * @var int
	 */        
    protected $x;
    
	/**
	 * The xOnPage property, specifies the horizontal coordinate of mouse pointer according to the document. 
     * @access protected
	 * @var int
	 */    
    protected $xOnPage;
    
	/**
	 * The xOnScreen property, specifies the horizontal coordinate of mouse pointer according to the screen. 
     * @access protected
	 * @var int
	 */      
    protected $xOnScreen;
    
	/**
	 * The y property, specifies the vertical coordinate of mouse pointer relative to the source component. 
     * @access protected
	 * @var int
	 */       
    protected $y;
    
	/**
	 * The yOnPage property, specifies the vertical coordinate of mouse pointer according to the document. 
     * @access protected
	 * @var int
	 */    
    protected $yOnPage;
    
	/**
	 * The xOnScreen property, specifies the horizontal coordinate of mouse pointer according to the screen. 
     * @access protected
	 * @var int
	 */        
    protected $yOnScreen;

    
	/**
     * The constructor for MouseEvent class, it initializes basic mouse event properties.
	 * @param StdClass  $event
     * @access public
     * @return MouseEvent
     */	    
    public function __construct(StdClass $event){
        parent::__construct($event);
        $this->button = $event->button;        
        $this->clickCount = $event->detail;
        $this->x = $event->x;
        $this->xOnPage = $event->pageX;
        $this->xOnScreen = $event->screenX;
        $this->y = $event->y;
        $this->yOnPage = $event->pageY;
        $this->yOnScreen = $event->screenY;
    }
    
  	/**
     * The getButton method, returns which, if any, of the mouse buttons has changed state.
     * @access public
     * @return int
     */    
    public function getButton(){
        return $this->button;
    }
    
  	/**
     * The getClickCount method, returns the number of mouse clicks associated with this event.
     * @access public
     * @return int
     */        
    public function getClickCount(){
        return $this->clickCount;
    }
    
  	/**
     * The getX method, returns the horizontal x position of the event relative to the source component.
     * @access public
     * @return int
     */       
    public function getX(){
        return $this->x;
    }
    
  	/**
     * The getXOnPage method, returns the horizontal x position of the event on the page.
     * @access public
     * @return int
     */           
    public function getXOnPage(){
        return $this->xOnPage;
    }   
    
  	/**
     * The getXOnScreen method, returns the horizontal x position of the event on the screen.
     * @access public
     * @return int
     */    
    public function getXOnScreen(){
        return $this->xOnScreen;
    }
    
  	/**
     * The getY method, returns the vertical y position of the event relative to the source component.
     * @access public
     * @return int
     */       
    public function getY(){
        return $this->y;
    }   
    
  	/**
     * The getYOnPage method, returns the vertical y position of the event on the page.
     * @access public
     * @return int
     */        
    public function getYOnPage(){
        return $this->yOnPage;
    }   
    
  	/**
     * The getYOnScreen method, returns the vertical y position of the event on the screen.
     * @access public
     * @return int
     */       
    public function getYOnScreen(){
        return $this->yOnScreen;
    }   

  	/**
     * The getLocation method, returns the mouse cursor position of the event relative to source component.
     * This method fetches an array/tuple of x and y coordinates.
     * @access public
     * @return array
     */         
    public function getLocation(){
        return [$this->x, $this->y];
    }

  	/**
     * The getLocationOnPage method, returns the mouse cursor position of the event on the page.
     * This method fetches an array/tuple of x and y coordinates on the page.
     * @access public
     * @return array
     */      
    public function getLocationOnPage(){
        return [$this->xOnPage, $this->yOnPage];
    }     
    
  	/**
     * The getLocationOnSceen method, returns the mouse cursor position of the event on the screen.
     * This method fetches an array/tuple of x and y coordinates on the screen.
     * @access public
     * @return array
     */     
    public function getLocationOnScreen(){
        return [$this->xOnScreen, $this->yOnScreen];
    }    
}
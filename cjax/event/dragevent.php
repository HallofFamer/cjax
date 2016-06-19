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
 * The DragEvent class, representing a drag and drop interaction event.
 * DragEvent inherits from MouseEvents, therefore it contains all properties for mouse event too.
 * @category CJAX
 * @package Event
 * @author Ordland Euroboros <halloffamer@mysidiarpg.com>
 * @copyright (c) 2008, CJ Galindo
 * @link https://github.com/ajaxboy/cjax
 * @version 6.0
 * @since 6.0
 * @api
 */

class DragEvent extends MouseEvent{

	/**
	 * The data property, stores the data that is transferred during a drag and drop interaction. 
     * @access protected
	 * @var mixed
	 */       
    protected $data;
    
	/**
	 * The dropEffect property, defines special drag and drop effects with this event. 
     * @access protected
	 * @var string
	 */      
    protected $dropEffect;
    
 	/**
	 * The effectAllowed property, specifies which types of operations are available. 
     * @access protected
	 * @var string
	 */       
    protected $effectAllowed;
    
 	/**
	 * The files property, contains a list of all the local files available on the data transfer.
     * @access protected
	 * @var array
	 */      
    protected $files;

    
	/**
     * The constructor for DragEvent class, it initializes basic drag event properties.
	 * @param StdClass  $event
     * @access public
     * @return DragEvent
     */	         
    public function __construct(StdClass $event){
        parent::__construct($event);
        $this->data = $event->data;        
        $this->dropEffect = $event->dropEffect;
        $this->effectAllowed = $event->effectAllowed;
        $this->files = $event->files;
    }
 
 	/**
     * The getData method, fetches the data transferred in this drag event.
     * @access public
     * @return mixed
     */      
    public function getData(){
        return $this->data;
    }
    
 	/**
     * The getDropEffect method, gets the type of drag-and-drop operation currently selected or sets the operation to a new type.
     * @access public
     * @return string
     */        
    public function getDropEffect(){
        return $this->dropEffect;
    }   
    
 	/**
     * The getEffectAllowed method, provides all of the types of operations that are possible.
     * @access public
     * @return string
     */     
    public function getEffectAllowed(){
        return $this->effectAllowed;
    }
    
 	/**
     * The getFiles method, returns a list of all the local files available on the data transfer.
     * @access public
     * @return array
     */     
    public function getFiles(){
        return $this->files;
    }
}
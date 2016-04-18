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
*   Date: 2/12/2007                           $     
*   File Last Changed:  04/18/2016           $     
**####################################################################################################    */ 

namespace CJAX\Core;

/**
 * The abstract AJAXController class that every CJAX controllers should extend.
 * By extending this class, child controller classes acquire an instance of CJAX through dependency injection.
 * Creation of this class marks the deprecation the singleton CJAX::getInstance() in future.
 * @category CJAX
 * @package Core
 * @author Ordland Euroboros <halloffamer@mysidiarpg.com>
 * @copyright (c) 2008, CJ Galindo
 * @link https://github.com/ajaxboy/cjax
 * @version 6.0
 * @since 6.0
 * @abstract
 * @api
 */

abstract class AJAXController{
	
	/**
	 * The ajax property, stores an instance of CJAX for child controllers to use easily.
     * @access protected
	 * @var CJAX
	 */       
	protected $ajax;
	
    
	/**
     * The constructor for AJAXController class, it instantiates controller class with property $ajax through DI.
	 * @param CJAX  $ajax
     * @access public
     * @return AJAXController
     */	    
	public function __construct(CJAX $ajax){
        $this->ajax = $ajax;
    }

	/**
     * The getCJAX method, acquires the instance of $ajax from controller.
     * @access public
     * @return CJAX
     */	      
    public function getAJAX(){
        return $this->ajax;
    }
}
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

namespace CJAX\Plugins\Dracula\Controllers;
use CJAX\Core\AJAXController;

/**
 * The Dracula class, the base AJAX Controller for Dracula plugin.
 * An AJAX controller class may extends from Dracula controller class, or stores it as a property. 
 * @category CJAX
 * @package Plugins
 * @subpackage Dracula
 * @subpackage Controllers
 * @author Ordland Euroboros <halloffamer@mysidiarpg.com>
 * @copyright (c) 2008, CJ Galindo
 * @link https://github.com/ajaxboy/cjax
 * @version 6.0
 * @since 6.0
 * @api
 */

class Dracula extends AJAXController{

	/**
	 * The elementsOrders property, stores an associative array of orders for elements inside containers.
     * For the elementsOrders array, the keys are containers' ids, and values are an array of ordered element ids inside each container. 
     * @access protected
	 * @var array
	 */     
    protected $elementsOrders;
    
    
   	/**
     * The loadElementsOrders method, loads elements orders from PHP cookie if available.
     * @access public
     * @return void
     */         
    protected function loadElementsOrders(){
        $elementsOrders = filter_input(INPUT_COOKIE, 'dragulaorders');
        if($elementsOrders){
            $this->elementsOrders = json_decode($elementsOrders, true);
        }        
    }
}
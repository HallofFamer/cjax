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
use DateTime;

/**
 * The ActionEvent class, representing a basic mouse single click event.
 * The action event is a lite-version of MouseEvent, which does not store any mouse related properties.
 * @category CJAX
 * @package Event
 * @author Ordland Euroboros <halloffamer@mysidiarpg.com>
 * @copyright (c) 2008, CJ Galindo
 * @link https://github.com/ajaxboy/cjax
 * @version 6.0
 * @since 6.0
 * @todo Implement getActionCommand method.
 * @api
 */

class ActionEvent extends Event{

    
	/**
     * The getActionCommand method, obtains the command string associate with this action.
     * @access public
     * @return string
     */	      
    public function getActionCommand(){
    
    }

	/**
     * The getWhen method, returns when the event occurs.
     * This method returns a DateTime object, as opposed to getTimeStamp method which returns timestamp.
     * @access public
     * @return DateTime
     */	    
    public function getWhen(){
        return new DateTime("@{$this->timeStamp}");
    }
}
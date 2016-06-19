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

namespace CJAX\Plugins\Autocomplete\Controllers;
use CJAX\Core\AJAXController;

/**
 * The Autocomplete class, the base AJAX Controller for Autocomplete plugin.
 * An AJAX controller class may extends from Autocomplete controller class, or stores it as a property. 
 * @category CJAX
 * @package Plugins
 * @subpackage Autocomplete
 * @subpackage Controllers
 * @author CJ Galindo <cjxxi@msn.com>
 * @copyright (c) 2008, CJ Galindo
 * @link https://github.com/ajaxboy/cjax
 * @version 6.0
 * @since 5.0
 * @api
 */

class Autocomplete extends AJAXController{
	
  	/**
     * The generateOutput method, creates data output used by autocomplete plugin.
     * @param string  $search
     * @param array  $data
     * @access protected
     * @return array
     */      
    
    protected function generateOutput($search, array $data = []){
        $output = [];
        foreach($data as $value){
			if(substr(strtolower($value), 0, strlen($search)) == strtolower($search)){
				$output[] = $value;
			}
        }
        return $output;
    }
}
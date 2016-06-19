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

/**
 * The autoloader.php File, it register autoloader for CJAX package.
 * Simply including/requiring this file is sufficient to load any CJAX classes.
 * This file uses function spl_autoload_register() to register autoloader for CJAX classes.
 * @category CJAX
 * @author Ordland Euroboros <halloffamer@mysidiarpg.com>
 * @copyright (c) 2008, CJ Galindo
 * @link https://github.com/ajaxboy/cjax
 * @version 6.0
 * @since 6.0
 */

spl_autoload_register(function($class){
    if(strpos($class, "CJAX") === FALSE){
        return;
    }
    $class = str_replace("\\", "/", $class);
    $class = str_replace("CJAX/", "", $class);
    require strtolower(__DIR__."/{$class}.php");
});      
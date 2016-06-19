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
*   File Last Changed:  04/26/2016            $     
**####################################################################################################    */   

namespace CJAX;
use CJAX\Core\Ext;

/**
 * The Config class that stores all settings and optional preferences.
 * @category CJAX
 * @author Ordland Euroboros <halloffamer@mysidiainc.com>
 * @copyright (c) 2008, CJ Galindo
 * @link https://github.com/ajaxboy/cjax
 * @version 6.0
 * @since 6.0
 * @final
 */

final class Config extends Ext{

    /**
     * The sizzle property, specifies if advanced selectors are enabled(all jquery selectors will work).
     * @access public
     * @var bool
     */	      
    public $sizzle = false;

    /**
     * The fallback property, allows cjax to fallback on a small footprint on the page to be able to pass the pending data.
     * Cjax uses PHP sessions to pass data across, some times sessions fail or some setting on the server's cache.
     * This may cause unexpected behavior, and could cause cjax not to able to pass the data - in that case.
     * By setting $fallback to true, this behavior may be prevented.
     * @access public
     * @var bool
     */    
    public $fallback = false;

    /**
     * The caching property, bypasses fallback if true. 
     * Don't turn on fallback and caching at the same time, do one of the two.
     * @access public
     * @var bool
     */	    
    public $caching = false;

    /**
     * The ajaxView property, allows the access to ajax.php from browser if true.
     * Otherwise it would only allow access to ajax request or inclusion.
     * @access public
     * @var bool
     */	        
    public $ajaxView = false;

    /**
     * The debug property, specifies if javaScript debug information will display in firebug console.
     * @access public
     * @var bool
     */    
    public $debug = false;

    /**
     * The ipDebug property, stores a list of IPs that debug mode is turned on.
     * Enter an IP below if you want to debug ONLY for this specific IP.
     * You may also enter an array of IPs to allow debug mode for multiple IPs. 
     * @access public
     * @var string
     */    
    public $ipDebug = "";

    /**
     * The initUrl property, defines the init url for CJAX scripts.
     * $ajax->init() will print the script tags to include the framework.
     * Generally you won't need to touch this. 
     * If you experience problems where the script path is not being set correctly.
     * Or you are using very fancy URLs where the paths can be confusing, then you might find this helpful.
     * This will help the framework find the correct path to the js file in a case where it cannot be found.
     * @access public
     * @var string
     */       
    public $initUrl = "";

    /**
     * The camelize property, determines whether to use camel case in class names.
     * @access public
     * @var bool
     */        
    public $camelize = true;

    /**
     * The camelizeUcfirst property, determines if first letter is capitalized if camelize is true.
     * @access public
     * @var bool
     */      
    public $camelizeUcfirst = false;

    /**
     * The uploadDir property, defines an optional default path for file uploading.
     * Uploading plugins may upload files with access to this default upload location.
     * You can specify that path here, which may or may not end with a slash.
     * @access public
     * @var string
     */     
    public $uploadDir = "";
}
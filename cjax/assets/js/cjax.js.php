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
*   File Last Changed:  07/16/2016           $     
**####################################################################################################    */   

/**
 * The cjax.js.php File, it is the entry point for AJAX requests.
 * @category CJAX
 * @author Ordland Euroboros <halloffamer@mysidiarpg.com>
 * @copyright (c) 2008, CJ Galindo
 * @link https://github.com/ajaxboy/cjax
 * @version 6.0
 * @since 6.0
 */

header('Content-type: application/x-javascript');
header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
header("Cache-Control: no-cache");
header("Pragma: no-cache");

$base = realpath(__DIR__);
defined('CJAX_HOME') or define('CJAX_HOME', realpath($base."/../../../")."/cjax");
defined('CJAX_CORE') or define('CJAX_CORE', realpath($base."/../../../")."/cjax/core");
define('AJAX_VIEW', true);
require_once "../../autoloader.php";

use CJAX\Core\CJAX;
$ajax = CJAX::getInstance();
$ajax->request->handleRequest();

function tryAgain(){
	usleep(1000);
	$ajax = CJAX::getInstance();
	$_cache = $ajax->get('cjax_x_cache');
	echo "//trying\n";
	return $_cache;
}

if(isset($_REQUEST['json'])){
	echo 'init();';
} 
else{
	if($ajax->config->caching && isset($_REQUEST['crc32'])){
		$source = $ajax->cache->tap($_REQUEST['crc32']);
	} 
    else{
		$debug = $ajax->get('cjax_debug')? 1:0;
		$preload = $ajax->get('cjax_preload');
		$_cache = $ajax->get('cjax_x_cache');
		
		if(!$_cache){
			$_cache = tryAgain();
			if(!$_cache){
				$_cache = tryAgain();
				if(!$_cache){
					$_cache = tryAgain();
					if(!$_cache){
						exit();
					}
				}
			}
		}		
		$source = 'CJAX.process_all("'.$_cache.'","'.$preload.'", '.$debug.', true);';
	}

	if(!$source){
		echo "//no source available";
	} 
    else{
		print $source;
	}
}
$ajax->clear();
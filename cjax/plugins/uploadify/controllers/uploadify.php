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

namespace CJAX\Plugins\Uploadify\Controllers;
use CJAX\Core\AJAXController;

/**
 * The Uploadify class, the base AJAX Controller for Uploadify plugin.
 * An AJAX controller class may extends from Uploadify controller class, or stores it as a property. 
 * @category CJAX
 * @package Plugins
 * @subpackage Uploadify
 * @subpackage Controllers
 * @author CJ Calindo <cjxxi@msn.com>
 * @copyright (c) 2008, CJ Galindo
 * @link https://github.com/ajaxboy/cjax
 * @version 6.0
 * @since 5.0
 * @api
 */

class Uploadify extends AJAXController{

  	/**
     * The fileExists method, checks if a file is uploaded or not.
     * @access public
     * @return void
     */          
	public function fileExists(){
		$plugin = $this->ajax->uploadify();				
		if(file_exists($plugin->target . $_POST['filename'])){
			echo 1;
		} 
        else{
			echo 0;
		}
	}
	
  	/**
     * The upload method, handles file upload action for Uploadify.
     * @access public
     * @return void
     */      
	public function upload(){
		$plugin = $this->ajax->uploadify();		
		$exts = $plugin->get('exts');		
		$target = $plugin->get('target');		
		$targetFolder = $target;
		
		if(!empty($_FILES)){			
			$tempFile = $_FILES['Filedata']['tmp_name'];			
			$targetFile = rtrim($targetFolder,'/') . '/' . $_FILES['Filedata']['name'];
			if($exts){
				$fileTypes = $exts; // File extensions
			} 
            else{
				$fileTypes = ['jpg','jpeg','gif','png']; // File extensions
			}
            
			$fileParts = pathinfo($_FILES['Filedata']['name']);
			if(in_array($fileParts['extension'], $fileTypes)){
				echo $targetFile;
				if(move_uploaded_file($tempFile, $targetFile)){
					echo '1';
				} 
                else{
					echo 'error!';
				}
			} 
            else{
				echo 'Invalid file type.';
			}
		}
	}
}
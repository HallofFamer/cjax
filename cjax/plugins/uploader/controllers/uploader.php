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

namespace CJAX\Plugins\Uploader\Controllers;
use CJAX\Core\AJAXController;

/**
 * The Uploader class, the base AJAX Controller for Uploader plugin.
 * An AJAX controller class may extends from Uploader controller class, or stores it as a property. 
 * @category CJAX
 * @package Plugins
 * @subpackage Uploader
 * @subpackage Controllers
 * @author CJ Calindo <cjxxi@msn.com>
 * @copyright (c) 2008, CJ Galindo
 * @link https://github.com/ajaxboy/cjax
 * @version 6.0
 * @since 4.0
 * @api
 */
 
class Uploader extends AJAXController{
    
	/**
	 * The error property, stores the error message for Uploader if action fails.
     * @access private 
	 * @var string
	 */     
	private $error;
    
	/**
	 * The posts property, stores a list of posted/saved files.
     * @access private 
	 * @var array
	 */     
	private $post;
	
	/**
	 * The options property, specifies an array of options for uploader.
     * @access private 
	 * @var array
	 */     
    private $options;
	
	/**
	 * The files property, stores a list of files for uploader.
     * @access private 
	 * @var array
	 */      
    private $files;	
	
	/**
	 * The uploadCount property, specifies the number of files uploaded.
     * @access private 
	 * @var array
	 */      
    private $uploadCount = 0;
	
    
  	/**
     * The upload method, it is called to handle file upload operation.
     * @access public
     * @return void
     */  
	public function upload(){
		$filesFount = false;
		$files = [];		
		$this->ajax->cacheWrapper(["<html><body>","</body></html>"]);
		$options = $this->ajax->get('upload_options', true);
		$this->options = $options;		
		if(!$this->options->target){
			$this->abort("No target directory.");
		}		
		if(!is_writable($options->target)){            
			return $this->abort("Directory is not writable.");
		}		
		$this->chkLength();
		
		if(!$_FILES){
			if(isset($_REQUEST['files']) && $_REQUEST['files']){
				if(count($_REQUEST['files']) > 1){
					$this->error = "The files you tried to upload are too big or invalid.";
				} 
                else{
					$this->error = "The file you tried to upload is too big or invalid.";
				}
			}
		} 
        else{
            $this->uploadFiles($filesFount, $files);
		}
		
		
		$this->debug($this->options);		
		if(!$filesFount){
			if(!$this->options->files_require && !$this->uploadCount){
				$this->flush();
				$this->ajax->message();
				return true;
			}
			if(!$this->error){
				$this->error = "No Files Were selected";
			}
		}
		
		if(!$this->error){
			if($this->post){
				$this->ajax->ajaxVars($this->post);
			}
			if($this->options->preview){
				$preview = $this->options->preview;
				$previewUrl = $this->options->preview_url;
				if($previewUrl) {
					$previewUrl = rtrim($previewUrl,'/').'/';
				}
                
				$range = range(1,  count($this->files));
				array_walk($range, function(&$v){
					$v =  "#image{$v}#";					
				});
				foreach($this->files as $k => $v){
					$this->files[$k] = $previewUrl.$v;
				}
				foreach($preview as $k => $v){
					$image = str_replace($range, $this->files, $v);
					$this->ajax->update($k, $image);
				}
			}
			
			$_files = implode(', ',$files);
			$message = $this->options->success_message;
			if(!$message){
				$message = "File(s) $_files successfully uploaded.";
			} 
            else{
				$message = str_replace("@files", $_files, $message);
			}
			$this->ajax->success($message, 5);
		} 
        else{			
			$this->ajax->warning($this->error, 5);
		}
		
	}
	
  	/**
     * The flush method, removes caches and resets the plugin object.
     * @access public
     * @return void
     */      
	public function flush(){
		CoreEvents::$lastCache = [];
		CoreEvents::$cache = [];
	}
	
  	/**
     * The abort method, aborts file upload operation and exits script execution.
     * @access public
     * @return void
     */    
	public function abort($error){
		$this->ajax->error($error, 8);		
		exit;
	}
    
  	/**
     * The abort method, it is used to debug uploader plugin and checks server environment.
     * @param array  $options
     * @access public
     * @return void
     */       
	public function debug($options){
		if($options && $options->debug) {	
			$options->{"List Of Files Uploaded"} = $this->post;				
			$settings['php.ini post_max_size'] = ini_get('post_max_size');
			$settings['php.ini upload_max_filesize'] = ini_get('upload_max_filesize');
			$settings['php.ini max_execution_time'] = ini_get('max_execution_time');
			$settings['CONTENT_LENGTH'] = @$_SERVER['CONTENT_LENGTH'].' bytes';
			
			$debugMessage = null;
			if(is_string($options->debug)) {
				$debugMessage = $options->debug."<br /><br />";
			}
			$this->ajax->dialog("
				$debugMessage
				To be able to upload files, the server has to be able to handle them. 
				These are settings you can control in php.ini file. Any file(s) that exceeds these limitations
				will not be uploaded.
				<br />
				<br />
				These are server settings:
				<pre>". print_r($settings,1)."</pre>
				Settings Passed:
				<pre>".print_r($options,1)."</pre>"
				."Files:".
				"<pre>".print_r($_FILES,1)."</pre>"
			,"Debug Information");
		}
	}
	
  	/**
     * The chkExt method, checks if the file has valid extension for uploading.
     * @param string  $filename
     * @access public
     * @return bool
     */
	public function chkExt($filename){
		$info = pathinfo($filename);		
		if($this->options->ext && is_array($this->options->ext)) {
			$exts =  array_map('strtolower', $this->options->ext);
			if(!in_array(strtolower($info['extension']), $exts)) {
				$this->error = "File Extension: .{$info['extension']}  is not supported.";
				return false;
			}
		}
		return true;
	}
	
  	/**
     * The uploadFiles method, handles the file uploading operation for all files.
     * @param bool  $filesFount
     * @param array  $files
     * @access public
     * @return void
     */    
    public function uploadFiles(&$filesFount, &$files){
        foreach($_FILES as $k => $v){
            if(is_array($v['error'])){
                foreach($v['error'] as $k2 => $err){
                    $filename = $v['name'][$k2];
                    if(!$filename){
                        continue;
                    }
                    $size = $v['size'][$k2];
                    if($filename){
                        $filesFount = true;
                    }
                    if($err){
                        $this->error = $this->error($err, $filename, $size);
                        continue;
                    } 
                    else{
                        if($filename && !$this->chkExt($filename)){
                            break;
                        }

                        if($f = $this->uploadFile($v['tmp_name'][$k2],$filename)){
                            $files[] = $f;
                        }
                    }
                }
            } 
            else{
                $filename = $v['name'];
                if(!$filename){
                    continue;
                }
                if($filename && !$this->chkExt($filename)){
                    break;
                }
                if($v['error']){
                    $this->error = $this->error($v['error'], $filename, $v['size']);
                    break;
                } 
                else{
                    if($v['name']){
                        $filesFount = true;
                    }
                    if($f = $this->uploadFile($v['tmp_name'], $filename)){
                        $files[] = $f;
                    }
                }
            }
        }       
    }
    
  	/**
     * The uploadFile method, carries out file upload operation by moving temporary files.
     * @param string  $tmpname
     * @param string  $filename
     * @access public
     * @return bool
     */
	public function uploadFile($tmpname, $filename){
		$info = pathinfo($filename);
		$filename = $info['filename'];
		
		if($prefix = $this->options->prefix){
			if($prefix=='time'){
				$prefix = time();
			}
			if($prefix=='rand'){
				$prefix = rand(1, 10000000);
			}
			$filename = $prefix.'_'.$filename;
		}
		if($suffix = $this->options->suffix){
			if($suffix=='time'){
				$suffix = time();
			}
			if($suffix=='rand'){
				$suffix = rand(1, 10000000);
			}
			$filename = $filename.'_'.$suffix;
		}
		
		$filename = $filename.'.'.$info['extension'];			
		$this->post['a'][] = $filename;
		$this->files[] = $filename;
		
		if(@move_uploaded_file($tmpname,$this->options->target.$filename)) {
			$this->uploadCount++;
			return $filename;
		} else {
			sleep(2);
			//try again
			if(!@copy($tmpname,$this->options->target.$filename)) {
				$this->uploadCount++;
				$error = error_get_last();
				$this->error = "Could not upload file $filename. {$error['message']}";
			}
		}	
	}
    
  	/**
     * The chkLength method, checks if the length of file is valid.
     * This method will not return boolean value, it terminates script execution if length check fails.
     * @access public
     * @return void
     */
	public function chkLength(){
		if(isset($_SERVER['CONTENT_LENGTH']) && $_SERVER['CONTENT_LENGTH']) {		
			$postMax = $_postMax = @ini_get('post_max_size');// / 8;		
			$postMax = preg_replace("/([^0-9]+)/","", $postMax);		
			switch(substr($_postMax,-1)){
			    case 'G':
				    $postMax = $postMax * 1024;
			    case 'M':
				    $postMax = $postMax * 1024;
			    case 'K':
				    $postMax = $postMax * 1024;
			}
			
			if($_SERVER['CONTENT_LENGTH'] > $postMax){
				$error = "Upload Failed. This server limits max upload to $_postMax (post_max_size in php.ini). ";
				$this->abort($error);
			}
		}
	}
	
  	/**
     * The error method, triggers an error message for failed file uploading actions.
     * @param int  $errorNo
     * @param string  $fileName
     * @param int  $size
     * @access public
     * @return string
     */    
	public function error($errorNo, $fileName, $size = 0){
		$error = null;
		
		if($errorNo){
			switch($errorNo){
				case UPLOAD_ERR_INI_SIZE:
					$_uploadMax = @ini_get('upload_max_filesize');					
					$error = "{$fileName} - File exceeds max upload limit of $_uploadMax";
				break;
				case UPLOAD_ERR_FORM_SIZE:
					$error = "{$fileName} - The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form. ";
				break;
				case UPLOAD_ERR_PARTIAL:
					$error = "{$fileName} -The uploaded file was only partially uploaded.";
				break;
				case UPLOAD_ERR_NO_FILE:
					$error = "{$fileName} - No file was uploaded. ";
				break;
				case UPLOAD_ERR_NO_TMP_DIR:
					$error = "{$fileName} - Missing a temporary folder.";
				break;
				case UPLOAD_ERR_CANT_WRITE:
					$error = "{$fileName} - Failed to write file to disk.";
				break;
				case UPLOAD_ERR_EXTENSION:
					$error = "{$fileName} - A PHP extension stopped the file upload. PHP does not provide a way to ascertain which extension caused the file upload to stop;<br /> examining the list of loaded extensions with phpinfo() may help.";
				break;
				default:
				$error = "$fileName Unknown Error Occurred.";
			}
		}		
		return $error;
	}
}
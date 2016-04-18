<?php 

/** ################################################################################################**   
* Copyright (c)  2008  CJ.   
* Permission is granted to copy, distribute and/or modify this document   
* under the terms of the GNU Free Documentation License, Version 1.2   
* or any later version published by the Free Software Foundation;   
* Provided 'as is' with no warranties, nor shall the author be responsible for any mis-use of the same.     
* A copy of the license is included in the section entitled 'GNU Free Documentation License'.   
*   
*   CJAX  6.0                $     
*   ajax made easy with cjax                    
*   -- DO NOT REMOVE THIS --                    
*   -- AUTHOR COPYRIGHT MUST REMAIN INTACT -   
*   Written by: CJ Galindo                  
*   Website: http://cjax.sourceforge.net                     $      
*   Email: cjxxi@msn.com    
*   Date: 2/12/2007                           $     
*   File Last Changed:  04/18/2016            $     
**####################################################################################################    */    

namespace CJAX\Core;

/**
 * The Format class, a utility class for generating formatted AJAX messages.
 * @category CJAX
 * @package Core
 * @author CJ Galindo <cjxxi@msn.com>
 * @copyright (c) 2008, CJ Galindo
 * @link https://github.com/ajaxboy/cjax
 * @version 6.0
 * @since 1.0
 */

class Format{
    
	/**
	 * The CSS_SUCCESS constant, defines the integer value for AJAX success message. 
	 * @var int
     * @constant
	 */  	
	const CSS_SUCCESS = 1;
    
	/**
	 * The CSS_SUCCESS constant, defines the integer value for AJAX warning message. 
	 * @var int
     * @constant
	 */      
	const CSS_WARNING = 2;
    
	/**
	 * The CSS_INFO constant, defines the integer value for AJAX info message. 
	 * @var int
     * @constant
	 */         
	const CSS_INFO = 3;
    
	/**
	 * The CSS_ERROR constant, defines the integer value for AJAX error message. 
	 * @var int
     * @constant
	 */         
	const CSS_ERROR = 4;
    
	/**
	 * The CSS_PROCESS constant, defines the integer value for AJAX process message. 
	 * @var int
     * @constant
	 */         
	const CSS_PROCESS = 5;
    
	/**
	 * The CSS_LOADING constant, defines the integer value for AJAX loading message. 
	 * @var int
     * @constant
	 */         
	const CSS_LOADING = 6;
   
	/**
	 * The prompts property, stores the number of time message is prompted. 
     * @access private
	 * @var int
     * @static
	 */       
	private static $prompts = 0;
	
	
	/**
     * The output method, writes an AJAX dialog on the screen.
	 * @param string  $content
     * @param string  $title
     * @access public
     * @return string
     */	    
	public function output($content = null, $title = null){
		$html[] = "<div class='cjax_dialog'>";		
		if($title){
			$html[] = "<div class='cjax_dialog_title'>";
			$html[] = $title;
			$html[] = "</div>";
		}
		
		$html[] = $content;		
		$html[] ="</div>";		
		return implode($html);
	}
	
	/**
	 * The prompt method, show an AJAX prompt message on the screen.
	 * @param string $msg
	 * @param string $title
	 * @param string $id
	 * @return string
	 */
	public function prompt($msg, $title = null, $id = 'cjax_prompt_box'){
		self::$prompts++;		
		$string = null;	
		if($type = CJAX::CSS_INFO){
			$css = "information";
		} 
        elseif($type == CJAX::CSS_WARNING){
			$css = "warning";
		}
		
		$msgId = $id.self::$prompts;		
		if($title){
            $string ="<div onmousedown=\"CJAX.drag(event,'{$msgId}')\" class='{$css} bar' style='padding: 5px 5px 0px 4px;font-weight: bold;'>$title <a href='javascript:void(0)' onclick=\"CJAX.remove('$msgId');\"/><div style='position: relative; float: right; top: -4px' class='cjax_close'></div></a></div>";
		}		
        return "<div id='{$msgId}' class='cjax_prompt_box_class'>{$string}
		            <div>{$msg}
                        <div style='clear:both'></div>
		            </div>
		        </div>";
	}

	/**
	 * The message method, show a custom CJAX message on the screen.
	 * @param string $text
	 * @param string $type
	 * @return string
	 */	
	public function message($text, $type = self::CSS_SUCCESS){
		if($type == self::CSS_SUCCESS || !$type){
            $css = " cjax_success";
		} 
        elseif($type == self::CSS_WARNING){
			$css = " cjax_warning";
		} 
        elseif($type == self::CSS_ERROR){
			$css = " cjax_error";
		}
        elseif($type == self::CSS_PROCESS){
			$css = " cjax_process cjax_loading_f";
		} 
        elseif($type == self::CSS_INFO){
			$css = " cjax_info";
		} 
        elseif($type == self::CSS_LOADING){
			$css = " cjax_process cjax_loading_f";
		}
        return "<div class='cjax_message cjax_message_type{$css}'>{$text}</div>\n";
	}	
}
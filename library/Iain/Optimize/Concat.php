<?php

	/**
	 * Concatention Model, to combine JavaScript and CSS 
	 * files. Instead of a single file per page build, this
	 * model will build concatention files.
	 * 
	 * @author Iain Cambridge
	 * @copyright Iain Cambridge all rights reserved 2011 (c)
	 * @license http://backie.org/copyright/bsd-license BSD License
	 */

class Iain_Optimize_Concat {
	
	/**
	 * Contains the raw files with the pages they  are to be used with.
	 * @var array
	 */	
	public static $rawFiles = array( 'scripts' => array(), 'styles' => array() );
	
	/**
	 * Holds the build names for each page.
	 * @var array
	 */
	public static $pageBuilds = array( 'scripts' => array(), 'styles' => array() );
	
	/**
	 * Holds all the builds lists.
	 * @var array
	 */
	public static $buildPatterns = array( 'scripts' => array(), 'styles' => array() );
	
	/**
	 * Handles adding CSS or JavaScript files to the self::$rawFiles[$fileType]
	 * array. 
	 *  
	 * @param String $fileType Is ethier scripts or styles.
	 * @param Array|String $controllers The name(s) of the controller(s) that the file(s) are for.
	 * @param Array|String $script The file(s) that are to be used.
	 * 
	 * @return Boolean true is successful, false is unsuccessful.
	 */
	public static function addFile($fileType,$controllers,$script){
		$fileType = strtolower($fileType);
		if ( $fileType != "scripts" && $fileType != "styles" ){
			return false;
		}
		
		if ( !is_array($controllers) ){
			$controllers = array($controllers);
		}
		
		foreach($controllers as $controller ){
			if ( !isset(self::$rawFiles[$fileType][$controller]) ){
				self::$rawFiles[$fileType][$controller] = array();
			}
			if ( !is_array($script) ){
				self::$rawFiles[$fileType][$controller][] = $script;
			} else {
				self::$rawFiles[$fileType][$controller] = array_merge(self::$rawFiles[$fileType][$controller],$script);
			}
		}
		
		return true;
	}
	
	/**
	 * Handles turning indiviual files into concentration patterns.
	 * 
	 * @param String $fileType
	 * 
	 * @return Array returns the build patterns
	 */
	protected static function process( $fileType ){
		$buildFiles = array();
		self::$buildPatterns = array();
		foreach ( self::$rawFiles[$fileType] as $controller => $controllerFiles ){
			
			foreach ( $controllerFiles as $file ){
				if ( !isset($buildFiles[$file]) || !is_array($buildFiles[$file]) ){
					$buildFiles[$file] = array();
				}
				$buildFiles[$file][] = $controller;
			}
			
		}
		
		foreach( $buildFiles as $file => $controllerArray ){
			
			sort($controllerArray);
			$key = implode("", $controllerArray);
			
			foreach ( $controllerArray as $controller ){
				if ( !isset(self::$pageBuilds[$fileType][$controller]) || !is_array(self::$pageBuilds[$fileType][$controller]) ){
					self::$pageBuilds[$fileType][$controller] = array();
				}
				self::$pageBuilds[$fileType][$controller][] = $key;	
			} 
			
			if ( !isset(self::$buildPatterns[$key]) || !is_array(self::$buildPatterns[$key]) ){
				self::$buildPatterns[$key] = array();	
			}
			self::$buildPatterns[$key][] = $file;
		}
		
		return self::$buildPatterns;
	}
	
	/**
	 * Fetches the build patterns.
	 * 
	 * @param unknown_type $fileType
	 */
	public static function getBuildPatterns($fileType){

		return self::process($fileType);
		
	}
	
}
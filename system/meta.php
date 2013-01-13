<?php

/*

	meta.php

	This file contains macros which
	return some global meta information.

*/

	//
	//	Include the system 
	//

	require_once('system.php');

	//
	//	Define a class which contains info about the app	
	//

	class Meta{

		public static $APPNAME = "Lightbulb";
		public static $SLOGAN = "Registration Alerts. At your service.";		
		public static $VERSION = '1.0';
		public static $AUTHORS = array('Moshe Berman');

		//
		//
		//

		function __construct(){
			
		}

		//
		//	This function returns the name 
		//	of the program as a string.
		//

		static function appName(){
			return Meta::$APPNAME;
		}

		//
		//	Returns the app name and version
		//

		static function fullAppName(){
			return Meta::$APPNAME . ' ' . Meta::$VERSION;
		}

		//
		//	Returns the full name with the slogan
		//

		static function fullNameWithSlogan(){
			return Meta::$APPNAME . ' - ' . Meta::$SLOGAN;
		}
	}

?>
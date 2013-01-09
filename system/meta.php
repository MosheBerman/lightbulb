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

		private $APPNAME = "Lightbulb";
		private $VERSION = '1.0';
		private $AUTHORS = array('Moshe Berman', 'Yosef Gunsburg');

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
			return $APPNAME;
		}

		//
		//	Returns the app name and version
		//

		static function fullAppName(){
			return $APPNAME . ' ' . $VERSION;
		}
	}

?>
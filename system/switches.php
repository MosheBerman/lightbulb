<?php

/*

	switches.php

	This file contains a class which contains
	flags for the entire project. Features can
	be enabled or disabled here.
	
*/

	include_once('system.php');

	/**
	* 
	*/
	class Switches 
	{
		
		//
		//	UI Switches
		//

		static public $LOG_ERRORS = true;
		static public $NEW_SIGN_UPS_ENABLED = true;
		static public $LOG_IN_ENABLED = true;
		static public $FAQ = true;
		static public $HUMBLE_HOME = false; // turn on to hide the site

		//
		//	CRON Switches
		//

		static public $CRON_ENABLED = true;
		static public $ALERTS_ENABLED = false;
	}

?>
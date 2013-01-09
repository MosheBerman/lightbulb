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

		static public $LOG_ERRORS = false;
		static public $NEW_SIGN_UPS_ENABLED = false;
		static public $LOG_IN_ENABLED = false;

		//
		//	CRON Switches
		//

		static public $CRON_ENABLED = true;
		static public $ALERTS_ENABLED = false;
	}

?>
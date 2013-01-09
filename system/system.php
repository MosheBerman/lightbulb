<?php

/*
	
	lightbulb-system.php

	This file require_onces all of the files 
	require_onced for lightbulb system to 
	operate correctly.

*/

	require_once('alerter.php');
	require_once('bcrypt.php');
	require_once('course.php');
	require_once('differ.php');
	require_once('mail.php');
	require_once('meta.php');
	require_once('serializer.php');
	require_once('timer.php');
	require_once('uimanager.php');
	require_once('user.php');
	require_once('usermanager.php');
	require_once('utils.php');
	require_once('scraper.php');
	require_once('session.php');
	require_once('switches.php');



	//
	//	Configure erros, based on the 
	//	switches defined in switches.php
	//

	if (Switches::$LOG_ERRORS == false) {
		error_reporting(E_ALL);
		ini_set('display_errors', 'On');		
	}
	else{
		error_reporting(0);
		ini_set('display_errors', 'Off');		
	}

	


?>
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

	/*
	**
	**	DEBUG: Set A_ALL or 0 to 
	**	toggle error reporting.
	**
	*/

	error_reporting(0);
	ini_set('display_errors', 'On');


?>
<?php

/*

	api.php

	This file contains code for a generalized 
	API for the lightbulb system.

*/

	//
	//	Require a valid API token.
	//
	//	TODO: Implement a class which 
	//	checks for valid API tokens.
	//

$token = $_REQUEST['token'] ? $_REQUEST['token'] : "";

if ($token == "") {
	return "Invalid token";
}



?>
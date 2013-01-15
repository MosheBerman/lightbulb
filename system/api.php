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


include_once('system.php');

$token = iSset($_REQUEST['token']) ? $_REQUEST['token'] : "";
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : null;
$code = isset($_REQUEST['code']) ? $_REQUEST['code'] : null;
$shouldFollow = isset($_REQUEST['follow']) ?$_REQUEST['follow'] : null;

if ($token == "") {
	//return "Invalid token";
}



if (isset($action)) {

	//
	//	Follow a section
	//

	if ($action == 'follow') {
		$follower = new lightbulb\Follower;
		return $follower->follow($code);
	}

	//
	//	Unfollow a section
	//

	else if($action == 'unfollow'){
		$follower = new lightbulb\Follower;
		return $follower->unfollow($code);
	}

	//
	//	Toggle if courses are followed.
	//

	else if($action == 'toggleFollowCourses'){
		$follower = new lightbulb\Follower;
		return $follower->setShouldFollowCourses($shouldFollow);
	}

	//
	//	Searches to go here...
	//

	else if ($action == 'search') {

		 
	}
}

else{
	return "no action";
}

?>
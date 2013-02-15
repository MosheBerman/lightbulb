<?php

	/*

		publicapi.php

		This file contains the public api
		calls available to lightbulb clients.

	*/

		require('../private/system/api.php');

		//
		//	Check for an action
		//

		$action = array_key_exists('action', $_REQUEST) ? $_REQUEST['action'] : null;

		//
		//	If there's no action
		//	return an error.
		//

		if (is_null($action)) {
			$result = array("error"=>"No action specified.", "code"=>"1");
			die(json_encode($result));
		}

		//
		//
		//

		$api = new API();

		//
		//
		//

		if ($action == "courses") {
			$result = $api->allCourses();
			die(json_encode($result));
		}

		else{
			$result = array("error"=>"Invalid action.", "code"=>"2");
			die(json_encode($result));			
		}

?>
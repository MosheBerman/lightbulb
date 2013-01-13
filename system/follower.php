<?php

/*

	follower.php

	The follower class allows users to 
	follow and unfollow sections, as 
	well as follow general class changes.

	It extens the user UserManager class 
	found in usermanager.php

	Designed for AJAX calls, the methods
	that perform follows and unfollows 
	don't actually return entire webpages.
	Instead, they return JSON dictionaries
	with responses in them.

*/

namespace lightbulb{

	/**
	* 
	*/
	class Follower extends UserManager
	{
		
		function __construct()
		{
			parent::__construct();
		}


		//
		//	Enables or disables course follows 
		//	for the currently logged in user.
		//
		//	If the follow failed, then return
		// 	a failure message encapsulated in
		//	an associative array.
		//

		function setShouldFollowCourses($shouldFollowCourses = null){
			
			if (!is_bool($shouldFollowCourses)) {
				return $this->error("Failed to change course preferences. Invalid value.");
			}

			//
			//	Prepare the connection
			//

			$connection = $this->connection();

			//
			//	Prepare the update statement
			//

			$updateStatement = $this->currentUser()->followStatement($shouldFollowCourses);

			//
			//	Execute the update
			//

			$connection->prepare($updateStatement)->execute();

			//
			//	Refresh the user
			//

			$this->refresh();

			//
			//	Respond.
			//

			return $this->response(0, "Updated.", array("following" => (bool)$this->currentUser()->isWatchingCourses));
		}

		//
		//
		//


		//
		//	Returns an error message encapsulated in 
		//

		function error($message){
			return $this->response(1, $message, null);
		}

		//
		//
		//

		function response($code = 0, $message = "", $payload = null){
			return json_encode(array("code" => $code, "message", "payload" => $payload));
		}
	}
}

?>
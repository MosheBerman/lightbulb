<?php

/*

	alerter.php

	This file contains the alerter class which alerts users
	via text or email when it's told to.	

*/

namespace lightbulb{

	include_once('system.php');

	class Alerter{

		private $differ;
		private $users;
		private $follower;

		function __construct($differ, $users){

			if (is_null($differ) || is_null($users)) {
				echo "Invalid Alerter.";
				return;
			}

			$this->differ = $differ;
			$this->users = $users;
			$this->follower = new Follower();

		}

		function alert(){
		
			if (is_null($this->differ)) {
				return;
			}

			if($this->differ->hasChanges()){


				

				}
			else{
				echo "No changes to alert.\n";
			}
			
		}

	}
}

?>
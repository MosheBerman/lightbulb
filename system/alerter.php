<?php

/*

	alerter.php

	This file contains the alerter class which alerts users
	via text or email when it's told to.	

*/

namespace lightbulb{

	class Alerter{

		private $differ;
		private $users;


		function __construct($differ, $users){

			if (is_null($differ) || is_null($users)) {
				echo "Invalid Alerter.";
				return;
			}

			$this->differ = $differ;
			$this->users = $users;

		}

		function alert(){
		
			if (is_null($this->differ)) {
				return;
			}

			if($this->differ->hasChanges()){
				echo "Alerting would happen here.\n";
			}
			else{
				echo "No changes to alert.\n";
			}
			
		}

	}
}

?>
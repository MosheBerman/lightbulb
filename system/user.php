<?php


/*

	user.php

	This file contains a user class. The user
	class describes a user and the classes
	that said user is following.

*/


		include('system.php');		

		class User
		{

			public $id;
			public $isWatchingCourses;
			public $email;
			public $number;
			public $username;
			public $password;

			public $followedSectionIDs;

			function __construct($username)
			{

				$this->username = $username;
					
				$this->followedCourseIDs = array();
				$this->followedSectionIDs = array();
			}

			//
			//	Returns an insert statement 
			//	for the user to be inserted
			//	into a database as necessary.
			//
			//	The insert statement assumes
			//	that the password hasn't been
			//	hashed yet.
			//

			function insertStatement(){
				return "INSERT INTO Users(username, password, number) Values('".$this->username."','".\Bcrypt::hash($this->password)."', '".$this->number."') ";
			}

			//
			//	Returns an update statement which 
			//	sets the user's password
			//
			//	We also assume a valid ID has been
			//	set on the User instance.
			//
			//	The update statement assumes
			//	that the password has been
			//	hashed already.
			//

			function updateStatement(){
				return "UPDATE Users password='".$this->password."', email='".$this->email."', number='".$this->number."' where id='".$this->id."'";
			}

			//	
			//	Returns a follow statement
			//	which changes the general 
			//	course follow status of a 
			//	user.
			//

			function followCourseStatement($follow){

				$follow = $follow ? 1 : 0;

				return "UPDATE Users isWatchingCourses='".$follow."'' WHERE id='".$this->id."'";;
			}

			//
			//	Returns a statement which fetches all sections 
			//	which the current user follows.
			//

			function followedSectionsStatement(){
				// TODO: Inner join to see selected courses?


			}

			//
			//	Returns an update statement which
			//	follows a course section for the user.
			//

			function followSectionStatement($code){
				return "INSERT INTO FollowedSections(userID, sectionCode) VALUES(". $this->id.", ".$code.")";
			}

			//
			//	Returns an update statement which
			//	unfollows a course section for the user.
			//

			function unfollowSectionStatement($code){
				return "DELETE FROM FollowedSections WHERE UserID='".$this->id."' AND sectionCode='".$code."'";
			}


		}


?>
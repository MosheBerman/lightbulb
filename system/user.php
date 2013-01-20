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
			//	Returns an update statement which
			//	follows a course section for the user.
			//

			function followSectionStatement($code){
				return "INSERT INTO FollowedSections(userID, code) VALUES(". $this->id.", ".$code.")";
			}

			//
			//	Returns an update statement which
			//	unfollows a course section for the user.
			//

			function unfollowSectionStatement($code){
				return "DELETE FROM FollowedSections WHERE UserID='".$this->id."' AND code='".$code."'";
			}

			//
			//	Returns the sections and course info followed by this user.
			//	Best used with PDO's associative array fetch type.
			//

			function showFollowedSectionsStatement(){
				return "SELECT Courses.id as coursesID, Sections.id as sectionsID, Sections.*, Courses.* FROM FollowedSections INNER JOIN Sections ON Sections.code = FollowedSections.code INNER JOIN Courses ON Courses.id = Sections.courseID WHERE FollowedSections.userID = '".$this->id."'";
			}

			//
			//	Returns the sections and course info followed by this user.
			//	Best used with PDO's associative array fetch type.
			//

			function showFollowedSectionCodesStatement(){
				return "SELECT * FROM FollowedSections";
			}			

		}


?>
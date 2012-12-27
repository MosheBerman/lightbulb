<?php 

/*

	serializer.php

	This file contains a class responsible for
	reading and saving CUNY courses to a database. 

*/

namespace Lightbulb{

	use \PDO as PDO;

	class Serializer
	{
		
		private $connection; 			// PDO object
		private $connectionString;		// String

		private $isDatabaseEmpty;

		private $courses;
		private $sections;

		private $failed;

		function __construct($_connectionString = null)
		{

			//
			//	If we have a connection string, 
			//	connect and try to deserialize.
			//

			if (!is_null($_connectionString)) {

				$this->setConnectionString($_connectionString);
				$this->failed = !$this->connect();	

				if(!$this->failed){
					$this->deserialize();										
				}			
			}

			//	Otherwise, there's no connection string, 
			//	so we fail immediately.

			else{
				$this->failed = true;
			}			
		}

		//
		//	Set the connectionString
		//

		function setConnectionString($_connectionString){

			if (is_null($_connectionString)) {
				$failed = true;				
				return;
			}

			$this->connectionString = $_connectionString;
		}

		//
		//	Attempts to connect to the database
		//

		function connect(){
			try{
				$this->connection = new PDO($this->connectionString);
				$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				return true;
			}
			catch(PDOException $e){
				echo "Error connecting.";
				return false;
			}
		}

		//
		//	Loads the class info out of the database
		//

		function deserialize(){
			//
			//	Prepare the PDO statements
			//
			
			$sectionQuery = $this->connection->query('SELECT * FROM Sections');
			$courseQuery = $this->connection->query('SELECT * FROM Courses ORDER BY name');
			
			//	List the parameters for the database
			$sectionProperties = array('section', 'code', 'openSeats', 'dayAndTime', 'instructor', 'buildingAndRoom', 'isOnline');
			$courseProperties = array('startDate', 'endDate', 'name', 'description', 'credits', 'hours', 'division', 'subject', 'lastUpdated', 'sections');
			
			//
			//	We want to fetch into objects, so let's hook that up.
			//
			
			$sectionQuery->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Section', $sectionProperties);
			$courseQuery->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Course', $courseProperties);
			
			//
			//	Perform the Query
			//
			
			$this->sections = $sectionQuery->fetchAll();
			$this->courses = $courseQuery->fetchAll();
			
			//
			//	Check if the database is empty
			//
			
			if(count($this->sections) == 0 && count($this->courses) == 0){
				$this->isDatabaseEmpty = true;
			}
			else{
				$this->isDatabaseEmpty = false;
				installSectionsIntoCourses($this->sections, &$this->courses); 
			}	
		}

		//
		//	Serialize courses into the database
		//

		function serialize($courses){

			//Empty old database
			$deleteCoursesStatement = $this->connection->prepare("TRUNCATE Courses");
			$deleteSectionsStatement = $this->connection->prepare("TRUNCATE Sections");
			
			$deleteCoursesStatement->execute();
			$deleteSectionsStatement->execute();
				
			//	Repopulate
			foreach($courses as $course){
				
				$sql = $course->SQLStatement();
				$preparedStatement = $this->connection->prepare($sql);
				
				try{
					$preparedStatement->execute();
				}
				catch(PDOException $e){
						echo "Failed to serialize course: " . $course->description();
						echo "\n";			
				}
				
				$lastID = $this->connection->lastInsertID();
							
				foreach($course->sections as $section){
					
					$sql = $section->SQLStatement($lastID);
					$preparedStatement = $this->connection->prepare($sql);
				
					try{
						$preparedStatement->execute();
					}
					catch(PDOException $e){
						echo "Failed to serialize course section : " . $section->description();
						echo "\n";
					}
				}
			}
		}

		/* Public getters for courses and sections */

		function getCourses(){
			return $this->courses;
		}

		function getSections(){
			return $this->sections;
		}

		//
		//	Failed?
		//

		function hasFailed(){
			return $this->failed;
		}
	}
}

?>
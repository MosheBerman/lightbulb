<?php

/*

course.php

This file defines a course class for storing class information.

*/

/**
* 
*/

namespace Lightbulb{
	class Course
	{
		
		//	Course info

		public $id;
		public $startDate;
		public $endDate;
		public $name;
		public $description;
		public $credits;
		public $hours; 
		public $division;
		public $subject;

		//	Meta
		public $lastUpdated;
		public $sections;

		//
		//	Constructor
		//
		
		function Course($id = '', $startDate = '', $endDate = '' , $name = '', $description = '', $credits = '', $hours = '', $division = '', $subject = '', $lastUpdated = '', $sections = array()){
			$this->startDate = $startDate;
			$this->endDate = $endDate;
			$this->name = $name;
			$this->description = $description;
			$this->credits = $credits;
			$this->hours = $hours;
			$this->division = $division;
			$this->subject = $subject;
			$this->lastUpdate = $lastUpdated;
			$this->sections = array();
		}

		//
		//	Adds a section to the course
		//

		function addSection($section){
			array_push($this->sections, $section);
		}

		//
		//	Returns a user visible description of the object
		//

		function description(){

			$description = " ------------- Course: -------------" . "\n";
			$description .= "" . $this->name . ": "; 
			$description .= " " . $this->credits . " credits,";
			$description .= " " . $this->hours . " hours,"; 
			$description .= " " . $this->division  . ","; 
			$description .= " " . $this->subject . "\n"; 
		
			$numberOfSections = count($this->sections);
			
			$description .= "Sections: " . $numberOfSections . "" . "\n"; 		

			foreach ($this->sections as $section) {
				$description .= "\n";
				$description .= $section->description()  . "\n";
			}

			return $description;
		}
		
		//
		//	This method returns an SQL statement for itself to be 
		//	entered into the database.
		//
		
		function SQLStatement(){
		
			$query = "INSERT INTO Courses (startDate, endDate, name, description, credits, hours, subject, division, lastUpdated)" .
			" VALUES('" . $this->startDate . "', '".$this->endDate."', '".$this->name."', '".$this->description."', '".$this->credits."', '".$this->hours."', '" . $this->subject . "', '".$this->divisionAsBool()."', NOW())";
			
			return $query;
		}
		
		//
		//	Converts the division to a zero or one,
		//	zero being Undergrad, one being grad.
		//
		
		function divisionAsBool(){
			return intval($this->division != "Undergraduate");
		}
	}

	/**
	* 
	*/

	class Section
	{
		public $id;
		public $courseID;
		public $section;
		public $code;
		public $openSeats;
		public $dayAndTime;
		public $instructor;
		public $buildingAndRoom;
		public $isOnline;
		public $course;

		function Section($id = '', $courseID = '', $section = '', $code = '', $openSeats = 0, $dayAndTime = '', $instructor = '', $buildingAndRoom = '', $isOnline = ''){
			$this->id = $id;
			$this->courseID = $courseID;
			$this->section = $section;
			$this->code = $code;
			$this->openSeats = $openSeats;
			$this->instructor = $instructor;
			$this->buildingAndRoom = $buildingAndRoom;
			$this->isOnline = $isOnline;
			
		}

		//
		//	Description Method
		//
		
		function description(){
			$description = "Section: " . $this->section . ", ";
			$description .= "Code: " . $this->code . ", ";
			$description .= " " . $this->openSeats . " open seats, "; 		
			$description .= " " . $this->dayAndTime . ", "; 		
			$description .= "Instructor: " . $this->instructor . ", ";
			$description .= " in " . $this->buildingAndRoom . ", "; 				
			$description .= "Online: " . $this->isOnline . "\n"; 

			return $description;				
		}
		
		//
		//	Short description
		//
		
		function shortDescription(){
			echo $this->course->name . " section " . $this->section . ", code " . $this->code;
		}
		
		//
		//	This method returns an SQL statement for itself to be
		//	entered into the database.
		//
		
		function SQLStatement($courseID){
		
			$query = "INSERT INTO Sections (courseID, section, code, openSeats, dayAndTime, buildingAndRoom, isOnline, instructor)" .
			" VALUES('".$courseID."', '".$this->section."', '".$this->code."', '".$this->openSeats."', '".$this->dayAndTime."', '".$this->buildingAndRoom."', '".$this->isOnlineAsBool()."', '" . $this->instructor . "')";
			
			return $query;
		}
		
		//
		//	Returns isOnline as a boolean
		//
		
		function isOnlineAsBool(){
			return intval(($this->isOnline == "Yes"));
		}
		
		//
		//	Returns true if there are no seats
		//
		
		function isClosed(){
			return $this->openSeats == 0;
		}
		
		//
		//	Returns true if there are 1 or more seats
		//
		
		function isOpen(){
			return $this->openSeats > 0;
		}
		
		//
		//	Returns true if a professor is listed
		//
		
		function hasProfessor(){
			return $this->instructor != "Staff";
		}
	}
}
?>
<?php

/*

course.php

This file defines a course class for storing class information.8

*/

/**
* 
*/

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
		$description .= "Name: " . $this->name . "\n"; 
		$description .= "Credits: " . $this->credits . "\n";
		$description .= "Hours: " . $this->hours . "\n"; 
		$description .= "Division: " . $this->division  . "\n"; 
		$description .= "Subject: " . $this->subject . "\n"; 
	
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

	function description(){
		$description = "Section: " . $this->section . "\n";
		$description .= "Code: " . $this->code . "\n";
		$description .= "Seats: " . $this->openSeats . "\n"; 		
		$description .= "Day and Time: " . $this->dayAndTime . "\n"; 		
		$description .= "Instructor: " . $this->instructor . "\n";
		$description .= "Where: " . $this->buildingAndRoom . "\n"; 				
		$description .= "Online: " . $this->isOnline . "\n"; 

		return $description;				
	}
	
	//
	//	This method returns an SQL statement for itself to be
	//	entered into the database.
	//
	
	function SQLStatement($courseID){
	
		$query = "INSERT INTO Sections (courseID, section, code, openSeats, dayAndTime, buildingAndRoom, isOnline)" .
		" VALUES('".$courseID."', '".$this->section."', '".$this->code."', '".$this->openSeats."', '".$this->dayAndTime."', '".$this->buildingAndRoom."', '".$this->isOnlineAsBool()."')";
		
		return $query;
	}
	
	//
	//	Returns isOnline as a boolean
	//
	
	function isOnlineAsBool(){
		return intval(($this->isOnline == "Yes"));
	}
}

?>
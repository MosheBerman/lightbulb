<?php

/*

course.php

This file defines a course class for storing class information.

*/


/**
* 
*/

class Course
{
	
	//	Course info

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

	function Course(){
		$this->sections = array();	
	}

	//
	//
	//

	function addSection($section){
		array_push($this->sections, $section);
		echo count($this->sections) . " sections in course " . $name . ". <br />\n";
	}

	//
	//
	//

	function description(){

		$description = " ------------- Course: ------------- <br />";
		$description .= "Name: " . $this->name . "<br />"; 
		$description .= "Credits: " . $this->credits . "<br />";
		$description .= "Hours: " . $this->hours . "<br />"; 
		$description .= "Division: " . $this->division . "<br />"; 
		$description .= "Subject: " . $this->subject . "<br />"; 
		
		$numberOfSections = $this->sections->length;

		foreach ($this->sections as $section) {
			$description .= $section->description() . "<br />";
		}

		return $description;
	}
}

/**
* 
*/

class Section
{
	public $section;
	public $code;
	public $openSeats;
	public $dayAndTime;
	public $instructor;
	public $buildingAndRoom;
	public $isOnline;

	function description(){
		$description = "Section: " . $this->section . "<br />";
		$description .= "Code: " . $this->code . "<br />";
		$description .= "Seats: " . $this->openSeats . "<br />"; 		
		$description .= "Day and Time: " . $this->dayAndTime . "<br />"; 		
		$description .= "Instructor: " . $this->instructor . "<br />"; 		
		$description .= "Where: " . $this->buildingAndRoom . "<br />"; 				
		$description .= "Online: " . $this->isOnline . "<br />"; 

		return $description;				
	}
}

?>
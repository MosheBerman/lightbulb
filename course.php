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
		$sections = array();
	}

	//
	//
	//

	function description(){

		$description = " ------------- Course: ------------- <br />";
		$description = $description . "Name: " . $this->name . "<br />"; 
		$description = $description . "Credits: " . $this->credits . "<br />";
		$description = $description . "Hours: " . $this->hours . "<br />"; 
		$description = $description . "Division: " . $this->division . "<br />"; 
		$description = $description . "Subject: " . $this->subject . "<br />"; 
		
		$numberOfSections = $this->sections->length;

		$description = $description . $numberOfSections . "Sections: <br />"; 

		for($i = 0; $i < $numberOfSections; $i++) {
			$section = $this->sections->item($i);
			$description = $description . $section->description() . "<br />";
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

	function Section(){

	}

	function description(){
		$description = "Section: " . $this->section . "<br />";
		$description = $description . "Code: " . $this->code . "<br />";
		$description = $description . "Seats: " . $this->openSeats . "<br />"; 		
		$description = $description . "Day and Time: " . $this->dayAndTime . "<br />"; 		
		$description = $description . "Instructor: " . $this->instructor . "<br />"; 		
		$description = $description . "Where: " . $this->buildingAndRoom . "<br />"; 				
		$description = $description . "Online: " . $this->isOnline . "<br />"; 

		return $description;				
	}
}

?>
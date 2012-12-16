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
	}

	//
	//
	//

	function description(){

		$description = " ------------- Course: ------------- <br />" . "\n";
		$description .= "Name: " . $this->name . "\n"; 
		$description .= "Credits: " . $this->credits . "\n";
		$description .= "Hours: " . $this->hours . "\n"; 
		$description .= "Division: " . $this->division  . "\n"; 
		$description .= "Subject: " . $this->subject . "\n"; 
	
		$numberOfSections = count($this->sections);
		
		$description .= "Sections: " . $numberOfSections . "<br />" . "\n"; 		

		foreach ($this->sections as $section) {
			$description .= "\n";
			$description .= $section->description()  . "\n";
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
		$description = "Section: " . $this->section . "\n";
		$description .= "Code: " . $this->code . "\n";
		$description .= "Seats: " . $this->openSeats . "\n"; 		
		$description .= "Day and Time: " . $this->dayAndTime . "\n"; 		
		$description .= "Instructor: " . $this->instructor . "\n"; 		
		$description .= "Where: " . $this->buildingAndRoom . "\n"; 				
		$description .= "Online: " . $this->isOnline . "\n"; 

		return $description;				
	}
}

?>
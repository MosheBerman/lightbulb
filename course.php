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
		$description .= "Name: " . $this->name . "<br />" ."\n"; 
		$description .= "Credits: " . $this->credits . "<br />" . "\n";
		$description .= "Hours: " . $this->hours . "<br />" . "\n"; 
		$description .= "Division: " . $this->division . "<br />" . "\n"; 
		$description .= "Subject: " . $this->subject . "<br />" . "\n"; 
	
		$numberOfSections = count($this->sections);
		
		$description .= "Sections: " . $numberOfSections . "<br />" . "\n"; 		

		foreach ($this->sections as $section) {
			$description .= " --- Section: ---" .  "\n";
			$description .= $section->description() . "<br />" . "\n";
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
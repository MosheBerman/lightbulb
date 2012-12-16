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

	var $name;
	var $description;
	var $credits;
	var $hours; 
	var $division;
	var $subject;

	//	Meta
	var $lastUpdated;
	var $sections;

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
		$description = $description . "Name: " . $name . "<br />"; 
		$description = $description . "Credits: " . $credits . "<br />";
		$description = $description . "Hours: " . $hours . "<br />"; 
		$description = $description . "Division: " . $division . "<br />"; 
		$description = $description . "Subject: " . $subject . "<br />"; 

		foreach ($sections as $section) {
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
	var $section;
	var $code;
	var $openSeats;
	var $dayAndTime;
	var $instructor;
	var $buildingAndRoom;
	var $isOnline;

	function Section(){

	}

	function description(){
		$description = "Section: " . $section . "<br />";
		$description = $description . "Code: " . $code . "<br />";
		$description = $description . "Seats: " . $openSeats . "<br />"; 		
		$description = $description . "Day and Time: " . $dayAndTime . "<br />"; 		
		$description = $description . "Instructor: " . $instructor . "<br />"; 		
		$description = $description . "Where: " . $buildingAndRoom . "<br />"; 				
		$description = $description . "Online: " . $isOnline . "<br />"; 

		return $description;				
	}
}

?>
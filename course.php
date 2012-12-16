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
}

?>
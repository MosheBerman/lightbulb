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

	private $name;
	private $description;
	private $credits;
	private $hours; 
	private $division;
	private $subject;

	//	Meta
	private $lastUpdated;

	$sections;

	//
	//	Constructor
	//

	function Course(){

	}



}

/**
* 
*/

class Section
{
	private $section;
	private $code;
	private $openSeats;
	private $dayAndTime;
	private $instructor;
	private $buildingAndRoom;
	private $isOnline;

	function __construct(argument)
	{
		# code...
	}
}

?>
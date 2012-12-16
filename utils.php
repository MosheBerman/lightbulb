<?php

/*

	utils.php

	This file contains some utility methods for the 
	CUNY Registration Alert Program.

*/

	include_once('./course.php');

	//	
	//	Tell us if a given element is
	//	a course section table.
	//

	function elementIsACourseSectionTable(DOMElement $element){
			
			$tableHasClass = $element->hasAttribute('class');
			$tableIsCourseTable = $element->getAttribute("class") == "coursetable";	

			return $tableHasClass && $tableIsCourseTable;
	}

	//
	//	Takes a table and parses it into an 
	//	instance of the Course class.
	//

	function courseFromTable(DOMElement $table){

	}

	//
	//	Take a table containing course sections
	//	and parse it put the results into a
	//	give course object.
	//

	function addSectionsToCourseUsingTable(Course $course, $DOMElement table){
		
	}

?>
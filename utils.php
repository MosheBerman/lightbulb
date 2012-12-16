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

		$secondRow = $table->getElementsByTagName('tbody')->item(0)->getElementsByTagName('tr')->item(1);
		$cells = $secondRow->getElementsByTagName('td');

		$course = new Course;

		$course->$name = valueForElementInList(0, $cells);
		$course->$description = valueForElementInList(1, $cells);
		$course->$credits = valueForElementInList(2, $cells);
		$course->$hours = valueForElementInList(3, $cells);
		$course->$division = valueForElementInList(4, $cells);
		$course->$subject = valueForElementInList(5, $cells);

		return $course;

	}


	//
	//	Takes a table and parses it into an 
	//	instance of the Section class.
	//

	function sectionFromRow(DOMElement $row){

		$secondRow = $table->getElementsByTagName('tbody')->item(0)->getElementsByTagName('tr')->item(1);
		$cells = $secondRow->getElementsByTagName('td');

		$section = new Section;

		$section->$section = valueForElementInList(0, $cells);
		$section->$code = valueForElementInList(1, $cells);
		$section->$openSeats = valueForElementInList(2, $cells);		
		$section->$dayAndTime = valueForElementInList(3, $cells);		
		$section->$instructor = valueForElementInList(4, $cells);		
		$section->$isOnline = valueForElementInList(5, $cells);		

		return $section;

	}

	//
	//	Take a table containing course sections
	//	and parse it put the results into a
	//	give course object.
	//

	function addSectionsToCourseUsingTable(Course $course, DOMElement $table){

		$rows = $table->getElementsByTagName('tr');
		$numRows = $rows->length;

		for ($i=0; $i < $numRows; $i++) { 

			$section = sectionFromRow($rows->item($i));
			$course->$sections[] = $section;
		}

		return $course;
	}

	//
	//	Returns the text from a cell
	//	with a 
	//

	function valueForCell($index, $list){
		return $list->item($index)->nodeValue;
	}

?>
<?php

/*

	utils.php

	This file contains some utility methods for the 
	CUNY Registration Alert Program.

*/

	require_once('course.php');

	//
	//
	//

	function elementIsCourseHeaderTable(DOMNode $element){

		if (is_null($element)) {
			return false;
		}

		$firstCell = $element->getElementsByTagName('td')->item(0);
		$isGrayClass = $firstCell->getAttribute('class') == "graybox";

		return $isGrayClass;
	}

	//	
	//	Tell us if a given element is
	//	a course section table.
	//

	function elementIsACourseSectionTable(DOMNode $element){
			
			$tableHasClass = $element->hasAttribute('class');
			$tableIsCourseTable = $element->getAttribute("class") == "coursetable";	

			return $tableHasClass && $tableIsCourseTable;
	}

	//
	//	Takes a table and parses it into an 
	//	instance of the Course class.
	//

	function courseFromTable(DOMNode $table){

		$secondRow = $table->getElementsByTagName('tr')->item(1);	
		$cells = $secondRow->getElementsByTagName('td');

		$course = new Course;

		$course->startDate = valueForElementInList(0, $cells);
		$course->endDate = valueForElementInList(1, $cells);		
		$course->name = valueForElementInList(2, $cells);
		$course->description = valueForElementInList(3, $cells);
		$course->credits = valueForElementInList(4, $cells);
		$course->hours = valueForElementInList(5, $cells);
		$course->division = valueForElementInList(6, $cells);
		$course->subject = valueForElementInList(7, $cells);

		return $course;

	}


	//
	//	Takes a table and parses it into an 
	//	instance of the Section class.
	//

	function sectionFromRow(DOMNode $row){

		$cells = $row->getElementsByTagName('td');

		//
		//	Skip any row with a single cell
		//

		if ($cells->length == 1) {
			return NULL;
		}

		//
		//	Skip header rows
		//

		if (valueForElementInList(0, $cells) == "Section" || valueForElementInList(0, $cells) == "") {
			return NULL;
		}


		$section = new Section;

		$section->section = valueForElementInList(0, $cells);
		$section->code = valueForElementInList(1, $cells);
		$section->openSeats = openSeatsForCell($cells);		
		$section->dayAndTime = valueForElementInList(3, $cells);		
		$section->instructor = valueForElementInList(4, $cells);		
		$section->buildingAndRoom = valueForElementInList(5, $cells);
		$section->isOnline = valueForElementInList(6, $cells);	

		return $section;

	}

	//
	//	Take a table containing course sections
	//	and parse it put the results into a
	//	give course object.
	//

	function addSectionsToCourseUsingTable(Course $course, DOMNode $table, $sections){

		$rows = $table->getElementsByTagName('tr');
		$numRows = $rows->length;

		for ($i=0; $i < $numRows; $i++) { 

			$section = sectionFromRow($rows->item($i));

			//	Make sure we have an array to put sections into	

			if (is_null($course->sections)) {
				$course->sections = array();
			}

			//	Skip "meta" rows, since they're not really sections

			if (is_null($section)) {
				continue;
			}

			$course->addSection($section);
			$sections[] = $section;
		}

		return $sections;
	}

	//
	//	Returns the text from a cell
	//	with a list of cells
	//

	function valueForElementInList($index, $list){
			
		// Pull out a value, stripping whitespace

		$value =  $list->item($index)->nodeValue;
		$value = trim($value);
		return $value;
	}

	//
	//	Counts the number of open seats in a class
	//
	
	function openSeatsForCell($cells){

		$seats = valueForElementInList(2, $cells);
		
		//
		//	If there's no parens in the string,
		//	return the numbers.
		//
		
		if(strpos($seats,"(") === false){
			return intval($seats);
		}
		
		//
		//	If the first char is an open paren
		//	then we have a positive number of
		//	seats.
		//
		
		else if(strpos($seats, "(" == 0)){
			return intval(trim(str_replace("**)", "", str_replace("(**", "", $seats))));
		}
		
		//
		//	Else, the section closed,
		//	return zero. 
		//
		
		else{
			return intval(0);
		}
	}
?>

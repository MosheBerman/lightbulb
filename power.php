<?php

/*

	power.php

	This file is the component that reads data from CUNY's website
	and saves it to a sqlite database.

*/


	$totalTime = microtime(true);

	//
	//	Tell PHP to stop managing my time.
	//

	set_time_limit(0);

	//
	//	Include some files
	//

	include('course.php');
	include('utils.php');

	//
	//	Set up a cURL handle and some POST headers
	//

	$curl_handle = curl_init();

	$CUNY_URL = "http://student.cuny.edu/cgi-bin/SectionMeeting/SectMeetEval.pl?DB=ORACLE_A&STYLE=NEW&COLLEGECODE=05";

	$URL_PARAMS = array('MAXCOURSE' => "037|64|MUSC.  ,119",
	  'COURSECNT' => "1507",
	  'PREFIX' => "title",
	  'NUMBER'  => "ANY", 
	  'SUBJECT' => "Select All",
	  'COLLEGE'  => "05",
	  'DIVISION' => "UG", 
	  'DUD'  => "1",
	  'COMPLETE' => "1",
	  'STYLE' => "NEW",
      	  'DB' => "ORACLE_A",
	  'DBSTATUS' => "ORACLE_A* : from database, A_DateTime is '2012/12/15 18:30:00', B_DateTime is '2012/12/15 17:00:00'",
	  'ASOF' => "<FONT class='orange'>UPDATED</FONT> <FONT class='gray'>12/15/2012,  6:30:00 PM</FONT>'",
	  'DBSTATORIG' => "ORACLE_A* : from database, A_DateTime is '2012/12/15 18:30:00', B_DateTime is '2012/12/15 17:00:00'", 
	  'ASOFORIG' => "<FONT class='orange'>UPDATED</FONT> <FONT class='gray'>12/15/2012,  6:30:00 PM</FONT>", 
	  'sessions' => "BOTH",
	  'only_open' => "ALL",
	  'sacsed' => "YES",
	  'oso' => "ALL",
	  'TERM' => "201302"

	  );

	//
	//	Configure CURL
	//

	curl_setopt($curl_handle, CURLOPT_URL, $CUNY_URL);
	curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT,0);
	curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($curl_handle, CURLOPT_POST, count($URL_PARAMS));
	curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $URL_PARAMS);

	//
	//	Announce cURL
	//

	echo "Running cURL... \n";

	$time = microtime(true);

	//
	//	Execute the request.
	//

	$response = curl_exec($curl_handle);
	// $response = file_get_contents('./source.html');

	//
	//	Log cURL time.
	//

	$time = microtime(true) - $time;

	echo "cURL took " . $time . " seconds. \n";

	//
	//	Announce cleanup
	//

	echo "Starting HTML cleanup... \n";

	$time = microtime(true);

	//	General cleanup 

	$response = str_replace('id="time"', 'class="time"', $response);
	$response = str_replace('COLSPAN=8 ALIGN=LEFT VALIGN=TOP', '', $response);
	$response = str_replace('TD', 'td', $response);
	$response = str_replace('BR', 'br / ', $response);
	$response = str_replace('TABLE', 'table', $response);	
	$response = str_replace('BORDER=0', 'border="0"', $response);
	$response = str_replace('CELLPADDING=3 CELLSPACING=2', 'cellpadding="3" cellspacing="2"', $response);
	$response = str_replace('FONT', 'span', $response);
	$response = str_replace('HTML', 'html', $response);
	$response = str_replace('HEAD', 'head', $response);	
	$response = str_replace('BODY', 'body', $response);	
	$response = str_replace('INPUT', 'input', $response);	
	$response = str_replace('NAME', 'name', $response);	
	$response = str_replace('TYPE', 'type', $response);	
	$response = str_replace('VALUE', 'value', $response);	
	$response = str_replace('A HREF', 'a href', $response);	
	$response = str_replace('IMG SRC', 'img src', $response);	
	$response = str_replace('FORM', 'form', $response);	
	$response = str_replace('TITLE', 'title', $response);
	$response = str_replace('HIDDEN', 'hidden', $response);
	$respone = str_replace('Â', '', $response);
	//	Specific hacks and fixes for the W3 validator

	$response = str_replace('/styles/maintwo.css', 'http://student.cuny.edu/styles/maintwo.css', $response);
	$response = str_replace('/images/', 'http://student.cuny.edu/images/', $response);	
	$response = str_replace('<!DOCtype html PUBLIC "-//W3C//Dtd html 4.01 Transitional//EN">', '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">', $response);
	$response = str_replace("Staff, .", 'Staff', $response);
	$response = str_replace("Staff, ", 'Staff', $response);	
	$response = str_replace("Staff", 'Staff', $response);	
	$response = str_replace('&MED', '&amp;MED', $response);
	//Hack character encoding meta tag in there...
	$response = str_replace('</title>', '</title><meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" >', $response);

	//
	//	Log cleanup time.
	//

	$time = microtime(true) - $time;

	echo "Cleanup took " . $time . " seconds. \n";		

	//	
	//	Announce Tidy
	//

	echo "Starting Tidy... ";

	$time = microtime(true);

	//
	//	Prepare some config for tidy
	//
		$config = array(
		           'indent'         => true,
		           'output-xhtml'   => true,
		           'wrap'           => 200);

	//
	// Tidy up the HTML
	//

	$tidy = new tidy;
	$tidy->parseString($response, $config, 'utf8');
	$tidy->cleanRepair();

	$html = $tidy;

	//
	//	Log Tidy time
	//

	$time = microtime(true) - $time;

	echo "Tidy took " . $time . " seconds. \n";

	//
	//	Announce DOMDocument
	//

	echo "Starting to load HTML into DOMDocument... \n";
	
	$time = microtime(true);

	//
	// Load the HTML into a DOMDocument
	//

	$dom = new DOMDocument();

	$dom->loadHTML($html);

	//
	//	Log DOMDocument
	//

	$time = microtime(true) - $time;

	echo "DOMDocument creation took " . $time . " seconds. \n";

	//
	//	Get all of the tables in the page
	//

	$tables = $dom->getElementsByTagName('table');

	//
	//	Announce iteration parse
	//

	echo "Loading objects...\n";
	
	$time = microtime(true) ;

	//
	//	Create a buffer for the courses
	//

	$courses = array();

	//
	//	Get the first item
	//	

	$table = $tables->item(0);

	//
	//	Prepare counters
	//

	$numCourses = 0;
	$numSections = 0;

	//
	//	Hang on to the courses and 
	//	sections in a pair of global
	// 	arrays.
	//
	
	$courses = array();
	$sections = array();
	
	//
	//	Iterate
	//


	while ($table != NULL) {
		
		$table = $tables->item(0);

		if ($table === NULL) {
			break;
		}

		//
		//	We've found a section table, parse it.
		//

		if (elementIsACourseSectionTable($table)) {

			$sections = addSectionsToCourseUsingTable($course, $table, $sections);
			$numSections++;			
		}

		//
		//	Skip the last table if it's not a course section
		//

		else if(elementIsCourseHeaderTable($table)){
			$course = courseFromTable($table);
			$courses[] = $course;
			$numCourses++;
		}

		//
		//	Remove the first item from the list
		//

		$first = $tables->item(0);
		$first->parentNode->removeChild($first);

		//
		//	Get the next table to parse
		//

		$table = $tables->item(0);
	}

	//
	//	Log table parsing
	//

	$time = microtime(true) - $time;

	echo "Loading objects took " . $time . " seconds.\n";	

	//
	//	Print out each course
	//

	echo "Records:\n-----\n";
	echo "There are " . count($courses) . " courses, and " . count($sections) . " sections.\n";	

	$totalTime = microtime(true) - $totalTime;

	//
	//	TODO: Pull records from the database here.
	//

	$storedCourses;

	//
	//	TODO: Compare the loaded to the stored;
	//
	
	//
	//	TODO: Send out alerts to users
	//
	
	//
	//	
	//
	
	echo "Total time: ". $totalTime . " seconds.\n";
?>

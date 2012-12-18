<?php

/*

	power.php

	This file is the component that reads data from CUNY's website
	and saves it to a sqlite database.

*/

	//
	//	Tell PHP to stop mamanging my time.
	//

	set_time_limit(0);

	//
	//	Include some files
	//

	include('./course.php');
	include('./utils.php');

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
	//	Execute the request
	//

	$perf[] = microtime(true) . " - Before cURL" ;

	$response = curl_exec($curl_handle);
	// $response = file_get_contents('./source.html');

	$perf[] =  microtime(true) . " - After cURL";

	//
	//	Clean up malformed HTML
	//

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

	$perf[] =  microtime(true) . " - After Cleanup";

	//
	//	Convert the HTML to UTF-8 for the parser
	//

	$html = $response; 

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
	$tidy->parseString($html, $config, 'utf8');
	$tidy->cleanRepair();

	$html = $tidy;

	$perf[] =  microtime(true) . " - After Tidy, before DOM load." ;

	//
	// Load the HTML into a DOMDocument
	//

	$dom = new DOMDocument();

	$dom->loadHTML($html);


	$perf[] =  microtime(true). " - After DOM - before pulling tables.";

	//
	//	Get all of the tables in the page
	//

	$tables = $dom->getElementsByTagName('table');


	$perf[] =  microtime(true) - " After pulling tables.";

	//
	//	Create a buffer for the courses
	//

	$courses = array();

	//
	//	Iterate
	//

	$numberOfTables = $tables->length;


	$perf[] = microtime(true) . " - Before loop...";

	$parsetime = microtime(true);

	$lastTable;

	while ($tables->length > 0) {
		
		$lastTable = $table;
		$table = $tables->item(0);

		if ($table === NULL) {
			break;
		}

		//
		//	We've found a section table, parse it.
		//
		
		if (elementIsACourseSectionTable($table)) {

			$course = addSectionsToCourseUsingTable($course, $table);			
		}

		//
		//	Skip the last table if it's not a course section
		//

		else if ($tables->length == 1) {
			//do nothing here
		}

		//
		//	Else assume a course section, and do something with it
		//

		else{
			$course = courseFromTable($table);
			$courses[] = $course;
		}

		//
		//	Remove the first item from the list
		//

		$first = $tables->item(0);
		$first->parentNode->removeChild($first);		
	}

	$parsetime = microtime(true) - $parsetime;
	
	$perf[] = microtime(true) . " - After Parse ";

	//
	//	Print out each course
	//

	$perf[] = microtime(true) . " Before printout.";

	foreach ($courses as $course) {
		echo $course->description();
	}	
	
	$perf[] =  microtime(true) . " - After printout.";

	echo "\n\nTimes:\n-----\n";
	foreach ($perf as $log){
		echo $log . "\n";
	}

	echo "Loop took " , $parsetime . " seconds. \n";
	echo "Looped " . $numberOfTables . " records.\n";
	echo "There are " . count($courses) . " courses.\n";
?>

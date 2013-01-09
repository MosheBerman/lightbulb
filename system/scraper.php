<?php

/*

	scraper.php

	This file contains a class responsible for
	scraping and parsing a CUNY course listings.

*/


namespace Lightbulb{

	class Scraper{
	
		public $courses;
		public $sections;
		
		private $curl_handle;
		private $url;
		private $response;

		private $local;
		private $path;
		
		function __construct($url = null, $auto = false){

			//
			//	Set up a URL and configure cURL
			//

			$this->url = $url;
			$this->configure();	

			if($auto == true){
				$this->response = file_get_contents($url);
				$this->cleanUpHTML();
				$this->parseHTML();
			}

		}
		
		//
		//	Configures the cURL request
		//
		
		function configure(){
		
		//
		//	Set up a cURL handle and some POST headers
		//

		$curl_handle = curl_init();

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

		curl_setopt($curl_handle, CURLOPT_URL, $this->url);
		curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT,0);
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl_handle, CURLOPT_POST, count($URL_PARAMS));
		curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $URL_PARAMS);		
		
		$this->curl_handle = $curl_handle;
		}
		
		//
		//	Executes the request
		//
		
		function runCURL(){
			$this->response = curl_exec($this->curl_handle);
		}

		//
		//	Loads a local file - used  for
		//  testing, instead of pulling from 
		//	the live server, we load a
		//	local file directly into the 
		//	response field and use that.
		//

		function loadFile($filename){
			$this->response = readfile($filename);
		}
		
		//
		//	Clean up pulled HTML
		//
		
		function cleanUpHTML(){
		
			//
			//	If there's no HTML, then
			//	return without doing anything.
			//
			
			if(is_null($this->response)){
				echo "WARNING [SCRAPER] : No response data to clean up.\n";
				return;
			}
		
			//	Temporary reference to the response object
			$response = $this->response;
			
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
			//
			//
			
			$config = array(
		           'indent'         => true,
		           'output-xhtml'   => true,
		           'wrap'           => 200);

			//
			// Tidy up the HTML's DOM structure
			//

			$tidy = new \tidy;
			$tidy->parseString($response, $config, 'utf8');
			$tidy->cleanRepair();

			$response = $tidy;
	
			//
			//	Replace the current instance's response 
			//	with new the cleaned up response.
			//
			
			$this->response = $response;
		}
		
		//
		//	Parse into objects
		//
		
		function parseHTML(){
		
			//	Set up the DOM
			$dom = new \DOMDocument();
			$dom->loadHTML($this->response);
		
			//	Grab the tables
			$tables = $dom->getElementsByTagName('table');
			
			//	Create a buffer for the courses
			$courses = array();

			//	Get the first item
			$table = $tables->item(0);

			//	Configure our arrays	
			$this->courses = array();
			$this->sections = array();
	
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
	
					//
					// 	Add section to the sections. Note that we pass
					//	the array by reference.
					//
			
					addSectionsToCourseUsingTable($course, $table, &$this->sections);
				}

				//
				//	Skip the last table if it's not a course section
				//

				else if(elementIsCourseHeaderTable($table)){
					$course = courseFromTable($table);
					$this->courses[$course->name] = $course;
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
		}
		
		
	}
}

?>
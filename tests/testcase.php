<?php

/*
	
	testcase.php

	This file defines a class which acts as
	unit test on the differ class. Each test
	looks for a file with a supplied name

*/

	class TestCase{

		public $filename;

		public $result;

		//
		//	Create a new test, optionally
		//	passing in an html file.
		//

		function __construct($_filename){

			if (!is_null($_filename)) {
				$this->filename = $_filename;
			}
		}

		//
		//	Execute a test, checking for
		//	a given property.
		//

		function testProperty($property){

			$dir = "./pages/";

			$original = new lightbulb\Scraper($dir . "original.html", true);
			$testCase = new lightbulb\Scraper($dir .$this->filename.".html", true);

			$differ = new lightbulb\Differ($original->courses, $testCase->courses);
			$differ->registerChanges();

			$this->result = $differ->{$property};

			return $this->result;
		}

		//
		//	Returns true if the test yielded a result
		//

		function success(){
			return (bool)(count($this->result) > 0);
		}

		//
		//	Tests sending email
		//

		function testMail(){

			$recipients = array('***REDACTED***', '***REDACTED***', '***REDACTED***');
			$mailman = new lightbulb\Mailman("***REDACTED***", "", "Registration Alert", $recipients);
			$mailman->sendMessage("Hello there from the Lightbulb mailman. This is a test email.");
		}
	}

?>
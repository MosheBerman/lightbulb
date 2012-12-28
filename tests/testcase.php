<?php

/*
	
	testcase.php

	This file defines a class which acts as
	unit test on the differ class. Each test
	looks for a file with a supplied name

*/

	class TestCase{

		public $filename;

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

			if (!is_null($_filename)) {
				$this->filename = $_filename;
				die("no filename.\n");
			}

			$original = new lightbulb\Scraper("http://lightbulb.mosheberman.com/pages/original.html", true);
			$testCase = new lightbulb\Scraper("http://lightbulb.mosheberman.com/pages/".$this->filename.".html", true);

			$differ = new lightbulb\Differ($original->courses, $testCase->courses);
			$differ->registerChanges();

			return count($differ->{$property}) > 0;
		}
	}

?>
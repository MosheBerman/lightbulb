<?php

	/*

		tests.php

		This file contains test written to
		test the differ engine for the 
		lightbulb project.

	*/
		
	require_once('../system.php');
	require('testcase.php');

	$tests = array(	'Open Sections' => 'courseSectionsThatHaveOpened',
					'Closed Sections'=> 'courseSectionsThatHaveClosed',
					'New Professors' => 'courseSectionsThatHaveNewProfessors',
					'Removed Professors' => 'courseSectionsThatNoLongerHaveProfessors',
					'New Courses' => 'newCourses',
					'Cancelled Courses' => 'cancelledCourses',
					'New Sections' => 'newCourseSections',
					'Cancelled Sections' =>'cancelledCourseSections',
					'Room Changes' => 'sectionsThatHaveNewRooms'
	 );

	$props = array(	'Open Sections' => 'sectionopen',
					'Closed Sections'=> 'sectionclose',
					'New Professors' => 'professoradd',
					'Removed Professors' => 'professorremove',
					'New Courses' => 'courseadd',
					'Cancelled Courses' => 'courseremove',
					'New Sections' => 'sectionadd',
					'Cancelled Sections' =>'sectionremove',
					'Room Changes' => 'roomchange'


		);
		
	
	//
	//	Loop the tests and execute them
	//

	foreach ($props as $test => $file) {

		$testcase = new TestCase($file);	

		echo 'Testing ' . $test . "..." . "\n";

		$testcase->testProperty($tests[$test]);

		echo "Result: " . $testcase->success() . "\n";


	}






?>
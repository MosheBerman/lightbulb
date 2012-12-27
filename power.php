<?php

/*

	power.php

	This file is the core of the lightbulb system.
	It scrapes course data and sends out emails to
	interested parties. When run at regular intervals,
	it makes for an interesting alert system.

*/
 
	//
	//	Tell PHP to stop managing my time.
	//

	set_time_limit(0);

	//
	//	Include some files
	//

	include('course.php');
	include('utils.php');
	include('mail.php');
	include('timer.php');
	include('differ.php');
	include('scraper.php');
	
	//
	//	Setup timers for tracking operation times.
	//
	
	$globalTimer = new Lightbulb\Timer();
	$timer = new Lightbulb\Timer();

	//
	//	Setup the scraper
	//
	
	$CUNY_URL = "http://student.cuny.edu/cgi-bin/SectionMeeting/SectMeetEval.pl?DB=ORACLE_A&STYLE=NEW&COLLEGECODE=05";
	$scraper = new Lightbulb\Scraper($CUNY_URL);
	
	$globalTimer->start("Lightulb 1.0 alpha");
	
	//
	//	CURL request
	//

	$timer->start("cURL request");
	$scraper->runCURL();
	$timer->stop();

	//
	//	Announce cleanup
	//

	$timer->start("HTML Cleanup");
	$scraper->cleanUpHTML();
	$timer->stop();

	//
	//	Announce DOMDocument
	//

	$timer->start("Load HTML document");
	$scraper->parseHTML();
	$timer->stop();

	//
	//	Pull records from the database here.
	//
		
	$timer->start("Database load");
	
	// A flag denoting if we should bother comparing and alerting 
	$isDatabaseEmpty = false;

	//
	// The connection
	//

	try{

		@$db = new PDO('mysql:host=127.0.0.1;dbname=fluorescent;charset=utf8', '***REDACTED***', '***REDACTED***');
		
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
		//
		//	Prepare the PDO statements
		//
		
		$sectionQuery = $db->query('SELECT * FROM Sections');
		$courseQuery = $db->query('SELECT * FROM Courses ORDER BY name');
		
		//	List the parameters for the database
		$sectionProperties = array('section', 'code', 'openSeats', 'dayAndTime', 'instructor', 'buildingAndRoom', 'isOnline');
		$courseProperties = array('startDate', 'endDate', 'name', 'description', 'credits', 'hours', 'division', 'subject', 'lastUpdated', 'sections');
		
		//
		//	We want to fetch into objects, so let's hook that up.
		//
		
		$sectionQuery->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Section', $sectionProperties);
		
		$courseQuery->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Course', $courseProperties);
		
		//
		//	Perform the Query
		//
		
		$storedSections = $sectionQuery->fetchAll();
		$storedCourses = $courseQuery->fetchAll();
		
		//
		//	Check if the database is empty
		//
		
		if(count($storedSections) == 0 && count($storedCourses) == 0){
			$isDatabaseEmpty = true;
		}
		else{
			$isDatabaseEmpty = false;
			
			installSectionsIntoCourses($storedSections, &$storedCourses); 
		}
		
		//
		//	Verify that it worked
		//
		
		// 	showCourses($storedCourses);		

	}
	
	//
	//	If we can't load up the data, die.
	//
	
	catch(PDOException $e){
		die("Failed to connect to database to retrieve stored courses. \nInfo:" . $e . "\n");
	}

	
	//
	//	Log out database time...
	//
	
	$timer->stop();
	
	//

	$timer->start("Checking changes");
	
	//
	//	Compare the loaded to the stored;
	//	Track the desired sections in a
	//	separate data object.
	//
	
	$differ = new lightbulb\Differ($storedCourses, $scraper->courses);	
	
	if($isDatabaseEmpty == false){	
		
		$differ->registerChanges();
		
		if($differ->sectionsHaveOpened()){
			echo "The following sections have opened:\n";
			showSections($differ->courseSectionsThatHaveOpened);
		}
		
		if($differ->sectionsHaveClosed()){
			echo "The following sections have closed:\n";
			showSections($differ->courseSectionsThatHaveClosed);		
		}
		
		if($differ->sectionsHaveNewProfessors()){
			echo "The following sections have new professors:\n";
			showSections($differ->courseSectionsThatHaveNewProfessors);
		}
		
		if($differ->sectionsLostProfessors()){
			echo "The following sections have new professors:\n";
			showSections($differ->courseSectionsThatNoLongerHaveProfessors);
		}
		
		if($differ->hasNewCourses()){
			echo "These courses are new:\n";
			showCourses($differ->newCourses);
		}
		
		if($differ->hasCancelledCourses()){
			echo "These courses have been cancelled:\n";
			showCourses($differ->cancelledCourses);
		}
		
		if($differ->hasNewSections()){
			echo "The sections are new:\n";
			
		}
	}
	
	//
	//
	//
	
	$timer->stop();
	
	$timer->start("Refreshing Database");
	
	//
	//	Store the new data in the database.
	//
	
	//Empty old database
	$deleteCoursesStatement = $db->prepare("TRUNCATE Courses");
	$deleteSectionsStatement = $db->prepare("TRUNCATE Sections");
	
	$deleteCoursesStatement->execute();
	$deleteSectionsStatement->execute();
		
	//	Repopulate
	foreach($scraper->courses as $course){
		
		$sql = $course->SQLStatement();
		$preparedStatement = $db->prepare($sql);
		
		try{
			$preparedStatement->execute();
		}
		catch(PDOException $e){
				echo "Failed to insert course: " . $course->description();
				echo "\n";			
		}
		
		$lastID = $db->lastInsertID();
					
		foreach($course->sections as $section){
			
			$sql = $section->SQLStatement($lastID);
			$preparedStatement = $db->prepare($sql);
		
			try{
				$preparedStatement->execute();
			}
			catch(PDOException $e){
				echo "Failed to insert section : " . $section->description();
				echo "\n";
			}
		}
	}
	
	//
	//
	//
	
	$timer->stop();
	
	$timer->start("Alerting Users");
	
	//
	//	Send out alerts to users
	//
	
	if($isDatabaseEmpty == false){
		
		//
		//	TODO: 	Pull out users that want alerts
		//			and send appropriate alerts. 
		//
	
	}

	$timer->stop();
	
	//
	//	Finally print total time
	//
	
	$globalTimer->stop();
?>

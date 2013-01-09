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

	require_once('system/system.php');
	
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
	
	$globalTimer->start("Lightulb version 1.0a");
	
	//
	//	CURL request
	//

	$timer->start("requesting page via cURL");
	$scraper->runCURL();
	$timer->stop();

	//
	//	Announce cleanup
	//

	$timer->start("cleaning HTML");
	$scraper->cleanUpHTML();
	$timer->stop();

	//
	//	Announce DOMDocument
	//

	$timer->start("Loading HTML into DOM");
	$scraper->parseHTML();
	$timer->stop();

	//
	//	Pull records from the database here.
	//
		
	$timer->start("loading database");

	//
	// The connection
	//

	$CONNECTION_STRING = 'mysql:host=127.0.0.1;dbname=fluorescent;charset=utf8';
	$username = '***REDACTED***';
	$password = '***REDACTED***';
	$serializer = new Lightbulb\Serializer($CONNECTION_STRING, $username, $password);	

	$failed = $serializer->hasFailed();
	
	if($failed){
		$timer->stop();
		die("Failed to deserialize. Quitting.\n");
	}

	//
	//	Log out database time...
	//
	
	$timer->stop();

	$timer->start("checking changes");
	
	//
	//	Compare the loaded to the stored;
	//	Track the desired sections in a
	//	separate data object.
	//
	
	$differ = new lightbulb\Differ($serializer->getCourses(), $scraper->courses);	
	
	if($serializer->hasFailed() == false){	
		
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

	
	$timer->start("Alerting Users");
	
	//
	//	Send out alerts to users
	//
		
	$alerter = new lightbulb\Alerter($differ, array());

	if($alerter){
		$alerter->alert();
	}

	$timer->stop();
	
	$timer->start("Refreshing Database");
	
	//
	//	Store the new data in the database.
	//
	
	$serializer->serialize($scraper->courses);
	
	//
	//
	//
	
	$timer->stop();
	
	//
	//	Finally print total time
	//
	
	$globalTimer->stop();
?>

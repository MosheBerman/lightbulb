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
	//	Check for a master kill switch
	//

	if (Switches::$CRON_ENABLED == false) {
		die("Cron is disabled.");
	}	

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
	
	$globalTimer->start(Meta::fullAppName());
	
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
			echo "\n\nThe following sections have opened:\n";
			showSections($differ->courseSectionsThatHaveOpened);
		}
		
		if($differ->sectionsHaveClosed()){
			echo "\n\nThe following sections have closed:\n";
			showSections($differ->courseSectionsThatHaveClosed);		
		}
		
		if($differ->sectionsHaveNewProfessors()){
			echo "\n\nThe following sections have new professors:\n";
			showSections($differ->courseSectionsThatHaveNewProfessors);
		}
		
		if($differ->sectionsLostProfessors()){
			echo "\n\nThe following sections' professors were removed:\n";
			showSections($differ->courseSectionsThatNoLongerHaveProfessors);
		}
		
		if($differ->hasNewCourses()){
			echo "\n\nThese courses are new:\n";
			showCourses($differ->newCourses);
		}
		
		if($differ->hasCancelledCourses()){
			echo "\n\nThese courses have been cancelled:\n";
			showCourses($differ->cancelledCourses);
		}
		
		if($differ->hasNewSections()){
			echo "\n\nThese sections are new:\n";
			showSections($differ->newCourseSections);
		}

		if ($differ->hasCancelledSections()) {
			echo "\n\nThese sections were cancelled:\n";
			showSections($differ->cancelledCourseSections);
		}
	}
	
	//
	//	Stop the timer
	//
	
	$timer->stop();

	//
	//	Check for permission to alert users
	//

	if (Switches::$ALERTS_ENABLED == true && $differ->hasChanges()) {

		$timer->start("Alerting Users");
		
		//
		//	Send out alerts to users
		//
			
		$alerter = new TwilioSender();

		$userManager = new Lightbulb\UserManager();

		$me = $userManager->userForName("moshe");

		$people = array($me);

		//$people = $userManager->allUsers();

		$alerter->messagePeople($people, "Lightbulb found an update. Log on to check if your sections opened.");

		$timer->stop();
		
	}
	else if(Switches::$ALERTS_ENABLED == true && !$differ->hasChanges()){
		echo "Alerting is enabled but there's no changes.\n";
	}	
	else{
		echo "Alerting is disabled in switches.php.\n";
	}

	//
	//	Update the database.
	//


	$timer->start("Refreshing Database");
		
	//
	//	Store the new data in the database.
	//
	
	if(!$serializer->hasFailed()){
		$serializer->serialize($scraper->courses);
	}

	//
	//
	//
		
	$timer->stop();

	//
	//	Finally print total time
	//
	
	$globalTimer->stop();
?>

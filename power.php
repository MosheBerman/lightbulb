<?php

/*

	power.php

	This file is the component that reads data from CUNY's website
	and saves it to a sqlite database.

*/

	$curl_handle = curl_init();

	$CUNY_URL = "http://student.cuny.edu/cgi-bin/SectionMeeting/SectMeetEval.pl?DB=ORACLE_A&STYLE=NEW&COLLEGECODE=05";

	//
	//	Configure CURL
	//

	curl_setopt($curl_handle, CURLOPT_URL, $CUNY_URL);
	curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);

	//
	//	Execute the request
	//

	$page = curl_exec($curl_handle);

	//
	//	Print out the results
	//

	echo $page ? $page : "Failed to load";
?>
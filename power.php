<!DOCTYPE html>
<html>
<head>
	</head>
	<body>
<?php

/*

	power.php

	This file is the component that reads data from CUNY's website
	and saves it to a sqlite database.

*/

	$curl_handle = curl_init();

	$CUNY_URL = "http://student.cuny.edu/cgi-bin/SectionMeeting/SectMeetEval.pl?DB=ORACLE_A&STYLE=NEW&COLLEGECODE=05";

	$URL_PARAMS = array('MAXCOURSE' => urlencode("037|64|MUSC.  ,119"),
	  'COURSECNT' => urlencode(1507),
	  'PREFIX' => urlencode("title"),
	  'NUMBER'  => urlencode("ANY"), 
	  'SUBJECT' => urlencode("Select All"),
	  'COLLEGE'  => urlencode("05"),
	  'DIVISION' => urlencode("UG"), 
	  'DUD'  => urlencode("1"),
	  'COMPLETE' => urlencode("1"),
	  'STYLE' => urlencode("NEW"),
      'DB' => urlencode("ORACLE_A"),
	  'DBSTATUS' => urlencode("ORACLE_A* : from database, A_DateTime is '2012/12/15 18:30:00', B_DateTime is '2012/12/15 17:00:00'"),
	  'ASOF' => urlencode("<FONT class='orange'>UPDATED</FONT> <FONT class='gray'>12/15/2012,  6:30:00 PM</FONT>'"),
	  'DBSTATORIG' => urlencode("ORACLE_A* : from database, A_DateTime is '2012/12/15 18:30:00', B_DateTime is '2012/12/15 17:00:00'"), 
	  'ASOFORIG' => urlencode("<FONT class='orange'>UPDATED</FONT> <FONT class='gray'>12/15/2012,  6:30:00 PM</FONT>"), 
	  'sessions' => urlencode("BOTH"),
	  'only_open' => urlencode("ALL"),
	  'sacsed' => urlencode("YES"),
	  'oso' => urlencode("ALL"),
	  'TERM' => urlencode("201302")

	  );

	//
	//	Configure CURL
	//

	curl_setopt($curl_handle, CURLOPT_URL, $CUNY_URL);
	curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT,2);
	curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($curl_handle, CURLOPT_POST, count($URL_PARAMS));
	curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $URL_PARAMS);

	//
	//	Execute the request
	//

	$response = curl_exec($curl_handle);

	//
	//	Convert the HTML to UTF-8 for the parser
	//

	$html = @mb_convert_encoding($response, 'HTML-ENTITIES', 'utf-8'); 

	//
	//	Parse out the HTML
	//

	$dom = new DOMDocument;

	$dom->loadHTML($html);

	//
	//	Print out the results
	//

	echo $dom ? $dom : "Couldn't read database";
?>
</body>
</html>

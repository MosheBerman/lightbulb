<?php

/*

	api.php

	This file contains endpoints for remote access 
	to the data.

*/

	require("system.php");

	use \PDO as PDO;

	/**
	* 
	*/
	class API
	{
		
		private $courses;
		private $sections;
		private $isDatabaseEmpty;

		function __construct()
		{

		}

		//
		//	Returns a connection
		//

		function connection(){

			$CONNECTION_STRING = 'mysql:host=127.0.0.1;dbname=fluorescent;charset=utf8';
			$username = '***REDACTED***';
			$password = '***REDACTED***';

			$connection = new PDO($CONNECTION_STRING, $username, $password);

			return $connection;
		}

		//
		//	Loads the class info out of the database
		//

		function allCourses(){

			$CONNECTION_STRING = 'mysql:host=127.0.0.1;dbname=fluorescent;charset=utf8';
			$username = '***REDACTED***';
			$password = '***REDACTED***';
			$serializer = new Lightbulb\Serializer($CONNECTION_STRING, $username, $password);	

			$serializer->deserialize();

			return $serializer->getCourses();
		}
	}


?>
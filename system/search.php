<?php

/*
	search.php

	This file contains search logic - for 
	searching the lightbulb database for 
	courses, professors, or sections.
*/

namespace lightbulb{

	class Search{


		//
		//
		//

		function __construct(){

		}

		//
		//	Allow users to search by section code/name
		//

		function searchForSection($term=null, $json_encode = true){

			if ($term == null) {

			}


		}

		//
		//	Allow users to search by course name/description
		//

		function searchForCourse($term=null, $json_encode = true){

		}

		//
		//	Allow users to search by professor
		//

		function searchForProfessor($term=null, $json_encode = true){

		}
	}


}

?>
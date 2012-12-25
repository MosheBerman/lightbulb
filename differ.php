<?php

/*

differ.php

	This file defines an object that tracks
	differences between two sets of courses 
	and their sections.


*/

namespace lightbulb{

	class Differ{
		
		private $oldData;
		private $newData;
		
		private $courseSectionsThatHaveOpened;
		private $courseSectionsThatHaveClosed;
	
		private $courseSectionsThatHaveNewProfessors;
		private $coursesThatNoLongerHaveProfessors;
	
		private $newCourses;
		private $cancelledCourses;
		
		private $newCourseSections;
		private $cancelledCourseSections;
		
		private $sectionsThatHaveNewRooms;
		
		function Differ(){
		
		}
		
		
		
	}
}

?>
<?php

/*

differ.php

	This file defines an object that tracks
	differences between two sets of courses 
	and their sections.


*/
namespace Lightbulb{
	class Differ{
		
		private $oldData;
		private $newData;
		
		public $courseSectionsThatHaveOpened;
		public $courseSectionsThatHaveClosed;
	
		public $courseSectionsThatHaveNewProfessors;
		public $courseSectionsThatNoLongerHaveProfessors;
	
		public $newCourses;
		public $cancelledCourses;
		
		public $newCourseSections;
		public $cancelledCourseSections;
		
		public $sectionsThatHaveNewRooms;
		
		//
		//	Constructor
		//
		
		function __construct($_oldData = array(), $_newData = array()){			
			
			if(count($_oldData) == 0){
				echo "WARNING [DIFFER]: No old data to diff.\n";
			}		
			
			if(count($_newData) == 0){
				echo "WARNING [DIFFER]: No new data to diff.\n";
			}					
			
			$this->oldData = $_oldData;
			$this->newData = $_newData;	
			
			//
			//	Initialize the arrays
			//	and register changes.
			//
			
			$this->registerChanges();
		}
		
		//
		//	Empty out the arrays
		//
		
		function reinitializeArrays(){
		
			$this->courseSectionsThatHaveOpened = array();
			$this->courseSectionsThatHaveClosed = array();
			
			$this->courseSectionsThatHaveNewProfessors = array();
			$this->courseSectionsThatNoLongerHaveProfessors = array();
			
			$this->newCourses = array();
			$this->cancelledCourses = array();
			
			$this->newCourseSections = array();				// SNR in-flight entertainment
			$this->cancelledCourseSections = array();
			
			$this->sectionsThatHaveNewRooms = array();		
		}
		
		//
		//	Runs the functions
		//
		
		function registerChanges(){
			
			if(is_null($this->oldData) || is_null($this->newData)){
			
				echo "One or both of the datasets is null.\n";
			
				return;			
			}
			
			$this->reinitializeArrays();
			
			$this->findSectionChanges();
			$this->findCourseChanges();
		}
		
		//
		//	Compares the sections that have 
		//
		
		function findSectionChanges(){
			
			//
			//	Outer two loops iterates the courses
			//
			//	Inner two loop iterates the sections
			//
		
			$nd = $this->newData;
			$od = $this->oldData;		
			
			foreach($nd as $new){
				foreach($od as $old){
					
					foreach($new->sections as $newSection){
						foreach($old->sections as $oldSection){
							
							//
							//	If we have the same section code, 
							//	compare against the matching section 
							//	for changes.
							//
							
							if($oldSection->code == $newSection->code){
								
								//	Check for newly opened sections
								if($oldSection->isClosed() && $newSection->isOpen()){
									$this->courseSectionsThatHaveOpened[$newSection->code] = $newSection;
								}
								
								// Check for newly closed sections
								if($oldSection->isOpen() && $newSection->isClosed()){
									$this->courseSectionsThatHaveClosed[$newSection->code] = $newSection;
								}
								
								//	Check for room changes
								if($oldSection->buildingAndRoom != $newSection->buildingAndRoom){
									$this->sectionsThatHaveNewRooms[$newSection->code] = $newSection;
								}
								
								//	Check for unlisted professors
								if($oldSection->hasProfessor() && !$newSection->hasProfessor()){
									$this->courseSectionsThatNoLongerHaveProfessors[$newSection->code] = $newSection;
								}
								
								//	Check for newly listed professors
								if(!$oldSection->hasProfessor() && $newSection->hasProfessor()){
									$this->courseSectionsThatHaveNewProfessors[$newSection->code] = $newSection;
								}
							}
						}
					}
				}
			}
			
		}
		
		//
		//	Finds changes in the courses
		//
		
		function findCourseChanges(){
		
			//
			//	First scan existing data...
			//
			
			foreach($this->oldData as $oldCourse){
				
				//	Find the matching new course
				$newCourse = $this->courseFromSetMatchingCourse($this->newData, $oldCourse);
				
				//	If the new course is null, it's been cancelled
				if($newCourse == null){
					$this->cancelledCourses[$oldCourse->name] = $oldCourse;
				}
				
				else if($newCourse != null){
					
					$numOfSectionsForOldCourse = count($oldCourse->sections);
					$numOfSectionsForNewCourse = count($newCourse->sections);
					
					//	If the number of sections aren't matching, 
					//	we should see which ones have changed.
					if($numOfSectionsForOldCourse != $numOfSectionsForNewCourse){

						//
						//	Check for section creation/cancellation
						//
			
						$addedSections = $this->sectionsAddedToCourse($oldCourse, $newCourse);
						$removedSections = $this->sectionsRemovedFromCourse($oldCourse, $newCourse);
	

						$this->newCourseSections = array_merge($this->newCourseSections, $addedSections);
						$this->cancelledCourseSections = array_merge($this->cancelledCourseSections, $removedSections);
													
					}
				}
				
			
			}
			
			//
			//	Check for new courses
			//
	
			foreach($this->newData as $newCourse){
				if($this->courseFromSetMatchingCourse($this->oldData, $newCourse) == null){
					$this->newCourses[] = $newCourse;
				}
			}

		}
		
		//
		//	Find a course from a set matching a given course.
		//	Matches are determined by name.
		//
		
		function courseFromSetMatchingCourse($courses, $courseToMatch){
			foreach($courses as $course){
				if($course->name == $courseToMatch->name){
					return $course;
				}
			}
			return null;
		}
		
		//
		//	Track sections that were removed
		//
		
		function sectionsRemovedFromCourse($oldCourse, $newCourse){
		
			// Track the removed sections
			$removedSections = array();

			//Ensure we have two valid courses
			if(is_null($newCourse) || is_null($oldCourse)){
				return $removedSections;
			}
		
			
			//	for each old section
			foreach($oldCourse->sections as $oldSection){
				
				//	Assume the section was removed
				$sectionWasRemoved = true;
				
				//	Look for a matching section,
				//	if we find it, the section wasn't 
				//	removed. Otherwise, it was.
				foreach($newCourse->sections as $newSection){
					if($oldSection->code == $newSection->code){
						$sectionWasRemoved = false;
					}
				}
				
				//	If a section was removed, we want 
				//	to know about it.
				
				if($sectionWasRemoved == true){
					$removedSections[$oldSection->code] = $oldSection;
				}
				
			}
			
			return $removedSections;
		}
		
		//
		//	Track sections that were added
		//
		
		function sectionsAddedToCourse($oldCourse, $newCourse){
			
			//Ensure we have two valid courses
			if(is_null($newCourse) || is_null($oldCourse)){
				return array();
			}
		
			// Track the removed sections
			$addedSections = array();
			
			//	for each old section
			foreach($newCourse->sections as $newSection){
				
				//	Assume the section was removed
				$sectionWasAdded = true;
				
				//	Look for a matching section,
				//	if we find it, the section wasn't 
				//	added. Otherwise, it was.
				foreach($oldCourse->sections as $oldSection){
					if($oldSection->code == $newSection->code){
						$sectionWasAdded = false;
					}
				}
				
				//	If a section was added, we want 
				//	to know about it.
				
				if($sectionWasAdded == true){
					$addedSections[$newSection->code] = $newSection;
				}
			}

			return $addedSections;		
		}
		
		/* Convenience check methods */
		
		//
		//	Returns true if there are opened sections
		//
		
		function sectionsHaveOpened(){
			return count($this->courseSectionsThatHaveOpened) > 0;
		}
		
		//
		//	Returns true if sections have closed
		//
		
		function sectionsHaveClosed(){
			return count($this->courseSectionsThatHaveClosed) > 0;
		}
		
		//
		//	Returns true if there are new professors
		//
		
		function sectionsHaveNewProfessors(){
			return count($this->courseSectionsThatHaveNewProfessors) > 0;
		}
		
		//
		//	Return true if professors have changed to STAFF
		//
		
		function sectionsLostProfessors(){
			return count($this->courseSectionsThatNoLongerHaveProfessors) > 0;			
		}
		
		//
		//	Returns true if there are new courses
		//
		
		function hasNewCourses(){
			return count($this->newCourses) > 0;			
		}
		
		//
		//	Returns true if there are cancelled courses
		//
		
		function hasCancelledCourses(){
			return count($this->cancelledCourses) > 0;			
		}
		
		//
		//	Returns true if there are new sections
		//
		
		function hasNewSections(){
			return count($this->newCourseSections) > 0;			
		}		
		
		//
		//	Returns true if there are cancelled sections
		//
		
		function hasCancelledSections(){
			return count($this->cancelledCourseSections) > 0;			
		}			
		
		//
		//	Returns true if there are room changes
		//
		
		function hasRoomChanges(){
			return count($this->sectionsThatHaveNewRooms) > 0;			
		}

		//
		//	Returns true if there are any section changes
		//

		function hasSectionChanges(){

			return $this->sectionsHaveOpened() 
			|| $this->sectionsHaveClosed() 
			|| $this->sectionsHaveNewProfessors()
			|| $this->sectionsLostProfessors()
			|| $this->hasNewSections()
			|| $this->hasCancelledSections()
			|| $this->hasRoomChanges();

		}

		function hasCourseChanges(){
			return $this->hasNewCourses()
			|| $this->hasCancelledCourses();
		}

		//
		//	Returns true if there are any changes
		//		

		function hasChanges(){
			return  $this->hasSectionChanges() || $this->hasCourseChanges();
		}
	}
}
?>
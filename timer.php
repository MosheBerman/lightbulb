<?php

/*

	timer.php

	This file contains some convenience 
	methods for logging and calculating time
	that various operations take.

*/

namespace Lightbulb{
	class Timer{
	
		private $timeInSeconds;
		private $operationName;
	
		function Time(){
			$this->timeInSeconds = 0;
		}
	
		function start($operation){
			$this->operationName = $operation;
			$this->timeInSeconds = microtime(true);
		
			echo "Start " . $this->operationName . "...\n";
		}
	
		function stop(){
			$this->timeInSeconds = microtime(true) - $this->timeInSeconds;
			echo ucfirst($this->operationName) . " took " . $this->timeInSeconds . " seconds.\n";
		}
	}	
}


?>
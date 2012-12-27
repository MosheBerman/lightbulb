<?php



namespace Lightbulb{

	include('Mail.php');

	class Mailman{
	
		private $to;
	
		private $message;
		
		private $subject;
		
		private $from;
		
		function __construct($from = "", $message = "", $subject = "", $to = array()){
			setFrom($from);
			setMessage($message);
			setSubject($subject);
			setTo($to);
		}

		/* Send message */

		function sendMessage($message){

		}
		
		/* Getters and setters */
		
		//
		//	Email message
		//
		
		function setMessage($_message){
			
			if(is_null($_message)){
				return;
			}
			
			$this->message = $_message;
			
		}
		
		function getMessage(){
			return $this->message;
		}
		
		//
		//	Subject
		//	
		
		function setSubject($_subject){
			if(is_null($_subject)){
				return;
			}
			$this->subject = $_subject;
		}
		
		function getSubject(){
			return $this->subject;
		}
		
		//
		//	From field
		//
		
		function setFrom($_from){
			if(is_null($_from)){
				return;
			}
			
			$this->from = $_from;
		}
		
		function getFrom(){
			return $this->from;
		}
		
		//
		//	To
		//
		
		function setTo($_to){
			if(is_null($_to)){
				$this->to = $_to;
			}
			
			$this->to = $_to;
		}
		
		function getTo(){
			return $this->to;
		}
		
		
	
	}
}

?>
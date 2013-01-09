<?php



namespace Lightbulb{

	class Mailman{
	
		private $to;
	
		private $message;
		
		private $subject;
		
		private $from;
		
		function __construct($from = "", $message = "", $subject = "", $to = array()){
			$this->setFrom($from);
			$this->setMessage($message);
			$this->setSubject($subject);
			$this->setTo($to);
		}

		/* Send message */

		function sendMessage($message){
			$headers = 'From: ' . $this->getFrom() . "\r\n";
			$headers .= 'Reply-To: ' . $this->getFrom() . "\r\n";
			$headers .= 'X-Mailer: Lightbulb 1.0';

			$toString = "";

			foreach($this->getTo() as $address){
				
				if($toString != ""){
					$toString .= ", ";
				}

				$toString .=  $address;
			}

			mail($toString, $this->getSubject(), $message, $headers);
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
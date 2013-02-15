<?php

/* 

	Twilio.php

	This file contains basic SMS code for Twilio 
	integration wiuth the lightbulb system

	*/

	require_once('system.php');

    class TwilioSender{

    	private $AccountSid;
    	private $AuthToken;
    	private $client;

    	function __construct(){
    		$this->AccountSid = "***REDACTED***";
    		$this->AuthToken = "***REDACTED***";
    		$this->client = new Services_Twilio($this->AccountSid, $this->AuthToken);
    	}

    	function messagePeople($people, $message){
       		foreach ($people as $user) {
 
        		$sms = $this->client->account->sms_messages->create(
 
	        	// Step 6: Change the 'From' number below to be a valid Twilio number 
        		// that you've purchased, or the (deprecated) Sandbox number
    	        "***REDACTED***", 
 
        	    // the number we are sending to - Any phone number
            	$user->number,
 
            	// the sms body
            	$message
        		);

    		}
 	   }
	}
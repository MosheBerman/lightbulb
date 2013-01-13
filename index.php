<?php

/*

	index.php
	
	This is the homepage for the lightbulb website.

*/

require('../private/system/system.php');

if (Switches::$HUMBLE_HOME) {
	die('quack');
}

$user_manager = new Lightbulb\UserManager;
$UI_manager = new Lightbulb\UIManager;

//
//	If there's an action, handle it
//	

if (isset($_REQUEST['action'])) {

	$action = $_REQUEST['action'];

	//
	//	Read out the request
	//

	$username = isset($_REQUEST['username']) ? trim(strtolower($_REQUEST['username'])) : "";
	$password = isset($_REQUEST['password']) ? $_REQUEST['password'] : "";
	$confirm = isset($_REQUEST['confirm']) ? $_REQUEST['confirm'] : "";
	$phone = isset($_REQUEST['phone']) ? $_REQUEST['phone'] : "";

	//	clean up the phone number
	$phone = preg_replace("/[^0-9]/", "", $phone);

	//
	//	Perform a login
	//

	if($action == 'login'){

		 if (!ctype_alnum($username)) {
		 		$UI_manager->loginPage('Invalid username.');
		 }

		$success = $user_manager->login($username, $password);

		if ($success == false) {
			$UI_manager->loginPage('Login failed.');
		}
	}

	//
	//	Perform a logout
	//

	else if($action == 'logout'){
		$user_manager->logout();
	}

	//
	//	Show a signup form
	//

	else if($action == 'registration'){
		$UI_manager->signupForm();


	}

	//
	//	Show a login form
	//

	else if($action == 'showlogin'){
		$UI_manager->loginPage();
	}

	//
	//	Show an FAQ page
	//

	else if($action == 'faq'){
		if(Switches::$FAQ == false) {
			$UI_manager->noticePage("FAQ is disabled.");
		}
		else{
			$UI_manager->faq();
		}
	}

	//
	//	Register the user 
	//

	else if($action == 'register'){

		//
		//	Handle if there's no username
		//

		if($username == "" || !isset($username)){
			$UI_manager->signupForm("You need to pick a username.",$_REQUEST);	
		}

		//
		//	Handle symbols etc.
		//

		else if (!ctype_alnum($username)) {
			$UI_manager->signupForm("Usernames can only have letters and numbers.",$_REQUEST);	
		}

		//
		//	Handle a whitespace'd username
		//

		else if (preg_match('/\s/',$username)) {
			$UI_manager->signupForm("You can't put spaces in a username.",$_REQUEST);	
		}

		//
		//	Handle an empty phone number
		//

		else if($phone == "" || !isset($phone)){
			$UI_manager->signupForm("Enter your ten digit phone number so you can get alerts.", $_REQUEST);
		}

		//
		//	Handle invalid phone numbers
		//

		else if(strlen($phone) < 10 || strlen($phone) > 10){
			$UI_manager->signupForm("Please enter a ten digit phone number that can recieve texts.", $_REQUEST);
		}

		//
		//	Handle the case of an existing username
		//

		else if ($user_manager->usernameExists($username)) {
			$UI_manager->signupForm("That username exists.", $_REQUEST);
		}

		//
		//	Handle the case of an empty password
		//

		else if($password == "" || !isset($password)){
			$UI_manager->signupForm("You gotta pick a password.", $_REQUEST);
		}

		//
		//	Handle a blank confirmation
		//

		else if($confirm == "" || !isset($confirm)){
			$UI_manager->signupForm("You gotta confirm your password.", $_REQUEST);			
		}


		//
		//	Handle mismatched passwords
		//

		else if($password != $confirm){
			$UI_manager->signupForm("Those passwords don't match.", $_REQUEST);
		}

		//
		//	Otherwise, attempt to sign up
		//


		else {
			
			$success = $user_manager->createUser($username, $password, $phone);

			if($success){
				$UI_manager->loginPage("User successfully created.");			
			}
			else{
				$UI_manager->signupForm("User creation failed, please try again.");				
			}
		}
	}

	//
	//	We've finished whatever action it is, so exit.
	//

	// exit(); 
}

$UI_manager->homepage();	

?>

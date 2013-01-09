<?php

/*

	index.php
	
	This is the homepage for the lightbulb website.

*/

require('../private/system/system.php');

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

	$username = isset($_REQUEST['username']) ? strtolower($_REQUEST['username']) : "";
	$password = isset($_REQUEST['password']) ? $_REQUEST['password'] : "";
	$confirm = isset($_REQUEST['confirm']) ? $_REQUEST['confirm'] : "";

	//
	//	Perform a login
	//

	if($action == 'login'){

		 if (!ctype_alnum($username)) {
		 		$UI_manager->loginForm('Invalid username.');
		 }

		$success = $user_manager->login($username, $password);

		if ($success == false) {
			$UI_manager->loginForm('Login failed.');
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
		$UI_manager->loginForm();
	}

	//
	//	Register the user 
	//

	else if($action == 'register'){

		//
		//	Handle if there's no username
		//

		if($username == "" || !isset($username)){
			$UI_manager->signupForm("You need to pick a username.");	
		}

		//
		//	Handle symbols etc.
		//

		else if (!ctype_alnum($username)) {
			$UI_manager->signupForm("Usernames can only have letters and numbers.");	
		}

		//
		//	Handle a whitespace'd username
		//

		else if (preg_match('/\s/',$username)) {
			$UI_manager->signupForm("You can't put spaces in a username.");	
		}

		//
		//	Handle the case of an existing username
		//

		else if ($user_manager->usernameExists($username)) {
			$UI_manager->signupForm("That username exists.");
		}

		//
		//	Handle the case of an empty password
		//

		else if($password == "" || !isset($password)){
			$UI_manager->signupForm("You gotta pick a password.");
		}

		//
		//
		//

		else if($confirm == "" || !isset($confirm)){
			$UI_manager->signupForm("You gotta confirm your password.");			
		}


		//
		//	Handle mismatched passwords
		//

		else if($password != $confirm){
			$UI_manager->signupForm("Those passwords don't match.");
		}

		else {
			
			$success = $user_manager->createUser($username, $password);

			if($success){
				$UI_manager->loginForm("User successfully created.");			
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

//
//	If the user is logged out show a login form.
//

if($user_manager->isLoggedOut()){
	$UI_manager->loginForm();
}

//
//	We're logged in, so show an appropriate page
//

else{
	$UI_manager->homepage();	
}
exit();


?>

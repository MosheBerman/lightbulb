<?php

/*

	uimanager.php

	The UIManager class contained in this
	file makes certain UI really easy to create.
*/

namespace lightbulb{

	include_once("system.php");

	use \Meta as Meta;
	use \Switches as Switches;	

	class UIManager{

		private $tag_stack;
		private $user_manager;

		function __construct(){
			$this->tag_stack = array();
			$this->user_manager = new UserManager();
		}

		//
		//	This function prints a tag and 
		//	its given attributes.
		//

		function openTag($tag=null, $attributes=null){

			if ($tag == null) {
				return;
			}

			$this->tag_stack[] = $tag;
			echo "<" . $tag . "";

			if ($attributes) {
				foreach ($attributes as $attribute => $value) {
					echo ' '. $attribute . '="' . $value . '"';
				}
			}

			echo ">\n";
		}

		//
		//	Close the most recently open tag
		//

		function closeLastOpenedTag(){
				$tag = array_pop(&$this->tag_stack);
				echo "</" . $tag . ">\n";
		}

		//
		//	Closes all open tags
		//

		function closeOpenTags(){

			while(count($this->tag_stack) > 0) {
				echo "</" . array_pop(&$this->tag_stack) . ">\n";
				
			}

		}

		//
		//
		//

		function header(){
			$this->openTag("html");
			$this->openTag("head");			
			$this->openTag("title");
			echo $this->title();
			$this->closeLastOpenedTag();

			$this->openTag("meta", array("name"=>"viewport", "content"=>"width=device-width, initial-scale=1, maximum-scale=1"));
			$this->closeLastOpenedTag();

			$this->openTag("style", array("type" => "text/css"));
			echo "@import 'style.css';\n";
			echo "@import 'color.css';\n";			
			$this->closeLastOpenedTag();
			$this->closeLastOpenedTag();
		}

		//
		//	This function prints a login form
		//

		function loginForm($message = null){

			//
			//	Check for a master kill of login
			//

			if (Switches::$LOG_IN_ENABLED == false) {
				$this->noticePage('Login is disabled.');
			}

			//
			//	Otherwise, proceed to log in
			//

			$this->header();
			$this->openTag("body");
			$this->topBanner();
			$this->openTag("div", array("id" => "wrapper"));

			if ($message) {
				$this->notice($message);
			}

			$this->openTag("form", array("method"=>"post", "action" => "/index.php"));

			$this->openTag("label", array("class" => "formLabel"));
			echo "Enter your username:";
			$this->closeLastOpenedTag();

			$this->openTag("input", array("type"=>"text", "id" => "username", "name" => "username"));
			$this->closeLastOpenedTag();
			
			$this->openTag("label", array("class" => "formLabel"));
			echo "Enter your password:";
			$this->closeLastOpenedTag();

			$this->openTag("input", array("type"=>"password", "id" => "password", "name" => "password"));			
			$this->closeLastOpenedTag();			
			
			$this->openTag("input", array("type"=> "hidden", "name"=> "action", "value" => "login"));
			$this->closeLastOpenedTag();							
			
			$this->openTag("input", array("type"=> "submit", "name" => "submit",  "id" =>"submit"));
			$this->closeLastOpenedTag();			

			$this->closeOpenTags();

			exit();			
		}

		//
		//	Register
		//

		function signupForm($error = null){

			//
			//	Check if there's a lock on signups
			//

			if (Switches::$NEW_SIGN_UPS_ENABLED == false) {
				$this->noticePage('Signup is disabled.');
			}

			//
			//	Otherwise, proceed to print a signup form
			//

			$this->header();
			
			$this->openTag("body");
			$this->topBanner();			
			$this->openTag("div", array("id" => "wrapper"));

			// Show an error message if appropriate
			if($error){
				$this->notice($error);
			}

			$this->openTag("form", array("method"=>"post"));
			
			$this->openTag("label", array("class" => "formLabel"));
			echo "Choose a username:";
			$this->closeLastOpenedTag();

			$this->openTag("input", array("type"=>"text", "id" => "username", "name" => "username"));
			$this->closeLastOpenedTag();

			$this->openTag("label", array("class" => "formLabel"));
			echo "Choose a password:";
			$this->closeLastOpenedTag();			
			$this->openTag("input", array("type"=>"password", "id" => "password", "name" => "password"));			
			$this->closeLastOpenedTag();	

			$this->openTag("label", array("class" => "formLabel"));
			echo "Confirm your password:";
			$this->closeLastOpenedTag();

			$this->openTag("input", array("type"=>"password", "id" => "confirm", "name" => "confirm"));			
			$this->closeLastOpenedTag();		

			$this->openTag("input", array("type"=> "hidden", "name"=> "action", "value" => "register"));
			$this->closeLastOpenedTag();							

			$this->openTag("input", array("type"=> "submit", "name" => "submit",  "id" =>"submit"));
			$this->closeLastOpenedTag();	

			$this->closeOpenTags();

			exit();			
		}

		//
		//	Prints out a notice and exits
		//

		function noticePage($message = null){

			$this->header();
			$this->openTag("body");
			$this->topBanner();
			$this->openTag("div", array("id" => "wrapper"));
			
			if ($message) {
				$this->notice($message);
			}

			$this->closeOpenTags();

			exit();				

		}

		//	Prints out the top banner
		//

		function topBanner(){	

			$this->openTag("div", array("id" => "topBanner"));

			//
			//	Print the app name
			//

			$this->openTag("span", array("id" => "title"));
			echo Meta::appName();
			$this->closeLastOpenedTag();

			//
			//	Show the appropriate button
			//

			if($this->user_manager->isLoggedOut()){

				//
				//	Check what the action is
				//

				$action = '';

				if(isset($_REQUEST['action'])){
					$action = $_REQUEST['action'];
				}

				//
				//	If we're on the registration page,
				//	then we want to show a "home" button.
				//

				if($action == 'registration'){

					$this->openTag("a", array("href" => "/?action=showlogin"));
					echo "Sign In";
					$this->closeLastOpenedTag();					

					$this->openTag("a", array("href" => "/?action=", "class" => "floatLeft"));
					echo "Back";
					$this->closeLastOpenedTag();
				}

				//
				//	Otherwise, we just want to show 
				//	the register and sign in buttons.
				//

				else{

					$this->openTag("a", array("href" => "/?action=showlogin"));
					echo "Sign In";
					$this->closeLastOpenedTag();					

					$this->openTag("a", array("href" => "/?action=registration"));
					echo "Get an Account";
					$this->closeLastOpenedTag();
				}
			}

			//
			//	Otherwise, we're logged in.
			//

			else if($this->user_manager->isLoggedIn()){
				$this->openTag("a", array("href" => "/?action=logout"));
				echo "Log Out";
				$this->closeLastOpenedTag();
			}

			$this->closeLastOpenedTag();
		}

		//
		//	Prints an error message
		//

		function error($message){
			$this->openTag("h3", array("class"=>"error"));
			echo $message . "\n";
			$this->closeLastOpenedTag();
		}

		//
		//	Prints an error message
		//

		function notice($message){
			$this->openTag("h3", array("class"=>"notice"));
			echo $message . "\n";
			$this->closeLastOpenedTag();
		}	

		//
		//	Echoes a simple homepage
		//

		function homepage(){
			$this->header();
			
			$this->openTag("body");
			$this->topBanner();			
			$this->openTag("div", array("id" => "wrapper"));

			echo "Welcome " . $this->user_manager->currentUser()->username;

			$this->closeOpenTags();	

			exit();
		}

		//
		//	Returns the appropriate title
		//	

		function title(){

			$action = null;

			if(isset($_REQUEST['action'])){
				$action = $_REQUEST['action'];
			}

			//
			//	Return the correct title 
			//	based upon the action.
			//

			if ($action == 'registration') {
				return "Register";
			}

			//
			//	Return a login title
			//

			else if($action == 'showlogin') {
				return 'Log In';
			}

			//
			//	Return a logout title
			//

			else if($action == 'logout') {
				return 'Log Out';
			}

			//
			//	Return the app name
			//

			return Meta::appName();
		}
	}

}

?>
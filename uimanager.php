<?php

/*

	uimanager.php

	The UIManager class contained in this
	file makes certain UI really easy to create.
*/

namespace lightbulb{

	class UIManager{

		private $tag_stack;

		function __construct(){
			$this->tag_stack = array();
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
			echo "Login";
			$this->closeLastOpenedTag();
			$this->openTag("style", array("type" => "text/css"));
			echo "@import 'style.css';\n";
			$this->closeLastOpenedTag();
			$this->closeLastOpenedTag();
		}

		//
		//	This function prints a login form
		//

		function loginForm($message = null){
			$this->header();
			$this->openTag("body");
			$this->openTag("div", array("id" => "wrapper"));

			if ($message) {
				$this->notice($message);
			}

			$this->openTag("form", array("method"=>"post"));
			$this->openTag("input", array("type"=>"text", "id" => "username", "name" => "username"));
			$this->closeLastOpenedTag();
			$this->openTag("input", array("type"=>"password", "id" => "password", "name" => "password"));			
			$this->closeLastOpenedTag();			
			$this->openTag("input", array('type'=> 'hidden', 'name'=> 'action', 'value' => 'login'));
			$this->closeLastOpenedTag();							
			$this->openTag("input", array('type'=> 'submit'));
			$this->closeLastOpenedTag();			

			$this->closeOpenTags();
		}

		//
		//	Register
		//

		function signupForm($error = null){
			$this->header();

			$this->openTag("body");
			$this->openTag("div", array("id" => "wrapper"));

			// Show an error message if appropriate
			if($error){
				$this->notice($error);
			}

			$this->openTag("form", array("method"=>"post"));
			
			$this->openTag("label");
			echo "Choose a username:";
			$this->closeLastOpenedTag();

			$this->openTag("input", array("type"=>"text", "id" => "username", "name" => "username"));
			$this->closeLastOpenedTag();

			$this->openTag("label");
			echo "Choose a password:";
			$this->closeLastOpenedTag();			
			$this->openTag("input", array("type"=>"password", "id" => "password", "name" => "password"));			
			$this->closeLastOpenedTag();	

			$this->openTag("label");
			echo "Confirm your password:";
			$this->closeLastOpenedTag();

			$this->openTag("input", array("type"=>"password", "id" => "confirm", "name" => "confirm"));			
			$this->closeLastOpenedTag();		

			$this->openTag("input", array('type'=> 'hidden', 'name'=> 'action', 'value' => 'register'));
			$this->closeLastOpenedTag();							

			$this->openTag("input", array('type'=> 'submit'));
			$this->closeLastOpenedTag();	

			$this->closeOpenTags();
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
	}

}

?>
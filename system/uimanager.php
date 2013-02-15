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
		use \PDO as PDO;

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

			function openTag($tag=null, $attributes=null, $selfClosing = false){

				if ($tag == null) {
					return;
				}

			
				echo "<" . $tag . "";

				if ($attributes) {
					foreach ($attributes as $attribute => $value) {
						echo ' '. $attribute . '="' . $value . '"';
					}
				}

				if ($selfClosing == true) {
					echo " /";
				}
				else{
					$this->tag_stack[] = $tag;
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
			//	Construct a standard header for the pages
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
				if($this->isIphone()) {
					echo "@import 'mobile.css';";			
				}
				else{
					echo "@import 'style.css';";
				}

				echo "@import 'color.css';";			
				$this->closeLastOpenedTag();
				$this->closeLastOpenedTag();
			}

			//
			//	This function prints a login form
			//

			function loginPage($message = null){

				$this->header();
				$this->openTag("body");
				$this->topBanner();
				$this->openTag("div", array("id" => "wrapper"));

				if ($message) {
					$this->notice($message);
				}
				
				$this->loginForm();

				$this->closeOpenTags();

				exit();			
			}

			function loginForm(){

				if (Switches::$LOG_IN_ENABLED == false) {
					$this->notice('The control panel is closed for now.');
					exit();
				}

				$this->openTag("form", array("method"=>"post", "action" => "/index.php"));

				$this->inputWithLabelAndAttributes("Enter your username:",  array("type"=>"text", "id" => "username", "name" => "username")); 
				$this->inputWithLabelAndAttributes("Enter your password:",array("type"=>"password", "id" => "password", "name" => "password"));
				$this->inputWithLabelAndAttributes(null, array("type"=> "hidden", "name"=> "action", "value" => "login"));
				$this->inputWithLabelAndAttributes(null, array("type"=> "submit", "name" => "submit",  "id" =>"submit"));
			}

			//
			//	Register
			//

			function signupForm($error = null, $request = null){

				//
				//	Check if there's a lock on signups
				//

				if (Switches::$NEW_SIGN_UPS_ENABLED == false) {
					$this->noticePage('New recruits aren\'t being accepted at this time. Sorry!');
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

				$this->inputWithLabelAndAttributes("Choose a username:", array("type"=>"text", "id" => "username", "name" => "username", "value" => $request['username']));
				$this->inputWithLabelAndAttributes("Enter your phone number:", array("type"=>"text", "id" => "phone", "name" => "phone", "value" => $request['phone']));
				$this->inputWithLabelAndAttributes("Choose a password:", array("type"=>"password", "id" => "password", "name" => "password"));
				$this->inputWithLabelAndAttributes("Confirm your password:", array("type"=>"password", "id" => "confirm", "name" => "confirm"));		
				$this->inputWithLabelAndAttributes(null, array("type"=> "hidden", "name"=> "action", "value" => "register"));
				$this->inputWithLabelAndAttributes(null, array("type"=> "submit", "name" => "submit",  "id" =>"submit"));

				$this->closeOpenTags();

				exit();			
			}

			//
			//	Prints an input tag with a label in a wrapper
			//

			function inputWithLabelAndAttributes($label = null, $attributes){

				//	A wrapper for the label and input
				$this->openTag("span", array("class" => "formRow"));

				// If there's a label, print it
				if($label != null){
					$this->openTag("label", array("class" => "formLabel"));
					echo $label;
					$this->closeLastOpenedTag();
				}

				// print the input tag
				$this->openTag("input", $attributes, true);			
		
				$this->closeLastOpenedTag();			
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

				$this->openTag("a", array("href" => "/", "id" => "title"));
				 $this->openTag("img", array("src" => "./images/Icon-44.png"), true);

				if($this->user_manager->isLoggedIn()){
					echo ucfirst($this->user_manager->currentUser()->username) . "'s " ;
				}

				echo Meta::appName();

				$this->closeLastOpenedTag();			
				

				//
				//	Check what the action is
				//

				$action = '';

				if(isset($_REQUEST['action'])){
					$action = $_REQUEST['action'];
				}			

				//
				//	Show the appropriate buttons
				//

				if($this->user_manager->isLoggedOut()){

					//
					//	Show a login button
					//


					$this->openTag("a", array("href" => "/?action=showlogin"));
					echo "Sign In";
					$this->closeLastOpenedTag();					
					

					//
					//	Show the account button
					//

					$this->openTag("a", array("href" => "/?action=registration"));
					echo "Get an Account";
					$this->closeLastOpenedTag();
					
				}

				//
				//	Otherwise, we're logged in.
				//

				else if($this->user_manager->isLoggedIn()){
					$this->openTag("a", array("href" => "/?action=logout"));
					echo "Log Out";
					$this->closeLastOpenedTag();
				}			

				//
				//	Otherwise, we just want to show 
				//	the register and sign in buttons.
				//
					
				$this->openTag("a", array("href" => "/?action=faq"));
				echo "What is this?";
				$this->closeLastOpenedTag();		

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
			//
			//

			function titleBar($message){
				$this->openTag("h3", array("class"=>"titleBar"));
				echo $message . "\n";
				$this->closeLastOpenedTag();			
			}

			//
			//	Echoes a homepage
			//

			function homepage(){
				
				//
				//	Check if there's a lock on signups
				//

				if (Switches::$HUMBLE_HOME == true) {
					die('moo');
				}

				$this->header();

				$this->openTag("body");
				$this->topBanner();			
				$this->openTag("div", array("id" => "wrapper"));			

				if($this->user_manager->isLoggedIn()){
					$this->controlPanel();
				}else{
					$this->titleBar("Welcome");
					$this->openTag("p", array("class"=> "content"));
					echo Meta::appName() . " allows Brooklyn College students to recieve text alerts when classes open and close. Alerts for course changes are also available. You can <a href=\"/?action=registration\">create an account here.</a>";
					$this->closeLastOpenedTag();
					$this->openTag("p", array("class"=> "content"));
					echo "After you sign up, enter the 4 digit registration codes of course sections that you want to follow. You'll get a text when CUNY updates thier website, and you can come back here to check on your classes.";
					$this->closeLastOpenedTag();
					$this->openTag("p", array("class"=>"content"));
					echo 'Unlike the geniuses in <a href="http://www.nytimes.com/2013/02/05/nyregion/tech-savvy-baruch-college-students-seek-an-edge-in-registration.html?_r=0">this article</a>, lightbulb doesn\'t log in to anything and hasn\'t come close to a million hits on any server.';
					$this->closeLastOpenedTag();					
				}

				$this->closeOpenTags();	

				exit();

			}

			//
			//	Echoes an FAQ page
			//

			function faq(){

				//
				//	Check if there's a lock on signups
				//

				if (Switches::$FAQ == false) {
					$this->noticePage('I\'m not talking about this project yet.');
				}

				$this->header();
				
				$this->openTag("body");
				$this->topBanner();			
				$this->openTag("div", array("id" => "wrapper"));

				$this->printFAQ("What is this place?", "Welcome to " . Meta::appName() . "! " . Meta::appName() . " is a way for Brooklyn College students to get alerts when classes open, close, and when professors are listed.");
				$this->printFAQ("How does it work?", "First, you sign up. Next you choose some course sections to follow. When there's a change, you'll get notified.");
				$this->printFAQ("Why does " . Meta::appName() . " need my number?", "Alerts are sent as text messages. If I can't get in touch with you, I can't tell you when a class changes.");
				$this->printFAQ("What about email?", "Lightbulb doesn't do email at the moment. Sorry. Text messages are cooler anyway.");
				$this->printFAQ("I don't have texting. Can I still use lightbulb?", "Yep! Set up a Google Voice account and make it email you all of your texts. Now you can get lightbulb alerts as emails.");			
				$this->printFAQ("What does it cost?", "Right now, nothing. " . Meta::appName() . " is a free <em>while I test it out</em>.");			
				$this->printFAQ("I want to pay!", 'Do you? Go <a href="http://itunes.apple.com/us/app/ibrooklyn/id500958091?mt=8">download iBrooklyn from the App Store</a>. Leave a nice review. Tell your friends to do the same. It\'ll make me happy.');
				$this->printFAQ("I really, really, want to pay you for this!", "If I helped you, you can <a href=\"http://amzn.com/w/1JN459HVK4WGO\"> send me something from my Amazon Wishlist.</a>" );				
				$this->printFAQ("But why?", "I wanted it. It helped me get into some classes. Oh, if anyone wants to drop core robotics, lemme know.");
				$this->printFAQ("Can you hold a class for me?", "No.");
				$this->printFAQ("Why not?", "Gaming the system usually doesn't pay. I tried it once and lost a course for someone. Don't put me in that position.");
				$this->printFAQ("How do honors classes work?", "I don't really know.");
				$this->printFAQ("Why don't you know? Aren't you the courses guy?", "I just made this thing. I don't run the bureaucracy that is CUNY. Google it. I'm sure it's on the internet.");

				$this->closeOpenTags();	

				exit();
			}

			//
			//	Prints a question for the FAQ
			// 

			function printFAQ($title = "FAQ", $answer = "Answer"){
				$this->printFAQTitle($title);
				$this->openTag("p", array("class"=> "content"));
				echo $answer;
				$this->closeLastOpenedTag();
			}

			function printFAQTitle($title = "FAQ"){
				$this->openTag("h3", array("class"=>"faqTitleBar titleBar"));
				echo $title . "\n";
				$this->closeLastOpenedTag();			
			}

			//
			//	Returns the appropriate title
			//	

			function title(){

				//
				//	Return the app name
				//

				return Meta::appName();
			}

			//
			//
			//

			function isIphone($user_agent=NULL) {
	    		if(!isset($user_agent)) {
	        		$user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
	    		}
	    		return (strpos($user_agent, 'iPhone') !== FALSE);
			}

			//
			//	Prints the control panel
			//
			
			function controlPanel(){


				?>

				<div id="controlPanel">

					<div class="controlPanelColumn">
						<span class="notice">
							Enter a 4 digit code to follow a class. <span class="showOnMobile">Log in on a desktop to see more info and drop courses.</span>
						</span>
						<form>
							<input type="text" name="code" />
							<input type="hidden" name="action" value="follow" /> 
							<input type="submit" name="submit" value="Follow" />
						</form>

						<?php 
							
							/*
								
								Fetch and display followed sections.
	
							*/

							$followedStatement = $this->user_manager->currentUser()->showFollowedSectionsStatement();

							$connection = $this->user_manager->connection();
					
							$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

							$statement = $connection->prepare($followedStatement);

							$results = $connection->query($followedStatement, PDO::FETCH_ASSOC);

							if ($results->rowCount() > 0) {

						?>


						<table>

							<tr class="tableHeader">
								<td>
									Code
								</td>
								<td>
									Seats
								</td>								
								<td>
									Who
								</td>
								<td>
									What
								</td>
								<td class="hideOnMobile">
									When
								</td>
								<td class="hideOnMobile">
									Where
								</td>

								<td class="hideOnMobile">Why?</td>
							</tr>

							<?php

							//	Print out each result
							foreach ($results as $result) {
								$this->openTag("tr");

								$code = $result['code'];
								$instructor = $result['instructor'];
								//	Clean up the name a bit
								$name = preg_replace('/[^(\x20-\x7F)]*/','', substr($result['name'], 0, strpos($result['name'], ' (')) . ' ' . $result['description']);  
								$when = preg_replace('/[^(\x20-\x7F)]*/','', $result['dayAndTime']);
								$where = preg_replace('/[^(\x20-\x7F)]*/','', $result['buildingAndRoom']);
								$seats = $result['openSeats'];

								?>

								<tr>
									<td>
										<?php echo $code; ?>
									</td>		
									<td>
										<?php echo $seats; ?>
									</td>										
									<td>
										<?php echo $instructor; ?>
									</td>									
									<td>
										<?php echo $name; ?>
									</td>					
									<td class="hideOnMobile">
										<?php echo $when; ?>
									</td>				
									<td class="hideOnMobile">
										<?php echo $where; ?>
									</td>		
									<td class="hideOnMobile">
										<?php $this->unfollowForm($code); ?>
									</td>																					
								</tr>

								<?php								

								$this->closeLastOpenedTag();								
							}



						?>
						</table>
						<?php

						}
						else{

							?>

							<span class="notice">
								You aren't following any classes.
							</span>

							<?php 
						}

						?>
					</div>
					<div class="controlPanelColumn">

					</div>


				</div>

				<?

			}

			//
			//
			//

			function unfollowForm($code){


				?>

				<form class="unfollowForm">

					<input type="hidden" name="code" value=<?php echo '"'.$code.'"' ?> />
					<input type="hidden" name="action" value="unfollow" />
					<input type="submit" value="Unfollow"  style="color: #5b172c;" class="dropButton" />
				</form>

				<?php

			}		
		}


	}

	?>

<?php

//
//
//

require('system.php');

$user_manager = new Lightbulb\UserManager;
$UI_manager = new Lightbulb\UIManager;

//
//	Attempt to log in
//	

if (isset($_REQUEST['action'])) {

	$action = $_REQUEST['action'];

	//
	//	Read out the request
	//

	$username = $_REQUEST['username'];
	$password = $_REQUEST['password'];
	$confirm = $_REQUEST['confirm'];

	//
	//	Take appropriate action
	//

	if($action == 'login'){
		$user_manager->login($username, $password);
	}

	else if($action == 'registration'){
		$UI_manager->signupForm();
		exit();
	}

	else if($action == 'register'){

		if ($user_manager->usernameExists($username)) {
			$UI_manager->signupForm("That username exists.");
			exit();
		}

		else if($password != $confirm){
			$UI_manager->signupForm("Those passwords don't match.");
			exit();
		}

		else {
			
			$success = $user_manager->createUser($username, $password);

			if($success){
				$UI_manager->loginForm("User successfully created.");			
			}
			else{
				$UI_manager->loginForm("User creation failed, please try again.");				
			}

			exit();
		}
	}
}

//
//	If the user isn't logged in, 
//	then show a login form.
//

if ($user_manager->isLoggedOut()) {
	$UI_manager->loginForm();
	exit();
}
else{
	echo "Welcome.";

	exit();
}

?>



<html>
	<head>
		<title>
			<?php

				if ($isLoggedIn) {
					echo "Welcome to Lightbulb";
				}

				else{
					echo "Log In";
				}
			?>


		</title>
	</head>
	<body>

		<div id="wrapper">
			<div id="header">
				<div id="topbar">

					<?php if($isLoggedIn){ ?>

					<span id="username">
						<?php echo phpCAS::username() ?>
					</span>
					<span id="logout">
						<a class="button" href="?logout">
							Logout
						</a>
					</span>

					<?php 

						}
						else{
					 ?>

					 <span>
					 	Lightbulb
					 </span>
					 <span id="login">
					 	<a href="/?login">Log In</a>
					 </span>

					 <?php } ?>
				</div>
			</div>
			<div>

			</div>
		</div>

	<!--
		<p>Welcome, <?php echo phpCAS::getUser(); ?>!</p>
		<ul id="links">
		<li>
			<a href="https://login.brooklyn.cuny.edu/cas/login?service=https://portal.brooklyn.edu/uPortal/Login&vm=portal6" target="_blank">
				BC Portal
			</a>
		</li>
		<li>
			<a href="https://login.brooklyn.cuny.edu/cas/login?service=http%3A%2F%2Fwebsql.brooklyn.cuny.edu%2Ffacultyevaluations%2Findex.jsp" target="_blank">
				BC Feedback Reports
			</a>
		</li>
		<li>
			<a href="https://login.brooklyn.cuny.edu/cas/login?service=https%3A%2F%2Fwebsql.brooklyn.cuny.edu%2Fealerts%2Fprocess_login.jsp" target="_blank">
				CUNY eAlerts
			</a>
		</li>
		<li>
			<a href="https://login.brooklyn.cuny.edu/cas/login?service=https%3A%2F%2Fwebsql.brooklyn.cuny.edu%2Fegrades%2Fcheckmail_egrades.jsp" target="_blank">
				eGrades
			</a>
		</li>
		<li>
			<a href="https://login.brooklyn.cuny.edu/cas/login?service=https%3A%2F%2Fwebsql.brooklyn.cuny.edu%2Fdegree_progress%2Fstudent%2FloginStuAction" target="_blank">
				My Degree Progress
			</a>
		</li>		
		<li>
			<a href="https://login.brooklyn.cuny.edu/cas/login?service=https%3A%2F%2Fwebsql.brooklyn.cuny.edu%2Fserva%2Fauthenticate.jsp" target="_blank">
				SERVA
			</a>
		</li>
		<li>
			<a href="?logout=">
				Logout
			</a>
		</li>
		</ul>
	-->

	</body>
</html>

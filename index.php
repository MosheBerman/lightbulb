<?php

require_once '../private/CAS/CAS.php';

phpCAS::client(CAS_VERSION_2_0, 'login.brooklyn.cuny.edu', 443, '/cas');
// phpCAS::setNoCasServerValidation();
phpCAS::setCasServerCACert("../private/CAS/BrooklynCollegeLogin.cert");


//
//	Force forceAuthentication
//

$isLoggedIn = phpCAS::isAuthenticated();

 // if (!$isLoggedIn && isset($_REQUEST['login'])) {
	phpCAS::forceAuthentication();
 // }


//
//	Optional Logout
//

if ($isLoggedIn && isset($_REQUEST['logout'])) {
	phpCAS::logout();
}

//
//	If we've made it this far, we've logged in.
//	So, we want to check if the user is in the 
//	database. If the user is, show their info.
//	If not, we need to add them and let em sign 
//	up for classes.
//

if ($isLoggedIn){
	
	//
	//	Prepare the connection
	//

	$CONNECTION_STRING = 'mysql:host=127.0.0.1;dbname=fluorescent;charset=utf8';
	$username = '***REDACTED***';
	$password = '***REDACTED***';

	$connection = new PDO($CONNECTION_STRING);

	$userQuery = $connection->query('SELECT * FROM Users');

	$userProperties = array('id', 'number', 'email', 'username');

	$userQuery->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'User', $userProperties);

	//
	//	Pull the users from the database
	//

	$users = $userQuery->fetchAll();

	//
	//	We want to check if the user
	//	exists and if it doesn't add
	//	them. 
	//

	$userExists = false;
	$workingUser = null;

	$username = phpCAS::getUser();

	foreach ($users as $user) {
		if ($user->username == $username) {
			$userExists = true;
			$workingUser = $user;
			break;
		}
	}

	if ($userExists == false) {
	
		$workingUser = new User(-1, "", "", $username);

		//
		//	TODO: Store user 
		//


		$sql = $connection->prepare("INSERT INTO USERS(number, email, username) VALUES('','','".$username."')");
		$sql->execute();

	}

	//
	//	User exists, so lets run any
	//	relevant actions.
	//

	else {

		$needsRedirect = false;

		//
		//	Save an updated phone number
		//

		if (isset($_REQUEST['phone'])) {

			$sql = $connection->prepare("UPDATE USERS SET phone=`".$_REQUEST['phone']."` WHERE username = `".$workingUser->username."`");
			$sql->execute();	
			$needsRedirect = true;
		}

		//
		//	Save an updated email
		//

		if (isset($_REQUEST['email'])) {	
			$sql = $connection->prepare("UPDATE USERS SET email=`".$_REQUEST['email']."` WHERE username = `".$workingUser->username."`");
			$sql->execute();	
			$needsRedirect = true;
		}		

		//
		//	At the end, redirect after applying any changes
		//

		if($needsRedirect){
			header("Location:/index.php");
		}
	}
}


echo $isLoggedIn;
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

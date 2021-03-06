<?php

	/*

		usermanager.php

		This file contains the UserManager class,
		which manages user lookup, creation, 
		and login.

	*/


namespace lightbulb{

	include_once("system.php");

	use \PDO as PDO;
	use \User as User;

	class UserManager{

		private $users;

		//
		//	Create a new user manager
		//

		function __construct(){
			$this->users = array();
			$this->refresh();
		}

		//
		//	Returns a connection
		//

		function connection(){

			$CONNECTION_STRING = 'mysql:host=127.0.0.1;dbname=fluorescent;charset=utf8';
			$username = '***REDACTED***';
			$password = '***REDACTED***';

			$connection = new PDO($CONNECTION_STRING, $username, $password);

			return $connection;
		}

		//
		//	Reload the users from the
		//	database and into the local
		//	cache of users.
		//

		function refresh(){
		
			//
			//	Prepare the connection
			//

			$connection = $this->connection();

			//
			//	Prepare a query
			//

			$userQuery = $connection->query('SELECT * FROM Users');

			$userProperties = array('id', 'number', 'email', 'username', 'password', 'isWatchingCourses');

			$userQuery->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'User', $userProperties);

			//
			//	Pull the users from the database
			//

			$this->users = $userQuery->fetchAll();
		}

		//
		//	This function creates a user.
		//

		function createUser($username, $password, $phone){

			if (!isset($username) || !isset($password) || !isset($phone)) {
				return false;
			}

			else if($username == "" || $password == "" || $phone == ""){
				return false;
			}

			$user = new User($username);
			$user->password = $password;
			$user->number = $phone;

			$connection = $this->connection();

			//
			//	Execute the insertion
			//

			$insert = $user->insertStatement();

			$sql = $connection->prepare($insert);
		
			try{
				$sql->execute();
				return true;
			}
			catch(PDOException $e){
				echo $e;
				return false;
			}

		}

		/* User update functions */

		//
		//	This function causes a user to update
		//	itself in the database.
		//

		function updateUser($user){

				$connection = $this->connection();

				//
				//	Execute the insertion
				//

				$success = $connection->prepare($user->updateStatement())->execute();

				$this->refresh();

				return $success;
		}

		//
		//	Updates a password
		//

		function updateUserPassword($username, $password){
			$user = $this->userForName($username);

			if ($user = $this->currentUser) {
				$user->password = Bcrypt::hash($password);
				$this->updateUser($user);
			}
		}

		//	
		//	Update a user's phone number
		//

		function updateUserNumber($username, $number){
			$user = $this->userForName($username);

			if ($user = $this->currentUser) {
				$user->number = $number;
				$this->updateUser($user);
			}
		}

		//
		//	Update a user's email
		//

		function updateUserEmail($username, $email){
			if ($user = $this->currentUser) {
				$user->email = $email;
				$this->updateUser($user);
			}
		}

		/* Login and logout */

		//
		//	Attempts to log a user in
		//

		function login($user, $password){

			$user = $this->userForName($user);

			if (!$user) {
				$this->logout();
				return false;
			}

			else{
				if (bcrypt_check($password, $user->password)) {
					$_SESSION['user'] = $user;
					return true;
				}
				else{
					$this->logout();
				}
			}

			return false;

		}

		//
		//	Logs a user out
		//

		function logout(){
				unset($_SESSION['user']);	
		}

		/* User lookup functions */

		//
		//	Checks if a user exists
		//

		function userForName($username){
			foreach ($this->users as $user) {
				if ($user->username == $username) {
					return $user;
				}
			}
			return null;
		}

		//
		//	Returns true if a username exists
		//

		function usernameExists($username){
			$this->refresh();
			return $this->userForName($username) != null;
		}

		//
		//	Checks if anyone is logged in
		//

		function isLoggedIn(){
			return isset($_SESSION['user']);
		}

		//
		//	Checks that nobody is logged in
		//

		function isLoggedOut(){
			return !isset($_SESSION['user']);
		}		

		//
		//	Returns the currently logged in user.
		//

		function currentUser(){
			return $_SESSION['user'];
		}

		//
		//	Returns all of the users
		//

		function allUsers(){
			return $this->users;
		}	

		//
		//	Returns users that want texts
		//

		function usersWhoWantTexts(){

			//
			//	TODO: Get a general array pf all students who want texts 
			//

		}

		//
		//	This method returns users 
		//	who are following a given
		//	section. 
		//
		//	The second parameter
		//	is optional, if left empty,
		//	the current set of users will
		//	be used. ($this->allUsers())
		//

		function usersForSection($code = null, $users = null){

			//	If there's no code, return null
			if ($code == null) {
				return null;
			}

			//	If there's no users, default to all users
			if ($users == null) {
				$users = $this->allUsers();
			}

			//	
			//	TODO: Follow sections
			//

		}
	}
}

?>
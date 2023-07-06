<?php
/*	Web Programming Using PHP Cwk2 Task 4 - MVC Controller
	This script should act as the controller for a Single Point of Entry MVC design model
*/
require_once 'includes/functions.php';  #your user defined function library to be implemented
require_once 'includes/config.php';  	#your database connection returns $pdo connection variable
#start your PHP session here
session_start();

echo "<h1>Web Programming Using PHP - Coursework 2 - Task 4</h1>";
#check for login form submission, authenticate user credentials, set session variables, set view from Cookie or 'home'
include 'html/loginFormTemplate.html';

if (isset($_POST['login'])) {
	$username = $_POST['username'];
	$password = $_POST['password'];
	$login = login($pdo, $username, $password);
	if ($login) {
		$_SESSION['username'] = $username;
		$_SESSION['password'] = $password;
		$_SESSION['logged_in'] = true;
		$_SESSION['view'] = 'home';
	} else {
		$_SESSION['logged_in'] = false;
		$_SESSION['view'] = 'login';
	}
}

#set login form placeholder data depending on results of login form authenication



#determine whether to display login or logout form by checking session data


#detect logout and set last page viewed Cookie and delete session correctly

#determine which view model code to include by checking URL parameter; 

#define NAV options based on session data

#include relevant model view code in \views based on selected view URL parameter from generated NAV code

#load main page template and replace placeholders with data generated from included model view code

#display main page template with all placeholder data for current selected view

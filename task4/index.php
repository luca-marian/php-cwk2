<?php
/*	Web Programming Using PHP Cwk2 Task 4 - MVC Controller
	This script should act as the controller for a Single Point of Entry MVC design model
*/
require_once 'includes/functions.php';  #your user defined function library to be implemented
require_once 'includes/config.php';  	#your database connection returns $pdo connection variable
#start your PHP session here
session_start();


#check for login form submission, authenticate user credentials, set session variables, set view from Cookie or 'home'
$formPlaceholders = clearFormPlaceholders();


#set login form placeholder data depending on results of login form authenication
$formPlaceholders = processLogin($formPlaceholders);


#determine whether to display login or logout form by checking session data
if (isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] === true) {
	$logInOutForm = file_get_contents('html/logoutFormTemplate.html');
	$formPlaceholders['[+loggedInName+]'] = $_SESSION['username'];
} else {
	$logInOutForm = file_get_contents('html/loginFormTemplate.html');
}

#detect logout and set last page viewed Cookie and delete session correctly
if (isset($_POST['logout'])) {
	$_SESSION['isLoggedIn'] = false;
	$_SESSION['username'] = null;
	$_SESSION['userType'] = null;
	$logInOutForm = file_get_contents('html/loginFormTemplate.html');

	header('Location: index.php');
}


#determine which view model code to include by checking URL parameter; 
$defaultView = 'home';

if (!isset($_GET['view'])) {
	$view = $defaultView;
} elseif (isset($_COOKIE['view'])) {
	$view = $_COOKIE['view'];
} else {
	$view = $defaultView;
}

switch ($view) {
	case 'home':
		include 'views/home.php';
		break;
	case 'student':
		include 'views/student.php';
		break;
	case 'admin':
		include 'views/admin.php';
		break;
	case 'academic':
		include 'views/academic.php';
		break;
	default:
		include 'views/404.php';
}


#define NAV options based on session data
$navOptions = array(
	'admin' => ['home', 'student', 'academic', 'admin'],
	'academic' => ['home', 'student', 'academic'],
	'student' => ['home', 'student'],
	'home' => 'home'
);

#include relevant model view code in \views based on selected view URL parameter from generated NAV code

#load main page template and replace placeholders with data generated from included model view code
$template = file_get_contents('html/pageTemplate.html');

$template  = str_replace(['[+heading+]', '[+content+]', '[+title+]', '[+loginOutForm+]'], [$viewHeading, $content, $headTitle, $logInOutForm], $template);

$formPlaceholders['[+nav+]'] = generateNavbar($navOptions);

foreach ($formPlaceholders as $placeholder => $value) {
	$template = str_replace($placeholder, $value, $template);
}

#display main page template with all placeholder data for current selected view
echo $template;

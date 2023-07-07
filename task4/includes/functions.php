<?php
#some basic HTML generation functions

function htmlHeading($text, $level)
{
	$heading = trim(strtolower($text));
	switch ($level) {
		case 1:
		case 2:
			$heading = ucwords($heading);
			break;
		case 3:
		case 4:
		case 5:
		case 6:
			$heading = ucfirst($heading);
			break;
		default: #traps unknown heading level exception
			$heading = '<FONT COLOR="#ff0000">Unknown heading level:' . $level . '</FONT>';
	}
	return '<h' . $level . '>' . htmlentities($heading) . '</h' . $level .  '>';
}

function htmlParagraph($text)
{
	return '<p>' . htmlentities(trim($text)) . '</p>';
}

#ADD YOUR USER DEFINED FUNCTIONS HERE

function renderRows($name, $value)
{
	return "<tr><td>" . htmlentities($name) . "</td><td>" . htmlentities($value) . "</td></tr>";
}

function calculateStatistics($tableName)
{
	global $pdo;

	$table = "";

	$classifications = [
		'1st' => ['min' => 70, 'max' => 101],
		'2.1' => ['min' => 60, 'max' => 70],
		'2.2' => ['min' => 50, 'max' => 60],
		'3rd' => ['min' => 45, 'max' => 50],
		'Pass' => ['min' => 40, 'max' => 45],
		'Fail' => ['min' => 0, 'max' => 40]
	];

	try {
		$table .= "<table>";
		$table .=  "<tr><th>Static</th><th>Number</th></tr>";

		foreach ($classifications as $name => $range) {
			$stmt = $pdo->prepare("SELECT COUNT(*) as count FROM $tableName 
                                    WHERE moduleResult >= :min AND moduleResult < :max");
			$stmt->execute([':min' => $range['min'], ':max' => $range['max']]);
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			$table .= renderRows($name, $row['count']);
		}

		$stmt = $pdo->prepare("SELECT AVG(moduleResult) as AverageMark FROM $tableName");
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$table .= renderRows("Average Mark", $row['AverageMark']);

		$stmt = $pdo->prepare("SELECT count(*) as TotalStudents FROM $tableName");
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$table .= renderRows("Total Students", $row['TotalStudents']);

		$table .= "</table>";
	} catch (PDOException $e) {
		echo "<p>Unable to connect to the database: " . $e->getMessage() . "</p>";
		exit;
	}

	return $table;
}
function clearFormPlaceholders()
{
	# Clear form placeholders
	return [
		'[+loginError+]' => '',
		// "[+loggedInName+]" => '',
		// "[+loginOutForm+]" => '',
		"[+uName+]" => '',
		// "[+title+]" => '',
		"[+nav+]" => '',
		"[+loggedInName+]" => '',
		// "[+heading+]" => '',
		// "[+content+]" => '',
	];
}

function htmlList($address, $name)
{
	return "<li>" . "<a href=" . "'index.php?view=" . htmlentities($address) . "'" . ">" . $name . "</a> " . "</li></>";
}

function generateNavbar($navOptions)
{
	$navbar = '<nav><ul>';

	$navPageName = [
		'home' => 'Home Page',
		'student' => 'Student View',
		'academic' => 'Academic View',
		'admin' => 'Admin View',
	];

	if (isset($_SESSION['userType'])) {
		foreach ($navOptions[$_SESSION['userType']] as $address => $name) {
			$navbar .= htmlList($address, $navPageName[$address]);
		}
	} else {
		$default = 'home';
		$navbar .= htmlList($default, $navPageName[$default]);
	}

	$navbar .= '</ul></nav>';

	return $navbar;
}

function getUserType($username)
{
	# Query to extract all users from database
	$query = "SELECT userType FROM usersTable WHERE username = :username";
	$rows = queryDatabase($query, [
		'username' => $username,
	]);

	# Check if there are any users in the database
	if (empty($rows) || $rows === false || count($rows) > 1) {
		return false;
	}

	return $rows[0]['userType'];
}
function processLogin($formPlaceholders)
{
	if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['login'])) {
		$username = trim($_POST['userName']);
		$password = trim($_POST['password']);

		$formPlaceholders['[+uName+]'] = $username;

		# Check if the username and password are empty
		if (empty($username) || empty($password)) {
			$formPlaceholders['[+loginError+]'] = "Please enter a username and password. ";
			return $formPlaceholders;
		}

		# Check if the username exists in the database
		$auth = authenticateUser($username);

		if ($auth !== true) {

			$formPlaceholders['[+loginError+]'] = $auth;
			return $formPlaceholders;
		}

		# Check if the password is correct
		$auth = checkPassword($username, $password);

		if ($auth !== true) {
			$formPlaceholders['[+loginError+]'] = $auth;
			return $formPlaceholders;
		}

		# Set the session variables
		$_SESSION['username'] = $username;
		echo "User type: " . getUserType($username) . "<br>";
		$_SESSION['userType'] = getUserType($username);
		$_SESSION['isLoggedIn'] = true;
	}

	return $formPlaceholders;
}

function queryDatabase($query, $parameters = [])
{
	global $pdo;
	# Prepare the query
	$stmt = $pdo->prepare($query);

	# Bind parameters
	foreach ($parameters as $name => $value) {
		$stmt->bindValue($name, $value);
	}

	# Execute the query
	$stmt->execute();

	# Return query results
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function checkPassword($username, $password)
{
	# Check if the password is correct
	$query = "SELECT password FROM usersTable WHERE username = :username";
	$rows = queryDatabase($query, [
		'username' => $username,
	]);

	$passwordToCheck = $rows[0]['password'];

	if ($passwordToCheck !== $password) {
		return "Incorrect password";
	}

	return true;
}

function authenticateUser($username)
{
	# Query to extract all users from database
	$query = "SELECT userType, username, password ,email FROM usersTable WHERE username = :username";
	$rows = queryDatabase($query, [
		'username' => $username,
	]);

	# Check if there are any users in the database
	if (empty($rows) || count($rows) > 1 || $rows === false) {
		return "user unknown";
	}

	return true;
}

function getUsers()
{
	global $pdo;
	// # Query to extract all users from database
	$query = "SELECT  * FROM usersTable";

	# Prepare the query
	$stmt = $pdo->prepare($query);
	$stmt->execute();
	$rows = $stmt->fetchAll();

	# Check if there are any users in the database
	if (empty($rows)) {
		$error_message = "No users stored in the database";
		echo htmlParagraph($error_message);
		return;
	}

	return $rows;
}

<!DOCTYPE html>
<html lang="en">

<head>
	<title>Web Programming using PHP - Coursework 2 - Task 3</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="css/styles.css?" />

</head>

<body>
	<header>
		<h1>Web Programming using PHP - Coursework 2 - Task 3</h1>
		<h2>Webform data entry, validation, and database storage</h1>
	</header>
	<main>
		<?php
		#Your PHP solution code should go here...
		require_once 'includes/functions.php';

		# Variables to store the database connection details
		$host = "127.0.0.1";
		$user = "root";
		$password = "";
		$db = "webprogramming";
		$tableName = "usersTable";

		# Variables to store the user forms information
		$formPlaceholders = clearFormPlaceholders();
		$validData = TRUE;

		# Functions to connect to the database and create the table
		$pdo = connectToDatabase($host, $db, $user, $password);
		createTable($pdo, $tableName);

		# Check if the clear button has been pressed
		if (isset($_POST['userDataClear'])) {
			header('Location: index.php');
			exit;
		}

		# Check if the save button has been pressed
		if (isset($_POST['userDataSubmitted'])) {

			if (isset($_POST['email'], $_POST['username'], $_POST['password'], $_POST['userType'])) {
				$email = $formPlaceholders['[+email+]'] = trim($_POST['email']);
				$username = $formPlaceholders['[+username+]'] = trim($_POST['username']);
				$password = $formPlaceholders['[+password+]'] = trim($_POST['password']);
				$userType = $formPlaceholders['[+userType+]'] = trim($_POST['userType']);

				if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($email)) {
					$error_message = "Invalid email format";
					$formPlaceholders['[+emailError+]'] = $error_message;
					$validData = FALSE;
				}

				if ((strlen($username) < 8) || (!ctype_alnum($username)) || empty($username)) {
					$error_message = htmlentities("Less than 8 characters or NOT alphanumeric!");
					$formPlaceholders['[+usernameError+]'] = $error_message;
					$validData = FALSE;
				}

				if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!<>£$%&*~#]).{8,}$/", $password) || empty($password)) {
					$error_message = htmlentities("Not in the required password format");
					$formPlaceholders['[+passwordError+]'] = $error_message;
					$validData = FALSE;
				}

				if (!in_array($userType, ['admin', 'academic', 'student'])) {
					$error_message = "Invalid user type";
					$formPlaceholders['[+userTypeError+]'] = $error_message;
					$validData = FALSE;
				} elseif ($userType === 'admin') {
					$formPlaceholders['[+adminSelected+]'] = 'selected';
				} elseif ($userType === 'academic') {
					$formPlaceholders['[+academicSelected+]'] = 'selected';
				} elseif ($userType === 'student') {
					$formPlaceholders['[+studentSelected+]'] = 'selected';
				}


				if (!empty($email)) {
					if (queryUnique($pdo, $tableName, "email", $email) === 0) {
						$error_message = "This email is already registered!";
						$formPlaceholders['[+emailError+]'] = $error_message;
						$validData = FALSE;
					}
				}
				if (!empty($username)) {
					if (queryUnique($pdo, $tableName, "username", $username) === 0) {
						$error_message = "another user already has this username!";
						$formPlaceholders['[+usernameError+]'] = $error_message;
						$validData = FALSE;
					}
				}

				if ($validData === TRUE) {

					try {
						$stmt = $pdo->prepare("INSERT INTO usersTable (email, username, password, userType)
															VALUES (:email, :username, :password, :userType)");

						$checkInserted = $stmt->execute([':email' => $email, ':username' => $username, ':password' => $password, ':userType' => $userType]);

						if ($checkInserted === FALSE) {
							echo "Error storing user data in the database";
							$validData = FALSE;
						}
					} catch (PDOException $e) {
						echo "Error: " . $e->getMessage();
					}
				}
				if ($validData === TRUE) {
					echo renderParagraph("New user $username successfully inserted into database");
					$formPlaceholders = clearFormPlaceholders();
				}
			}
		}

		echo renderTemplate($formPlaceholders);

		echo reanderHeadings("Users stored in the Database", 2);

		renderUsersFromDatabase($pdo, $tableName);


		?>
	</main>
</body>

</html>
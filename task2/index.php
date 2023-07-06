<!DOCTYPE html>
<html lang="en">
<head>
    <title>Web Programming using PHP - Coursework 2 - Task 2</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="css/styles.css?" />

</head>
<body>
	<header>
        <h1>Web Programming using PHP - Coursework 2 - Task 2 PDO Database read/write</h1>
	</header>
	<main>

		<?php

		require_once 'includes/functions.php';
		#Your PHP solution code should go here...
		echo "<h2>Web Programming using Php</h2>";
		
		# Database connection parameters
		$host = "127.0.0.1";
		$user = "root";
		$password = "";
		$db = "webprogramming";
		$table_name = "moduleResults";
		$data_file_name = "data/p1.csv";

		
		$pdo = connectToDatabase($host, $db, $user, $password);
		createTable($pdo, $table_name);
		insertFromDataToDatabase($pdo, $table_name, $data_file_name);
		calculateStatistics($pdo, $table_name);
						
		?>
    </main>
</body>
</html>
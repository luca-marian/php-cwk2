<?php
# your PDO database connection code should go here

$host = "127.0.0.1";
$user = "root";
$password = "";
$db = "webprogramming";
$tableName = "usersTable";

# Connect to the database
try {
    $dsn = "mysql:host=$host;dbname=$db";
    $pdo = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    echo "<p>Unable to connect to the database: " . $e->getMessage() . "</p>";
    exit;
}

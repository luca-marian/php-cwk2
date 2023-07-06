<?php
#your PDO database connection code should go here

function connectToDatabase($host, $db, $user, $password)
{
    # Connect to the database
    try {
        $dsn = "mysql:host=$host;dbname=$db";
        $pdo = new PDO($dsn, $user, $password);
        // echo "<p>Connected to the $db database</p>";
    } catch (PDOException $e) {
        echo "<p>Unable to connect to the database: " . $e->getMessage() . "</p>";
        exit;
    }

    return $pdo;
}

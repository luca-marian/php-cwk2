<?php
function connectToDatabase($host, $db, $user, $password)
{
    // Connect to the database
    try {
        $dsn = "mysql:host=$host;dbname=$db";
        $pdo = new PDO($dsn, $user, $password);
    } catch (PDOException $e) {
        echo "<p>Unable to connect to the database: " . $e->getMessage() . "</p>";
        exit;
    }

    return $pdo;
}

function queryBoolean($pdo, $sql)
{
    # Funtion to query the database and return a boolean value
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        return 1;
    } else {
        return 0;
    }
}

function queryUnique($pdo, $tableName, $type, $parameterToCheck)
{
    // Check if the record already exists, for the given parameter
    if ($type === "email") {
        $stmt = $pdo->prepare("SELECT email FROM $tableName
                                WHERE email = :email");
        $stmt->execute([':email' => $parameterToCheck,]);
    } elseif ($type === "username") {
        $stmt = $pdo->prepare("SELECT username FROM $tableName
                                WHERE username = :username");
        $stmt->execute([':username' => $parameterToCheck,]);
    }

    if ($stmt->rowCount() > 0) {
        return 0;
    } else {
        return 1;
    }
}

function createTable($pdo, $tableName)
{
    # Query to extract table with specfic name
    $sql = "SHOW TABLES like '$tableName'";
    $result = queryBoolean($pdo, $sql);

    # Check if table exists 
    if ($result === 0) {

        # Query to create table
        $sql = "CREATE TABLE $tableName (
            userID INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(45)  NOT NULL UNIQUE,
            username VARCHAR(30) NOT NULL UNIQUE,
            password VARCHAR(15) NOT NULL,
            userType VARCHAR(10) NOT NULL
        )";

        # Execute query
        $result = queryBoolean($pdo, $sql);

        #Check if table was created
        if ($result === FALSE) {
            echo "<p>Error creating table $tableName: " . $pdo->errorInfo()[2] . "</p>";
        }
    }
}


function queryDatabase($pdo, $query)
{
    # Query the database
    $stmt = $pdo->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

# Function to render html 
function renderTemplate($formPlaceholders)
{
    # Get content of html file
    $template = file_get_contents('html/userDataForm.html');

    # Replace placeholders with values
    foreach ($formPlaceholders as $placeholder => $value) {
        $template = str_replace($placeholder, $value, $template);
    }

    return $template;
}

function clearFormPlaceholders()
{
    # Clear form placeholders
    $formPlaceholders = [
        '[+email+]' => '',
        "[+username+]" => '',
        "[+password+]" => '',
        "[+academicSelected+]" => '',
        "[+adminSelected+]" => '',
        "[+studentSelected+]" => '',
        '[+emailError+]' => '',
        "[+usernameError+]" => '',
        "[+passwordError+]" => '',
        "[+userTypeError+]" => '',
    ];

    return $formPlaceholders;
}

function renderUser($userID, $email, $username, $userType)
{
    # Function to render user from database
    return "<p>" . "ID:" . htmlentities($userID) . ", " . "Email:" . htmlentities($email) . ", "  . "Username:" . htmlentities($username) . ", Type:" . htmlentities($userType) . "</p>";
}

function reanderHeadings($text, $level)
{
    # Funtion to render headings
    return "<h" . $level . '>' . htmlentities($text) . "</h" . $level . '>';
}

function renderParagraph($text)
{
    # Function to render paragraphs
    return '<p>' . htmlentities(trim($text)) . '</p>';
}

function renderUsersFromDatabase($pdo, $tableName)
{
    # Query to extract all users from database
    $query = "SELECT userID, userType, username, email FROM $tableName";
    $rows = queryDatabase($pdo, $query);

    # Check if there are any users in the database
    if (count($rows) === 0) {
        $error_message = "No users stored in the database";
        echo renderParagraph($error_message);
        return;
    }

    # Render users
    foreach ($rows as $row => $value) {
        $userID = $value['userID'];
        $username = $value['username'];
        $userType = $value['userType'];
        $email = $value['email'];

        echo renderUser($userID, $email, $username, $userType);
    }
}

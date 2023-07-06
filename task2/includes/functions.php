<?php
function connectToDatabase($host,$db, $user, $password){
			# Connect to the database
			try{
				$dsn = "mysql:host=$host;dbname=$db";
				$pdo = new PDO($dsn, $user, $password);
				// echo "<p>Connected to the $db database</p>";
			}
			catch(PDOException $e){
				echo "<p>Unable to connect to the database: " . $e->getMessage() . "</p>";
				exit;
			}

			return $pdo;
		}

function createTable($pdo, $table_name){
    # Check if the table exists
    $sql = "SHOW TABLES like '$table_name'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    if($stmt->rowCount() <= 0) {

        # Table does not exist
        echo "<p>Table $table_name does not exist</p>";
        # Create table
        $sql = "CREATE TABLE $table_name (
            moduleCode VARCHAR(2) NOT NULL,
            studentID INT(8)  NOT NULL PRIMARY KEY,
            moduleResult INT(3) NOT NULL
        )";
        $result = $pdo->prepare($sql);
        $result->execute();

        if($result === FALSE){
            echo "<p>Error creating table $table_name: " . $pdo->errorInfo()[2] . "</p>";
        }

    }
}

function insertFromDataToDatabase($pdo, $table_name,$data_file_name){

    $open = fopen($data_file_name, "r");
    if ($open === FALSE) {
        echo "<p>Failed to open file</p>";
        exit;
    }

    while (($data = fgetcsv($open, 1000, ",")) !== FALSE)
    {

        $moduleCode = $data[0];
        $studentID = $data[1];
        $moduleResult = $data[2];

        // Check if the record already exists
        $stmt = $pdo->prepare("SELECT * FROM $table_name
                                WHERE moduleResult = :moduleResult AND studentID = :studentID");
        $stmt->execute([':moduleResult' => $moduleResult, ':studentID' => $studentID]);

        if($stmt->rowCount() == 0) {
            // Insert the data into the database
            $stmt = $pdo->prepare("INSERT INTO moduleResults (moduleCode, studentID, moduleResult)
                                                        VALUES (:moduleCode, :studentID, :moduleResult)");
            $stmt->execute([':moduleCode' => $moduleCode, ':studentID' => $studentID, ':moduleResult' => $moduleResult]);
            }
    }
    
    fclose($open);
}

function renderRows($name,$value){
    echo "<tr><td>" . $name . "</td><td>" . $value . "</td></tr>";
}

function calculateStatistics($pdo, $tableName){

    $classifications = [
            '1st' => ['min' => 70, 'max' => 101],
            '2.1' => ['min' => 60, 'max' => 70],
            '2.2' => ['min' => 50, 'max' => 60],
            '3rd' => ['min' => 45, 'max' => 50],
            'Pass' => ['min' => 40, 'max' => 45],
            'Fail' => ['min' => 0, 'max' => 40]
        ];

    try {
        
        echo "<table>";
        echo "<tr><th>Static</th><th>Number</th></tr>";

        foreach($classifications as $name => $range) {
            $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM $tableName 
                                    WHERE moduleResult >= :min AND moduleResult < :max");
            $stmt->execute([':min' => $range['min'], ':max' => $range['max']]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            renderRows($name,$row['count']);
        }

        $stmt = $pdo->prepare("SELECT AVG(moduleResult) as AverageMark FROM $tableName");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        renderRows("Average Mark",$row['AverageMark']);

        $stmt = $pdo->prepare("SELECT count(*) as TotalStudents FROM $tableName");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        renderRows("Total Students",$row['TotalStudents']);

        echo "</table>";

    }catch(PDOException $e){
        echo "<p>Unable to connect to the database: " . $e->getMessage() . "</p>";
        exit;
    }
}

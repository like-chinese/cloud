<?php

// Attempt to connect to the MySQL database
$conn = mysqli_connect("assignment-db.creskwyc4zzz.us-east-1.rds.amazonaws.com", "main", "assignmentPassword", "assignment");

// Check connection
if (mysqli_connect_errno()) {
    // Connection failed, display an error message
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit(); // Exit the script to prevent further execution
}

// Connection successful
// You can place further code here that needs database connection

//$db_name = 'mysql:host=localhost;dbname=cloud';
//$user_name = 'root';
//$user_password = '';

//$conn = new PDO($db_name, $user_name, $user_password);


?>







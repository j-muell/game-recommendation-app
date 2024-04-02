<?php
session_start();
// Database connection settings
$host = 'localhost';
$username = 'root';
$password = 'rootPassword';
$database = 'gamequest';

// Connect to the database
$connection = new mysqli($host, $username, $password, $database, 3306);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Retrieve posted data from wishlist.php
$gameName = $_POST['gameName'];
$userID = $_SESSION['userID'];

// Prepare and execute the SQL query to insert data into wishlist table
$query = "INSERT INTO wishlist (userUid, gameName) VALUES (?, ?)";
$stmt = $connection->prepare($query);
$stmt->bind_param("is", $userID, $gameName);
$stmt->execute();

// Check if the insertion was successful
if ($stmt->affected_rows > 0) {
    echo "Data inserted successfully!";
    sleep(2);
    header("location: ../components/wishlist.php");
    exit();
} else {
    echo "Failed to insert data.";
}

// Close the database connection
$stmt->close();
$connection->close();
sleep(2);
header("location: ../components/wishlist.php");
exit();

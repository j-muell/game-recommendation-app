<?php
session_start();
// Database connection settings
$host = 'localhost';
$username = 'root';
$password = 'rootPassword';
$database = 'gamequest';

// Connect to the database
$conn = new mysqli($host, $username, $password, $database, 3306);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve posted data from wishlist.php
$igdbId = $_POST['wishlistGameId'];
$userID = $_SESSION['userID'];

// Prepare and execute the SQL query to insert data into wishlist table
$query = "INSERT INTO wishlist (userUid, igdbGameId) VALUES (?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $userID, $igdbId);
$stmt->execute();

$affectedRows = $stmt->affected_rows;

$stmt->close();
// Check if the insertion was successful
if ($affectedRows > 0) {
    echo 'true';
} else {
    echo 'false';
}

// Close the database connection

$conn->close();
exit();

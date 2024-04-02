<?php

$gameName = urldecode($_POST['gameName']);

echo $gameName;

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "rootPassword";
$dbname = "gamequest";

$conn = new mysqli($servername, $username, $password, $dbname, 3306);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Search for the game in the wishlist table
$stmt = $conn->prepare("SELECT * FROM wishlist WHERE igdbGameId IS NULL AND gameName = ?");
$stmt->bind_param("s", $gameName);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Game found, remove it from the database
    $stmt = $conn->prepare("DELETE FROM wishlist WHERE igdbGameId IS NULL AND gameName = ?");
    $stmt->bind_param("s", $gameName);
    if ($stmt->execute()) {
        echo "Game removed from wishlist successfully";
    } else {
        echo "Error removing game from wishlist: " . $conn->error;
    }
} else {
    // Game not found in the wishlist
    echo "Game not found in wishlist";
}

// Close the database connection
$conn->close();

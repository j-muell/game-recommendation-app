<?php
session_start();

$userUid = isset($_SESSION['userID']) ? $_SESSION['userID'] : null;

$gameData = isset($_POST['gameData']) ? urldecode($_POST['gameData']) : null;


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

if (isset($gameData) && isset($userUid)) {

    if (!is_numeric($gameData)) {
        $stmt = $conn->prepare("SELECT * FROM wishlist WHERE igdbGameId IS NULL AND gameName = ? AND userUid = ?");
        $stmt->bind_param("si", $gameData, $userUid);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Game found, remove it from the database
            $stmt = $conn->prepare("DELETE FROM wishlist WHERE igdbGameId IS NULL AND gameName = ? AND userUid = ?");
            $stmt->bind_param("si", $gameData, $userUid);
            if ($stmt->execute()) {
                echo "Game removed from wishlist successfully";
            } else {
                echo "Error removing game from wishlist: " . $conn->error;
            }
        }
    } else {
        // gameData is numeric, so it is an igdbGameId
        $stmt = $conn->prepare("SELECT * FROM wishlist WHERE igdbGameId IS NOT NULL AND igdbGameId = ? AND userUid = ?");
        $stmt->bind_param("si", $gameData, $userUid);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Game found, remove it from the database
            $stmt = $conn->prepare("DELETE FROM wishlist WHERE igdbGameId IS NOT NULL AND igdbGameId = ? AND userUid = ?");
            $stmt->bind_param("si", $gameData, $userUid);
            if ($stmt->execute()) {
                echo "Game removed from wishlist successfully";
            } else {
                echo "Error removing game from wishlist: " . $conn->error;
            }
        } else {
            echo "Game not found in the wishlist";
        }
    }
}
// Close the database connection
$conn->close();

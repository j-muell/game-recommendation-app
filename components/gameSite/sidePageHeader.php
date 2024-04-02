<?php
session_start();
if (!isset($_SESSION["userID"])) {
    header("location: landing.php");
    exit();
}
include("../functions/steamapi/SteamAPI.class.php");
$steamAPI = new SteamAPI();

if (isset($_SESSION["userSteamID"])) {
    $results = $steamAPI->GetPlayerInfo($_SESSION["userSteamID"]);
}

foreach ($results as $player) {
    $steamName = $player['personaname'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/game-recommendation-app/styles/wishlist.css" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <title>GameQuest</title>
</head>

<body>
    <nav>
        <h2><a href="/game-recommendation-app/components/landing.php">GameQuest</a></h2>
        <ul>
            <li><a href="/game-recommendation-app/components/index.php">Home</a></li>
            <li><a href="/game-recommendation-app/components/about.php">About</a></li>
            <li><a href="/game-recommendation-app/components/how_to_use.php">How to use</a></li>
            <li><a href="/game-recommendation-app/includes/logout.inc.php">Logout</a></li>
            <li class="message">Hello, <?php echo !empty($steamName) ? $steamName : 'User'; ?></li>
        </ul>
    </nav>
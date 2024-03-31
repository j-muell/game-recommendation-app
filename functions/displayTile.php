<?php

require_once "steamapi/SteamAPI.class.php";
require_once "igdb/src/class.igdb.php";
require_once "../includes/igdb-inc.php";

// testing some functions to see the functionality

$tileDisplayOne = displayTile("Red Dead Redemption 2");
$tileDisplayTwo = displayTile("Hollow Knight");
$tileDisplayThree = displayTile("Overwatch");
$tileDisplayFour = displayTile("Counter-Strike 2");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Tiles</title>
    <!-- <link rel="stylesheet" href="../styles/tile.css"> -->
</head>

<body>

    <div class="grid-container">
        <?php
        echo $tileDisplayOne;
        echo $tileDisplayTwo;
        echo $tileDisplayThree;
        echo $tileDisplayFour;
        ?>
    </div>

</body>

</html>
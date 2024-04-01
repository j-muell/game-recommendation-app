<?php

include('gameSite/indexHeader.php');
require_once "../functions/steamapi/SteamAPI.class.php";
require_once "../functions/igdb/src/class.igdb.php";
require_once "../includes/igdb-inc.php";










$tileDisplayOne = displayTile("Red Dead Redemption 2");
$tileDisplayTwo = displayTile("Hollow Knight");
$tileDisplayThree = displayTile("Overwatch");
$tileDisplayFour = displayTile("Counter-Strike 2");
$tileDisplayFive = displayTile("Paladins");
$tileDisplaySix = displayTile("Dragons Dogma");
$tileDisplaySeven = displayTile("Sons of the Forest");
$tileDisplayEight = displayTile("Binding of Isaac");
$tileDisplayNine = displayTile("Elden Ring");

?>
<head>
    <link rel="stylesheet" href="../styles/index.css">
</head>
<div class="search-first-container">
    <form action="" method="POST" class="search-bar-container">
        <input type="text" name="search" class="search-bar" id="searchInput" placeholder="Search for a game...">
        <button type="submit" class="search-button">
            <i class='bx bx-search'></i>
        </button>
    </form>
</div>
<div class="search-second-container">
    <?php
    if (!empty($_POST["search"])) {
        $searchTerm = $_POST["search"];
        $games = gameSearchForNameAndImage($searchTerm, $amount = 5);
        if (!empty($games)) {
            echo "<div id='searchResults' class='search-results'>";
            foreach ($games as $game) {
                echo "<div class='game-result'>";
                echo "<img src='{$game['Cover']}' alt='{$game['Name']}' class='game-cover'>";
                echo "<span>{$game['Name']}</span>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<div id='searchResults' class='search-results'>";
            echo "<p>No games found matching your search.</p>";
            echo "</div>";
        }
    }
    ?>
</div>






?>

    <div class="grid-container">
        <?php
        echo $tileDisplayOne;
        echo $tileDisplayTwo;
        echo $tileDisplayThree;
        echo $tileDisplayFour;
        echo $tileDisplayFive;
        echo $tileDisplaySix;
        echo $tileDisplaySeven;
        echo $tileDisplayEight;
        echo $tileDisplayNine;

        ?>
    </div>
</div>


<!-- <style>
    html,
    body {
        background-color: #271c19;
        margin: 0;
        padding: 0;
        height: 100%;
    }
</style> -->
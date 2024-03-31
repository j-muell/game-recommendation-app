<?php

include('gameSite/indexHeader.php');

require_once "../functions/steamapi/SteamAPI.class.php";
require_once "../functions/igdb/src/class.igdb.php";
require_once "../includes/igdb-inc.php";




// testing some functions to see the functionality

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
<div class="background">
    <div class="search-container">
        <input type="text" class="search-bar" placeholder="Search for a game...">
        <button class="search-button">
            <i class='bx bx-search'></i>
        </button>
    </div>
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
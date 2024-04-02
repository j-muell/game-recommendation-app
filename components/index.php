<?php


include('gameSite/indexHeader.php');
require_once "../functions/steamapi/SteamAPI.class.php";
require_once "../functions/igdb/src/class.igdb.php";
require_once "../includes/igdb-inc.php";
// require "../includes/retrieveGame-inc.php";


if (isset($_SESSION['games'])) {
    $gameTiles = $_SESSION['games'];
}

?>
<head>
    <link rel="stylesheet" href="../styles/index.css">
</head>

<div class="search-container">
    <form action="" method="POST" class="search-bar-container">
        <input type="text" name="search" class="search-bar" id="searchInput" placeholder="Search for a game...">
        <button type="submit" class="search-button">
            <i class="bx bx-search"></i>
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
        ?>
    </div>
</div>

<div class="background">
  <div class="grid-container" id="grid">
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


<script>
    $(document).ready(function() {
        // Function to send AJAX request
        function sendRequest() {
            $.post("../includes/retrieveGame-inc.php", $("#filter-form").serialize(), function(data) {
                $(".grid-container").html(data);
            });
        }

        // Send AJAX request when form is submitted
        $("#filter-form").on("submit", function(event) {
            event.preventDefault();
            sendRequest();
        });

        // Send AJAX request when page loads
        sendRequest();
    });
</script>
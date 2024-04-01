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

<div class="search-container">
    <input type="text" class="search-bar" placeholder="Search for a game...">
    <button class="search-button">
        <i class='bx bx-search'></i>
    </button>
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
                $(".submit-button button").removeClass("loading");
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
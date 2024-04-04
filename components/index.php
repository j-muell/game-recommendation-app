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
    <div class="search-bar-container">
        <input type="text" class="search-bar" placeholder="Search for a game...">
        <button class="search-button" onclick="Search()">
            <i class='bx bx-search'></i>
        </button>
    </div>

    <div class="search-results">

    </div>
</div>

<!-- used for blurring under search -->
<div class="overlay">
</div>



<div class="grid-container" id="grid">
</div>



<!-- THIS IS FOR LATER. MAKING THE SEARCH OVERLAY ON TOP OF THE CONTENT. THIS WILL MAKE IT SO YOU click -->
<!-- ANYWHERE OUTSIDE THE DISPLAY TO CLOSE THE ENTIRE OVERLAY. -->

<!-- // Get the overlay and search results elements
var overlay = document.querySelector('.overlay');
var searchResults = document.querySelector('.search-results');

// Add a click event listener to the overlay
overlay.addEventListener('click', function() {
  overlay.style.display = 'none'; // Hide the overlay
});

// Add a click event listener to the search results container
searchResults.addEventListener('click', function(event) {
  event.stopPropagation(); // Stop the click event from propagating up to the overlay
}); -->



<script>
    $(document).ready(function() {
        // Function to send AJAX request
        function sendRequest() {
            $.post("../includes/retrieveGame-inc.php", $("#filter-form").serialize(), function(data) {
                $(".grid-container").html(data);
                $(".submit-button button").removeClass("loading");

                $('.tile-wrapper').each(function() {
                    var gameId = $(this).data('game-id');
                    $(this).append("<i class='bx bxs-bookmark-alt-plus' onclick='addToWishlistFromIndex(\"" + gameId + "\")'></i>");
                });

            });
        }

        // Send AJAX request when form is submitted
        $("#filter-form").on("submit", function(event) {
            event.preventDefault();
            sendRequest();
        });

        // Send AJAX request when page loads
        sendRequest();


        var overlay = document.querySelector('.overlay');
        var searchResults = document.querySelector('.search-results');

        // Add a click event listener to the overlay
        overlay.addEventListener('click', function() {
            overlay.style.display = 'none'; // Hide the overlay
            searchResults.classList.remove('open'); // Close the search results
        });

        // Add a click event listener to the search results container
        searchResults.addEventListener('click', function(event) {
            event.stopPropagation(); // Stop the click event from propagating up to the overlay
        });


    });

    function addToWishlistFromIndex(gameId) {
        $.post("../includes/addToWishlistFromIndex-inc.php", {
            wishlistGameId: gameId
        }, function(response) {
            if (response.trim() === 'true') {
                alert("Game added to wishlist.");
            } else {
                alert("Error adding game to wishlist.");
            }

        });
    }

    function Search($gameId) {
        var searchInput = document.querySelector('.search-bar').value;
        var search = $(".search-bar").val();
        $.post("../includes/search-inc.php", {
            search: search
        }, function(data) {

            $(".search-results").empty();
            var games = data.split("<!--delimiter-->")

            $.each(games, function(index, game) {
                console.log(game);
                $(".search-results").append(game);
            });

            $(".search-results").addClass("open");
            $(".overlay").addClass("active");
            $(".overlay").css("display", "block");

        });
    }
</script>
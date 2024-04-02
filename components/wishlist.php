<?php

include 'gameSite/sidePageHeader.php';
require_once '../includes/igdb-inc.php';

// Connect to the database
$mysqli = new mysqli('localhost', 'root', 'rootPassword', 'gamequest');

// Check connection
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

?>
<div class="wishlist-info">
    <h1>Welcome to your wishlist, <?php echo $steamName ?>!</h1>
    <h4>Below you will find all of your wishlisted games.</h4>
    <p>If you would like to add your own personal entry into your wishlist, you can do so at any time by clicking 'Add to Wishlist'.</p>
</div>
<div class="separator-container">
    <div class="wishlist-separator">
        <h2>Games added to your wishlist through GameQuest</h2>
    </div>

</div>


<?php

$wishlistTilesWithId = [];
$wishlistTilesNoId = [];

// Retrieve games with igdbUserId from the database
$userID = $_SESSION['userID'];
$queryWithUserId = "SELECT * FROM wishlist WHERE igdbGameId IS NOT NULL AND userUid = '$userID'";
$resultWithUserId = $mysqli->query($queryWithUserId);

if ($resultWithUserId->num_rows > 0) {
    while ($game = $resultWithUserId->fetch_assoc()) {

        if ($game != null) {
            $wishlistTilesWithId[] = displayTile($game['igdbGameId']);
        } else {
            break;
        }
    }
} else {
    echo '<div class="error-message"><h5>No games found within your wishlist added through GameQuest.</h5></div>';
}
?>


<div class="grid-container">
</div>


<div class="wishlist-form-container">
    <div class="popup-container">
        <i class='bx bx-x'></i>
        <form id="wishlist" action="../includes/addToWishlist-inc.php" method="post">
            <input type="text" name="gameName" placeholder="Name of game...">
            <input type="submit" name="submit" value="Add">
        </form>
    </div>
    <button class="manual-add">Add to Wishlist</button>
    <script>
        var button = document.querySelector('.manual-add');
        var popupContainer = document.querySelector('.popup-container');
        var closeIcon = document.querySelector('.popup-container i');

        button.addEventListener('click', function() {
            popupContainer.classList.add('open');
            button.style.display = 'none';
        });

        closeIcon.addEventListener('click', function() {
            popupContainer.classList.remove('open');
            button.style.display = 'block';
        });
    </script>
</div>
<div class="separator-container">
    <div class="wishlist-separator">

        <h2>Games added to your wishlist manually</h2>
    </div>
</div>

<?php
// Retrieve games without igdbUserId from the database
$queryWithoutUserId = "SELECT * FROM wishlist WHERE igdbGameId IS NULL AND userUid = '$userID'";
$resultWithoutUserId = $mysqli->query($queryWithoutUserId);

if ($resultWithoutUserId->num_rows > 0) {
    while ($game = $resultWithoutUserId->fetch_assoc()) {
        // Display game details
        if ($game != null) {
            $gameName = $game['gameName'];

            $results = gameSearchForNameAndImage($gameName);
            if ($results != null && !empty($results)) {

                $html = "<div class='simple-tile-wrapper'>";
                $html .= "<i class='bx bx-x' onclick='removeFromWishlist(\"{$gameName}\")'></i>";
                $html .= "<div class='simple-topper'>";
                $html .= "<img class='simple-game-image' src='{$results[0]['Cover']}'>";
                $html .= "<h3 class='game-title'>{$results[0]['Name']}</h3>";
                $html .= "</div>";
                $html .= "</div>";

                $wishlistTilesNoId[] =  $html;
            } else {
                $html = "<div class='simple-tile-wrapper'>";
                $html .= "<i class='bx bx-x' onclick='removeFromWishlist(\"{$gameName}\")'></i>";
                $html .= "<div class='simple-topper'>";
                $html .= "<img class='simple-game-image' src='https://images.igdb.com/igdb/image/upload/t_logo_med/nocover.png'>";
                $html .= "<h3 class='game-title'>There was an error displaying this game.</h3>";
                $html .= "</div>";
                $html .= "</div>";

                $wishlistTilesNoId[] = $html;
            }
        }
    }
} else {
    echo '<div class="error-message"><h5>No games added by you manually.</h5></div>';
}
?>

<script>
    function removeFromWishlist(gameName) {
        // Create a new AJAX request
        console.log('clicked')
        var xhr = new XMLHttpRequest();

        // Configure the request
        xhr.open('POST', '../includes/removeFromWishlist-inc.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

        // Handle the response
        xhr.onload = function() {
            if (xhr.status == 200) {
                // If the request was successful, remove the item from the DOM or refresh the page
                location.reload();
            }
        };

        // Send the request with the name of the game to be deleted
        xhr.send('gameName=' + encodeURIComponent(gameName));
    }
</script>
<div class="grid-container">
    <!-- this is where I will display the game tiles. -->
    <?php
    if (!empty($wishlistTilesNoId)) {
        foreach ($wishlistTilesNoId as $tile) {
            echo $tile;
        }
    }

    ?>
</div>

<?php

include 'gameSite/sidePageFooter.php';
?>
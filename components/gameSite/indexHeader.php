<?php
session_start();
if (!isset($_SESSION["userID"])) // checks is userID is set. This is not username. Username is userUsername.
{
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
    $avatarLink = $player['avatar'];
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../styles/indexSidebar.css" />
    <link rel="stylesheet" href="../../styles/index.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <script src="../scripts/index.js" defer></script>
    <title>GameQuest</title>
</head>

<body>
    <div class="sidebar">
        <div class="logo-details">
            <i class='bx bx-dice-6'></i>
            <a href="index.php">
                <span class="logo-name">GameQuest</span>
            </a>
            <div class="menu-collapse">
                <i class='bx bx-menu'></i>
            </div>
        </div>
        <ul class="sidebar-content">
            <!-- entire sidebar exists inside this -->
            <li class="info-link">
                <div class="icon-link">
                    <a href="#">
                        <i class='bx bx-info-square'></i>
                        <span class="link-name">Info & Wishlist</span>
                    </a>
                    <i class='bx bxs-chevron-down arrow'></i>
                </div>
                <ul class="sub-menu">
                    <li><a class="link-name" href="#">About</a></li>
                    <li><a href="#">How to use</a></li>
                    <li><a href="#">Contact</a></li>
                    <li><a href="#">Wish-list</a></li>
                </ul>
            </li>
            <li class="separator">
                <a href="#">
                    <i class='bx bx-category-alt'></i>
                    <span class="link-name">Categories</span>
                </a>
            </li>
            <form action="../includes/filters-inc.php" method="post">
                <ul class="genre-container">
                    <li class="genre">
                        <input type="checkbox" name="shooter" id="shooter">
                        <label for="shooter">Shooter</label>
                    </li>
                    <li class="genre">
                        <input type="checkbox" name="rpg" id="rpg">
                        <label for="rpg">RPG</label>
                    </li>
                    <li class="genre">
                        <input type="checkbox" name="indie" id="indie">
                        <label for="indie">Indie</label>
                    </li>
                    <li class="genre">
                        <input type="checkbox" name="singeplayer" id="singeplayer">
                        <label for="singeplayer">Singleplayer</label>
                    </li>
                    <li class="genre">
                        <input type="checkbox" name="multiplayer" id="multiplayer">
                        <label for="multiplayer">Multiplayer</label>
                    </li>
                    <li class="genre">
                        <input type="checkbox" name="platformer" id="platformer">
                        <label for="platformer">Platformer</label>
                    </li>
                    <li class="genre">
                        <input type="checkbox" name="adventure" id="adventure">
                        <label for="adventure">Adventure</label>
                    </li>
                    <li class="genre">
                        <input type="checkbox" name="survival" id="survival">
                        <label for="survival">Survival</label>
                    </li>
                    <li class="genre">
                        <input type="checkbox" name="puzzle" id="puzzle">
                        <label for="puzzle">Puzzle</label>
                    </li>
                    <li class="genre">
                        <input type="checkbox" name="roguelike" id="roguelike">
                        <label for="roguelike">Rogue-Like</label>
                    </li>
                    <li class="genre">
                        <input type="checkbox" name="soulslike" id="soulslike">
                        <label for="soulslike">Souls-Like</label>
                    </li>
                    <li class="genre">
                        <input type="checkbox" name="metroidvania" id="metroidvania">
                        <label for="metroidvania">Metroidvania</label>
                    </li>
                    <li class="genre">
                        <input type="checkbox" name="horror" id="horror">
                        <label for="horror">Horror</label>
                    </li>
                    <li class="genre">
                        <input type="checkbox" name="action" id="action">
                        <label for="action">Action</label>
                    </li>
                    <li class="genre">
                        <input type="checkbox" name="deckbuilder" id="deckbuilder">
                        <label for="deckbuilder">Deckbuilder</label>
                    </li>
                    <li class="genre">
                        <input type="checkbox" name="strategy" id="strategy">
                        <label for="strategy">Strategy</label>
                    </li>
                </ul>
            </form>
        </ul>

        <div class="profile-details">
            <div class="profile-content">
                <img src="<?php echo $avatarLink ?>" alt="Avatar">
            </div>
            <div class="details">
                <div class="steam-name"><?php echo $steamName ?></div> <!-- This will grab steam name in the future.-->
            </div>
            <a href="../includes/logout.inc.php"><i class="bx bx-log-out"></i></a>
        </div>

    </div>
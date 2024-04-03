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

$genres = [
    2 => 'Point-and-click',
    4 => 'Fighting',
    5 => 'Shooter',
    7 => 'Music',
    8 => 'Platform',
    9 => 'Puzzle',
    10 => 'Racing',
    11 => 'Real Time Strategy (RTS)',
    12 => 'Role-playing (RPG)',
    13 => 'Simulator',
    14 => 'Sport',
    15 => 'Strategy',
    16 => 'Turn-based strategy (TBS)',
    24 => 'Tactical',
    25 => 'Hack and Slash',
    26 => 'Quiz/Trivia',
    30 => 'Pinball',
    31 => 'Adventure',
    32 => 'Indie',
    33 => 'Arcade',
    34 => 'Visual Novel',
    35 => 'Card & Board Game',
    36 => 'MOBA'
];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../styles/indexSidebar.css" />
    <link rel="stylesheet" href="../styles/index.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../scripts/index.js" defer></script>
    <title>GameQuest</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        * {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit,
                    minmax(400px, 1fr));
            /* Three columns */
            gap: 0px;
            /* Gap between tiles */
            max-width: 86%;
            float: right;
        }

        .game-title {
            font-weight: 500;
            color: #ffc0ad;
            text-align: center;
        }

        .topper {
            display: flex;
        }

        .tile-wrapper {
            position: relative;
            background: #55423d;
            border-radius: 16px;
            padding: 1.25rem;
            margin: 1rem;
            max-width: 400px;
        }

        .tile-wrapper i {
            position: absolute;
            top: 10px;
            right: 10px;
            color: #fff3ec;
            font-size: 1.5rem;
            transition: all 0.3s ease;
        }

        .tile-wrapper i:hover {
            color: #ffc0ad;
            cursor: pointer;
        }


        .topper img {
            border-radius: 1rem;
            margin-bottom: 6px;
            width: calc(100% / 3 - 20px);
        }

        .title-rating-price {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 25px;
            color: #fff3ec;
        }

        .summary,
        .genre-list {
            color: #fff3ec;
        }

        .genre-list {
            text-decoration: underline;
        }

        .summary {
            font-size: 14px;
        }

        .game-rating,
        .game-price {
            font-weight: 400;
        }

        .submit-button {
            display: flex;
            justify-content: center;
        }

        .submit-button button {
            background-color: #ffc0ad;
            color: #271c19;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 20px 0;
            position: relative;
            transition: background-color 0.3s ease;
        }

        .submit-button button:hover {
            background-color: #ff9e80;
        }

        .submit-button button.loading {
            /* Hide the button text */
            color: transparent;
        }

        .submit-button button.loading:after {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 20px;
            height: 20px;
            border: 2px solid #271c19;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }



        @keyframes spin {
            0% {
                transform: translate(-50%, -50%) rotate(0deg);
            }

            100% {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }
    </style>
    <script>
        $(document).ready(function() {
            $(".submit-button button").click(function() {
                $(this).addClass("loading");
            });
        });
    </script>
</head>

<body>
    <div class="sidebar open">
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
                    <li><a class="link-name" href="aboutLoggedIn.php">About</a></li>
                    <li><a href="howToUseLoggedIn.php">How to use</a></li>
                    <li><a href="contactUs.php">Contact</a></li>
                    <li><a href="wishlist.php">Wish-list</a></li>
                </ul>
            </li>
            <li class="separator">
                <a href="#">
                    <i class='bx bx-category-alt'></i>
                    <span class="link-name">Categories</span>
                </a>
            </li>
            <form action="../includes/retrieveGame-inc.php" method="post" id="filter-form">
                <ul class="genre-container">
                    <?php
                    foreach ($genres as $id => $genre) {
                        echo "<li class=\"genre\">
                            <input type=\"checkbox\" name=\"$id\" id=\"$id\">
                            <label for=\"$id\">$genre</label>
                        </li>";
                    }
                    ?>

                </ul>
                <div class="submit-button">
                    <button type="submit" name="submit">Apply Filters</button>
                </div>
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

    <div class="sidebar-mini close">
        <div class="logo-details">
            <i class='bx bx-menu' id="minisidebar"></i>
        </div>

        <ul class="sidebar-content close">
            <li class="info-link">
                <div class="icon-link">
                    <a href="#">
                        <i class='bx bx-info-square'></i>
                    </a>
                </div>
                <ul class="sub-menu">
                    <li><a class="link-name" href="aboutLoggedIn.php">About</a></li>
                    <li><a href="howToUseLoggedIn.php">How to use</a></li>
                    <li><a href="contactUs.php">Contact</a></li>
                    <li><a href="wishlist.php">Wish-list</a></li>
                </ul>
            </li>
        </ul>
        <div class="profile-details">
            <div class="profile-content">
                <img src="<?php echo $avatarLink ?>" alt="Avatar">
            </div>
            <a href="../includes/logout.inc.php"><i class="bx bx-log-out"></i></a>
        </div>
    </div>
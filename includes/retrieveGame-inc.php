<?php
// IF SESSION HAS THE STEAM ID SET, USE THAT, OTHERWISE USE THE USER ID TO GET THE STEAM ID THROUGH THE DATABASE
include_once 'igdb-inc.php';
include_once '../functions/steamapi/SteamAPI.class.php';
include_once '../functions/igdb/src/class.igdb.php';
if (!isset($_SESSION)) {
    session_start();
}

function retrieveGame()
{
    if (empty($_POST)) {
        if (isset($_SESSION['userSteamID'])) {
            $steamid = $_SESSION['userSteamID'];
        } else {
            if (isset($_SESSION['userID'])) {
                $userID = $_SESSION['userID'];
            }
            // Create connection (MySQLi object-oriented
            $mysqli = new mysqli("localhost", "root", "rootPassword", "gamequest");

            if ($mysqli->connect_errno) {
                // Handle connection error
                die("Failed to connect to MySQL: " . $mysqli->connect_error);
            }

            $query = "SELECT userUid FROM users WHERE userUid = $userID";
            $result = $mysqli->query($query);


            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $userSteamID = $row['userSteamID'];
            } else {
                // Handle user not found
                die("User not found");
            }

            $mysqli->close();
        }

        $steamAPI = new SteamAPI();

        $steamid = $steamAPI->resolveURL($steamid);

        if ($steamid === false) {
            // Handle invalid Steam ID
            die("Invalid Steam ID");
        }

        $recentGames = $steamAPI->getRecentPlayedGames($steamid, 3);


        $games = $recentGames[0]['games'];


        $gameData = [];

        foreach ($games as $game) {
            if ($game['appid'] == 431960) {
                continue;
            }
            $name = $game['name'];
            $playtime = $game['playtime_2weeks_hours'];

            $gameData[] = [
                'name' => $name,
                'playtime' => $playtime
            ];
        }

        // print_r($gameData[0]['name']);

        $similarGames = [];
        foreach ($gameData as $game) {
            $gameName = $game['name'];
            $gameName = preg_replace('/[^a-zA-Z0-9\s\-:\']/', '', $gameName); // Strip special characters
            $gameId = getGameID($gameName);
            if (empty($gameId)) {
                continue;
            }
            $gameInfo = allGameInfo($gameId);
            $similarGames[] = array(
                'gameName' => $gameName,
                'gameSimilarGames' => $gameInfo[0]['similar_games']
            );

            // Process similar games
        }

        // Sort $gameData based on highest playtime first
        usort($gameData, function ($a, $b) {
            return $b['playtime'] - $a['playtime'];
        });

        // print_r($gameData);

        // Get similar games for the first element of $gameData

        $firstGame = $gameData[0];
        $firstGameName = $firstGame['name'];
        $firstGameSimilarGames = [];

        // print_r($similarGames);

        foreach ($similarGames as $game) {
            if ($game['gameName'] === $firstGameName) {
                $firstGameSimilarGames = $game['gameSimilarGames'];
                break;
            }
        }

        // Get 4 similar games for the first element
        $firstGameSimilarGames = array_slice($firstGameSimilarGames, 0, 4);

        // Get 2 similar games for the remaining games
        $remainingGamesSimilarGames = [];

        for ($i = 1; $i < count($gameData); $i++) {
            $gameName = $gameData[$i]['name'];
            $gameSimilarGames = [];

            foreach ($similarGames as $game) {
                if ($game['gameName'] === $gameName) {
                    $gameSimilarGames = $game['gameSimilarGames'];
                    break;
                }
            }

            $remainingGamesSimilarGames[$gameName] = array_slice($gameSimilarGames, 0, 2);
        }

        $displayTiles = [];

        foreach ($firstGameSimilarGames as $gameId) {
            $displayTiles[] = displayTile($gameId);
        }

        foreach ($remainingGamesSimilarGames as $gameSimilarGames) {
            foreach ($gameSimilarGames as $gameId) {
                $displayTiles[] = displayTile($gameId);
            }
        }

        return implode('', $displayTiles);
        // $_SESSION['games'] = $displayTiles;

        // Case when filter-submit is set
    } else {
        $selectedGenres = [];
        for ($i = 2; $i <= 36; $i++) {
            if (isset($_POST[$i])) {
                $selectedGenres[] = $i;
            }
        }

        $displayTiles = [];

        for ($i = 0; $i < 8; $i++) {
            $displayTiles[] = displayTile($selectedGenres);
        }

        // $_SESSION['games'] = $displayTiles;

        return implode('', $displayTiles);
    }
}

echo retrieveGame();

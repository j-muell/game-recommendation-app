<?php
/*

    LIST OF FUNCTIONS

    gameSearchForNameAndImage($gameInput, $amountOfGames)           igdb
    allGameInfo($gameID, $totalRatingRequest = 60, $limit = 1)      igdb
    gameNameAndGenre($gameInput, $limit = 1)
    getSteamAppIdIfExists($gameInput, $limit = 1)
    getGameID($gameInput)                               returns igdb game id

*/


require_once "../functions/igdb/src/class.igdb.php";
require_once "../functions/steamapi/SteamAPI.class.php";

$steamAPI = new SteamAPI();
$builder = new IGDBQueryBuilder();

$config = parse_ini_file('config.ini', true);

$client_id = $config['IGDB']['client_id'];
$client_secret = $config['IGDB']['client_secret'];

$url = 'https://id.twitch.tv/oauth2/token';

$data = array(
    'client_id' => $client_id,
    'client_secret' => $client_secret,
    'grant_type' => 'client_credentials'
);

$curl = curl_init();

// SET CURL OPTIONS

curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query($data), // send in our data built as an associative array
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/x-www-form-urlencoded'
    )
));


$response = curl_exec($curl);
curl_close($curl);

if ($response === false) {
    echo 'Error' . curl_error($curl);
} else {
    $json_response = json_decode($response, true);

    if (isset($json_response['access_token'])) { // Search for access token in the json response
        $access_token = $json_response['access_token'];
        $expires_in = $json_response['expires_in'];
    } else {
        echo 'Error: ' . $json_response['error'] . ', ' . $json_response['error_description'];
    }
}


$igdb = new IGDB($client_id, $access_token);
$builder = new IGDBQueryBuilder();

/* 
    The following functions are used for returning anything about games including the following:
        id, name, genres.name (name of the genre), player_perspectives.name, similar_games.name, 
        total_rating, websites, cover.*

        Each function will preface with what it will return. Each return will be as an associative array
        with fully parsed json content.
*/


function retreiveGames($genre)
{
}


// This function will return as string with echo content to display in a separate file. Takes game data, either igdb id or string.
function displayTile($gameData)
{

    global $igdb, $builder, $steamAPI;

    if (!empty($gameData)) {
        try {
            if (is_numeric($gameData)) {
                $query = $builder
                    ->fields("name, cover.*, genres.name, summary, total_rating")
                    ->where("id = $gameData")
                    ->where("category = 0")
                    ->limit(1)
                    ->build();
            } else {
                $query = $builder
                    ->search($gameData)
                    ->fields("name, cover.*, genres.name, summary, total_rating")
                    ->where("category = 0")
                    ->limit(1)
                    ->build();
            }
        } catch (IGDBInvalidParameterException $e) {
            echo $e->getMessage();
        }

        try {
            $result = $igdb->game($query);
        } catch (IGDBEndpointException $e) {
            echo $e->getMessage();
        }

        $finalArray = array();

        if (!empty($result)) {
            foreach ($result as $game) {

                $total_rating = number_format($game->total_rating, 1);
                $summary = isset($game->summary) ? $game->summary : '';

                if (!empty($game->genres)) {
                    $genres = [];

                    foreach ($game->genres as $genre) {
                        $genres[] = $genre->name;
                    }

                    $genres = implode(", ", $genres);
                } else {
                    continue;
                }


                if (isset($game->cover)) {
                    $cover = $game->cover;
                    $width = $cover->width;
                    $imgId = $cover->image_id;

                    $url = 'https://images.igdb.com/igdb/image/upload/t_';

                    if ($width === 1920) {
                        $url .= '1080p/';
                    } else {
                        $url .= 'cover_big/';
                    }

                    $url .= $imgId . '.jpg';
                }

                $finalArray[] = array(
                    'name' => $game->name,
                    'cover' => $url,
                    'totalRating' => $total_rating,
                    'summary' => $summary,
                    'genres' => $genres
                );
            }
        }
    }


    $name = $finalArray[0]['name'];
    $pic = $finalArray[0]['cover'];
    $rating = $finalArray[0]['totalRating'];
    $summary = $finalArray[0]['summary'];
    $genres = $finalArray[0]['genres'];

    $igdbId = getGameID($name);
    $steamId = getSteamAppIdIfExists($igdbId);
    $steamId = $steamId[0];

    $gamePrice = $steamAPI->GetGamePrice($steamId, ['ca']);

    if (empty($gamePrice)) {
        $gamePrice = "Free";
    } else {
        $gamePrice = "CDN $" . number_format($gamePrice[0]['final'] / 100, 2);
    }

    // $html = "<div class='grid-container'>";
    $html = "<div class='tile-wrapper'>";
    $html .= "<div class='topper'>";
    $html .= "<img src='{$pic}'>";
    $html .= "<div class='title-rating-price'>";                   // this div will be flex row
    $html .= "<h3 class='game-title'>{$name}</h3>";
    $html .= "<h5 class='game-rating'>Average Rating: {$rating}</h5>";
    $html .= "<h5 class='game-price'>{$gamePrice}</h5>";
    $html .= "</div>";
    $html .= "</div>";
    $html .= "<p class='genre-list'>{$genres}</p>";
    $html .= "<p class='summary'>{$summary}</p>";
    $html .= "</div>";
    // $html .= "</div>";

    $styles = "
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

            * {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            }

            .grid-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* Three columns */
            gap: 20px; /* Gap between tiles */
            }

            .game-title {
            font-weight: 500;
            color: #ffc0ad;
            }

            .topper {
            display: flex;
            }

            .tile-wrapper {
            background: #55423d;
            border-radius: 16px;
            padding: 1.25rem;
            margin: 1rem;
            }

            .topper img {
            border-radius: 1rem;
            margin-bottom: 6px;
            }

            .title-rating-price {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 50px;
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

        </style>
    ";

    return $html;
}

// This function will take a game name string and amount of games to find and return the following data as an associative array:
function gameSearchForNameAndImage($gameInput, $amountOfGames = 1)
{
    global $igdb, $builder;

    try {
        if (is_numeric($gameInput)) {
            $query = $builder
                ->fields("name, cover.*")
                ->where("id = $gameInput")
                ->where("category = 0")
                ->limit($amountOfGames)
                ->build();
        } else {
            $query = $builder
                ->search($gameInput)
                ->fields("name, cover.*")
                ->where("category = 0")
                ->limit($amountOfGames)
                ->build();
        }
    } catch (IGDBInvalidParameterException $e) {
        echo $e->getMessage();
    }

    try {
        $result = $igdb->game($query);
    } catch (IGDBEndpointException $e) {
        echo $e->getMessage();
    }

    $finalArray = array();

    if (!empty($result)) {
        foreach ($result as $game) {
            if (isset($game->cover)) {
                $cover = $game->cover;

                $width = $cover->width;
                $imgId = $cover->image_id;

                $url = 'https://images.igdb.com/igdb/image/upload/t_';

                if ($width === 1920) {
                    $url .= '1080p/';
                } else {
                    $url .= 'cover_big/';
                }

                $url .= $imgId . '.jpg';

                $finalArray[] = array(
                    'Name' => $game->name,
                    'Cover' => $url
                );
            }
        }
    }

    return $finalArray;
}


// $gameid = getGameID("Hollow Knight");
// $gameinfo = allGameInfo($gameid, 1);
// $steamid = getSteamAppIdIfExists($gameid);
// $gamenamePics = gameSearchForNameAndImage($gameid);
// $gameGenres = gameInfo($gameid);

// echo "<img src='" . $gamenamePics[0]['Cover'] . "' alt='" . $gamenamePics[0]['Name'] . "'>";
// echo "<br><br>";
// print_r($gamenamePics);

// echo "<br><br><br>";
// print_r($gameinfo);
// echo "<br><br>";
// print_r($steamid);
// echo "<br><br>";
// print_r($gameGenres[0]['genres']);

// print_r($results);
// print_r($results);

// foreach ($results as $game) {
//     echo "<img src='{$game['Cover']}' />";
//     echo "Game Name: " . $game['Name'];
// }

/*
    This function, when given an igdb app id, will return all game info relevant to GameQuest any way that is not accessed in other functions
    player_perspectives.name, similar_games.name, 
    total_rating, websites, cover.*
*/

function allGameInfo($gameID, $totalRatingRequest = 60, $limit = 1)
{
    global $igdb, $builder;

    try {
        $query = $builder
            ->fields("player_perspectives.name, similar_games, total_rating")
            ->where("id = $gameID")
            ->where("category = 0")
            ->limit(1)
            ->build();
    } catch (IGDBInvalidParameterException $e) {
        echo $e->getMessage();
    }

    try {
        $result = $igdb->game($query);
    } catch (IGDBInvalidParameterException $e) {
        echo $e->getMessage();
    }

    // var_dump($result);

    $finalArray = array();

    if (!empty($result)) {
        foreach ($result as $game) {
            $similarGames = $game->similar_games;
            $totalRating = $game->total_rating;

            $similarGamesFinal = [];
            if (!empty($similarGames)) {
                foreach ($similarGames as $similarGame) {
                    $similarGamesFinal[] = $similarGame;
                }
            }

            foreach ($game->player_perspectives as $perspective) {
                $gamePerspective = $perspective->name;
            }

            $finalArray[] = array(
                'similar_games' => $similarGamesFinal,
                'total_rating' => $totalRating,
                'player_perspective' => $gamePerspective
            );
        }
    }

    return $finalArray;
}

/* 
    With this function you can send eithe the game string itself or you can send in the game id to be used to query for the game.
    This is not the app id. Can be used for very specific applications if you need to get specific games genres etc.
    Also will return summary information.
*/

function gameInfo($gameInput, $limit = 1)
{
    global $igdb, $builder;



    try {
        if (is_numeric($gameInput)) {
            $query = $builder
                ->name("Game and Genre")
                ->fields("id, name, summary, genres.name")
                ->where("id = $gameInput")
                ->where("category = 0")
                ->limit($limit)
                ->build();
        } else {
            $query = $builder
                ->search("$gameInput")
                ->fields("id, name, summary, genres.name")
                ->where("category = 0")
                ->limit($limit)
                ->build();
        }
    } catch (IGDBInvalidParameterException $e) {
        echo $e->getMessage();
    }

    try {
        $result = $igdb->game($query);
    } catch (IGDBEndpointException $e) {
        echo $e->getMessage();
    }

    $finalArray = array();

    if (!empty($result)) {
        foreach ($result as $game) {
            $gameName = $game->name;
            $summary = isset($game->summary) ? $game->summary : '';

            if (!empty($game->genres)) {
                $genres = [];

                foreach ($game->genres as $genre) {
                    $genres[] = $genre->name;
                }

                $genres = implode(", ", $genres);
            } else {
                continue;
            }

            $finalArray[] = array(
                "gameName" => $gameName,
                "genres" => $genres,
                "summary" => $summary
            );
        }
    }

    return $finalArray;
}

/*
    This function will take in the game id or gameString and check if the game exists on the steam store. If it does, it will return an app id for this game.
    For accuracy, send the actual game id from igdb, otherwise its possible it can return a game you may not want. For this reason, you can also add a limit
    if you want to. By default, limit is 1.
    This function will be heavily used to check for games to display on the game recommendaton main page.
*/

function getSteamAppIdIfExists($gameInput, $limit = 1)
{
    global $igdb, $builder;

    try {
        if (is_numeric($gameInput)) {
            $query = $builder
                ->name("Steam App ID/Website link")
                ->fields("websites.url, websites.category")
                ->where("id = $gameInput")
                ->where("websites.category = 13")
                ->limit($limit)
                ->build();
        } else {
            $query = $builder
                ->search($gameInput)
                ->fields("websites.url, websites.category")
                ->where("websites.category = 13")
                ->limit($limit)
                ->build();
        }
    } catch (IGDBInvalidParameterException $e) {
        echo $e->getMessage();
    }

    try {
        $result = $igdb->game($query);
    } catch (IGDBEndpointException $e) {
        echo $e->getMessage();
    }

    $websiteUrls = [];

    // print_r($result);

    if (!empty($result)) {
        foreach ($result as $game) {
            foreach ($game->websites as $website) {
                if ($website->category === 13) {
                    array_push($websiteUrls, $website->url);
                }
            }
        }
    }

    // print_r($websiteUrls);

    $pattern = '/\d+/';

    $appIds = [];

    foreach ($websiteUrls as $url) {
        preg_match($pattern, $url, $matches);

        if (!empty($matches)) {
            $appIds[] = $matches[0];
        }
    }

    return $appIds;
}

/*
    The purpose of this function is to be only a getter and return the igdb game id. It will take only a string and return a single result. Be sure to check that
    you have received the correct game id for the correct game.
*/

function getGameID($gameInput)
{
    global $igdb, $builder;

    try {
        $query = $builder
            ->search("$gameInput")
            ->fields("id")
            ->limit(1)
            ->build();
    } catch (IGDBInvalidParameterException $e) {
        echo $e->getMessage();
    }

    try {
        $result = $igdb->game($query);
    } catch (IGDBEndpointException $e) {
        echo $e->getMessage();
    }

    $gameID = null;

    if (!empty($result[0])) {
        $gameID = $result[0]->id;
    }

    return $gameID;
}

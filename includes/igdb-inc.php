<?php

require_once "../functions/igdb/src/class.igdb.php";
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


// This function will take a game name string and return the following data as an associative array:

function gameSearchForNameAndImage($gameString, $amountOfGames = 5)
{
    global $igdb, $builder;

    try {
        $query = $builder
            ->search($gameString)
            ->fields("name, cover.*")
            ->where("category = 0")
            ->limit($amountOfGames)
            ->build();
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


$results = gameSearchForNameAndImage("League Of Legends", 3);
// print_r($results);

foreach ($results as $game) {
    echo "<img src='{$game['Cover']}' />";
    echo "Game Name: " . $game['Name'];
}

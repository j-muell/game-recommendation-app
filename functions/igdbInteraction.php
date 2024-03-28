<?php

require_once "igdb/src/class.igdb.php";
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


try {
    $query = $builder
        ->search("Overwatch")
        ->fields("id, name, genres, websites")
        ->where("category = 0")
        ->limit(2)
        ->build();
} catch (IGDBInvalidParameterException $e) {
    echo $e->getMessage();
}

try {
    $result = $igdb->game($builder);
} catch (IGDBEndpointException $e) {
    echo $e->getMessage();
}

if (!empty($result)) {
    print_r($result);
} else {
    echo "results was empty.";
}

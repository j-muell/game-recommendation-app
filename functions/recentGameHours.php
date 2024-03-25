<?php
// include('../components/landing/landingHeader.php');
include('steamapi/SteamAPI.class.php');

$api_key = '';

$steam_id = '';


$steamAPI = new SteamAPI($api_key);

$handler = $steamAPI->GetRecentPlayedGames($steam_id);



function debug_to_console($data)
{
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}


echo "<pre>";
print_r($handler);
echo "</pre>";
// include('../components/landing/landingFooter.php');

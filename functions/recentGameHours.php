<?php
// include('../components/landing/landingHeader.php');
include('steamapi/SteamAPI.class.php');

$steam_id = '76561198394835255';

$vanity_id = 'freeziex17';



$steamAPI = new SteamAPI(); // create a new steamAPI object


$handler = $steamAPI->GetRecentPlayedGames($steam_id); // run a function on the steamapi and send it the steam id

$profile = $steamAPI->GetPlayerInfo($vanity_id);

function debug_to_console($data)
{
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}


echo "<pre>";
print_r($handler);
print_r($profile);
print_r($profile[0]['avatar']);

// EXAMPLE OF HOW TO ACCESS THINGS FROM THE API AND SEND IN AN HTML ELEMENT.
// USING THE RETURNED INFORMATION, USE 0 TO ACCESS THE FIRST 'profile'. THEN ENTER THE KEY(s) YOU WISH TO RETURN A VALUE.
echo "<img src='{$profile[0]['avatar']}' alt=''>";
echo "</pre>";
// include('../components/landing/landingFooter.php');

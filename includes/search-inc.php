<?php
session_start();
require_once "../functions/steamapi/SteamAPI.class.php";
require_once "../includes/igdb-inc.php";



if (isset($_POST['search'])) {
    $searchValue = $_POST['search'];

    $results = gameSearchForNameAndImage($searchValue, 5);

    if (!empty($results)) {

        $finalArray = [];

        foreach ($results as $game) {
            if (isset($game['id'])) {
                $gameId = $game['id'];

                $html = "<div class='game-result'>";
                $html .= "<img src='{$game['Cover']}' alt='{$game['Name']}' class='game-cover' >";
                $html .= "<span>{$game['Name']}</span>";
                $html .= "<button class='wishlist-button' onclick='addToWishlistFromIndex({$gameId})'><i class='bx bxs-bookmark-alt-plus'></i></button>";
                $html .= "</div>";

                $finalArray[] = $html;
            } else {
                $gameId = getGameID($searchValue);
                $html = "<div class='game-result'>";
                $html .= "<img src='{$game['Cover']}' alt='{$game['Name']}' class='game-cover' >";
                $html .= "<span>{$game['Name']}</span>";
                $html .= "<button class='wishlist-button' onclick='addToWishlistFromIndex({$gameId})'><i class='bx bxs-bookmark-alt-plus'></i></button>";
                $html .= "</div>";

                $finalArray[] = $html;
            }
        }
    } else {
        $html = "<div class='game-result'>";
        $html .= "<span>No results found.</span>";
        $html .= "</div>";
    }

    $fullString = implode("<!--delimiter-->", $finalArray);

    echo $fullString;
}

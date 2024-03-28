<?php

$serverName = "localhost";
$dBUsername = "root";
$dBPassword = "rootPassword";
$dBName = "gamequest";

$conn = mysqli_connect($serverName, $dBUsername, $dBPassword, $dBName, 3306);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

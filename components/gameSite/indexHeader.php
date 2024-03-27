<?php
session_start();
if (!isset($_SESSION["userID"])) // checks is userID is set. This is not username. Username is userUsername.
{
    header("location: landing.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../styles/index.css" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <title>GameQuest</title>
</head>

<body>
    <div class="sidebar close">
        <div class="logo-details">
            <i class='bx bx-dice-6'></i>
            <a href="index.php">
                <span class="logo-name">GameQuest</span>
            </a>

        </div>
        <ul class="sidebar-content">
            <!-- entire sidebar exists inside this -->
            <li class="info-link">
                <div class="icon-link">
                    <a href="#">
                        <i class='bx bx-info-square'></i>
                        <span class="link-name">GameQuest Info</span>
                    </a>
                    <i class='bx bxs-chevron-down'></i>
                </div>
                <ul class="sub-menu">
                    <li><a class="link-name" href="#">About</a></li>
                    <li><a href="#">How to use</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </li>
            <li>
                <a href="#">
                    <i class='bx bx-category-alt'></i>
                    <span class="link-name">Categories</span>
                </a>
            </li>
        </ul>

    </div>
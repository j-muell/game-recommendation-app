<?php

if (isset($_POST["submit"])) {
    $username = $_POST["userID"];
    $pwd = $_POST["pwd"];

    require_once 'dbh-inc.php';
    require_once 'functions-inc.php';

    if (emptyInputLogin($username, $pwd) !== false) {
        header("location: ../components/login.php?error=emptyinput");
        exit();
    }

    loginUser($conn, $username, $pwd);
} else {
    header("location: ../components/login.php?error=unexpectedError");
    echo "Username is $username";
    exit();
}

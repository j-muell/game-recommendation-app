<?php

if (isset($_POST["submit"])) {

    $username = $_POST["userID"];
    $steamid = $_POST["steamID"];
    $pwd = $_POST["pwd"];
    $pwdConfirm = $_POST["pwd2"];
    $terms = isset($_POST["terms"]) ? $_POST["terms"] : null; // if isset is true, set terms to "terms" which returns "on". otherwise, make it null.

    require_once 'dbh-inc.php';
    require_once 'functions-inc.php';

    if (emptyInputSignup($username, $steamid, $pwd, $pwdConfirm, $terms) !== false) {
        header("location: ../components/signup.php?error=emptyinput");
        exit();
    }
    if (invalidUsername($username) !== false) {
        header("location: ../components/signup.php?error=invalidUsername");
        exit();
    }
    if (invalidPassword($pwd) !== false) {
        header("location: ../components/signup.php?error=passwdtooshort");
        exit();
    }
    if (pwdMatch($pwd, $pwdConfirm) !== false) {
        header("location: ../components/signup.php?error=passwddontmatch");
        exit();
    }
    if (userExists($conn, $username) !== false) {
        header("location: ../components/signup.php?error=usernameTaken");
        exit();
    }
    // if (invalidSteamID($steamid) !== false) {
    //     header("location: ../components/signup.php?error=steamiddoesnotexist");
    //     exit();
    // }
    if (invalidProfileVisiblity($steamid) !== false) {
        header("location: ../components/signup.php?error=profilenotpublic");
        exit();
    }
    if (termsUnchecked($terms) !== false) {
        header("location: ../components/signup.php?error=termsUnchecked");
        exit();
    } else {
        $terms = 1; // this is considered true in terms of sql database. if it is zero, it is false.
    }

    // we send in $terms as a value of 1 (integer)
    createUser($conn, $username, $steamid, $pwd, $terms);
} else {
    header("location: ../components/signup.php");
    exit();
}

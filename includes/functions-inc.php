<?php
include('../functions/steamapi/SteamAPI.class.php');
function emptyInputSignup($username, $steamid, $pwd, $pwdConfirm, $terms)
{
    $result = false;

    if (empty($username) || empty($steamid) || empty($pwd) || empty($pwdConfirm) || empty($terms)) {
        $result = true;
    }

    return $result;
}
function invalidUsername($username)
{
    $result = false;

    if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        $result = true;
    }

    return $result;
}
function pwdMatch($pwd, $pwdConfirm)
{
    $result = false;

    if ($pwd != $pwdConfirm) {
        $result = true;
    }

    return $result;
}

function invalidPassword($pwd)
{
    $result = false;

    if (strlen($pwd) < 8) {
        $result = true;
    }

    return $result;
}

function termsUnchecked($terms)
{
    $result = false;

    if (!isset($terms)) {
        return true;
    }

    return $result;
}

function invalidSteamID($steamid)
{
    include('../functions/steamapi/SteamAPI.class.php');
    $steamAPI = new SteamAPI();

    $validID = $steamAPI->steamIDExists($steamid);

    if ($validID === true) {
        return false;
    }

    return true;
}

function invalidProfileVisiblity($steamid)
{
    $steamAPI = new SteamAPI();

    $profileVisibility = $steamAPI->isProfilePublic($steamid);

    if ($profileVisibility === true) {
        return false;
    }

    return true;
}
function userExists($conn, $username)
{
    $sql = "SELECT * FROM user WHERE userUsername= ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../components/signup.php?error=stmtFailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        $result = false;
        return $result;
    }

    mysqli_stmt_close($stmt);
}
function createUser($conn, $username, $steamid, $pwd, $terms)
{
    $sql = "INSERT INTO user (userUsername, userSteamID, userPasswd, userTerms) VALUES (?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../components/signup.php?error=stmtFailed");
        exit();
    }

    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "sssi", $username, $steamid, $hashedPwd, $terms); // we send in 3 strings and 1 int. the int is 1 and is nonzero
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("location: ../components/index.php");
    exit();
}
function emptyInputLogin($username, $pwd)
{
    $result = false;

    if (empty($username) || empty($pwd)) {
        $result = true;
    }

    return $result;
}

function loginUser($conn, $username, $pwd)
{
    $userIDExists = userExists($conn, $username);
    // file_put_contents("userExists_debug.log", print_r($userIDExists, true), FILE_APPEND); debugging content
    if ($userIDExists === false) {
        header("location: ../components/login.php?error=wronglogin");
        exit();
    }

    $pwdHashed = $userIDExists["userPasswd"];

    $checkPwd = password_verify($pwd, $pwdHashed);

    if ($checkPwd === false) {
        header("location: ../components/login.php?error=wrongpass");
        exit();
    } else if ($checkPwd === true) {
        session_start();
        $_SESSION["userID"] = $userIDExists["userUid"];
        $_SESSION["userUsername"] = $userIDExists["userUsername"];
        $_SESSION["userSteamID"] = $userIDExists["userSteamID"];
        header("location: ../components/index.php");
        exit();
    }
}

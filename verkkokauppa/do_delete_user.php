<?php

session_start();
require "config/db_connect.php";
include_once "lib/class.user.php";

if ($_SESSION['logged_in']) {
    $user_id = $_SESSION['id'];
    $user = new User($user_id, $mysqli_conn, "users");

    //? Tarkistetaan ja tallennetaan tietokantaan
    if ($user->delete()) {
        //? success
        sleep(1);
        header("Location: login");
    } else {
        //! error
        echo 'Query error: ' . mysqli_error($conn);
    }
    header("Location: login");
    $_SESSION['logged_in'] = false;
}

session_unset();
session_destroy();

?>

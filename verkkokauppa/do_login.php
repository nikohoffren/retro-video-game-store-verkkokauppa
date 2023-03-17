<?php

session_start();
require "config/db_connect.php";
include "lib/class.user.php";
include_once "autoloader.php";

$user = new User($_SESSION['id'], $mysqli_conn, "user");

//? facebook login check
if ($_SESSION['fb_logged_in']) {
    $user->email = $_SESSION['fb_user_info']['email'];
    $user->first_name = $_SESSION['fb_user_info']['first_name'];
    $user->last_name = $_SESSION['fb_user_info']['last_name'];
    $user->fb_user_id = $_SESSION['fb_user_id'];
    // $user->fb_access_token = $_SESSION['fb_access_token'];

    //* check if user with same email lready exists and is not deleted
    $check_user_sql = "SELECT * FROM users WHERE email = '$user->email' AND deleted = 0";
    $result = mysqli_query($conn, $check_user_sql);

    if (mysqli_num_rows($result) > 0) {
        $result_assoc = mysqli_fetch_all($result, MYSQLI_ASSOC);
        if ($user->updateFacebookUser()) {
            header("Location: index");
        }
    } else {
       $user->registerNewFacebookUser();
    }
}

//? username and password login check
if (isset($_POST['submit'])) {
    //? check username
    if (empty($_POST['username'])) {
        $errors['username'] = "Lisää käyttäjänimi <br /><br />";
        header("Location: login");
    } else {
        $user->username = $_POST['username'];
    }

    //? check password
    if (empty($_POST['password'])) {
        $errors['password'] = "Lisää salasana <br /><br />";
        header("Location: login");
    } else {
        $user->password = $_POST['password'];
    }

    if (!array_filter($errors) && !$_SESSION['fb_logged_in']) {
        $check_username_sql = "SELECT * FROM users WHERE username = '$user->username'";
        $result = mysqli_query($conn, $check_username_sql);
        if (mysqli_num_rows($result) > 0) {
            $result_assoc = mysqli_fetch_all($result, MYSQLI_ASSOC);
        } else {
            $_SESSION['login_not_successfull'] = true;
            header("Location: login");
        }
        foreach ($result_assoc as $assoc_result) {
            $logged_id = $assoc_result['id'];
            $deleted = $assoc_result['deleted'];
                if ($deleted == '1') {
                    $_SESSION['user_deleted'] = true;
                    header("Location: login");
                } else {
                    if ($user->login() && $logged_id > 0 && $deleted == '0') {
                        $_SESSION['logged_in'] = true;
                        $user->id = $logged_id;
                        header("Location: ./");
                    } else {
                        $_SESSION['logged_in'] = false;
                        $_SESSION['login_not_successfull'] = true;
                        header("Location: login");
                    }
                }
        }
    } else {
        $_SESSION['login_not_successfull'] = true;
        header("Location: login");
    }
}

?>

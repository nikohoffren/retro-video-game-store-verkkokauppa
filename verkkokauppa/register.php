<?php

session_start();
require "config/db_connect.php";
require_once "lib/class.user.php";

$errors = array('username'=>"", 'password'=>"", 'password2'=>"", 'email'=>"");
$user = new User($_SESSION['id'], $mysqli_conn, "user");

if (isset($_POST['submit'])) {

    //? check username
    if (empty($_POST['username'])) {
        $errors['username'] = "Lisää käyttäjänimi <br /><br />";
    } else {
        $user->username = $_POST['username'];
    }

    //? check password
    if (empty($_POST['password'])) {
        $errors['password'] = "Lisää salasana <br /><br />";
    } else {
        $user->password = $_POST['password'];
    }

    //? check password 2
    if (empty($_POST['password2'])) {
        $errors['password2'] = "Lisää salasana <br /><br />";
    } else {
        $user->password2 = $_POST['password2'];
    }

    //? check if passwords match
    if ($_POST['password'] !== $_POST['password2']) {
        $errors['password'] = "Salasanat eivät täsmää! <br /><br />";
        $errors['password2'] = "Salasanat eivät täsmää! <br /><br />";
    }

    //? check email
    if (empty($_POST['email'])) {
        $errors['email'] = "Lisää sähköpostiosoitteesi <br /><br />";
    } else {
        $user->email = $_POST['email'];
        if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Sähköpostiosoite täytyy olla olemassa oleva <br /><br />";
        }
    }

    //? if no errors in form
    if (!array_filter($errors)) {
        //*Check if username or email already exists in db
        $duplicate_query = "SELECT * FROM users WHERE username = '$user->username' OR email = '$user->email'";
        $duplicate_result = mysqli_query($mysqli_conn, $duplicate_query);
        if (mysqli_num_rows($duplicate_result) > 0) {
            $user_taken = true;
        } else {
            $user->registerNewUser();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<?php require('templates/header.php'); ?>

<div class="container center">
    <h4>REKISTERÖIDY</h4>
</div>
<div class="container margin-bottom even-smaller-width">
    <hr>
</div>
<br />

<div class="container">
    <form class="form container" action="register" method="POST">
        <div class="center input-field">
            <label class="big-text-1 theme-text" for="username">Käyttäjätunnus:</label>
            <input autofocus utocomplete="off" type="text" class="center theme-text" name="username" value="<?= $user->username ?>">
                <div class="red-text small-text-1"><?= $errors['username']; ?></div>
        </div>

        <div class="center input-field">
            <label class="big-text-1 theme-text" for="password">Salasana:<i class="fa fa-eye small-margin-left" id="togglePassword1"></i></label>
            <input autocomplete="off" type="password" class="center theme-text" name="password" id="id_password_1">

                <div class="red-text small-text-1"><?= $errors['password']; ?></div>
        </div>

        <div class="center input-field">
            <label class="big-text-1 theme-text" for="password2">Salasana uudelleen:<i class="fa fa-eye small-margin-left" id="togglePassword2"></i></label>
            <input autocomplete="off" type="password" class="center theme-text" name="password2" id="id_password_2">

                <div class="red-text small-text-1"><?= $errors['password2']; ?></div>
        </div>

        <div class="center input-field">
            <label class="big-text-1 theme-text" for="email">Sähköpostiosoite:</label>
            <input autocomplete="off" type="text" class="center theme-text" name="email" value="<?= $user->email ?>">
                <div class="red-text small-text-1"><?= $errors['email']; ?></div>
        </div>

        <div class="center margin-top">
            <input type="submit" name="submit" value="REKISTERÖIDY" id="registerButton" class="btn custom-linear-gradient-2">
        </div>
        <br>
        <?php if ($user_taken) : ?>
            <div class="container center">
                <div class="red-text small-text-1"><?= 'Käyttäjätunnus tai sähköpostiosoite on jo käytössä!'; ?></div>
            </div>
        <?php endif; ?>
    </form>
</div>
<br /><br/>

<?php require('templates/footer.php'); ?>
<?php
    $_SESSION['register_form'] = false;
    $user_taken = false;
?>

</html>

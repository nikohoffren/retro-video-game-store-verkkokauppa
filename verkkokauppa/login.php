<?php

session_start();
include "lib/class.user.php";
include_once "autoloader.php";

//? test if we get the facebook token
// if (isset($_GET['code'])) {
//     $accessToken = getAccessTokenWithCode($_GET['code']);
//     echo '<pre>';
//     print_r($accessToken);
//     die();
// }

//? try to login with facebook
if (isset($_GET['state']) && FB_APP_STATE == $_GET['state']) {
    $fbLogin = tryAndLoginWithFacebook($_GET);
}

//? if logging with facebook
if ($_SESSION['fb_logged_in']) {
    header("Location: do_login");
}

if ($_SESSION['logged_in']) {
    header("Location: user_information");
    exit;
}

$errors = array('username'=>"", 'password'=>"");

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

    //? if no errors in form
    if (!array_filter($errors)) {
        header("Location: do_login.php");
    } else {
        header("Location: login");
    }

}

require('templates/header.php');

?>

<!DOCTYPE html>
<html lang="en">

<section class="center margin">

    <h4 class="theme-text">Kirjaudu sisään</h4>
    <div class="container margin-bottom even-smaller-width">
        <hr>
        <br />
    </div>
        <br />
        <div class="container">
        <form class="form container" action="do_login.php" method="POST">
            <div class="center container margin-top input-field">
                <label class="big-text-1 theme-text" for="name">Käyttäjätunnus:</label>
                <input autofocus autocomplete="off" type="text" class="center theme-text" name="username"
                value="<?= $user->username ?>">
                <div class="red-text small-text-1"><?= $errors['username']; ?></div>
            </div>
            <div class="center container margin-top input-field">
                <label class="big-text-1 theme-text" for="name">Salasana:<i class="fa fa-eye small-margin-left" id="togglePassword1"></i></label>
                <input autocomplete="off" type="password" class="center theme-text" name="password"
                value="<?= $user->password ?>" id="id_password_1">
                    <div class="red-text small-text-1"><?= $errors['password']; ?></div>
            </div>
            <div class="center margin-top">
                <input type="submit" name="submit" value="KIRJAUDU" class="btn green mobile-btn">
                <a href="<?= getFacebookLoginUrl(); ?>" class="btn blue"><i class="fa fa-facebook left mobile-brn"></i>Kirjaudu</a>
                <a href="register" class="btn-small custom-linear-gradient-2 margin-left mobile-btn">REKISTERÖIDY</a>
                <?php if ($_SESSION['login_not_successfull']): ?>
                    <div class="red-text small-text-1 margin-top">
                        <?= "Väärä käyttäjätunnus tai salasana"; ?>
                    </div>
                <?php elseif ($_SESSION['user_deleted']): ?>
                    <div class="red-text small-text-1 margin-top"><?= "Käyttäjä poistettu"; ?></div>
                <?php endif; ?>
                <br /><br />
                </div>
            </div>
        </form>

    <br />
</section>

<?php $_SESSION['login_not_successfull'] = false; ?>
<?php require('templates/footer.php'); ?>

</html>

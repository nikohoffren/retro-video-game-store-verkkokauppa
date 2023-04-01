<?php

session_start();
require "config/db_connect.php";
include_once "lib/class.user.php";
include_once "templates/header.php";

$user = new User($_SESSION['id'], $mysqli_conn, "users");
$user->session_id = session_id();
$userdata = $user->getUserData();
$firstname = "";
$lastname = "";
$phone = "";
$email = "";
$message ="";
$no_errors = false;
$errors = array('firstname'=>"", 'lastname'=>"", 'phone'=>"", 'email'=>"", 'message'=>"");

if (isset($_POST['submit'])) {

    //* check firstname
    if (empty($_POST['firstname'])) {
        $errors['firstname'] = "Lisää etunimesi <br /><br />";
    } else {
        $firstname = $_POST['firstname'];
        if (!preg_match('/^[a-zA-Z\s]+$/', $firstname)) {
            $errors['firstname'] = "Lisää vain isoja ja pieniä kirjaimia! <br /><br />";
        }
    }

    //* check lastname
    if (empty($_POST['lastname'])) {
        $errors['lastname'] = "Lisää sukunimesi <br /><br />";
    } else {
        $lastname = $_POST['lastname'];
        if (!preg_match('/^[a-zA-Z\s]+$/', $lastname)) {
            $errors['lastname'] = "Lisää vain isoja ja pieniä kirjaimia! <br /><br />";
        }
    }

    //* check phone
    if (empty($_POST['phone'])) {
        $errors['phone'] = "Lisää puhelinnumerosi <br /><br />";
    } else {
        $phone = $_POST['phone'];
        if (!preg_match('/^[0-9-+\s]+$/', $phone)) {
            $errors['phone'] = "Lisää vain numeroita <br /><br />";
        }
    }

    //* check email
    if (empty($_POST['email'])) {
        $errors['email'] = "Lisää sähköpostiosoitteesi <br /><br />";
    } else {
        $email = $_POST['email'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Sähköpostiosoite täytyy olla olemassa oleva <br /><br />";
        }
    }

    //* if no errors in form
    if (!array_filter($errors)) {
        ini_set('display errors', 1);
        error_reporting(E_ALL);

        $user->first_name = mysqli_real_escape_string($conn, $_POST['firstname']);
        $user->last_name = mysqli_real_escape_string($conn, $_POST['lastname']);
        $user->phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $user->email = mysqli_real_escape_string($conn, $_POST['email']);
        $user->message = mysqli_real_escape_string($conn, $_POST['message']);
        $headers = "From: Retro Video Games <retro@retrogames.com>\r\n";
        $headers .= "Reply-To: reply@retrogames.com\r\n";
        $headers .= "Content-type: text/html\r\n";

        $mail = 'Henkilö nimeltä ' .$user->first_name. ' ' .$user->last_name. ' otti sinuun yhteyttä nettisivujen kautta. Hänen puhelinnumeronsa on ' .$user->phone. ' ja sähköpostiosoite ' .$user->email. '.<br /><br />Viesti: ' .$user->message. ' ';

        //* send email to niko.hoffren@gmail.com
        if (mail('niko.hoffren@gmail.com', 'Sait viestin käyttäjältä ' .$user->first_name. ' ' .$user->last_name. ' ', $mail, $headers)) {
            header("Location: contact/messagesent");
        } else {
            header("Location: contact");
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

    <br />
    <div class="container">
    <?php if ($_GET['messagesent'] == 1) : ?>
        <div class="container center theme-text">
            <h4>KIITOS YHTEYDENOTOSTASI!</h4>
            <h6>Otamme yhteyttä ilmoittamaasi sähköpostiosoitteeseen tai puhelinnumeroon mahdollisimman pian.</h6>
            <br /><br /><br />
            <a href="./" class="btn-small custom-linear-gradient-2">TAKAISIN ETUSIVULLE</a>
            <br /><br /><br />
        </div>
    <?php else : ?>
        <div class="container">
            <div class="container center theme-text">
                <h5>Lähetä meille viesti niin otamme sinuun yhteyttä mahdollisimman pian:</h5>
            </div>
        <br />
        <form class="form" action="contact" method="POST">
            <div class="center">
                <label class="big-text-1 grey-text text-darken-2" for="firstname">Etunimesi:</label>
                <input autofocus autocomplete="off" type="text" class="center theme-text" name="firstname" value="<?= $userdata['first_name'] ?>">
                    <div class="red-text small-text-1"><?= $errors['firstname']; ?></div>
            </div>

            <div class="center">
                <label class="big-text-1 grey-text text-darken-2" for="lastname">Sukunimesi:</label>
                <input autofocus autocomplete="off" type="text" class="center theme-text" name="lastname" value="<?= $userdata['last_name'] ?>">
                    <div class="red-text small-text-1"><?= $errors['lastname']; ?></div>
            </div>

            <div class="center">
                <label class="big-text-1 grey-text text-darken-2" for="phone">Puhelinnumero:</label>
                <input autocomplete="off" type="text" class="center theme-text" name="phone" value="<?= $userdata['phone'] ?>">
                    <div class="red-text small-text-1"><?= $errors['phone']; ?></div>
            </div>

            <div class="center">
                <label class="big-text-1 grey-text text-darken-2" for="email">Sähköpostiosoite:</label>
                <input autocomplete="off" type="text" class="center theme-text" name="email" value="<?= $userdata['email'] ?>">
                    <div class="red-text small-text-1"><?= $errors['email']; ?></div>
            </div>

            <div class="center">
                <label class="big-text-1 grey-text text-darken-2" for="email">Viestisi:</label><br />
                    <div class="input-field">
                        <textarea autocomplete="off" type="text" class="center theme-text materialize-textarea" rows="10" columns="10" name="message" value="<?= $message ?>"></textarea>
                            <div class="red-text small-text-1"><?= $errors['message']; ?></div>
                    </div>
            </div>

            <div class="center margin-top">
                <input type="submit" name="submit" value="LÄHETÄ" class="btn custom-linear-gradient-2">
            </div>
            <br>
        </form>
        </div>

    <?php endif; ?>
    </div>
<br /><br />

<?php require('templates/footer.php'); ?>

</html>

<?php

    session_start();
    require "config/db_connect.php";
    require "templates/header.php";

    $_SESSION['updated'] = false;
    $user = new User($_SESSION['id'], $mysqli_conn, "users");
    $user->session_id = session_id();

    $userdata = $user->getUserData();
    $user_orders = $user->getUserOrders();
    $unique_user_orders = $user->getUniqueUserOrders();

    foreach ($unique_user_orders as $order) {
        $unique_order_id = $order['id'];
        $product_name = $user->getUserOrderProducts($unique_order_id);
        $product_names[] = $product_name;
    }

    //* modify user
    if (isset($_POST['submit'])) {
        if ($_SESSION['fb_logged_in']) {
            $user->email = $userdata['email'];
            $user->first_name = $userdata['first_name'];
            $user->last_name = $userdata['last_name'];
            $user->address = $_POST['address'];
            $user->zip = $_POST['zip'];
            $user->city = $_POST['city'];
            $user->phone = $_POST['phone'];
        } else {
            $user->email = $_POST['email'];
            $user->first_name = $_POST['firstname'];
            $user->last_name = $_POST['lastname'];
            $user->address = $_POST['address'];
            $user->zip = $_POST['zip'];
            $user->city = $_POST['city'];
            $user->phone = $_POST['phone'];
        }

        //* update database
        if ($user->update()) {
            $_SESSION['updated'] = true;
            header("Location: user_information");
        } else {
            echo 'query error: ' . mysqli_error($conn);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<div class="container center">

    <div class="container margin-bottom">
        <h4>TILAUKSESI</h4>
        <?php if (!$user_orders) : ?>
            <h6>Ei vielä tehtyjä tilauksia!</h6>
        <?php endif;?>

            <?php foreach ($unique_user_orders as $order) : ?>
                <ul class="collapsible">
                    <li>
                        <div class="collapsible-header"><i class="blue-text material-icons">local_offer</i>Tilausnumero: <?= $order['id'] ?>
                            <span>
                                <p><span class="tab"></span>Maksun tila:
                                    <?php if ($order['status'] == 0) : ?>
                                        <p class="red-text"><span class="tab"></span><?= 'maksamatta'; ?></p>
                                    <?php else : ?>
                                        <p class="green-text"><span class="tab"></span><?= 'maksettu'; ?></p>
                                    <?php endif; ?>
                                </p>
                            </span>
                        </div>
                        <div class="collapsible-body white">
                            <span>
                                <h6><span class="bold">Päivämäärä:</span> <?= $order['create_date'] ?></h6>
                                <h6><span class="bold">Hinta yhteensä:</span> <?= round($order['total'], 2) ?> €</h6>
                                <h6><span class="bold">Veroton hinta yhteensä:</span> <?= round($order['total_no_tax'], 2) ?> €</h6>
                                <h6><span class="bold">Tuotteet:</span><br />
                                    <?php
                                        $products = $user->getUserOrderProducts($order['id']);
                                        foreach ($products as $product) {
                                            echo $product['name'] . ', ' . $product['quantity'] . ' kpl<br />';
                                        }
                                    ?>
                                </h6>
                            </span>
                        </div>
                    </li>
                </ul>
            <?php endforeach; ?>
    </div>
    <br />

</div>

<div class="container center">
<?php if ($_SESSION['updated']) : ?>
    <div class="center">
        <h3>Tiedot päivitetty!</h3>
    </div>
<?php endif; ?>
    <h4>OMAT TIETOSI</h4>
</div>

<div class="container">
        <div class="container margin-bottom even-smaller-width">
            <hr>
        </div>

    <div class="container center">
        <?php if ($_SESSION['fb_logged_in']) : ?>
            <div class="center flex">
                <div class="img-container">
                    <img src="<?= $_SESSION['fb_user_picture'] ?>" alt="Facebook user image" />
                </div>
                <h6 class="grey-text text-darken-2">
                    Etunimi: <span class="black-text"><?= $userdata['first_name'] ?></span>
                </h6>
                <h6 class="grey-text text-darken-2">
                    Sukunimi: <span class="black-text"><?= $userdata['last_name'] ?></span>
                </h6>
                <h6 class="grey-text text-darken-2">
                    Sähköpostiosoite: <span class="black-text"><?= $userdata['email'] ?></span>
                </h6>
            </div>
        <?php endif; ?>
        <br />

        <form class="form" action="user_information" method="POST">

            <?php if (!$_SESSION['fb_logged_in']) : ?>
            <div class="center input-field">
                <label class="big-text-1 grey-text text-darken-2" for="firstname">Etunimi:</label>
                <input autocomplete="off" type="text" class="center theme-text" name="firstname" value="<?= $userdata['first_name'] ?>">
            </div>
            <?php endif; ?>

            <?php if (!$_SESSION['fb_logged_in']) : ?>
            <div class="center input-field">
                <label class="big-text-1 grey-text text-darken-2" for="lastname">Sukunimi:</label>
                <input autocomplete="off" type="text" class="center theme-text" name="lastname" value="<?= $userdata['last_name'] ?>">
            </div>
            <?php endif; ?>

            <div class="center input-field">
                <label class="big-text-1 grey-text text-darken-2" for="address">Osoite:</label>
                <input autocomplete="off" type="text" class="center theme-text" name="address" value="<?= $userdata['address'] ?>">
            </div>

            <div class="center input-field">
                <label class="big-text-1 grey-text text-darken-2" for="zip">Postinumero:</label>
                <input autocomplete="off" type="number" class="center theme-text" name="zip" value="<?= $userdata['zip'] ?>">
            </div>

            <div class="center input-field">
                <label class="big-text-1 grey-text text-darken-2" for="city">Kaupunki:</label>
                <input autocomplete="off" type="text" class="center theme-text" name="city" value="<?= $userdata['city'] ?>">
            </div>

            <?php if (!$_SESSION['fb_logged_in']) : ?>
            <div class="center input-field">
                <label class="big-text-1 grey-text text-darken-2" for="email">Sähköpostiosoite:</label>
                <input autocomplete="off" type="text" class="center theme-text" name="email" value="<?= $userdata['email'] ?>">
            </div>
            <?php endif; ?>

            <div class="center input-field">
                <label class="big-text-1 grey-text text-darken-2" for="phone">Puhelinnumero:</label>
                <input autocomplete="off" type="number" class="center theme-text" name="phone" value="<?= $userdata['phone'] ?>">
            </div>

            <div class="center margin-top">
                <input type="submit" name="submit" value="TALLENNA" id="checkoutSubmitButton" class="btn blue">
            </div>
            <br>
        </form>

        <?php if (!$_SESSION['fb_logged_in']) : ?>
            <a href="delete_user" class="btn red">
                <i class="material-icons left">delete</i>POISTA KÄYTTÄJÄTILISI
            </a>
        <?php endif; ?>
    </div>
    <br />
</div>

<?php include "templates/footer.php" ; ?>

</html>

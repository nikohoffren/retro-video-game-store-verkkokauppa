<?php

session_start();
require "config/db_connect.php";
include_once "lib/class.user.php";
require "templates/header.php";

$user = new User($_SESSION['id'], $mysqli_conn, "users");
$user->session_id = session_id();
$user_orders = $user->getUniqueUserOrders();
$latest_order = end($user_orders);
$order_products = $user->getUserOrderProducts($latest_order['id']);
$delivery_cost = $_COOKIE['delivery_cost'];
$userdata = $user->getUserData();

//? if customer has chosen to pay with a check
if ($_COOKIE['payment_type'] == "check" && $_SESSION['counter'] == 1) {
    ini_set('display errors', 1);
    error_reporting(E_ALL);
    $user->message = '<h4>Tervehdys ' .$userdata['first_name']. ' ' .$userdata['last_name']. '!</h4>Ohessa lasku Retro Video Games -verkkokaupassa tekemästäsi ostoksesta. Maksathan sen seitsemän (7) arkipäivän sisällä!<br /><br />Hinta yhteensä: ' .round($latest_order['total'], 2). '€<br /><br />';
    $headers = "From: Retro Video Games <retro@retrogames.com>\r\n";
    $headers .= "Reply-To: reply@retrogames.com\r\n";
    $headers .= "Content-type: text/html\r\n";

    //* send email to customer
    if (mail($userdata['email'], 'Lasku', $user->message, $headers)) {
        header("Location: order_done.php?messagesent=1");
    } else {
        header("Location: order_done");
    }
}

foreach ($order_products as $product) {
    $arr[] = $product['name'] . ", ";
    $arr[] = $product['quantity'] . " kpl" . "<br>";
}
$arr_result = implode(" ",$arr);

if ($_SESSION['counter'] == 1) {
    ini_set('display errors', 1);
    error_reporting(E_ALL);
    $user->message = '<h4>Tervehdys ' .$userdata['first_name']. ' ' .$userdata['last_name']. '!</h4>Ohessa tilausvahvistus Retro Video Games -verkkokaupassa tekemästäsi ostoksesta.<br /><br />Hinta yhteensä: ' .round($latest_order['total'], 2). '€<br /><br /><h5>Tilaamasi tuotteet:</h5>' .$arr_result. '';
    $headers = "From: Retro Video Games <retro@retrogames.com>\r\n";
    $headers .= "Reply-To: reply@retrogames.com\r\n";
    $headers .= "Content-type: text/html\r\n";
    $_SESSION['counter'] = 0;

    //* send email to customer
    if (mail($userdata['email'], 'Tilausvahvistus', $user->message, $headers)) {
        header("Location: order_done.php?messagesent=1");
    } else {
        header("Location: order_done");
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<div class="container center">
    <div class="margin-bottom">
        <h4>Kiitos tilauksesta!</h4>
        <p>Tilausvahvistus lähetetty ilmoittamaasi sähköpostiosoitteeseen.</p>
        <h5>Yhteenveto:</h5>
        <div class="margin">
            <h6>Tilausnumero: <?= $latest_order['id'] ?></h6>
            <?php if ($latest_order['status'] == 0) : ?>
            <p class="red-text"><?= 'maksamatta'; ?></p>
            <?php else : ?>
            <p class="green-text"><?= 'maksettu'; ?></p>
            <?php endif; ?>
            <h6>Päivämäärä: <?= $latest_order['create_date'] ?></h6>
            <h6>Hinta yhteensä: <?= round($latest_order['total'], 2) ?> €</h6>
            <h6>Veroton hinta yhteensä: <?= round($latest_order['total_no_tax'], 2) ?> €</h6>
            <h6 class="grey-text text-darken-2">
                <?php if ($delivery_cost == 0) {
                    echo 'Toimitus lähimpään postiin 3-5:ssä arkipäivässä (0 €)';
                } elseif ($delivery_cost == 5.50) {
                     echo 'Toimitus lähimpään postiin kahden arkipäivän sisällä (5.50 €)';
                } elseif ($delivery_cost == 6.90) {
                    echo 'Toimitus kotiosoitteeseen kahden arkipäivän sisällä (6.90 €)';
                } ?>
            </h6>
            <br />
            <h6>Tuotteet:</h6>
            <div class="container">
            <table id="table" class="responsive-table centered theme-text">
                <thead>
                    <tr>
                        <th>Määrä</th>
                        <th>Nimi</th>
                        <th>Hinta</th>
                    </tr>
                </thead>

                <tbody>
                <?php foreach ($order_products as $product) : ?>
                    <tr>
                        <td><?= $product['quantity'] ?></td>
                        <td><?= $product['name'] ?></td>
                        <td><?= round($product['price'], 2)?> €</td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>
    <br />
    <a href="./" class="btn-small blue">Takaisin etusivulle</a>
    <br /><br /><br />
</div>

<?php include "templates/footer.php" ; ?>

</html>

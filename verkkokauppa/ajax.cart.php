<?php
    session_start();
    require "config/db_connect.php";
    include_once "lib/class.page.php";
    include_once "lib/class.cart.php";
    include_once "lib/class.product.php";

    $cart = new Cart(0, $mysqli_conn, "cart");
    $cart->session_id = session_id();
    $cart_result = $cart->getQuantityOfProductFromCart();

    $json = json_encode($cart_result);
    echo $json;
?>

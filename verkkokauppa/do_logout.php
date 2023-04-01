<?php

session_start();
require "lib/class.user.php";
require_once "lib/class.cart.php";

$cart = new Cart($_SESSION['id'], $mysqli_conn, "cart");
$user = new User($_SESSION['id'], $mysqli_conn, "user");
$user->logout();
if ($cart->getAllProductsFromCart()) {
    $cart->emptyCart();
}

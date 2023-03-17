<?php

session_start();
require "config/db_connect.php";
include_once "lib/class.page.php";
include_once "lib/class.user.php";
include_once "lib/class.product.php";

if (isset($_GET['id'])) {
    $product = new Product($_SESSION['id'], $mysqli_conn, "product");
    $product->product_id = $_GET['id'];
    $product->session_id = session_id();
    $product->customer_id = $_SESSION['id'];

    if ($product->deleteProductFromCart()) {
        header("Location: cart");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}

?>

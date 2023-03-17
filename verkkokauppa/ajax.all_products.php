<?php
    session_start();
    require "config/db_connect.php";
    include_once "lib/class.product.php";

    $products = new Product($_SESSION['id'], $mysqli_conn, "product");
    $product_result = $products->getAllProducts();

    $json = json_encode($product_result);
    echo $json;

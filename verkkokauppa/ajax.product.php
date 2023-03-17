<?php
    session_start();
    require "config/db_connect.php";
    include_once "lib/class.page.php";
    include_once "lib/class.cart.php";
    include_once "lib/class.product.php";

    if (!empty($_SESSION['id'])) {
        $id = $_SESSION['id'];
        $query = "SELECT * FROM users WHERE id = '".$id."'";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
    }

    $cart = new Cart($_SESSION['id'], $mysqli_conn, "cart");
    $cart->session_id = session_id();
    $cart->customer_id = $id;
    $cart_result = $cart->getAllProductsFromCart();

    if ($cart_result) {
        $arrayResult = $cart->implodeArrayOfArrays($cart_result);

        if ($cart->customer_id > 0) {
            $result = $mysqli_conn->query("SELECT * FROM products JOIN cart ON cart.product_id = products.id WHERE cart.product_id IN ($arrayResult) AND customer_id = '$cart->customer_id'");
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        } else {
            $result = $mysqli_conn->query("SELECT * FROM products JOIN cart ON cart.product_id = products.id WHERE cart.product_id IN ($arrayResult) AND session_id = '$cart->session_id'");
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }
        $json = json_encode($products);
        echo $json;

    }

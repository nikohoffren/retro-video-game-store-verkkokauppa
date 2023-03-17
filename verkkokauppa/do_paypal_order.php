<?php
    session_start();
    require "config/db_connect.php";
    include_once "lib/class.page.php";
    include_once "lib/class.cart.php";
    include_once "lib/class.product.php";

    $product_ids = $_COOKIE['productids'];
    $delivery_cost = $_COOKIE['delivery_cost'];
    $cart = new Cart($_SESSION['id'], $mysqli_conn, "cart");
    $cart->session_id = session_id();
    $cart->customer_id = $_SESSION['id'];
    $delivery_method = $cart->delivery_method;
    $total = $cart->getCartTotal();
    $tax = $total / 7;
    $total_notax = $total - $tax;
    $cart->cart_total = $total + $delivery_cost;
    $cart->cart_total_notax = $total_notax + $delivery_cost;

    $cart->status = 1;
    if ($delivery_cost == 0) {
        $cart->delivery_method = 'free';
        echo 'Toimitus lähimpään postiin 3-5:ssä arkipäivässä (0 €)';
    } elseif ($delivery_cost == 5.50) {
        $cart->delivery_method = 'fast';
        echo 'Toimitus lähimpään postiin kahden arkipäivän sisällä (5.50 €)';
    } elseif ($delivery_cost == 6.90) {
        $cart->delivery_method = 'home';
        echo 'Toimitus kotiosoitteeseen kahden arkipäivän sisällä (6.90 €)';
    }

    if ($cart->addOrder()) {
        $cart->order_id = $cart->getOrderId();
        //* get all the individual product id:s and add them to order_items table
        $delimiter = ',';
        $ids = explode($delimiter, $product_ids);
        foreach ($ids as $id) {
            $cart->addProductsToOrderItems($id);
        }
        $cart->emptyCart();
        header("Location: order_done");
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }

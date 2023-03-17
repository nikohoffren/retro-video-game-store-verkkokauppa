<?php
    session_start();
    require "config/db_connect.php";
    include_once "lib/class.page.php";
    include_once "lib/class.cart.php";
    include_once "lib/class.product.php";

    $cart = new Cart(0, $mysqli_conn, "cart");
    $cart->session_id = session_id();
    $cart_result = $cart->getAllProductsFromCart();

    function implodeArrayofArrays($array) {
        $string = "";
        foreach ($array as $subarray) {
            $string .= implode(',' , $subarray);
        }
        return $string;
    }

    $where_in = implodeArrayofArrays($cart_result);
    $myarray = str_split($where_in, 1);
    $myArrayResult = implode(', ', $myarray);
    // $finalString = implode(array($myArrayResult, ', 2'));

    // print_r($myArrayResult);

    $result = $mysqli_conn->query("SELECT * FROM products WHERE id IN ($myArrayResult)");
    $row = $result->fetch_assoc();
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    $json = json_encode($products);
    echo $json;

?>

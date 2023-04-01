<?php
session_start();
include_once "lib/class.base.php";

class Cart extends Base
{
    public $customer_id;
    public $product_id;
    public $product_name;
    public $order_id;
    public $add_date;
    public $create_date;
    public $finish_date;
    public $session_id;
    public $cart_total;
    public $cart_total_notax;
    public $status;
    public $delivery_method;

    public function init($id, $sql, $name) {
        $result = $this->$sql->query("SELECT * FROM cart WHERE id = '".$id."'");
        $row = $result->fetch_assoc();

        $this->$name = $row['name'];
        $this->product_id = $row['user_id'];
        $this->add_date = $row['add_date'];
    }

    public function getAllProductsFromCart() {
        if ($this->customer_id > 0) {
            $result = $this->sql->query("SELECT product_id FROM cart WHERE customer_id = '$this->customer_id' ");
        } else {
            $result = $this->sql->query("SELECT product_id FROM cart WHERE session_id = '$this->session_id' ");
        }
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        return $products;
    }

    public function getQuantityOfProductFromCart() {
        if ($this->customer_id > 0) {
            $result = $this->sql->query("SELECT quantity FROM cart WHERE customer_id = '$this->customer_id'");
        } else {
            $result = $this->sql->query("SELECT quantity FROM cart WHERE session_id = '$this->session_id'");
        }
        $products = [];
        while ($row = $result->fetch_assoc()) {
        $products[] = $row;
        }
        return $products;
    }

    public function implodeArrayOfArrays($array) {
        $string = "";
        foreach ($array as $subarray) {
            $string .= implode(',',$subarray);
            $string .= str_split($string);
        }
        //? replace the word 'Array' from the string
        $result = str_replace('Array', ',', $string);
        //? remove the last comma
        return substr($result, 0, -1);
    }

    //* count the sum of associative array
    public function arrayMultisum(array $arr){
        $sum = null;
        foreach ($arr as $child){
            $sum += is_array($child) ? $this->arrayMultisum($child):$child;
        }
        return $sum;
    }

    public function getCartTotal() {
        $result = $this->getAllProductsFromCart();
        $array_result = $this->implodeArrayOfArrays($result);

        if ($this->customer_id > 0) {
            $query_result = $this->sql->query("SELECT quantity, price, discount FROM products JOIN cart ON cart.product_id = products.id WHERE cart.product_id IN ($array_result) AND customer_id = '$this->customer_id'");
            foreach ($query_result as $row) {
                $discount = $row['price'] * ($row['discount'] / 100);
                $discount_price = $row['price'] - $discount;
                $this->cart_total = $this->cart_total + $row['quantity'] * $discount_price;
            }
            return $this->cart_total;
        } else {
            $query_result = $this->sql->query("SELECT quantity, price, discount FROM products JOIN cart ON cart.product_id = products.id WHERE cart.product_id IN ($array_result) AND session_id = '$this->session_id'");
            foreach ($query_result as $row) {
            $discount = $row['price'] * ($row['discount'] / 100);
            $discount_price = $row['price'] - $discount;
            $this->cart_total = $this->cart_total + $row['quantity'] * $discount_price;
            }
            return $this->cart_total;
        }

    }

    //* get the latest order id
    public function getOrderId() {
        //? if logged in
        if ($this->id) {
            $result = $this->sql->query("SELECT id FROM orders WHERE customer_id = '$this->id' ORDER BY id DESC LIMIT 1");
            $row = $result->fetch_assoc();
            return $row['id'];
        } else {
            $result = $this->sql->query("SELECT id FROM orders WHERE session_id = '$this->session_id' ORDER BY id DESC LIMIT 1");
            $row = $result->fetch_assoc();
            return $row['id'];
        }
    }

    public function addOrder() : bool {
        if ($this->finish_date == 1) {
            $query = $this->sql->query("INSERT INTO orders (customer_id, session_id, create_date, finish_date, total, total_no_tax, status, delivery_method) VALUES(
                '".$this->id."',
                '".$this->session_id."',
                NOW(),
                NOW(),
                '".$this->cart_total."',
                '".$this->cart_total_notax."',
                '".$this->status."',
                '".$this->delivery_method."'
                )");
        } else {
            $query = $this->sql->query("INSERT INTO orders (customer_id, session_id, create_date, total, total_no_tax, status, delivery_method) VALUES(
                '".$this->id."',
                '".$this->session_id."',
                NOW(),
                '".$this->cart_total."',
                '".$this->cart_total_notax."',
                '".$this->status."',
                '".$this->delivery_method."'
                )");
        }

        if ($query) {
            return true;
        }
    }

    public function emptyCart() : bool {
        if ($this->customer_id > 0) {
            $query = $this->sql->query("DELETE FROM cart WHERE customer_id = '$this->customer_id'");
        } else {
            $query = $this->sql->query("DELETE FROM cart WHERE session_id = '$this->session_id'");
        }
        if ($query) {
            return true;
        }
        return false;
    }

    public function getCartProductsQuantity() {
        if ($this->customer_id > 0) {
            $result = $this->sql->query("SELECT quantity FROM cart WHERE customer_id = '$this->customer_id'");
        } else {
            $result = $this->sql->query("SELECT quantity FROM cart WHERE session_id = '$this->session_id'");
        }
        $quantity = [];
        while ($row = $result->fetch_assoc()) {
            $quantity[] = $row['quantity'];
        }
        return $this->arrayMultisum($quantity);
    }

    public function addProductsToOrderItems($product_id) : bool {
        $query_result = $this->sql->query("SELECT quantity, name, price, discount, tax FROM products JOIN cart ON cart.product_id = products.id WHERE cart.product_id = '$product_id'");

        foreach ($query_result as $row) {
            $discount = $row['price'] * ($row['discount'] / 100);
            $discount_price = $row['price'] - $discount;
            $product_quantity = $row['quantity'];
            $product_price = $discount_price * $product_quantity;
            $product_name = $row['name'];
            $product_tax = $row['tax'];
        }

        $query = $this->sql->query("INSERT INTO order_items (product_id, order_id, name, quantity, price, tax, create_date) VALUES(
            '$product_id',
            '$this->order_id',
            '$product_name',
            '$product_quantity',
            '$product_price',
            '$product_tax',
            NOW()
        )");

        if ($query) {
            return true;
        }
    }
}

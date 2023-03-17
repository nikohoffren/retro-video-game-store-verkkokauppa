<?php
session_start();
include_once "lib/class.base.php";
include_once "lib/class.cart.php";
include_once "config/db_connect.php";

class Product extends Base
{
    public $name;
    public $description;
    public $price;
    public $discount;
    public $tax;
    public $image;
    public $category;
    public $product_id;
    public $order_id;
    public $session_id;
    public $quantity;
    public $create_date;
    public $add_date;
    public $updated_at;
    public $customer_id;

    public function init($id, $sql, $name) {
        $result = $this->$sql->query("SELECT * FROM products WHERE id = '".$id."'");
        $row = $result->fetch_assoc();

        $this->$name = $row['name'];
        $this->product_id = $row['user_id'];
        $this->create_date = $row['create_date'];
    }

    public function getAllProducts() {
        $result = $this->sql->query("SELECT * FROM products");
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        return $products;
    }

    public function getProduct() {
        $result = $this->sql->query("SELECT * FROM products WHERE id = '".$this->product_id."'");
        $row = $result->fetch_assoc();
        return $row;
    }

    public function getAllProductsFromCategory() {
        if ($this->category == 'onsale') {
            $result = $this->sql->query("SELECT * FROM products WHERE discount > 0");
            $products = [];
            while ($row = $result->fetch_assoc()) {
            $products[] = $row;
            }
            return $products;
        } else {
            $result = $this->sql->query("SELECT * FROM products WHERE category = '$this->category'");
            $products = [];
            while ($row = $result->fetch_assoc()) {
            $products[] = $row;
            }
            return $products;
        }
    }

    public function addProductToCart() {
        $cart = new Cart($_SESSION['id'], $this->sql, "cart");
        if ($cart->customer_id > 0) {
            $check_quantity_query = "SELECT quantity FROM cart WHERE product_id = '$this->product_id' AND customer_id = '$cart->customer_id'";
            $result = $this->sql->query($check_quantity_query);
            $this->quantity = $result->fetch_assoc()['quantity'] + 1;

        } else {
            $check_quantity_query = "SELECT quantity FROM cart WHERE product_id = '$this->product_id' AND session_id = '$this->session_id'";
            $result = $this->sql->query($check_quantity_query);
            $this->quantity = $result->fetch_assoc()['quantity'] + 1;
        }

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                if ($cart->customer_id > 0) {
                    $query = $this->sql->query("UPDATE cart SET quantity = '$this->quantity' WHERE product_id = '$this->product_id' AND customer_id = '$cart->customer_id'");
                } else {
                    $query = $this->sql->query("UPDATE cart SET quantity = '$this->quantity' WHERE product_id = '$this->product_id' AND session_id = '$this->session_id'");
                }

                if ($query) {
                    return true;
                } else {
                    echo 'query error: ' . mysqli_error($this->sql);
                }
            } else {
                $query = $this->sql->query("INSERT INTO cart (quantity, customer_id, product_id, add_date, session_id) VALUES(
                    '1',
                    '$this->id',
                    '$this->product_id',
                    NOW(),
                    '$this->session_id'
                )");

                if ($query) {
                    return true;
                }
            }
        }
    }

    public function deleteProductFromCart() {
        $cart = new Cart($_SESSION['id'], $this->sql, "cart");
        $cart->customer_id = $this->customer_id;
        if ($cart->customer_id > 0) {
            $check_quantity_query = "SELECT quantity FROM cart WHERE product_id = '$this->product_id' AND customer_id = '$cart->customer_id'";
            $result = $this->sql->query($check_quantity_query);
            $this->quantity = $result->fetch_assoc()['quantity'] - 1;
        } else {
            $check_quantity_query = "SELECT quantity FROM cart WHERE product_id = '$this->product_id' AND session_id = '$this->session_id'";
            $result = $this->sql->query($check_quantity_query);
            $this->quantity = $result->fetch_assoc()['quantity'] - 1;
        }

        if ($result) {
            if ($this->quantity > 0) {
                if ($cart->customer_id > 0) {
                    $query = $this->sql->query("UPDATE cart SET quantity = '$this->quantity' WHERE product_id = '$this->product_id' AND customer_id = '$cart->customer_id'");
                } else {
                    $query = $this->sql->query("UPDATE cart SET quantity = '$this->quantity' WHERE product_id = '$this->product_id' AND session_id = '$this->session_id'");
                }

                if ($query) {
                    $this->quantity = $result->fetch_assoc()['quantity'] - 1;
                    return true;
                } else {
                    echo 'query error: ' . mysqli_error($this->sql);
                }
            } elseif ($result->fetch_assoc()['quantity'] <= 1) {
                if ($cart->customer_id > 0) {
                    $query = $this->sql->query("DELETE FROM cart WHERE product_id = '$this->product_id' AND customer_id = '$cart->customer_id'");
                } else {
                    $query = $this->sql->query("DELETE FROM cart WHERE product_id = '$this->product_id' AND session_id = '$this->session_id'");
                }

                if ($query) {
                    return true;
                }
            }
        }
    }
}

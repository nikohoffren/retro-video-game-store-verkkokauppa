<?php

include_once "lib/class.base.php";

class User extends Base
{
    public $username;
    public $password;
    public $password2;
    public $first_name;
    public $last_name;
    public $address;
    public $zip;
    public $city;
    public $phone;
    public $email;
    public $id;
    public $session_id;
    public $order_id;
    public $lastlogin;
    public $message;
    public $fb_user_id;
    public $fb_access_token;

    public function init($id, $sql) {
        //? Fetch userdata from database
        $result = $this->$sql->query("SELECT * FROM users WHERE id = '".$id."'");

        $row = $result->fetch_assoc();
        $this->username = $row['username'];
        $this->first_name = $row['first_name'];
        $this->last_name = $row['last_name'];
        $this->address = $row['address'];
        $this->zip = $row['zip'];
        $this->city = $row['city'];
        $this->phone = $row['phone'];
        $this->email = $row['email'];
    }

    public function getRowWithValue($tableName, $column, $value) {
        $result = $this->sql->query("SELECT * FROM $tableName WHERE $column = $value");
        return $result->fetch_assoc();
    }

    public function registerNewUser() {
        //* create sql
        $query = "INSERT INTO users (username, password, email, lastlogin) VALUES ('$this->username', '$this->password', '$this->email', NOW())";

        //* insert into db and check
        if (mysqli_query($this->sql, $query)) {
            $this->login();
            header("Location: index");
        } else {
            //! error
            echo 'Query error: ' . mysqli_error($this->sql);
        }
    }

    public function registerNewFacebookUser() {
        $query = "INSERT INTO users (email, first_name, last_name, lastlogin, fb_user_id) VALUES ('$this->email','$this->first_name', '$this->last_name', NOW(), '$this->fb_user_id')";

        if (mysqli_query($this->sql, $query)) {
            $this->facebookLogin();
            header("Location: index");
        } else {
            echo 'Query error: ' . mysqli_error($this->sql);
        }
    }


    public function login() : bool {
        $query = "SELECT * FROM users WHERE username = '".$this->username."' AND password = '".$this->password."'";
        $result = mysqli_query($this->sql, $query);
        $row = mysqli_fetch_assoc($result);

        if (mysqli_num_rows($result) > 0) {
            if ($this->username == $row['username'] && $this->password == $row['password']) {
                $_SESSION['logged_in'] = true;
                $_SESSION['id'] = $row['id'];

                $query = $this->sql->query("UPDATE users SET lastlogin = NOW() WHERE id = '$this->id'");
                if ($query) {
                    return true;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function facebookLogin() : bool {
        $query = "SELECT * FROM users WHERE fb_user_id = '".$this->fb_user_id."' AND deleted = 0";
        $result = mysqli_query($this->sql, $query);
        $row = mysqli_fetch_assoc($result);

        if (mysqli_num_rows($result) > 0) {
            $_SESSION['logged_in'] = true;
            $_SESSION['id'] = $row['id'];

            $query = $this->sql->query("UPDATE users SET lastlogin = NOW() WHERE id = '$this->id'");
            if ($query) {
                return true;
            }
        } else {
            return false;
        }
    }

    public function logout() {
        if ($_SESSION['logged_in']) {
            $_SESSION['logged_in'] = false;
            $_SESSION['fb_logged_in'] = false;
            header("Location: ./");
        }
        session_unset();
        session_destroy();
    }

    public function getUserData() {
        $query = "SELECT * FROM users WHERE id = '$this->id' AND deleted = 0";
        $result = mysqli_query($this->sql, $query);
        $row = mysqli_fetch_assoc($result);

        if (mysqli_num_rows($result) > 0) {
            return $row;
        } else {
            return null;
        }
    }

    public function getUserOrders() {
        $result = $this->sql->query("SELECT * FROM order_items JOIN orders ON order_items.order_id = orders.id WHERE customer_id = '$this->id'");
        $order_items = [];
        while ($row = $result->fetch_assoc()) {
            $order_items[] = $row;
        }
        return $order_items;
    }

    public function getUniqueUserOrders() {
        $result = $this->sql->query("SELECT * FROM orders WHERE customer_id = '$this->id'");
        $order_items = [];
        while ($row = $result->fetch_assoc()) {
            $order_items[] = $row;
        }
        return $order_items;
    }

    public function getUserOrderProducts($order_id) {
        $result = $this->sql->query("SELECT * FROM order_items WHERE order_id = $order_id");
        $order_items = [];
        while ($row = $result->fetch_assoc()) {
            $order_items[] = $row;
        }
        return $order_items;
    }


    public function add() : bool {
        $query = $this->sql->query("INSERT INTO users (username, password, email) VALUES(
            '".$this->username."',
            '".$this->password."'
            '".$this->email."'
            )");

        if ($query) {
            return true;
        }
    }

    public function update() : bool {
        $query = $this->sql->query("UPDATE users SET
        email = '$this->email',
        first_name = '$this->first_name',
        last_name = '$this->last_name',
        address = '$this->address',
        zip = '$this->zip',
        city = '$this->city',
        phone = '$this->phone'
        WHERE id = '$this->id'"
        );

        if ($query) {
            return true;
        }
    }

    public function updateFacebookUser() : bool {
        $query = $this->sql->query("UPDATE users SET
        email = '$this->email',
        first_name = '$this->first_name',
        last_name = '$this->last_name',
        lastlogin = NOW(),
        fb_user_id = '$this->fb_user_id'
        WHERE email = '$this->email'"
        );

        if ($query) {
            $this->facebookLogin();
            $_SESSION['logged_in'] = true;
            return true;
        }
    }

    public function changePassword() : bool {
        $query = $this->sql->query("UPDATE users SET
        password = '".$this->password."'
        WHERE id = '".$this->id."'"
        );

        if ($query) {
            return true;
        }
    }

    public function sendEmail() : bool {
        ini_set('display errors', 1);
        error_reporting(E_ALL);
        $to = ' .$this->email. ';
        $subject = 'Retro Games -lasku';
        $message = '<h1>Hei!</h1><p>Kiitos tilauksestasi!</p>';
        $headers = "From: Lähettäjä <retro@retrogames.com>\r\n";
        $headers .= "Reply-To: reply@retrogames.com\r\n";
        $headers .= "Content-type: text/html\r\n";
        if (mail($to, $subject, $message, $headers)) {
            return true;
        }
    }
}

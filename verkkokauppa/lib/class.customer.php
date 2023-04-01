<?php
session_start();
include_once "lib/class.base.php";

class Customer extends Base
{
    public $id;
    public $first_name;
    public $last_name;
    public $address;
    public $zip;
    public $city;
    public $phone;
    public $email;
    public $session_id;

    public function init($id, $sql, $name) {
        $result = $this->$sql->query("SELECT * FROM cart WHERE id = '".$id."'");
        $row = $result->fetch_assoc();
        $this->$name = $row['name'];
    }

    public function addCustomer() : bool {
        $query = $this->sql->query("INSERT INTO customer (session_id, first_name, last_name, address, zip, city, phone, email) VALUES(
            '".$this->session_id."',
            '".$this->first_name."',
            '".$this->last_name."',
            '".$this->address."',
            '".$this->zip."',
            '".$this->city."',
            '".$this->phone."',
            '".$this->email."'
            )");

        if ($query) {
            return true;
        }
    }
}

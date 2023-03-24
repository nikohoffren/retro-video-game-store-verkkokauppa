<?php

//? connect to database
    $conn = mysqli_connect('xxx', 'xxx', 'xxx', 'xxx');
    $mysqli_conn = new mysqli('xxx', 'xxx', 'xxx', 'xxx');

//? check connection
    if (!$conn) {
        echo 'Connection error: ' . mysqli_connect_error();
    }
    if ($mysqli_conn -> connect_error) {
        die("Connection failed: " . $mysqli_conn->connect_error);
    }

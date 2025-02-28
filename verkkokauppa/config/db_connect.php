<?php

//? connect to database
    $conn = mysqli_connect();
    $mysqli_conn = new mysqli();

//? check connection
    if (!$conn) {
        echo 'Connection error: ' . mysqli_connect_error();
    }
    if ($mysqli_conn -> connect_error) {
        die("Connection failed: " . $mysqli_conn->connect_error);
    }

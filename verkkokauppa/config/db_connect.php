<?php

//? connect to database
    $conn = mysqli_connect('sakky.luowa.fi', 'niko_hoffren', 'Xyp!u378', 'sakky_niko_hoffren');
    $mysqli_conn = new mysqli('sakky.luowa.fi', 'niko_hoffren', 'Xyp!u378', 'sakky_niko_hoffren');

//? check connection
    if (!$conn) {
        echo 'Connection error: ' . mysqli_connect_error();
    }
    if ($mysqli_conn -> connect_error) {
        die("Connection failed: " . $mysqli_conn->connect_error);
    }

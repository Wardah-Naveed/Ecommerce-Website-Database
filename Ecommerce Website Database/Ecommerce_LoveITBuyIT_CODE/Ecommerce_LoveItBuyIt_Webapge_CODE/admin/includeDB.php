<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "shoppping_site";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        echo "Connection failed: " . $conn->connect_error . "<br> <br>";
    }
?>
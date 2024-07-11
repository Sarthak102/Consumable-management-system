<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inventory";
$port = 3307; // specify the correct port number

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

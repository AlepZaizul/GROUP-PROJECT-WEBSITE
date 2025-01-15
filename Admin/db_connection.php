<?php
// Database credentials
$servername = "localhost";  // Change to your server if not localhost
$username = "root";         // Your database username
$password = "";             // Your database password (if any)
$dbname = "grand_tanjong_malim_hotel";  // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

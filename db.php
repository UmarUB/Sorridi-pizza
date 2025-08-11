<?php
// Database connection settings
$host = "localhost";
$username = "root";
$password = "";
$database = "sorridi-pizza";

// Create connection
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("❌ Connection failed: " . mysqli_connect_error());
}
// echo "✅ Connected successfully"; // Enable only for testing
?>

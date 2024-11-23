<?php
// Database connection details
$servername = "localhost"; // Usually localhost for local development
$username = "root";       // Default username for XAMPP
$password = "";           // Default password for XAMPP (leave blank)
$dbname = "twiiter";      // Your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

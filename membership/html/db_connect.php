<?php
$host = "localhost";
$dbname = "greenview_library";
$username = "root";
$password = "";


try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    // Set error mode to exceptions (important for debugging)
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Optional: echo "Connected successfully";
} 
catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
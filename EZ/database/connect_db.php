<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sia_db2";

try {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Set charset to utf8mb4
    if (!$conn->set_charset("utf8mb4")) {
        throw new Exception("Error setting charset: " . $conn->error);
    }

    // Disable strict mode for this connection
    $conn->query("SET SESSION sql_mode = ''");
    
} catch (Exception $e) {
    error_log("Database connection error: " . $e->getMessage());
    throw $e;
}
?>

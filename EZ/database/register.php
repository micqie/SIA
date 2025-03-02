<?php
session_start();
include 'connect_db.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
        exit();
    }

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $role = "U"; // Default role as User

    // Insert new user
    $sql = "INSERT INTO accounts (username, password, role) VALUES ('$email', '$hashed_password', '$role')";

    
    if (mysqli_query($conn, $sql)) {
        echo "Registration successful! <a href='index.php'>Login Here</a>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<?php
session_start();
include 'connect_db.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Debug information
    echo "Attempting to register with username: " . $username . "<br>";
    echo "Database connection status: " . ($conn ? "Connected" : "Not connected") . "<br>";

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
        exit();
    }

    // Check if username already exists
    $check_username = mysqli_query($conn, "SELECT * FROM accounts WHERE username = '$username'");
    if (!$check_username) {
        echo "Error checking username: " . mysqli_error($conn) . "<br>";
    }
    
    if (mysqli_num_rows($check_username) > 0) {
        echo "Username already taken!";
        exit();
    }

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $role = "U"; // Default role as User

    // Insert new user
    $sql = "INSERT INTO accounts (username, password, role) VALUES ('$username', '$hashed_password', '$role')";

    if (mysqli_query($conn, $sql)) {
        // Verify the account was created
        $verify = mysqli_query($conn, "SELECT * FROM accounts WHERE username = '$username'");
        if (!$verify) {
            echo "Error verifying account: " . mysqli_error($conn) . "<br>";
        }
        
        if (mysqli_num_rows($verify) > 0) {
            echo "Registration successful! Account verified in database.<br>";
            echo "Username: " . $username . "<br>";
            echo "Role: " . $role . "<br>";
            echo "<a href='../index.php'>Login Here</a>";
        } else {
            echo "Registration appeared successful but account not found in database.";
            echo "SQL Error: " . mysqli_error($conn);
        }
    } else {
        echo "Error during registration: " . mysqli_error($conn);
    }
}
?>

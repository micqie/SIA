<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'U') {
    header("Location: index.php"); // Redirect if not user
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    
</body>
</html>
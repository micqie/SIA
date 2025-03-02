<?php
session_start();
include 'database/connect_db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM accounts WHERE username='$username'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] == 'A') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: user_dashboard.php");
        }
        exit();
    } else {
        echo "Invalid credentials.";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EZ Leather Bar - Login</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">EZ Leather Bar</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#">About us</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Contact us</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="main-container">
    <!-- Left Side: Image -->
    <div class="left-side"></div>

    <!-- Right Side: Login Form -->
    <div class="right-side">
        <div class="login-card">
            <h3 class="text-center mb-4">LOGIN</h3>
            <form method="POST" action="">
            <?php if ($error): ?>
                    
                    <div class="alert alert-danger text-center"><?php echo $error; ?></div>
                <?php endif; ?>
                <div class="mb-3">
                    <input type="text" class="form-control" name="username" placeholder="Enter your username" required>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" name="password" placeholder="Enter your password" required>
                </div>
              
                <button type="submit" class="btn btn-primary w-100">LOGIN</button>
                <button type="button" class="btn btn-secondary w-100 mt-2">Guest</button>
            </form>
            <p class="text-center mt-3">Don’t have an account? <a href="sign_up.php">Sign Up</a></p>
        </div>
    </div>
</div>

</body>
</html>

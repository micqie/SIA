<?php
session_start();
include 'database/connect_db.php';

$error = ""; // Initialize error message

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
            header("Location: admin/admin_dashboard.php"); // Redirect to admin page
        } else {
            header("Location: user/user_dashboard.php"); // Redirect to user page
        }
        exit();
  
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

    <style>
        body {
            overflow-y: auto;
            height: 100vh;
        }
        .main-container {
            display: flex;
            min-height: 100vh;
        }
        .left-side {
            flex: 1;
            background: url('images/login-bg.jpg') no-repeat center center;
            background-size: cover;
        }
        .right-side {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background: #fff;
            border-radius: 8px;
        }
        .section {
            padding: 60px 0;
            text-align: center;
        }
        .social-icons a {
            margin: 0 10px;
            font-size: 24px;
        }
    </style>
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
                    <li class="nav-item"><a class="nav-link" href="#about">About us</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact us</a></li>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <div class="main-container">
        <div class="left-side"></div>
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

    <!-- About Us Section -->
    <section id="about" class="section bg-light">
        <div class="container">
            <h2>About Us</h2>
            <p>EZ Leather Bar specializes in high-quality handcrafted leather products. We take pride in offering premium leather accessories with exquisite designs.</p>
            <img src="images/about-us.jpg" class="img-fluid rounded" alt="About Us">
        </div>
    </section>

    <!-- Contact Us Section -->
    <section id="contact" class="section">
        <div class="container">
            <h2>Contact Us</h2>
            <p>Have questions? Reach out to us on our social media platforms or send us an email.</p>
            <div class="social-icons">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center py-3 bg-dark text-light">
        <p>&copy; 2025 EZ Leather Bar. All rights reserved.</p>
    </footer>
    
    <!-- Font Awesome for Icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    

</body>
</html>

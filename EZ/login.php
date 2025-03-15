<?php
session_start();
require 'database/connect_db.php'; // Ensure this file correctly connects to your database

$error = ""; // Initialize error message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    if (!empty($username) && !empty($password)) {
        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM accounts WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $row["password"])) {
                $_SESSION["username"] = $username;
                $_SESSION["role"] = $row["role"];

                if ($row["role"] == 'U') {
                    header("location: user_dashboard.php");
                } elseif ($row["role"] == 'A') {
                    header("location: admin_dashboard.php");
                }
                exit();
            } else {
                $error = "Incorrect password!";
            }
        } else {
            $error = "Username not found!";
        }

        $stmt->close();
    } else {
        $error = "Please fill in all fields.";
    }
    
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EZ Leather Bar - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="css/index.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url(assets/leather_bg.png);
   
            background-size: 100%;
            background-repeat: no-repeat;

            color: white;
            text-align: center;
            scroll-behavior: smooth;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow-x: hidden;
            overflow-y: hidden;
        }
    
        .main-container {
            display: flex;
            min-height: 100vh;
            width: 100%;
            padding-top: 80px;
        }

        .left-side {
            flex: 1;

            background: url('assets/bg.png') no-repeat center center;
            background-size: cover;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .left-content {
            position: relative;
            z-index: 2;
            text-align: left;
            max-width: 600px;
            animation: fadeInUp 1s ease-out;
            margin-bottom: 100px;
        }

        .brand-highlight {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 20px;
            color: #ffc107;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .feature-list {
            list-style: none;
            padding: 0;
            margin: 30px 0;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 15px;
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            backdrop-filter: blur(5px);
            transform: translateX(-100%);
            animation: slideIn 0.5s forwards;
        }

        .feature-item i {
            font-size: 24px;
            margin-right: 15px;
            color: #ffc107;
        }

        .feature-text {
            font-size: 1.1rem;
        }

        .floating-images {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
        }

        .floating-image {
            position: absolute;
            width: 100px;
            height: 100px;
            border-radius: 10px;
            background-size: cover;
            animation: float 6s infinite ease-in-out;
        }

        .image-1 {
            top: 10%;
            left: 10%;
            animation-delay: 0s;
            background-image: url('assets/leather1.jpg');
        }

        .image-2 {
            top: 60%;
            left: 20%;
            animation-delay: 2s;
            background-image: url('assets/leather2.jpg');
        }

        .image-3 {
            top: 30%;
            right: 15%;
            animation-delay: 4s;
            background-image: url('assets/perfume1.jpg');
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideIn {
            to {
                transform: translateX(0);
            }
        }

        .feature-item:nth-child(1) { animation-delay: 0.2s; }
        .feature-item:nth-child(2) { animation-delay: 0.4s; }
        .feature-item:nth-child(3) { animation-delay: 0.6s; }

        .overlay {
            background: rgba(0, 0, 0, 0.6);
            position: absolute;
            top: 0;
            left: 0;
           width: 500%;
            height: 100%;
        }

        .right-side {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: url('assets/right-bg.jpg') no-repeat center center;
            background-size: cover;
            position: relative;
        }

        .login-card {
            margin-bottom: 100px;
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            margin: auto;
            color: white;
            animation: fadeInDown 0.8s ease-in-out; /* Apply the fade-in-down animation */
        }

        .login-card h3 {
            color: white;
            font-weight: bold;
            font-size: 1.8rem;
            margin-bottom: 20px;
        }

        .login-card input {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            width: 100%;
            margin-bottom: 15px;
            transition: 0.3s;
        }

        .login-card input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .login-card input:focus {
            background: rgba(255, 255, 255, 0.3);
            outline: none;
        }

        .login-card .btn {
            width: 100%;
            padding: 12px;
            font-size: 1rem;
            font-weight: bold;
            border-radius: 8px;
            transition: 0.3s;
        }

        .btn-login {
            background: #ffc107;
            color: black;
        }

        .btn-login:hover {
            background: rgba(224, 179, 0, 0.8);
            color: white;
        }

        .btn-guest {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .btn-guest:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .signup-text {
            color: white;
            font-size: 1rem;
            margin-top: 15px;
        }

        .signup-text a {
            color: #ffc107;
            font-weight: bold;
            text-decoration: none;
            transition: 0.3s;
            padding: 8px 15px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
        }

        .signup-text a:hover {
            background: #ffc107;
            color: black;
        }
        
        @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
     
        /* Add these new navbar styles at the top of your existing styles */
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            padding: 15px 0;
            transition: all 0.3s ease;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            font-size: 1.5rem;
            color: #000 !important;
            font-weight: 700;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            color: #ffc107 !important;
        }

        .nav-link {
            color: #000 !important;
            font-weight: 500;
            padding: 8px 15px !important;
            margin: 0 5px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: #ffc107 !important;
            background: rgba(255, 193, 7, 0.1);
        }

        .navbar-toggler {
            border: none;
            padding: 0.5rem;
        }

        .navbar-toggler:focus {
            box-shadow: none;
            outline: none;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-store-alt me-2"></i>EZ Leather Bar
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <i class="fas fa-home me-1"></i>Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">
                            <i class="fas fa-info-circle me-1"></i>About
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">
                            <i class="fas fa-envelope me-1"></i>Contact
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="main-container">
        <div class="left-side">
            <div class="overlay"></div>
            <div class="floating-images">
                <div class="floating-image image-1"></div>
                <div class="floating-image image-2"></div>
                <div class="floating-image image-3"></div>
            </div>
            <div class="left-content">
                <h1 class="brand-highlight">EZ Leather Bar</h1>
                <p class="lead">Experience luxury and quality with our premium leather accessories and fragrances.</p>
                
                <ul class="feature-list">
                    <li class="feature-item">
                        <i class="fas fa-star"></i>
                        <div class="feature-text">
                            <strong>Premium Quality</strong><br>
                            Handcrafted leather accessories made with finest materials
                        </div>
                    </li>
                    <li class="feature-item">
                        <i class="fas fa-gift"></i>
                        <div class="feature-text">
                            <strong>Bundle Packages</strong><br>
                            Exclusive collections of leather goods and fragrances
                        </div>
                    </li>
                    <li class="feature-item">
                        <i class="fas fa-shield-alt"></i>
                        <div class="feature-text">
                            <strong>Quality Guaranteed</strong><br>
                            100% satisfaction with our premium products
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="right-side">
            <div class="login-card">
                <h3>Welcome Back</h3>
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger text-center"> <?php echo $error; ?> </div>
                <?php endif; ?>
                <form method="POST">
                    <input type="text" name="username" placeholder="Enter your username" required>
                    <input type="password" name="password" placeholder="Enter your password" required>
                    <button type="submit" class="btn btn-login">LOGIN</button>
                    <button type="button" class="btn btn-guest mt-2">Continue as Guest</button>
                </form>
                <p class="signup-text">Don't have an account?  
                    <a href="sign_up.php">Sign Up</a>
                </p>
            </div>
        </div>
    </div>
    
 

    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
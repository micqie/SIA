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
                    header("location: user/user_dashboard.php");
                } elseif ($row["role"] == 'A') {
                    header("location: admin/admin_dashboard.php");
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

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/index.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url(assets/leather_bg.png);
            background-size: cover;
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
            width: 100%; /* Ensure the container takes full width */
        }
        .left-side {
            flex: 1;
            background: url('assets/bg.png') no-repeat center center;
            background-size: cover;
            position: relative;
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
        .overlay {
            background: rgba(0, 0, 0, 0.6);
            position: absolute;
            top: 0;
            left: 0;
            width: 500%;
            height: 100%;
        }
        .hero-content {
            position: relative;
            z-index: 2;
            margin-top: 10%;
            padding: 100px 0;
        }
        .hero-section {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 30px;
            text-align: center;
        }
        .hero-text {
            flex: 1;
            max-width: 500px;
            animation: fadeIn 2s ease-in-out;
        }
        .hero-card {
            flex: 1;
            max-width: 500px;
            background: rgba(203, 206, 166, 0.49);
            padding: 20px;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            animation: fadeIn 2s ease-in-out;
        }
        .hero-card img {
            width: 100%;
            border-radius: 10px;
        }
        .btn-custom {
            background-color: #ffc107;
            color: black;
            padding: 12px 25px;
            font-size: 1.1rem;
            border-radius: 30px;
            transition: 0.3s;
            margin: 10px;
        }
        .btn-custom:hover {
            background-color:rgba(224, 179, 0, 0.63);
            color: white;
        }

        .section {
            padding: 60px 0;
            text-align: center;
        }
        .social-icons a {
            margin: 0 10px;
            font-size: 24px;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            margin: auto;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .login-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
 
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">EZ Leather Bar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">About us</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact us</a></li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="main-container">
        <div class="left-side">
            <div class="overlay"></div>
        </div>
        <div class="right-side">
            <div class="login-card">
                <h3 class="text-center mb-4" style="color: white;">LOGIN</h3>
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger text-center"> <?php echo $error; ?> </div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <input type="text" class="form-control" name="username" placeholder="Enter your username" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control" name="password" placeholder="Enter your password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">LOGIN</button>
                    <button type="button" class="btn btn-secondary w-100 mt-2">Guest</button>
                </form>
                <p class="text-center mt-3" style="text-align: center; margin-top: 1rem; font-size: 1rem; color: white;">
    Don’t have an account? 
    <a href="sign_up.php" style="color:rgb(249, 253, 255); font-weight: bold; text-decoration: none; transition: color 0.3s; background-color:rgb(30, 70, 231); padding: 10px; border-radius: 5px;">
        Sign Up
    </a>
</p>
            </div>
        </div>
    </div>
    
 

    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
<?php
session_start();
include 'database/connect_db.php'; // Database connection

$success = false; // Variable to track successful registration
$errors = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim(mysqli_real_escape_string($conn, $_POST['username']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic validation
    if (strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters long";
    }

    if (strlen($password) < 4) {
        $errors[] = "Password must be at least 4 characters long";
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match!";
    }

    // Check if username already exists
    $check_username = mysqli_query($conn, "SELECT * FROM accounts WHERE username = '$username'");
    if (mysqli_num_rows($check_username) > 0) {
        $errors[] = "Username already taken";
    }

    if (empty($errors)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $role = "U"; // Default role as User

        // Use prepared statement for insertion
        $stmt = $conn->prepare("INSERT INTO accounts (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hashed_password, $role);

        if ($stmt->execute()) {
            $success = true;
        } else {
            $errors[] = "Registration failed: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EZ Leather Bar - Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="css/index.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url(assets/leather_bg.png);
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            overflow-y: hidden;
        }

        .main-container {
            display: flex;
            min-height: 100vh;
            width: 100%;
            margin-top: 80px; /* Add space for fixed navbar */
        }

        .left-side {
            margin-bottom: 500px;
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
            margin: 20px 0;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
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
            margin-bottom: 50px;
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
            margin-bottom: 500px;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: url('assets/right-bg.jpg') no-repeat center center;
            background-size: cover;
            position: relative;
            padding: 40px;
        }

        .register-card {
            background: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            color: white;
            animation: fadeInDown 0.8s ease-in-out;
        }

        .register-card h3 {
            color: white;
            font-weight: bold;
            font-size: 1.8rem;
            margin-bottom: 20px;
        }

        .register-card input {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 12px;
            border-radius: 8px;
            width: 100%;
            margin-bottom: 15px;
            transition: 0.3s;
        }

        .register-card input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .register-card input:focus {
            background: rgba(255, 255, 255, 0.3);
            outline: none;
            border-color: #ffc107;
        }

        .register-card .btn {
            width: 100%;
            padding: 12px;
            font-size: 1rem;
            font-weight: bold;
            border-radius: 8px;
            transition: 0.3s;
        }

        .btn-primary {
            background: #ffc107 !important;
            color: black !important;
            border: none !important;
        }

        .btn-primary:hover {
            background: rgba(224, 179, 0, 0.8) !important;
            color: white !important;
        }

        .register-card p a {
            color: #ffc107;
            font-weight: bold;
            text-decoration: none;
            transition: 0.3s;
            padding: 8px 15px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
        }

        .register-card p a:hover {
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

        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            padding: 20px 0;
            transition: all 0.3s ease;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-size: 1.8rem;
            color: #000 !important;
            font-weight: 700;
            transition: all 0.3s ease;
            padding: 0 15px;
        }

        .navbar-brand i {
            font-size: 1.6rem;
            margin-right: 10px;
        }

        .nav-link {
            color: #000 !important;
            font-weight: 600;
            font-size: 1.1rem;
            padding: 12px 20px !important;
            margin: 0 8px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-link i {
            font-size: 1.1rem;
            margin-right: 8px;
        }

        .navbar-nav {
            gap: 10px;
        }

        .alert {
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid rgba(220, 53, 69, 0.3);
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert p {
            margin: 0;
        }

        /* Remove floating images and their animations */
        .floating-images, .floating-image {
            display: none;
        }

        /* Adjust feature list spacing */
        .feature-list {
            margin: 20px 0;
        }

        .feature-item {
            margin-bottom: 15px;
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
                    <li class="nav-item">
                        <a class="nav-link" href="employee_dashboard.php">
                            <i class="fas fa-user-tie me-1"></i>Employee
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
                <p class="lead">Join our exclusive community and discover premium leather accessories</p>
                
                <ul class="feature-list">
                    <li class="feature-item">
                        <i class="fas fa-star"></i>
                        <div class="feature-text">
                            <strong>Automated Booking System</strong><br>
                            Easily book and manage your reservations online, eliminating manual processes and reducing errors.
                        </div>
                    </li>
                    <li class="feature-item">
                        <i class="fas fa-gift"></i>
                        <div class="feature-text">
                            <strong>Paperless and Eco-Friendly System</strong><br>
                            Go digital! Reduce reliance on physical materials by streamlining booking and verification.
                        </div>
                    </li>
                    <li class="feature-item">
                        <i class="fas fa-shield-alt"></i>
                        <div class="feature-text">
                            <strong>Seamless Experience for Businesses and Clients</strong><br>
                            A fast, secure, and scalable solution designed for personalized souvenir and event package services.
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="right-side">
            <div class="register-card">
                <h3>Create Account</h3>
                <?php if (!empty($errors)): ?>
                    <div class="alert">
                        <?php foreach ($errors as $error): ?>
                            <p><?php echo $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <form action="" method="post">
                    <div class="mb-3">
                        <input type="text" name="username" class="form-control" placeholder="Username" required
                            value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">SIGN UP</button>
                </form>
                <p class="text-center mt-3">Already have an account? <a href="login.php">Login</a></p>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Registration Successful</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <h4 class="text-success">Registered Successfully!</h4>
                    <p>Your account has been created. You can now login with your username and password.</p>
                </div>
                <div class="modal-footer">
                    <a href="login.php" class="btn btn-success">Go to Login</a>
                </div>
            </div>
        </div>
    </div>

    <?php if ($success): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var successModal = new bootstrap.Modal(document.getElementById("successModal"));
            successModal.show();
        });
    </script>
    <?php endif; ?>

    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
<?php
session_start();
include 'database/connect_db.php'; // Database connection

$success = false; // Variable to track successful registration

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $role = "U"; // Default role as User

        $sql = "INSERT INTO accounts (username, password, role) VALUES ('$email', '$hashed_password', '$role')";

        if (mysqli_query($conn, $sql)) {
            $success = true; // Registration was successful
        } else {
            echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EZ Leather Bar - Sign Up</title>

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
            overflow-x: hidden; /* Prevent horizontal scrolling */
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

.register-card {
    background: rgba(255, 255, 255, 0.1);
    padding: 20px;
    border-radius: 10px;
    backdrop-filter: blur(10px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    max-width: 400px;
    width: 100%;
    margin: auto;
    color: white;
    animation: fadeInDown 1s ease-in-out; /* Apply fade-in-down effect */
}

.register-card h3 {
    text-align: center;
    margin-bottom: 20px;
    font-weight: bold;
}

.register-card input {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border: none;
}

.register-card input::placeholder {
    color: rgba(255, 255, 255, 0.7);
}

.register-card input:focus {
    background: rgba(255, 255, 255, 0.3);
    color: white;
}

.register-card .btn-primary {
    background-color: #ffc107;
    color: black;
    font-size: 1.1rem;
    border-radius: 30px;
    transition: 0.3s;
    margin-top: 10px;
}

.register-card .btn-primary:hover {
    background-color: rgba(224, 179, 0, 0.63);
    color: white;
}

.register-card p {
    margin-top: 15px;
    text-align: center;
}

.register-card p a {
    color: rgb(249, 253, 255);
    font-weight: bold;
    text-decoration: none;
    transition: color 0.3s;
    background-color: rgb(30, 70, 231);
    padding: 10px;
    border-radius: 5px;
}

.register-card p a:hover {
    background-color: rgb(20, 50, 200);
}
    </style>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm fixed-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">EZ Leather Bar</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="#home">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#about">About us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contact">Contact us</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="main-container">
    <div class="left-side">
        <div class="overlay"></div>
    </div>
    <div class="right-side">
    <div class="register-card">
                    <h3 class="sign-up-text">SIGN UP</h3>

                    <form action="" method="post">
                        <div class="mb-3">
                            <input type="text" name="full_name" class="form-control" placeholder="Full Name" required>
                        </div>
                        <div class="mb-3">
                            <input type="email" name="email" class="form-control" placeholder="Email Address" required>
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
            </div>
            <div class="modal-footer">
                <a href="index.php" class="btn btn-success">Go to Login</a>
            </div>
        </div>
    </div>
</div>

<!-- Show Modal if Registration is Successful -->
<?php if ($success): ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var successModal = new bootstrap.Modal(document.getElementById("successModal"));
        successModal.show();
    });
</script>
<?php endif; ?>

</body>
</html>
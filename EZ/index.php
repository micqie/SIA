<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EZ Leather Bar - Welcome</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
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
        }

      
        .main-container {
            display: flex;
            min-height: 100vh;
        }
        .left-side {
            flex: 1;
            background: url('assets/bg.png') no-repeat center center;
            background-size: cover;
            position: relative;
            width: 40px;
        }
        .right-side {
            margin-top: 30px;

            background: url('assets/right-bg.jpg') no-repeat center center;
           width: 40px;
           margin-right: 10px;
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
            margin-top: 30px;
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
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm fixed-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">EZ Leather Bar</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link  "   style="margin-right: 60px;" href="#home">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"  style="margin-right: 70px;"  href="#about">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"  style="margin-right: 80px; " href="#contact">Contact Us</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="main-container">
    <div class="left-side" >
        <div class="overlay"></div>
        <div class="container hero-content" id="home">
            <div class="hero-section">
                <div class="hero-text">
                    <h1>Welcome to EZ Leather Bar</h1>
                    <p style="color: #f9e046;">Premium Personalized Leathers - Crafted Just for You!</p>
                    <div class="buttons">
                        <a href="login.php" class="btn btn-custom">Get Started</a>  
                        <a href="guest.php" class="btn btn-custom">Guest</a>
                        <a href="sign_up.php" class="btn btn-custom">Sign Up</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="right-side">
        <div class="hero-card">
            <img src="assets/logo2.jpg" alt="Leather Keychains">
        </div>
    </div>
</div>

<section id="about" class="section bg-light">
    <div class="container">
        <h2>About Us</h2>
        <p>EZ Leather Bar specializes in high-quality handcrafted leather products. We take pride in offering premium leather accessories with exquisite designs.</p>
        <img src="images/about-us.jpg" class="img-fluid rounded" alt="About Us">
    </div>
</section>

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

<footer class="text-center py-3 bg-dark text-light">
    <p>&copy; 2025 EZ Leather Bar. All rights reserved.</p>
</footer>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
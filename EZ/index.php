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
                        <a class="nav-link" href="#home">
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

<!-- About Us Section -->
<section id="about" class="section" style="background: rgba(0, 0, 0, 0.8); padding: 80px 0;">
    <div class="container">
        <h2 class="display-4 mb-5" style="color: #ffc107;">About Us</h2>
        <div class="row align-items-center">
            <div class="col-md-6 mb-4">
                <div class="about-image-container" style="position: relative; overflow: hidden; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
                    <img src="assets/image.png" class="img-fluid" alt="Leather Crafting" style="width: 100%; transform: scale(1.02);">
                    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.2);"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="about-content" style="padding: 30px; background: rgba(255,255,255,0.1); border-radius: 20px;">
                    <h3 style="color: #ffc107; margin-bottom: 20px;">Our Craft & Passion</h3>
                    <p class="lead" style="color: white; margin-bottom: 20px;">EZ Leather Bar specializes in creating premium quality leather products that combine traditional craftsmanship with modern design.</p>
                    <div class="features" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="feature" style="text-align: center; padding: 15px; background: rgba(255,255,255,0.05); border-radius: 10px;">
                            <i class="fas fa-hand-holding-heart" style="font-size: 2rem; color: #ffc107; margin-bottom: 10px;"></i>
                            <h5 style="color: white;">Quality Materials</h5>
                        </div>
                        <div class="feature" style="text-align: center; padding: 15px; background: rgba(255,255,255,0.05); border-radius: 10px;">
                            <i class="fas fa-star" style="font-size: 2rem; color: #ffc107; margin-bottom: 10px;"></i>
                            <h5 style="color: white;">Expert Crafting</h5>
                        </div>
                        <div class="feature" style="text-align: center; padding: 15px; background: rgba(255,255,255,0.05); border-radius: 10px;">
                            <i class="fas fa-gem" style="font-size: 2rem; color: #ffc107; margin-bottom: 10px;"></i>
                            <h5 style="color: white;">Premium Finish</h5>
                        </div>
                        <div class="feature" style="text-align: center; padding: 15px; background: rgba(255,255,255,0.05); border-radius: 10px;">
                            <i class="fas fa-heart" style="font-size: 2rem; color: #ffc107; margin-bottom: 10px;"></i>
                            <h5 style="color: white;">Made with Love</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="section" style="background: rgba(0, 0, 0, 0.7); padding: 80px 0;">
    <div class="container">
        <h2 class="display-4 mb-5" style="color: #ffc107;">Contact Us</h2>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div style="background: rgba(255,255,255,0.1); padding: 40px; border-radius: 20px; backdrop-filter: blur(10px);">
                    <div class="row mb-4">
                        <div class="col-md-6 mb-4">
                            <div style="padding: 20px; background: rgba(255,255,255,0.05); border-radius: 15px;">
                                <i class="fas fa-phone" style="font-size: 2rem; color: #ffc107; margin-bottom: 15px;"></i>
                                <h4 style="color: white;">Call Us</h4>
                                <p style="color: #ddd;">09526313663</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div style="padding: 20px; background: rgba(255,255,255,0.05); border-radius: 15px;">
                                <i class="fas fa-envelope" style="font-size: 2rem; color: #ffc107; margin-bottom: 15px;"></i>
                                <h4 style="color: white;">Email Us</h4>
                                <p style="color: #ddd;">ezleatherbarcdo@gmail.com</p>
                            </div>
                        </div>
                    </div>
                    <div class="social-icons" style="margin-top: 30px;">
                        <a href="https://web.facebook.com/profile.php?id=61558304313354" class="social-link" style="color: white; text-decoration: none; display: inline-block; margin: 0 15px; transition: all 0.3s ease;" onmouseover="this.style.color='#ffc107'" onmouseout="this.style.color='white'">
                            <i class="fab fa-facebook me-2"></i>
                            <span>EZ Leather Bar CDO</span>
                        </a>
                        <a href="https://instagram.com/ezleatherbarcdo" class="social-link" style="color: white; text-decoration: none; display: inline-block; margin: 0 15px; transition: all 0.3s ease;" onmouseover="this.style.color='#ffc107'" onmouseout="this.style.color='white'">
                            <i class="fab fa-instagram me-2"></i>
                            <span>@ezleatherbarcdo</span>
                        </a>
                        <a href="https://tiktok.com/@ezleatherbarcdoo" class="social-link" style="color: white; text-decoration: none; display: inline-block; margin: 0 15px; transition: all 0.3s ease;" onmouseover="this.style.color='#ffc107'" onmouseout="this.style.color='white'">
                            <i class="fab fa-tiktok me-2"></i>
                            <span>@ezleatherbarcdoo</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer style="background: rgba(0, 0, 0, 0.9); padding: 50px 0 20px; color: white;">
    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-4">
                <h3 style="color: #ffc107; margin-bottom: 20px;">EZ Leather Bar</h3>
                <p style="color: #ddd;">Crafting premium leather products with passion and precision. Your satisfaction is our priority.</p>
            </div>
            <div class="col-md-6 mb-4">
                <h3 style="color: #ffc107; margin-bottom: 20px;">Quick Links</h3>
                <ul style="list-style: none; padding: 0;">
                    <li style="margin-bottom: 10px;"><a href="#home" style="color: #ddd; text-decoration: none; transition: all 0.3s ease;" onmouseover="this.style.color='#ffc107'" onmouseout="this.style.color='#ddd'">Home</a></li>
                    <li style="margin-bottom: 10px;"><a href="#about" style="color: #ddd; text-decoration: none; transition: all 0.3s ease;" onmouseover="this.style.color='#ffc107'" onmouseout="this.style.color='#ddd'">About Us</a></li>
                    <li style="margin-bottom: 10px;"><a href="#contact" style="color: #ddd; text-decoration: none; transition: all 0.3s ease;" onmouseover="this.style.color='#ffc107'" onmouseout="this.style.color='#ddd'">Contact</a></li>
                </ul>
            </div>
        </div>
        <div class="text-center" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.1);">
            <p style="color: #ddd; margin: 0;">&copy; 2024 EZ Leather Bar. All rights reserved.</p>
        </div>
    </div>
</footer>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
<?php
session_start();
require 'database/connect_db.php';

// Fetch all active packages
$packages_query = "SELECT p.*, 
                  GROUP_CONCAT(pr.product_name) as products,
                  GROUP_CONCAT(pp.quantity) as quantities
                  FROM packages p
                  LEFT JOIN package_products pp ON p.package_id = pp.package_id
                  LEFT JOIN products pr ON pp.product_id = pr.product_id
                  WHERE p.is_active = 1
                  GROUP BY p.package_id";
$result = mysqli_query($conn, $packages_query);
$packages = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Packages - EZ Leather Bar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #F8E2A8, #9D4D36);
            min-height: 100vh;
            font-family: 'Arial', sans-serif;
            padding-bottom: 60px;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .main-container {
            margin-top: 80px;
            padding: 20px;
        }

        .package-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s;
            border: 2px solid transparent;
        }

        .package-card:hover {
            transform: translateY(-5px);
            border-color: #9D4D36;
        }

        .package-header {
            border-bottom: 2px solid #F8E2A8;
            margin-bottom: 20px;
            padding-bottom: 15px;
        }

        .package-price {
            font-size: 2rem;
            color: #9D4D36;
            font-weight: bold;
        }

        .package-features {
            list-style: none;
            padding: 0;
            margin: 0 0 20px 0;
        }

        .package-features li {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        .package-features li:last-child {
            border-bottom: none;
        }

        .package-features i {
            color: #9D4D36;
            margin-right: 10px;
        }

        .btn-brown {
            background-color: #9D4D36;
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
            transition: all 0.3s;
        }

        .btn-brown:hover {
            background-color: #8B3D26;
            color: white;
            transform: translateY(-2px);
        }

        .section-title {
            color: white;
            text-align: center;
            margin-bottom: 40px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .package-badge {
            position: absolute;
            top: -10px;
            right: 20px;
            background: #F8E2A8;
            color: #9D4D36;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-store-alt me-2"></i>EZ Leather Bar
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['role'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $_SESSION['role'] === 'A' ? 'admin_dashboard.php' : 'user_dashboard.php'; ?>">
                                <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="main-container container">
        <h2 class="section-title">Choose Your Perfect Package</h2>
        
        <div class="row">
            <?php foreach ($packages as $package): ?>
                <div class="col-md-6 col-lg-3">
                    <div class="package-card position-relative">
                        <?php if ($package['package_name'] === 'Wedding Collection'): ?>
                            <div class="package-badge">Most Popular</div>
                        <?php endif; ?>
                        
                        <div class="package-header">
                            <h3><?php echo $package['package_name']; ?></h3>
                            <div class="package-price">
                                ₱<?php echo number_format($package['base_price'], 2); ?>
                            </div>
                        </div>
                        
                        <ul class="package-features">
                            <?php
                            $products = explode(',', $package['products']);
                            $quantities = explode(',', $package['quantities']);
                            for ($i = 0; $i < count($products); $i++): ?>
                                <li>
                                    <i class="fas fa-check"></i>
                                    <?php echo $quantities[$i] . 'x ' . $products[$i]; ?>
                                </li>
                            <?php endfor; ?>
                            <li><i class="fas fa-check"></i>Free Customization</li>
                            <li><i class="fas fa-check"></i>Gift Packaging</li>
                        </ul>
                        
                        <div class="text-center">
                            <a href="user_dashboard.php?package=<?php echo $package['package_id']; ?>" 
                               class="btn btn-brown">
                                <i class="fas fa-shopping-cart me-2"></i>Select Package
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 
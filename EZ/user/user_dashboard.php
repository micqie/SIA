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
    <title>Bundle Booking Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 0;
        }
        .navbar {
            background-color: #333;
            padding: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            color: white !important;
            font-weight: bold;
        }
        .nav-link {
            color: rgba(255,255,255,0.8) !important;
            transition: color 0.3s;
        }
        .nav-link:hover {
            color: white !important;
        }
        .main-content {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .bundle-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin: 20px 0;
        }
        .bundle-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .bundle-card:hover {
            transform: translateY(-5px);
        }
        .bundle-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }
        .bundle-details {
            padding: 20px;
        }
        .bundle-title {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #333;
        }
        .bundle-description {
            color: #666;
            margin-bottom: 15px;
        }
        .bundle-items {
            margin-bottom: 15px;
        }
        .bundle-item {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
            padding: 5px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        .bundle-item i {
            margin-right: 10px;
            color: #28a745;
        }
        .bundle-price {
            font-size: 1.8rem;
            color: #28a745;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .bundle-actions {
            display: flex;
            gap: 10px;
        }
        .booking-form {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .date-picker {
            margin-bottom: 15px;
        }
        .quantity-selector {
            width: 100px;
        }
        .cart-icon {
            position: relative;
        }
        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
        }
        .welcome-section {
            background: linear-gradient(135deg, #333 0%, #1a1a1a 100%);
            padding: 40px;
            border-radius: 15px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
            color: white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .welcome-content {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .welcome-text {
            flex: 1;
        }

        .welcome-image {
            flex: 1;
            text-align: center;
            position: relative;
        }

        .welcome-image img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            animation: float 3s ease-in-out infinite;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .stat-card {
            background: rgba(255,255,255,0.1);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            backdrop-filter: blur(5px);
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #ffc107;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #e0e0e0;
            font-size: 0.9rem;
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
            100% {
                transform: translateY(0px);
            }
        }

        .decoration-circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            animation: pulse 4s infinite;
        }

        .circle-1 {
            width: 100px;
            height: 100px;
            top: -20px;
            left: -20px;
            animation-delay: 0s;
        }

        .circle-2 {
            width: 150px;
            height: 150px;
            bottom: -40px;
            right: -40px;
            animation-delay: 1s;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 0.5;
            }
            50% {
                transform: scale(1.2);
                opacity: 0.3;
            }
            100% {
                transform: scale(1);
                opacity: 0.5;
            }
        }

        .bundle-container {
            margin-top: 0;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">EZ Bundle Store</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#"><i class="fas fa-home"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-calendar-alt"></i> My Bookings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-user"></i> Profile</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <a href="#" class="nav-link cart-icon me-3">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count">0</span>
                    </a>
                    <a href="logout.php" class="nav-link">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="main-content">
        <div class="welcome-section">
            <div class="decoration-circle circle-1"></div>
            <div class="decoration-circle circle-2"></div>
            <div class="welcome-content">
                <div class="welcome-text">
                    <h1>Welcome to EZ Bundle Store</h1>
                    <p class="lead">Discover our exclusive collection of premium leather accessories and luxury fragrances.</p>
                    <div class="stats-container">
                        <div class="stat-card">
                            <div class="stat-number">5+</div>
                            <div class="stat-label">Premium Leather Items</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">10</div>
                            <div class="stat-label">Unique Fragrances</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">100%</div>
                            <div class="stat-label">Quality Guaranteed</div>
                        </div>
                    </div>
                </div>
                <div class="welcome-image">
                    <img src="https://images.unsplash.com/photo-1590874103328-eac38a683ce7" alt="Premium Collection">
                </div>
            </div>
        </div>

        <h2 class="text-center mb-4">Available Bundles</h2>
        
        <div class="bundle-container">
            <!-- Leather Bundle -->
            <div class="bundle-card">
            <img src="https://images.unsplash.com/photo-1590874103328-eac38a683ce7" alt="Leather Bundle" class="bundle-image">
                <div class="bundle-details">
                    <h3 class="bundle-title">Premium Leather Bundle</h3>
                    <p class="bundle-description">Complete set of handcrafted leather accessories for medical professionals.</p>
                    <div class="bundle-items">
                        <div class="bundle-item">
                            <i class="fas fa-check"></i>
                            <span>Professional Bag Tag</span>
                        </div>
                        <div class="bundle-item">
                            <i class="fas fa-check"></i>
                            <span>Stethoscope Holder</span>
                        </div>
                        <div class="bundle-item">
                            <i class="fas fa-check"></i>
                            <span>Leather Keychain</span>
                        </div>
                        <div class="bundle-item">
                            <i class="fas fa-check"></i>
                            <span>ID Badge Holder</span>
                        </div>
                        <div class="bundle-item">
                            <i class="fas fa-check"></i>
                            <span>Medical Tool Pouch</span>
        </div>
        </div>
                    <div class="bundle-price">$149.99</div>
                    <div class="bundle-actions">
                        <button class="btn btn-primary" onclick="openBookingModal('Premium Leather Bundle', 149.99)">
                            <i class="fas fa-calendar-plus"></i> Book Now
                        </button>
                        <button class="btn btn-outline-primary" onclick="viewBundleDetails('leather')">
                            <i class="fas fa-info-circle"></i> Details
                        </button>
        </div>
        </div>
        </div>

            <!-- Perfume Bundle -->
            <div class="bundle-card">
                <img src="https://images.unsplash.com/photo-1541643600914-78b084683601" alt="Perfume Bundle" class="bundle-image">
                <div class="bundle-details">
                    <h3 class="bundle-title">Luxury Fragrance Set</h3>
                    <p class="bundle-description">Collection of 10 premium scents for every occasion.</p>
                    <div class="bundle-items">
                        <div class="bundle-item">
                            <i class="fas fa-check"></i>
                            <span>Set 1: Fresh & Citrus Collection</span>
                            <small class="text-muted ms-2">(5 scents)</small>
                        </div>
                        <div class="bundle-item">
                            <i class="fas fa-check"></i>
                            <span>Set 2: Floral & Sweet Collection</span>
                            <small class="text-muted ms-2">(5 scents)</small>
                        </div>
                        <div class="bundle-item">
                            <i class="fas fa-star text-warning"></i>
                            <span>Includes Travel-Size Versions</span>
                        </div>
                        <div class="bundle-item">
                            <i class="fas fa-gift text-info"></i>
                            <span>Luxury Gift Box Packaging</span>
                        </div>
                        <div class="bundle-item">
                            <i class="fas fa-spray-can"></i>
                            <span>10ml Each Fragrance</span>
                        </div>
                    </div>
                    <div class="bundle-price">$299.99</div>
                    <div class="bundle-actions">
                        <button class="btn btn-primary" onclick="openBookingModal('Luxury Fragrance Set', 299.99)">
                            <i class="fas fa-calendar-plus"></i> Book Now
                        </button>
                        <button class="btn btn-outline-primary" onclick="viewBundleDetails('perfume')">
                            <i class="fas fa-info-circle"></i> Details
                        </button>
                    </div>
                </div>
        </div>
        </div>
    </div>

    <!-- Booking Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Book Bundle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="bookingForm">
                        <div class="mb-3">
                            <label class="form-label">Selected Bundle:</label>
                            <input type="text" class="form-control" id="selectedBundle" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Preferred Date:</label>
                            <input type="date" class="form-control" id="bookingDate" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Quantity:</label>
                            <input type="number" class="form-control quantity-selector" id="quantity" min="1" value="1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Total Price:</label>
                            <input type="text" class="form-control" id="totalPrice" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Special Requests:</label>
                            <textarea class="form-control" id="specialRequests" rows="3" placeholder="Any specific preferences or customization requests?"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="confirmBooking()">Confirm Booking</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bundle Details Modal -->
    <div class="modal fade" id="bundleDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bundleDetailsTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="bundleDetailsContent">
                </div>
            </div>
        </div>
    </div>

    <script>
        let cartCount = 0;
        let currentBundlePrice = 0;

        const bundleDetails = {
            leather: {
                title: "Premium Leather Bundle Details",
                content: `
                    <h4>Leather Items Specifications:</h4>
                    <ul>
                        <li><strong>Professional Bag Tag:</strong> Personalized name tag with adjustable strap</li>
                        <li><strong>Stethoscope Holder:</strong> Secure holder with quick-release clip</li>
                        <li><strong>Leather Keychain:</strong> Durable with custom engraving option</li>
                        <li><strong>ID Badge Holder:</strong> Retractable with reinforced clip</li>
                        <li><strong>Medical Tool Pouch:</strong> Multiple compartments for organization</li>
                    </ul>
                    <p><strong>Material:</strong> Premium genuine leather</p>
                    <p><strong>Customization:</strong> Available for all items</p>
                    <p><strong>Warranty:</strong> 1-year warranty on all items</p>
                `
            },
            perfume: {
                title: "Luxury Fragrance Set Details",
                content: `
                    <h4>Set 1: Fresh & Citrus Collection</h4>
                    <ul>
                        <li>Ocean Breeze - Fresh aquatic scent</li>
                        <li>Citrus Burst - Energizing lemon and orange</li>
                        <li>Morning Dew - Light and refreshing</li>
                        <li>Green Tea - Subtle and calming</li>
                        <li>Mediterranean - Bright and invigorating</li>
                    </ul>
                    <h4>Set 2: Floral & Sweet Collection</h4>
                    <ul>
                        <li>Rose Garden - Classic floral blend</li>
                        <li>Sweet Vanilla - Warm and comforting</li>
                        <li>Jasmine Night - Rich and exotic</li>
                        <li>Cherry Blossom - Light and sweet</li>
                        <li>Lavender Dreams - Soothing and elegant</li>
                    </ul>
                    <p><strong>Volume:</strong> 10ml each fragrance</p>
                    <p><strong>Type:</strong> Eau de Parfum</p>
                    <p><strong>Longevity:</strong> 8-12 hours</p>
                `
            }
        };

        function openBookingModal(bundleName, price) {
            currentBundlePrice = price;
            document.getElementById('selectedBundle').value = bundleName;
            document.getElementById('totalPrice').value = `$${price}`;
            
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('bookingDate').min = today;
            
            new bootstrap.Modal(document.getElementById('bookingModal')).show();
        }

        function updateTotalPrice() {
            const quantity = document.getElementById('quantity').value;
            const total = (currentBundlePrice * quantity).toFixed(2);
            document.getElementById('totalPrice').value = `$${total}`;
        }

        function confirmBooking() {
            const bundle = document.getElementById('selectedBundle').value;
            const date = document.getElementById('bookingDate').value;
            const quantity = document.getElementById('quantity').value;
            const requests = document.getElementById('specialRequests').value;
            
            if (!date) {
                alert('Please select a date');
                return;
            }

            cartCount++;
            document.querySelector('.cart-count').innerText = cartCount;
            alert(`Booking confirmed!\nBundle: ${bundle}\nDate: ${date}\nQuantity: ${quantity}\nSpecial Requests: ${requests}`);
            
            bootstrap.Modal.getInstance(document.getElementById('bookingModal')).hide();
        }

        function viewBundleDetails(bundleType) {
            const details = bundleDetails[bundleType];
            document.getElementById('bundleDetailsTitle').innerText = details.title;
            document.getElementById('bundleDetailsContent').innerHTML = details.content;
            new bootstrap.Modal(document.getElementById('bundleDetailsModal')).show();
        }

        document.getElementById('quantity').addEventListener('change', updateTotalPrice);
    </script>
</body>
</html>


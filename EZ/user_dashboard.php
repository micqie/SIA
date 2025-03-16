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
    <title>EZ Leather Bar - Booking Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root {
            --primary-color: #ffc107;
            --primary-hover: rgba(224, 179, 0, 0.8);
            --text-light: #ffffff;
            --text-dark: #000000;
            --overlay-bg: rgba(0, 0, 0, 0.6);
            --card-bg: rgba(255, 255, 255, 0.1);
        }

        body {
            font-family: 'Poppins', Arial, sans-serif;
            background-image: url(assets/leather_bg.png);
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            padding-top: 80px;
        }

        /* Enhanced Navbar Styles */
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            padding: 20px 0;
            transition: all 0.3s ease;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-size: 2rem;
            color: var(--text-dark) !important;
            font-weight: 800;
            transition: all 0.3s ease;
            padding: 0 15px;
            letter-spacing: 1px;
        }

        .navbar-brand:hover {
            color: var(--primary-color) !important;
            transform: translateY(-2px);
        }

        .navbar-brand i {
            font-size: 1.8rem;
            margin-right: 12px;
            color: var(--primary-color);
        }

        .nav-link {
            color: var(--text-dark) !important;
            font-weight: 600;
            font-size: 1.1rem;
            padding: 12px 24px !important;
            margin: 0 8px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
            transform: translateY(-2px);
        }

        /* Main Content Styles */
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .welcome-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            color: var(--text-light);
            text-align: center;
        }

        .category-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            color: var(--text-light);
        }

        .bundle-info {
            background: rgba(255, 255, 255, 0.2);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            border: 1px solid var(--primary-color);
        }

        .bundle-info h3 {
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .bundle-info p {
            margin-bottom: 5px;
            color: var(--text-light);
        }

        .bundle-highlight {
            font-size: 1.2rem;
            color: var(--primary-color);
            font-weight: 600;
            margin: 10px 0;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .product-card {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.2);
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .product-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--text-light);
        }

        .product-price {
            font-size: 1.4rem;
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 15px;
        }

        .book-btn {
            background: var(--primary-color);
            color: var(--text-dark);
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
        }

        .book-btn:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
        }

        /* Modal Styles */
        .modal-content {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
        }

        .modal-header {
            border-bottom: none;
            padding: 20px;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-footer {
            border-top: none;
            padding: 20px;
        }

        .quantity-input {
            width: 100px;
            text-align: center;
            margin: 0 10px;
        }

        /* Cart Styles */
        .cart-icon {
            position: relative;
            font-size: 1.5rem;
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--primary-color);
            color: var(--text-dark);
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 0.8rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-store-alt"></i>EZ Leather Bar
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-home me-2"></i>Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-shopping-cart me-2"></i>My Bookings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-user me-2"></i>Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-container">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <h1>Welcome to EZ Leather Bar Booking</h1>
            <p>Book your bundle package - Each bundle contains 200 pieces</p>
            <div class="bundle-info">
                <h3>Bundle Package Information</h3>
                <p><i class="fas fa-box me-2"></i>Each bundle contains 200 pieces</p>
                <p><i class="fas fa-users me-2"></i>Designed for 1 pax</p>
                <p><i class="fas fa-info-circle me-2"></i>Choose between Leather Items or Perfume Set</p>
            </div>
        </div>

        <!-- Leather Bundle Section -->
        <div class="category-section">
            <h2><i class="fas fa-briefcase me-2"></i>Leather Bundle Package</h2>
            <div class="bundle-info">
                <p>200 pieces of your chosen leather item:</p>
                <ul class="list-unstyled">
                    <li><i class="fas fa-check me-2"></i>Desk Organizer</li>
                    <li><i class="fas fa-check me-2"></i>Cord Organizer</li>
                    <li><i class="fas fa-check me-2"></i>Keychain</li>
                    <li><i class="fas fa-check me-2"></i>Stethoscope Sleeve</li>
                    <li><i class="fas fa-check me-2"></i>Bag Tags</li>
                    <li><i class="fas fa-check me-2"></i>Coin Purse</li>
                </ul>
            </div>
            <div class="product-grid">
                <!-- Desk Organizer Bundle -->
                <div class="product-card">
                    <img src="assets/desk_organizer.jpg" alt="Desk Organizer Bundle" class="product-image">
                    <h3 class="product-title">Desk Organizer Bundle</h3>
                    <p class="bundle-highlight">200 pieces</p>
                    <p class="product-price">₱180,000</p>
                    <button class="book-btn" data-bs-toggle="modal" data-bs-target="#bookingModal" 
                            data-product="Desk Organizer Bundle" data-price="180000" data-type="leather">
                        <i class="fas fa-box me-2"></i>Book Bundle
                    </button>
                </div>

                <!-- Cord Organizer Bundle -->
                <div class="product-card">
                    <img src="assets/cord_organizer.jpg" alt="Cord Organizer Bundle" class="product-image">
                    <h3 class="product-title">Cord Organizer Bundle</h3>
                    <p class="bundle-highlight">200 pieces</p>
                    <p class="product-price">₱150,000</p>
                    <button class="book-btn" data-bs-toggle="modal" data-bs-target="#bookingModal"
                            data-product="Cord Organizer Bundle" data-price="150000" data-type="leather">
                        <i class="fas fa-box me-2"></i>Book Bundle
                    </button>
                </div>

                <!-- Keychain Bundle -->
                <div class="product-card">
                    <img src="assets/keychain.jpg" alt="Keychain Bundle" class="product-image">
                    <h3 class="product-title">Keychain Bundle</h3>
                    <p class="bundle-highlight">200 pieces</p>
                    <p class="product-price">₱50,000</p>
                    <button class="book-btn" data-bs-toggle="modal" data-bs-target="#bookingModal"
                            data-product="Keychain Bundle" data-price="50000" data-type="leather">
                        <i class="fas fa-box me-2"></i>Book Bundle
                    </button>
                </div>

                <!-- Stethoscope Sleeve Bundle -->
                <div class="product-card">
                    <img src="assets/stethoscope_sleeve.jpg" alt="Stethoscope Sleeve Bundle" class="product-image">
                    <h3 class="product-title">Stethoscope Sleeve Bundle</h3>
                    <p class="bundle-highlight">200 pieces</p>
                    <p class="product-price">₱100,000</p>
                    <button class="book-btn" data-bs-toggle="modal" data-bs-target="#bookingModal"
                            data-product="Stethoscope Sleeve Bundle" data-price="100000" data-type="leather">
                        <i class="fas fa-box me-2"></i>Book Bundle
                    </button>
                </div>

                <!-- Bag Tags Bundle -->
                <div class="product-card">
                    <img src="assets/bagtags.jpg" alt="Bag Tags Bundle" class="product-image">
                    <h3 class="product-title">Bag Tags Bundle</h3>
                    <p class="bundle-highlight">200 pieces</p>
                    <p class="product-price">₱70,000</p>
                    <button class="book-btn" data-bs-toggle="modal" data-bs-target="#bookingModal"
                            data-product="Bag Tags Bundle" data-price="70000" data-type="leather">
                        <i class="fas fa-box me-2"></i>Book Bundle
                    </button>
                </div>

                <!-- Coin Purse Bundle -->
                <div class="product-card">
                    <img src="assets/coin_purse.jpg" alt="Coin Purse Bundle" class="product-image">
                    <h3 class="product-title">Coin Purse Bundle</h3>
                    <p class="bundle-highlight">200 pieces</p>
                    <p class="product-price">₱90,000</p>
                    <button class="book-btn" data-bs-toggle="modal" data-bs-target="#bookingModal"
                            data-product="Coin Purse Bundle" data-price="90000" data-type="leather">
                        <i class="fas fa-box me-2"></i>Book Bundle
                    </button>
                </div>
            </div>
        </div>

        <!-- Perfume Bundle Section -->
        <div class="category-section">
            <h2><i class="fas fa-spray-can me-2"></i>Perfume Bundle Package</h2>
            <div class="bundle-info">
                <p>200 sets of our exclusive 10-scent collection:</p>
                <p><i class="fas fa-star me-2"></i>Each set contains 10 unique fragrances</p>
                <p><i class="fas fa-check me-2"></i>Premium quality scents</p>
                <p><i class="fas fa-box me-2"></i>Beautifully packaged in gift boxes</p>
            </div>
            <div class="product-card">
                <img src="assets/perfume_set.jpg" alt="Perfume Bundle" class="product-image">
                <h3 class="product-title">Premium Perfume Bundle</h3>
                <p class="bundle-highlight">200 sets × 10 scents</p>
                <p class="product-description">Wholesale package of our exclusive fragrance collection</p>
                <p class="product-price">₱350,000</p>
                <button class="book-btn" data-bs-toggle="modal" data-bs-target="#bookingModal"
                        data-product="Premium Perfume Bundle" data-price="350000" data-type="perfume">
                    <i class="fas fa-box me-2"></i>Book Bundle
                </button>
            </div>
        </div>
    </div>

    <!-- Booking Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Book Bundle Package</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="bookingForm">
                        <div class="bundle-info mb-4">
                            <p class="mb-0"><i class="fas fa-box me-2"></i>Each bundle contains 200 pieces/sets</p>
                            <p class="mb-0"><i class="fas fa-users me-2"></i>Designed for 1 pax</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Selected Bundle:</label>
                            <input type="text" class="form-control" id="selectedItem" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Bundle Price:</label>
                            <input type="text" class="form-control" id="itemPrice" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Number of Bundles:</label>
                            <div class="d-flex align-items-center">
                                <button type="button" class="btn btn-outline-secondary" onclick="updateQuantity(-1)">-</button>
                                <input type="number" class="form-control quantity-input" id="quantity" value="1" min="1" max="5">
                                <button type="button" class="btn btn-outline-secondary" onclick="updateQuantity(1)">+</button>
                            </div>
                            <small class="text-muted">Each bundle contains 200 pieces/sets</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Total Amount:</label>
                            <input type="text" class="form-control" id="totalAmount" readonly>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="confirmBooking()">Confirm Bundle Booking</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Booking Modal Functionality
        const bookingModal = document.getElementById('bookingModal');
        bookingModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const product = button.getAttribute('data-product');
            const price = button.getAttribute('data-price');
            const type = button.getAttribute('data-type');

            document.getElementById('selectedItem').value = product;
            document.getElementById('itemPrice').value = '₱' + price;
            updateTotal();
        });

        function updateQuantity(change) {
            const quantityInput = document.getElementById('quantity');
            let newValue = parseInt(quantityInput.value) + change;
            newValue = Math.max(1, Math.min(5, newValue));
            quantityInput.value = newValue;
            updateTotal();
        }

        function updateTotal() {
            const price = parseInt(document.getElementById('itemPrice').value.replace('₱', ''));
            const quantity = parseInt(document.getElementById('quantity').value);
            const total = price * quantity;
            document.getElementById('totalAmount').value = '₱' + total;
        }

        function confirmBooking() {
            const item = document.getElementById('selectedItem').value;
            const quantity = document.getElementById('quantity').value;
            const total = document.getElementById('totalAmount').value;

            // Here you would typically send this data to your server
            alert(`Booking Confirmed!\n\nItem: ${item}\nQuantity: ${quantity}\nTotal: ${total}`);
            
            // Close the modal
            const modal = bootstrap.Modal.getInstance(bookingModal);
            modal.hide();
        }

        // Add event listener for quantity input
        document.getElementById('quantity').addEventListener('input', updateTotal);
    </script>
</body>
</html>


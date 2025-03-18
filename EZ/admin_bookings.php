<?php
session_start();
require 'database/connect_db.php';

// Check if admin is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'A') {
    header("Location: login.php");
    exit();
}

// Fetch all bookings with user and product details
$query = "SELECT b.*, 
          a.username,
          GROUP_CONCAT(p.product_name) as products,
          GROUP_CONCAT(bd.quantity) as quantities,
          GROUP_CONCAT(bd.unit_price) as prices
          FROM bookings b
          LEFT JOIN accounts a ON b.account_id = a.account_id
          LEFT JOIN booking_details bd ON b.booking_id = bd.booking_id
          LEFT JOIN products p ON bd.product_id = p.product_id
          GROUP BY b.booking_id
          ORDER BY b.created_at DESC";

$result = mysqli_query($conn, $query);
$bookings = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Bookings - EZ Leather Bar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }

        .navbar {
            background: #9D4D36 !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 0.5rem 1rem;
        }

        .navbar-brand {
            color: white !important;
            font-size: 1.5rem;
        }

        .profile-dropdown {
            position: relative;
            display: inline-block;
        }

        .profile-btn {
            background: none;
            border: none;
            color: white;
            padding: 0.5rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .profile-btn i {
            font-size: 1.2rem;
        }

        .profile-menu {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            min-width: 160px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            z-index: 1001;
            border-radius: 8px;
            overflow: hidden;
        }

        .profile-menu.show {
            display: block;
        }

        .profile-menu a {
            color: #333;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            transition: background-color 0.3s;
        }

        .profile-menu a:hover {
            background-color: #f8f9fa;
        }

        .profile-menu .divider {
            border-top: 1px solid #eee;
            margin: 0;
        }

        .profile-menu .logout-btn {
            color: #dc3545;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background: #9D4D36;
            padding-top: 80px;
            z-index: 1000;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }

        .sidebar .nav-link {
            color: white;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            transition: all 0.3s;
            border-left: 3px solid transparent;
            text-decoration: none;
        }

        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            border-left-color: #fff;
        }

        .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            border-left-color: #fff;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        .booking-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .booking-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .status-badge {
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .status-pending {
            background: #ffeeba;
            color: #856404;
        }

        .status-confirmed {
            background: #d4edda;
            color: #155724;
        }

        .status-declined {
            background: #f8d7da;
            color: #721c24;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-confirm {
            background: #28a745;
            color: white;
        }

        .btn-decline {
            background: #dc3545;
            color: white;
        }

        .btn-confirm:hover, .btn-decline:hover {
            color: white;
            opacity: 0.9;
        }

        .products-list {
            margin-top: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .product-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px solid #eee;
        }

        .product-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="admin_dashboard.php">
                <i class="fas fa-store-alt me-2"></i>EZ Leather Bar Admin
            </a>
            <div class="profile-dropdown">
                <button class="profile-btn" onclick="toggleProfileMenu()">
                    <i class="fas fa-user-circle"></i>
                    <span>Admin</span>
                </button>
                <div class="profile-menu" id="profileMenu">
                    <a href="admin_settings.php">
                        <i class="fas fa-user me-2"></i>Profile
                    </a>
                    <hr class="divider">
                    <a href="logout.php" class="logout-btn">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="sidebar">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="admin_dashboard.php">
                    <i class="fas fa-chart-line"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="admin_bookings.php">
                    <i class="fas fa-calendar-check"></i>Bookings
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admin_inventory.php">
                    <i class="fas fa-boxes"></i>Inventory
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admin_settings.php">
                    <i class="fas fa-cog"></i>Settings
                </a>
            </li>
        </ul>
    </div>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-calendar-check me-2"></i>Manage Bookings</h2>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary" onclick="filterBookings('all')">All</button>
                <button class="btn btn-outline-warning" onclick="filterBookings('pending')">Pending</button>
                <button class="btn btn-outline-success" onclick="filterBookings('confirmed')">Confirmed</button>
                <button class="btn btn-outline-danger" onclick="filterBookings('declined')">Declined</button>
            </div>
        </div>

        <div id="bookings-container">
            <?php foreach ($bookings as $booking): ?>
                <div class="booking-card" data-status="<?php echo strtolower($booking['status']); ?>">
                    <div class="booking-header">
                        <div>
                            <h5>Booking #<?php echo $booking['booking_id']; ?></h5>
                            <p class="mb-0">By: <?php echo htmlspecialchars($booking['username']); ?></p>
                        </div>
                        <span class="status-badge status-<?php echo strtolower($booking['status']); ?>">
                            <?php echo ucfirst($booking['status']); ?>
                        </span>
                    </div>
                    <div class="booking-details">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Booking Reference:</strong> <?php echo $booking['booking_reference']; ?></p>
                                <p><strong>Preferred Date:</strong> <?php echo date('M d, Y', strtotime($booking['preferred_date'])); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Total Amount:</strong> ₱<?php echo number_format($booking['total_amount'], 2); ?></p>
                                <p><strong>Processing Fee:</strong> ₱<?php echo number_format($booking['processing_fee'], 2); ?></p>
                            </div>
                        </div>
                        
                        <div class="products-list">
                            <h6 class="mb-3">Ordered Products</h6>
                            <?php 
                            $products = explode(',', $booking['products']);
                            $quantities = explode(',', $booking['quantities']);
                            $prices = explode(',', $booking['prices']);
                            
                            for ($i = 0; $i < count($products); $i++): ?>
                                <div class="product-item">
                                    <span><?php echo $products[$i]; ?></span>
                                    <span>
                                        <?php echo $quantities[$i]; ?> x ₱<?php echo number_format($prices[$i], 2); ?>
                                    </span>
                                </div>
                            <?php endfor; ?>
                        </div>

                        <?php if ($booking['special_instructions']): ?>
                            <div class="mt-3">
                                <strong>Special Instructions:</strong>
                                <p class="mb-0"><?php echo htmlspecialchars($booking['special_instructions']); ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if ($booking['status'] === 'pending'): ?>
                            <div class="action-buttons mt-3">
                                <button class="btn btn-confirm" onclick="updateBookingStatus(<?php echo $booking['booking_id']; ?>, 'confirmed')">
                                    <i class="fas fa-check me-1"></i>Confirm
                                </button>
                                <button class="btn btn-decline" onclick="updateBookingStatus(<?php echo $booking['booking_id']; ?>, 'declined')">
                                    <i class="fas fa-times me-1"></i>Decline
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleProfileMenu() {
            const menu = document.getElementById('profileMenu');
            menu.classList.toggle('show');
        }

        window.onclick = function(event) {
            if (!event.target.matches('.profile-btn')) {
                const menu = document.getElementById('profileMenu');
                if (menu.classList.contains('show')) {
                    menu.classList.remove('show');
                }
            }
        }

        function filterBookings(status) {
            const bookings = document.querySelectorAll('.booking-card');
            bookings.forEach(booking => {
                if (status === 'all' || booking.dataset.status === status) {
                    booking.style.display = 'block';
                } else {
                    booking.style.display = 'none';
                }
            });
        }

        function updateBookingStatus(bookingId, status) {
            if (!confirm('Are you sure you want to ' + status + ' this booking?')) {
                return;
            }

            const formData = new FormData();
            formData.append('booking_id', bookingId);
            formData.append('status', status);

            fetch('update_booking_status.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Booking status updated successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the booking status.');
            });
        }
    </script>
</body>
</html>

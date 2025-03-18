<?php
session_start();
require 'database/connect_db.php';

// Check if admin is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'A') {
    header("Location: login.php");
    exit();
}

// Get booking statistics
$stats_query = "SELECT 
    COUNT(*) as total_bookings,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_bookings,
    SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed_bookings,
    SUM(CASE WHEN status = 'declined' THEN 1 ELSE 0 END) as declined_bookings,
    SUM(total_amount) as total_revenue,
    COUNT(DISTINCT account_id) as total_customers,
    AVG(total_amount) as average_order_value
    FROM bookings";
$stats_result = mysqli_query($conn, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);

// Get today's statistics
$today_stats_query = "SELECT 
    COUNT(*) as today_bookings,
    SUM(total_amount) as today_revenue
    FROM bookings 
    WHERE DATE(created_at) = CURDATE()";
$today_stats_result = mysqli_query($conn, $today_stats_query);
$today_stats = mysqli_fetch_assoc($today_stats_result);

// Get monthly revenue trend
$monthly_revenue_query = "SELECT 
    DATE_FORMAT(created_at, '%Y-%m') as month,
    SUM(total_amount) as revenue
    FROM bookings 
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
    ORDER BY month ASC";
$monthly_revenue_result = mysqli_query($conn, $monthly_revenue_query);
$monthly_revenue = mysqli_fetch_all($monthly_revenue_result, MYSQLI_ASSOC);

// Get top selling products
$top_products_query = "SELECT 
    p.product_name,
    SUM(bd.quantity) as total_sold,
    SUM(bd.subtotal) as total_revenue
    FROM booking_details bd
    JOIN products p ON bd.product_id = p.product_id
    GROUP BY p.product_id
    ORDER BY total_sold DESC
    LIMIT 5";
$top_products_result = mysqli_query($conn, $top_products_query);
$top_products = mysqli_fetch_all($top_products_result, MYSQLI_ASSOC);

// Fetch recent bookings
$recent_bookings_query = "SELECT b.*, a.username 
                         FROM bookings b 
                         JOIN accounts a ON b.account_id = a.account_id 
                         ORDER BY b.created_at DESC LIMIT 5";
$recent_bookings_result = mysqli_query($conn, $recent_bookings_query);
$recent_bookings = mysqli_fetch_all($recent_bookings_result, MYSQLI_ASSOC);

// Fetch low stock products
$low_stock_query = "SELECT product_id, product_name, stock 
                    FROM products 
                    WHERE stock < 500 
                    ORDER BY stock ASC 
                    LIMIT 5";
$low_stock_result = mysqli_query($conn, $low_stock_query);
$low_stock_products = mysqli_fetch_all($low_stock_result, MYSQLI_ASSOC);

// Count low stock items
$low_stock_count_query = "SELECT COUNT(*) as count 
                         FROM products 
                         WHERE stock < 500";
$low_stock_count_result = mysqli_query($conn, $low_stock_count_query);
$low_stock_count = mysqli_fetch_assoc($low_stock_count_result);
$low_stock_items = $low_stock_count['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - EZ Leather Bar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            margin-top: 80px;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .card:hover {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .card-header {
            background: none;
            border-bottom: 1px solid rgba(0,0,0,0.1);
            padding: 15px 20px;
        }

        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .bg-primary-soft {
            background: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
        }

        .bg-success-soft {
            background: rgba(25, 135, 84, 0.1);
            color: #198754;
        }

        .bg-warning-soft {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .bg-danger-soft {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .stats-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            height: 400px;
            position: relative;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            border-top: none;
            font-weight: 600;
            color: #6c757d;
        }

        .table td {
            vertical-align: middle;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-pending {
            background: #fff3cd;
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

        .stock-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .stock-high {
            background: #d4edda;
            color: #155724;
        }

        .stock-medium {
            background: #fff3cd;
            color: #856404;
        }

        .stock-low {
            background: #f8d7da;
            color: #721c24;
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
                <a class="nav-link active" href="admin_dashboard.php">
                    <i class="fas fa-chart-line"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admin_bookings.php">
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
        <div class="row">
            <div class="col-md-3">
                <div class="card bg-primary-soft">
                    <div class="card-body">
                        <div class="stats-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <h5 class="card-title">Total Bookings</h5>
                        <h2 class="mb-0"><?php echo $stats['total_bookings']; ?></h2>
                        <small class="text-muted">Today: <?php echo $today_stats['today_bookings']; ?></small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success-soft">
                    <div class="card-body">
                        <div class="stats-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <h5 class="card-title">Total Revenue</h5>
                        <h2 class="mb-0">₱<?php echo number_format($stats['total_revenue'], 2); ?></h2>
                        <small class="text-muted">Today: ₱<?php echo number_format($today_stats['today_revenue'], 2); ?></small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning-soft">
                    <div class="card-body">
                        <div class="stats-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h5 class="card-title">Total Customers</h5>
                        <h2 class="mb-0"><?php echo $stats['total_customers']; ?></h2>
                        <small class="text-muted">Avg. Order: ₱<?php echo number_format($stats['average_order_value'], 2); ?></small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger-soft">
                    <div class="card-body">
                        <div class="stats-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <h5 class="card-title">Low Stock Items</h5>
                        <h2 class="mb-0"><?php echo $low_stock_items; ?></h2>
                        <small class="text-muted"><a href="admin_inventory.php">View Inventory →</a></small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-pie me-1"></i>
                        Booking Status Distribution
                    </div>
                    <div class="card-body">
                        <canvas id="bookingChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-star me-1"></i>
                        Top Selling Products
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Sold</th>
                                        <th>Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($top_products as $product): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                                            <td><?php echo $product['total_sold']; ?></td>
                                            <td>₱<?php echo number_format($product['total_revenue'], 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-calendar-check me-1"></i>
                        Recent Bookings
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Reference</th>
                                        <th>Customer</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_bookings as $booking): ?>
                                        <tr>
                                            <td><?php echo $booking['booking_reference']; ?></td>
                                            <td><?php echo htmlspecialchars($booking['username']); ?></td>
                                            <td>₱<?php echo number_format($booking['total_amount'], 2); ?></td>
                                            <td>
                                                <span class="status-badge status-<?php echo strtolower($booking['status']); ?>">
                                                    <?php echo ucfirst($booking['status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($booking['created_at'])); ?></td>
                                            <td>
                                                <a href="admin_bookings.php?id=<?php echo $booking['booking_id']; ?>" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Low Stock Alerts
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Stock</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($low_stock_products as $product): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                                            <td>
                                                <?php
                                                $stock_class = 'stock-high';
                                                if ($product['stock'] < 200) {
                                                    $stock_class = 'stock-low';
                                                } elseif ($product['stock'] < 500) {
                                                    $stock_class = 'stock-medium';
                                                }
                                                ?>
                                                <span class="stock-badge <?php echo $stock_class; ?>">
                                                    <?php echo $product['stock']; ?> pieces
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($product['stock'] < 200): ?>
                                                    <span class="text-danger">Critical</span>
                                                <?php elseif ($product['stock'] < 500): ?>
                                                    <span class="text-warning">Low</span>
                                                <?php else: ?>
                                                    <span class="text-success">Good</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
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

        // Booking Status Chart
        const ctx = document.getElementById('bookingChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Confirmed', 'Pending', 'Declined'],
                datasets: [{
                    data: [
                        <?php echo $stats['confirmed_bookings']; ?>,
                        <?php echo $stats['pending_bookings']; ?>,
                        <?php echo $stats['declined_bookings']; ?>
                    ],
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(220, 53, 69, 0.8)'
                    ],
                    borderColor: [
                        'rgba(40, 167, 69, 1)',
                        'rgba(255, 193, 7, 1)',
                        'rgba(220, 53, 69, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            font: {
                                size: 12
                            }
                        }
                    }
                },
                layout: {
                    padding: {
                        top: 20,
                        bottom: 20
                    }
                },
                animation: {
                    duration: 500
                }
            }
        });
    </script>
</body>
</html>

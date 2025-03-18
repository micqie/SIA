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
    SUM(total_amount) as total_revenue
    FROM bookings";
$stats_result = mysqli_query($conn, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);

// Get recent bookings
$recent_bookings_query = "SELECT b.*, a.username 
    FROM bookings b
    LEFT JOIN accounts a ON b.account_id = a.account_id
    ORDER BY b.created_at DESC LIMIT 5";
$recent_bookings_result = mysqli_query($conn, $recent_bookings_query);
$recent_bookings = mysqli_fetch_all($recent_bookings_result, MYSQLI_ASSOC);
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
        }

        .navbar-brand, .nav-link {
            color: white !important;
        }

        .main-container {
            margin-top: 80px;
            padding: 20px;
        }

        .stats-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }

        .stats-card:hover {
            transform: translateY(-5px);
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

        .chart-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            position: relative;
            height: 400px;
        }

        .recent-bookings {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .booking-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        .booking-item:last-child {
            border-bottom: none;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
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
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-store-alt me-2"></i>EZ Leather Bar Admin
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="admin_bookings.php">
                            <i class="fas fa-calendar-check me-1"></i>Bookings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="fas fa-sign-out-alt me-1"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="main-container container">
        <h2 class="mb-4"><i class="fas fa-chart-line me-2"></i>Dashboard Overview</h2>
        
        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-icon bg-primary-soft">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h3><?php echo $stats['total_bookings']; ?></h3>
                    <p class="text-muted mb-0">Total Bookings</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-icon bg-success-soft">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3><?php echo $stats['confirmed_bookings']; ?></h3>
                    <p class="text-muted mb-0">Confirmed Bookings</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-icon bg-warning-soft">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3><?php echo $stats['pending_bookings']; ?></h3>
                    <p class="text-muted mb-0">Pending Bookings</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-icon bg-danger-soft">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <h3><?php echo $stats['declined_bookings']; ?></h3>
                    <p class="text-muted mb-0">Declined Bookings</p>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <!-- Charts -->
            <div class="col-md-8">
                <div class="chart-container">
                    <h4 class="mb-4">Booking Statistics</h4>
                    <canvas id="bookingChart"></canvas>
                </div>
            </div>

            <!-- Recent Bookings -->
            <div class="col-md-4">
                <div class="recent-bookings">
                    <h4 class="mb-4">Recent Bookings</h4>
                    <?php foreach ($recent_bookings as $booking): ?>
                        <div class="booking-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">Booking #<?php echo $booking['booking_id']; ?></h6>
                                    <p class="mb-1">By: <?php echo htmlspecialchars($booking['username']); ?></p>
                                    <small class="text-muted">
                                        <?php echo date('M d, Y', strtotime($booking['created_at'])); ?>
                                    </small>
                                </div>
                                <span class="status-badge status-<?php echo strtolower($booking['status']); ?>">
                                    <?php echo ucfirst($booking['status']); ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="text-center mt-3">
                        <a href="admin_bookings.php" class="btn btn-sm btn-outline-primary">View All Bookings</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize charts
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

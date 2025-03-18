<?php
session_start();
require 'database/connect_db.php';

$booking = null;
$error = null;

// Check if booking reference is provided
if (isset($_GET['ref'])) {
    $booking_reference = mysqli_real_escape_string($conn, trim($_GET['ref']));
    
    // Debug log
    error_log("Searching for booking reference: " . $booking_reference);
    
    // Fetch booking details with product information
    $query = "SELECT b.*, 
              GROUP_CONCAT(DISTINCT p.product_name) as products,
              GROUP_CONCAT(DISTINCT bd.quantity) as quantities,
              GROUP_CONCAT(DISTINCT bd.unit_price) as prices,
              GROUP_CONCAT(DISTINCT bd.subtotal) as subtotals
              FROM bookings b
              LEFT JOIN booking_details bd ON b.booking_id = bd.booking_id
              LEFT JOIN products p ON bd.product_id = p.product_id
              WHERE b.booking_reference = ?
              GROUP BY b.booking_id";
    
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error);
        $error = "System error occurred. Please try again later.";
    } else {
        $stmt->bind_param('s', $booking_reference);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            $booking = $result->fetch_assoc();
            error_log("Booking found: " . print_r($booking, true));
        } else {
            error_log("No booking found for reference: " . $booking_reference);
            $error = "Booking not found. Please check your reference number.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Dashboard - EZ Leather Bar</title>
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

        .search-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
        }

        .booking-details {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .status-badge {
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            display: inline-block;
            margin-bottom: 20px;
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

        .products-list {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
        }

        .product-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #dee2e6;
        }

        .product-item:last-child {
            border-bottom: none;
        }

        .qr-container {
            text-align: center;
            margin-top: 20px;
            padding: 20px;
            background: white;
            border-radius: 8px;
        }

        .btn-brown {
            background-color: #9D4D36;
            color: white;
        }

        .btn-brown:hover {
            background-color: #8B3D26;
            color: white;
        }

        @media print {
            body {
                background: white;
            }
            .no-print {
                display: none;
            }
            .booking-details {
                box-shadow: none;
                margin: 0;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top no-print">
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
                        <a class="nav-link" href="login.php">
                            <i class="fas fa-sign-in-alt me-1"></i>Login
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="main-container container">
        <?php if (!$booking): ?>
            <div class="search-container">
                <h2 class="mb-4"><i class="fas fa-search me-2"></i>Track Your Booking</h2>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <form action="" method="GET" class="col-md-6 mx-auto">
                    <div class="input-group mb-3">
                        <input type="text" name="ref" class="form-control" 
                               placeholder="Enter your booking reference" required 
                               pattern="BK[0-9]+" 
                               title="Please enter a valid booking reference (starts with BK followed by numbers)"
                               value="<?php echo isset($_GET['ref']) ? htmlspecialchars($_GET['ref']) : ''; ?>">
                        <button class="btn btn-brown" type="submit">
                            <i class="fas fa-search me-1"></i>Track
                        </button>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div class="booking-details">
                <div class="text-center mb-4">
                    <img src="assets/logo2.jpg" alt="Logo" style="height: 80px;">
                    <h4 class="mt-3">EZ Leather Bar</h4>
                    <p class="text-muted">Premium Leather Products</p>
                </div>

                <div class="text-center">
                    <span class="status-badge status-<?php echo strtolower($booking['status']); ?>">
                        <?php echo ucfirst($booking['status']); ?>
                    </span>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Booking Reference:</strong><br><?php echo $booking['booking_reference']; ?></p>
                        <p><strong>Booking Date:</strong><br><?php echo date('M d, Y', strtotime($booking['created_at'])); ?></p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p><strong>Preferred Date:</strong><br><?php echo date('M d, Y', strtotime($booking['preferred_date'])); ?></p>
                        <p><strong>Status Updated:</strong><br><?php echo $booking['updated_at'] ? date('M d, Y', strtotime($booking['updated_at'])) : 'Not updated'; ?></p>
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
                            <span><?php echo $quantities[$i]; ?> x ₱<?php echo number_format($prices[$i], 2); ?></span>
                        </div>
                    <?php endfor; ?>
                </div>

                <div class="row mt-4">
                    <div class="col-6">
                        <p><strong>Subtotal:</strong></p>
                        <p><strong>Processing Fee:</strong></p>
                        <p><strong>Total Amount:</strong></p>
                    </div>
                    <div class="col-6 text-end">
                        <p>₱<?php echo number_format($booking['total_amount'], 2); ?></p>
                        <p>₱<?php echo number_format($booking['processing_fee'], 2); ?></p>
                        <p>₱<?php echo number_format($booking['total_amount'] + $booking['processing_fee'], 2); ?></p>
                    </div>
                </div>

                <?php if ($booking['special_instructions']): ?>
                    <div class="mt-4">
                        <strong>Special Instructions:</strong>
                        <p class="mb-0"><?php echo htmlspecialchars($booking['special_instructions']); ?></p>
                    </div>
                <?php endif; ?>

                <div class="qr-container">
                    <div id="qrcode"></div>
                    <p class="mt-2 mb-0"><strong>Verification Code:</strong></p>
                    <h5 class="mb-3"><?php echo $booking['booking_reference']; ?></h5>
                    <p class="text-muted small">Show this code when visiting our store</p>
                </div>

                <div class="text-center mt-4 no-print">
                    <button class="btn btn-brown" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Print Receipt
                    </button>
                    <a href="guest_dashboard.php" class="btn btn-outline-secondary ms-2">
                        <i class="fas fa-search me-2"></i>Track Another Booking
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode.js@1.0.0/qrcode.min.js"></script>
    <?php if ($booking): ?>
    <script>
        // Generate QR code
        new QRCode(document.getElementById("qrcode"), {
            text: "<?php echo $booking['booking_reference']; ?>",
            width: 128,
            height: 128
        });
    </script>
    <?php endif; ?>
</body>
</html> 
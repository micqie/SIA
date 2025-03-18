<?php
session_start();
require 'database/connect_db.php';

$order = null;
$error = null;
$receipt_data = null;

// Store receipt data if available
if (isset($_SESSION['receipt_data'])) {
    $receipt_data = $_SESSION['receipt_data'];
}

// Check if reference number is provided
if (isset($_GET['ref'])) {
    $reference = mysqli_real_escape_string($conn, trim($_GET['ref']));
    
    // Fetch order details with product information
    $query = "SELECT b.*, 
              p.product_name, p.description, p.image_path,
              gb.guest_name, gb.customization_details
              FROM bookings b
              JOIN booking_details bd ON b.booking_id = bd.booking_id
              JOIN products p ON bd.product_id = p.product_id
              LEFT JOIN guest_bookings gb ON b.booking_id = gb.booking_id
              WHERE b.booking_reference = ? OR gb.guest_reference = ?
              LIMIT 1";
    
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        $error = "System error occurred. Please try again later.";
    } else {
        $stmt->bind_param('ss', $reference, $reference);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            $order = $result->fetch_assoc();
            $customization = json_decode($order['customization_details'] ?? '{}', true);
        } else {
            $error = "Order not found. Please check your reference number.";
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
            margin: 20px auto;
            max-width: 500px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
        }

        .order-details {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            margin: 20px auto;
            max-width: 800px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .product-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .color-option {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: inline-block;
            margin: 5px;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .color-option.selected {
            border-color: #9D4D36;
        }

        .btn-brown {
            background-color: #9D4D36;
            color: white;
        }

        .btn-brown:hover {
            background-color: #8B3D26;
            color: white;
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
        <?php if (!$order): ?>
            <div class="search-container">
                <h2 class="mb-4"><i class="fas fa-search me-2"></i>Enter Reference Number</h2>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <form action="" method="GET" class="col-md-8 mx-auto">
                    <div class="input-group mb-3">
                        <input type="text" name="ref" class="form-control form-control-lg" 
                               placeholder="Enter your reference number" required
                               value="<?php echo isset($_GET['ref']) ? htmlspecialchars($_GET['ref']) : ''; ?>">
                        <button class="btn btn-brown btn-lg" type="submit">
                            <i class="fas fa-search me-1"></i>View Order
                        </button>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div class="order-details">
                <div class="text-center mb-4">
                    <img src="<?php echo htmlspecialchars($order['image_path']); ?>" 
                         alt="<?php echo htmlspecialchars($order['product_name']); ?>" 
                         class="product-image">
                    <h4><?php echo htmlspecialchars($order['product_name']); ?></h4>
                    <p class="text-muted"><?php echo htmlspecialchars($order['description']); ?></p>
                </div>

                <div class="text-center">
                    <span class="status-badge status-<?php echo strtolower($order['status']); ?>">
                        <?php echo ucfirst($order['status']); ?>
                    </span>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <p><strong>Reference Number:</strong><br><?php echo htmlspecialchars($order['booking_reference']); ?></p>
                        <p><strong>Order Date:</strong><br><?php echo date('M d, Y', strtotime($order['created_at'])); ?></p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p><strong>Total Amount:</strong><br>₱<?php echo number_format($order['total_amount'], 2); ?></p>
                        <p><strong>Status Updated:</strong><br><?php echo $order['updated_at'] ? date('M d, Y', strtotime($order['updated_at'])) : 'Not updated'; ?></p>
                    </div>
                </div>

                <?php if (isset($_SESSION['customization_error'])): ?>
                    <div class="alert alert-danger">
                        <?php 
                        echo htmlspecialchars($_SESSION['customization_error']);
                        unset($_SESSION['customization_error']);
                        ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="update_customization.php" onsubmit="return validateForm()">
                    <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($order['booking_id']); ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Your Name (Optional)</label>
                        <input type="text" class="form-control" name="guest_name" 
                               value="<?php echo htmlspecialchars($order['guest_name'] ?? ''); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Choose Leather Color</label>
                        <div>
                            <div class="color-option <?php echo ($customization['color'] ?? '') === '#8B4513' ? 'selected' : ''; ?>" 
                                 style="background: #8B4513;" onclick="selectColor(this, '#8B4513')"></div>
                            <div class="color-option <?php echo ($customization['color'] ?? '') === '#000000' ? 'selected' : ''; ?>" 
                                 style="background: #000000;" onclick="selectColor(this, '#000000')"></div>
                            <div class="color-option <?php echo ($customization['color'] ?? '') === '#C0C0C0' ? 'selected' : ''; ?>" 
                                 style="background: #C0C0C0;" onclick="selectColor(this, '#C0C0C0')"></div>
                            <div class="color-option <?php echo ($customization['color'] ?? '') === '#DAA520' ? 'selected' : ''; ?>" 
                                 style="background: #DAA520;" onclick="selectColor(this, '#DAA520')"></div>
                        </div>
                        <input type="hidden" name="selected_color" id="selected_color" 
                               value="<?php echo htmlspecialchars($customization['color'] ?? ''); ?>">
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-brown">
                            <i class="fas fa-save me-2"></i>Save Customization
                        </button>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <!-- Customization Success Modal -->
    <div class="modal fade" id="customizationSuccessModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="customizationSuccessModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="customizationSuccessModalLabel">
                        <i class="fas fa-check-circle me-2"></i>Customization Saved Successfully
                    </h5>
                </div>
                <div class="modal-body text-center">
                    <div class="mb-4">
                        <h4><?php echo isset($_SESSION['customization_details']['guest_name']) ? htmlspecialchars($_SESSION['customization_details']['guest_name']) : 'Guest'; ?></h4>
                        <p class="text-muted">Reference Number: <?php echo isset($_SESSION['customization_details']['booking_reference']) ? htmlspecialchars($_SESSION['customization_details']['booking_reference']) : ''; ?></p>
                    </div>
                    
                    <div class="mb-4">
                        <h5>Product Details</h5>
                        <p><?php echo isset($_SESSION['customization_details']['product_name']) ? htmlspecialchars($_SESSION['customization_details']['product_name']) : ''; ?></p>
                        <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
                            <span>Selected Color:</span>
                            <span class="color-display" style="background-color: <?php echo isset($_SESSION['customization_details']['selected_color']) ? htmlspecialchars($_SESSION['customization_details']['selected_color']) : ''; ?>"></span>
                            <span><?php echo isset($_SESSION['customization_details']['selected_color']) ? htmlspecialchars($_SESSION['customization_details']['selected_color']) : ''; ?></span>
                        </div>
                        <p class="mb-0"><strong>Customization Code:</strong></p>
                        <p class="h4"><?php echo isset($_SESSION['customization_details']['customization_code']) ? htmlspecialchars($_SESSION['customization_details']['customization_code']) : ''; ?></p>
                    </div>

                    <?php if (isset($_SESSION['customization_details']['customization_code'])): ?>
                    <div class="qr-code-container mb-4">
                        <?php
                        $qr_code_url = "generate_qr.php?code=" . urlencode($_SESSION['customization_details']['customization_code']);
                        ?>
                        <img src="<?php echo htmlspecialchars($qr_code_url); ?>" 
                             alt="Customization QR Code" 
                             class="img-fluid" 
                             style="max-width: 200px; border: 1px solid #ddd; padding: 10px; background: white;"
                             onerror="this.onerror=null; this.src='assets/images/qr-placeholder.png'; console.log('QR Code failed to load');">
                        <p class="text-muted mt-2">Scan this QR code to view customization details</p>
                        <p class="text-muted small">Code: <?php echo htmlspecialchars($_SESSION['customization_details']['customization_code']); ?></p>
                        <p class="text-muted small">
                            <a href="<?php echo htmlspecialchars($qr_code_url); ?>" target="_blank" class="text-decoration-none">
                                <i class="fas fa-external-link-alt me-1"></i>View QR Code
                            </a>
                        </p>
                    </div>
                    <?php endif; ?>

                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-success" onclick="window.print()">
                            <i class="fas fa-print me-2"></i>Print Details
                        </button>
                        <button type="button" class="btn btn-primary" onclick="location.reload()">
                            <i class="fas fa-plus me-2"></i>New Order
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Receipt Modal -->
    <?php if (isset($_SESSION['show_receipt']) && isset($receipt_data)): ?>
    <div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-brown text-white">
                    <h5 class="modal-title" id="receiptModalLabel">
                        <i class="fas fa-receipt me-2"></i>Customization Receipt
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="receipt-container p-4">
                        <div class="receipt-header text-center mb-4">
                            <h4 class="text-brown">EZ Leather Bar</h4>
                            <p class="text-muted mb-0">Customization Receipt</p>
                            <small class="text-muted">Date: <?php echo date('F d, Y h:i A', strtotime($receipt_data['timestamp'])); ?></small>
                        </div>

                        <div class="qr-section text-center mb-4 p-4 bg-light rounded">
                            <img src="generate_qr.php?code=<?php echo urlencode($receipt_data['customization_code']); ?>" 
                                 alt="Customization QR Code" class="img-fluid mb-3" style="max-width: 200px;">
                            <div class="customization-code mb-2">
                                <strong class="fs-5"><?php echo $receipt_data['customization_code']; ?></strong>
                            </div>
                            <p class="text-muted mb-0">
                                <i class="fas fa-info-circle me-1"></i>
                                Show this QR code to our staff when picking up your order
                            </p>
                        </div>

                        <div class="receipt-details">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <p class="mb-2"><strong>Guest Name:</strong><br>
                                    <?php echo htmlspecialchars($receipt_data['guest_name'] ?: 'Not provided'); ?></p>
                                    <p class="mb-2"><strong>Reference Number:</strong><br>
                                    <?php echo htmlspecialchars($order['booking_reference']); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-2"><strong>Product:</strong><br>
                                    <?php echo htmlspecialchars($order['product_name']); ?></p>
                                    <p class="mb-2"><strong>Selected Color:</strong><br>
                                    <span style="display: inline-block; width: 20px; height: 20px; background-color: <?php echo htmlspecialchars($receipt_data['selected_color']); ?>; border-radius: 50%; margin-right: 5px; vertical-align: middle;"></span>
                                    <?php echo htmlspecialchars($receipt_data['selected_color']); ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="receipt-footer text-center mt-4 pt-3 border-top">
                            <p class="mb-1">Thank you for choosing EZ Leather Bar!</p>
                            <p class="text-muted mb-0">Please keep this receipt and show the QR code to our staff when picking up your order.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-brown" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Print Receipt
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function selectColor(element, color) {
            document.querySelectorAll('.color-option').forEach(opt => {
                opt.classList.remove('selected');
            });
            element.classList.add('selected');
            document.getElementById('selected_color').value = color;
        }

        function validateForm() {
            const selectedColor = document.getElementById('selected_color').value;
            if (!selectedColor) {
                alert('Please select a color for your customization.');
                return false;
            }
            return true;
        }

        // Show success modal if customization was successful
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (isset($_SESSION['customization_success']) && $_SESSION['customization_success']): ?>
            const successModal = new bootstrap.Modal(document.getElementById('customizationSuccessModal'));
            successModal.show();
            <?php 
            // Clear the session variables after showing the modal
            unset($_SESSION['customization_success']);
            unset($_SESSION['customization_details']);
            ?>
            <?php endif; ?>
        });
    </script>
</body>
</html> 
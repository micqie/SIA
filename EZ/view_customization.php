<?php
session_start();
require 'database/connect_db.php';

$customization = null;
$error = null;

if (isset($_GET['code'])) {
    $code = trim($_GET['code']);
    
    // Get customization details
    $stmt = $conn->prepare("SELECT gb.*, b.booking_reference, b.status, 
                                  p.product_name, p.description, p.image_path
                           FROM guest_bookings gb
                           JOIN bookings b ON gb.booking_id = b.booking_id
                           JOIN booking_details bd ON b.booking_id = bd.booking_id
                           JOIN products p ON bd.product_id = p.product_id
                           WHERE JSON_EXTRACT(gb.customization_details, '$.customization_code') = ?");
    
    if ($stmt) {
        $stmt->bind_param('s', $code);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $customization = $result->fetch_assoc();
            $customization['details'] = json_decode($customization['customization_details'], true);
        } else {
            $error = "Customization not found";
        }
        $stmt->close();
    } else {
        $error = "System error occurred";
    }
} else {
    $error = "No customization code provided";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customization Details - EZ Leather Bar</title>
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

        .details-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            margin: 20px auto;
            max-width: 800px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .product-image {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .color-display {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 10px;
            vertical-align: middle;
            border: 2px solid #ddd;
        }

        .status-badge {
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            display: inline-block;
        }

        .status-pending {
            background: #ffeeba;
            color: #856404;
        }

        .status-confirmed {
            background: #d4edda;
            color: #155724;
        }

        .status-completed {
            background: #cce5ff;
            color: #004085;
        }

        .qr-code {
            max-width: 200px;
            margin: 20px auto;
        }

        @media print {
            body {
                background: none;
            }
            .no-print {
                display: none;
            }
            .details-card {
                box-shadow: none;
                margin: 0;
                padding: 15px;
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
        </div>
    </nav>

    <div class="main-container container">
        <?php if ($error): ?>
            <div class="details-card text-center">
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($error); ?>
                </div>
                <a href="employee_dashboard.php" class="btn btn-primary no-print">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>
        <?php elseif ($customization): ?>
            <div class="details-card">
                <div class="text-center mb-4">
                    <img src="<?php echo htmlspecialchars($customization['image_path']); ?>" 
                         alt="<?php echo htmlspecialchars($customization['product_name']); ?>" 
                         class="product-image">
                    <h3><?php echo htmlspecialchars($customization['product_name']); ?></h3>
                    <p class="text-muted"><?php echo htmlspecialchars($customization['description']); ?></p>
                    
                    <span class="status-badge status-<?php echo strtolower($customization['status']); ?>">
                        <?php echo ucfirst($customization['status']); ?>
                    </span>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>Customer Details</h5>
                        <p><strong>Guest Name:</strong><br>
                        <?php echo htmlspecialchars($customization['guest_name'] ?: 'Not provided'); ?></p>
                        <p><strong>Reference Number:</strong><br>
                        <?php echo htmlspecialchars($customization['booking_reference']); ?></p>
                        <p><strong>Customization Code:</strong><br>
                        <?php echo htmlspecialchars($customization['details']['customization_code']); ?></p>
                    </div>
                    <div class="col-md-6">
                        <h5>Customization Details</h5>
                        <p>
                            <strong>Selected Color:</strong><br>
                            <span class="color-display" style="background-color: <?php echo htmlspecialchars($customization['details']['color']); ?>"></span>
                            <?php echo htmlspecialchars($customization['details']['color']); ?>
                        </p>
                        <p><strong>Date Customized:</strong><br>
                        <?php echo date('F d, Y h:i A', strtotime($customization['details']['timestamp'])); ?></p>
                    </div>
                </div>

                <div class="text-center qr-code">
                    <img src="generate_qr.php?code=<?php echo urlencode($customization['details']['customization_code']); ?>" 
                         alt="QR Code" class="img-fluid">
                </div>

                <div class="text-center mt-4 no-print">
                    <button type="button" class="btn btn-success me-2" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Print Details
                    </button>
                    <a href="employee_dashboard.php" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 
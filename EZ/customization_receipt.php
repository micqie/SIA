<?php
session_start();
require 'database/connect_db.php';

// Check if we have a customization code
if (!isset($_GET['code'])) {
    header('Location: guest_dashboard.php');
    exit();
}

$customization_code = filter_input(INPUT_GET, 'code', FILTER_SANITIZE_STRING);

// Fetch customization details
$query = "SELECT gb.*, b.booking_reference, b.created_at, p.product_name, p.image_path 
          FROM guest_bookings gb 
          JOIN bookings b ON gb.booking_id = b.booking_id 
          JOIN booking_details bd ON b.booking_id = bd.booking_id 
          JOIN products p ON bd.product_id = p.product_id 
          WHERE JSON_EXTRACT(gb.customization_details, '$.customization_code') = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param('s', $customization_code);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: guest_dashboard.php');
    exit();
}

$booking = $result->fetch_assoc();
$customization_details = json_decode($booking['customization_details'], true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customization Receipt - EZ Leather Bar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #F8E2A8, #9D4D36);
            min-height: 100vh;
            font-family: 'Arial', sans-serif;
            padding: 20px;
        }

        .receipt-container {
            background: white;
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px dashed #9D4D36;
        }

        .receipt-header h1 {
            color: #9D4D36;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .receipt-header p {
            color: #666;
            margin-bottom: 0;
        }

        .qr-section {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .qr-code {
            max-width: 200px;
            margin: 0 auto 15px;
        }

        .customization-code {
            font-size: 18px;
            font-weight: bold;
            color: #9D4D36;
            margin: 10px 0;
        }

        .receipt-details {
            margin: 30px 0;
        }

        .receipt-details .row {
            margin-bottom: 15px;
        }

        .receipt-details .label {
            font-weight: bold;
            color: #666;
        }

        .receipt-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px dashed #9D4D36;
            color: #666;
        }

        .btn-print {
            background-color: #9D4D36;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        .btn-print:hover {
            background-color: #8B3D26;
            color: white;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .receipt-container {
                box-shadow: none;
                margin: 0;
                padding: 20px;
            }

            .btn-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="receipt-header">
            <h1>EZ Leather Bar</h1>
            <p>Customization Receipt</p>
        </div>

        <div class="qr-section">
            <img src="generate_qr.php?code=<?php echo urlencode($customization_code); ?>" 
                 alt="Customization QR Code" class="qr-code">
            <div class="customization-code">
                <?php echo $customization_code; ?>
            </div>
            <p class="text-muted">Show this QR code to our staff when picking up your order</p>
        </div>

        <div class="receipt-details">
            <div class="row">
                <div class="col-md-6">
                    <p><span class="label">Guest Name:</span><br><?php echo htmlspecialchars($booking['guest_name']); ?></p>
                    <p><span class="label">Reference Number:</span><br><?php echo htmlspecialchars($booking['booking_reference']); ?></p>
                </div>
                <div class="col-md-6">
                    <p><span class="label">Product:</span><br><?php echo htmlspecialchars($booking['product_name']); ?></p>
                    <p><span class="label">Customization Date:</span><br><?php echo date('M d, Y', strtotime($booking['created_at'])); ?></p>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <p><span class="label">Selected Color:</span><br>
                        <span style="display: inline-block; width: 20px; height: 20px; background-color: <?php echo htmlspecialchars($customization_details['color']); ?>; border-radius: 50%; margin-right: 5px;"></span>
                        <?php echo htmlspecialchars($customization_details['color']); ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="receipt-footer">
            <p>Thank you for choosing EZ Leather Bar!</p>
            <p>Please keep this receipt and show the QR code to our staff when picking up your order.</p>
        </div>

        <div class="text-center">
            <button onclick="window.print()" class="btn-print">
                <i class="fas fa-print me-2"></i>Print Receipt
            </button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 
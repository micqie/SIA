<?php
require 'database/connect_db.php';

// Function to get customization details
function getCustomizationDetails($code) {
    global $conn;
    $stmt = $conn->prepare("
        SELECT gb.*, a.username as guest_name, b.booking_reference, p.product_name 
        FROM guest_bookings gb 
        JOIN accounts a ON gb.account_id = a.account_id 
        JOIN bookings b ON gb.booking_id = b.booking_id 
        JOIN booking_details bd ON b.booking_id = bd.booking_id 
        JOIN products p ON bd.product_id = p.product_id 
        WHERE JSON_EXTRACT(gb.customization_details, '$.customization_code') = ?
    ");
    $stmt->bind_param('s', $code);
    $stmt->execute();
    $result = $stmt->get_result();
    $details = $result->fetch_assoc();
    if ($details) {
        // Extract customization details from JSON
        $customization_details = json_decode($details['customization_details'], true);
        $details['color'] = $customization_details['color'] ?? '';
        $details['customization_code'] = $customization_details['customization_code'] ?? '';
        $details['customization_date'] = $customization_details['date'] ?? date('Y-m-d');
    }
    $stmt->close();
    return $details;
}

// Handle QR code scan result
$customization = null;
$error = '';
if (isset($_GET['code'])) {
    $code = trim($_GET['code']);
    $customization = getCustomizationDetails($code);
    if (!$customization) {
        $error = 'Invalid customization code or customization not found.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard - EZ Leather Bar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://unpkg.com/html5-qrcode"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #9D4D36;
        }
        .navbar-brand {
            color: white !important;
        }
        .btn-brown {
            background-color: #9D4D36;
            color: white;
        }
        .btn-brown:hover {
            background-color: #8B3D26;
            color: white;
        }
        #reader {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
        }
        .customization-details {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .status-verified {
            color: #28a745;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-qrcode me-2"></i>Employee Dashboard
            </a>
            <a href="index.php" class="btn btn-light btn-sm">
                <i class="fas fa-home me-2"></i>Back to Home
            </a>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title text-center mb-4">
                            <i class="fas fa-camera me-2"></i>Scan QR Code
                        </h5>
                        <div id="reader"></div>
                        <div class="text-center mt-3">
                            <p class="text-muted">or enter code manually</p>
                            <form class="d-flex justify-content-center gap-2" method="GET">
                                <input type="text" name="code" class="form-control" style="max-width: 200px;" placeholder="Enter code">
                                <button type="submit" class="btn btn-brown">
                                    <i class="fas fa-search me-2"></i>Verify
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <?php if ($customization): ?>
                    <div class="customization-details mb-4">
                        <div class="text-center mb-4">
                            <i class="fas fa-check-circle status-verified" style="font-size: 48px;"></i>
                            <h4 class="mt-2">Customization Verified</h4>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Guest Name:</strong> <?php echo htmlspecialchars($customization['guest_name']); ?></p>
                                <p><strong>Reference:</strong> <?php echo htmlspecialchars($customization['booking_reference']); ?></p>
                                <p><strong>Product:</strong> <?php echo htmlspecialchars($customization['product_name']); ?></p>
                                <p><strong>Color:</strong> <?php echo htmlspecialchars($customization['color']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Code:</strong> <?php echo htmlspecialchars($customization['customization_code']); ?></p>
                                <p><strong>Date:</strong> <?php echo date('F j, Y', strtotime($customization['customization_date'])); ?></p>
                                <p><strong>Status:</strong> <span class="badge bg-success">Valid</span></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function onScanSuccess(decodedText, decodedResult) {
            // Extract code from URL if it's a URL
            let code = decodedText;
            try {
                const url = new URL(decodedText);
                const params = new URLSearchParams(url.search);
                if (params.has('code')) {
                    code = params.get('code');
                }
            } catch (e) {
                // If not a URL, use the decoded text as is
            }
            
            // Redirect to verify the code
            window.location.href = `?code=${encodeURIComponent(code)}`;
        }

        function onScanFailure(error) {
            // console.warn(`QR error: ${error}`);
        }

        // Initialize QR scanner
        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader",
            { fps: 10, qrbox: {width: 250, height: 250} },
            false
        );
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 
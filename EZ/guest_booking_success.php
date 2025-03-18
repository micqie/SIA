<?php
session_start();

// Check if there's a success message
if (!isset($_SESSION['booking_success'])) {
    header('Location: guest_dashboard.php');
    exit();
}

$booking_data = $_SESSION['booking_success'];
unset($_SESSION['booking_success']); // Clear the success message
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Success - EZ Leather Bar</title>
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

        .success-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            margin: 20px auto;
            max-width: 600px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
        }

        .success-icon {
            font-size: 64px;
            color: #28a745;
            margin-bottom: 20px;
        }

        .reference-box {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }

        .reference-code {
            font-size: 24px;
            font-weight: bold;
            color: #9D4D36;
            letter-spacing: 2px;
        }

        .btn-brown {
            background-color: #9D4D36;
            color: white;
        }

        .btn-brown:hover {
            background-color: #8B3D26;
            color: white;
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
        <div class="success-card">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2>Booking Successful!</h2>
            <p class="lead">Thank you, <?php echo htmlspecialchars($booking_data['guest_name']); ?>!</p>
            <p>Your booking has been received and is being processed.</p>
            
            <div class="reference-box">
                <p class="mb-1">Your Booking Reference:</p>
                <div class="reference-code"><?php echo htmlspecialchars($booking_data['guest_reference']); ?></div>
            </div>

            <div class="mt-4">
                <p><strong>Number of Bundles:</strong> <?php echo $booking_data['quantity']; ?></p>
                <p><strong>Total Amount:</strong> ₱<?php echo number_format($booking_data['total_amount'], 2); ?></p>
            </div>
            
            <p class="text-muted">Please keep this reference number for future inquiries.</p>
            
            <div class="mt-4">
                <a href="guest_dashboard.php" class="btn btn-brown">
                    <i class="fas fa-home me-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookingModalLabel">Booking Successful</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Thank you, <?php echo htmlspecialchars($booking_data['guest_name']); ?>!</p>
                    <p>Your booking reference number is <strong><?php echo htmlspecialchars($booking_data['guest_reference']); ?></strong>.</p>
                    <p>Please keep this reference number for future inquiries.</p>
                </div>
                <div class="modal-footer">
                    <a href="guest_dashboard.php" class="btn btn-brown">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var bookingModal = new bootstrap.Modal(document.getElementById('bookingModal'));
            bookingModal.show();
        });
    </script>
</body>
</html>
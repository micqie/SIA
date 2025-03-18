<?php
session_start();
include 'database/connect_db.php';

// Check if user is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'U') {
    header("Location: login.php");
    exit();
}

// Fetch all leather products
$query = "SELECT * FROM products WHERE category_id = 1 AND is_active = 1 ORDER BY product_name";
$result = mysqli_query($conn, $query);
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Fetch user's booking history
$account_id = $_SESSION['account_id'];
$bookings_query = "SELECT b.*, GROUP_CONCAT(p.product_name) as products
                  FROM bookings b 
                  LEFT JOIN booking_details bd ON b.booking_id = bd.booking_id
                  LEFT JOIN products p ON bd.product_id = p.product_id
                  WHERE b.account_id = ? 
                  GROUP BY b.booking_id
                  ORDER BY b.created_at DESC LIMIT 5";
$stmt = $conn->prepare($bookings_query);
$stmt->bind_param("i", $account_id);
$stmt->execute();
$bookings = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - EZ Leather Bar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #F8E2A8, #9D4D36);
            min-height: 100vh;
            font-family: 'Arial', sans-serif;
            padding-bottom: 60px;
            margin: 0;
            overflow-x: hidden;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .main-container {
            margin-top: 80px;
            padding: 20px;
            width: 100%;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        .welcome-section {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .welcome-section h2 {
            color: #9D4D36;
            margin-bottom: 15px;
        }

        .gallery-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .product-card {
            background: white;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            position: relative;
            border: 2px solid transparent;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            border-color: #F8E2A8;
        }

        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .product-card h5 {
            color: #9D4D36;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .product-card .price {
            color: #e67e22;
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .product-card .stock {
            color: #27ae60;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        .product-card .selection-overlay {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(255, 255, 255, 0.9);
            padding: 5px;
            border-radius: 5px;
            z-index: 2;
        }

        .quantity-controls {
            display: none;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }

        .quantity-controls.show {
            display: block;
        }

        .quantity-input {
            width: 60px;
            text-align: center;
            margin: 0 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 4px;
        }

        .quantity-btn {
            background: #9D4D36;
            color: white;
            border: none;
            border-radius: 4px;
            width: 28px;
            height: 28px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .quantity-btn:hover {
            background: #8B3D26;
        }

        .quantity-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .form-check-input:checked {
            background-color: #9D4D36;
            border-color: #9D4D36;
        }

        .action-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.95);
            padding: 15px;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            display: none;
            z-index: 999;
        }

        .action-bar.show {
            display: block;
        }

        #selectedCount {
            font-weight: bold;
            color: #9D4D36;
        }

        .booking-history {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .booking-item {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            border-left: 4px solid #9D4D36;
        }

        .booking-item:last-child {
            margin-bottom: 0;
        }

        .modal-content {
            border-radius: 15px;
            max-width: 800px;
            margin: 0 auto;
        }

        .modal-header {
            background: #9D4D36;
            color: white;
            border-radius: 15px 15px 0 0;
        }

        .modal-body {
            padding: 20px;
        }

        .btn-brown {
            background-color: #9D4D36;
            color: white;
        }

        .btn-brown:hover {
            background-color: #8B3D26;
            color: white;
        }

        /* Fix for responsive layout */
        @media (max-width: 768px) {
            .main-container {
                padding: 10px;
                margin-top: 60px;
            }
            
            .product-card {
                margin-bottom: 15px;
            }
            
            .action-bar {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-store-alt me-2"></i>EZ Leather Bar
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
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
        <!-- Welcome Section -->
        <div class="welcome-section">
            <h2><i class="fas fa-user-circle me-2"></i>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
            <p>Browse our premium leather products and create your bundle by selecting multiple items.</p>
        </div>

        <!-- Products Gallery -->
        <div class="gallery-container">
            <h4 class="mb-4"><i class="fas fa-shopping-bag me-2"></i>Available Leather Products</h4>
            <div class="row">
                <?php foreach ($products as $product): ?>
                <div class="col-md-4 col-lg-3">
                    <div class="product-card">
                        <img src="<?php echo $product['image_path']; ?>" 
                             alt="<?php echo htmlspecialchars($product['product_name']); ?>" 
                             onerror="this.src='assets/bag_tags.png'"
                             class="product-image">
                        <h5><?php echo htmlspecialchars($product['product_name']); ?></h5>
                        <div class="price">₱<?php echo number_format($product['base_price'], 2); ?></div>
                        <div class="stock">
                            <i class="fas fa-box me-1"></i>
                            <?php echo $product['pieces_per_bundle']; ?> pieces per bundle
                        </div>
                        <div class="quantity-controls">
                            <div class="d-flex align-items-center justify-content-center">
                                <button type="button" class="quantity-btn decrease-quantity" disabled>
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" class="quantity-input" value="0" min="0" max="5" 
                                       data-product-id="<?php echo $product['product_id']; ?>"
                                       data-price="<?php echo $product['base_price']; ?>"
                                       data-name="<?php echo $product['product_name']; ?>"
                                       data-pieces="<?php echo $product['pieces_per_bundle']; ?>">
                                <button type="button" class="quantity-btn increase-quantity">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <small class="text-muted d-block text-center mt-2">Max 5 bundles</small>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="booking-history">
            <h4 class="mb-4"><i class="fas fa-history me-2"></i>Recent Bookings</h4>
            <?php if (empty($bookings)): ?>
                <p class="text-muted">No recent bookings found.</p>
            <?php else: ?>
                <?php foreach ($bookings as $booking): ?>
                    <div class="booking-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6>Booking #<?php echo $booking['booking_id']; ?></h6>
                                <p class="mb-0">Date: <?php echo date('M d, Y', strtotime($booking['preferred_date'])); ?></p>
                                <p class="mb-0">Total: ₱<?php echo number_format($booking['total_amount'], 2); ?></p>
                            </div>
                            <span class="badge bg-<?php echo $booking['status'] == 'completed' ? 'success' : 'warning'; ?>">
                                <?php echo ucfirst($booking['status']); ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Action Bar -->
        <div class="action-bar">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <span class="me-3">Selected Items: <span id="selectedCount">0</span></span>
                    </div>
                    <div class="col-md-4">
                        <span class="me-3">Total: ₱<span id="totalPrice">0.00</span></span>
                    </div>
                    <div class="col-md-4 text-end">
                        <button class="btn btn-brown" id="bookSelectedBtn">
                            <i class="fas fa-calendar-check me-2"></i>Book Selected Items
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Booking Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Your Booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="bookingForm">
                        <div class="selected-items-list mb-4"></div>
                        <div class="mb-3">
                            <label class="form-label">Preferred Date</label>
                            <input type="date" class="form-control" id="preferredDate" required
                                   min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Special Instructions</label>
                            <textarea class="form-control" id="specialInstructions" rows="3"></textarea>
                        </div>
                        
                        <!-- Payment Information Section -->
                        <div class="payment-section mb-4">
                            <h5 class="mb-3">Payment Information</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Payment Method</label>
                                        <select class="form-select" id="paymentMethod" required>
                                            <option value="">Select payment method</option>
                                            <option value="gcash">GCash</option>
                                            <option value="cash">Cash</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Reference Number</label>
                                        <input type="text" class="form-control" id="referenceNumber" placeholder="Enter reference number">
                                        <small class="text-muted">Required for GCash payments</small>
                                    </div>
                                </div>
                            </div>
                            <div class="payment-summary bg-light p-3 rounded">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span>₱<span id="paymentSubtotal">0.00</span></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Processing Fee (5%):</span>
                                    <span>₱<span id="paymentFee">0.00</span></span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <strong>Total Amount:</strong>
                                    <strong>₱<span id="paymentTotal">0.00</span></strong>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5>Total Amount: ₱<span id="modalTotalPrice">0.00</span></h5>
                                <small class="text-muted">Processing fee included</small>
                            </div>
                            <button type="submit" class="btn btn-brown">
                                <i class="fas fa-check me-2"></i>Confirm Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Success Modal -->
    <div class="modal fade" id="paymentSuccessModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Payment Successful</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    <h4 class="mt-3">Payment Confirmed!</h4>
                    <p>Your booking has been successfully processed.</p>
                    <div class="mt-4">
                        <button class="btn btn-success" onclick="window.location.reload()">
                            <i class="fas fa-home me-2"></i>Return to Dashboard
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Receipt Modal -->
    <div class="modal fade" id="receiptModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Booking Receipt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <img src="assets/logo2.jpg" alt="Logo" style="height: 80px;">
                        <h4 class="mt-3">EZ Leather Bar</h4>
                        <p class="text-muted">Premium Leather Products</p>
                    </div>
                    <div class="receipt-details"></div>
                    <div class="text-center mt-4">
                        <div id="qrcode"></div>
                        <p class="mt-2 mb-0"><strong>Verification Code:</strong></p>
                        <h5 class="verification-code mb-3"></h5>
                        <p class="text-muted small">Show this code when visiting our store</p>
                    </div>
                    <div class="text-center mt-4">
                        <button class="btn btn-brown" onclick="window.print()">
                            <i class="fas fa-print me-2"></i>Print Receipt
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode.js@1.0.0/qrcode.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const actionBar = document.querySelector('.action-bar');
            const selectedCountSpan = document.getElementById('selectedCount');
            const totalPriceSpan = document.getElementById('totalPrice');
            const modalTotalPriceSpan = document.getElementById('modalTotalPrice');
            const bookSelectedBtn = document.getElementById('bookSelectedBtn');
            const bookingModal = new bootstrap.Modal(document.getElementById('bookingModal'));
            const selectedItemsList = document.querySelector('.selected-items-list');
            const bookingForm = document.getElementById('bookingForm');
            const receiptModal = new bootstrap.Modal(document.getElementById('receiptModal'));

            let selectedProducts = new Map(); // Using Map to store product quantities

            // Initialize quantity controls
            document.querySelectorAll('.product-card').forEach(card => {
                const quantityInput = card.querySelector('.quantity-input');
                const decreaseBtn = card.querySelector('.decrease-quantity');
                const increaseBtn = card.querySelector('.increase-quantity');
                const quantityControls = card.querySelector('.quantity-controls');

                // Show quantity controls by default
                quantityControls.classList.add('show');

                // Decrease quantity
                decreaseBtn.addEventListener('click', function() {
                    const currentValue = parseInt(quantityInput.value);
                    if (currentValue > 0) {
                        quantityInput.value = currentValue - 1;
                        decreaseBtn.disabled = currentValue - 1 === 0;
                        increaseBtn.disabled = false;
                        updateProductSelection(quantityInput);
                    }
                });

                // Increase quantity
                increaseBtn.addEventListener('click', function() {
                    const currentValue = parseInt(quantityInput.value);
                    if (currentValue < 5) {
                        quantityInput.value = currentValue + 1;
                        decreaseBtn.disabled = false;
                        increaseBtn.disabled = currentValue + 1 === 5;
                        updateProductSelection(quantityInput);
                    }
                });

                // Manual input
                quantityInput.addEventListener('change', function() {
                    let value = parseInt(this.value);
                    if (isNaN(value) || value < 0) value = 0;
                    if (value > 5) value = 5;
                    this.value = value;
                    decreaseBtn.disabled = value === 0;
                    increaseBtn.disabled = value === 5;
                    updateProductSelection(this);
                });
            });

            function updateProductSelection(input) {
                const quantity = parseInt(input.value);
                const productId = input.dataset.productId;
                const price = parseFloat(input.dataset.price);
                const name = input.dataset.name;
                const pieces = parseInt(input.dataset.pieces);

                if (quantity > 0) {
                    selectedProducts.set(productId, {
                        id: productId,
                        price: price,
                        name: name,
                        pieces: pieces,
                        quantity: quantity
                    });
                } else {
                    selectedProducts.delete(productId);
                }

                updateUI();
            }

            function updateUI() {
                const totalBundles = Array.from(selectedProducts.values())
                    .reduce((sum, product) => sum + product.quantity, 0);
                selectedCountSpan.textContent = totalBundles;

                const totalPrice = Array.from(selectedProducts.values())
                    .reduce((sum, product) => sum + (product.price * product.quantity), 0);
                const totalWithFee = totalPrice + (totalPrice * 0.05);

                totalPriceSpan.textContent = totalPrice.toFixed(2);
                modalTotalPriceSpan.textContent = totalWithFee.toFixed(2);
                actionBar.classList.toggle('show', selectedProducts.size > 0);
                
                // Update payment summary
                updatePaymentSummary();
            }

            // Update payment summary when products are selected
            function updatePaymentSummary() {
                const totalPrice = Array.from(selectedProducts.values())
                    .reduce((sum, product) => sum + (product.price * product.quantity), 0);
                const processingFee = totalPrice * 0.05;
                const totalWithFee = totalPrice + processingFee;

                document.getElementById('paymentSubtotal').textContent = totalPrice.toFixed(2);
                document.getElementById('paymentFee').textContent = processingFee.toFixed(2);
                document.getElementById('paymentTotal').textContent = totalWithFee.toFixed(2);
            }

            bookSelectedBtn.addEventListener('click', function() {
                selectedItemsList.innerHTML = '';
                
                selectedProducts.forEach(product => {
                    const itemDiv = document.createElement('div');
                    itemDiv.className = 'mb-3 p-3 bg-light rounded';
                    const totalPrice = product.price * product.quantity;
                    const totalPieces = product.pieces * product.quantity;
                    
                    itemDiv.innerHTML = `
                        <h6>${product.name}</h6>
                        <p class="mb-1">Quantity: ${product.quantity} bundle(s)</p>
                        <p class="mb-1">Price per bundle: ₱${product.price.toFixed(2)}</p>
                        <p class="mb-1">Subtotal: ₱${totalPrice.toFixed(2)}</p>
                        <p class="mb-0">Total pieces: ${totalPieces} pieces</p>
                    `;
                    selectedItemsList.appendChild(itemDiv);
                });

                bookingModal.show();
            });

            bookingForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const submitButton = this.querySelector('button[type="submit"]');
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';

                try {
                    // Validate payment information
                    const paymentMethod = document.getElementById('paymentMethod').value;
                    const referenceNumber = document.getElementById('referenceNumber').value;

                    if (!paymentMethod) {
                        throw new Error('Please select a payment method');
                    }

                    if (paymentMethod === 'gcash' && !referenceNumber) {
                        throw new Error('Please provide a reference number for GCash payment');
                    }

                    // Check session status
                    const sessionResponse = await fetch('check_session.php', {
                        method: 'GET',
                        credentials: 'same-origin'
                    });
                    const sessionData = await sessionResponse.json();
                    
                    if (!sessionData.isLoggedIn || sessionData.role !== 'U') {
                        throw new Error('Your session has expired. Please log in again.');
                    }

                    // Validate form data
                    const preferredDate = document.getElementById('preferredDate').value;
                    const specialInstructions = document.getElementById('specialInstructions').value;

                    if (!preferredDate) {
                        throw new Error('Please select a preferred date');
                    }

                    if (selectedProducts.size === 0) {
                        throw new Error('Please select at least one product');
                    }

                    // Format products data
                    const products = Array.from(selectedProducts.values()).map(product => ({
                        id: parseInt(product.id),
                        quantity: parseInt(product.quantity),
                        price: parseFloat(product.price),
                        name: product.name
                    }));

                    const formData = {
                        products: products,
                        preferredDate: preferredDate,
                        specialInstructions: specialInstructions,
                        totalAmount: parseFloat(document.getElementById('paymentTotal').textContent.replace(/,/g, '')),
                        paymentMethod: paymentMethod,
                        referenceNumber: referenceNumber
                    };

                    // Send booking request
                    const response = await fetch('process_booking.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(formData),
                        credentials: 'same-origin'
                    });

                    const data = await response.json();

                    if (!data.success) {
                        throw new Error(data.message || 'Booking failed');
                    }

                    // Success handling
                    bookingForm.reset();
                    selectedProducts.clear();
                    document.querySelectorAll('.quantity-input').forEach(input => {
                        input.value = 0;
                        input.dispatchEvent(new Event('change'));
                    });
                    updateUI();
            
                    // Hide booking modal and show payment success modal
                    bookingModal.hide();
                    const paymentSuccessModal = new bootstrap.Modal(document.getElementById('paymentSuccessModal'));
                    paymentSuccessModal.show();
            
                    // Show receipt
                    showReceipt(data);

                } catch (error) {
                    console.error('Booking error:', error);
                    alert('Error: ' + error.message);
                } finally {
                    submitButton.disabled = false;
                    submitButton.innerHTML = '<i class="fas fa-check me-2"></i>Confirm Booking';
                }
            });

            function showReceipt(data) {
                const receiptDetails = document.querySelector('.receipt-details');
                const receipt = data.receipt;
                
                receiptDetails.innerHTML = `
                    <div class="row mb-4">
                        <div class="col-6">
                            <p><strong>Booking Reference:</strong><br>${receipt.booking_reference}</p>
                            <p><strong>Date:</strong><br>${receipt.date}</p>
                        </div>
                        <div class="col-6 text-end">
                            <p><strong>Preferred Date:</strong><br>${receipt.preferred_date}</p>
                        </div>
                    </div>
                    <div class="selected-items-list mb-4"></div>
                    <div class="row">
                        <div class="col-6">
                            <p><strong>Subtotal:</strong></p>
                            <p><strong>Processing Fee:</strong></p>
                            <p><strong>Total Amount:</strong></p>
                        </div>
                        <div class="col-6 text-end">
                            <p>₱${receipt.total_amount}</p>
                            <p>₱${receipt.processing_fee}</p>
                            <p>₱${receipt.grand_total}</p>
                        </div>
                    </div>
                `;

                // Copy selected items to receipt
                const receiptItemsList = receiptDetails.querySelector('.selected-items-list');
                receiptItemsList.innerHTML = document.querySelector('#bookingModal .selected-items-list').innerHTML;

                // Show receipt modal
                receiptModal.show();
            }
        });
    </script>
</body>
</html>
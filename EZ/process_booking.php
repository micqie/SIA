<?php
session_start();
require 'database/connect_db.php';

header('Content-Type: application/json');

// Debug session data
error_log("Session data: " . print_r($_SESSION, true));

// Comprehensive session validation
if (!isset($_SESSION['account_id']) || !isset($_SESSION['role'])) {
    error_log("Session validation failed: Missing account_id or role");
    echo json_encode([
        'success' => false, 
        'message' => 'Your session has expired. Please log in again.',
        'code' => 'SESSION_EXPIRED'
    ]);
    exit();
}

if ($_SESSION['role'] !== 'U') {
    error_log("Session validation failed: Invalid role - " . $_SESSION['role']);
    echo json_encode([
        'success' => false, 
        'message' => 'Invalid user role. Please log in with a user account.',
        'code' => 'INVALID_ROLE'
    ]);
    exit();
}

// Get and validate POST data
$raw_data = file_get_contents('php://input');
error_log("Received data: " . $raw_data); // Debug received data

$data = json_decode($raw_data, true);

if (!$data) {
    error_log("Invalid JSON data received");
    echo json_encode([
        'success' => false, 
        'message' => 'Invalid data format received',
        'code' => 'INVALID_DATA'
    ]);
    exit();
}

// Validate required fields with detailed checks
if (empty($data['products'])) {
    echo json_encode([
        'success' => false, 
        'message' => 'No products selected',
        'code' => 'NO_PRODUCTS'
    ]);
    exit();
}

if (empty($data['preferredDate'])) {
    echo json_encode([
        'success' => false, 
        'message' => 'Preferred date is required',
        'code' => 'NO_DATE'
    ]);
    exit();
}

if (!isset($data['totalAmount']) || !is_numeric($data['totalAmount'])) {
    echo json_encode([
        'success' => false, 
        'message' => 'Invalid total amount',
        'code' => 'INVALID_AMOUNT'
    ]);
    exit();
}

// Validate payment information
if (empty($data['paymentMethod']) || empty($data['referenceNumber'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Payment information is required',
        'code' => 'INVALID_PAYMENT'
    ]);
    exit();
}

try {
    $conn->begin_transaction();

    // Generate booking reference
    $booking_reference = 'BK' . date('YmdHis') . rand(1000, 9999);
    $total_amount = floatval($data['totalAmount']);
    $processing_fee = $total_amount * 0.05;
    $special_instructions = isset($data['specialInstructions']) ? $data['specialInstructions'] : '';
    $account_id = intval($_SESSION['account_id']);

    error_log("Processing booking for account_id: " . $account_id);

    // Insert booking record with payment status
    $sql = "INSERT INTO bookings (account_id, booking_reference, total_amount, processing_fee, 
            preferred_date, special_instructions, status, payment_status) 
            VALUES (?, ?, ?, ?, ?, ?, 'pending', 'paid')";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Error preparing booking statement: " . $conn->error);
    }

    $stmt->bind_param('isddss', 
        $account_id,
        $booking_reference,
        $total_amount,
        $processing_fee,
        $data['preferredDate'],
        $special_instructions
    );
    
    if (!$stmt->execute()) {
        throw new Exception("Error creating booking: " . $stmt->error);
    }
    
    $booking_id = $conn->insert_id;
    
    // Insert payment record
    $payment_sql = "INSERT INTO payments (booking_id, amount, payment_method, payment_status, transaction_id) 
                    VALUES (?, ?, ?, 'completed', ?)";
    $payment_stmt = $conn->prepare($payment_sql);
    
    if (!$payment_stmt) {
        throw new Exception("Error preparing payment statement: " . $conn->error);
    }
    
    $payment_stmt->bind_param('idss',
        $booking_id,
        $total_amount,
        $data['paymentMethod'],
        $data['referenceNumber']
    );
    
    if (!$payment_stmt->execute()) {
        throw new Exception("Error recording payment: " . $payment_stmt->error);
    }
    
    // Insert booking details
    $detail_sql = "INSERT INTO booking_details (booking_id, product_id, quantity, unit_price, subtotal) 
                   VALUES (?, ?, ?, ?, ?)";
    $detail_stmt = $conn->prepare($detail_sql);
    
    if (!$detail_stmt) {
        throw new Exception("Error preparing detail statement: " . $conn->error);
    }

    foreach ($data['products'] as $product) {
        if (!isset($product['id']) || !isset($product['quantity']) || !isset($product['price'])) {
            throw new Exception("Invalid product data");
        }

        $subtotal = floatval($product['price']) * intval($product['quantity']);
        $product_id = intval($product['id']);
        $quantity = intval($product['quantity']);
        $price = floatval($product['price']);

        $detail_stmt->bind_param('iiidd',
            $booking_id,
            $product_id,
            $quantity,
            $price,
            $subtotal
        );
        
        if (!$detail_stmt->execute()) {
            throw new Exception("Error adding product details: " . $detail_stmt->error);
        }
    }
    
    $conn->commit();
    
    // Prepare receipt data
    $receipt = [
        'booking_reference' => $booking_reference,
        'date' => date('Y-m-d H:i:s'),
        'preferred_date' => $data['preferredDate'],
        'total_amount' => number_format($total_amount, 2),
        'processing_fee' => number_format($processing_fee, 2),
        'grand_total' => number_format($total_amount + $processing_fee, 2),
        'products' => $data['products'],
        'payment_method' => $data['paymentMethod'],
        'reference_number' => $data['referenceNumber']
    ];
    
    echo json_encode([
        'success' => true,
        'message' => 'Booking confirmed successfully',
        'receipt' => $receipt
    ]);
    
} catch (Exception $e) {
    $conn->rollback();
    error_log("Booking error: " . $e->getMessage()); // Log the error
    echo json_encode([
        'success' => false,
        'message' => 'Error processing booking: ' . $e->getMessage()
    ]);
}

$conn->close();
?> 
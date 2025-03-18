<?php
session_start();
include 'database/connect_db.php';

// Check if user is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'U') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Get JSON data
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if ($data === null) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON data']);
    exit();
}

try {
    // Start transaction
    $conn->begin_transaction();

    // Generate booking reference
    $booking_reference = 'BK' . date('YmdHis') . rand(100, 999);
    $verification_code = strtoupper(substr(md5(uniqid()), 0, 8));
    
    // Calculate totals
    $total_amount = $data['totalAmount'];
    $processing_fee = $total_amount * 0.05;
    $grand_total = $total_amount + $processing_fee;

    // Insert booking record
    $stmt = $conn->prepare("INSERT INTO bookings (account_id, booking_reference, verification_code, preferred_date, special_instructions, total_amount, processing_fee, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("issssdd", 
        $_SESSION['account_id'],
        $booking_reference,
        $verification_code,
        $data['preferredDate'],
        $data['specialInstructions'],
        $total_amount,
        $processing_fee
    );
    $stmt->execute();
    $booking_id = $conn->insert_id;

    // Insert payment record
    $payment_stmt = $conn->prepare("INSERT INTO payments (booking_id, amount, payment_method, payment_status) VALUES (?, ?, ?, 'pending')");
    $payment_stmt->bind_param("ids",
        $booking_id,
        $grand_total,
        $data['paymentMethod']
    );
    $payment_stmt->execute();

    // Insert booking details and update stock
    foreach ($data['products'] as $product) {
        // Check stock availability
        $stock_check = $conn->prepare("SELECT stock FROM products WHERE product_id = ? AND stock >= ?");
        $stock_check->bind_param("ii", $product['id'], $product['quantity']);
        $stock_check->execute();
        $stock_result = $stock_check->get_result();
        
        if ($stock_result->num_rows === 0) {
            throw new Exception("Insufficient stock for one or more products");
        }

        // Insert booking detail
        $detail_stmt = $conn->prepare("INSERT INTO booking_details (booking_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)");
        $detail_stmt->bind_param("iiid", $booking_id, $product['id'], $product['quantity'], $product['price']);
        $detail_stmt->execute();

        // Update stock
        $update_stock = $conn->prepare("UPDATE products SET stock = stock - ? WHERE product_id = ?");
        $update_stock->bind_param("ii", $product['quantity'], $product['id']);
        $update_stock->execute();
    }

    // Commit transaction
    $conn->commit();

    // Prepare receipt data
    $receipt = [
        'booking_reference' => $booking_reference,
        'verification_code' => $verification_code,
        'date' => date('Y-m-d H:i:s'),
        'preferred_date' => $data['preferredDate'],
        'total_amount' => number_format($total_amount, 2),
        'processing_fee' => number_format($processing_fee, 2),
        'grand_total' => number_format($grand_total, 2)
    ];

    echo json_encode([
        'success' => true,
        'message' => 'Booking successful',
        'receipt' => $receipt
    ]);

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$conn->close();
?> 
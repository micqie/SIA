<?php
session_start();
require 'database/connect_db.php';

header('Content-Type: application/json');

// Get the reference number
$reference = isset($_GET['ref']) ? trim($_GET['ref']) : '';

if (empty($reference)) {
    echo json_encode([
        'success' => false,
        'message' => 'Reference number is required'
    ]);
    exit();
}

try {
    // Check if the reference exists and is valid
    $query = "SELECT b.*, gb.guest_reference 
              FROM bookings b 
              LEFT JOIN guest_bookings gb ON b.booking_id = gb.booking_id 
              WHERE b.booking_reference = ? OR gb.guest_reference = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $reference, $reference);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid reference number'
        ]);
        exit();
    }
    
    $booking = $result->fetch_assoc();
    
    // Store booking info in session for guest dashboard
    $_SESSION['guest_booking'] = [
        'booking_id' => $booking['booking_id'],
        'booking_reference' => $booking['booking_reference'],
        'guest_reference' => $booking['guest_reference'],
        'status' => $booking['status']
    ];
    
    echo json_encode([
        'success' => true,
        'message' => 'Access granted'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error verifying access: ' . $e->getMessage()
    ]);
}

$conn->close();
?> 
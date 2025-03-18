<?php
session_start();
require 'database/connect_db.php';

header('Content-Type: application/json');

// Check if admin is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'A') {
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized access'
    ]);
    exit();
}

// Get and validate POST data
$booking_id = isset($_POST['booking_id']) ? intval($_POST['booking_id']) : 0;
$status = isset($_POST['status']) ? $_POST['status'] : '';

if (!$booking_id || !in_array($status, ['confirmed', 'declined'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid parameters'
    ]);
    exit();
}

try {
    $conn->begin_transaction();

    // Update booking status
    $update_sql = "UPDATE bookings SET status = ?, updated_at = NOW() WHERE booking_id = ?";
    $stmt = $conn->prepare($update_sql);
    
    if (!$stmt) {
        throw new Exception("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param('si', $status, $booking_id);
    
    if (!$stmt->execute()) {
        throw new Exception("Error updating booking: " . $stmt->error);
    }

    $conn->commit();
    
    echo json_encode([
        'success' => true,
        'message' => 'Booking status updated successfully'
    ]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode([
        'success' => false,
        'message' => 'Error updating booking status: ' . $e->getMessage()
    ]);
}

$conn->close();
?> 
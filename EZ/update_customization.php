<?php
// Prevent any output before this point
if (ob_get_level()) ob_end_clean();

// Start fresh output buffer
ob_start();

// Set error handling
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Set headers
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

session_start();
require 'database/connect_db.php';

// Basic validation
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['booking_id']) || empty($_POST['selected_color'])) {
    $_SESSION['customization_error'] = 'Missing required fields';
    header('Location: guest_dashboard.php');
    exit();
}

$booking_id = intval($_POST['booking_id']);
$guest_name = isset($_POST['guest_name']) ? trim($_POST['guest_name']) : '';
$selected_color = trim($_POST['selected_color']);

try {
    // Get product details
    $stmt = $conn->prepare("SELECT p.product_name, b.booking_reference 
                           FROM bookings b 
                           LEFT JOIN booking_details bd ON b.booking_id = bd.booking_id 
                           LEFT JOIN products p ON bd.product_id = p.product_id 
                           WHERE b.booking_id = ?");
    
    if (!$stmt || !$stmt->bind_param('i', $booking_id) || !$stmt->execute()) {
        throw new Exception('Database error');
    }

    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        throw new Exception('Booking not found');
    }

    $product_info = $result->fetch_assoc();
    $stmt->close();

    // Generate customization code
    $customization_code = 'CUST' . date('Ymd') . rand(1000, 9999);
    $customization_details = json_encode([
        'color' => $selected_color,
        'customization_code' => $customization_code,
        'timestamp' => date('Y-m-d H:i:s')
    ]);

    // Save or update customization
    $stmt = $conn->prepare("INSERT INTO guest_bookings (booking_id, guest_name, customization_details) 
                           VALUES (?, ?, ?) 
                           ON DUPLICATE KEY UPDATE 
                           guest_name = VALUES(guest_name), 
                           customization_details = VALUES(customization_details)");

    if (!$stmt || !$stmt->bind_param('iss', $booking_id, $guest_name, $customization_details) || !$stmt->execute()) {
        throw new Exception('Failed to save customization');
    }

    $stmt->close();

    // Set success message
    $_SESSION['customization_success'] = true;
    $_SESSION['customization_details'] = [
        'guest_name' => $guest_name,
        'booking_reference' => $product_info['booking_reference'],
        'product_name' => $product_info['product_name'],
        'selected_color' => $selected_color,
        'customization_code' => $customization_code
    ];

} catch (Exception $e) {
    error_log('Customization Error: ' . $e->getMessage());
    $_SESSION['customization_error'] = $e->getMessage();
}

header('Location: guest_dashboard.php');
exit();
?> 
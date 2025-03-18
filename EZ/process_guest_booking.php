<?php
session_start();
require 'database/connect_db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: guest_dashboard.php');
    exit();
}

$error = null;
$success = false;

// Validate and sanitize input
$bundle_id = filter_input(INPUT_POST, 'bundle_id', FILTER_VALIDATE_INT);
$guest_name = filter_input(INPUT_POST, 'guest_name', FILTER_SANITIZE_STRING);
$guest_email = filter_input(INPUT_POST, 'guest_email', FILTER_VALIDATE_EMAIL);
$guest_phone = filter_input(INPUT_POST, 'guest_phone', FILTER_SANITIZE_STRING);
$selected_color = filter_input(INPUT_POST, 'selected_color', FILTER_SANITIZE_STRING);
$special_instructions = filter_input(INPUT_POST, 'special_instructions', FILTER_SANITIZE_STRING);
$bundle_quantity = filter_input(INPUT_POST, 'bundle_quantity', FILTER_VALIDATE_INT);

// Validate required fields
if (!$bundle_id || !$guest_name || !$guest_email || !$guest_phone || !$selected_color || !$bundle_quantity || $bundle_quantity < 1 || $bundle_quantity > 2) {
    $error = "Please fill in all required fields correctly.";
} else {
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Generate unique booking reference
        $booking_reference = 'BK' . date('Ymd') . rand(1000, 9999);
        
        // Get bundle price and calculate total
        $query = "SELECT price, pieces_count FROM bundles WHERE bundle_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $bundle_id);
        $stmt->execute();
        $bundle_result = $stmt->get_result();
        $bundle_data = $bundle_result->fetch_assoc();
        
        $total_amount = $bundle_data['price'] * $bundle_quantity;
        $total_pieces = $bundle_data['pieces_count'] * $bundle_quantity;
        
        // Insert into bookings table
        $query = "INSERT INTO bookings (booking_reference, verification_code, total_amount, processing_fee, preferred_date, special_instructions, status) 
                  VALUES (?, ?, ?, 0, DATE_ADD(CURDATE(), INTERVAL 1 DAY), ?, 'pending')";
        
        $stmt = $conn->prepare($query);
        $verification_code = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 8);
        $stmt->bind_param('ssds', $booking_reference, $verification_code, $total_amount, $special_instructions);
        $stmt->execute();
        
        $booking_id = $conn->insert_id;
        
        // Insert into guest_bookings table
        $query = "INSERT INTO guest_bookings (booking_id, guest_reference, guest_name, guest_email, guest_phone, customization_details, status) 
                  VALUES (?, ?, ?, ?, ?, ?, 'pending')";
        
        $stmt = $conn->prepare($query);
        $customization_details = json_encode([
            'color' => $selected_color,
            'quantity' => $bundle_quantity
        ]);
        $guest_reference = 'GB' . date('Ymd') . rand(1000, 9999);
        $stmt->bind_param('isssss', $booking_id, $guest_reference, $guest_name, $guest_email, $guest_phone, $customization_details);
        $stmt->execute();
        
        // Insert into booking_details table
        $query = "INSERT INTO booking_details (booking_id, product_id, quantity, unit_price, subtotal) 
                  VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($query);
        $unit_price = $bundle_data['price'];
        $subtotal = $unit_price * $bundle_quantity;
        $stmt->bind_param('iiidd', $booking_id, $bundle_id, $bundle_quantity, $unit_price, $subtotal);
        $stmt->execute();
        
        // Commit transaction
        $conn->commit();
        $success = true;
        
        // Store success message in session
        $_SESSION['booking_success'] = [
            'guest_reference' => $guest_reference,
            'guest_name' => $guest_name,
            'quantity' => $bundle_quantity,
            'total_amount' => $total_amount
        ];
        
        // Redirect to success page
        header('Location: guest_booking_success.php');
        exit();
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        $error = "An error occurred while processing your booking. Please try again later.";
        error_log("Booking error: " . $e->getMessage());
    }
}

// If there was an error, redirect back to dashboard with error message
if ($error) {
    $_SESSION['booking_error'] = $error;
    header('Location: guest_dashboard.php');
    exit();
}
?> 
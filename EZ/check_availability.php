<?php
session_start();
require 'database/connect_db.php';

header('Content-Type: application/json');

// Get the date to check
$date = isset($_GET['date']) ? $_GET['date'] : '';

if (empty($date)) {
    echo json_encode([
        'success' => false,
        'message' => 'Date parameter is required'
    ]);
    exit();
}

try {
    // Get the count of bookings for the specified date
    $query = "SELECT COUNT(*) as booking_count 
              FROM bookings 
              WHERE DATE(preferred_date) = ? 
              AND status IN ('pending', 'confirmed')";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $date);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    $booking_count = $row['booking_count'];
    $max_bookings_per_day = 1; // Maximum number of bookings allowed per day
    
    // Determine availability status
    $availability = [
        'available' => $booking_count < $max_bookings_per_day,
        'booking_count' => $booking_count,
        'max_bookings' => $max_bookings_per_day,
        'slots_left' => max(0, $max_bookings_per_day - $booking_count)
    ];
    
    echo json_encode([
        'success' => true,
        'data' => $availability
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error checking availability: ' . $e->getMessage()
    ]);
}

$conn->close();
?> 
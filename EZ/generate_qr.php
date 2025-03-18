<?php
// Get the customization code from URL parameter
$code = isset($_GET['code']) ? $_GET['code'] : '';

if (empty($code)) {
    die('No code provided');
}

// Generate the URL for the customization view page
$url = 'http://' . $_SERVER['HTTP_HOST'] . '/SIA/EZ/view_customization.php?code=' . urlencode($code);

// Use QR Server API instead of Google Charts
$qr_url = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($url);

// Initialize cURL session
$ch = curl_init();

// Set cURL options
curl_setopt_array($ch, [
    CURLOPT_URL => $qr_url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_TIMEOUT => 10
]);

// Execute cURL session and get the QR code image
$qr_image = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    error_log('QR Code Generation Error: ' . curl_error($ch));
    // Return a simple error image or message
    header('Content-Type: text/plain');
    die('Error: Unable to generate QR code. Please try again.');
}

// Get HTTP response code
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if ($http_code !== 200) {
    error_log('QR Code HTTP Error: ' . $http_code);
    header('Content-Type: text/plain');
    die('Error: Server returned code ' . $http_code);
}

// Close cURL session
curl_close($ch);

// Set headers
header('Content-Type: image/png');
header('Cache-Control: public, max-age=86400');
header('Pragma: public');

// Output the QR code image
echo $qr_image;
?> 
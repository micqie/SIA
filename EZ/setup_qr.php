<?php
// Create phpqrcode directory if it doesn't exist
if (!file_exists('phpqrcode')) {
    mkdir('phpqrcode', 0777, true);
}

// Download the PHP QR Code library
$url = 'https://raw.githubusercontent.com/t0k4rt/phpqrcode/master/qrlib.php';
$qrlib = file_get_contents($url);

if ($qrlib === false) {
    die('Failed to download QR Code library');
}

// Save the library
file_put_contents('phpqrcode/qrlib.php', $qrlib);

echo "QR Code library has been installed successfully!";
?> 
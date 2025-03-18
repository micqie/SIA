<?php
session_start();
header('Content-Type: application/json');

$response = [
    'isLoggedIn' => isset($_SESSION['account_id']) && isset($_SESSION['role']),
    'role' => isset($_SESSION['role']) ? $_SESSION['role'] : null,
    'account_id' => isset($_SESSION['account_id']) ? $_SESSION['account_id'] : null
];

echo json_encode($response);
?> 
<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'U') {
    header("Location: index.php"); // Redirect if not user
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
            margin: 0;
        }
        .container {
            text-align: center;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            max-width: 800px;
            margin: auto;
        }
        .product {
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: transform 0.2s;
        }
        .product:hover {
            transform: scale(1.05);
        }
        img {
            width: 100%;
            border-radius: 10px;
        }
        h3 {
            margin: 10px 0;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Store Dashboard</h2>
        <div class="grid">
            <div class="product" onclick="location.href='order.html?product=1'">
                <img src="product1.jpg" alt="Product 1">
                <h3>Product 1</h3>
            </div>
            <div class="product" onclick="location.href='order.html?product=2'">
                <img src="product2.jpg" alt="Product 2">
                <h3>Product 2</h3>
            </div>
            <div class="product" onclick="location.href='order.html?product=3'">
                <img src="product3.jpg" alt="Product 3">
                <h3>Product 3</h3>
            </div>
            <div class="product" onclick="location.href='order.html?product=4'">
                <img src="product4.jpg" alt="Product 4">
                <h3>Product 4</h3>
            </div>
        </div>
    </div>
</body>
</html>
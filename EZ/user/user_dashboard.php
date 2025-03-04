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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 20px;
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
    <h2>Store Dashboard</h2>
    <div class="grid">
        <div class="product" onclick="openModal('Product 1')">
            <img src="product1.jpg" alt="Product 1">
            <h3>Product 1</h3>
        </div>
        <div class="product" onclick="openModal('Product 2')">
            <img src="product2.jpg" alt="Product 2">
            <h3>Product 2</h3>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal" id="orderModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productTitle">Select Quantity</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label>White:</label>
                    <input type="number" id="whiteQty" class="form-control" min="0" value="0">
                    <label>Black:</label>
                    <input type="number" id="blackQty" class="form-control" min="0" value="0">
                    <label>Total Quantity:</label>
                    <input type="number" id="totalQty" class="form-control" min="1" value="200">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="confirmOrder()">Confirm</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal(productName) {
            document.getElementById('productTitle').innerText = productName;
            var myModal = new bootstrap.Modal(document.getElementById('orderModal'));
            myModal.show();
        }

        function confirmOrder() {
            let white = parseInt(document.getElementById('whiteQty').value) || 0;
            let black = parseInt(document.getElementById('blackQty').value) || 0;
            let total = parseInt(document.getElementById('totalQty').value);

            if (white + black !== total) {
                alert("Total quantity must be " + total + " items.");
                return;
            }

            alert("Order Confirmed: " + white + " White, " + black + " Black.");
        }
    </script>
</body>
</html>

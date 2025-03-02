<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'A') {
    header("Location: index.php"); // Redirect if not admin
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="css/admin.css">

</head>
<body>
    <div class="top-nav">
        <h1>Admin Dashboard</h1>
    </div>
    <div class="sidebar">
        <a href="#">DASHBOARD</a>
        <a href="#">BOOKINGS</a>
        <a href="#">PRODUCTS</a>
        <a href="#">REPORTS</a>
        <a href="#" class="logout">LOG OUT</a>
    </div>
    <div class="main-content">
        <h2>Dashboard Overview</h2>
        <div class="dashboard-card">
            <div class="row">
                <div class="col-md-3 text-center">
                    <h5>Total Bookings</h5>
                    <p>120</p>
                </div>
                <div class="col-md-3 text-center">
                    <h5>Stocks Left</h5>
                    <p>Leather: 50 | Perfumes: 30</p>
                </div>
                <div class="col-md-3 text-center">
                    <h5>Reports Generated</h5>
                    <p>15 This Month</p>
                </div>
                <div class="col-md-3 text-center">
                    <h5>Total Users</h5>
                    <p>200</p>
                </div>
            </div>
            <canvas id="chart"></canvas>
        </div>
    </div>
    <script>
        var ctx = document.getElementById('chart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                datasets: [{
                    label: 'Bookings',
                    data: [12, 19, 3, 5, 2, 3, 10, 15, 9, 7, 11, 13],
                    backgroundColor: 'rgba(54, 162, 235, 0.5)'
                }]
            }
        });
    </script>
</body>
</html>

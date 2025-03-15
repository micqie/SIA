<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container-fluid d-flex">
        <!-- Sidebar -->
        <div class="sidebar bg-warning p-3">
            <h2 class="text-dark">EZ Leather Bar</h2>
            <ul class="nav flex-column">
                <li class="nav-item"><a href="#" class="nav-link">ADMIN</a></li>
                <li class="nav-item"><a href="#" class="nav-link">DASHBOARD</a></li>
                <li class="nav-item bg-secondary"><a href="#" class="nav-link text-white">BOOKINGS</a></li>
                <li class="nav-item"><a href="#" class="nav-link">STOCKS</a></li>
                <li class="nav-item"><a href="#" class="nav-link">REPORTS</a></li>
            </ul>
            <button class="btn btn-danger w-100 mt-3">LOG OUT</button>
        </div>
        
        <!-- Main Content -->
        <div class="main-content p-4 w-100">
            <h3>Bookings</h3>
            <p>Below is the list of customer bookings, including their reserved items and current status.</p>
            <button class="btn btn-success mb-2">+ Add Booking</button>
            <input type="text" class="form-control mb-2" placeholder="Search by name or ID">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Customer Name</th>
                        <th>Event Date</th>
                        <th>Address</th>
                        <th>Item Reserved</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>13865</td>
                        <td>John Doe</td>
                        <td>2025-02-20</td>
                        <td>CDO</td>
                        <td>Leather Wallets</td>
                        <td>3</td>
                        <td class="text-warning">Pending</td>
                        <td>
                            <button class="btn btn-primary btn-sm">Edit</button>
                            <button class="btn btn-success btn-sm">View</button>
                        </td>
                    </tr>
                    <!-- Add more rows dynamically from the database -->
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

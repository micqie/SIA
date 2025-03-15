<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/admin.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
        }
        .top-nav {
            background-color: #1E3A8A;
            color: white;
            padding: 15px 20px;
            text-align: center;
            font-size: 22px;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .top-nav:hover {
            background-color: #2563eb;
        }

        .top-nav .brand {
            font-weight: bold;
            letter-spacing: 1px;
        }

        .top-nav .nav-items {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .sidebar {
            width: 250px;
            position: fixed;
            top: 60px;
            left: 0;
            height: calc(100% - 60px);
            background: #172554;
            padding-top: 20px;
        }

        .sidebar a {
            padding: 15px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background: #1E3A8A;
        }

        .main-content {
            margin-left: 260px;
            margin-top: 80px;
            padding: 20px;
        }

        .settings-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 15px;
        }

        .btn-primary {
            background-color: #1E3A8A;
            border: none;
        }
        
        .btn-primary:hover {
            background-color: #172554;
        }
    </style>
</head>
<body>

    <div class="top-nav">
        <span>Admin Dashboard</span>
    </div>

    <div class="sidebar">
        <a href="dashboard.php">DASHBOARD</a>
        <a href="booking.php">BOOKINGS</a>
        <a href="stocks.php">STOCKS</a>
        <a href="reports.php">REPORTS</a>
        <a href="settings.php">SETTINGS</a>
        <a href="logout.php" class="text-danger">LOG OUT</a>
    </div>

    <div class="main-content">
        <h2>Admin Settings</h2>
        <div class="settings-container">
            <h4>Profile Settings</h4>
            <form>
                <div class="form-group">
                    <label>Admin Name:</label>
                    <input type="text" class="form-control" value="Admin Name">
                </div>
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" class="form-control" value="admin@example.com">
                </div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>

            <hr>

            <h4>Change Password</h4>
            <form>
                <div class="form-group">
                    <label>Current Password:</label>
                    <input type="password" class="form-control">
                </div>
                <div class="form-group">
                    <label>New Password:</label>
                    <input type="password" class="form-control">
                </div>
                <div class="form-group">
                    <label>Confirm New Password:</label>
                    <input type="password" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Update Password</button>
            </form>

            <hr>

            <h4>General Settings</h4>
            <form>
                <div class="form-group">
                    <label>Notification Preferences:</label>
                    <select class="form-control">
                        <option>Email Notifications</option>
                        <option>SMS Notifications</option>
                        <option>Push Notifications</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Theme:</label>
                    <select class="form-control">
                        <option>Light Mode</option>
                        <option>Dark Mode</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Save Settings</button>
            </form>
        </div>
    </div>

</body>
</html>
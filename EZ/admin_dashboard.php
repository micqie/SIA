<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'A') {
    header("Location: ../index.php"); // Redirect if not admin
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background: #f0f2f5;
            margin: 0;
            min-height: 100vh;
            color: #2c3e50;
            overflow-x: hidden;
            overflow-y: hidden;
        }

        /* Top Navigation */
        .top-nav {
            background: #ffffff;
            padding: 0 24px;
            height: 60px;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
        }

        .top-nav span {
            font-size: 1.4rem;
            font-weight: 600;
            color: #2c3e50;
            letter-spacing: 0.5px;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            position: fixed;
            top: 60px;
            left: 0;
            height: calc(100vh - 60px);
            background: #9D4D36;
            padding: 15px 0;
            transition: 0.3s ease-in-out;
            border-right: none;
            overflow-y: auto;
        }

        .sidebar a {
            padding: 14px 20px;
            text-decoration: none;
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s ease;
            margin: 4px 15px;
            border-radius: 10px;
            font-weight: 500;
            letter-spacing: 0.3px;
            border: 1px solid transparent;
        }

        .sidebar a i {
            width: 22px;
            text-align: center;
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.9);
        }

        .sidebar a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            transform: translateX(5px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar a.active {
            background: #F8E2A8;
            color: #9D4D36;
            font-weight: 600;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: none;
        }

        .sidebar a.active i {
            color: #9D4D36;
        }

        /* Sidebar Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            margin-top: 60px;
            padding: 20px;
            background: #F8E2A8;
            min-height: calc(100vh - 60px);
        }

        .main-content h2 {
            color: #2c3e50;
            margin: 0 0 20px 0;
            font-size: 1.6rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        /* Dashboard Cards */
        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: #ffffff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 1px solid #e6e9ed;
            position: relative;
            overflow: hidden;
            height: 100%;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(to right, #3498db, #2980b9);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .stat-card h3 {
            color: #5c6c7c;
            font-size: 1.1rem;
            margin-bottom: 15px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .stat-card p {
            color: #2c3e50;
            font-size: 2rem;
            margin: 0;
            font-weight: 600;
            line-height: 1.2;
        }

        .stat-card .stat-label {
            color: #8392a5;
            font-size: 0.9rem;
            margin-top: 8px;
            font-weight: 400;
        }

        /* Chart Container */
        .chart-container {
            background: #ffffff;
            padding: 25px;
            border-radius: 12px;
            margin-top: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid #e6e9ed;
        }

        .chart-container h3 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 1.1rem;
            font-weight: 500;
            padding-bottom: 15px;
            border-bottom: 1px solid #e6e9ed;
        }

        /* Progress Bar */
        .stat-progress {
            width: 100%;
            height: 6px;
            background: #f0f2f5;
            border-radius: 3px;
            margin-top: 15px;
            overflow: hidden;
        }

        .stat-progress-bar {
            height: 100%;
            background: linear-gradient(to right, #3498db, #2980b9);
            border-radius: 3px;
            transition: width 0.3s ease;
        }

        /* Icons */
        .stat-card .icon {
            font-size: 1.2rem;
            color: #3498db;
            background: rgba(52, 152, 219, 0.1);
            padding: 8px;
            border-radius: 8px;
        }

        .stocks-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 15px;
        }

        .stock-item {
            text-align: center;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .stock-item p {
            font-size: 1.5rem !important;
            margin-bottom: 5px;
        }

        /* Profile Menu */
        .profile-menu {
            position: relative;
        }

        .profile-icon {
            width: 40px;
            height: 40px;
            background-color: #333;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            cursor: pointer;
            transition: 0.3s ease;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .profile-icon:hover {
            background-color: #555;
            transform: scale(1.05);
        }

        .dropdown-menu {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            padding: 10px;
            min-width: 180px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .dropdown-menu a {
            color: #333;
            padding: 12px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
            margin: 5px 0;
            font-weight: 500;
        }

        .dropdown-menu a:hover {
            background: rgba(255, 215, 0, 0.1);
            color: #333;
            transform: translateX(5px);
        }

        .dropdown-menu a.text-danger {
            color: #dc3545;
        }

        .dropdown-menu a.text-danger:hover {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }
    </style>
</head>
<body>

    <div class="top-nav">
     
        <span>Admin Dashboard</span>
        
        <!-- Profile Icon -->
        <div class="profile-menu">
    <div class="profile-icon" onclick="toggleDropdown()">A</div> <!-- You can replace this with an image -->
    <div class="dropdown-menu" id="profileDropdown">
        <a href="admin_profile.php">Profile</a>
        <a href="admin_settings.php">Settings</a>
        <a href="logout.php" class="text-danger">Logout</a>
    </div>
</div>
    </div>

    <div class="sidebar" id="sidebar">
        <a href="#" class="active"><i class="fas fa-th-large"></i> Dashboard</a>
        <a href="admin_bookings.php"><i class="fas fa-calendar-alt"></i> Bookings</a>
        <a href="admin_stocks.php"><i class="fas fa-box"></i> Stocks</a>
        <a href="admin_reports.php"><i class="fas fa-chart-bar"></i> Reports</a>
    </div>

    <div class="main-content">
        <h2>Dashboard Overview</h2>
        
        <div class="dashboard-stats">
            <div class="stat-card">
                <h3><i class="fas fa-calendar-check icon"></i>Total Bookings</h3>
                <p>120</p>
                <div class="stat-label">Last 30 days</div>
                <div class="stat-progress">
                    <div class="stat-progress-bar" style="width: 75%"></div>
                </div>
            </div>
            
            <div class="stat-card">
                <h3><i class="fas fa-box icon"></i>Stocks Overview</h3>
                <div class="stocks-grid">
                    <div class="stock-item">
                        <p>50</p>
                        <div class="stat-label">Leather Items</div>
                        <div class="stat-progress">
                            <div class="stat-progress-bar" style="width: 60%"></div>
                        </div>
                    </div>
                    <div class="stock-item">
                        <p>30</p>
                        <div class="stat-label">Perfumes</div>
                        <div class="stat-progress">
                            <div class="stat-progress-bar" style="width: 40%"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="stat-card">
                <h3><i class="fas fa-file-alt icon"></i>Reports Generated</h3>
                <p>15</p>
                <div class="stat-label">This Month</div>
                <div class="stat-progress">
                    <div class="stat-progress-bar" style="width: 45%"></div>
                </div>
            </div>
        </div>

        <div class="chart-container">
            <h3>Booking Trends</h3>
            <canvas id="chart"></canvas>
        </div>
    </div>

    <script>
        var ctx = document.getElementById('chart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Bookings',
                    data: [12, 19, 3, 5, 2, 3, 10, 15, 9, 7, 11, 13],
                    backgroundColor: 'rgba(52, 152, 219, 0.1)',
                    borderColor: '#3498db',
                    borderWidth: 2,
                    tension: 0.4,
                    pointBackgroundColor: '#3498db',
                    pointBorderColor: '#3498db',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#3498db',
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        labels: {
                            color: '#2c3e50',
                            font: {
                                size: 12,
                                family: "'Poppins', sans-serif"
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#e6e9ed'
                        },
                        ticks: {
                            color: '#5c6c7c',
                            font: {
                                size: 11,
                                family: "'Poppins', sans-serif"
                            }
                        }
                    },
                    x: {
                        grid: {
                            color: '#e6e9ed'
                        },
                        ticks: {
                            color: '#5c6c7c',
                            font: {
                                size: 11,
                                family: "'Poppins', sans-serif"
                            }
                        }
                    }
                }
            }
        });

        function toggleDropdown() {
            var dropdown = document.getElementById("profileDropdown");
            dropdown.classList.toggle("show");
        }

        window.onclick = function(event) {
            if (!event.target.closest('.profile-menu')) {
                document.getElementById("profileDropdown").classList.remove("show");
            }
        };
    </script>

</body>
</html>

<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'U') {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Availability Calendar - EZ Leather Bar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet'>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>

    <style>
        :root {
            --primary-color: #9D4D36;
            --accent-color: #ffc107;
            --text-light: #ffffff;
            --text-dark: #2c3e50;
            --bg-transparent: rgba(108, 86, 22, 0.434);
        }

        body {
            font-family: 'Poppins', Arial, sans-serif;
            background: url(../assets/leather_bg.png) no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            padding-top: 80px;
        }

        .navbar {
            background: var(--bg-transparent) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .calendar-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            max-width: 1200px;
        }

        .legend {
            background: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            margin-right: 10px;
        }

        .availability-info {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .availability-info h5 {
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .info-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .info-item i {
            color: var(--primary-color);
            margin-right: 10px;
            width: 20px;
        }

        #calendar {
            background: white;
            padding: 20px;
            border-radius: 10px;
        }

        .fc-theme-standard td, .fc-theme-standard th {
            border-color: #ddd;
        }

        .fc-day-today {
            background: rgba(157, 77, 54, 0.1) !important;
        }

        .fc-button-primary {
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
        }

        .fc-button-primary:hover {
            background-color: #8B3D26 !important;
        }

        .status-available {
            background-color: #28a745;
        }

        .status-busy {
            background-color: #dc3545;
        }

        .status-limited {
            background-color: #ffc107;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand text-white" href="user_dashboard.php">
                <img src="../assets/logo2.jpg" alt="Logo" style="height: 40px; margin-right: 10px;">
                EZ Leather Bar
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="user_dashboard.php">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </li>
                </ul>
                <div class="user-email text-white">
                    <i class="fas fa-user-circle"></i>
                    <?php echo $_SESSION['username']; ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="calendar-container">
            <div class="row">
                <div class="col-md-3">
                    <div class="availability-info">
                        <h5><i class="fas fa-info-circle me-2"></i>Team Availability</h5>
                        <div class="info-item">
                            <i class="fas fa-clock"></i>
                            <span>Working Hours: 9 AM - 6 PM</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Monday - Saturday</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-tools"></i>
                            <span>Production: 3-5 days</span>
                        </div>
                    </div>

                    <div class="legend">
                        <h6 class="mb-3">Calendar Legend</h6>
                        <div class="legend-item">
                            <div class="legend-color status-available"></div>
                            <span>Available</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color status-limited"></div>
                            <span>Limited Availability</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color status-busy"></div>
                            <span>Fully Booked</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek'
                },
                selectable: true,
                selectMirror: true,
                weekends: false, // Hide weekends
                events: [
                    // Sample events - replace with actual availability data
                    {
                        title: 'Available',
                        start: '2024-03-20',
                        className: 'status-available'
                    },
                    {
                        title: 'Limited Slots',
                        start: '2024-03-21',
                        className: 'status-limited'
                    },
                    {
                        title: 'Fully Booked',
                        start: '2024-03-22',
                        className: 'status-busy'
                    }
                ],
                eventClick: function(info) {
                    alert('Status: ' + info.event.title);
                }
            });
            calendar.render();
        });
    </script>
</body>
</html> 
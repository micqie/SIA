<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EZ Leather Bar - Guest Access</title>
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- QR Code Scanner -->
    <script src="https://unpkg.com/html5-qrcode"></script>

    <style>
        /* Common Design Elements */
        :root {
            --primary-color: #ffc107;
            --primary-hover: rgba(224, 179, 0, 0.8);
            --text-light: #ffffff;
            --text-dark: #000000;
            --overlay-bg: rgba(0, 0, 0, 0.6);
            --card-bg: rgba(255, 255, 255, 0.1);
        }

        body {
            font-family: 'Poppins', Arial, sans-serif;
            background-image: url(assets/leather_bg.png);
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: var(--text-light);
            text-align: center;
            scroll-behavior: smooth;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow-x: hidden;
        }

        /* Enhanced Navbar Styles */
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            padding: 20px 0;
            transition: all 0.3s ease;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-size: 2rem;
            color: var(--text-dark) !important;
            font-weight: 800;
            transition: all 0.3s ease;
            padding: 0 15px;
            letter-spacing: 1px;
        }

        .navbar-brand:hover {
            color: var(--primary-color) !important;
            transform: translateY(-2px);
        }

        .navbar-brand i {
            font-size: 1.8rem;
            margin-right: 12px;
            color: var(--primary-color);
        }

        .nav-link {
            color: var(--text-dark) !important;
            font-weight: 600;
            font-size: 1.1rem;
            padding: 12px 24px !important;
            margin: 0 8px;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .nav-link:before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary-color);
            transition: width 0.3s ease;
        }

        .nav-link:hover:before {
            width: 100%;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
            transform: translateY(-2px);
        }

        .nav-link i {
            font-size: 1.2rem;
            margin-right: 8px;
            color: var(--primary-color);
        }

        /* Guest Content Styles */
        .main-container {
            padding-top: 100px;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .guest-card {
            background: var(--card-bg);
            padding: 30px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
            max-width: 600px;
            width: 100%;
            margin: 20px;
            color: var(--text-light);
            animation: fadeInUp 0.8s ease-out;
            transition: all 0.3s ease;
        }

        .guest-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.4);
        }

        .guest-card h2 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 25px;
            color: var(--primary-color);
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        #qr-reader {
            width: 100%;
            max-width: 500px;
            margin: 0 auto 20px;
            border-radius: 10px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.1);
        }

        #qr-reader video {
            border-radius: 10px;
        }

        .input-group {
            margin-top: 30px;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.15);
            color: var(--text-light);
            border: 2px solid transparent;
            padding: 15px;
            font-size: 1.1rem;
            border-radius: 10px !important;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.2);
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 15px rgba(255, 193, 7, 0.2);
            color: var(--text-light);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .btn-submit {
            background: var(--primary-color);
            color: var(--text-dark);
            border: none;
            padding: 15px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .btn-submit:hover {
            background: var(--primary-hover);
            color: var(--text-light);
            transform: translateY(-3px);
        }

        .or-divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 30px 0;
            color: var(--text-light);
            font-weight: 500;
        }

        .or-divider::before,
        .or-divider::after {
            content: '';
            flex: 1;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        }

        .or-divider::before {
            margin-right: 15px;
        }

        .or-divider::after {
            margin-left: 15px;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Selection Buttons Styles */
        .selection-container {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-bottom: 30px;
        }

        .selection-btn {
            background: var(--card-bg);
            color: var(--text-light);
            border: 2px solid var(--primary-color);
            padding: 20px 30px;
            font-size: 1.2rem;
            font-weight: 600;
            border-radius: 15px;
            transition: all 0.3s ease;
            width: 200px;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .selection-btn i {
            font-size: 2rem;
            color: var(--primary-color);
        }

        .selection-btn:hover {
            transform: translateY(-5px);
            background: var(--primary-color);
            color: var(--text-dark);
        }

        .selection-btn:hover i {
            color: var(--text-dark);
        }

        /* Hide sections initially */
        #scanner-section, #manual-section {
            display: none;
            animation: fadeIn 0.5s ease-out;
        }

        #scanner-section.active, #manual-section.active {
            display: block;
        }

        .back-btn {
            background: rgba(255, 255, 255, 0.1);
            color: var(--text-light);
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            margin-bottom: 20px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateX(-5px);
        }

        .scan-status {
            margin-top: 15px;
            padding: 10px;
            border-radius: 8px;
            display: none;
        }

        .scan-status.success {
            background: rgba(40, 167, 69, 0.2);
            color: #28a745;
            display: block;
        }

        .scan-status.error {
            background: rgba(220, 53, 69, 0.2);
            color: #dc3545;
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-store-alt me-2"></i>EZ Leather Bar
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <i class="fas fa-home me-1"></i>Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">
                            <i class="fas fa-info-circle me-1"></i>About
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">
                            <i class="fas fa-envelope me-1"></i>Contact
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-container">
        <div class="guest-card">
            <h2>Guest Access</h2>
            <p class="lead mb-4">Choose your preferred method to access the system</p>

            <!-- Selection Buttons -->
            <div id="selection-section">
                <div class="selection-container">
                    <button class="selection-btn" onclick="showSection('scanner')">
                        <i class="fas fa-qrcode"></i>
                        Scan QR Code
                    </button>
                    <button class="selection-btn" onclick="showSection('manual')">
                        <i class="fas fa-keyboard"></i>
                        Manual Input
                    </button>
                </div>
            </div>

            <!-- Scanner Section -->
            <div id="scanner-section">
                <button class="back-btn" onclick="showSelection()">
                    <i class="fas fa-arrow-left"></i> Back
                </button>
                <h3 class="mb-4">Scan QR Code</h3>
                <div id="qr-reader"></div>
                <div id="scan-status" class="scan-status"></div>
            </div>

            <!-- Manual Input Section -->
            <div id="manual-section">
                <button class="back-btn" onclick="showSelection()">
                    <i class="fas fa-arrow-left"></i> Back
                </button>
                <h3 class="mb-4">Enter Code Manually</h3>
                <form id="manual-code-form">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Enter your QR code number" id="code-input" required>
                    </div>
                    <button type="submit" class="btn btn-submit">Submit Code</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        let html5QrcodeScanner = null;

        // Show selected section
        function showSection(section) {
            document.getElementById('selection-section').style.display = 'none';
            if (section === 'scanner') {
                document.getElementById('scanner-section').classList.add('active');
                initializeScanner();
            } else {
                document.getElementById('manual-section').classList.add('active');
            }
        }

        // Show selection buttons
        function showSelection() {
            document.getElementById('selection-section').style.display = 'block';
            document.getElementById('scanner-section').classList.remove('active');
            document.getElementById('manual-section').classList.remove('active');
            if (html5QrcodeScanner) {
                html5QrcodeScanner.clear();
                html5QrcodeScanner = null;
            }
        }

        // Initialize QR Scanner
        function initializeScanner() {
            if (!html5QrcodeScanner) {
                html5QrcodeScanner = new Html5QrcodeScanner(
                    "qr-reader",
                    { 
                        fps: 10, 
                        qrbox: { width: 250, height: 250 },
                        aspectRatio: 1.0
                    }
                );
                html5QrcodeScanner.render(onScanSuccess, onScanError);
            }
        }

        // Handle successful scan
        function onScanSuccess(decodedText, decodedResult) {
            const statusDiv = document.getElementById('scan-status');
            statusDiv.textContent = `Code scanned successfully: ${decodedText}`;
            statusDiv.className = 'scan-status success';
            
            // Stop scanning after successful scan
            if (html5QrcodeScanner) {
                html5QrcodeScanner.clear();
                html5QrcodeScanner = null;
            }

            // You can add your logic here to handle the scanned code
            // For example, redirect to another page or submit to server
            setTimeout(() => {
                // Add your code handling logic here
                console.log('Processing code:', decodedText);
            }, 1500);
        }

        // Handle scan error
        function onScanError(errorMessage) {
            const statusDiv = document.getElementById('scan-status');
            statusDiv.textContent = 'Error scanning code. Please try again.';
            statusDiv.className = 'scan-status error';
        }

        // Handle manual form submission
        document.getElementById('manual-code-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const code = document.getElementById('code-input').value;
            if (code.trim()) {
                // Add your code handling logic here
                console.log('Processing manual code:', code);
            }
        });
    </script>
</body>
</html>
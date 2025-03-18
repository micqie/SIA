<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Access - EZ Leather Bar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #F8E2A8, #9D4D36);
            min-height: 100vh;
            font-family: 'Arial', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            max-width: 500px;
            padding: 20px;
        }

        .access-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
        }

        .logo-container {
            margin-bottom: 30px;
        }

        .logo-container img {
            height: 80px;
            margin-bottom: 15px;
        }

        h1 {
            color: #9D4D36;
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .form-control {
            height: 50px;
            font-size: 1.1rem;
            border-radius: 25px;
            padding: 10px 20px;
            border: 2px solid #ddd;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #9D4D36;
            box-shadow: 0 0 0 0.2rem rgba(157, 77, 54, 0.25);
        }

        .btn-brown {
            background-color: #9D4D36;
            color: white;
            border-radius: 25px;
            padding: 12px 30px;
            font-size: 1.1rem;
            transition: all 0.3s;
        }

        .btn-brown:hover {
            background-color: #8B3D26;
            color: white;
            transform: translateY(-2px);
        }

        .back-link {
            margin-top: 20px;
            display: inline-block;
            color: #9D4D36;
            text-decoration: none;
            transition: all 0.3s;
        }

        .back-link:hover {
            color: #8B3D26;
            transform: translateX(-5px);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .access-card {
            animation: fadeIn 0.5s ease-out;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="access-card">
            <div class="logo-container">
                <img src="assets/logo2.jpg" alt="Logo">
                <h1>Guest Access</h1>
            </div>
            
            <form action="guest_dashboard.php" method="GET" class="mb-4">
                <div class="mb-4">
                    <input type="text" name="ref" class="form-control" 
                           placeholder="Enter your booking reference" required 
                           pattern="BK[0-9]+" 
                           title="Please enter a valid booking reference (starts with BK followed by numbers)">
                </div>
                <button type="submit" class="btn btn-brown w-100">
                    <i class="fas fa-arrow-right me-2"></i>Access Dashboard
                </button>
            </form>
            
            <a href="index.php" class="back-link">
                <i class="fas fa-chevron-left me-2"></i>Back to Home
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
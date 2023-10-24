<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Splash Page</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <style>
        /* Additional inline CSS for this page */
        body {
            background: linear-gradient(135deg, #2E8B57, #32CD32, #228B22);
            text-align: center;
            font-family: Arial, sans-serif;
            color: #fff;
        }

        .splash-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        h1 {
            font-size: 36px;
            margin-bottom: 20px;
        }

        .quote {
            font-size: 18px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="splash-content">
        <h1>Welcome, Farmer</h1>
        <p class="quote">"Farming is a profession of hope."</p>
    </div>
</body>
</html>
<script>
        // Set a timer to redirect to the dashboard after 5 seconds (5000 milliseconds)
        setTimeout(function() {
            window.location.href = "dashboard_farmer.php"; // Replace with your dashboard URL
        }, 5000); // Adjust the time in milliseconds as needed
    </script>



<?php
include('dbconnection.php');
?>
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
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .splash-content {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            max-width: 80%;
            text-align: center;
        }

        h1 {
            font-size: 36px;
            margin: 0 0 20px;
        }

        .quote {
            font-size: 18px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="splash-content">
       
        <h1>Welcome </h1><?php
        if (isset($_SESSION['useremail'])) {
            echo '' . $_SESSION['useremail'];
        }
        ?>
        <p class="quote" id="motivational-quote"></p>
    </div>

    <script>
        // List of possible motivational quotes for farmers
        const quotes = [
            "Farming is a profession of hope.",
            "The farmer is the only man in our economy who buys everything at retail, sells everything at wholesale, and pays the freight both ways.",
            "The ultimate goal of farming is not the growing of crops, but the cultivation and perfection of human beings.",
            "Farmers are the founders of human civilization.",
            "Agriculture is our wisest pursuit because it will, in the end, contribute most to real wealth, good morals, and happiness.",
            
            "Every day is a new day. It is better to be lucky. But I would rather be exact. Then when luck comes you are ready."
        ];

        // Function to generate a random quote
        function generateRandomQuote() {
            const randomIndex = Math.floor(Math.random() * quotes.length);
            return quotes[randomIndex];
        }

        // Display a random quote
        const quoteElement = document.getElementById("motivational-quote");
        quoteElement.textContent = `"${generateRandomQuote()}"`;

        // Redirect to the dashboard after 5 seconds (5000 milliseconds)
        setTimeout(function() {
            window.location.href = "dashboard_farmer.php"; // Replace with your dashboard URL
        }, 3000);
    </script>
</body>
</html>


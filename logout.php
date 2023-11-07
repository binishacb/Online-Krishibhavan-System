<!--
// Start or resume the session
session_start();

// Destroy the session
session_destroy();

// Redirect the user to the login page or another location
header("Location: login.php"); // Replace "login.php" with the desired page URL
exit();
?>
-->

<?php
	session_start();
		$_SESSION['logged_in'] = false;
	session_unset();
	session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: 'Balsamiq Sans', cursive;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        #banner {
            background-color: #007bff;
            
            color: #fff;
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .container {
            max-width: 400px;
            color: #333;
            background-color: #f5f5f5;
        }

        h2 {
            font-size: 36px;
            margin: 0;
            
        }

        p {
            font-size: 18px;
            margin: 20px 0;
        }

        .button.special {
            background-color: #0056b3;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 18px;
            display: inline-block;
            transition: background-color 0.3s;
        }

        .button.special:hover {
            background-color: #007bff;
        }
    </style>
</head>
<body>
    <section id="banner">
        <div class="container">
            <header class="major">
                <h2>Thanks for visiting !!!</h2>
                <center>
                    <p>You have been successfully logged out !!!</p>
                    <div class="6u 12u$(xsmall)">
                        <br />
                        <a href="index.php" class="button special">HOME PAGE</a>
                    </div>
                </center>
            </header>
        </div>
    </section>
</body>
</html>

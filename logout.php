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
<html><body>
    <section id="banner">
        <div class="container">
            <header class="major">
                <h2 style="font-family: 'Balsamiq Sans', cursive;">Thanks for visiting !!!</h2>
                <center>
                    <p style="font-family: 'Balsamiq Sans', cursive;">You have been succesfully logged out !!!</p>
                    <div class="6u 12u$(xsmall)">
                        <br />
                        <a href="index.php" class="button special">HOME PAGE</a>
                    </div>
                </center></body></html>
<!--
<!DOCTYPE html>
<html>
<head>
    <title>Agri-Bizz: LogOut</title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="../bootstrap\css\bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="../bootstrap\js\bootstrap.min.js"></script>
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <!--[if lte IE 8]><script src="css/ie/html5shiv.js"></script><![endif]
    <script src="../js/jquery.min.js"></script>
    <script src="../js/skel.min.js"></script>
    <script src="../js/skel-layers.min.js"></script>
    <script src="../js/init.js"></script>
    <link rel="stylesheet" href="../css/skel.css" />
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/style-xlarge.css" />
</head>
<body>
    <section id="banner">
        <div class="container">
            <header class="major">
                <h2 style="font-family: 'Balsamiq Sans', cursive;">Thanks for visiting !!!</h2>
                <center>
                    <p style="font-family: 'Balsamiq Sans', cursive;">You have been succesfully logged out !!!</p>
                    <div class="6u 12u$(xsmall)">
                        <br />
                        <a href="index.php" class="button special">HOME</a>
                    </div>
                </center>
            </header>
        </div>
        </div>
    </section>
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/jquery.scrolly.min.js"></script>
    <script src="../assets/js/jquery.scrollex.min.js"></script>
    <script src="../assets/js/skel.min.js"></script>
    <script src="../assets/js/util.js"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>
-->
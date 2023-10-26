<?php
// Start or resume the session
session_start();
include('dbconnection.php');
if (!isset($_SESSION['useremail'])) {
    header('Location: index.php'); // Redirect to index.php
    exit(); // Stop further execution of the current script
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    /* Custom styles for the vertical navbar */
    .vertical-navbar {
        width: 250px;
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        background-color: green;
        color: white;
        padding-top: 20px;
    }

    .vertical-navbar a {
        padding: 15px;
        text-align: left;
        text-decoration: none;
        color: white;
        display: block;
    }

    .vertical-navbar a:hover {
        background-color: #555;
    }

    /* Custom styles for the horizontal navbar */
    .horizontal-navbar {
        background-color: green;
    }

    .horizontal-navbar .navbar-nav {
        margin-left: auto;
    }

    .horizontal-navbar .navbar-nav .nav-item {
        margin-right: 10px;
    }

    .horizontal-navbar .navbar-nav .nav-item:last-child {
        margin-right: 0;
    }
    </style>
</head>

<body>
    <?php
    include('navbar/navbar_admin.php');
    ?>

    <div class="container mt-4">

        <center>
            <h1>Welcome ADMIN</h1>
        </center>

        <!-- <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <img src="images/a1.jpg" class="card-img-top" alt="Admin Component 1">
                    <div class="card-body">
                        <h5 class="card-title">Admin Component 1</h5>
                        <p class="card-text">Description of the first admin component.</p>
                        <a href="#" class="btn btn-success">Explore</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <img src="images/a2.jpg" class="card-img-top" alt="Admin Component 2">
                    <div class="card-body">
                        <h5 class="card-title">Admin Component 2</h5>
                        <p class="card-text">Description of the second admin component.</p>
                        <a href="#" class="btn btn-info">Explore</a>
                    </div>
                </div>
            </div> -->
        </div>

        <h3 class="mt-4">Admin Dashboard Overview</h3>
        <p>This section provides an overview of the admin dashboard's main features and functionalities.</p>

        <!-- Your additional content goes here -->
    </div>

    <br>
    <br>

    <!-- Include Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <br><br><br><br><br><br><br><br><br>
    <?php
        include('footer/footer.php')
        ?>
</body>

</html>
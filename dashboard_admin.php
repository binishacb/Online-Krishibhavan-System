<?php
// Start or resume the session
session_start();
include('dbconnection.php');
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
    <!-- Vertical Navbar -->
    <nav class="vertical-navbar"><br>
        
        <a href="viewfarmer.php">Farmers</a>
        <a href="officer_registration.php">Add officers</a>
        <a href="#">Officers</a>
        <a href="#">News</a>
        <a href="#">Crops</a>
        <a href="#">Govt. Schemes</a>
    </nav>

    <!-- Horizontal Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-dark horizontal-navbar">
       
        <form class="form-inline my-2 my-lg-0">
            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="logout.php" class="btn btn-danger" role="button">Logout</a>
            </li>
        </ul>
    </nav>

    <!-- Additional Content -->
    <div class="container mt-4">
       <center> <h1>Welcome ADMIN</h1></center>
        <!-- Your additional content goes here -->
    </div>


    <!-- Include Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
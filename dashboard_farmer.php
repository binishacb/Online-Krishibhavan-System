<!DOCTYPE html>
<?php
include('dbconnection.php');
session_start();

// Display the user's email on the dashboard
//$username = $_SESSION['email'];

?>
<html>
<head>
    <title>Farmer Dashboard</title>
    <style>
        /* Basic styling for the dashboard layout */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }
        
        #sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: green;
            padding-top: 40px;
            color: white;
        }
        
        #sidebar a {
            padding: 15px 25px;
            text-align: left;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
        }
        
        #sidebar a:hover {
            background-color: #555;
        }
        
        #content {
            margin-left: 260px;
            padding: 20px;
        }
        
        /* Style the motivational message */
        #motivation {
            font-size: 24px;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div id="sidebar">
    <a href="logout.php" class="btn btn-danger" role="button">Logout</a>
        <a href="#">Profile</a>
        <a href="#">Add Details</a>
        <a href="#">Govt Schemes</a>
        <a href="#">Crops</a>
    </div>

    <!-- Content -->
    <div id="content">
        <!-- Motivational Message -->
        <h1>Welcome </h1> <div id="motivation">
            <p>Dear Farmer, your hard work and dedication are the foundation of our agriculture. Keep nurturing the land, and it will reward you with abundance.</p>
        </div>
    </div>
</body>
</html>

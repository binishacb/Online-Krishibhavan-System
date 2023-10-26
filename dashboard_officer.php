<!DOCTYPE html>
<?php
include('dbconnection.php');
session_start();
if (!isset($_SESSION['useremail'])) {
    header('Location: index.php'); // Redirect to index.php
    exit(); // Stop further execution of the current script
}
?>
<html>

<head>
    <title>Officer Dashboard</title>
    <!-- Add your CSS and Bootstrap link here -->

    <!-- Link to Bootstrap CSS (you may have already linked it in your project) -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Add your custom CSS styles here -->
    <style>
    /* Add custom CSS styles for this page if needed */
    </style>
</head>

<body>
    <?php
    include('navbar/navbar_officer.php');
    ?>
    <br>
    <div class="container">
        <div class="card">
            <h5 class="card-header">Welcome, <?php
                if (isset($_SESSION['useremail'])) {
                    echo '' . $_SESSION['useremail'];
                }
                ?></h5>
            <div class="card-body">
                <h5 class="card-title">Find More</h5>
                <p class="card-text">Explore the Website</p>
                <a href="#" class="btn btn-primary">Explore</a>
            </div>
        </div>

        <!-- Additional Bootstrap elements for the officer's dashboard -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Tasks</h5>
                        <p class="card-text">Manage your tasks here.</p>
                        <a href="#" class="btn btn-success">View Tasks</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Reports</h5>
                        <p class="card-text">Access and generate reports.</p>
                        <a href="#" class="btn btn-info">View Reports</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <div class="alert alert-warning" role="alert">
                Important notice or announcement for officers.
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JavaScript files and your custom scripts here -->

</body>

</html>
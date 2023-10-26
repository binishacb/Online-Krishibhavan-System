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
    <title>Farmer Dashboard</title>
    <!-- Add your CSS and Bootstrap link here -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <?php
    include('navbar/navbar_farmer.php');
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
                <h5 class="card-title">Explore Agriculture</h5>
                <p class="card-text">Dear Farmer, your hard work and dedication are the foundation of our agriculture.
                    Keep nurturing the land, and it will reward you with abundance.</p>
                <a href="#" class="btn btn-primary">Explore</a>
            </div>
        </div>

        <!-- Additional Bootstrap elements and agricultural content -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <img src="images/b1.jpg" class="card-img-top" alt="Crops">
                    <div class="card-body">
                        <h5 class="card-title">Crop Information</h5>
                        <p class="card-text">Learn about different crops, their cultivation, and best practices.</p>
                        <a href="#" class="btn btn-success">Explore Crops</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <img src="images/b2.jpg" class="card-img-top" alt="Government Schemes">
                    <div class="card-body">
                        <h5 class="card-title">Government Schemes</h5>
                        <p class="card-text">Find out about government schemes and benefits for farmers.</p>
                        <a href="#" class="btn btn-info">View Schemes</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Agricultural tips or news -->
        <h3 class="mt-4">Agricultural Tips</h3>
        <div class="card">
            <img src="images/b3.jpg" class="card-img-top" alt="Agriculture Tips">
            <div class="card-body">
                <h5 class="card-title">Latest Agricultural Tips</h5>
                <p class="card-text">Stay updated with the latest agricultural tips and news.</p>
                <a href="#" class="btn btn-primary">Read More</a>
            </div>
        </div>
    </div>

</body>

</html>